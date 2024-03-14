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

class ExtraWatchRequestHelper {

    const HTTP_STATUS_OK = 200;
    const HTTP_STATUS_CREATED = 201;
    const REQUEST_TIMEOUT = 5;

    private $extraWatchCmsSpecific;

    public function __construct(ExtraWatchCMSSpecific $extraWatchCmsSpecific) {
        $this->extraWatchCmsSpecific = $extraWatchCmsSpecific;
    }

    function httpGetRequest($url) {
        return $this->extraWatchCmsSpecific->remoteGET($url);
    }

    function httpPostRequest($url, $postFields, $token = "") {
        return $this->extraWatchCmsSpecific->remotePOST($url, $postFields, $token);
    }

    function httpPostRequestWithBasicAuth($url, $postFields, $basicAuthUsername, $basicAuthPassword) {
        return $this->extraWatchCmsSpecific->remotePOSTWithBasicAuth($url, $postFields, $basicAuthUsername, $basicAuthPassword);
    }


    public static function isSuccessByHttpStatus($http_status) {
        if ($http_status == self::HTTP_STATUS_OK || $http_status == self::HTTP_STATUS_CREATED) {
            return true;
        }
        return false;
    }

}