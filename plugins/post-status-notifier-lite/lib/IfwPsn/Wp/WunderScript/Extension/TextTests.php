<?php
/**
 * ifeelweb.de WordPress Plugin Framework
 * For more information see http://www.ifeelweb.de/wp-plugin-framework
 * 
 * 
 *
 * @author    Timo Reith <timo@ifeelweb.de>
 * @version   $Id: TextTests.php 972646 2014-08-25 20:12:32Z worschtebrot $
 * @package   
 */ 
class IfwPsn_Wp_WunderScript_Extension_TextTests implements IfwPsn_Wp_WunderScript_Extension_Interface
{
    public function load(IfwPsn_Vendor_Twig_Environment $env)
    {
        require_once IFW_PSN_LIB_ROOT . 'IfwPsn/Vendor/Twig/SimpleTest.php';

        $env->addTest( new IfwPsn_Vendor_Twig_SimpleTest('serialized', 'is_serialized') );
    }
}
 