<?php
/**
 * ifeelweb.de WordPress Plugin Framework
 * For more information see http://www.ifeelweb.de/wp-plugin-framework
 * 
 * 
 *
 * @author    Timo Reith <timo@ifeelweb.de>
 * @version   $Id: Abstract.php 1850578 2018-03-31 19:27:09Z worschtebrot $
 * @package   
 */ 
abstract class IfwPsn_Wp_Plugin_Update_Api_Abstract implements IfwPsn_Wp_Plugin_Update_Api_Interface
{
    /**
     * @var IfwPsn_Wp_Plugin_Manager
     */
    protected $_pm;

    /**
     * @var
     */
    protected $_license;

    /**
     * @param IfwPsn_Wp_Plugin_Manager $pm
     */
    public function __construct(IfwPsn_Wp_Plugin_Manager $pm)
    {
        $this->_pm = $pm;
    }

    /**
     * @return bool
     */
    public function hasLicense()
    {
        return !empty($this->_license);
    }

    /**
     * @return mixed
     */
    public function getLicense()
    {
        if (!empty($this->_license)) {
            return $this->_license;
        }
        return null;
    }

    /**
     * @param mixed $license
     */
    public function setLicense($license)
    {
        $this->_license = $license;
    }

    /**
     * @return string
     */
    public function getLicenseServerConnectionErrorMessage()
    {
        return sprintf(
            __('Please make sure that your server is able to connect to the license server %s. If you are not sure, please contact your server support.', 'ifw'),
            $this->_pm->getConfig()->plugin->updateServer
        );
    }
}
