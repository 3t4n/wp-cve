<?php
/**
 * ifeelweb.de Wordpress Plugin Framework
 * For more information see http://www.ifeelweb.de/wp-plugin-framework
 *
 * Abstract module
 *
 * @author   Timo Reith <timo@ifeelweb.de>
 * @version  $Id: Abstract.php 2990970 2023-11-07 16:18:32Z worschtebrot $
 * @package  IfwPsn_Wp_Plugin_Admin
 */
require_once dirname(__FILE__) . '/Interface.php';

abstract class IfwPsn_Wp_Module_Bootstrap_Abstract implements IfwPsn_Wp_Module_Bootstrap_Interface
{
    /**
     * @var IfwPsn_Wp_Plugin_Manager
     */
    protected $_pm;

    /**
     * @var IfwPsn_Wp_Plugin_Bootstrap_Abstract
     */
    protected $_mainBootstrap;

    /**
     * @var IfwPsn_Wp_Env_Module
     */
    protected $_env;

    /**
     * @var IfwPsn_Wp_Pathinfo_Module
     */
    protected $_pathinfo;

    /**
     * @var
     */
    protected $_locationName;

    /**
     * @var bool
     */
    protected $_initialized = false;


    /**
     * @param IfwPsn_Wp_Pathinfo_Module $pathinfo
     * @param $locationName
     * @param IfwPsn_Wp_Plugin_Manager $pm
     * @internal param $bootstrapPath
     */
    public final function __construct(IfwPsn_Wp_Pathinfo_Module $pathinfo, $locationName, IfwPsn_Wp_Plugin_Manager $pm, IfwPsn_Wp_Plugin_Bootstrap_Abstract $mainBootstrap)
    {
        $this->_pathinfo = $pathinfo;
        $this->_locationName = $locationName;
        $this->_pm = $pm;
        $this->_mainBootstrap = $mainBootstrap;
    }

    /**
     * Inits default logic
     */
    public function init()
    {
        // check if module properties are set
        $this->_checkProperties();

        // register lib
        if ($this->_pathinfo->hasRootLib()) {
            // add the module lib dir to the autoloader
            $classPrefix = $this->_pm->getAbbr() . '_Module_' . $this->_pathinfo->getDirname();
            IfwPsn_Wp_Autoloader::registerModule($classPrefix, $this->_pathinfo->getRootLib());
        }

        // register templates dir
        if ($this->_pm->getAccess()->isPlugin()) {
            $this->initTpl();
        }

        // init module translation
        $this->_initTranslation();

        // load default script and style files
        if ($this->_pm->getAccess()->isPlugin() && !$this->_pm->getAccess()->isAjax()) {
            $this->_enqueueScripts();
        }

        if ($this->_pm->getAccess()->isAjax() && !$this->_pm->getAccess()->isHeartbeat()) {
            $this->_initAjax();
        }

        if ($this->_pm->getOptions()->isAccess()) {
            $this->onOptionsPage();
        }

        if (!$this->_pm->getAccess()->isHeartbeat() &&
            ($this->_pm->getAccess()->isAdmin() || $this->_pm->getAccess()->isNetworkAdmin())) {

            $this->loadInstaller();
            // register patches
            add_action($this->_pm->getAbbr() . '_before_patch_execution', array($this, 'addPatches'));
        }

        if ($this->_pm->getAccess()->isAdmin()) {

            if ($this->getPathinfo()->hasRootTpl()) {
                IfwPsn_Wp_Tpl::getFilesytemInstance($this->_pm)->getLoader()->addPath($this->getPathinfo()->getRootTpl());
            }

            $this->onAdmin();

            if ($this->_pm->getAccess()->isPlugin()) {
                $this->onPluginAdmin();
            }

            if (!$this->_pm->getAccess()->isHeartbeat() && !$this->_pm->getAccess()->isActivation()) {
                if ($this->_pm->getAccess()->isPlugin()) {
                    add_action($this->_pm->getAbbrLower() . '_selftester_activate', array($this, 'addSelftests'));
                }
            }
        }

        $this->_initialized = true;
    }

    /**
     * called before bootstrap
     */
    public function onOptionsPage()
    {
    }

    public function onAdmin()
    {
    }

    public function onPluginAdmin()
    {
    }

    /**
     * called before bootstrap
     */
    public function loadInstaller()
    {
    }

    /**
     * called before bootstrap
     * @param IfwPsn_Wp_Plugin_Update_Patcher $patcher
     */
    public function addPatches(IfwPsn_Wp_Plugin_Update_Patcher $patcher)
    {
    }

    /**
     * @param IfwPsn_Wp_Plugin_Selftester $selftester
     */
    public function addSelftests(IfwPsn_Wp_Plugin_Selftester $selftester)
    {
    }

    /**
     * Fires when custom modules get activated, may be overwritten by custom modules
     */
    public function onActivate()
    {
    }

    /**
     * Fires when custom modules get deactivated, may be overwritten by custom modules
     */
    public function onDeactivate()
    {
    }

    /**
     * Fires when custom modules get deleted, may be overwritten by custom modules
     */
    public function onDelete()
    {
    }

    /**
     * Activates the module
     */
    public function activate()
    {
        IfwPsn_Wp_Module_Activator::getInstance($this->_pm)->activate($this);
        $this->onActivate();
    }

    /**
     * Deactivates the module
     */
    public function deactivate()
    {
        IfwPsn_Wp_Module_Activator::getInstance($this->_pm)->deactivate($this);
        $this->onDeactivate();
    }

    /**
     * Deletes the module
     */
    public function delete()
    {
        if (!$this->isActivated()) {
            $this->onDelete();
            ifw_rrmdir($this->getPathinfo()->getRoot());
        }
    }

    /**
     * @return bool
     * @throws IfwPsn_Wp_Module_Exception
     */
    protected function _checkProperties()
    {
        $properties = array('_id', '_name', '_description', '_textDomain', '_version', '_author', '_authorHomepage', '_homepage', '_dependencies');
        foreach ($properties as $prop) {
            if (!isset($this->$prop)) {
                throw new IfwPsn_Wp_Module_Exception('Module must have $' . $prop);
            }
        }
        return true;
    }

    /**
     * Registers the controller path
     */
    public function registerPath()
    {
        if ($this->_pm->getAccess()->isPlugin()) {

            // add controller dir
            if(is_dir($this->_pathinfo->getDirnamePath() . 'controllers')) {

                $front = IfwPsn_Zend_Controller_Front::getInstance();
                if ($front instanceof IfwPsn_Vendor_Zend_Controller_Front) {

                    $front->addControllerDirectory($this->_pathinfo->getDirnamePath() . 'controllers',
                        strtolower($this->_pathinfo->getDirname()));
                }
            }
        }
    }

    /**
     * register the module's tpl dir to loader path
     */
    public function initTpl()
    {
        if ($this->_pathinfo->hasRootTpl()) {
            IfwPsn_Wp_Tpl::getFilesytemInstance($this->_pm)->getLoader()->addPath($this->_pathinfo->getRootTpl());
        }
    }

    /**
     * registeres the module's files that handle the ajax requests
     */
    protected function _initAjax()
    {
        if ($this->_pm->getAccess()->isAdmin() && $this->_pm->getAccess()->isAjax() && !$this->_pm->getAccess()->isHeartbeat() &&
            method_exists($this, '_registerAdminAjaxRequests')) {

            $this->_registerAdminAjaxRequests();

//            // register admin ajax request
//            $requests = $this->_registerAdminAjaxRequests();
//            if ($requests !== null) {
//                if (!is_array($requests)) {
//                    $requests = array($requests);
//                }
//                foreach ($requests as $request) {
//                    $this->_pm->getAjaxManager()->registerRequest($request);
//                }
//            }
        }

        if ($this->_pm->getAccess()->isAjax() && !$this->_pm->getAccess()->isHeartbeat() &&
            method_exists($this, '_registerAjaxRequests')) {

            $this->_registerAjaxRequests();
//
//            // register global ajax requests
//            $requests = $this->_registerAjaxRequests();
//            if ($requests !== null) {
//                if (!is_array($requests)) {
//                    $requests = array($requests);
//                }
//                foreach ($requests as $request) {
//                    $this->_pm->getAjaxManager()->registerRequest($request);
//                }
//            }
        }
    }

    /**
     * Checks if plugin translation files exist and inits the plugin textdomain
     *
     * @return bool
     */
    protected function _initTranslation()
    {
        $result = false;

        if ($this->_pathinfo->hasRootLang()) {
            $langRelPath = $this->_pm->getPathinfo()->getDirname() . '/modules/' . $this->_pathinfo->getDirname() . '/lang';
            $result = IfwPsn_Wp_Proxy::loadTextdomain($this->getTextDomain(), false, $langRelPath);
        }

        return $result;
    }

    /**
     * Loads js/css on admin_enqueue_scripts
     */
    protected function _enqueueScripts()
    {
        if ($this->_pm->getAccess()->isModule(strtolower($this->getName()))) {
            $this->_loadAdminCss();
            $this->_loadAdminJs();
        }
    }

    /**
     *
     */
    protected function _loadAdminCss()
    {
        $adminCssPath = $this->_pathinfo->getRootCss() . 'admin.css';

        if (file_exists($adminCssPath)) {
            $handle = $this->getId() . '-' .'admin-css';
            IfwPsn_Wp_Proxy_Style::loadAdmin($handle, $this->_env->getUrlCss() . 'admin.css', array(), $this->getVersion());
        }
    }

    /**
     *
     */
    protected function _loadAdminJs()
    {
        $adminJsPath = $this->_pathinfo->getRootJs() . 'admin.js';

        if (file_exists($adminJsPath)) {
            $handle = $this->getId() . '-' .'admin-js';
            IfwPsn_Wp_Proxy_Script::loadAdmin($handle, $this->_env->getUrlJs() . 'admin.js', array(), $this->getVersion());
        }
    }

    /**
     * @return \IfwPsn_Wp_Plugin_Manager
     */
    public function getPluginManager()
    {
        return $this->_pm;
    }

    /**
     * @param \IfwPsn_Wp_Env_Module $env
     */
    public function setEnv($env)
    {
        $this->_env = $env;
    }

    /**
     * @return \IfwPsn_Wp_Env_Module
     */
    public function getEnv()
    {
        return $this->_env;
    }

    /**
     * @return \IfwPsn_Wp_Pathinfo_Module
     */
    public function getPathinfo()
    {
        return $this->_pathinfo;
    }

    /**
     * @return string
     */
    public function getAuthor()
    {
        return $this->_author;
    }

    /**
     * @return string
     */
    public function getAuthorHomepage()
    {
        return $this->_authorHomepage;
    }

    /**
     * @return string
     */
    public function getDependencies()
    {
        if (!is_array($this->_dependencies)) {
            $this->_dependencies = array($this->_dependencies);
        }
        return $this->_dependencies;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->_description;
    }

    /**
     * @return string
     */
    public function getHomepage()
    {
        return $this->_homepage;
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->_id;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->_name;
    }

    /**
     * @return string
     */
    public function getTextDomain()
    {
        return $this->_textDomain;
    }

    /**
     * @return string
     */
    public function getVersion()
    {
        return $this->_version;
    }

    /**
     * @return bool
     */
    public function isInitialized()
    {
        return $this->_initialized === true;
    }

    /**
     * @return bool
     */
    public function isActivated()
    {
        return IfwPsn_Wp_Module_Activator::getInstance($this->_pm)->isActivated($this);
    }

    /**
     * @return mixed
     */
    public function getLocationName()
    {
        return $this->_locationName;
    }

    /**
     * @return bool
     */
    public function isCustomModule()
    {
        return $this->_locationName == IfwPsn_Wp_Module_Manager::LOCATION_NAME_CUSTOM;
    }

}
