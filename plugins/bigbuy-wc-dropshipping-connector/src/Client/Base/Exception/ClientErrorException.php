<?php

namespace WcMipConnector\Client\Base\Exception;

use WcMipConnector\Enum\StatusTypes;

defined('ABSPATH') || exit;

class ClientErrorException extends \Exception
{
    public function __construct($responseCode, $message = '')
    {
        $returnMessage = 'Call Returned ErrorCode: '.$responseCode;

        if (!empty($message)) {
            $returnMessage .= ' -> '.$message;
        }

        $returnMessage = $this->handleBadRequest($responseCode, $returnMessage);

        $returnMessage = $this->handleInternalServerRequest($responseCode, $returnMessage);

        $returnMessage = $this->handleConflictRequest($responseCode, $returnMessage, $message);

        parent::__construct($returnMessage, $responseCode);
    }

    private function handleBadRequest($responseCode, $responseMessage): string
    {
        if (
            $responseCode < StatusTypes::HTTP_BAD_REQUEST
            || $responseCode >= StatusTypes::HTTP_INTERNAL_SERVER_ERROR
        ) {
            return $responseMessage;
        }

        $responseMessage .= ' -> Failed sending api request.';

        return $responseMessage;
    }

    private function handleInternalServerRequest($responseCode, $responseMessage): string
    {
        if (
            $responseCode < StatusTypes::HTTP_INTERNAL_SERVER_ERROR
            || $responseCode > StatusTypes::INVALID_REQUEST_CODE
        ) {
            return $responseMessage;
        }

        $responseMessage .= ' -> API server failed to respond.';

        return $responseMessage;
    }

    private function handleConflictRequest($responseCode, $responseMessage, $message)
    {
        if ($responseCode !== StatusTypes::HTTP_CONFLICT) {
            return $responseMessage;
        }

        return $message;
    }
}