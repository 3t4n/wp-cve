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

class ExtraWatchController {

    private $extraWatchCmsSpecific;
    private $extraWatchURLHelper;
    private $extraWatchRequestHelper;
    private $extraWatchRenderer;

    public function __construct(ExtraWatchCMSSpecific $extraWatchCMSSpecific) {
        $this->extraWatchCmsSpecific = $extraWatchCMSSpecific;
        $this->extraWatchURLHelper = new ExtraWatchURLHelper($extraWatchCMSSpecific);
        $this->extraWatchRequestHelper = new ExtraWatchAPI($extraWatchCMSSpecific);
        $this->extraWatchRenderer = new ExtraWatchRenderer($extraWatchCMSSpecific);
        $this->extraWatchTempLogin = new ExtraWatchLogin($extraWatchCMSSpecific);
        $this->extraWatchProject = new ExtraWatchProject($extraWatchCMSSpecific);
        $this->extraWatchSettings = new ExtraWatchSettings($extraWatchCMSSpecific);
    }


    public function createAccountAndProject() {
        $nonceValue = @$this->extraWatchCmsSpecific->getSanitizedFromPOST(EXTRAWATCH_NONCE);
        if (!$this->extraWatchCmsSpecific->verifyNonce($nonceValue, EXTRAWATCH_NONCE)) {
            die("Not authorized");
        }
        $createAccountStatus = $this->extraWatchTempLogin->createAccountAndLogin();
        if ($createAccountStatus) {
            $this->createProjectForURLFromSettings();
            return true;
        }
        return false;
    }

    public function controlLivePage() {

        $extraWatchPreReq = new ExtraWatchPrerequisites();
        $preReqMessage = $extraWatchPreReq->prerequisiteCheck();
        if ($preReqMessage) {
            die($preReqMessage);
        }

        if($this->extraWatchSettings->isSettingsSaveTriggered()) {
            if (!$this->extraWatchSettings->saveSettings()) {
                if ($this->extraWatchSettings->isSettingsPage()) {
                    $this->extraWatchSettings->renderExtraWatchSettings();
                } else {
                    $this->extraWatchSettings->renderExtraWatchCreateAccount();
                }
                return false;
            }
        }

        if ($this->extraWatchSettings->isSettingsPage()) {
            $this->extraWatchSettings->renderExtraWatchSettings();
            return;
        }

        if ($this->extraWatchSettings->isFromCreateAccount()) {
            $status = $this->createAccountAndProject();
            if ($status) {
                $this->extraWatchSettings->resetStoredTempPasswordAndToken();
                echo $this->extraWatchRenderer->renderAccountCreated();
                return;
            } else {
                $email = $this->extraWatchCmsSpecific->getPluginOptionEmail();
                echo("<br/><span style='color: red'>Email already registered. Either enter another email, or <a href='https://app.extrawatch.com/#/pages/reset/init?email=".htmlentities($email)."' target='_blank' style='color: red'><b>reset password</b></a>.</span>");
                $this->extraWatchSettings->renderExtraWatchCreateAccount();
                return false;
            }
        }

        if (!$this->extraWatchSettings->isInitialSettingProvided()) {
            $this->extraWatchSettings->renderExtraWatchCreateAccount();
            return;
        }


        $from = base64_encode($this->extraWatchCmsSpecific->getPluginOptionEmail());
        return $this->extraWatchRenderer->renderIFrame($from);
    }

    public function controlTrackingCode() {
        $projectId = $this->extraWatchCmsSpecific->getPluginOptionProjectId();
        if ($projectId) {
            $this->extraWatchRenderer->renderTrackingCode($projectId);
        }
        return;
    }


    public function getExtraWatchURLHelper() {
        return $this->extraWatchURLHelper;
    }

    public function getExtraWatchRequestHelper() {
        return $this->extraWatchRequestHelper;
    }

    public function getExtraWatchRenderer() {
        return $this->extraWatchRenderer;
    }

    public function getExtraWatchTempLogin() {
        return $this->extraWatchTempLogin;
    }

    /**
     * @return array
     */
    public function createProjectForURLFromSettings() {
        $projectId = $this->extraWatchCmsSpecific->getPluginOptionProjectId();
        if (!$projectId) {
            $token = $this->extraWatchCmsSpecific->getPluginOptionToken();
            $url = $this->extraWatchCmsSpecific->getPluginOptionURL();
            $projectId = $this->extraWatchProject->createProjectForUrl($url, $token);
            $this->extraWatchCmsSpecific->savePluginOptionProjectId($projectId);
        }
        return $projectId;
    }


}