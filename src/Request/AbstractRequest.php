<?php
namespace Elplat\KktShtrih\Request;

abstract class AbstractRequest implements \Stringable
{
    public int $responseTimeout = 5000;

    public int $command;

    public int $password = 0;

    protected static function to1251(string $str): string
    {
        return iconv(mb_internal_encoding(), 'CP1251', $str);
    }
}
