<?php
/**
 * ifeelweb.de WordPress Plugin Framework
 * For more information see http://www.ifeelweb.de/wp-plugin-framework
 * 
 * 
 *
 * @author    Timo Reith <timo@ifeelweb.de>
 * @copyright Copyright (c) ifeelweb.de
 * @version   $Id: Interface.php 911603 2014-05-10 10:58:23Z worschtebrot $
 * @package   
 */
interface IfwPsn_Wp_Plugin_Update_Patch_Interface
{
    /**
     * @param IfwPsn_Util_Version $presentVersion
     * @param IfwPsn_Wp_Plugin_Manager $pm
     */
    public function execute(IfwPsn_Util_Version $presentVersion, IfwPsn_Wp_Plugin_Manager $pm);

    /**
     * @return string
     */
    public function getName();
}
