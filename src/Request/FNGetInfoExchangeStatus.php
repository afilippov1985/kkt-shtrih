<?php
namespace Elplat\KktShtrih\Request;

class FNGetInfoExchangeStatus extends AbstractRequest
{
    public function __construct()
    {
        $this->command = 0xFF39;
    }

    public function __toString(): string
    {
        return pack('nV', $this->command, $this->password);
    }
}
