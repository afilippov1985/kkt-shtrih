<?php
namespace Elplat\KktShtrih\Request;

use Elplat\KktShtrih\Response\GetFieldStruct;

class WriteTable extends AbstractRequest
{
    /** @var int Номер таблицы */
    public int $tableNumber;

    /** @var int Номер ряда */
    public int $rowNumber = 1;

    /** @var int Номер поля */
    public int $fieldNumber;

    /** @var string|int Значение поля */
    public string|int $valueOfField;

    /** @var string Значение поля */
    public string $valueOfFieldRaw;

    public function __construct()
    {
        $this->command = 0x1E;
    }

    public function __toString(): string
    {
        return pack(
            'CVCvCa*',
            $this->command,
            $this->password,
            $this->tableNumber,
            $this->rowNumber,
            $this->fieldNumber,
            $this->valueOfFieldRaw,
        );
    }

    public function translateRawValue(GetFieldStruct $fieldStruct)
    {
        if ($fieldStruct->fieldType) {
            $this->valueOfFieldRaw = pack('a40', substr(self::to1251($this->valueOfField), 0, 40));
        } else {
            $t = $fieldStruct->getPackFormat();
            $this->valueOfFieldRaw = pack($t, $this->valueOfField);
        }
    }
}
