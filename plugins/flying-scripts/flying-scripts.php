<?php
/**
 * Plugin Name: Flying Scripts
 * Plugin URI: https://wordpress.org/plugins/flying-scripts/
 * Description: Download and execute JavaScript on user interaction.
 * Author: WP Speed Matters
 * Author URI: https://wpspeedmatters.com/
 * Version: 1.2.3
 * Text Domain: flying-scripts
 */

// If this file is called directly, abort.
if (! defined('WPINC')) {
    die;
}

// Define constant with current version
define('FLYING_SCRIPTS_VERSION', '1.2.3');

include('init-config.php');
include('settings/index.php');
include('inject-js.php');
include('html-rewrite.php');
include('shortcuts.php');
add_filter('plugin_action_links_' . plugin_basename(__FILE__), 'flying_scripts_add_shortcuts');