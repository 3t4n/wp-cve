<?php 
/**
	Admin Page Framework v3.9.0b10 by Michael Uno 
	Generated by PHP Class Files Script Generator <https://github.com/michaeluno/PHP-Class-Files-Script-Generator>
	<http://en.michaeluno.jp/task-scheduler>
	Copyright (c) 2013-2021, Michael Uno; Licensed under MIT <http://opensource.org/licenses/MIT> */
class TaskScheduler_AdminPageFramework_Model__FormSubmission__Validator__Filter extends TaskScheduler_AdminPageFramework_Model__FormSubmission_Base {
    public $oFactory;
    public $aInputs = array();
    public $aRawInputs = array();
    public $aOptions = array();
    public $aSubmitInformation = array();
    private $_bHasFieldErrors = false;
    public function __construct() {
        $_aParameters = func_get_args() + array($this->oFactory, $this->aInputs, $this->aRawInputs, $this->aOptions, $this->aSubmitInformation,);
        $this->oFactory = $_aParameters[0];
        $this->aInputs = $_aParameters[1];
        $this->aRawInputs = $_aParameters[2];
        $this->aOptions = $_aParameters[3];
        $this->aSubmitInformation = $_aParameters[4];
    }
    public function get() {
        return $this->_getFiltered($this->aInputs, $this->aRawInputs, $this->aOptions, $this->aSubmitInformation);
    }
    private function _getFiltered($aInputs, $aRawInputs, $aStoredData, $aSubmitInformation) {
        $_aData = array('sPageSlug' => $aSubmitInformation['page_slug'], 'sTabSlug' => $aSubmitInformation['tab_slug'], 'aInput' => $this->getAsArray($aInputs), 'aStoredData' => $aStoredData, 'aStoredTabData' => array(), 'aStoredDataWODynamicElements' => $this->addAndApplyFilter($this->oFactory, "validation_saved_options_without_dynamic_elements_{$this->oFactory->oProp->sClassName}", $this->oFactory->oForm->dropRepeatableElements($aStoredData), $this->oFactory), 'aStoredTabDataWODynamicElements' => array(), 'aEmbeddedDataWODynamicElements' => array(), 'aSubmitInformation' => $aSubmitInformation,);
        $_aData = $this->_validateEachField($_aData, $aRawInputs);
        $_aData = $this->_validateTabFields($_aData);
        $_aData = $this->_validatePageFields($_aData);
        $_aInput = $this->_getValidatedData("validation_{$this->oFactory->oProp->sClassName}", call_user_func_array(array($this->oFactory, 'validate'), array($_aData['aInput'], $_aData['aStoredData'], $this->oFactory, $_aData['aSubmitInformation'])), $_aData['aStoredData'], $_aData['aSubmitInformation']);
        $_aInput = $this->getAsArray($_aInput);
        $_aInput = $this->getInputsUnset($_aInput, $this->oFactory->oProp->sStructureType, 1);
        $this->_bHasFieldErrors = $this->oFactory->hasFieldError();
        if (!$this->_bHasFieldErrors) {
            return $_aInput;
        }
        $this->_setSettingNoticeAfterValidation(empty($_aInput));
        $this->oFactory->setLastInputs($aRawInputs);
        add_filter("options_update_status_{$this->oFactory->oProp->sClassName}", array($this, '_replyToSetStatus'));
        $_oException = new Exception('aReturn');
        $_oException->aReturn = $_aInput;
        throw $_oException;
    }
    public function _replyToSetStatus($aStatus) {
        return array('field_errors' => $this->_bHasFieldErrors,) + $aStatus;
    }
    private function _validateEachField(array $aData, array $aInputsToParse) {
        foreach ($aInputsToParse as $_sID => $_aSectionOrFields) {
            if ($this->oFactory->oForm->isSection($_sID)) {
                if (!$this->_isValidSection($_sID, $aData['sPageSlug'], $aData['sTabSlug'])) {
                    continue;
                }
                foreach ($_aSectionOrFields as $_sFieldID => $_aFields) {
                    $aData['aInput'][$_sID][$_sFieldID] = $this->_getValidatedData("validation_{$this->oFactory->oProp->sClassName}_{$_sID}_{$_sFieldID}", $aData['aInput'][$_sID][$_sFieldID], $this->getElement($aData, array('aStoredData', $_sID, $_sFieldID), null), $aData['aSubmitInformation']);
                }
                $_aSectionInput = is_array($aData['aInput'][$_sID]) ? $aData['aInput'][$_sID] : array();
                $_aSectionInput = $_aSectionInput + (isset($aData['aStoredDataWODynamicElements'][$_sID]) && is_array($aData['aStoredDataWODynamicElements'][$_sID]) ? $aData['aStoredDataWODynamicElements'][$_sID] : array());
                $aData['aInput'][$_sID] = $this->_getValidatedData("validation_{$this->oFactory->oProp->sClassName}_{$_sID}", $_aSectionInput, $this->getElement($aData, array('aStoredData', $_sID), null), $aData['aSubmitInformation']);
                continue;
            }
            if (!$this->_isValidSection('_default', $aData['sPageSlug'], $aData['sTabSlug'])) {
                continue;
            }
            $aData['aInput'][$_sID] = $this->_getValidatedData("validation_{$this->oFactory->oProp->sClassName}_{$_sID}", $aData['aInput'][$_sID], $this->getElement($aData, array('aStoredData', $_sID), null), $aData['aSubmitInformation']);
        }
        return $aData;
    }
    private function _isValidSection($sSectionID, $sPageSlug, $sTabSlug) {
        if ($sPageSlug && isset($this->oFactory->oForm->aSections[$sSectionID]['page_slug']) && $sPageSlug !== $this->oFactory->oForm->aSections[$sSectionID]['page_slug']) {
            return false;
        }
        if ($sTabSlug && isset($this->oFactory->oForm->aSections[$sSectionID]['tab_slug']) && $sTabSlug !== $this->oFactory->oForm->aSections[$sSectionID]['tab_slug']) {
            return false;
        }
        return true;
    }
    private function _validateTabFields(array $aData) {
        if (!$aData['sTabSlug'] || !$aData['sPageSlug']) {
            return $aData;
        }
        $aData['aStoredTabData'] = $this->oFactory->oForm->getTabOptions($aData['aStoredData'], $aData['sPageSlug'], $aData['sTabSlug']);
        $aData['aStoredTabData'] = $this->addAndApplyFilter($this->oFactory, "validation_saved_options_{$aData['sPageSlug']}_{$aData['sTabSlug']}", $aData['aStoredTabData'], $this->oFactory);
        $_aOtherTabOptions = $this->oFactory->oForm->getOtherTabOptions($aData['aStoredData'], $aData['sPageSlug'], $aData['sTabSlug']);
        $aData['aStoredTabDataWODynamicElements'] = $this->oFactory->oForm->getTabOptions($aData['aStoredDataWODynamicElements'], $aData['sPageSlug'], $aData['sTabSlug']);
        $aData['aStoredTabDataWODynamicElements'] = $this->addAndApplyFilter($this->oFactory, "validation_saved_options_without_dynamic_elements_{$aData['sPageSlug']}_{$aData['sTabSlug']}", $aData['aStoredTabDataWODynamicElements'], $this->oFactory);
        $aData['aStoredDataWODynamicElements'] = $aData['aStoredTabDataWODynamicElements'] + $aData['aStoredDataWODynamicElements'];
        $_aTabOnlyOptionsWODynamicElements = $this->oFactory->oForm->getTabOnlyOptions($aData['aStoredTabDataWODynamicElements'], $aData['sPageSlug'], $aData['sTabSlug']);
        $aData['aInput'] = $aData['aInput'] + $_aTabOnlyOptionsWODynamicElements;
        $aData['aInput'] = $this->_getValidatedData("validation_{$aData['sPageSlug']}_{$aData['sTabSlug']}", $aData['aInput'], $aData['aStoredTabData'], $aData['aSubmitInformation']);
        $aData['aEmbeddedDataWODynamicElements'] = $this->_getEmbeddedOptions($aData['aInput'], $aData['aStoredTabDataWODynamicElements'], $_aTabOnlyOptionsWODynamicElements);
        $aData['aInput'] = $aData['aInput'] + $_aOtherTabOptions;
        return $aData;
    }
    private function _validatePageFields(array $aData) {
        if (!$aData['sPageSlug']) {
            return $aData['aInput'];
        }
        $_aPageOptions = $this->oFactory->oForm->getPageOptions($aData['aStoredData'], $aData['sPageSlug']);
        $_aPageOptions = $this->addAndApplyFilter($this->oFactory, "validation_saved_options_{$aData['sPageSlug']}", $_aPageOptions, $this->oFactory);
        $_aOtherPageOptions = $this->invertCastArrayContents($this->oFactory->oForm->getOtherPageOptions($aData['aStoredData'], $aData['sPageSlug']), $_aPageOptions);
        $_aPageOptionsWODynamicElements = $this->addAndApplyFilter($this->oFactory, "validation_saved_options_without_dynamic_elements_{$aData['sPageSlug']}", $this->oFactory->oForm->getPageOptions($aData['aStoredDataWODynamicElements'], $aData['sPageSlug']), $this->oFactory);
        $_aPageOnlyOptionsWODynamicElements = $this->oFactory->oForm->getPageOnlyOptions($_aPageOptionsWODynamicElements, $aData['sPageSlug']);
        $aData['aInput'] = $aData['aInput'] + $_aPageOnlyOptionsWODynamicElements;
        $aData['aInput'] = $this->_getValidatedData("validation_{$aData['sPageSlug']}", $aData['aInput'], $_aPageOptions, $aData['aSubmitInformation']);
        $_aPageOptions = $aData['sTabSlug'] && !empty($aData['aStoredTabData']) ? $this->invertCastArrayContents($_aPageOptions, $aData['aStoredTabData']) : (!$aData['sTabSlug'] ? array() : $_aPageOptions);
        $_aEmbeddedOptionsWODynamicElements = $aData['aEmbeddedDataWODynamicElements'] + $this->_getEmbeddedOptions($aData['aInput'], $_aPageOptionsWODynamicElements, $_aPageOnlyOptionsWODynamicElements);
        $aData['aInput'] = $aData['aInput'] + $this->uniteArrays($_aPageOptions, $_aOtherPageOptions, $_aEmbeddedOptionsWODynamicElements);
        return $aData;
    }
    private function _getEmbeddedOptions(array $aInputs, array $aOptions, array $aPageSpecificOptions) {
        $_aEmbeddedData = $this->invertCastArrayContents($aOptions, $aPageSpecificOptions);
        return $this->invertCastArrayContents($_aEmbeddedData, $aInputs);
    }
    private function _getValidatedData($sFilterName, $aInputs, $aStoredData, $aSubmitInfo = array()) {
        return $this->addAndApplyFilter($this->oFactory, $sFilterName, $aInputs, $aStoredData, $this->oFactory, $aSubmitInfo);
    }
    }
    