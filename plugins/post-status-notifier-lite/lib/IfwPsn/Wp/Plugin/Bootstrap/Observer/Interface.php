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
interface IfwPsn_Wp_Plugin_Bootstrap_Observer_Interface
{
    /**
     * @return string
     */
    public function getId();

    /**
     * @param $notificationType
     * @param IfwPsn_Wp_Plugin_Bootstrap_Abstract $bootstrap
     * @return mixed
     */
    public function notify($notificationType, IfwPsn_Wp_Plugin_Bootstrap_Abstract $bootstrap);

    /**
     * @return mixed
     */
    public function getResource();
}
