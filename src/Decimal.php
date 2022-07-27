<?php
namespace Elplat\KktShtrih;

abstract class Decimal
{

    protected static function toBinaryInternal(float $value, int $coeff, int $size): string
    {
        $decimals = round($value * $coeff);

        if (PHP_INT_SIZE === 8) {
            $s = pack('P', (int)$decimals);
            return substr($s, 0, $size);
        } else {
            ini_set('precision', '15');
            $g = gmp_init((string)$decimals, 10);
            ini_restore('precision');
            $s = gmp_export($g, 1, GMP_LSW_FIRST | GMP_LITTLE_ENDIAN);
            return pack("a{$size}", $s);
        }
    }

    protected static function fromBinaryInternal(string $str, int $coeff): float
    {
        if (PHP_INT_SIZE === 8) {
            $str = str_pad($str, 8, "\x00");
            $a = unpack('P', $str);
            return (float)$a[1] / $coeff;
        } else {
            $g = gmp_import($str, 1, GMP_LSW_FIRST | GMP_LITTLE_ENDIAN);
            $s = gmp_strval($g);
            return (float)$s / $coeff;
        }
    }
}
