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


class ExtraWatchAPI {

    private $extraWatchCMSSpecific;
    private $extraWatchRequestHelper;


    public function __construct(ExtraWatchCMSSpecific $extraWatchCMSSpecific) {
        $this->extraWatchCMSSpecific = $extraWatchCMSSpecific;
        $this->extraWatchURLHelper = new ExtraWatchURLHelper($extraWatchCMSSpecific);
        $this->extraWatchRequestHelper = new ExtraWatchRequestHelper($extraWatchCMSSpecific);
    }

    public function createProjectForUrl($url, $email, $token) {

        $postFields = array(
            "url" => $url,
            "email" => $email
        );

        $url = ExtraWatchConfig::UAA_URL."/api/projects";
        return $this->extraWatchRequestHelper->httpPostRequest($url, $postFields, $token);

    }

    public function findIfEmailExists($email) {
        $url = ExtraWatchConfig::UAA_URL."/api/users/email:".urlencode($email);
        $result = $this->extraWatchRequestHelper->httpGetRequest($url);
        return $result;
    }

    public function createNewAccount($email, $password, $generatedPassword) {
        $url = ExtraWatchConfig::UAA_URL."/api/users";
        $postFields = array("email" => $email, "password" => $password, "generatedPassword" => $generatedPassword);
        $result = $this->extraWatchRequestHelper->httpPostRequest($url, $postFields);
        if ($result) {
            return true;
        }
        return false;

    }


    public function requestLoginWithProjectIdAndTempPassword($projectId, $tempPassword) {
        $url = $this->getUrlToLoginWithTempPassword($projectId, $tempPassword);
        $content = trim($this->extraWatchURLHelper->doURLRequest($url));
        return (int) $content;

    }

    public function requestPasswordReset($email) {
        $url = ExtraWatchConfig::UAA_URL."/api/account/reset_password/init";

        $postParams = array("mail" => $email);
        return $this->extraWatchRequestHelper->httpPostRequest($url, $postParams);
    }


}