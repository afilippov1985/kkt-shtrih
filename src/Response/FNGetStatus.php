<?php
namespace Elplat\KktShtrih\Response;

class FNGetStatus extends AbstractResponse
{
    /**
     * @var int Состояние фазы жизни
     * Бит 0 - Проведена настройка ФН
     * Бит 1 - Открыт фискальный режим
     * Бит 2 - Закрыт фискальный режим
     * Бит 3 - Закончена передача ФД в ОФД
     */
    public int $fnLifeState;

    /** @var int Текущий документ */
    public int $fnCurrentDocument;

    /** @var int Данные документа */
    public int $fnDocumentData;

    /** @var int Состояние смены */
    public int $fnSessionState;

    /**
     * @var int Флаги предупреждения
     * Бит 0 - Срочная замена криптографического сопроцессора (до окончания срока действия 3 дня)
     * Бит 1 - Исчерпание ресурса криптографического сопроцессора (до окончания срока действия 30 дней)
     * Бит 2 - Переполнение памяти ФН (Архив ФН заполнен на 90%)
     * Бит 3 - Превышено время ожидания ответа ОФД
     */
    public int $fnWarningFlags;

    /** @var string Дата и время ФН */
    public string $dateTime;

    /** @var string Заводской номер ФН */
    public string $fnSerialNumber;

    /** @var int Номер фискального документа */
    public int $documentNumber;

    public static function fromString(string $m): self
    {
        $a = unpack('CFNLifeState/CFNCurrentDocument/CFNDocumentData/CFNSessionState/CFNWarningFlags/CDateYear/CDateMonth/CDateDay/CTimeHours/CTimeMinutes/Z16FNSerialNumber/VDocumentNumber', $m);

        $r = new self();
        $r->fnLifeState = $a['FNLifeState'];
        $r->fnCurrentDocument = $a['FNCurrentDocument'];
        $r->fnDocumentData = $a['FNDocumentData'];
        $r->fnSessionState = $a['FNSessionState'];
        $r->fnWarningFlags = $a['FNLifeState'];
        $r->dateTime = self::formatDateTime($a['DateYear'], $a['DateMonth'], $a['DateDay'], $a['TimeHours'], $a['TimeMinutes']);
        $r->fnSerialNumber = $a['FNSerialNumber'];
        $r->documentNumber = $a['DocumentNumber'];
        return $r;
    }
}
