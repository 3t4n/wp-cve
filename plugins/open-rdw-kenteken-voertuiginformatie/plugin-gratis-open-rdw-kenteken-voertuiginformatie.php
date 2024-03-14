<?php

use Tussendoor\OpenRDW\Config;
use Tussendoor\OpenRDW\Plugin;

/**
 * Plugin Name:       Tussendoor - Open RDW (Gratis)
 * Plugin URI:        http://www.tussendoor.nl
 * Description:       Open RDW Kenteken voertuiginformatie for requesting and sending of vehicle information in WordPress.
 * Version:           3.0.1
 * Author:            Tussendoor internet & marketing
 * Author URI:        http://www.tussendoor.nl
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       open-rdw-kenteken-voertuiginformatie
 * Domain Path:       /languages
 * Tested up to:      6.0
 * Requires PHP:      7.4
 */

// If this file is called directly, abort.
if (! defined('WPINC')) {
    die;
}

require __DIR__.'/bootstrap.php';

global $dot_config;

if(!function_exists('get_plugin_data')){
    require_once(ABSPATH . 'wp-admin/includes/plugin.php');
}
$plugin_data = get_plugin_data(__FILE__);


$open_rdw_config_obj = new Config(plugin_basename(__FILE__), dirname(plugin_basename(__FILE__)), $plugin_data);
$dot_config = $open_rdw_config_obj->get_config();

$plugin = new Plugin();
register_activation_hook(__FILE__,  [$plugin, 'prevent_to_activate_pro_exists']);
register_deactivation_hook(__FILE__,  [$plugin, 'deactivate']);
add_action('activated_plugin', [$plugin, 'redirect_after_activation']);
add_action('plugins_loaded', [$plugin, 'boot']);

