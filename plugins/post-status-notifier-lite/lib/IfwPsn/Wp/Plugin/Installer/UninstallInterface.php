<?php
/**
 * ifeelweb.de WordPress Plugin Framework
 * For more information see http://www.ifeelweb.de/wp-plugin-framework
 * 
 * 
 *
 * @author   Timo Reith <timo@ifeelweb.de>
 * @version  $Id: UninstallInterface.php 2990970 2023-11-07 16:18:32Z worschtebrot $
 * @package  IfwPsn_Wp
 */
interface IfwPsn_Wp_Plugin_Installer_UninstallInterface
{
    /**
     * @param $pm null|IfwPsn_Wp_Plugin_Manager
     * @param bool $networkwide
     * @return mixed
     */
    public static function execute($pm, $networkwide = false);
}
