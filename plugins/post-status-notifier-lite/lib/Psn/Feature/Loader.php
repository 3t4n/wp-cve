<?php
/**
 * PSN feature loader
 *
 * @author    Timo Reith <timo@ifeelweb.de>
 * @copyright Copyright (c) 2014 ifeelweb.de
 * @version   $Id: Loader.php 1248505 2015-09-18 13:49:54Z worschtebrot $
 * @package
 */
class Psn_Feature_Loader extends IfwPsn_Wp_Plugin_Feature_Loader
{
    protected function _init()
    {
        $this->addFeature(new Psn_Feature_Core($this->_pm));
    }
}
