<?php
/**
 * ifeelweb.de WordPress Plugin Framework
 * For more information see http://www.ifeelweb.de/wp-plugin-framework
 * 
 * Abstract loader
 *
 * @author   Timo Reith <timo@ifeelweb.de>
 * @version  $Id: Abstract.php 2990970 2023-11-07 16:18:32Z worschtebrot $
 * @package  IfwPsn_Wp
 */
require_once dirname(__FILE__) . '/Interface.php';

abstract class IfwPsn_Wp_Plugin_Loader_Abstract implements IfwPsn_Wp_Plugin_Loader_Interface
{
    /**
     * @var IfwPsn_Wp_Pathinfo_Plugin
     */
    protected $_pluginPathinfo;



    /**
     * @param string $pathinfo
     */
    public function __construct($pathinfo)
    {
        $this->_initPathinfo($pathinfo);
        $this->_initAutoloader();
    }
    
    /**
     * Inits the pathinfo object
     */
    protected function _initPathinfo($pathinfo)
    {
        if (!class_exists('IfwPsn_Wp_Pathinfo_Plugin')) {
            require_once dirname(__FILE__) . '/../../Pathinfo/Abstract.php';
            require_once dirname(__FILE__) . '/../../Pathinfo/Plugin.php';
        }

        $this->_pluginPathinfo = new IfwPsn_Wp_Pathinfo_Plugin($pathinfo);
    }

    /**
     * Loads autoloader before other resources for convenience
     */
    protected function _initAutoloader()
    {
        if (!class_exists('IfwPsn_Wp_Autoloader')) {
            require_once $this->_pluginPathinfo->getRootLib() . 'IfwPsn/Wp/Autoloader.php';
        }

//        $composerAutoloadPath = $this->_pluginPathinfo->getRoot() . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';
//        if (file_exists($composerAutoloadPath)) {
//            require_once $composerAutoloadPath;
//        }

        if (!IfwPsn_Wp_Autoloader::init($this->_pluginPathinfo->getRootLib())) {
            ifw_debug('Autoloader error: Could not init ' . $this->_pluginPathinfo->getRootLib());
        }
        if (!IfwPsn_Wp_Autoloader::init($this->_pluginPathinfo->getRootAdminMenu())) {
            ifw_debug('Autoloader error: Could not init ' . $this->_pluginPathinfo->getRootAdminMenu());
        }
    }

    /**
     * @return IfwPsn_Wp_Plugin_Logger
     */
    abstract public function getLogger();

    /**
     * @return IfwPsn_Wp_Plugin_Manager
     */
    abstract public function getPluginManager();
    
    /**
     * @return IfwPsn_Wp_Env_Plugin
     */
    abstract public function getEnv();
    
}
