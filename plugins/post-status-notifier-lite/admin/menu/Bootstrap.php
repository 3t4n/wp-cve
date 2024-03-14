<?php
/**
 * Admin menu bootstrap 
 *
 * @author   Timo Reith <timo@ifeelweb.de>
 * @version  $Id: Bootstrap.php 1248505 2015-09-18 13:49:54Z worschtebrot $
 */
require_once dirname(__FILE__) . '/controllers/PsnApplicationController.php';
require_once dirname(__FILE__) . '/controllers/PsnModelBindingController.php';

class Psn_Admin_Menu_Bootstrap extends IfwPsn_Zend_Application_Bootstrap_Bootstrap
{
    protected function _initResources()
    {
    }
}
