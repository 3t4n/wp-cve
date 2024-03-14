<?php

namespace Reamaze\API\Clients;

use Reamaze\API\Config;
use Reamaze\API\Exceptions\Api as ApiException;

class CurlClient extends BaseClient {
    public function makeRequest($method, $url, $params = null) {
        $curl = curl_init();
        $method = strtolower($method);
        $headers = self::getHeaders();
        $opts = array();

        $credentials = Config::getCredentials();

        if (empty($credentials) || empty($credentials['login']) || empty($credentials['apiToken'])) {
            throw new ApiException("Authorization error. Please ensure your login and API Token credentials are correct.");
        }

        $opts[CURLOPT_HTTPAUTH] = CURLAUTH_BASIC;
        $opts[CURLOPT_USERPWD] = $credentials['login'] . ':' . $credentials['apiToken'];
        $opts[CURLOPT_RETURNTRANSFER] = true;
        $opts[CURLOPT_CONNECTTIMEOUT] = 30;
        $opts[CURLOPT_TIMEOUT] = Config::DEFAULT_REQUEST_TIMEOUT;
        $opts[CURLOPT_HTTPHEADER] = $headers;

        switch ($method) {
            case 'get':
                if (count($params) > 0) {
                    $url = "{$url}?" .  http_build_query($params, null, '&');
                }
                break;

            case 'post':
                $opts[CURLOPT_POST] = 1;
                $opts[CURLOPT_POSTFIELDS] = json_encode($params);
                break;

            case 'delete':
                $opts[CURLOPT_CUSTOMREQUEST] = 'DELETE';
                if (count($params) > 0) {
                    $url = "{$url}?" .  http_build_query($params, null, '&');
                }
                break;

            case 'put':
                $opts[CURLOPT_CUSTOMREQUEST] = 'PUT';
                $opts[CURLOPT_POSTFIELDS] = json_encode($params);
                break;

            default:
                throw new ApiException("Unrecognized API Method: {$method}");
        }

        $opts[CURLOPT_URL] = $url;

        curl_setopt_array($curl, $opts);

        $response = curl_exec($curl);

        if ($response === false) {
            $errno = curl_errno($curl);
            $message = curl_error($curl);
            curl_close($curl);
            throw new ApiException($message, $errno);
        }

        $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);

        try {
            $result = json_decode($response, true);
        } catch (Exception $e) {
            throw new ApiException("Invalid API Response: $response", $httpCode);
        }

        if ($httpCode < 200 || $httpCode >= 300) {
            throw new ApiException("API Error $httpCode: $response", $httpCode);
        }

        return $result;
    }
}
