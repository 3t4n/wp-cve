<?php
/**
 * ifeelweb.de WordPress Plugin Framework
 * For more information see http://www.ifeelweb.de/wp-plugin-framework
 * 
 * 
 *
 * @author    Timo Reith <timo@ifeelweb.de>
 * @version   $Id: PostFunctions.php 972646 2014-08-25 20:12:32Z worschtebrot $
 * @package   
 */ 
class IfwPsn_Wp_WunderScript_Extension_PostFunctions implements IfwPsn_Wp_WunderScript_Extension_Interface
{
    public function load(IfwPsn_Vendor_Twig_Environment $env)
    {
        require_once IFW_PSN_LIB_ROOT . 'IfwPsn/Vendor/Twig/SimpleFunction.php';

        $env->addFunction( new IfwPsn_Vendor_Twig_SimpleFunction('get_post', array($this, 'getPost')) );
    }

    public function getPost($id)
    {
        $id = (int)$id;

        return IfwPsn_Wp_Proxy_Post::get($id);
    }
}
 