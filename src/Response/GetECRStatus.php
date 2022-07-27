<?php
namespace Elplat\KktShtrih\Response;

use Elplat\KktShtrih\ECRMode;
use Elplat\KktShtrih\ECRAdvancedMode;

class GetECRStatus extends AbstractResponse
{
    /** @var string Версия ПО */
    public string $ecrSoftVersion;

    /** @var int Сборка ПО */
    public int $ecrBuild;

    /** @var string Дата ПО */
    public string $ecrSoftDate;

    /** @var int Номер ККМ в зале */
    public int $logicalNumber;

    /** @var int Номер документа */
    public int $openDocumentNumber;

    /** @var int ФлагиKKT */
    public int $ecrFlags;

    public ?ECRMode $ecrMode;

    public ?ECRAdvancedMode $ecrAdvancedMode;

    /** @var int Номер порта */
    public int $portNumber;

    public int $fmSoftVersion;

    public int $fmBuild;

    public string $fmSoftDate;

    public string $dateTime;

    /**
     * @var int Флаги ФП
     * Бит 0 - Наличие ФП1: true = Есть, false = Нет
     * Бит 1 - Наличие ФП2: true = Есть, false = Нет
     * Бит 2 - Лицензия введена: true = Есть, false = Нет
     * Бит 3 - Переполнение ФП: true = Есть, false = Нет
     * Бит 4 - Батарея ФП разряжена: true = Есть, false = Нет
     * Бит 5 - Последняя запись в ФП повреждена: true = Да, false = Нет
     * Бит 6 - Смена в ФП: true = Открыта, false = Закрыта
     * Бит 7 - 24 часа в ФП кончились: true = Да, false = Нет
     */
    public int $fmFlags;

    /** @var int Заводской номер */
    public int $serialNumber;

    public int $freeRecordInFM;

    public int $registrationNumber;

    public int $freeRegistration;

    public string $inn;

    public static function fromString(string $m): self
    {
        $a = unpack('COperatorNumber/CECRSoftVersionMajor/CECRSoftVersionMinor/vECRBuild/CECRSoftDateDay/CECRSoftDateMonth/CECRSoftDateYear/CLogicalNumber/vOpenDocumentNumber/vECRFlags/CECRMode/CECRAdvancedMode/CPortNumber/vFMSoftVersion/vFMBuild/CFMSoftDateDay/CFMSoftDateMonth/CFMSoftDateYear/CDateDay/CDateMonth/CDateYear/CTimeHours/CTimeMinutes/CTimeSeconds/CFMFlags/VSerialNumber/vSessionNumber/vFreeRecordInFM/CRegistrationNumber/CFreeRegistration/VINNLow/vINNHigh', $m);

        $r = new self();
        $r->operatorNumber = $a['OperatorNumber'];
        $r->ecrSoftVersion = chr($a['ECRSoftVersionMajor']) . '.' . chr($a['ECRSoftVersionMinor']);
        $r->ecrBuild = $a['ECRBuild'];
        $r->ecrSoftDate = self::formatDate($a['ECRSoftDateYear'], $a['ECRSoftDateMonth'], $a['ECRSoftDateDay']);
        $r->logicalNumber = $a['LogicalNumber'];
        $r->openDocumentNumber = $a['OpenDocumentNumber'];
        $r->ecrFlags = $a['ECRFlags'];
        $r->ecrMode = ECRMode::from($a['ECRMode'] & 0x0F);
        $r->ecrAdvancedMode = ECRAdvancedMode::from($a['ECRAdvancedMode']);
        $r->portNumber = $a['PortNumber'];
        $r->fmSoftVersion = $a['FMSoftVersion'];
        $r->fmBuild = $a['FMBuild'];
        $r->fmSoftDate = self::formatDate($a['FMSoftDateYear'], $a['FMSoftDateMonth'], $a['FMSoftDateDay']);
        $r->dateTime = self::formatDateTime($a['DateYear'], $a['DateMonth'], $a['DateDay'], $a['TimeHours'], $a['TimeMinutes'], $a['TimeSeconds']);
        $r->fmFlags = $a['FMFlags'];
        $r->serialNumber = $a['SerialNumber'];
        $r->freeRecordInFM = $a['FreeRecordInFM'];
        $r->registrationNumber = $a['RegistrationNumber'];
        $r->freeRegistration = $a['FreeRegistration'];

        if (PHP_INT_SIZE === 8) {
            $inn = ($a['INNHigh'] << 32) | $a['INNLow'];
        } else {
            $inn = 4294967296.0 * $a['INNHigh'] + (float)sprintf('%u', $a['INNLow']);
        }
        $r->inn = str_pad((string)$inn, $inn < 10000000000 ? 10 : 12, '0', STR_PAD_LEFT);
        return $r;
    }
}
