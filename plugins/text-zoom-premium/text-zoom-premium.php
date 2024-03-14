<?php
/**
 * Plugin Name: Text ZOOM Premium
 * Plugin URI: https://wordpress.org/plugins/text-zoom-premium
 * Description: Text ZOOM Premium provides your users with the ability to personalize the font size
 * Version: 4.0.1
 * Requires at least: 4.7
 * Requires PHP: 7.1
 * Author:      abilitools
 * Author URI:  https://abilitools.com
 * License:     GPL2
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 */

if (!defined('WPINC')) die('No access outside of wordpress.');

// pzat -> Premium Zoom AbiliTools
if (!defined('PZAT_DIR')) {
  define('PZAT_DIR', plugin_dir_path(__FILE__));
}

if (!defined('PZAT_PLUGIN_NAME')) {
  define('PZAT_PLUGIN_NAME', 'Text Zoom');
}

if (!defined('PZAT_ASSETS_URL')) {
  define('PZAT_ASSETS_URL', plugins_url('assets/', __FILE__));
}

if (!defined('PZAT_INCLUDES_DIR')) {
  define('PZAT_INCLUDES_DIR', PZAT_DIR . 'includes/');
}

if (!defined('PZAT_PLUGIN_BASENAME')) {
  define('PZAT_PLUGIN_BASENAME', plugin_basename(__FILE__));
}

foreach (glob(PZAT_INCLUDES_DIR . "*.php") as $filename) {
  include($filename);
}

register_activation_hook(__FILE__, 'pzat_activate');
register_deactivation_hook(__FILE__, 'pzat_deactivate');
register_uninstall_hook(__FILE__, 'pzat_uninstall');
