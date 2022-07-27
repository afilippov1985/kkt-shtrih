<?php
namespace Elplat\KktShtrih\Request;

class ContinuePrint extends AbstractRequest
{
    public function __construct()
    {
        $this->command = 0xB0;
    }

    public function __toString(): string
    {
        return pack('CV', $this->command, $this->password);
    }
}
