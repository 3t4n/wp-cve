<?php
/**
 * ifeelweb.de WordPress Plugin Framework
 * For more information see http://www.ifeelweb.de/wp-plugin-framework
 * 
 * 
 *
 * @author    Timo Reith <timo@ifeelweb.de>
 * @version   $Id: Interface.php 1147446 2015-04-27 19:08:42Z worschtebrot $
 * @package   
 */
interface IfwPsn_Wp_Options_Renderer_Interface 
{
    public function init();
    public function render(IfwPsn_Wp_Options $options, $pageId = null);
}
 