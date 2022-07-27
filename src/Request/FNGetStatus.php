<?php
namespace Elplat\KktShtrih\Request;

class FNGetStatus extends AbstractRequest
{
    public function __construct()
    {
        $this->command = 0xFF01;
    }

    public function __toString(): string
    {
        return pack('nV', $this->command, $this->password);
    }
}
