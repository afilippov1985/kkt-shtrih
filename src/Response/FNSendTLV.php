<?php
namespace Elplat\KktShtrih\Response;

class FNSendTLV extends AbstractResponse
{
    public static function fromString(string $m): self
    {
        return new self();
    }
}
