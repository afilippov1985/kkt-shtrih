<?php
namespace Elplat\KktShtrih\Response;

class Beep extends AbstractResponse
{
    public static function fromString(string $m): self
    {
        $a = unpack('COperatorNumber', $m);

        $r = new self();
        $r->operatorNumber = $a['OperatorNumber'];
        return $r;
    }
}
