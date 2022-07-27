<?php
namespace Elplat\KktShtrih;

class Quantity extends Decimal
{

    public static function toBinary(float $value): string
    {
        return self::toBinaryInternal($value, 1000000, 6);
    }

    public static function fromBinary(string $str): float
    {
        return self::fromBinaryInternal($str, 1000000);
    }
}
