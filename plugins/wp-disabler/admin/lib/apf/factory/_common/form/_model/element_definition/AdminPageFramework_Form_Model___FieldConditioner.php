<?php 
/**
	Admin Page Framework v3.8.20 by Michael Uno 
	Generated by PHP Class Files Script Generator <https://github.com/michaeluno/PHP-Class-Files-Script-Generator>
	<http://en.michaeluno.jp/admin-page-framework>
	Copyright (c) 2013-2019, Michael Uno; Licensed under MIT <http://opensource.org/licenses/MIT> */
class AdminPageFramework_Form_Model___SectionConditioner extends AdminPageFramework_FrameworkUtility {
    public $aSectionsets = array();
    public function __construct() {
        $_aParameters = func_get_args() + array($this->aSectionsets,);
        $this->aSectionsets = $_aParameters[0];
    }
    public function get() {
        return $this->_getSectionsConditioned($this->aSectionsets);
    }
    private function _getSectionsConditioned(array $aSections = array()) {
        $_aNewSections = array();
        foreach ($aSections as $_sSectionID => $_aSection) {
            if (!$this->_isAllowed($_aSection)) {
                continue;
            }
            $_aNewSections[$_sSectionID] = $_aSection;
        }
        return $_aNewSections;
    }
    protected function _isAllowed(array $aDefinition) {
        if (!current_user_can($aDefinition['capability'])) {
            return false;
        }
        return ( boolean )$aDefinition['if'];
    }
    }
    class AdminPageFramework_Form_Model___FieldConditioner extends AdminPageFramework_Form_Model___SectionConditioner {
        public $aSectionsets = array();
        public $aFieldsets = array();
        public function __construct() {
            $_aParameters = func_get_args() + array($this->aSectionsets, $this->aFieldsets,);
            $this->aSectionsets = $_aParameters[0];
            $this->aFieldsets = $_aParameters[1];
        }
        public function get() {
            return $this->_getFieldsConditioned($this->aFieldsets, $this->aSectionsets);
        }
        private function _getFieldsConditioned(array $aFields, array $aSections) {
            $aFields = $this->castArrayContents($aSections, $aFields);
            $_aNewFields = array();
            foreach ($aFields as $_sSectionID => $_aSubSectionOrFields) {
                if (!is_array($_aSubSectionOrFields)) {
                    continue;
                }
                $this->_setConditionedFields($_aNewFields, $_aSubSectionOrFields, $_sSectionID);
            }
            return $_aNewFields;
        }
        private function _setConditionedFields(array & $_aNewFields, $_aSubSectionOrFields, $_sSectionID) {
            foreach ($_aSubSectionOrFields as $_sIndexOrFieldID => $_aSubSectionOrField) {
                if ($this->isNumericInteger($_sIndexOrFieldID)) {
                    $_sSubSectionIndex = $_sIndexOrFieldID;
                    $_aFields = $_aSubSectionOrField;
                    foreach ($_aFields as $_aField) {
                        if (!$this->_isAllowed($_aField)) {
                            continue;
                        }
                        $_aNewFields[$_sSectionID][$_sSubSectionIndex][$_aField['field_id']] = $_aField;
                    }
                    continue;
                }
                $_aField = $_aSubSectionOrField;
                if (!$this->_isAllowed($_aField)) {
                    continue;
                }
                $_aNewFields[$_sSectionID][$_aField['field_id']] = $_aField;
            }
        }
    }
    