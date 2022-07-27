<?php
namespace Elplat\KktShtrih\Response;

class FNGetCurrentSessionParams extends AbstractResponse
{
    /** @var int Состояние смены */
    public int $fnSessionState;

    /** @var int Номер смены */
    public int $sessionNumber;

    /** @var int Номер чека */
    public int $receiptNumber;

    public static function fromString(string $m): self
    {
        $a = unpack('CFNSessionState/vSessionNumber/vReceiptNumber', $m);

        $r = new self();
        $r->fnSessionState = $a['FNSessionState'];
        $r->sessionNumber = $a['SessionNumber'];
        $r->receiptNumber = $a['ReceiptNumber'];
        return $r;
    }
}
