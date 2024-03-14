<?php
/**
 * ifeelweb.de WordPress Plugin Framework
 * For more information see http://www.ifeelweb.de/wp-plugin-framework
 * 
 * Loggable interface
 *
 * @author   Timo Reith <timo@ifeelweb.de>
 * @version  $Id: Loggable.php 911603 2014-05-10 10:58:23Z worschtebrot $
 * @package  IfwPsn_Wp_Interface
 */
interface IfwPsn_Wp_Interface_Loggable
{
    /**
     * Sets logger
     * @param IfwPsn_Wp_Plugin_Logger $logger
     */
    public function setLogger(IfwPsn_Wp_Plugin_Logger $logger);
    
    /**
     * Retrieves logger
     * @return null|IfwPsn_Wp_Plugin_Logger
     */
    public function getLogger();
}
?>