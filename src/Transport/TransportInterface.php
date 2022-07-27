<?php
namespace Elplat\KktShtrih\Transport;

interface TransportInterface
{
    public function sendMessage(string $message, int $responseTimeout): string;
}
