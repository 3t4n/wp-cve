<?php
/**
 *
 *
 * @author      Timo Reith <timo@ifeelweb.de>
 * @version     $Id: MailBody.php 911603 2014-05-10 10:58:23Z worschtebrot $
 * @copyright   Copyright (c) ifeelweb.de
 * @package     Psn_Admin
 */
class Psn_Admin_Form_Validate_MailBody extends IfwPsn_Vendor_Zend_Validate_NotEmpty
{
    /**
     * (non-PHPdoc)
     * @see IfwPsn_Vendor_Zend_Validate_Interface::isValid()
     */
    public function isValid($value, $context = null)
    {
        if (isset($context['mail_tpl']) && $context['mail_tpl'] != '0') {
            return true;
        }

        return parent::isValid($value);
    }
}
