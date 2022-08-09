<?php
namespace Elplat\KktShtrih\Response;

class Unknown extends AbstractResponse
{
    public string $messageRaw;

    public static function fromString(string $m): self
    {
        $r = new self();
        $r->messageRaw = $m;
        return $r;
    }
}
