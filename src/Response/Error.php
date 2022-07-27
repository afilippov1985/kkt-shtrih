<?php
namespace Elplat\KktShtrih\Response;

class Error extends AbstractResponse
{
    private const ERRORS = [
        115 => 'Команда не поддерживается в данном режиме',
    ];

    public static function fromString(string $m): self
    {
        return new self();
    }

    public function getErrorDescription(): string
    {
        return self::ERRORS[$this->resultCode] ?? '';
    }
}
