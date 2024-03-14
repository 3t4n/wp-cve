<?php
/**
 * Fired during plugin activation
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Mobile_Switcher
 * @subpackage Mobile_Switcher/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Mobile_Switcher
 * @subpackage Mobile_Switcher/includes
 * @author     Timur Khamitov <timurkhamitov@mail.ru>
 */
class Mobile_Switcher_Activator
{

    /**
     * Set base plugin options
     *
     * @since    1.0.0
     */
    public static function activate()
    {
        $theme = wp_get_theme();
        add_option('mobile_switcher_enabled', TRUE);
        add_option('ms_mobile_template', $theme->Template);
        add_option('ms_tablet_template', $theme->Template);
        add_option('ms_desktop_template', $theme->Template);
    }

}
