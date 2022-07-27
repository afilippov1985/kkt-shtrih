<?php
namespace Elplat\KktShtrih\Response;

class FNOpenSession extends AbstractResponse
{
    /** @var int Номер смены */
    public int $sessionNumber;

    /** @var int Номер фискального документа */
    public int $documentNumber;

    /** @var int Фискальный признак */
    public int $fiscalSign;

    public static function fromString(string $m): self
    {
        $a = unpack('vSessionNumber/VDocumentNumber/VFiscalSign', $m);

        $r = new self();
        $r->sessionNumber = $a['SessionNumber'];
        $r->documentNumber = $a['DocumentNumber'];
        $r->fiscalSign = $a['FiscalSign'];
        return $r;
    }
}
