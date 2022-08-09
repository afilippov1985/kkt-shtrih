<?php
namespace Elplat\KktShtrih\Request;

abstract class AbstractRequest implements \Stringable
{
    /**
     * @var int Таймаут ответа в милисекундах
     */
    protected int $responseTimeout = 2000;

    protected int $command;

    public int $password = 0;

    protected static function to1251(string $str): string
    {
        return iconv(mb_internal_encoding(), 'CP1251', $str);
    }

    public function getCommand(): int
    {
        return $this->command;
    }

    public function getResponseTimeout(): int
    {
        return $this->responseTimeout;
    }
}
