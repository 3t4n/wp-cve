<?php 
/**
	Admin Page Framework v3.8.20 by Michael Uno 
	Generated by PHP Class Files Script Generator <https://github.com/michaeluno/PHP-Class-Files-Script-Generator>
	<http://en.michaeluno.jp/admin-page-framework>
	Copyright (c) 2013-2019, Michael Uno; Licensed under MIT <http://opensource.org/licenses/MIT> */
abstract class AdminPageFramework_TaxonomyField_Router extends AdminPageFramework_Factory {
    public function __construct($oProp) {
        parent::__construct($oProp);
        if (!$this->oProp->bIsAdmin) {
            return;
        }
        $this->oUtil->registerAction('wp_loaded', array($this, '_replyToDetermineToLoad'));
        add_action('set_up_' . $this->oProp->sClassName, array($this, '_replyToSetUpHooks'));
    }
    protected function _isInThePage() {
        if (!$this->oProp->bIsAdmin) {
            return false;
        }
        if ($this->oProp->bIsAdminAjax) {
            return $this->_isValidAjaxReferrer();
        }
        if (!in_array($this->oProp->sPageNow, array('edit-tags.php', 'term.php'))) {
            return false;
        }
        if (isset($_GET['taxonomy']) && !in_array($_GET['taxonomy'], $this->oProp->aTaxonomySlugs)) {
            return false;
        }
        return true;
    }
    protected function _isValidAjaxReferrer() {
        $_aReferrer = parse_url($this->oProp->sAjaxReferrer) + array('query' => '', 'path' => '');
        parse_str($_aReferrer['query'], $_aQuery);
        $_sBaseName = basename($_aReferrer['path']);
        if (!in_array($_sBaseName, array('edit-tags.php', 'term.php'))) {
            return false;
        }
        $_sTaxonomy = $this->oUtil->getElement($this->oProp->aQuery, array('taxonomy'), '');
        return in_array($_sTaxonomy, $this->oProp->aTaxonomySlugs);
    }
    public function _replyToSetUpHooks($oFactory) {
        foreach ($this->oProp->aTaxonomySlugs as $_sTaxonomySlug) {
            add_action("created_{$_sTaxonomySlug}", array($this, '_replyToValidateOptions'), 10, 2);
            add_action("edited_{$_sTaxonomySlug}", array($this, '_replyToValidateOptions'), 10, 2);
            add_action("{$_sTaxonomySlug}_add_form_fields", array($this, '_replyToPrintFieldsWOTableRows'));
            add_action("{$_sTaxonomySlug}_edit_form_fields", array($this, '_replyToPrintFieldsWithTableRows'));
            add_filter("manage_edit-{$_sTaxonomySlug}_columns", array($this, '_replyToManageColumns'), 10, 1);
            add_filter("manage_edit-{$_sTaxonomySlug}_sortable_columns", array($this, '_replyToSetSortableColumns'));
            add_action("manage_{$_sTaxonomySlug}_custom_column", array($this, '_replyToPrintColumnCell'), 10, 3);
        }
        $this->_load();
    }
    }
    abstract class AdminPageFramework_TaxonomyField_Model extends AdminPageFramework_TaxonomyField_Router {
        public function _replyToManageColumns($aColumns) {
            return $this->_getFilteredColumnsByFilterPrefix($this->oUtil->getAsArray($aColumns), 'columns_', isset($_GET['taxonomy']) ? $_GET['taxonomy'] : '');
        }
        public function _replyToSetSortableColumns($aSortableColumns) {
            return $this->_getFilteredColumnsByFilterPrefix($this->oUtil->getAsArray($aSortableColumns), 'sortable_columns_', isset($_GET['taxonomy']) ? $_GET['taxonomy'] : '');
        }
        private function _getFilteredColumnsByFilterPrefix(array $aColumns, $sFilterPrefix, $sTaxonomy) {
            if ($sTaxonomy) {
                $aColumns = $this->oUtil->addAndApplyFilter($this, "{$sFilterPrefix}{$_GET['taxonomy']}", $aColumns);
            }
            return $this->oUtil->addAndApplyFilter($this, "{$sFilterPrefix}{$this->oProp->sClassName}", $aColumns);
        }
        public function _replyToGetSavedFormData() {
            return array();
        }
        protected function _setOptionArray($iTermID = null, $sOptionKey) {
            $this->oForm->aSavedData = $this->_getSavedFormData($iTermID, $sOptionKey);
        }
        private function _getSavedFormData($iTermID, $sOptionKey) {
            return $this->oUtil->addAndApplyFilter($this, 'options_' . $this->oProp->sClassName, $this->_getSavedTermFormData($iTermID, $sOptionKey));
        }
        private function _getSavedTermFormData($iTermID, $sOptionKey) {
            $_aSavedTaxonomyFormData = $this->_getSavedTaxonomyFormData($sOptionKey);
            return $this->oUtil->getElementAsArray($_aSavedTaxonomyFormData, $iTermID);
        }
        private function _getSavedTaxonomyFormData($sOptionKey) {
            return get_option($sOptionKey, array());
        }
        public function _replyToValidateOptions($iTermID) {
            if (!$this->_shouldProceedValidation()) {
                return;
            }
            $_aTaxonomyFormData = $this->_getSavedTaxonomyFormData($this->oProp->sOptionKey);
            $_aSavedFormData = $this->_getSavedTermFormData($iTermID, $this->oProp->sOptionKey);
            $_aSubmittedFormData = $this->oForm->getSubmittedData($_POST);
            $_aSubmittedFormData = $this->oUtil->addAndApplyFilters($this, 'validation_' . $this->oProp->sClassName, call_user_func_array(array($this, 'validate'), array($_aSubmittedFormData, $_aSavedFormData, $this)), $_aSavedFormData, $this);
            $_aTaxonomyFormData[$iTermID] = $this->oUtil->uniteArrays($_aSubmittedFormData, $_aSavedFormData);
            update_option($this->oProp->sOptionKey, $_aTaxonomyFormData);
        }
        protected function _shouldProceedValidation() {
            if (!isset($_POST[$this->oProp->sClassHash])) {
                return false;
            }
            if (!wp_verify_nonce($_POST[$this->oProp->sClassHash], $this->oProp->sClassHash)) {
                return false;
            }
            return true;
        }
    }
    abstract class AdminPageFramework_TaxonomyField_View extends AdminPageFramework_TaxonomyField_Model {
        public function content($sContent) {
            return $sContent;
        }
        public function _replyToGetInputNameAttribute() {
            $_aParams = func_get_args() + array(null, null, null);
            $_aField = $_aParams[1];
            $_sKey = ( string )$_aParams[2];
            $_sKey = $this->oUtil->getAOrB('0' !== $_sKey && empty($_sKey), '', "[{$_sKey}]");
            return $_aField['field_id'] . $_sKey;
        }
        public function _replyToGetFlatInputName() {
            $_aParams = func_get_args() + array(null, null, null);
            $_aField = $_aParams[1];
            $_sKey = ( string )$_aParams[2];
            $_sKey = $this->oUtil->getAOrB('0' !== $_sKey && empty($_sKey), '', "|{$_sKey}");
            return "{$_aField['field_id']}{$_sKey}";
        }
        public function _replyToPrintFieldsWOTableRows($oTerm) {
            echo $this->_getFieldsOutput(isset($oTerm->term_id) ? $oTerm->term_id : null, false);
        }
        public function _replyToPrintFieldsWithTableRows($oTerm) {
            echo $this->_getFieldsOutput(isset($oTerm->term_id) ? $oTerm->term_id : null, true);
        }
        private function _getFieldsOutput($iTermID, $bRenderTableRow) {
            $_aOutput = array();
            $_aOutput[] = wp_nonce_field($this->oProp->sClassHash, $this->oProp->sClassHash, true, false);
            $this->_setOptionArray($iTermID, $this->oProp->sOptionKey);
            $_aOutput[] = $this->oForm->get($bRenderTableRow);
            $_sOutput = $this->oUtil->addAndApplyFilters($this, 'content_' . $this->oProp->sClassName, $this->content(implode(PHP_EOL, $_aOutput)));
            $this->oUtil->addAndDoActions($this, 'do_' . $this->oProp->sClassName, $this);
            return $_sOutput;
        }
        public function _replyToPrintColumnCell($vValue, $sColumnSlug, $sTermID) {
            $_sCellHTML = '';
            if (isset($_GET['taxonomy']) && $_GET['taxonomy']) {
                $_sCellHTML = $this->oUtil->addAndApplyFilter($this, "cell_{$_GET['taxonomy']}", $vValue, $sColumnSlug, $sTermID);
            }
            $_sCellHTML = $this->oUtil->addAndApplyFilter($this, "cell_{$this->oProp->sClassName}", $_sCellHTML, $sColumnSlug, $sTermID);
            $_sCellHTML = $this->oUtil->addAndApplyFilter($this, "cell_{$this->oProp->sClassName}_{$sColumnSlug}", $_sCellHTML, $sTermID);
            echo $_sCellHTML;
        }
    }
    abstract class AdminPageFramework_TaxonomyField_Controller extends AdminPageFramework_TaxonomyField_View {
        public function setUp() {
        }
    }
    abstract class AdminPageFramework_TaxonomyField extends AdminPageFramework_TaxonomyField_Controller {
        protected $_sStructureType = 'taxonomy_field';
        function __construct($asTaxonomySlug, $sOptionKey = '', $sCapability = 'manage_options', $sTextDomain = 'admin-page-framework') {
            if (empty($asTaxonomySlug)) {
                return;
            }
            $_sProprtyClassName = isset($this->aSubClassNames['oProp']) ? $this->aSubClassNames['oProp'] : 'AdminPageFramework_Property_' . $this->_sStructureType;
            $this->oProp = new $_sProprtyClassName($this, get_class($this), $sCapability, $sTextDomain, $this->_sStructureType);
            $this->oProp->aTaxonomySlugs = ( array )$asTaxonomySlug;
            $this->oProp->sOptionKey = $sOptionKey ? $sOptionKey : $this->oProp->sClassName;
            parent::__construct($this->oProp);
        }
    }
    