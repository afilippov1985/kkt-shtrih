<?php
namespace Elplat\KktShtrih;

use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;

class KktShtrih implements LoggerAwareInterface
{
    use LoggerAwareTrait;

    public int $password = 30;

    public function __construct(private readonly Transport\TransportInterface $transport)
    {
    }

    protected function logMessage(string $prefix, string $message)
    {
        $this->logger?->info($prefix . strlen($message) . ' ' . bin2hex($message));
    }

    /**
     * Записать значение в таблицу
     *
     * @param int $tableNumber
     * @param int $fieldNumber
     * @param int $rowNumber
     * @param string|int $value
     * @return bool
     * @throws ECRError
     */
    public function writeTable(int $tableNumber, int $fieldNumber, int $rowNumber, string|int $value): bool
    {
        $structResponse = $this->getFieldStruct($tableNumber, $fieldNumber);

        $request = new Request\WriteTable();
        $request->tableNumber = $tableNumber;
        $request->fieldNumber = $fieldNumber;
        $request->rowNumber = $rowNumber;
        $request->valueOfField = $value;
        $request->translateRawValue($structResponse);
        $response = $this->sendRequest($request);

        return true;
    }

    /**
     * @param int $tableNumber
     * @param int $fieldNumber
     * @return Response\GetFieldStruct
     * @throws ECRError
     */
    private function getFieldStruct(int $tableNumber, int $fieldNumber): Response\GetFieldStruct
    {
        $request = new Request\GetFieldStruct();
        $request->tableNumber = $tableNumber;
        $request->fieldNumber = $fieldNumber;
        return $this->sendRequest($request);
    }

    /**
     * Прочитать значение из таблицы
     *
     * @param int $tableNumber
     * @param int $fieldNumber
     * @param int $rowNumber
     * @return string|int
     * @throws ECRError
     */
    public function readTable(int $tableNumber, int $fieldNumber, int $rowNumber = 1): string|int
    {
        $structResponse = $this->getFieldStruct($tableNumber, $fieldNumber);

        $request = new Request\ReadTable();
        $request->tableNumber = $tableNumber;
        $request->fieldNumber = $fieldNumber;
        $request->rowNumber = $rowNumber;
        /** @var Response\ReadTable $response */
        $response = $this->sendRequest($request);
        $response->translateRawValue($structResponse);
        return $response->valueOfField;
    }

    /**
     * Прочитать значение из таблицы (предполагается что таблица хранит строковое значение)
     *
     * @param int $tableNumber
     * @param int $fieldNumber
     * @param int $rowNumber
     * @return string
     * @throws ECRError
     */
    private function readTableString(int $tableNumber, int $fieldNumber, int $rowNumber = 1): string
    {
        $request = new Request\ReadTable();
        $request->tableNumber = $tableNumber;
        $request->fieldNumber = $fieldNumber;
        $request->rowNumber = $rowNumber;
        /** @var Response\ReadTable $response */
        $response = $this->sendRequest($request);

        $structResponse = new Response\GetFieldStruct();
        $structResponse->fieldType = 1;

        $response->translateRawValue($structResponse);
        return $response->valueOfField;
    }

    /**
     * Получить заводской номер ККТ
     *
     * @return string
     * @throws ECRError
     */
    public function getKKTFactoryNumber(): string
    {
        return $this->readTableString(18, 1);
    }

    /**
     * Получить серийный номер ФН
     *
     * @return string
     * @throws ECRError
     */
    public function getFNSerialNumber(): string
    {
        return $this->readTableString(18, 4);
    }

    protected function beforeSendMessage(Request\AbstractRequest $request): ?string
    {
        return null;
    }

    /**
     * @param Request\AbstractRequest $request
     * @return Response\AbstractResponse
     * @throws ECRError
     */
    public function sendRequest(Request\AbstractRequest $request): Response\AbstractResponse
    {
        if (!$request->password) {
            $request->password = $this->password;
        }

        $requestMessage = (string)$request;
        $this->logMessage('Request: ', $requestMessage);
        $responseMessage = $this->beforeSendMessage($request) ?? $this->transport->sendMessage($requestMessage, $request->getResponseTimeout());
        $this->logMessage('Response: ', $responseMessage);

        return Response\AbstractResponse::decode($responseMessage, $request);
    }

    /**
     * Ждать окончания печати
     *
     * @return bool
     */
    protected function waitForPrint(): bool
    {
        $i = 0;
        do {
            usleep(100000);
            $ecrStatus = $this->getShortECRStatus();
            if ($ecrStatus->ecrAdvancedMode === ECRAdvancedMode::After) {
                $this->sendRequest(new Request\ContinuePrint());
            }
        } while (++$i < 20 && $ecrStatus->ecrAdvancedMode !== ECRAdvancedMode::Idle);

        return $ecrStatus->ecrAdvancedMode === ECRAdvancedMode::Idle;
    }

    /**
     * Ждать окончания выполнения команды Request\PrintReportWithCleaning
     *
     * @retrun bool
     */
    protected function waitZReport(): bool
    {
        $i = 0;
        do {
            sleep(1);
            $ecrStatus = $this->getShortECRStatus();
        } while (++$i < 30 && $ecrStatus->ecrMode === ECRMode::EKLZReport);

        return $ecrStatus->ecrMode !== ECRMode::EKLZReport;
    }

    /**
     * ККТ готова к работе
     *
     * @return bool
     */
    public function isReady(): bool
    {
        $ecrStatus = $this->getShortECRStatus();
        if ($ecrStatus->ecrAdvancedMode !== ECRAdvancedMode::Idle) {
            $this->waitForPrint();
            $ecrStatus = $this->getShortECRStatus();
        }

        return $ecrStatus->ecrAdvancedMode === ECRAdvancedMode::Idle && ($ecrStatus->ecrMode === ECRMode::Open || $ecrStatus->ecrMode === ECRMode::Closed);
    }

    /**
     * @return Response\GetShortECRStatus
     * @throws ECRError
     */
    public function getShortECRStatus(): Response\GetShortECRStatus
    {
        return $this->sendRequest(new Request\GetShortECRStatus());
    }

    /**
     * @param bool $wait
     * @return void
     * @throws ECRError
     */
    public function printReportWithCleaning(bool $wait = true): void
    {
        $response = $this->sendRequest(new Request\PrintReportWithCleaning());
        if ($wait) {
            $this->waitZReport();
        }
    }

    /**
     * @param int $tag
     * @param string $value
     * @return void
     * @throws ECRError
     */
    public function fnSendTLVString(int $tag, string $value): void
    {
        $request = new Request\FNSendTLV();
        $request->tlv = new TLVString($tag, $value);
        $this->sendRequest($request);
    }
}
