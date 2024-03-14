<?php
/**
 * ifeelweb.de WordPress Plugin Framework
 * For more information see http://www.ifeelweb.de/wp-plugin-framework
 * 
 * 
 *
 * @author    Timo Reith <timo@ifeelweb.de>
 * @copyright Copyright (c) ifeelweb.de
 * @version   $Id: Interface.php 911603 2014-05-10 10:58:23Z worschtebrot $
 * @package   
 */
interface IfwPsn_Wp_Plugin_Menu_Page_Interface 
{
    public function getSlug();
    public function getPageHook();
    public function onLoad();
    public function handle();
}
