<?php
/**
 * ifeelweb.de WordPress Plugin Framework
 * For more information see http://www.ifeelweb.de/wp-plugin-framework
 * 
 * 
 *
 * @author    Timo Reith <timo@ifeelweb.de>
 * @copyright Copyright (c) ifeelweb.de
 * @version   $Id: Translation.php 1850578 2018-03-31 19:27:09Z worschtebrot $
 * @package   
 */
require_once dirname(__FILE__) . '/Abstract.php';

class IfwPsn_Wp_Plugin_Bootstrap_Observer_Translation extends IfwPsn_Wp_Plugin_Bootstrap_Observer_Abstract
{
    /**
     * @return string
     */
    public function getId()
    {
        return 'translation';
    }

    protected function _preBootstrap()
    {
        if (!$this->_pm->getAccess()->isHeartbeat() && $this->_pm->getAccess()->isAdmin()) {

            $skip = false;
            $options = get_option($this->_pm->getAbbrLower() . '_options');
            if (is_array($options) && isset($options[$this->_pm->getAbbrLower() . '_option_admin_in_english'])) {
                $skip = true;
            }

            if (!$skip && is_dir($this->_pm->getPathinfo()->getRootLang())) {

                // load the framework translation
                require_once $this->_pm->getPathinfo()->getRootLib() . '/IfwPsn/Wp/Proxy.php';
                IfwPsn_Wp_Proxy::loadTextdomain('ifw', false, $this->_pm->getPathinfo()->getDirname() . '/lib/IfwPsn/Wp/Translation');
                
                $langRelPath = $this->_pm->getPathinfo()->getDirname() . '/lang';
                $result = IfwPsn_Wp_Proxy::loadTextdomain($this->_pm->getEnv()->getTextDomain(), false, $langRelPath);
            }
        }
    }

}
