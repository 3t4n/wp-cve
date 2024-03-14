<?php

/**
 * Plugin Name: Dynamic Animations for Elementor
 * Description: Elementor plugin animations
 * Plugin URI:  https://www.dynamic.ooo/widget/dynamic-animations/
 * Version:     1.0.0
 * Author:      Dynamic.ooo
 * Author URI:  https://www.dynamic.ooo/
 * Text Domain: dynamic-animations-for-elementor
 */
if (!defined('ABSPATH'))
    exit; // Exit if accessed directly

define('DAE_VERSION', '1.0.0');
define('DAE_TEXTDOMAIN', 'dynamic-animations-for-elementor');

/**
 * Load DAE
 *
 * Load the plugin after Elementor (and other plugins) are loaded.
 *
 * @since 1.0.0
 */
function dynamic_animations_for_elementor_load() {
    // Load localization file
    load_plugin_textdomain(DAE_TEXTDOMAIN);

    // Notice if the Elementor is not active
    if (!did_action('elementor/loaded')) {
        add_action('admin_notices', 'dynamic_animations_for_elementor_fail_load');
        return;
    }

    // Check required version
    $elementor_version_required = '1.8.0';
    if (!version_compare(ELEMENTOR_VERSION, $elementor_version_required, '>=')) {
        add_action('admin_notices', 'dynamic_animations_for_elementor_fail_load_out_of_date');
        return;
    }

    // Require the main plugin file
    require( __DIR__ . '/plugin.php' );
}

add_action('plugins_loaded', 'dynamic_animations_for_elementor_load');

function dynamic_animations_for_elementor_fail_load_out_of_date() {
    if (!current_user_can('update_plugins')) {
        return;
    }

    $file_path = 'elementor/elementor.php';

    $upgrade_link = wp_nonce_url(self_admin_url('update.php?action=upgrade-plugin&plugin=') . $file_path, 'upgrade-plugin_' . $file_path);
    $message = '<p>' . __('Dynamic Animations for Elementor is not working because you are using an old version of Elementor.', DAE_TEXTDOMAIN) . '</p>';
    $message .= '<p>' . sprintf('<a href="%s" class="button-primary">%s</a>', $upgrade_link, __('Update Elementor Now', DAE_TEXTDOMAIN)) . '</p>';

    echo '<div class="error">' . $message . '</div>';
}
