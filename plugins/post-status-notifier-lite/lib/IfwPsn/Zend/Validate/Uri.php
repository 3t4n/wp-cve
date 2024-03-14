<?php
/**
 * ifeelweb.de WordPress Plugin Framework
 * For more information see http://www.ifeelweb.de/wp-plugin-framework
 * 
 * URI Validator
 *
 * @author   Timo Reith <timo@ifeelweb.de>
 * @version  $Id: Uri.php 911603 2014-05-10 10:58:23Z worschtebrot $
 * @package  IfwPsn_Wp
 */
class IfwPsn_Zend_Validate_Uri extends IfwPsn_Vendor_Zend_Validate_Abstract
{
    const MSG_URI = 'msgUri';
    
    protected $_messageTemplates = array(
        self::MSG_URI => 'Invalid URI',
    );

    /**
     * (non-PHPdoc)
     * @see IfwPsn_Vendor_Zend_Validate_Interface::isValid()
     */
    public function isValid($value)
    {
        $this->_setValue($value);
    
        // Validate the URI
        $valid = IfwPsn_Vendor_Zend_Uri::check($value);
    
        if ($valid) {
            return true;
        } else {
            $this->_error(self::MSG_URI);
            return false;
        }
    }
}
