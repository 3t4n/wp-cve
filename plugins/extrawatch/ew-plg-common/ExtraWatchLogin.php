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

class ExtraWatchLogin {

    private $extraWatchCMSSpecific;

    const EW_PWD_LENGTH = 10;

    public function __construct(ExtraWatchCMSSpecific $extraWatchCMSSpecific) {
        $this->extraWatchCMSSpecific = $extraWatchCMSSpecific;
        $this->extraWatchRequestHelper = new ExtraWatchAPI($extraWatchCMSSpecific);
        $this->extraWatchAuth = new ExtraWatchAuth($extraWatchCMSSpecific);

    }

    public function createAccountAndLogin() {
        if (!$this->extraWatchCMSSpecific->isAdmin()) {
            die("Not authorized");
        }

        $email = $this->extraWatchCMSSpecific->getPluginOptionEmail();
        $accountExists = $this->extraWatchRequestHelper->findIfEmailExists($email);

        if (!$accountExists) {
            $success = $this->createAccountWithRandomPassword($email);
            if (!$success) {
                throw new Exception( "It was not possible to create new account. Account associated with email you specified is probably already registered. \n
                    Use https://app.extrawatch.com -> \"Forgot password?\". After specifying email address, you'll get instructions how to reset your password. \n
                    Don't forget to set that email and project ID in ExtraWatch settings.");
            }
        }

        $pluginOptionTempPassword = $this->extraWatchCMSSpecific->getPluginOptionTempPassword();
        $loginSuccess = $this->login($email, $pluginOptionTempPassword);
        if (!$loginSuccess) {
            return false;
        }
        return true;
    }

    private function login($email, $password) {
        if (!$this->extraWatchCMSSpecific->isAdmin()) {
            die("Not authorized");
        }
        $accessToken = $this->extraWatchAuth->retrieveAuthToken($email, $password);

        if (!$accessToken) {
            return false;
        }
        $this->extraWatchCMSSpecific->deletePluginOptionToken();
        $this->extraWatchCMSSpecific->savePluginOptionToken($accessToken);

        return true;

    }

    private function createAccountWithRandomPassword($email) {
        if (!$this->extraWatchCMSSpecific->isAdmin()) {
            die("Not authorized");
        }
        $randomPassword = $this->generateRandomPassword();
        $success = $this->extraWatchRequestHelper->createNewAccount($email, $randomPassword, true);
        if ($success) {
            $this->extraWatchCMSSpecific->savePluginOptionTempPassword($randomPassword);
        }
        return $success;
    }


    private function generateRandomPassword() {
        $alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
        $pass = array(); //remember to declare $pass as an array
        $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
        for ($i = 0; $i < self::EW_PWD_LENGTH; $i++) {
            $n = rand(0, $alphaLength);
            $pass[] = $alphabet[$n];
        }
        return implode($pass); //turn the array into a string
    }

}