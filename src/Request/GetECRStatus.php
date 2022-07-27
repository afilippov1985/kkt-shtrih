<?php
namespace Elplat\KktShtrih\Request;

class GetECRStatus extends AbstractRequest
{
    public function __construct()
    {
        $this->command = 0x11;
    }

    public function __toString(): string
    {
        return pack('CV', $this->command, $this->password);
    }
}
