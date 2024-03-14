<?php
/**
 * ifeelweb.de WordPress Plugin Framework
 * For more information see http://www.ifeelweb.de/wp-plugin-framework
 *
 * Twig extension for localized date filter
 * Uses strftime (http://www.php.net/manual/de/function.strftime.php) format syntax
 *
 * @author   Timo Reith <timo@ifeelweb.de>
 * @version  $Id: WP.php 2990970 2023-11-07 16:18:32Z worschtebrot $
 */
require_once dirname(__FILE__) . '/../../../Vendor/Twig/ExtensionInterface.php';
require_once dirname(__FILE__) . '/../../../Vendor/Twig/Extension.php';

class IfwPsn_Wp_Tpl_Extension_WP extends IfwPsn_Vendor_Twig_Extension
{
    /**
     * @return string
     */
    public function getName()
    {
        return 'WP';
    }

    /**
     * @return array
     */
    public function getFunctions()
    {
        require_once dirname(__FILE__) . '/../../../Vendor/Twig/Function/Method.php';

        return array(
            'admin_url' => new IfwPsn_Vendor_Twig_Function_Method($this, 'admin_url'),
        );
    }

    /**
     * @param string $path
     * @param string $scheme
     * @return string|void
     */
    public function admin_url($path = '', $scheme = 'admin')
    {
        return admin_url($path, $scheme);
    }
}
