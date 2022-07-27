<?php
namespace Elplat\KktShtrih\Request;

class PrintReportWithCleaning extends AbstractRequest
{
    public function __construct()
    {
        $this->command = 0x41;
    }

    public function __toString(): string
    {
        return pack('CV', $this->command, $this->password);
    }
}
