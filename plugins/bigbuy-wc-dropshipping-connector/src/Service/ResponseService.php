<?php

namespace WcMipConnector\Service;

use WcMipConnector\Enum\StatusTypes;
use WcMipConnector\Enum\WooCommerceErrorCodes;

defined('ABSPATH') || exit;

class ResponseService
{

    private const PERMISSION_DENIED_RESPONSE_ERROR = 'Unable to create directory';
    private const NOT_FOUND_RESPONSE_ERROR = 'Not Found';
    private const TIMEOUT_RESPONSE_ERROR = 'Connection timed out';

    /** @var LoggerService */
    private $loggerService;

    /**
     * ResponseService constructor.
     */
    public function __construct()
    {
        $this->loggerService = new LoggerService();
    }

    /**
     * @param string $message
     * @param array $data
     */
    public function jsonResponseSuccess(string $message = '', array $data = []): void
    {
        $this->jsonResponse(StatusTypes::HTTP_OK, $message, $data);
    }

    /**
     * @param string $message
     * @param array $data
     */
    public function jsonResponseBadRequest(string $message = '', array $data = []): void
    {
        $this->jsonResponse(StatusTypes::HTTP_BAD_REQUEST, $message, $data);
    }

    /**
     * @param string $message
     * @param array $data
     */
    public function jsonResponseNotFound(string $message = '', array $data = []): void
    {
        $this->jsonResponse(StatusTypes::HTTP_NOT_FOUND, $message, $data);
    }

    /**
     * @param string $message
     * @param array $data
     */
    public function jsonResponseInternalError(string $message = 'System Error', array $data = []): void
    {
        $this->jsonResponse(StatusTypes::HTTP_INTERNAL_SERVER_ERROR, $message, $data);
    }

    /**
     * @param string $message
     * @param array $data
     */
    public function jsonResponseForbidden(string $message = 'Forbidden', array $data = []): void
    {
        $this->jsonResponse(StatusTypes::HTTP_FORBIDDEN, $message, $data);
    }

    /**
     * @param int $code
     * @param string $message
     * @param array $data
     */
    public function jsonResponse(int $code, string $message = '', array $data = []): void
    {
        $this->loggerService->debug(
            "$code - $message",
            [
                'Data' => $data,
                'Post' => \json_encode(sanitize_post($_POST)),
                'Get' => \json_encode(sanitize_post($_GET)),
                'Server' => \json_encode($_SERVER),
            ]
        );

        $result = ['Code' => $code, 'Message' => $message, 'Data' => $data];

        header('Content-Type: application/json');

        $response = \json_encode($result, JSON_UNESCAPED_UNICODE);

        if ($code > 399) {
            $this->loggerService->alert("RESPONSE: $response");
        } else {
            $this->loggerService->debug("RESPONSE: $response");
        }

        die($response);
    }

    public static function getInternalErrorCodeFromResponseError(string $errorMessage): string
    {
        if (strpos($errorMessage, self::PERMISSION_DENIED_RESPONSE_ERROR) !== false) {
            return LoggerService::CODE_IMAGE_INVALID_FOLDER_PERMISSIONS;
        }

        if (strpos($errorMessage, self::NOT_FOUND_RESPONSE_ERROR) !== false) {
            return LoggerService::CODE_IMAGE_NOT_FOUND;
        }

        if (strpos($errorMessage, self::TIMEOUT_RESPONSE_ERROR) !== false) {
            return LoggerService::CODE_IMAGE_TIMED_OUT;
        }

        return WooCommerceErrorCodes::UPLOAD_PRODUCT_IMAGE_ERROR;
    }
}