<?php
/**
 * ifeelweb.de WordPress Plugin Framework
 * For more information see http://www.ifeelweb.de/wp-plugin-framework
 * 
 * IfwPsn_Wp_Plugin_Application interface
 *
 * @author    Timo Reith <timo@ifeelweb.de>
 * @copyright Copyright (c) ifeelweb.de
 * @version   $Id: Interface.php 911603 2014-05-10 10:58:23Z worschtebrot $
 * @package   IfwPsn_Wp_Plugin_Application
 */
interface IfwPsn_Wp_Plugin_Application_Adapter_Interface 
{
    /**
     * Loads the application
     * @return void
     */
    public function load();

    /**
     * Renders the application page
     * @return mixed
     */
    public function render();

    /**
     * Display the application page
     * @return mixed
     */
    public function display();

    /**
     * @return IfwPsn_Wp_Plugin_Manager
     */
    public function getPluginManager();
}
