<?php
namespace Mnet\Utils;

class Response
{
    const SUCCESS_MESSAGE = 'OK';
    const ERROR_MESSAGE = 'FAIL';

    public static function success($payload) {
        $response = self::wrap($payload);
        \wp_send_json($response, 200);
    }

    public static function fail($payload, $statusCode = 500, $message = "") {
        $response = self::wrap($payload, $message, self::ERROR_MESSAGE);
        \wp_send_json($response, $statusCode);
    }

    private static function wrap($payload, $message = "", $statusMessage = self::SUCCESS_MESSAGE)
    {
        $response = [];
        $response['payload'] = $payload;
        $response['message'] = $message;
        $response['status'] = $statusMessage;
        return $response;
    }
}
