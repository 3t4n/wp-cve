<?php 
/**
	Admin Page Framework v3.9.0b10 by Michael Uno 
	Generated by PHP Class Files Script Generator <https://github.com/michaeluno/PHP-Class-Files-Script-Generator>
	<http://en.michaeluno.jp/task-scheduler>
	Copyright (c) 2013-2021, Michael Uno; Licensed under MIT <http://opensource.org/licenses/MIT> */
class TaskScheduler_AdminPageFramework_PostType_Model__SubMenuOrder extends TaskScheduler_AdminPageFramework_FrameworkUtility {
    public $oFactory;
    public function __construct($oFactory) {
        $this->oFactory = $oFactory;
        if (!$oFactory->oProp->bIsAdmin) {
            return;
        }
        add_action('admin_menu', array($this, '_replyToSetSubMenuOrder'), 200);
        add_action('admin_menu', array($this, 'sortAdminSubMenu'), 9999);
    }
    public function _replyToSetSubMenuOrder() {
        $_bsShowInMeenu = $this->getShowInMenuPostTypeArgument($this->oFactory->oProp->aPostTypeArgs);
        if (!$_bsShowInMeenu) {
            return;
        }
        $_sSubMenuSlug = is_string($_bsShowInMeenu) ? $_bsShowInMeenu : 'edit.php?post_type=' . $this->oFactory->oProp->sPostType;
        $this->_setSubMenuSlugForSorting($_sSubMenuSlug);
        $this->_setSubMenuItemIndex($_sSubMenuSlug);
    }
    private function _setSubMenuSlugForSorting($sSubMenuSlug) {
        $GLOBALS['_apf_sub_menus_to_sort'] = isset($GLOBALS['_apf_sub_menus_to_sort']) ? $GLOBALS['_apf_sub_menus_to_sort'] : array();
        $GLOBALS['_apf_sub_menus_to_sort'][$sSubMenuSlug] = $sSubMenuSlug;
    }
    private function _setSubMenuItemIndex($sSubMenuSlug) {
        $this->_setSubMenuIndexByLinksSlugs($sSubMenuSlug, $this->_getPostTypeMenuLinkSlugs() + $this->oFactory->oProp->aTaxonomySubMenuOrder);
    }
    private function _getPostTypeMenuLinkSlugs() {
        $_nSubMenuOrderManage = $this->getElement($this->oFactory->oProp->aPostTypeArgs, 'submenu_order_manage', 5);
        $_bShowAddNew = $this->getElement($this->oFactory->oProp->aPostTypeArgs, 'show_submenu_add_new', true);
        $_nSubMenuOrderAddNew = $this->getElement($this->oFactory->oProp->aPostTypeArgs, 'submenu_order_addnew', 10);
        $_sLinkSlugManage = 'edit.php?post_type=' . $this->oFactory->oProp->sPostType;
        $_aLinkSlugs = array($_sLinkSlugManage => $_nSubMenuOrderManage, 'post-new.php?post_type=' . $this->oFactory->oProp->sPostType => $_nSubMenuOrderAddNew,);
        if (5 == $_nSubMenuOrderManage) {
            unset($_aLinkSlugs[$_sLinkSlugManage]);
        }
        if (!$_bShowAddNew || 10 == $_nSubMenuOrderAddNew) {
            unset($_aLinkSlugs['post-new.php?post_type=' . $this->oFactory->oProp->sPostType]);
        }
        return $_aLinkSlugs;
    }
    private function _setSubMenuIndexByLinksSlugs($sSubMenuSlug, array $aLinkSlugs) {
        foreach ($this->getElementAsArray($GLOBALS, array('submenu', $sSubMenuSlug)) as $_nIndex => $_aSubMenuItem) {
            foreach ($aLinkSlugs as $_sLinkSlug => $_nOrder) {
                $_bIsSet = $this->_setSubMenuIndexByLinksSlug($sSubMenuSlug, $_nIndex, $_aSubMenuItem, $_sLinkSlug, $_nOrder);
                if ($_bIsSet) {
                    unset($aLinkSlugs[$_sLinkSlug]);
                }
            }
        }
    }
    private function _setSubMenuIndexByLinksSlug($sSubMenuSlug, $nIndex, $aSubMenuItem, $sLinkSlug, $nOrder) {
        if (!isset($aSubMenuItem[2])) {
            return false;
        }
        if ($aSubMenuItem[2] !== $sLinkSlug) {
            return false;
        }
        unset($GLOBALS['submenu'][$sSubMenuSlug][$nIndex]);
        $_nNewIndex = $this->getUnusedNumericIndex($this->getElementAsArray($GLOBALS, array('submenu', $sSubMenuSlug)), $nOrder);
        $GLOBALS['submenu'][$sSubMenuSlug][$_nNewIndex] = $aSubMenuItem;
        return true;
    }
    }
    