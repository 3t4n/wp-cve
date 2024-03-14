<?php
/**
 * @file
 * ExtraWatch - Real-time visitor dashboard and stats
 * @package ExtraWatch
 * @version 4.0
 * @revision 53
 * @license http://www.gnu.org/licenses/gpl-3.0.txt     GNU General Public License v3
 * @copyright (C) 2021 by CodeGravity.com - All rights reserved!
 * @website http://www.extrawatch.com
 */

class ExtraWatchAuth {

    private $extraWatchCurlHelper;

    /**
     * ExtraWatchAuth constructor.
     * @param $extraWatchCurlHelper
     */
    public function __construct(ExtraWatchCMSSpecific $extraWatchCMSSpecific) {
        $this->extraWatchCurlHelper = new ExtraWatchRequestHelper($extraWatchCMSSpecific);
    }


    function retrieveAuthToken($login, $password) {

        $url = ExtraWatchConfig::UAA_URL ."/oauth/token";

        $postFields = array (
            "client_id" => $login,
            "username" => $login,
            "password" => urlencode($password),
            "grant_type" => "client_credentials"
        );

        $return = $this->extraWatchCurlHelper->httpPostRequestWithBasicAuth($url, $postFields, $login, $password);

        $jsonDecoded = json_decode($return);
        if (!$jsonDecoded) {
            return false;
        }
        $accessToken = $jsonDecoded->access_token;
        return $accessToken;

    }




}