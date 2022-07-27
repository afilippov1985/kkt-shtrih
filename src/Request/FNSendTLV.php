<?php
namespace Elplat\KktShtrih\Request;

class FNSendTLV extends AbstractRequest
{
    public int $tag;

    public string $value;

    public function __construct()
    {
        $this->command = 0xFF0C;
    }

    public function __toString(): string
    {
        return pack(
            'nVvva*',
            $this->command,
            $this->password,
            $this->tag,
            strlen($this->value),
            $this->value,
        );
    }
}
