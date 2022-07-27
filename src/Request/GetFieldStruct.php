<?php
namespace Elplat\KktShtrih\Request;

class GetFieldStruct extends AbstractRequest
{
    /** @var int Номер таблицы */
    public int $tableNumber;

    /** @var int Номер поля */
    public int $fieldNumber;

    public function __construct()
    {
        $this->command = 0x2E;
    }

    public function __toString(): string
    {
        return pack('CVCC', $this->command, $this->password, $this->tableNumber, $this->fieldNumber);
    }
}
