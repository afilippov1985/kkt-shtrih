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
     */
    public function writeTable(int $tableNumber, int $fieldNumber, int $rowNumber, string|int $value): bool
    {
        $request = new Request\GetFieldStruct();
        $request->tableNumber = $tableNumber;
        $request->fieldNumber = $fieldNumber;
        $structResponse = $this->sendRequest($request);

        if (!$structResponse instanceof Response\GetFieldStruct) {
            return false;
        }

        $request = new Request\WriteTable();
        $request->tableNumber = $tableNumber;
        $request->fieldNumber = $fieldNumber;
        $request->rowNumber = $rowNumber;
        $request->valueOfField = $value;
        $request->translateRawValue($structResponse);
        $response = $this->sendRequest($request);

        return $response instanceof Response\WriteTable;
    }

    /**
     * Прочитать значение из таблицы
     *
     * @param int $tableNumber
     * @param int $fieldNumber
     * @param int $rowNumber
     * @return string|int|null
     */
    public function readTable(int $tableNumber, int $fieldNumber, int $rowNumber = 1): string|int|null
    {
        $request = new Request\GetFieldStruct();
        $request->tableNumber = $tableNumber;
        $request->fieldNumber = $fieldNumber;
        $structResponse = $this->sendRequest($request);

        if (!$structResponse instanceof Response\GetFieldStruct) {
            return null;
        }

        $request = new Request\ReadTable();
        $request->tableNumber = $tableNumber;
        $request->fieldNumber = $fieldNumber;
        $request->rowNumber = $rowNumber;
        $response = $this->sendRequest($request);

        if (!$response instanceof Response\ReadTable) {
            return null;
        }

        $response->translateRawValue($structResponse);
        return $response->valueOfField;
    }

    /**
     * Прочитать значение из таблицы (предполагается что таблица хранит строковое значение)
     *
     * @param int $tableNumber
     * @param int $fieldNumber
     * @param int $rowNumber
     * @return string|null
     */
    private function readTableString(int $tableNumber, int $fieldNumber, int $rowNumber = 1): ?string
    {
        $request = new Request\ReadTable();
        $request->tableNumber = $tableNumber;
        $request->fieldNumber = $fieldNumber;
        $request->rowNumber = $rowNumber;
        $response = $this->sendRequest($request);

        if (!$response instanceof Response\ReadTable) {
            return null;
        }

        $structResponse = new Response\GetFieldStruct();
        $structResponse->fieldType = 1;

        $response->translateRawValue($structResponse);
        return $response->valueOfField;
    }

    /**
     * Получить заводской номер ККТ
     *
     * @return string|null
     */
    public function getKKTFactoryNumber(): ?string
    {
        return $this->readTableString(18, 1);
    }

    /**
     * Получить серийный номер ФН
     *
     * @return string|null
     */
    public function getFNSerialNumber(): ?string
    {
        return $this->readTableString(18, 4);
    }

    protected function beforeSendMessage(Request\AbstractRequest $request): ?string
    {
        return null;
    }

    public function sendRequest(Request\AbstractRequest $request): Response\AbstractResponse
    {
        if (!$request->password) {
            $request->password = $this->password;
        }

        $requestMessage = (string)$request;
        $this->logMessage('Request: ', $requestMessage);
        $responseMessage = $this->beforeSendMessage($request) ?? $this->transport->sendMessage($requestMessage, $request->responseTimeout);
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
            $ecrStatus = $this->sendRequest(new Request\GetShortECRStatus());
            if ($ecrStatus instanceof Response\GetShortECRStatus && $ecrStatus->ecrAdvancedMode === ECRAdvancedMode::After) {
                $this->sendRequest(new Request\ContinuePrint());
            }
        } while (++$i < 20 && $ecrStatus instanceof Response\GetShortECRStatus && $ecrStatus->ecrAdvancedMode !== ECRAdvancedMode::Idle);

        return $ecrStatus instanceof Response\GetShortECRStatus && $ecrStatus->ecrAdvancedMode === ECRAdvancedMode::Idle;
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
            $ecrStatus = $this->sendRequest(new Request\GetShortECRStatus());
        } while (++$i < 30 && $ecrStatus instanceof Response\GetShortECRStatus && $ecrStatus->ecrMode === ECRMode::EKLZReport);

        return $ecrStatus instanceof Response\GetShortECRStatus && $ecrStatus->ecrMode !== ECRMode::EKLZReport;
    }

    /**
     * ККТ готова к работе
     *
     * @return bool
     */
    public function isReady(): bool
    {
        $ecrStatus = $this->sendRequest(new Request\GetShortECRStatus());
        if ($ecrStatus instanceof Response\GetShortECRStatus && $ecrStatus->ecrAdvancedMode !== ECRAdvancedMode::Idle) {
            $this->waitForPrint();
            $ecrStatus = $this->sendRequest(new Request\GetShortECRStatus());
        }

        return $ecrStatus instanceof Response\GetShortECRStatus && $ecrStatus->ecrAdvancedMode === ECRAdvancedMode::Idle && ($ecrStatus->ecrMode === ECRMode::Open || $ecrStatus->ecrMode === ECRMode::Closed);
    }
}
