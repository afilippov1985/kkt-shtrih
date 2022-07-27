<?php
namespace Elplat\KktShtrih\Request;

class GetShortECRStatus extends AbstractRequest
{
    public function __construct()
    {
        $this->command = 0x10;
    }

    public function __toString(): string
    {
        return pack('CV', $this->command, $this->password);
    }
}
