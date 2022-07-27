<?php
namespace Elplat\KktShtrih\Response;

use Elplat\KktShtrih\Currency;

class FNCloseCheckEx extends AbstractResponse
{
    public float $change;

    public int $documentNumber;

    public int $fiscalSign;

    public static function fromString(string $m): self
    {
        $a = unpack('a5Change/VDocumentNumber/VFiscalSign', $m);

        $r = new self();
        $r->change = Currency::fromBinary($a['Change']);
        $r->documentNumber = $a['DocumentNumber'];
        $r->fiscalSign = $a['FiscalSign'];
        return $r;
    }
}
