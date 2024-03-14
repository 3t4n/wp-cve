<?php
/**
 * ifeelweb.de WordPress Plugin Framework
 * For more information see http://www.ifeelweb.de/wp-plugin-framework
 * 
 * Plugin installer
 *
 * @author    Timo Reith <timo@ifeelweb.de>
 * @copyright Copyright (c) ifeelweb.de
 * @version   $Id: Installer.php 2990970 2023-11-07 16:18:32Z worschtebrot $
 * @package   IfwPsn_Wp_Plugin
 */
class IfwPsn_Wp_Plugin_Installer
{
    /**
     * Instance store
     * @var array
     */
    public static $_instances = array();
    
    /**
     * @var IfwPsn_Wp_Plugin_Manager
     */
    protected $_pm;

    /**
     * @var array
     */
    protected $_activation = array();

    /**
     * @var array
     */
    protected $_deactivation = array();

    /**
     * @var array
     */
    protected static $_uninstall = array();

    /**
     * @var bool
     */
    protected static $_isActivating = false;



    /**
     * Retrieves singleton IfwPsn_Wp_Plugin_Admin object
     * 
     * @param IfwPsn_Wp_Plugin_Manager $pm
     * @return IfwPsn_Wp_Plugin_Installer
    */
    public static function getInstance(IfwPsn_Wp_Plugin_Manager $pm)
    {
        if (!isset(self::$_instances[$pm->getAbbr()])) {
            self::$_instances[$pm->getAbbr()] = new self($pm);
        }
        return self::$_instances[$pm->getAbbr()];
    }

    /**
     * @param IfwPsn_Wp_Plugin_Manager $pm
     */
    protected function __construct(IfwPsn_Wp_Plugin_Manager $pm)
    {
        $this->_pm = $pm;
        $this->_initActivation();
        $this->_initDeactivation();
        $this->_initUninstall();
    }

    protected function _initActivation()
    {
        $this->registerActivation();

        ifw_raise_memory_limit();

        // add default activation commands
        require_once $this->_pm->getPathinfo()->getRootLib() . 'IfwPsn/Wp/Plugin/Installer/Command/ActivationPresentVersion.php';

        $this->addActivation(new IfwPsn_Wp_Plugin_Installer_Command_ActivationPresentVersion());
    }

    protected function _initDeactivation()
    {
        $this->registerDeactivation();
    }

    protected function _initUninstall()
    {
        //self::$_uninstall[$this->_pm->getPathinfo()->getFilenamePath()] = array();
        self::$_uninstall[$this->_pm->getPathinfo()->getFilenamePath()] = new IfwPsn_Util_PriorityArray();
        $this->registerUninstall();

        ifw_raise_memory_limit();

        // add default uninstall commands
        require_once $this->_pm->getPathinfo()->getRootLib() . 'IfwPsn/Wp/Plugin/Installer/Command/UninstallDeleteLog.php';
        require_once $this->_pm->getPathinfo()->getRootLib() . 'IfwPsn/Wp/Plugin/Installer/Command/UninstallResetOptions.php';
        require_once $this->_pm->getPathinfo()->getRootLib() . 'IfwPsn/Wp/Plugin/Installer/Command/UninstallRemoveHooks.php';

        $this->addUninstall(new IfwPsn_Wp_Plugin_Installer_Command_UninstallDeleteLog());
        $this->addUninstall(new IfwPsn_Wp_Plugin_Installer_Command_UninstallResetOptions());
        $this->addUninstall(new IfwPsn_Wp_Plugin_Installer_Command_UninstallRemoveHooks());
    }

    /**
     * Add the register_activation_hook
     */
    public function registerActivation()
    {
        register_activation_hook($this->_pm->getPathinfo()->getFilenamePath(), array($this, 'activate'));

        add_action('wp_initialize_site', [$this, 'onWpInitializeSite'], 10, 2);
    }

    /**
     * @param WP_Site $new_site
     * @param array $args
     */
    public function onWpInitializeSite(WP_Site $new_site, array $args)
    {
        $currentBlogId = IfwPsn_Wp_Proxy_Blog::getBlogId();
        IfwPsn_Wp_Proxy_Blog::switchToBlog($new_site->blog_id);
        $this->activate();
        IfwPsn_Wp_Proxy_Blog::switchToBlog($currentBlogId);
    }
    
    /**
     * 
     * @param IfwPsn_Wp_Plugin_Installer_ActivationInterface $activation
     */
    public function addActivation(IfwPsn_Wp_Plugin_Installer_ActivationInterface $activation)
    {
        array_push($this->_activation, $activation);
    }

    /**
     * Loop over all added activation objects
     * @param bool $networkwide
     */
    public function activate($networkwide = false)
    {
        if (!current_user_can('activate_plugins') || self::$_isActivating) {
            return;
        }

        self::$_isActivating = true;

        do_action($this->_pm->getAbbrLower() . '_before_activation');

        /**
         * @var $activation IfwPsn_Wp_Plugin_Installer_ActivationInterface
         */
        foreach ($this->_activation as $activation) {
            $activation->execute($this->_pm, $networkwide);
        }

        update_option(self::getActivatedVersionMarkerName($this->_pm), $this->_pm->getEnv()->getVersion());
    }

    /**
     * @param IfwPsn_Wp_Plugin_Manager $pm
     * @return string
     */
    public static function getActivatedVersionMarkerName(IfwPsn_Wp_Plugin_Manager $pm)
    {
        return sprintf('%s_activated_version', $pm->getAbbrLower());
    }

    /**
     * @param IfwPsn_Wp_Plugin_Manager $pm
     * @return string
     */
    public static function getActivatedVersionMarkerValue(IfwPsn_Wp_Plugin_Manager $pm)
    {
        return get_option(self::getActivatedVersionMarkerName($pm));
    }

    /**
     * Add the register_activation_hook
     */
    public function registerDeactivation()
    {
        register_deactivation_hook($this->_pm->getPathinfo()->getFilenamePath(), array($this, 'deactivate'));
    }

    /**
     * 
     * @param IfwPsn_Wp_Plugin_Installer_DeactivationInterface $deactivation
     */
    public function addDeactivation(IfwPsn_Wp_Plugin_Installer_DeactivationInterface $deactivation)
    {
        array_push($this->_deactivation, $deactivation);
    }

    /**
     * Loop over all added deactivation objects
     */
    public function deactivate($networkwide)
    {
        if (!current_user_can('activate_plugins')) {
            return;
        }

        do_action($this->_pm->getAbbrLower() . '_before_deactivation');

        /**
         * @var $activaion IfwPsn_Wp_Plugin_Installer_DeactivationInterface
         */
        foreach ($this->_deactivation as $deactivaion) {
            $deactivaion->execute($this->_pm, $networkwide);
        }
    }

    /**
     *
     */
    public function registerUninstall()
    {
        register_uninstall_hook($this->_pm->getPathinfo()->getFilenamePath(), 'IfwPsn_Wp_Plugin_Installer::uninstall');

        add_action('wp_delete_site', [$this, 'onWpDeleteSite']);
    }

    /**
     * @param WP_Site $old_site
     */
    public function onWpDeleteSite(WP_Site $old_site)
    {
        $currentBlogId = IfwPsn_Wp_Proxy_Blog::getBlogId();
        IfwPsn_Wp_Proxy_Blog::switchToBlog($old_site->blog_id);

        self::executeUninstall($this->_pm);

        IfwPsn_Wp_Proxy_Blog::switchToBlog($currentBlogId);
    }

    /**
     *
     * @param IfwPsn_Wp_Plugin_Installer_UninstallInterface $uninstall
     * @param int $priority
     */
    public function addUninstall(IfwPsn_Wp_Plugin_Installer_UninstallInterface $uninstall, $priority = 10)
    {
        if (self::$_uninstall[$this->_pm->getPathinfo()->getFilenamePath()] instanceof IfwPsn_Util_PriorityArray) {
            self::$_uninstall[$this->_pm->getPathinfo()->getFilenamePath()]->add($uninstall, $priority);
        }
        //array_push(self::$_uninstall[$this->_pm->getPathinfo()->getFilenamePath()], $uninstall);
    }

    /**
     * @internal param \IfwPsn_Wp_Plugin_Installer_UninstallInterface $uninstall
     */
    public static function uninstall()
    {
        if (!current_user_can('activate_plugins')) {
            return;
        }

        if (isset($_GET['checked'])) {
            $checked = array_values($_GET['checked']);
            if (!empty($checked)) {
                $filenamePath = array_shift($checked);
            }
        } elseif (isset($_POST['plugin'])) {
            $filenamePath = $_POST['plugin'];
        }

        if (isset($filenamePath) && !empty($filenamePath)) {
            $pm = IfwPsn_Wp_Plugin_Manager::getInstanceFromFilenamePath($filenamePath);
            self::executeUninstall($pm, true);
        }
    }

    /**
     * @param $pm
     * @param bool $networkwide
     */
    protected static function executeUninstall($pm, $networkwide = false)
    {
        if ($pm instanceof IfwPsn_Wp_Plugin_Manager &&
            self::$_uninstall[$pm->getPathinfo()->getFilenamePath()] instanceof IfwPsn_Util_PriorityArray) {

            foreach (self::$_uninstall[$pm->getPathinfo()->getFilenamePath()]->get() as $uninstall) {
                call_user_func(get_class($uninstall) . '::execute', $pm, $networkwide);
            }
        }

        delete_option(self::getActivatedVersionMarkerName($pm));
    }
}