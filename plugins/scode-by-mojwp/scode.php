<?php
/**
Plugin Name: sCode (Easy Shortcodes)
Plugin URI: https://wpshop.ru/
Description: Easy way to creat and manage shortcode from Admin panel site.
Author: WPShop.ru
Version: 1.1.5
Author URI: https://wpshop.ru/
Text Domain: scode
Domain Path: /languages
License: GPL
 */

if (!function_exists('add_action')) {
    echo 'Hi there! I\'m just a plugin, not much I can do when called directly.';
    exit;
}

define('SCODE_VERSION', '1.1.5');
define('SCODE_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('SCODE_PLUGIN_URL', plugin_dir_url(__FILE__));

add_action('plugins_loaded', 'scode_texdomain_init');
function scode_texdomain_init() {
    load_plugin_textdomain('scode', false, dirname(plugin_basename(__FILE__)).'/languages/' );
}


register_activation_hook(__FILE__, 'scode_activation');
register_deactivation_hook(__FILE__, 'scode_deactivation');
register_uninstall_hook(__FILE__, 'scode_uninstall');

require_once(SCODE_PLUGIN_DIR . '/includes/functions.php');
require_once(SCODE_PLUGIN_DIR . '/includes/global.php');
if (is_admin()) {
    require_once(SCODE_PLUGIN_DIR . '/includes/admin.php');
    require_once(SCODE_PLUGIN_DIR . '/includes/ajax.php');
} else
    require_once(SCODE_PLUGIN_DIR . '/includes/frontend.php');

function scode_activation() {
    global $wpdb;

    $create_shortcodes_table = "CREATE TABLE IF NOT EXISTS `".$wpdb->prefix."shortcodes` (
	`shortcode_id` int(11) NOT NULL AUTO_INCREMENT,
	`group_id` int(11) NOT NULL,
	`code` varchar(32) NOT NULL,
	`description` varchar(255) NOT NULL,
	`value` text NOT NULL,
	PRIMARY KEY (`shortcode_id`),
	UNIQUE KEY `code` (`code`)
	) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;";

    $create_shortcodes_groups_table = "CREATE TABLE IF NOT EXISTS `".$wpdb->prefix."shortcodes_groups` (
	`group_id` int(11) NOT NULL AUTO_INCREMENT,
	`group_name` varchar(100) NOT NULL,
	PRIMARY KEY (`group_id`)
	) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($create_shortcodes_table);
    dbDelta($create_shortcodes_groups_table);
}

function scode_deactivation() {
    // удаляем опции
}

function scode_uninstall() {
    global $wpdb;

    $wpdb->query("DROP TABLE `".$wpdb->prefix."shortcodes`");
    $wpdb->query("DROP TABLE `".$wpdb->prefix."shortcodes_groups`");
}

?>