<?php

use Elplat\KktShtrih\{STLV, TLVString, TLVUInt8};
use PHPUnit\Framework\TestCase;

class TLVTest extends TestCase
{
    public function testStlv()
    {
        $stlv = new STLV(1224, [new TLVString(1171, '+79123456789'), new TLVUInt8(1225, 128)]);
        $expected = 'c804150093040c002b3739313233343536373839c904010080';
        $this->assertSame(hex2bin($expected), (string)$stlv);
    }
}
