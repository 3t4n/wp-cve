<?php

namespace Avecdo\SDK\Classes;

use Avecdo\SDK\POPO\KeySet;

class Auth
{
    const MAX_AUTH_TIMEOUT = 200;

    public static function enable(KeySet $keySet)
    {
        $headers = Helpers::getAllHeaders();

        if (!isset($headers['x-apikey']) || !isset($headers['x-datetime']) || !isset($headers['x-signature'])) {
            Response::asJsonError('Invalid headers.', 400);
        }

        $time = time();

        $publicKeyFromServer = $headers['x-apikey'];
        $dateTimeFromServer = $headers['x-datetime'];
        $signatureFromServer = $headers['x-signature'];

        $stringToSign = $dateTimeFromServer . $keySet->getPublicKey();
        $signature = hash_hmac('sha256', $stringToSign, $keySet->getPrivateKey());

        if(($time - $dateTimeFromServer) > static::MAX_AUTH_TIMEOUT) {
            Response::asJsonError('Authentication failed due to timeout being exceeded.', 401);
        }

        if($keySet->getPublicKey() != $publicKeyFromServer) {
            Response::asJsonError('Authentication failed due to invalid API key.', 401);
        }

        if($signature != $signatureFromServer) {
            Response::asJsonError('Authentication failed due to invalid credentials.', 401);
        }
    }
}
