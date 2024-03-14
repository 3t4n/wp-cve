<?php

namespace WcMipConnector\Service;

defined('ABSPATH') || exit;

use WcMipConnector\Manager\ConfigurationOptionManager;

class SecurityService
{
    private const SIGN_ALGORITHM_SHA256 = 'sha256';
    private const BODY_SIGNATURE_MESSAGE_ERROR = 'The X-Body-Signature parameter is not defined.';
    private const VERSION_MESSAGE_ERROR = 'The X-Version or X-Connector-Version parameter is not defined.';
    public const ACCESS_TOKEN_MESSAGE_ERROR = 'The Access-Token parameter is not defined.';

    /** @var ResponseService */
    private $responseService;

    /**
     * SecurityService constructor.
     */
    public function __construct() {
        $this->responseService = new ResponseService();
    }

    /**
     * @param array $headers
     * @param string $message
     */
    public function check(array $headers, string $message): void
    {
        $this->checkMethod($_SERVER['REQUEST_METHOD']);
        $this->checkHeaders($headers);

        $accessToken = $headers['Access-Token'];
        $signature = $headers['X-Body-Signature'];
        $this->checkSecurityToken($accessToken);
        $this->checkSignature($signature, $message);
    }

    private function checkSecurityToken(string $messageAccessToken): void
    {
        $accessToken = ConfigurationOptionManager::getAccessToken();

        if ($messageAccessToken !== $accessToken) {
            $this->responseService->jsonResponseBadRequest(
                'The Access-Token is not right '.$messageAccessToken
            );
        }
    }

    private function checkSignature(string $messageSignature, string $messageContent): void
    {
        $secretKey = ConfigurationOptionManager::getSecretKey();
        $signature = $this->sign($messageContent, $secretKey);

        if ($messageSignature !== $signature) {
            $this->responseService->jsonResponseForbidden(
                'Incorrect message signature: '.$messageSignature // Right: '.$signature
            );
        }
    }

    /**
     * @param string $data
     * @param string $secretKey
     * @return string
     */
    private function sign(string $data, string $secretKey): string
    {
        return base64_encode(
            hash_hmac(self::SIGN_ALGORITHM_SHA256, $data, $secretKey, true)
        );
    }

    private function checkMethod(string $method): void
    {
        if ($method !== 'POST') {
            $this->responseService->jsonResponseBadRequest(
                'The method of request '.$method.' is not valid.'
            );
        }
    }

    public function sanitizeHeaders(array $headers): array
    {
        foreach ($headers as $header => $value) {
            $sanitizeHeader = str_replace(' ', '-', ucwords(strtolower(str_replace('-', ' ', $header))));
            $headers[$sanitizeHeader] = $value;
        }

        return $headers;
    }

    private function checkHeaders(array $headers): void
    {
        if (empty($headers['Access-Token'])) {
            $this->responseService->jsonResponseBadRequest(
                self::ACCESS_TOKEN_MESSAGE_ERROR
            );
        }

        if (empty($headers['X-Body-Signature'])) {
            $this->responseService->jsonResponseBadRequest(
                self::BODY_SIGNATURE_MESSAGE_ERROR
            );
        }

        if (empty($headers['X-Version']) && empty($headers['X-Connector-Version'])) {
            $this->responseService->jsonResponseBadRequest(
                self::VERSION_MESSAGE_ERROR
            );
        }
    }
}