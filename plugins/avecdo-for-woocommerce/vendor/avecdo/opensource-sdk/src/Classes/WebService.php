<?php

namespace Avecdo\SDK\Classes;

use DateTime;
use Exception;
use Avecdo\SDK\Constants;
use Avecdo\SDK\Exceptions\AuthException;
use Avecdo\SDK\POPO\KeySet;
use stdClass;

class WebService
{
    private $baseUrl;

    public function __construct()
    {
        $this->baseUrl = Constants::API_BASE_URL;
    }

    protected static function post($url, $body, $headers)
    {
        $ch = curl_init();

        $curlOptions = array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_FOLLOWLOCATION => 0,
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_POST => 1,
            CURLOPT_POSTFIELDS => $body,
            CURLOPT_ENCODING => '',
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_HEADER => 0,
        );

        curl_setopt_array($ch, $curlOptions);

        $response   = curl_exec($ch);
        $error      = curl_error($ch);
        $httpStatusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        if ($error) {
            throw new Exception($error);
        }

        $toReturn = new stdClass();
        $toReturn->code = $httpStatusCode;
        $toReturn->body = json_decode($response);

        return $toReturn;
    }

    protected function getAuthenticationHeaders(KeySet $keySet)
    {
        $privateKey = $keySet->getPrivateKey();
        $publicKey = $keySet->getPublicKey();

        $date = new DateTime();
        $dateAsString = $date->getTimestamp();

        $stringToSign = $dateAsString . $publicKey;
        $signature = hash_hmac('sha256', $stringToSign, $privateKey);

        return array(
            'x-apikey: '.$publicKey,
            'x-datetime: '.$dateAsString,
            'x-signature: '.$signature
        );
    }

    public function authenticate(KeySet $keySet, $webshopPath)
    {
        $headers = array(
            'user-agent: avecdo-sdk ' . Constants::SDK_VERSION
        );

        $headers = array_merge($headers, $this->getAuthenticationHeaders($keySet));

        $query = array(
           'webshopPath' => $webshopPath
        );

        $response = static::post($this->baseUrl . "/api/shop/plugin/authenticate", $query, $headers);

        if ($response->code == 200) {
            return $response->body;
        } else {
            $authException = new AuthException("Authentication Error", $response->code);
            $authException->setPayload($response->body);
            throw $authException;
        }
    }
}
