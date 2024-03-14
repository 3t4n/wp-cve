<?php

/**
 * Plugin Hooks
 *
 * The main hooks class which is inherited.
 *
 * @author     Denra.com aka SoftShop Ltd <support@denra.com>
 * @copyright  2019-2020 Denra.com aka SoftShop Ltd
 * @license    GPLv2 or later
 * @version    1.1.2
 * @link       https://www.denra.com/
 */

namespace Denra\Plugins;

class PluginHooks {
    
    public static $plugin_id;
    public static $plugin_class;
    
    public static function activate() {
        
        // Change old deprecated settings key used in version 1.2
        // until all old versions are upgraded to version 2.0 or newer
        $id_u = str_replace('-', '_', static::$plugin_id);
        $settings_id_u =  $id_u . '_settings';
        $settings = \get_option($settings_id_u);
        if ($settings && isset($settings['remove_plugin_data_on_uninstall'])) {
            $settings['delete_plugin_settings_on_uninstall'] = $settings['remove_plugin_data_on_uninstall'];
            unset($settings['remove_plugin_data_on_uninstall']);
            \update_option($settings_id_u, $settings, FALSE);
        }
        $just_activated_id_u = $id_u . '_just_activated';
        \update_option($just_activated_id_u, 1, FALSE);
    }
    
    public static function deactivate() {
        
        // Clearing the just_activate option
        $id_u = str_replace('-', '_', static::$plugin_id);
        $just_activated_id_u = $id_u . '_just_activated';
        \update_option($just_activated_id_u, 0, FALSE);
        
    }
    
    public static function uninstall() {
        
        // Remove plugin settings and data on uninstall if requested
        $id_u = str_replace('-', '_', static::$plugin_id);
        $settings_id_u = $id_u . '_settings';
        $just_activated_id_u = $id_u . '_just_activated';
        $settings = \get_option($settings_id_u);
        if ($settings['delete_plugin_settings_on_uninstall']) {
            \delete_option($settings_id_u);
            \delete_option($just_activated_id_u);
        }
        
    }
    
}