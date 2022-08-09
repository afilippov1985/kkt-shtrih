<?php
namespace Elplat\KktShtrih\Request;

class PrintReportWithCleaning extends AbstractRequest
{
    public function __construct()
    {
        $this->command = 0x41;
        $this->responseTimeout = 60000;
    }

    public function __toString(): string
    {
        return pack('CV', $this->command, $this->password);
    }
}
