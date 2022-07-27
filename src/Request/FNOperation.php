<?php
namespace Elplat\KktShtrih\Request;

use Elplat\KktShtrih\{Currency, PaymentItemSign, PaymentTypeSign, Quantity};

class FNOperation extends AbstractRequest
{
    /** @var int Тип операции */
    public int $checkType = 1;

    /** @var float Количество */
    public float $quantity = 1;

    /** @var float Цена за единицу */
    public float $price;

    /** @var float|null Сумма операции */
    public ?float $summ1 = null;

    /** @var float|null Сумма налога */
    public ?float $taxValue = null;

    /** @var int Налоговая ставка */
    public int $tax1;

    public int $department = 1;

    /** @var PaymentTypeSign Признак способа расчета */
    public PaymentTypeSign $paymentTypeSign;

    /** @var PaymentItemSign Признак предмета расчета */
    public PaymentItemSign $paymentItemSign;

    public string $stringForPrinting;

    public function __construct()
    {
        $this->command = 0xFF46;
    }

    public function __toString(): string
    {
        return pack(
            'nVCa6a5a5a5CCCCa*',
            $this->command,
            $this->password,
            $this->checkType,
            Quantity::toBinary($this->quantity),
            Currency::toBinary($this->price),
            $this->summ1 === null ? "\xFF\xFF\xFF\xFF\xFF" : Currency::toBinary($this->summ1),
            $this->taxValue === null ? "\xFF\xFF\xFF\xFF\xFF" : Currency::toBinary($this->taxValue),
            $this->tax1,
            $this->department,
            $this->paymentTypeSign->value,
            $this->paymentItemSign->value,
            substr(self::to1251($this->stringForPrinting), 128),
        );
    }
}
