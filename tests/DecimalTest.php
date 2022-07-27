<?php

use Elplat\KktShtrih\{Currency, Quantity};
use PHPUnit\Framework\TestCase;

class DecimalTest extends TestCase
{
    public function testCurrencyRandom()
    {
        $val = round((float)mt_rand(0, 0xFFFFFFFFFF) * 0.01, 2);
        $bin = Currency::toBinary($val);
        $val2 = Currency::fromBinary($bin);
        $this->assertSame($val2, $val);
    }

    public function testQuantityRandom()
    {
        $val = round((float)mt_rand(0, 0xFFFFFFFFFFFF) * 0.000001, 6);
        $bin = Quantity::toBinary($val);
        $val2 = Quantity::fromBinary($bin);
        $this->assertSame($val2, $val);
    }

    public function testQuantity1()
    {
        $val = 1.0;
        $bin = Quantity::toBinary($val);
        $this->assertSame($bin, hex2bin('40420f000000'));
        $val2 = Quantity::fromBinary($bin);
        $this->assertSame($val2, $val);
    }

    public function testQuantity2()
    {
        $val = 281474976.710655;
        $bin = Quantity::toBinary($val);
        $this->assertSame($bin, hex2bin('ffffffffffff'));
        $val2 = Quantity::fromBinary($bin);
        $this->assertSame($val2, $val);
    }
}
