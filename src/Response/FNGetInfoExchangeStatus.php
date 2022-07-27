<?php
namespace Elplat\KktShtrih\Response;

class FNGetInfoExchangeStatus extends AbstractResponse
{
    public int $infoExchangeStatus;

    public int $messageState;

    public int $messageCount;

    public int $documentNumber;

    public string $dateTime;

    public static function fromString(string $m): self
    {
        $a = unpack('CInfoExchangeStatus/CMessageState/vMessageCount/VDocumentNumber/CDateYear/CDateMonth/CDateDay/CTimeHours/CTimeMinutes', $m);

        $r = new self();
        $r->infoExchangeStatus = $a['InfoExchangeStatus'];
        $r->messageState = $a['MessageState'];
        $r->messageCount = $a['MessageCount'];
        $r->documentNumber = $a['DocumentNumber'];
        $r->dateTime = self::formatDateTime($a['DateYear'], $a['DateMonth'], $a['DateDay'], $a['TimeHours'], $a['TimeMinutes']);
        return $r;
    }
}
