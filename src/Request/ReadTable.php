<?php
namespace Elplat\KktShtrih\Request;

class ReadTable extends AbstractRequest
{
    /** @var int Номер таблицы */
    public int $tableNumber;

    /** @var int Номер ряда */
    public int $rowNumber = 1;

    /** @var int Номер поля */
    public int $fieldNumber;

    public function __construct()
    {
        $this->command = 0x1F;
    }

    public function __toString(): string
    {
        return pack(
            'CVCvC',
            $this->command,
            $this->password,
            $this->tableNumber,
            $this->rowNumber,
            $this->fieldNumber,
        );
    }
}
