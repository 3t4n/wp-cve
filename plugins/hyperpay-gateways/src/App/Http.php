<?php

namespace Hyperpay\Gateways\App;

use WP_Error;
use Hyperpay\Gateways\App\Log;

final class Http
{

    public static function post($url, $data = [])
    {
        $response = wp_remote_post($url, $data);
        return self::handelResponse($response);
    }

    public static function get($url, $options = [])
    {
        $response = wp_remote_get($url, $options);
        return self::handelResponse($response);
    }

    public static function handelResponse($response)
    {
        $result =  json_decode(wp_remote_retrieve_body($response), true);

        if (is_wp_error($response)) {
            $error = $response->get_error_message();
            Log::write(["error" => $error, "response" => $result]);

            return [
                "result" => [
                    "code" => wp_remote_retrieve_response_code($response),
                    "description" => $error
                ]
            ];
        }

        return $result;
    }
}
