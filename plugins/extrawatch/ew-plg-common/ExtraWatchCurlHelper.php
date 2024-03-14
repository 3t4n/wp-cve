<?php
/**
 * @file
 * ExtraWatch - Real-time visitor dashboard and stats
 * @package ExtraWatch
 * @version 4.0
 * @revision 34
 * @license http://www.gnu.org/licenses/gpl-3.0.txt     GNU General Public License v3
 * @copyright (C) 2018 by CodeGravity.com - All rights reserved!
 * @website http://www.extrawatch.com
 */

class ExtraWatchCurlHelper {

    const HTTP_STATUS_OK = 200;
    const HTTP_STATUS_CREATED = 201;

    /**
     * @param $url
     * @param $additionalHeaders
     * @param $username
     * @param $password
     * @param $payloadName
     */
    const REQUEST_TIMEOUT = 5;

    function httpGetRequest($url, $token = "") {
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded'));
        $this->addHttpHeaderWithAuthToken($token, $curl);
        curl_setopt($curl, CURLOPT_TIMEOUT, self::REQUEST_TIMEOUT);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
        $return = curl_exec($curl);
        $status = $this->getStatus($curl);
        if ($status == self::HTTP_STATUS_OK) {
            return $return;
        }
        return $this->isSuccessByHttpStatus($curl);
    }


    function httpPostRequest($url, $postFields, $token = "") {

        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded'));
        curl_setopt($curl, CURLOPT_TIMEOUT, self::REQUEST_TIMEOUT);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($postFields));
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
        $this->addHttpHeaderWithAuthToken($token, $curl);
        $return = curl_exec($curl);
        curl_close($curl);
        if (!$return) {
            return $this->isSuccessByHttpStatus($curl);
        }
        return $return;

    }

    function httpPostRequestWithBasicAuth($url, $postFields, $basicAuthUsername, $basicAuthPassword) {
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded'));
        curl_setopt($curl, CURLOPT_USERPWD, $basicAuthUsername . ":" . $basicAuthPassword);
        curl_setopt($curl, CURLOPT_TIMEOUT, self::REQUEST_TIMEOUT);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($postFields));
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
        $return = curl_exec($curl);
        curl_close($curl);
        return $return;

    }


        /**
     * @param $token
     * @return string
     */
    private function createTokenHttpHeader($token) {
        $authorization = "Authorization: Bearer $token";
        return $authorization;
    }

    /**
     * @param $token
     * @param $curl
     */
    private function addHttpHeaderWithAuthToken($token, $curl)
    {
        if ($token) {
            $authorization = $this->createTokenHttpHeader($token);
            curl_setopt($curl, CURLOPT_HTTPHEADER, array($authorization));
        }
    }

    /**
     * @param $curl
     * @return bool
     */
    private function isSuccessByHttpStatus($curl) {
        $http_status = $this->getStatus($curl);
        if ($http_status == self::HTTP_STATUS_OK || $http_status == self::HTTP_STATUS_CREATED) {
            return true;
        }
        return false;
    }

    /**
     * @param $curl
     * @return mixed
     */
    private function getStatus($curl) {
        $http_status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        return $http_status;
    }


}