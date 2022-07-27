<?php
namespace Elplat\KktShtrih\Request;

class Beep extends AbstractRequest
{
    public function __construct()
    {
        $this->command = 0x13;
    }

    public function __toString(): string
    {
        return pack('CV', $this->command, $this->password);
    }
}
