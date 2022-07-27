<?php
namespace Elplat\KktShtrih;

class Currency extends Decimal
{

    public static function toBinary(float $value): string
    {
        return self::toBinaryInternal($value, 100, 5);
    }

    public static function fromBinary(string $str): float
    {
        return self::fromBinaryInternal($str, 100);
    }
}
