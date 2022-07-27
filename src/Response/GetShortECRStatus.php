<?php
namespace Elplat\KktShtrih\Response;

use Elplat\KktShtrih\ECRMode;
use Elplat\KktShtrih\ECRAdvancedMode;

class GetShortECRStatus extends AbstractResponse
{
    /**
     * @var int Флаги
     * Бит 0 - Рулон журнала: true = Есть, false = Нет
     * Бит 1 - Рулон чековой ленты: true = Есть, false = Нет
     * Бит 2 - Верхний датчик ПД: true = Да, false = Нет
     * Бит 3 - Нижний датчик ПД: true = Да, false = Нет
     * Бит 4 - Положение десятичной точки: true = 2 знака, false = 0 знаков
     * Бит 5 - ЭКЛЗ: true = Есть, false = Нет
     * Бит 6 - Оптический датчик журнала: true = Бумага есть, false = Бумаги нет
     * Бит 7 - Оптический датчик чековой ленты: true = Бумага есть, false = Бумаги нет
     * Бит 8 - Рычаг термоголовки контрольной ленты: true = Опущен, false = Поднят
     * Бит 9 - Рычаг термоголовки чековой ленты: true = Опущен, false = Поднят
     * Бит 10 - Крышка корпуса ФР: true = Поднята, false = Опущена
     * Бит 11 - Денежный ящик: true = Открыть, false = Закрыт
     * Бит 12
     * Бит 13
     * Бит 14 - ЭКЛЗ почти заполнена: true = Да, false = Нет
     * Бит 15
     */
    public int $ecrFlags;

    public ?ECRMode $ecrMode;

    public ?ECRAdvancedMode $ecrAdvancedMode;

    /** @var int Количество операций в чеке */
    public int $quantityOfOperations;

    /** @var int Напряжение батареи */
    public int $batteryVoltageRaw;

    /** @var int Напряжение источника */
    public int $powerSourceVoltageRaw;

    /** @var float Напряжение батареи */
    public float $batteryVoltage;

    /** @var float Напряжение источника */
    public float $powerSourceVoltage;

    /** @var int Температура ТПГ */
    public int $temperature;

    /** @var int Предыдущий режим ККТ */
    public int $lastECRMode;

    public int $lastPrintResult;

    public static function fromString(string $m): self
    {
        $a = unpack('COperatorNumber/vECRFlags/CECRMode/CECRAdvancedMode/CQuantityOfOperationsLow/CBatteryVoltage/CPowerSourceVoltage/CFMResultCode/CEKLZResultCode/CQuantityOfOperationsHigh/CTemperature/CLastECRMode/CLastPrintResult', $m);

        $r = new self();
        $r->operatorNumber = $a['OperatorNumber'];
        $r->ecrFlags = $a['ECRFlags'];
        $r->ecrMode = ECRMode::from($a['ECRMode'] & 0x0F);
        $r->ecrAdvancedMode = ECRAdvancedMode::from($a['ECRAdvancedMode']);
        $r->quantityOfOperations = ($a['QuantityOfOperationsHigh'] << 8) | $a['QuantityOfOperationsLow'];
        $r->batteryVoltageRaw = $a['BatteryVoltage'];
        $r->powerSourceVoltageRaw = $a['PowerSourceVoltage'];
        $r->batteryVoltage = round((float)$r->batteryVoltageRaw / 51, 2);
        $r->powerSourceVoltage = round((float)$r->powerSourceVoltageRaw / 9, 2);
        $r->temperature = $a['Temperature'];
        $r->lastECRMode = $a['LastECRMode'];
        $r->lastPrintResult = $a['LastPrintResult'];
        return $r;
    }
}
