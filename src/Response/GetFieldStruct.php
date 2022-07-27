<?php
namespace Elplat\KktShtrih\Response;

class GetFieldStruct extends AbstractResponse
{
    /** @var string Название поля */
    public string $fieldName;

    /** @var int Тип поля */
    public int $fieldType;

    /** @var int Размер поля */
    public int $fieldSize;

    /** @var ?int Минимальное значение поля */
    public ?int $minValueOfField = null;

    /** @var ?int Максимальное значение поля */
    public ?int $maxValueOfField = null;

    public static function fromString(string $m): self
    {
        $a = unpack('Z40FieldName/CFieldType/CFieldSize', $m);

        $r = new self();
        $r->fieldName = self::from1251($a['FieldName']);
        $r->fieldType = $a['FieldType'];
        $r->fieldSize = $a['FieldSize'];
        if (!$r->fieldType) {
            $t = $r->getPackFormat();
            $a = unpack("{$t}MINValueOfField/{$t}MAXValueOfField", substr($m, 42));
            $r->minValueOfField = $a['MINValueOfField'];
            $r->maxValueOfField = $a['MAXValueOfField'];
        }
        return $r;
    }

    public function getPackFormat(): string
    {
        return match ($this->fieldSize) {
            1 => 'C',
            2 => 'v',
            4 => 'V',
            default => throw new \UnexpectedValueException(),
        };
    }
}
