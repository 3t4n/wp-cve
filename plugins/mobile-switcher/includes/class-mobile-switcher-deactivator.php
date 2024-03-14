<?php
/**
 * Fired during plugin deactivation
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Mobile_Switcher
 * @subpackage Mobile_Switcher/includes
 */

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      1.0.0
 * @package    Mobile_Switcher
 * @subpackage Mobile_Switcher/includes
 * @author     Timur Khamitov <timurkhamitov@mail.ru>
 */
class Mobile_Switcher_Deactivator
{

    /**
     * All base plugin options
     *
     * @since    1.0.0
     */
    public static function deactivate()
    {
        delete_option('mobile_switcher_enabled');
        delete_option('ms_mobile_template');
        delete_option('ms_tablet_template');
        delete_option('ms_desktop_template');
    }

}
