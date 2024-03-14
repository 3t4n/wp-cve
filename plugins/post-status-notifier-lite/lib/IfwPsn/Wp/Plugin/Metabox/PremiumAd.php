<?php
/**
 * ifeelweb.de WordPress Plugin Framework
 * For more information see http://www.ifeelweb.de/wp-plugin-framework
 *
 * Premium ad metabox
 *
 * @author   Timo Reith <timo@ifeelweb.de>
 * @version  $Id: PremiumAd.php 911603 2014-05-10 10:58:23Z worschtebrot $
 * @package  IfwPsn_Wp
 */
require_once dirname(__FILE__) . '/Abstract.php';

class IfwPsn_Wp_Plugin_Metabox_PremiumAd extends IfwPsn_Wp_Plugin_Metabox_Abstract
{
    /**
     * (non-PHPdoc)
     * @see IfwPsn_Wp_Plugin_Admin_Menu_Metabox_Abstract::_initId()
     */
    protected function _initId()
    {
        return 'premium-ad';
    }
    
    /**
     * (non-PHPdoc)
     * @see IfwPsn_Wp_Plugin_Admin_Menu_Metabox_Abstract::_initTitle()
     */
    protected function _initTitle()
    {
        return __('Go Premium!', 'ifw');
    }
    
    /**
     * (non-PHPdoc)
     * @see IfwPsn_Wp_Plugin_Admin_Menu_Metabox_Abstract::_initPriority()
     */
    protected function _initPriority()
    {
        return 'core';
    }

    /**
     * @see IfwPsn_Wp_Plugin_Admin_Menu_Metabox_Abstract::render()
     */
    public function render()
    {
        $tpl = IfwPsn_Wp_Tpl::getInstance($this->_pm);
        $tpl->display('premium_ad.html.twig', array(
            'plugin_homepage' => $this->_pm->getEnv()->getHomepage(),
            'premium_url' => $this->_pm->getConfig()->plugin->premiumUrl,
        ));
    }
}
