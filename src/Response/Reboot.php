<?php
namespace Elplat\KktShtrih\Response;

class Reboot extends AbstractResponse
{
    public static function fromString(string $m): self
    {
        return new self();
    }
}
