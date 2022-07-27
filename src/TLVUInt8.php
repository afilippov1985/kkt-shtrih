<?php
namespace Elplat\KktShtrih;

class TLVUInt8 extends TLV
{
    public function __construct(int $tag, int $value)
    {
        parent::__construct($tag, pack('C', $value));
    }
}
