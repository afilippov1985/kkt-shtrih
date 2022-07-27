<?php
namespace Elplat\KktShtrih\Response;

class CancelCheck extends AbstractResponse
{
    public static function fromString(string $m): self
    {
        $a = unpack('COperatorNumber', $m);

        $r = new self();
        $r->operatorNumber = $a['OperatorNumber'];
        return $r;
    }
}
