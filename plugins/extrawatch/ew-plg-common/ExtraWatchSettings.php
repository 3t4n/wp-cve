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

if (!defined("EXTRAWATCH_SETTING_PROJECT_ID"))    define("EXTRAWATCH_SETTING_PROJECT_ID",'extrawatch-projectId');
if (!defined("EXTRAWATCH_SETTING_EMAIL"))    define("EXTRAWATCH_SETTING_EMAIL",'extrawatch-email');
if (!defined("EXTRAWATCH_SETTING_URL"))    define("EXTRAWATCH_SETTING_URL",'extrawatch-url');
if (!defined("EXTRAWATCH_SETTING_TEMP_PASSWORD"))    define("EXTRAWATCH_SETTING_TEMP_PASSWORD",'extrawatch-temp-password');
if (!defined("EXTRAWATCH_OAUTH2_TOKEN"))    define("EXTRAWATCH_OAUTH2_TOKEN",'extrawatch-oauth2-token');
if (!defined("EXTRAWATCH_SETTING_TERMS"))    define("EXTRAWATCH_SETTING_TERMS",'extrawatch-terms');
if (!defined("EXTRAWATCH_NONCE"))    define("EXTRAWATCH_NONCE",'extrawatch-nonce');

class ExtraWatchSettings {

    const CHECKBOX_ON = "on";

    private $extraWatchCmsSpecific;

    public function __construct(ExtraWatchCMSSpecific $extraWatchCmsSpecific) {
        $this->extraWatchCmsSpecific = $extraWatchCmsSpecific;

    }

    public function isSettingsSaveTriggered() {
        return @$this->extraWatchCmsSpecific->getSanitizedFromGET('action') == "save";
    }

    public function isSettingsPage() {
        return (@$this->extraWatchCmsSpecific->getSanitizedFromGET('page') == "extrawatch-settings"
            || @$this->extraWatchCmsSpecific->getSanitizedFromPOST('page') == "extrawatch-settings");
    }


    public function saveSettings() {

        if (!$this->extraWatchCmsSpecific->isAdmin()) {
            die("Not authorized");
        }

        $nonceValue = @$this->extraWatchCmsSpecific->getSanitizedFromPOST(EXTRAWATCH_NONCE);
        if (!$this->extraWatchCmsSpecific->verifyNonce($nonceValue, EXTRAWATCH_NONCE)) {
            die("Not authorized");
        }

        $terms = @$this->extraWatchCmsSpecific->getSanitizedFromPOST(EXTRAWATCH_SETTING_TERMS);
        $projectId = @$this->extraWatchCmsSpecific->getSanitizedFromPOST(EXTRAWATCH_SETTING_PROJECT_ID);
        $email = @$this->extraWatchCmsSpecific->getSanitizedFromPOST(EXTRAWATCH_SETTING_EMAIL);
        $url = @$this->extraWatchCmsSpecific->getSanitizedFromPOST(EXTRAWATCH_SETTING_URL);

        $this->extraWatchCmsSpecific->savePluginOptionProjectId($projectId);
        $this->extraWatchCmsSpecific->savePluginOptionEmail($email);

        if ($url) {
            $this->extraWatchCmsSpecific->savePluginOptionURL($url);
        }

        if ($terms != self::CHECKBOX_ON) {
            return false;
        }
        $termsValue = "Terms accepted, unix timestamp: ".time();
        $this->extraWatchCmsSpecific->savePluginOptionTerms($termsValue);

        echo("<br/><br/><span style='color: green'>Settings have been saved.</span>");

        return true;
    }

    public function renderExtraWatchSettings() {
        $tmplFolder = realpath(dirname(__FILE__) . "/assets/");
        require($tmplFolder . "/settings.php");
    }

    public function renderExtraWatchCreateAccount() {
        $tmplFolder = realpath(dirname(__FILE__) . "/assets/");
        require($tmplFolder ."/create-account.php");
    }


    public function isInitialSettingProvided() {
        return ($this->extraWatchCmsSpecific->getPluginOptionTerms()
            && $this->extraWatchCmsSpecific->getPluginOptionProjectId());
    }

    public function isFromCreateAccount() {
        return ($this->extraWatchCmsSpecific->getSanitizedFromPOST(EXTRAWATCH_SETTING_TERMS) == self::CHECKBOX_ON);
    }

    public function resetStoredTempPasswordAndToken() {
        $this->extraWatchCmsSpecific->savePluginOptionTempPassword("");
        $this->extraWatchCmsSpecific->savePluginOptionToken("");
    }

}