<?php
namespace Elplat\KktShtrih\Response;

class FNSendTLVOperation extends AbstractResponse
{
    public static function fromString(string $m): self
    {
        return new self();
    }
}
