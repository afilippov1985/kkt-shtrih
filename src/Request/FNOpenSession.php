<?php
namespace Elplat\KktShtrih\Request;

class FNOpenSession extends AbstractRequest
{
    public function __construct()
    {
        $this->command = 0xFF0B;
        $this->responseTimeout = 15000;
    }

    public function __toString(): string
    {
        return pack('nV', $this->command, $this->password);
    }
}
