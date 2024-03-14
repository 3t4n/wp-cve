<?php
/**
 * ifeelweb.de WordPress Plugin Framework
 * For more information see http://www.ifeelweb.de/wp-plugin-framework
 *
 * @author    Timo Reith <timo@ifeelweb.de>
 * @version   $Id: Abstract.php 2044844 2019-03-05 21:18:19Z worschtebrot $
 * @package
 */
abstract class IfwPsn_Wp_Plugin_Bootstrap_SubUnit_Abstract implements IfwPsn_Wp_Plugin_Bootstrap_SubUnit_Interface
{
    /**
     * @var IfwPsn_Wp_Plugin_Bootstrap_Abstract
     */
    protected $_bootstrapContext;

    /**
     * @var IfwPsn_Wp_Plugin_Manager
     */
    protected $_pm;


    /**
     * IfwPsn_Wp_Plugin_Bootstrap_SubUnit_Abstract constructor.
     * @param IfwPsn_Wp_Plugin_Bootstrap_Abstract $bootstrap
     */
    public function __construct(IfwPsn_Wp_Plugin_Bootstrap_Abstract $bootstrap)
    {
        $this->_bootstrapContext = $bootstrap;
        $this->_pm = $bootstrap->getPluginManager();
    }

    /**
     * @param IfwPsn_Wp_Plugin_Installer_UninstallInterface $uninstaller
     * @param int $priority
     */
    public function addUninstaller(IfwPsn_Wp_Plugin_Installer_UninstallInterface $uninstaller, $priority = 10)
    {
        try {
            $installer = $this->_pm->getBootstrap()->getInstaller();
            if ($installer instanceof IfwPsn_Wp_Plugin_Installer) {
                $installer->addUninstall($uninstaller, $priority);
            }
        } catch (Exception $e) {
            apply_filters($this->_pm->getAbbrLower() . '_add_uninstaller_exception', $uninstaller, $e);
        }
    }
}
