<?php
namespace Elplat\KktShtrih;

enum VatType: int
{
    case Vat20 = 1; // НДС 20%
    case Vat10 = 2; // НДС 10%
    case Vat0 = 4; // НДС 0%
    case None = 8; // Без НДС
    case Vat20120 = 16; // НДС 20/120
    case Vat10110 = 32; // НДС 10/110

    public static function fromString(string $str): self
    {
        return match ($str) {
            'none' => self::None,
            'vat0' => self::Vat0,
            'vat10' => self::Vat10,
            'vat20' => self::Vat20,
            'vat110' => self::Vat10110,
            'vat120' => self::Vat20120,
        };
    }
}
