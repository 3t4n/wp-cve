<?php
/**
 * ifeelweb.de WordPress Plugin Framework
 * For more information see http://www.ifeelweb.de/wp-plugin-framework
 *
 *
 *
 * @author   Timo Reith <timo@ifeelweb.de>
 * @version  $Id: User.php 911603 2014-05-10 10:58:23Z worschtebrot $
 */
class IfwPsn_Wp_User
{
    public static function isAdmin()
    {
        return current_user_can('install_plugins');
    }
}
