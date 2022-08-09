<?php
namespace Elplat\KktShtrih;

enum AgentType: int
{
    case BankPayingAgent = 1;
    case BankPayingSubagent = 2;
    case PayingAgent = 4;
    case PayingSubagent = 8;
    case Attorney = 16;
    case CommissionAgent = 32;
    case Another = 64;

    public static function fromString(string $str): self
    {
        return match ($str) {
            'bank_paying_agent' => self::BankPayingAgent,
            'bank_paying_subagent' => self::BankPayingSubagent,
            'paying_agent' => self::PayingAgent,
            'paying_subagent' => self::PayingSubagent,
            'attorney' => self::Attorney,
            'commission_agent' => self::CommissionAgent,
            'another' => self::Another,
        };
    }
}
