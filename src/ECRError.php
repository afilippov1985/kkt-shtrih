<?php
namespace Elplat\KktShtrih;

class ECRError extends \Exception
{
    public int $command;

    private const ERRORS = [
        115 => 'Команда не поддерживается в данном режиме',
    ];

    public function __construct(int $command, int $code)
    {
        $this->command = $command;
        parent::__construct(self::ERRORS[$code] ?? '', $code);
    }
}
