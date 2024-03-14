<?php
/**
 * Plugin Name: AWEOS WP Lock
 * Plugin URI: https://aweos.de
 * Description: This Plugin displays a coming soon page in development mode
 * Version: 1.2.2
 * Author: AWEOS GmbH
 * Author URI: https://aweos.de
 * License: GPLv2
 */
 
defined('ABSPATH') || exit;

require_once(plugin_dir_path(__FILE__) . 'check-requirements.php');

if (!wplock_requirement_are_ok()) {
    return;
}

require_once(plugin_dir_path(__FILE__) . 'vendor/autoload.php');

function loadAdminStyle() {
	wp_register_script('wplock-vue', plugins_url('', __FILE__) . '/node_modules/vue/dist/vue.min.js');
	wp_register_script('wplock-moment', plugins_url('', __FILE__) . '/node_modules/moment/min/moment-with-locales.min.js');
	wp_register_script('wplock-moment-load', plugins_url('', __FILE__) . '/js/moment-load.js', array('wplock-moment', 'wplock-vue'));

	wp_enqueue_script('wplock-vue');
	wp_enqueue_script('wplock-moment');
	wp_enqueue_script('wplock-moment-load');
}
function loadFrontendAndBackendStyle() {
	if (is_user_logged_in() && current_user_can('administrator')) {
		wp_register_style('wplock-backend', plugins_url('', __FILE__) . '/styles/backend-style.css');
		wp_enqueue_style('wplock-backend');
	}
}
add_action('admin_enqueue_scripts', 'loadAdminStyle');
add_action('wp_enqueue_scripts', 'loadFrontendAndBackendStyle');
add_action('admin_enqueue_scripts', 'loadFrontendAndBackendStyle');

class WpLock {
	public function hook() {
		require_once(plugin_dir_path(__FILE__) . 'includes/CsActivator.php');
		register_activation_hook(__FILE__, array('CsActivator', 'activate'));


		require_once(plugin_dir_path(__FILE__) . 'includes/FrontendMenu.php');
		add_action('admin_menu', array('FrontendMenu', 'create'));

		add_action('admin_post_update_wplock_settings', array('FrontendMenu', 'updateValues'));

		require_once(plugin_dir_path(__FILE__) . 'includes/Handler.php');
		$handler = new Handler(FrontendMenu::getOptions());
		add_action('template_redirect', array($handler, 'handle'));

		require_once(plugin_dir_path(__FILE__) . 'includes/AdminBarMenu.php');
		$adminBarMenu = new AdminBarMenu($handler);
		add_action('admin_bar_menu', array($adminBarMenu, 'display'));

		if ($handler->isActive()) {
			remove_filter('template_redirect', 'redirect_canonical');
		}

	}

}

$wpLock= new WpLock();
$wpLock->hook();
