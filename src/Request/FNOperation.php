<?php
namespace Elplat\KktShtrih\Request;

use Elplat\KktShtrih\{CheckType, Currency, PaymentItemSign, PaymentTypeSign, Quantity, VatType};

class FNOperation extends AbstractRequest
{
    /** @var CheckType Тип операции */
    public CheckType $checkType;

    /** @var float Количество */
    public float $quantity = 1;

    /** @var float Цена за единицу */
    public float $price;

    /** @var float|null Сумма операции */
    public ?float $summ1 = null;

    /** @var float|null Сумма налога */
    public ?float $taxValue = null;

    /** @var VatType Налоговая ставка */
    public VatType $tax1 = VatType::None;

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
            $this->checkType->value,
            Quantity::toBinary($this->quantity),
            Currency::toBinary($this->price),
            $this->summ1 === null ? "\xFF\xFF\xFF\xFF\xFF" : Currency::toBinary($this->summ1),
            $this->taxValue === null ? "\xFF\xFF\xFF\xFF\xFF" : Currency::toBinary($this->taxValue),
            $this->tax1->value,
            $this->department,
            $this->paymentTypeSign->value,
            $this->paymentItemSign->value,
            substr(self::to1251($this->stringForPrinting), 128),
        );
    }
}
