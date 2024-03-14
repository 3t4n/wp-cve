<?php
/**
 * ifeelweb.de WordPress Plugin Framework
 * For more information see http://www.ifeelweb.de/wp-plugin-framework
 * 
 * 
 *
 * @author    Timo Reith <timo@ifeelweb.de>
 * @copyright Copyright (c) ifeelweb.de
 * @version   $Id: UpdateManager.php 2990970 2023-11-07 16:18:32Z worschtebrot $
 * @package   
 */
require_once dirname(__FILE__) . '/Abstract.php';

class IfwPsn_Wp_Plugin_Bootstrap_Observer_UpdateManager extends IfwPsn_Wp_Plugin_Bootstrap_Observer_Abstract
{
    /**
     * @return string
     */
    public function getId()
    {
        return 'update_manager';
    }

    protected function _postModules()
    {
        if (!$this->_pm->getAccess()->isHeartbeat() && $this->_pm->getAccess()->isAdmin()) {

            require_once $this->_pm->getPathinfo()->getRootLib() . '/IfwPsn/Wp/Plugin/Update/Manager.php';
            $this->_resource = new IfwPsn_Wp_Plugin_Update_Manager($this->_pm);
            $this->_resource->init();
        }
    }

    protected function _postBootstrap()
    {
        if (!$this->_pm->getAccess()->isHeartbeat() &&
            // !$this->_pm->getAccess()->isActivation() && # removed to patch option on activation
            $this->_pm->getAccess()->isAdmin()) {

            if ($this->_resource instanceof IfwPsn_Wp_Plugin_Update_Manager) {
                $patcher = $this->_resource->getPatcher();
                if ($patcher instanceof IfwPsn_Wp_Plugin_Update_Patcher) {
                    try {
                        $patcher->autoUpdate();
                    } catch (Exception $e) {
                        apply_filters($this->_pm->getAbbrLower() . '_exception_during_patching', $e);
                    }
                }
            }
        }
    }
}
