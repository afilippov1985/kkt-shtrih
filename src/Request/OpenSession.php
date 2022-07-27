<?php
namespace Elplat\KktShtrih\Request;

class OpenSession extends AbstractRequest
{
    public function __construct()
    {
        $this->command = 0xE0;
    }

    public function __toString(): string
    {
        return pack('CV', $this->command, $this->password);
    }
}
