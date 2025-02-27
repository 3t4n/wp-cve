<?php 
/**
	Admin Page Framework v3.8.20 by Michael Uno 
	Generated by PHP Class Files Script Generator <https://github.com/michaeluno/PHP-Class-Files-Script-Generator>
	<http://en.michaeluno.jp/admin-page-framework>
	Copyright (c) 2013-2019, Michael Uno; Licensed under MIT <http://opensource.org/licenses/MIT> */
abstract class AdminPageFramework_Form_View___Generate_Field_Base extends AdminPageFramework_Form_View___Generate_Section_Base {
    public $aArguments = array();
    protected function _isSectionSet() {
        return isset($this->aArguments['section_id']) && $this->aArguments['section_id'] && '_default' !== $this->aArguments['section_id'];
    }
    }
    class AdminPageFramework_Form_View___Generate_FieldName extends AdminPageFramework_Form_View___Generate_Field_Base {
        public function get() {
            $_sResult = $this->_getFiltered($this->_getFieldName());
            return $_sResult;
        }
        public function getModel() {
            return $this->get() . '[' . $this->sIndexMark . ']';
        }
        protected function _getFieldName() {
            $_aFieldPath = $this->aArguments['_field_path_array'];
            if (!$this->_isSectionSet()) {
                return $this->_getInputNameConstructed($_aFieldPath);
            }
            $_aSectionPath = $this->aArguments['_section_path_array'];
            if ($this->_isSectionSet() && isset($this->aArguments['_section_index'])) {
                $_aSectionPath[] = $this->aArguments['_section_index'];
            }
            $_sFieldName = $this->_getInputNameConstructed(array_merge($_aSectionPath, $_aFieldPath));
            return $_sFieldName;
        }
    }
    class AdminPageFramework_Form_View___Generate_FlatFieldName extends AdminPageFramework_Form_View___Generate_FieldName {
        public function get() {
            return $this->_getFiltered($this->_getFlatFieldName());
        }
        public function getModel() {
            return $this->get() . '|' . $this->sIndexMark;
        }
        protected function _getFlatFieldName() {
            $_sSectionIndex = isset($this->aArguments['section_id'], $this->aArguments['_section_index']) ? "|{$this->aArguments['_section_index']}" : '';
            return $this->getAOrB($this->_isSectionSet(), "{$this->aArguments['_section_path']}{$_sSectionIndex}|{$this->aArguments['_field_path']}", "{$this->aArguments['_field_path']}");
        }
    }
    class AdminPageFramework_Form_View___Generate_FieldAddress extends AdminPageFramework_Form_View___Generate_FlatFieldName {
        public function get() {
            return $this->_getFlatFieldName();
        }
        public function getModel() {
            return $this->get() . '|' . $this->sIndexMark;
        }
    }
    