<?php 
/**
	Admin Page Framework v3.9.0b10 by Michael Uno 
	Generated by PHP Class Files Script Generator <https://github.com/michaeluno/PHP-Class-Files-Script-Generator>
	<http://en.michaeluno.jp/task-scheduler>
	Copyright (c) 2013-2021, Michael Uno; Licensed under MIT <http://opensource.org/licenses/MIT> */
class TaskScheduler_AdminPageFramework_Model__FormSubmission__Validator_Base extends TaskScheduler_AdminPageFramework_Model__FormSubmission_Base {
    public $oFactory;
    public $sHookType = 'action';
    public $sActionHookPrefix = 'try_validation_before_';
    public $iHookPriority = 10;
    public $iCallbackParameters = 5;
    public $sCallbackName = '_replyToCallback';
    public function __construct($oFactory) {
        $this->oFactory = $oFactory;
        $_sFunctionName = 'action' === $this->sHookType ? 'add_action' : 'add_filter';
        $_sFunctionName($this->sActionHookPrefix . $this->oFactory->oProp->sClassName, array($this, $this->sCallbackName), $this->iHookPriority, $this->iCallbackParameters);
    }
    protected function _confirmSubmitButtonAction($sPressedInputName, $sSectionID, $sType = 'reset') {
        switch ($sType) {
            default:
            case 'reset':
                $_sFieldErrorMessage = $this->oFactory->oMsg->get('reset_options');
                $_sTransientKey = 'apf_rc_' . md5($sPressedInputName . get_current_user_id());
            break;
            case 'email':
                $_sFieldErrorMessage = $this->oFactory->oMsg->get('send_email');
                $_sTransientKey = 'apf_ec_' . md5($sPressedInputName . get_current_user_id());
            break;
        }
        $_aNameKeys = explode('|', $sPressedInputName) + array('', '', '');
        $_sFieldID = $this->getAOrB($sSectionID, $_aNameKeys[2], $_aNameKeys[1]);
        $_aErrors = array();
        if ($sSectionID && $_sFieldID) {
            $_aErrors[$sSectionID][$_sFieldID] = $_sFieldErrorMessage;
        } else if ($_sFieldID) {
            $_aErrors[$_sFieldID] = $_sFieldErrorMessage;
        }
        $this->oFactory->setFieldErrors($_aErrors);
        $this->setTransient($_sTransientKey, $sPressedInputName, 60 * 2);
        $this->oFactory->setSettingNotice($this->oFactory->oMsg->get('confirm_perform_task'), 'error confirmation');
        return $this->oFactory->oProp->aOptions;
    }
    }
    