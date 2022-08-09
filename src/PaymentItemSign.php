<?php
namespace Elplat\KktShtrih;

enum PaymentItemSign: int
{
    case Commodity = 1; // Товар
    case Excise = 2; // Подакцизный товар
    case Job = 3; // Работа
    case Service = 4; // Услуга
    case GamblingBet = 5; // Ставка азартной игры
    case GamblingPrize = 6; // Выигрыш азартной игры
    case Lottery = 7; // Лотерейный билет
    case LotteryPrize = 8; // Выигрыш лотереи
    case IntellectualActivity = 9; // Предоставление РИД
    case Payment = 10; // Платеж
    case AgentCommission = 11; // Агентское вознаграждение
    case Composite = 12; // Составной предмет расчета
    case Another = 13; // Иной предмет расчета
    case PropertyRight = 14; // Имущественное право
    case NonOperatingGain = 15; // Внереализационный доход
    case InsurancePremium = 16; // Страховые взносы
    case SalesTax = 17; // Торговый сбор
    case ResortFee = 18; // Курортный сбор

    public static function fromString(string $str): self
    {
        return match ($str) {
            'commodity' => self::Commodity,
            'excise' => self::Excise,
            'job' => self::Job,
            'service' => self::Service,
            'gambling_bet' => self::GamblingBet,
            'gambling_prize' => self::GamblingPrize,
            'lottery' => self::Lottery,
            'lottery_prize' => self::LotteryPrize,
            'intellectual_activity' => self::IntellectualActivity,
            'payment' => self::Payment,
            'agent_commission' => self::AgentCommission,
            'composite' => self::Composite,
            'another' => self::Another,
            'property_right' => self::PropertyRight,
            'non-operating_gain' => self::NonOperatingGain,
            'insurance_premium' => self::InsurancePremium,
            'sales_tax' => self::SalesTax,
        };
    }
}
