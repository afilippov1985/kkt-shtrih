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
}
