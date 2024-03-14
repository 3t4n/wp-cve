<?php
/**
 * ifeelweb.de WordPress Plugin Framework
 * For more information see http://www.ifeelweb.de/wp-plugin-framework
 *
 * Twig extension for localized date filter
 * Uses strftime (http://www.php.net/manual/de/function.strftime.php) format syntax
 *
 * @author   Timo Reith <timo@ifeelweb.de>
 * @version  $Id: PrintR.php 354 2014-11-26 22:02:26Z timoreithde $
 */
require_once dirname(__FILE__) . '/../../../Vendor/Twig/ExtensionInterface.php';
require_once dirname(__FILE__) . '/../../../Vendor/Twig/Extension.php';

class IfwPsn_Wp_Tpl_Extension_Uniqid extends IfwPsn_Vendor_Twig_Extension
{
    /**
     * @return string
     */
    public function getName()
    {
        return 'uniqid';
    }

    /**
     * @return array
     */
    public function getFunctions()
    {
        require_once dirname(__FILE__) . '/../../../Vendor/Twig/Function/Method.php';

        return array(
            'uniqid' => new IfwPsn_Vendor_Twig_Function_Method($this, 'uniqid'),
        );
    }

    /**
     * @param string $prefix
     * @param false $more_entropy
     */
    public function uniqid($prefix = '', $more_entropy = false)
    {
        return uniqid($prefix, $more_entropy);
    }
}
