<?php
	/**
	 * Plugin Name: IURNY by INDIGITALL – WhatsApp Chat, Web Push Notifications (FREE)
	 * Plugin URI: https://documentation.iurny.com/reference/wordpress-plugin
	 * Description: Free plugin to communicate with your leads and customers by WhatsApp Chat and Web Push notifications.
	 * Version: 3.2.5
	 * Author: iurny by indigitall
	 * Author URI: https://iurny.com/
	 * License: GPLv2
	 */

	defined( 'ABSPATH' ) or die('This page may not be accessed directly.');

	define('IWP_PLUGIN_PATH', plugin_dir_path(__FILE__));
	define('IWP_PLUGIN_URL', plugin_dir_url(__FILE__));
	define('IWP_PLUGIN_REL_PATH', (dirname(plugin_basename(__FILE__)) . "/"));
	define('IWP_PLUGIN_BASENAME', plugin_basename(__FILE__));
	define('IWP_PLUGIN_MAIN_FILE', __FILE__);

	include_once IWP_PLUGIN_PATH . 'includes/iwp-activation.php';
	include_once IWP_PLUGIN_PATH . 'includes/iwp-deactivation.php';
	include_once IWP_PLUGIN_PATH . 'includes/iwp-language-init.php';
	include_once IWP_PLUGIN_PATH . 'includes/iwp-activated-plugin.php';
	include_once IWP_PLUGIN_PATH . 'includes/iwp-plugin-action-links.php';
	include_once IWP_PLUGIN_PATH . 'includes/iwp-plugin-row-meta.php';
	include_once IWP_PLUGIN_PATH . 'includes/iwp-ajax.php';

	if (is_admin()) {
		require_once IWP_PLUGIN_PATH . 'admin/iwpAdmin.php';
		add_action('init', array('iwpAdmin', 'init'));

		if( ! function_exists('get_plugin_data') ){
			require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
		}
		$plugin_data = get_plugin_data(__FILE__);
		define('IWP_PLUGIN_VERSION', $plugin_data['Version']);
		define('IWP_WORDPRESS_VERSION', get_bloginfo('version'));
	} else {
		$plugin_data = get_file_data(__FILE__, [
			'Version' => 'Version'
		], 'plugin');
		define('IWP_PLUGIN_VERSION', $plugin_data['Version']);

		require_once IWP_PLUGIN_PATH . 'public/iwpPublic.php';
		add_action('init', array('iwpPublic', 'init'));
	}
