<?php
namespace Elplat\KktShtrih\Request;

class FNGetFiscalizationResult extends AbstractRequest
{
    public function __construct()
    {
        $this->command = 0xFF09;
    }

    public function __toString(): string
    {
        return pack('nV', $this->command, $this->password);
    }
}
