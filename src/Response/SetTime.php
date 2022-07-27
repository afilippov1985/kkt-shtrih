<?php
namespace Elplat\KktShtrih\Response;

class SetTime extends AbstractResponse
{
    public static function fromString(string $m): self
    {
        return new self();
    }
}
