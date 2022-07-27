<?php
namespace Elplat\KktShtrih\Request;

class Reboot extends AbstractRequest
{
    public function __construct()
    {
        $this->command = 0xFEF3;
    }

    public function __toString(): string
    {
        return pack('nV', $this->command, 0);
    }
}
