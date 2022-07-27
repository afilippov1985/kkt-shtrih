<?php
namespace Elplat\KktShtrih;

require __DIR__ . '/../vendor/autoload.php';

class EchoLogger implements \Psr\Log\LoggerInterface
{
    use \Psr\Log\LoggerTrait;

    public function log($level, string|\Stringable $message, array $context = []): void
    {
        echo $message, "\n";
    }
}

class KktShtrihMock extends KktShtrih
{
    protected function beforeSendMessage(Request\AbstractRequest $request): ?string
    {
        $responses = [
            0x41 => '41 00 1E',
            0x80 => '80 00 1E',
            0x81 => '81 00 1E',
            0x82 => '82 00 1E',
            0x83 => '83 00 1E',
            0x85 => '85 00 1E 00 00 00 00 00',
            0xE0 => 'E0 00 1E',
            0xFF01 => 'FF 01 00 03 00 00 01 00 12 06 15 06 25 39 32 38 39 30 30 30 31 30 30 30 35 35 36 33 32 F9 B2 00 00',
            0xFF0A => 'FF 0A 00 03 00 12 06 15 06 25 F9 B2 00 00 79 58 50 F5 01 F0 49 02 00 00',
            0xFF0B => 'FF 0B 00 01 34 00 00 00 01 00 00 00 01',
            0xFF0C => 'FF 0C 00',
            0xFF40 => 'FF 40 00 01 34 01 05 00',
            0xFF45 => 'FF 45 00 00 00 00 00 00 B3 60 03 00 DB 45 DB 70',
            0xFF46 => 'FF 46 00',
            0xFF4D => 'FF 4D 00',
        ];

        $m = $responses[$request->command] ?? null;

        if ($m !== null) {
            $m = str_replace(' ', '', $m);
            $m = hex2bin($m);
            $this->logMessage('Mock Response: ', $m);
        }

        return $m;
    }
}

$connectionString = 'tcp://192.168.1.101:7778';
$connectionString = '\\\\.\\COM14';
$t = new Transport\Transport8($connectionString);
// $t->setLogger(new EchoLogger());
$kkt = new KktShtrihMock($t);
$kkt->setLogger(new EchoLogger());

// $request = new Request\GetECRStatus();
$request = new Request\PrintReportWithCleaning();
$response = $kkt->sendRequest($request);
var_dump($response);
