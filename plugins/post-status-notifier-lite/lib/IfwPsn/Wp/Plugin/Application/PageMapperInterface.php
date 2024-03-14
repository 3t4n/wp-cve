<?php
/**
 * ifeelweb.de WordPress Plugin Framework
 * For more information see http://www.ifeelweb.de/wp-plugin-framework
 * 
 * 
 *
 * @author    Timo Reith <timo@ifeelweb.de>
 * @copyright Copyright (c) ifeelweb.de
 * @version   $Id: PageMapperInterface.php 911603 2014-05-10 10:58:23Z worschtebrot $
 * @package   
 */ 
interface IfwPsn_Wp_Plugin_Application_PageMapperInterface
{
    /**
     * Represents the callback function of add_options_page, add_menu_page and add_submenu_page
     *
     * @param IfwPsn_Wp_Plugin_Menu_Page_Interface $page
     * @return mixed
     */
    public function handlePage(IfwPsn_Wp_Plugin_Menu_Page_Interface $page);
}
