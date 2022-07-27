<?php
namespace Elplat\KktShtrih\Response;

use Elplat\KktShtrih\Currency;

class FNFindDocument extends AbstractResponse
{
    /**
     * @var int Тип фискального документа
     * 1 - Отчет о регистрации
     * 2 - Отчет об открытии смены
     * 3 - Кассовый чек
     * 4 - Бланк строгой отчетности
     * 31 - Кассовый чек коррекции
     * 5 - Отчет о закрытии смены
     * 6 - Отчет о закрытии фискального накопителя
     * 11 - Отчет об изменении параметров регистрации
     * 21 - Отчет о состоянии расчетов
     */
    public int $documentType;

    /** @var int Получена ли квитанция из ОФД */
    public int $ofdTicketReceived;

    /** @var string Дата и время документа */
    public string $dateTime;

    /** @var int Номер фискального документа */
    public int $documentNumber;

    /** @var int Фискальный признак */
    public int $fiscalSign;

    public ?string $inn = null;

    public ?string $kktRegistrationNumber = null;

    public ?int $taxType = null;

    public ?int $workMode = null;

    public ?int $registrationReasonCode = null;

    public ?int $sessionNumber = null;

    public ?int $operationType = null;

    public ?float $summ1 = null;

    public static function fromString(string $m): self
    {
        $a = unpack('CDocumentType/COFDTicketReceived/CDateYear/CDateMonth/CDateDay/CTimeHours/CTimeMinutes/VDocumentNumber/VFiscalSign', $m);

        $r = new self();
        $r->documentType = $a['DocumentType'];
        $r->ofdTicketReceived = $a['OFDTicketReceived'];
        $r->dateTime = self::formatDateTime($a['DateYear'], $a['DateMonth'], $a['DateDay'], $a['TimeHours'], $a['TimeMinutes']);
        $r->documentNumber = $a['DocumentNumber'];
        $r->fiscalSign = $a['FiscalSign'];

        $m2 = substr($m, 15);
        switch ($r->documentType) {
            case 6: // Отчет о закрытии фискального накопителя
                $a = unpack('A12INN/A20KKTRegistrationNumber', $m2);
                $r->inn = $a['INN'];
                $r->kktRegistrationNumber = $a['KKTRegistrationNumber'];
                break;

            case 1: // Отчет о регистрации
                $a = unpack('A12INN/A20KKTRegistrationNumber/CTaxType/CWorkMode', $m2);
                $r->inn = $a['INN'];
                $r->kktRegistrationNumber = $a['KKTRegistrationNumber'];
                $r->taxType = $a['TaxType'];
                $r->workMode = $a['WorkMode'];
                break;

            case 11: // Отчет об изменении параметров регистрации
                $a = unpack('A12INN/A20KKTRegistrationNumber/CTaxType/CWorkMode/CRegistrationReasonCode', $m2);
                $r->inn = $a['INN'];
                $r->kktRegistrationNumber = $a['KKTRegistrationNumber'];
                $r->taxType = $a['TaxType'];
                $r->workMode = $a['WorkMode'];
                $r->registrationReasonCode = $a['RegistrationReasonCode'];
                break;

            case 2: // Отчет об открытии смены
            case 5: // Отчет о закрытии смены
                $a = unpack('vSessionNumber', $m2);
                $r->sessionNumber = $a['SessionNumber'];
                break;

            case 3: // Кассовый чек
            case 4:
            case 31:
                $a = unpack('COperationType/a5Summ1', $m2);
                $r->operationType = $a['OperationType'];
                $r->summ1 = Currency::fromBinary($a['Summ1']);
                break;

        }

        return $r;
    }
}
