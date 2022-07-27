<?php
namespace Elplat\KktShtrih\Request;

class FNGetCurrentSessionParams extends AbstractRequest
{
    public function __construct()
    {
        $this->command = 0xFF40;
    }

    public function __toString(): string
    {
        return pack('nV', $this->command, $this->password);
    }
}
