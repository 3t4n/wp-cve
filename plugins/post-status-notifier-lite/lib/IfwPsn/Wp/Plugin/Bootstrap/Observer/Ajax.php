<?php
/**
 * ifeelweb.de WordPress Plugin Framework
 * For more information see http://www.ifeelweb.de/wp-plugin-framework
 * 
 * 
 *
 * @author    Timo Reith <timo@ifeelweb.de>
 * @copyright Copyright (c) ifeelweb.de
 * @version   $Id: Ajax.php 1248505 2015-09-18 13:49:54Z worschtebrot $
 * @package   
 */
require_once dirname(__FILE__) . '/Abstract.php';

class IfwPsn_Wp_Plugin_Bootstrap_Observer_Ajax extends IfwPsn_Wp_Plugin_Bootstrap_Observer_Abstract
{
    /**
     * @return string
     */
    public function getId()
    {
        return 'ajax';
    }

    protected function _preBootstrap()
    {
        if ($this->_pm->getAccess()->isAdmin() && $this->_pm->getAccess()->isAjax() && !$this->_pm->getAccess()->isHeartbeat()) {

            // register admin ajax request
            if (method_exists($this->_pm->getBootstrap(), 'registerAdminAjaxRequests')) {

                $this->_pm->getBootstrap()->registerAdminAjaxRequests();

//                $requests = $this->_pm->getBootstrap()->registerAdminAjaxRequests();
//                if (!is_array($requests)) {
//                    $requests = array($requests);
//                }
//                foreach ($requests as $request) {
//                    $this->_pm->getAjaxManager()->registerRequest($request);
//                }
            }
        }

        if ($this->_pm->getAccess()->isAjax() && !$this->_pm->getAccess()->isHeartbeat()) {

            // register global admin ajax request
            if (method_exists($this->_pm->getBootstrap(), 'registerAjaxRequests')) {

                $this->_pm->getBootstrap()->registerAjaxRequests();

//                $requests = $this->_pm->getBootstrap()->registerAjaxRequests();
//                if (!is_array($requests)) {
//                    $requests = array($requests);
//                }
//                foreach ($requests as $request) {
//                    $this->_pm->getAjaxManager()->registerRequest($request);
//                }
            }
        }
    }

    protected function _postModules()
    {
//        if ($this->_pm->getAccess()->isAjax() && !$this->_pm->getAccess()->isHeartbeat()) {
//            $this->_pm->getAjaxManager()->load();
//        }
    }
}
