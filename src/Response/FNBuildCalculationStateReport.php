<?php
namespace Elplat\KktShtrih\Response;

class FNBuildCalculationStateReport extends AbstractResponse
{
    /** @var int Номер фискального документа */
    public int $documentNumber;

    /** @var int Фискальный признак */
    public int $fiscalSign;

    /** @var int Количество неподтверждённых документов  */
    public int $documentCount;

    /** @var string Дата первого неподтверждённого документа */
    public string $date;


    public static function fromString(string $m): self
    {
        $a = unpack('VDocumentNumber/VFiscalSign/VDocumentCount/CDateYear/CDateMonth/CDateDay', $m);

        $r = new self();
        $r->documentNumber = $a['DocumentNumber'];
        $r->fiscalSign = $a['FiscalSign'];
        $r->documentCount = $a['DocumentCount'];
        $r->date = self::formatDate($a['DateYear'], $a['DateMonth'], $a['DateDay']);
        return $r;
    }
}
