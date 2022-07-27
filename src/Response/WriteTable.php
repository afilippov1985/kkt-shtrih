<?php
namespace Elplat\KktShtrih\Response;

class WriteTable extends AbstractResponse
{
    public static function fromString(string $m): self
    {
        return new self();
    }
}
