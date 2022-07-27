<?php

use Elplat\KktShtrih\Transport\{StreamException, Transport8};
use PHPUnit\Framework\TestCase;

class Transport8Test extends TestCase
{
    public function testSendTcp()
    {
        $this->expectException(StreamException::class);

        $t = new Transport8('tcp://127.0.0.1:7778');
        $t->sendMessage('', 1);
    }

    public function testSendFile()
    {
        $this->expectException(StreamException::class);

        $t = new Transport8('\\\\.\\COM99');
        $t->sendMessage('', 1);
    }
}
