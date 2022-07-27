<?php
namespace Elplat\KktShtrih;

class TLVString extends TLV
{
    public function __construct(int $tag, string $value)
    {
        parent::__construct($tag, self::to866($value));
    }

    private static function to866(string $str): string
    {
        return iconv(mb_internal_encoding(), 'CP866', $str);
    }
}
