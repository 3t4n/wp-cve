<?php 
/**
	Admin Page Framework v3.9.0b10 by Michael Uno 
	Generated by PHP Class Files Script Generator <https://github.com/michaeluno/PHP-Class-Files-Script-Generator>
	<http://en.michaeluno.jp/task-scheduler>
	Copyright (c) 2013-2021, Michael Uno; Licensed under MIT <http://opensource.org/licenses/MIT> */
abstract class TaskScheduler_AdminPageFramework_Model_Page extends TaskScheduler_AdminPageFramework_Controller_Form {
    public function _replyToFinalizeInPageTabs() {
        if (!$this->oProp->isPageAdded()) {
            return;
        }
        foreach ($this->oProp->aPages as $_sPageSlug => $_aPage) {
            if (!isset($this->oProp->aInPageTabs[$_sPageSlug])) {
                continue;
            }
            $_oFormatter = new TaskScheduler_AdminPageFramework_Format_InPageTabs($this->oProp->aInPageTabs[$_sPageSlug], $_sPageSlug, $this);
            $this->oProp->aInPageTabs[$_sPageSlug] = $_oFormatter->get();
            $this->oProp->aDefaultInPageTabs[$_sPageSlug] = $this->_getDefaultInPageTab($_sPageSlug, $this->oProp->aInPageTabs[$_sPageSlug]);
        }
    }
    protected function _finalizeInPageTabs() {
        $this->_replyToFinalizeInPageTabs();
    }
    private function _getDefaultInPageTab($sPageSlug, $aInPageTabs) {
        foreach ($aInPageTabs as $_aInPageTab) {
            if (!isset($_aInPageTab['tab_slug'])) {
                continue;
            }
            return $_aInPageTab['tab_slug'];
        }
    }
    public function _getPageCapability($sPageSlug) {
        return $this->oUtil->getElement($this->oProp->aPages, array($sPageSlug, 'capability'));
    }
    public function _getInPageTabCapability($sTabSlug, $sPageSlug) {
        return $this->oUtil->getElement($this->oProp->aInPageTabs, array($sPageSlug, $sTabSlug, 'capability'));
    }
    }
    