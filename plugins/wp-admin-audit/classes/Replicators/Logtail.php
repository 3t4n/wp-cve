<?php
/**
 * @copyright (C) 2021 - 2024 Holger Brandt IT Solutions
 * @license GPL2
 */

if(!defined('ABSPATH')){
    exit;
}

class WADA_Replicator_Logtail extends WADA_Replicator_Base
{
    protected $apiUrl = 'https://in.logs.betterstack.com';
    protected $token = null;

    protected function setup(){

        /* @@REMOVE_START_WADA_startup@@ */
        /*  */
        /* @@REMOVE_END_WADA_startup@@ */

    }

    protected function send($severity, $message){

        /* @@REMOVE_START_WADA_startup@@ */
        /*  */
        /* @@REMOVE_END_WADA_startup@@ */

        return false;
    }

    protected function doLogtailSendRequest($jsonData){
        $result = $httpCode = $curlError = null;

        /* @@REMOVE_START_WADA_startup@@ */
        /*  */
        /* @@REMOVE_END_WADA_startup@@ */

        return array($result, $httpCode, $curlError);
    }

}