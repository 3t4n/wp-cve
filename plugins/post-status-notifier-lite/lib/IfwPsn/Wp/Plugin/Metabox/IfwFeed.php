<?php
/**
 * ifeelweb.de WordPress Plugin Framework
 * For more information see http://www.ifeelweb.de/wp-plugin-framework
 * 
 * 
 *
 * @author   Timo Reith <timo@ifeelweb.de>
 * @version  $Id: IfwFeed.php 1248505 2015-09-18 13:49:54Z worschtebrot $
 * @package  IfwPsn_Wp
 */
class IfwPsn_Wp_Plugin_Metabox_IfwFeed extends IfwPsn_Wp_Plugin_Metabox_Ajax
{
    /**
     * @param IfwPsn_Wp_Plugin_Manager $pm
     * @param null $ajaxRequest
     */
    public function __construct(IfwPsn_Wp_Plugin_Manager $pm, $ajaxRequest = null)
    {
        $ajaxRequest = new IfwPsn_Wp_Plugin_Metabox_IfwFeedAjax($pm, 'http://www.ifeelweb.de/?feed=rss2');

        parent::__construct($pm, $ajaxRequest);
    }

    /**
     * (non-PHPdoc)
     * @see IfwPsn_Wp_Plugin_Admin_Menu_Metabox_Abstract::_initId()
     */
    protected function _initId()
    {
        return 'ifeelweb_de';
    }
    
    /**
     * (non-PHPdoc)
     * @see IfwPsn_Wp_Plugin_Admin_Menu_Metabox_Abstract::_initTitle()
     */
    protected function _initTitle()
    {
        return 'www.ifeelweb.de Feed';
    }
    
    /**
     * (non-PHPdoc)
     * @see IfwPsn_Wp_Plugin_Admin_Menu_Metabox_Abstract::_initPriority()
     */
    protected function _initPriority()
    {
        return 'core';
    }
}
