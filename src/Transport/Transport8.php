<?php
namespace Elplat\KktShtrih\Transport;

use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;

class Transport8 implements TransportInterface, LoggerAwareInterface
{
    use LoggerAwareTrait;

    public const ERROR_CONNECT = 301;

    public const ERROR_WRITE = 302;

    public const ERROR_READ = 303;

    private const STX = "\x02";

    private const ENQ = "\x05";

    private const ACK = "\x06";

    private const NAK = "\x15";

    private bool $connected = false;
    private bool $readAfterAck;
    private mixed $stream;

    public function __construct(private readonly string $connectionString)
    {
    }

    public function __destruct()
    {
        $this->disconnect();
    }

    private function connect(): void
    {
        if (!$this->connected) {
            $errno = 0;
            $errstr = '';

            if (str_starts_with($this->connectionString, 'tcp://')) {
                $this->readAfterAck = true;
                $this->stream = @stream_socket_client(
                    $this->connectionString,
                    $errno,
                    $errstr,
                    5,
                    STREAM_CLIENT_CONNECT,
                    stream_context_create(['socket' => ['tcp_nodelay' => true]]),
                );
            } else {
                $this->readAfterAck = false;
                $this->stream = @fopen($this->connectionString, 'r+b');
                if ($this->stream) {
                    stream_set_read_buffer($this->stream, 0);
                }
            }

            if (!$this->stream) {
                throw new StreamException("Не удалось открыть поток '{$this->connectionString}'. {$errstr}", self::ERROR_CONNECT);
            }

            $this->connected = true;
        }
    }

    private function disconnect(): void
    {
        if ($this->connected) {
            fclose($this->stream);
            $this->connected = false;
        }
    }

    private function setTimeout(int $sec, int $usec = 0): void
    {
        if (!stream_set_timeout($this->stream, $sec, $usec)) {
            $this->logError("Can't set timeout {$sec} {$usec}");
        }
    }

    private function computeCrc(string $data): string
    {
        $bytes = unpack('C*', $data);
        $crc = count($bytes);
        foreach ($bytes as $b) {
            $crc ^= $b;
        }
        return chr($crc);
    }

    protected function logRead(string $data): void
    {
        $this->logger?->info('>>Readed: ' . bin2hex($data));
    }

    protected function logWrite(string $data): void
    {
        $this->logger?->info('<<Write : ' . bin2hex($data));
    }

    protected function logError(string $str): void
    {
        $this->logger?->error('!!' . $str);
    }

    private function read(int $len): string
    {
        $data = '';
        do {
            $chunk = fread($this->stream, $len - strlen($data));
            if ($chunk === false) {
                throw new StreamException('Read error', self::ERROR_READ);
            } elseif ($chunk === '') {
                throw new StreamTimeoutException('Read timeout', self::ERROR_READ);
            }
            $this->logRead($chunk);
            $data .= $chunk;
        } while (strlen($data) !== $len);
        return $data;
    }

    private function write(string $data): void
    {
        $this->logWrite($data);
        if (fwrite($this->stream, $data) === false) {
            throw new StreamException('Write error', self::ERROR_WRITE);
        }
    }

    /**
     * @return ?string
     * @throws StreamException
     */
    private function receiveMessage(): ?string
    {
        try {
            while ($this->read(1) !== self::STX) {
                //
            }

            $this->setTimeout(1); // default timeout
            $len = ord($this->read(1));
            $data = $this->read($len + 1);
            $message = substr($data, 0, -1);
            $crc = substr($data, -1);

            if ($this->computeCrc($message) !== $crc) {
                $this->write(self::NAK);
                return null;
            }

            $this->write(self::ACK);

            if ($this->readAfterAck) {
                try {
                    $this->setTimeout(0, 10000);
                    $this->read(1); // Для TCP подключения касса отправляет FF после получения ACK, что не описано в протоколе
                } catch (StreamException $ex) {
                    $this->logError($ex->getMessage());
                }
            }

            return $message;
        } catch (StreamTimeoutException $ex) {
            $this->logError($ex->getMessage());
        }

        return null;
    }

    /**
     * @param string $message
     * @param int $responseTimeout
     * @return string
     * @throws Exception
     */
    public function sendMessage(string $message, int $responseTimeout): string
    {
        $maxCount = 3;
        $enqRepeatCount = 0;
        $txRepeatCount = 0;
        $rxRepeatCount = 0;
        $commandSent = false;

        $this->connect();

        do {
            try {
                $this->setTimeout(1); // default timeout
                $this->write(self::ENQ);

                $r = $this->read(1);
                if ($r === self::NAK) {
                    $this->write(self::STX . chr(strlen($message)) . $message . $this->computeCrc($message));
                    $commandSent = true;

                    if ($this->read(1) === self::ACK) {
                        $this->setTimeout($responseTimeout / 1000);
                        $response = $this->receiveMessage();
                        if ($response !== null) {
                            return $response;
                        }
                        ++$rxRepeatCount;
                        $this->logError("rxRepeatCount1 {$rxRepeatCount}");
                    } else {
                        ++$txRepeatCount;
                        $this->logError("txRepeatCount {$txRepeatCount}");
                    }
                } elseif ($r === self::ACK) {
                    $this->logError('Read resended message');
                    $response = $this->receiveMessage();
                    if ($response !== null && $commandSent) {
                        return $response;
                    }
                    ++$rxRepeatCount;
                    $this->logError("rxRepeatCount2 {$rxRepeatCount}");
                } else {
                    ++$enqRepeatCount;
                    $this->logError("enqRepeatCount {$enqRepeatCount}");
                }
            } catch (StreamException $ex) {
                ++$enqRepeatCount;
                $this->logError($ex->getMessage());
            }
        } while ($enqRepeatCount < $maxCount && $txRepeatCount < $maxCount && $rxRepeatCount < $maxCount);

        $this->disconnect();
        throw new Exception('Can\'t send message');
    }

}
