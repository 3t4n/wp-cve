<?php
/*
Plugin Name: MyBookProgress - Stormhill Media
Plugin URI: http://www.mybookprogress.com/
Description: A WordPress plugin, originally created by Author Media, which helps authors show off their progress with customized progress bars, gain subscribers, and hit deadlines.
Author: Stormhill Media
Author URI: http://www.stormhillmedia.com
Version: 1.0.8
*/

define("MBP_VERSION", "1.0.8");
define("MBP_ROOT", __FILE__);

require_once(dirname(__FILE__).'/includes/functions.php');
require_once(dirname(__FILE__).'/includes/admin.php');
require_once(dirname(__FILE__).'/includes/frontend.php');
require_once(dirname(__FILE__).'/includes/shortcodes.php');
require_once(dirname(__FILE__).'/includes/widgets.php');

/*---------------------------------------------------------*/
/* Initialize Plugin                                       */
/*---------------------------------------------------------*/

function mbp_init() {
	mbp_load_settings();
	mbp_detect_updates();
	mbp_customize_plugins_page();

	do_action('mbp_init');
}
add_action('plugins_loaded', 'mbp_init');

function mbp_plugin_activate() { mbp_init(); do_action('mbp_plugin_activate'); }
register_activation_hook(__FILE__, 'mbp_plugin_activate');
function mbp_plugin_deactivate() { do_action('mbp_plugin_deactivate'); }
register_deactivation_hook(__FILE__, 'mbp_plugin_deactivate');

function mbp_customize_plugins_page() {
	add_filter('plugin_action_links_'.plugin_basename(__FILE__), 'mbp_plugin_action_links');
	add_filter('plugin_row_meta', 'mbp_plugin_row_meta', 10, 2);
}

function mbp_plugin_action_links($actions) {
	unset($actions['edit']);
	$actions['settings'] = '<a href="'.mbp_admin_page_url().'">'.__('Settings', 'mybookprogress').'</a>';
	$actions['review'] = '<a target="_blank" href="http://wordpress.org/support/view/plugin-reviews/mybookprogress?filter=5#postform">'.__('Review this plugin', 'mybookprogress').'</a>';
	return $actions;
}

function mbp_plugin_row_meta($links, $file) {
	if($file == plugin_basename(__FILE__)) {
		$links[] = '<a target="_blank" href="http://wordpress.org/support/view/plugin-reviews/mybookprogress?filter=5#postform">'.__('Review this plugin', 'mybookprogress').'</a>';
	}
	return $links;
}
