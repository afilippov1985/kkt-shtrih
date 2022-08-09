<?php
namespace Elplat\KktShtrih\Response;

use Elplat\KktShtrih\ECRError;
use Elplat\KktShtrih\Request\AbstractRequest;

abstract class AbstractResponse
{
    private const COMMAND_TO_CLASS = [
        0x10 => GetShortECRStatus::class,
        0x11 => GetECRStatus::class,
        0x13 => Beep::class,
        0x1E => WriteTable::class,
        0x1F => ReadTable::class,
        0x21 => SetTime::class,
        0x2E => GetFieldStruct::class,
        0x41 => PrintReportWithCleaning::class,
        0x88 => CancelCheck::class,
        0xB0 => ContinuePrint::class,
        0xE0 => OpenSession::class,
        0xFEF3 => Reboot::class,
        0xFF01 => FNGetStatus::class,
        0xFF09 => FNGetFiscalizationResult::class,
        0xFF0A => FNFindDocument::class,
        0xFF0B => FNOpenSession::class,
        0xFF0C => FNSendTLV::class,
        0xFF38 => FNBuildCalculationStateReport::class,
        0xFF39 => FNGetInfoExchangeStatus::class,
        0xFF40 => FNGetCurrentSessionParams::class,
        0xFF45 => FNCloseCheckEx::class,
        0xFF46 => FNOperation::class,
        0xFF4D => FNSendTLVOperation::class,
    ];

    public int $command;

    /** @var int Порядковый номер оператора, чей пароль был введён */
    public int $operatorNumber = 0;

    protected static function formatDate(int $year, int $month, int $day): string
    {
        return sprintf('%04d-%02d-%02d', 2000 + $year, $month, $day);
    }

    protected static function formatDateTime(int $year, int $month, int $day, int $hours, int $minutes, int $seconds = 0): string
    {
        return sprintf('%04d-%02d-%02d %02d:%02d:%02d', 2000 + $year, $month, $day, $hours, $minutes, $seconds);
    }

    protected static function from1251(string $str): string
    {
        return iconv('CP1251', mb_internal_encoding(), $str);
    }

    abstract public static function fromString(string $m): self;

    /**
     * @param string $m Ответ кассы в бинарном виде
     * @param AbstractRequest|null $request
     * @return static
     * @throws ECRError
     */
    public static function decode(string $m, ?AbstractRequest $request = null): self
    {
        if ($m[0] === "\xFF") {
            $a = unpack('nCommand/CResultCode', $m);
            $m = substr($m, 3);
        } else {
            $a = unpack('CCommand/CResultCode', $m);
            $m = substr($m, 2);
        }

        $command = $a['Command'];
        $resultCode = $a['ResultCode'];

        if ($command === 0xFE && $request !== null && ($request->getCommand() & 0xFE00) === 0xFE00) {
            $command = $request->getCommand();
        }

        if ($resultCode) {
            throw new ECRError($command, $resultCode);
        }

        $responseClass = self::COMMAND_TO_CLASS[$command] ?? Unknown::class;

        $response = call_user_func([$responseClass, 'fromString'], $m);

        $response->command = $command;
        return $response;
    }
}
