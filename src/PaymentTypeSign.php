<?php
namespace Elplat\KktShtrih;

enum PaymentTypeSign: int
{
    case FullPrepayment = 1; // Предоплата 100%
    case Prepayment = 2; // Частичная предоплата
    case Advance = 3; // Аванс
    case FullPayment = 4; // Полный расчет
    case PartialPayment = 5; // Частичный расчет и кредит
    case Credit = 6; // Передача в кредит
    case CreditPayment = 7; // Оплата кредита

    public static function fromString(string $str): self
    {
        return match ($str) {
            'full_prepayment' => self::FullPrepayment,
            'prepayment' => self::Prepayment,
            'advance' => self::Advance,
            'full_payment' => self::FullPayment,
            'partial_payment' => self::PartialPayment,
            'credit' => self::Credit,
            'credit_payment' => self::CreditPayment,
        };
    }
}
