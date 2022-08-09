<?php
namespace Elplat\KktShtrih;

enum CheckType: int
{
    case Sell = 1; // Приход
    case SellRefund = 2; // Возврат прихода
    case Buy = 3; // Расход
    case BuyRefund = 4; // Возврат расхода

    public static function fromString(string $str): self
    {
        return match ($str) {
            'sell' => self::Sell,
            'sell_refund' => self::SellRefund,
            'buy' => self::Buy,
            'buy_refund' => self::BuyRefund,
        };
    }
}
