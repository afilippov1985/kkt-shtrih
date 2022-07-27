<?php
namespace Elplat\KktShtrih\Request;

class FNFindDocument extends AbstractRequest
{
    /** @var int Номер фискального документа */
    public int $documentNumber;

    public function __construct()
    {
        $this->command = 0xFF0A;
    }

    public function __toString(): string
    {
        return pack('nVV', $this->command, $this->password, $this->documentNumber);
    }
}
