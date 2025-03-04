<?php 
/**
	Admin Page Framework v3.8.20 by Michael Uno 
	Generated by PHP Class Files Script Generator <https://github.com/michaeluno/PHP-Class-Files-Script-Generator>
	<http://en.michaeluno.jp/admin-page-framework>
	Copyright (c) 2013-2019, Michael Uno; Licensed under MIT <http://opensource.org/licenses/MIT> */
class AdminPageFramework_Form_Model___BuiltInFieldTypeDefinitions {
    static protected $_aDefaultFieldTypeSlugs = array('default', 'text', 'number', 'textarea', 'radio', 'checkbox', 'select', 'hidden', 'file', 'submit', 'import', 'export', 'image', 'media', 'color', 'taxonomy', 'posttype', 'size', 'section_title', 'system', 'inline_mixed', '_nested',);
    public $sCallerID = '';
    public $oMsg;
    public function __construct($sCallerID, $oMsg) {
        $this->sCallerID = $sCallerID;
        $this->oMsg = $oMsg;
    }
    public function get() {
        $_aFieldTypeDefinitions = array();
        foreach (self::$_aDefaultFieldTypeSlugs as $_sFieldTypeSlug) {
            $_sFieldTypeClassName = "AdminPageFramework_FieldType_{$_sFieldTypeSlug}";
            $_oFieldType = new $_sFieldTypeClassName($this->sCallerID, null, $this->oMsg, false);
            foreach ($_oFieldType->aFieldTypeSlugs as $_sSlug) {
                $_aFieldTypeDefinitions[$_sSlug] = $_oFieldType->getDefinitionArray();
            }
        }
        return $_aFieldTypeDefinitions;
    }
    }
    