<?php
/**
 * ifeelweb.de WordPress Plugin Framework
 * For more information see http://www.ifeelweb.de/wp-plugin-framework
 * 
 * Plugin config based in Zend_Config_Ini
 *
 * @author    Timo Reith <timo@ifeelweb.de>
 * @copyright Copyright (c) ifeelweb.de
 * @version   $Id: Config.php 1850578 2018-03-31 19:27:09Z worschtebrot $
 * @package   IfwPsn_Wp_Plugin
 */
require_once dirname(__FILE__) . '/../../Vendor/Zend/Config.php';

class IfwPsn_Wp_Plugin_Config extends IfwPsn_Vendor_Zend_Config
{
    /**
     * Instance store
     * @var array
     */
    public static $_instances = array();

    /**
     * Retrieves singleton IfwPsn_Wp_Plugin_Config object
     *
     * @param \IfwPsn_Wp_Pathinfo_Plugin|\IfwPsn_Wp_Plugin_Pathinfo $pluginPathinfo
     * @return IfwPsn_Wp_Plugin_Config
     */
    public static function getInstance(IfwPsn_Wp_Pathinfo_Plugin $pluginPathinfo)
    {
        $instanceToken = $pluginPathinfo->getDirname();
        
        if (!isset(self::$_instances[$instanceToken])) {
            // $env is used in config.php
            $pluginEnv = IfwPsn_Wp_Env_Plugin::getInstance($pluginPathinfo);
            $env = $pluginEnv->getEnvironmet();

            $configArray = include_once $pluginPathinfo->getDirnamePath() . 'config.php';
            self::$_instances[$instanceToken] = new self($configArray);
        }
        return self::$_instances[$instanceToken];
    }

    /**
     * @return string
     */
    public function getActionKey()
    {
        return $this->application->action->key;
    }
    /**
     * @return string
     */
    public function getControllerKey()
    {
        return $this->application->controller->key;
    }
}
