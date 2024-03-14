<?php
/**
 *
 *
 * @author    Timo Reith <timo@ifeelweb.de>
 * @copyright Copyright (c) 2014 ifeelweb.de
 * @version   $Id: Core.php 1248505 2015-09-18 13:49:54Z worschtebrot $
 * @package
 */
class Psn_Feature_Core extends IfwPsn_Wp_Plugin_Feature_Abstract
{
    function init()
    {

    }

    function load()
    {
        if ($this->_pm->hasOption('apc_clear_cache') && function_exists('apc_clear_cache') && $this->_pm->getAccess()->isPlugin()) {
            apc_clear_cache();
        }
    }
}
