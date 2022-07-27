<?php
namespace Elplat\KktShtrih\Response;

class ReadTable extends AbstractResponse
{
    /** @var string|int Значение поля */
    public string|int $valueOfField;

    /** @var string Значение поля */
    public string $valueOfFieldRaw;

    public static function fromString(string $m): self
    {
        $r = new self();
        $r->valueOfFieldRaw = $m;
        return $r;
    }

    public function translateRawValue(GetFieldStruct $fieldStruct)
    {
        if ($fieldStruct->fieldType) {
            $a = unpack('Z*', $this->valueOfFieldRaw);
            $this->valueOfField = self::from1251($a[1]);
        } else {
            $t = $fieldStruct->getPackFormat();
            $a = unpack($t, $this->valueOfFieldRaw);
            $this->valueOfField = $a[1];
        }
    }
}
