<?php 
/**
	Admin Page Framework v3.8.20 by Michael Uno 
	Generated by PHP Class Files Script Generator <https://github.com/michaeluno/PHP-Class-Files-Script-Generator>
	<http://en.michaeluno.jp/admin-page-framework>
	Copyright (c) 2013-2019, Michael Uno; Licensed under MIT <http://opensource.org/licenses/MIT> */
class AdminPageFramework_Form_View__Resource__Head extends AdminPageFramework_FrameworkUtility {
    public $oForm;
    public function __construct($oForm, $sHeadActionHook = 'admin_head') {
        $this->oForm = $oForm;
        if (in_array($this->oForm->aArguments['structure_type'], array('widget'))) {
            return;
        }
        add_action($sHeadActionHook, array($this, '_replyToInsertRequiredInternalScripts'));
    }
    public function _replyToInsertRequiredInternalScripts() {
        if (!$this->oForm->isInThePage()) {
            return;
        }
        if ($this->hasBeenCalled(__METHOD__)) {
            return;
        }
        echo "<script type='text/javascript' class='admin-page-framework-form-script-required-in-head'>" . '/* <![CDATA[ */ ' . $this->_getScripts_RequiredInHead() . ' /* ]]> */' . "</script>";
    }
    private function _getScripts_RequiredInHead() {
        return 'document.write( "<style class=\'admin-page-framework-js-embedded-internal-style\'>' . str_replace('\\n', '', esc_js($this->_getInternalCSS())) . '</style>" );';
    }
    private function _getInternalCSS() {
        $_oLoadingCSS = new AdminPageFramework_Form_View___CSS_Loading;
        $_oLoadingCSS->add($this->_getScriptElementConcealerCSSRules());
        return $_oLoadingCSS->get();
    }
    private function _getScriptElementConcealerCSSRules() {
        return ".admin-page-framework-form-js-on {visibility: hidden;}.widget .admin-page-framework-form-js-on { visibility: visible; }";
    }
    }
    