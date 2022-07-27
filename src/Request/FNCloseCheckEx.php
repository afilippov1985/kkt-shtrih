<?php
namespace Elplat\KktShtrih\Request;

use Elplat\KktShtrih\{Currency, PaymentItemSign, PaymentTypeSign, Quantity};

class FNCloseCheckEx extends AbstractRequest
{
    /** @var float Сумма */
    public float $summ1 = 0;

    /** @var float Сумма */
    public float $summ2 = 0;

    /** @var float Сумма */
    public float $summ3 = 0;

    /** @var float Сумма */
    public float $summ4 = 0;

    /** @var float Сумма */
    public float $summ5 = 0;

    /** @var float Сумма */
    public float $summ6 = 0;

    /** @var float Сумма */
    public float $summ7 = 0;

    /** @var float Сумма */
    public float $summ8 = 0;

    /** @var float Сумма */
    public float $summ9 = 0;

    /** @var float Сумма */
    public float $summ10 = 0;

    /** @var float Сумма */
    public float $summ11 = 0;

    /** @var float Сумма */
    public float $summ12 = 0;

    /** @var float Сумма */
    public float $summ13 = 0;

    /** @var float Сумма */
    public float $summ14 = 0;

    /** @var float Сумма */
    public float $summ15 = 0;

    /** @var float Сумма */
    public float $summ16 = 0;

    /** @var int Округление до рубля в копейках */
    public int $roundingSumm = 0;

    /** @var float Налог */
    public float $tax1Value = 0;

    /** @var float Налог */
    public float $tax2Value = 0;

    /** @var float Налог */
    public float $tax3Value = 0;

    /** @var float Налог */
    public float $tax4Value = 0;

    /** @var float Налог */
    public float $tax5Value = 0;

    /** @var float Налог */
    public float $tax6Value = 0;

    /** @var int Система налогообложения (битовое поле) */
    public int $taxType = 0;

    /** @var string Текст до 64 символов */
    public string $stringForPrinting;

    public function __construct()
    {
        $this->command = 0xFF45;
    }

    public function __toString(): string
    {
        return pack(
            'nVa5a5a5a5a5a5a5a5a5a5a5a5a5a5a5a5Ca5a5a5a5a5a5Ca*',
            $this->command,
            $this->password,
            $this->summ1,
            $this->summ2,
            $this->summ3,
            $this->summ4,
            $this->summ5,
            $this->summ6,
            $this->summ7,
            $this->summ8,
            $this->summ9,
            $this->summ10,
            $this->summ11,
            $this->summ12,
            $this->summ13,
            $this->summ14,
            $this->summ15,
            $this->summ16,
            $this->roundingSumm,
            $this->tax1Value,
            $this->tax2Value,
            $this->tax3Value,
            $this->tax4Value,
            $this->tax5Value,
            $this->tax6Value,
            $this->taxType,
            substr(self::to1251($this->stringForPrinting), 0, 64),
        );
    }
}
