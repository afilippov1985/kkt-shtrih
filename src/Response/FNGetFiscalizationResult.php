<?php
namespace Elplat\KktShtrih\Response;

class FNGetFiscalizationResult extends AbstractResponse
{
    /** @var string Дата и время документа */
    public string $dateTime;

    public string $inn;

    public string $kktRegistrationNumber;

    public int $taxType;

    public int $workMode;

    /** @var int Номер фискального документа */
    public int $documentNumber;

    /** @var int Фискальный признак */
    public int $fiscalSign;

    public static function fromString(string $m): self
    {
        $a = unpack('CDateYear/CDateMonth/CDateDay/CTimeHours/CTimeMinutes/Z12INN/Z20KKTRegistrationNumber/CTaxType/CWorkMode/VDocumentNumber/VFiscalSign', $m);

        $r = new self();
        $r->dateTime = self::formatDateTime($a['DateYear'], $a['DateMonth'], $a['DateDay'], $a['TimeHours'], $a['TimeMinutes']);
        $r->inn = rtrim($a['INN']);
        $r->kktRegistrationNumber = rtrim($a['KKTRegistrationNumber']);
        $r->taxType = $a['TaxType'];
        $r->workMode = $a['WorkMode'];
        $r->documentNumber = $a['DocumentNumber'];
        $r->fiscalSign = $a['FiscalSign'];
        return $r;
    }
}
