<?php
namespace Elplat\KktShtrih\Request;

use Elplat\KktShtrih\TLV;

class FNSendTLV extends AbstractRequest
{
    public TLV $tlv;

    public function __construct()
    {
        $this->command = 0xFF0C;
    }

    public function __toString(): string
    {
        return pack(
            'nVa*',
            $this->command,
            $this->password,
            (string)$this->tlv,
        );
    }
}
