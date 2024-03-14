<?php

/**
 * Plugin Name: Ultimate Store Kit
 * Plugin URI: https://bdthemes.com/ultimate-store-kit/
 * Description: <a href="https://bdthemes.com/ultimate-store-kit/">Ultimate Store Kit</a> is a packed of elementor widgets. This plugin gives you extra widgets features for elementor page builder plugin that help you to build an ecommerce website with woocommerce.
 * Version: 1.5.2
 * Author: BdThemes
 * Author URI: https://bdthemes.com/
 * Text Domain: ultimate-store-kit
 * Domain Path: /languages
 * License: GPL3
 * Elementor requires at least: 3.0.0
 * Elementor tested up to: 3.18.3
 */

// Some pre define value for easy use
define('BDTUSK_VER', '1.5.2');
define('BDTUSK__FILE__', __FILE__);
define('BDTUSK_PNAME', basename(dirname(BDTUSK__FILE__)));
define('BDTUSK_PBNAME', plugin_basename(BDTUSK__FILE__));
define('BDTUSK_PATH', plugin_dir_path(BDTUSK__FILE__));
define('BDTUSK_URL', plugins_url('/', BDTUSK__FILE__));
define('BDTUSK_MODULES_PATH', BDTUSK_PATH . 'modules/');
define('BDTUSK_INC_PATH', BDTUSK_PATH . 'includes/');
define('BDTUSK_ADMIN_PATH', BDTUSK_PATH . 'admin/');
define('BDTUSK_ADMIN_URL', BDTUSK_URL . 'admin/');
define('BDTUSK_ASSETS_URL', BDTUSK_URL . 'assets/');
define('BDTUSK_ASSETS_PATH', BDTUSK_PATH . 'assets/');
define('BDTUSK_MODULES_URL', BDTUSK_URL . 'modules/');
define('BDTUSK_ADM_PATH', BDTUSK_PATH . 'admin/');
define('BDTUSK_ADM_ASSETS_URL', BDTUSK_URL . 'admin/assets/');

define('BDTUSK_TITLE', 'Ultimate Store Kit');

/**
 * The code that runs during plugin activation.
 */
// function bdthemes_ultimate_store_kit_activate_plugin() {
// 	require_once plugin_dir_path(__FILE__) . 'includes/ultimate-store-kit-activator.php';
// 	Ultimate_Store_Kit_Activator::activate();
// }
// register_activation_hook(__FILE__, 'bdthemes_ultimate_store_kit_activate_plugin');



if (!function_exists('_is_usk_pro_installed')) {

	function _is_usk_pro_installed() {

		if (!function_exists('get_plugins')) {
			require_once ABSPATH . 'wp-admin/includes/plugin.php';
		}

		$file_path = 'ultimate-store-kit-pro/ultimate-store-kit-pro.php';
		$installed_plugins = get_plugins();

		return isset($installed_plugins[$file_path]);
	}
}

if (!function_exists('_is_usk_pro_activated')) {

	function _is_usk_pro_activated() {

		if (!function_exists('get_plugins')) {
			require_once ABSPATH . 'wp-admin/includes/plugin.php';
		}

		$file_path = 'ultimate-store-kit-pro/ultimate-store-kit-pro.php';
		$installed_plugins = get_plugins();

		if (is_plugin_active($file_path)) {
			return true;
		}

		return false;
	}
}

// Helper function here
require(dirname(__FILE__) . '/includes/helper.php');

if (!_is_usk_pro_activated()) {
	require_once BDTUSK_INC_PATH . 'class-pro-widget-map.php';
}

if (function_exists('usk_license_validation') && true !== usk_license_validation()) {
	require_once BDTUSK_INC_PATH . 'class-pro-widget-map.php';
}

require(dirname(__FILE__) . '/includes/utils.php');


/**
 * Plugin load here correctly
 * Also loaded the language file from here
 */
function bdthemes_ultimate_store_kit_load_plugin() {
	load_plugin_textdomain('ultimate-store-kit', false, basename(dirname(__FILE__)) . '/languages');

	if (!did_action('elementor/loaded')) {
		add_action('admin_notices', 'bdthemes_ultimate_store_kit_fail_load');
		return;
	}

	if ((!class_exists('Easy_Digital_Downloads')) && (!class_exists('woocommerce'))) {
		add_action('admin_notices', 'bdthemes_ultimate_store_kit_dependencies_plugin_fail_load');
		return;
	}

	// Widgets filters here
	require(BDTUSK_INC_PATH . 'ultimate-store-kit-filters.php');

	// Element pack widget and assets loader
	require(BDTUSK_PATH . 'loader.php');

	// Notice class
	require(BDTUSK_ADM_PATH . 'admin-notice.php');
}
add_action('plugins_loaded', 'bdthemes_ultimate_store_kit_load_plugin', 9);


/**
 * Check Elementor installed and activated correctly
 */
function bdthemes_ultimate_store_kit_fail_load() {
	$screen = get_current_screen();
	if (isset($screen->parent_file) && 'plugins.php' === $screen->parent_file && 'update' === $screen->id) {
		return;
	}

	$plugin = 'elementor/elementor.php';

	if (_is_dep_plugin_installed($plugin)) {
		if (!current_user_can('activate_plugins')) {
			return;
		}
		$activation_url = wp_nonce_url('plugins.php?action=activate&amp;plugin=' . $plugin . '&amp;plugin_status=all&amp;paged=1&amp;s', 'activate-plugin_' . $plugin);
		$admin_message = '<p>' . esc_html__('Ops! Ultimate Store Kit not working because you need to activate the Elementor plugin first.', 'ultimate-store-kit') . '</p>';
		$admin_message .= '<p>' . sprintf('<a href="%s" class="button-primary">%s</a>', $activation_url, esc_html__('Activate Elementor Now', 'ultimate-store-kit')) . '</p>';
	} else {
		if (!current_user_can('install_plugins')) {
			return;
		}
		$install_url = wp_nonce_url(self_admin_url('update.php?action=install-plugin&plugin=elementor'), 'install-plugin_elementor');
		$admin_message = '<p>' . esc_html__('Ops! Ultimate Store Kit not working because you need to install the Elementor plugin', 'ultimate-store-kit') . '</p>';
		$admin_message .= '<p>' . sprintf('<a href="%s" class="button-primary">%s</a>', $install_url, esc_html__('Install Elementor Now', 'ultimate-store-kit')) . '</p>';
	}

	printf('<div class="error">%1$s</div>', $admin_message);
}

/**
 * Check Woocommerce installed and activated correctly
 */
function bdthemes_ultimate_store_kit_dependencies_plugin_fail_load() {
	$screen = get_current_screen();
	if (isset($screen->parent_file) && 'plugins.php' === $screen->parent_file && 'update' === $screen->id) {
		return;
	}

	$plugin = 'woocommerce/woocommerce.php';





	if (_is_dep_plugin_installed($plugin)) {
		if (!current_user_can('activate_plugins')) {
			return;
		}
		$admin_message = '<p>' . esc_html__('Ops! Ultimate Store Kit not working because you need to activate a eCommerce plugin like WooCommerce or Easy Digital Downloads first.', 'ultimate-store-kit') . '</p>';
	} else {
		if (!current_user_can('install_plugins')) {
			return;
		}
		$admin_message = '<p>' . esc_html__('Ops! Ultimate Store Kit not working because you need to install a eCommerce plugin like WooCommerce or Easy Digital Download first.', 'ultimate-store-kit') . '</p>';
	}

	printf('<div class="error">%1$s</div>', $admin_message);
}


/**
 * Check Woocommerce installed and activated correctly
 */
function bdthemes_ultimate_store_kit_easy_digital_downloads_fail_load() {
	$screen = get_current_screen();
	if (isset($screen->parent_file) && 'plugins.php' === $screen->parent_file && 'update' === $screen->id) {
		return;
	}

	$plugin = 'easy-digital-downloads/easy-digital-downloads.php';

	if (_is_dep_plugin_installed($plugin)) {
		if (!current_user_can('activate_plugins')) {
			return;
		}
		$activation_url = wp_nonce_url('plugins.php?action=activate&amp;plugin=' . $plugin . '&amp;plugin_status=all&amp;paged=1&amp;s', 'activate-plugin_' . $plugin);
		$admin_message = '<p>' . esc_html__('Ops! Ultimate Store Kit not working because you need to activate the Easy Digital Donwlaods plugin first.', 'ultimate-store-kit') . '</p>';
		$admin_message .= '<p>' . sprintf('<a href="%s" class="button-primary">%s</a>', $activation_url, esc_html__('Activate Easy Digital Donwlaods Now', 'ultimate-store-kit')) . '</p>';
	} else {
		if (!current_user_can('install_plugins')) {
			return;
		}
		$install_url = wp_nonce_url(self_admin_url('update.php?action=install-plugin&plugin=easy-digital-downloads'), 'install-plugin_easy_digital_downloads');
		$admin_message = '<p>' . esc_html__('Ops! Ultimate Store Kit not working because you need to install the Easy Digital Downloads plugin', 'ultimate-store-kit') . '</p>';
		$admin_message .= '<p>' . sprintf('<a href="%s" class="button-primary">%s</a>', $install_url, esc_html__('Install Easy Digital Downloads Now', 'ultimate-store-kit')) . '</p>';
	}

	printf('<div class="error">%1$s</div>', $admin_message);
}

if (!function_exists('_is_dep_plugin_installed')) {

	/**
	 * @plug_slug string plugins slug which you want to check installed or not
	 */
	function _is_dep_plugin_installed($plugin_slug) {
		$file_path = $plugin_slug;
		$installed_plugins = get_plugins();

		return isset($installed_plugins[$file_path]);
	}
}
