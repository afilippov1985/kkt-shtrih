<?php
namespace Elplat\KktShtrih\Request;

class CancelCheck extends AbstractRequest
{
    public function __construct()
    {
        $this->command = 0x88;
    }

    public function __toString(): string
    {
        return pack('CV', $this->command, $this->password);
    }
}
