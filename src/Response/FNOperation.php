<?php
namespace Elplat\KktShtrih\Response;

class FNOperation extends AbstractResponse
{
    public static function fromString(string $m): self
    {
        return new self();
    }
}
