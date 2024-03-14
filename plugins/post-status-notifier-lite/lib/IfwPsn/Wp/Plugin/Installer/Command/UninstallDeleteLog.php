<?php
/**
 * ifeelweb.de WordPress Plugin Framework
 * For more information see http://www.ifeelweb.de/wp-plugin-framework
 * 
 * Tries to delete the log file if it exists in case in can not be deleted by WP uninstall process
 *
 * @author   Timo Reith <timo@ifeelweb.de>
 * @version  $Id: UninstallDeleteLog.php 2990970 2023-11-07 16:18:32Z worschtebrot $
 */
require_once dirname(__FILE__) . '/../UninstallInterface.php';

class IfwPsn_Wp_Plugin_Installer_Command_UninstallDeleteLog implements IfwPsn_Wp_Plugin_Installer_UninstallInterface
{
    /**
     * @param IfwPsn_Wp_Plugin_Manager|null $pm
     * @return mixed|void
     */
    public static function execute($pm, $networkwide = false)
    {
        if (!($pm instanceof IfwPsn_Wp_Plugin_Manager)) {
            return;
        }

        $logFilePath = $pm->getPathinfo()->getRoot() . 'log/plugin.log';
        if (file_exists($logFilePath)) {
            unlink($logFilePath);
        }
    }
}
