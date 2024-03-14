<?php
/**
 *  Plugins functionality.
 *
 * @package white-label
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

/**
 * Hide Plugins from the plugin page.
 *
 * @return void
 */
function white_label_hide_plugins()
{
    // Exit If it's WL Admin.
    if (white_label_is_wl_admin()) {
        return;
    }

    $hidden_plugins = white_label_get_option('hidden_plugins', 'white_label_plugins', false);

    // Exit if no settings.
    if (empty($hidden_plugins)) {
        return;
    }

    global $wp_list_table;

    $all_plugins = $wp_list_table->items;
    // Check each plugin name.
    foreach ($all_plugins as $plugin_key => $val) {
        if (in_array($plugin_key, $hidden_plugins, true)) {
            unset($wp_list_table->items[$plugin_key]);
        }
    }
}

add_action('pre_current_active_plugins', 'white_label_hide_plugins', 999);

/**
 * Hide plugin updates from the transient & updates page.
 *
 * @param array $value transient updates array.
 *
 * @return array $value update information.
 */
function white_label_hide_plugin_updates($value)
{
    // Exit if it's WL Admin.
    if (white_label_is_wl_admin()) {
        return $value;
    }

    $hidden_plugins = white_label_get_option('hidden_plugins', 'white_label_plugins', false);

    if (!empty($hidden_plugins)) {
        // Hide each plugin update.
        foreach ($hidden_plugins  as $plugin) {
            if (isset($value->response[$plugin])) {
                unset($value->response[$plugin]); // E.g 'akismet/akismet.php'.
            }
        }
    }

    return $value;
}

add_filter('site_transient_update_plugins', 'white_label_hide_plugin_updates');