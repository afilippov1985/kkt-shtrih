<?php
namespace Elplat\KktShtrih\Response;

class Unknown extends AbstractResponse
{
    public static function fromString(string $m): self
    {
        return new self();
    }
}
