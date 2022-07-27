<?php
namespace Elplat\KktShtrih;

enum ECRAdvancedMode: int
{
    case Idle = 0; // Бумага есть
    case Passive = 1; // Пассивное отсутствие бумаги
    case Active = 2; // Активное отсутствие бумаги
    case After = 3; // После активного отсутствия бумаги
    case Report = 4; // Фаза печати отчетов
    case Print = 5; // Фаза печати операции
}
