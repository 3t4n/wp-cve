<?php
/**
 * Plugin Name: XML for Google Merchant Center
 * Plugin URI: https://icopydoc.ru/category/documentation/xml-for-google-merchant-center/ 
 * Description: Connect your store to Google Merchant Center and unload products, getting new customers!
 * Version: 3.0.8
 * Requires at least: 4.5
 * Requires PHP: 7.0.0
 * Author: Maxim Glazunov
 * Author URI: https://icopydoc.ru
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: xml-for-google-merchant-center
 * Domain Path: /languages
 * Tags: xml, google, Google Merchant Center, export, woocommerce
 * WC requires at least: 3.0.0
 * WC tested up to: 8.3.1
 *
 * This program is free software; you can redistribute it and/or modify it under the terms of the GNU
 * General Public License version 2, as published by the Free Software Foundation. You may NOT assume
 * that you can use any other version of the GPL.
 *
 * This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without
 * even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 * 
 * Copyright 2018-2023 (Author emails: djdiplomat@yandex.ru, support@icopydoc.ru)
 */
defined( 'ABSPATH' ) || exit;

$nr = false;
// Check php version
if ( version_compare( phpversion(), '7.0.0', '<' ) ) { // не совпали версии
	add_action( 'admin_notices', function () {
		warning_notice( 'notice notice-error',
			sprintf(
				'<strong style="font-weight: 700;">%1$s</strong> %2$s 7.0.0 %3$s %4$s',
				'XML for Google Merchant Center',
				__( 'plugin requires a php version of at least', 'xml-for-google-merchant-center' ),
				__( 'You have the version installed', 'xml-for-google-merchant-center' ),
				phpversion()
			)
		);
	} );
	$nr = true;
}

// Check if WooCommerce is active
$plugin = 'woocommerce/woocommerce.php';
if ( ! in_array( $plugin, apply_filters( 'active_plugins', get_option( 'active_plugins', [] ) ) )
	&& ! ( is_multisite()
		&& array_key_exists( $plugin, get_site_option( 'active_sitewide_plugins', [] ) ) )
) {
	add_action( 'admin_notices', function () {
		warning_notice(
			'notice notice-error',
			sprintf(
				'<strong style="font-weight: 700;">XML for Google Merchant Center</strong> %1$s',
				__( 'requires WooCommerce installed and activated', 'xml-for-google-merchant-center' )
			)
		);
	} );
	$nr = true;
} else {
	// поддержка HPOS
	add_action( 'before_woocommerce_init', function () {
		if ( class_exists( \Automattic\WooCommerce\Utilities\FeaturesUtil::class) ) {
			\Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'custom_order_tables', __FILE__, true );
		}
	} );
}

if ( ! function_exists( 'warning_notice' ) ) {
	/**
	 * Display a notice in the admin Plugins page. Usually used in a @hook 'admin_notices'
	 * 
	 * @since	0.1.0
	 * 
	 * @param	string			$class (not require)
	 * @param	string 			$message (not require)
	 * 
	 * @return	string|void
	 */
	function warning_notice( $class = 'notice', $message = '' ) {
		printf( '<div class="%1$s"><p>%2$s</p></div>', $class, $message );
	}
}

// Define constants
define( 'XFGMC_PLUGIN_VERSION', '3.0.8' );

$upload_dir = wp_get_upload_dir();
// http://site.ru/wp-content/uploads
define( 'XFGMC_SITE_UPLOADS_URL', $upload_dir['baseurl'] );

// /home/site.ru/public_html/wp-content/uploads
define( 'XFGMC_SITE_UPLOADS_DIR_PATH', $upload_dir['basedir'] );

// http://site.ru/wp-content/uploads/xfgmc
define( 'XFGMC_PLUGIN_UPLOADS_DIR_URL', $upload_dir['baseurl'] . '/xfgmc' );

// /home/site.ru/public_html/wp-content/uploads/xfgmc
define( 'XFGMC_PLUGIN_UPLOADS_DIR_PATH', $upload_dir['basedir'] . '/xfgmc' );
unset( $upload_dir );

// http://site.ru/wp-content/plugins/xml-for-google-merchant-center/
define( 'XFGMC_PLUGIN_DIR_URL', plugin_dir_url( __FILE__ ) );

// /home/p135/www/site.ru/wp-content/plugins/xml-for-google-merchant-center/
define( 'XFGMC_PLUGIN_DIR_PATH', plugin_dir_path( __FILE__ ) );

// /home/p135/www/site.ru/wp-content/plugins/xml-for-google-merchant-center/xml-for-google-merchant-center.php
define( 'XFGMC_PLUGIN_MAIN_FILE_PATH', __FILE__ );

// xml-for-google-merchant-center - псевдоним плагина
define( 'XFGMC_PLUGIN_SLUG', wp_basename( dirname( __FILE__ ) ) );

// xml-for-google-merchant-center/xml-for-google-merchant-center.php - полный псевдоним плагина (папка плагина + имя главного файла)
define( 'XFGMC_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );

// $nr = apply_filters('xfgmc_f_nr', $nr);

// load translation
add_action( 'plugins_loaded', function () {
	load_plugin_textdomain( 'xml-for-google-merchant-center', false, dirname( XFGMC_PLUGIN_BASENAME ) . '/languages/' );
} );

if ( false === $nr ) {
	unset( $nr );
	require_once XFGMC_PLUGIN_DIR_PATH . '/packages.php';
	register_activation_hook( __FILE__, [ 'XmlforGoogleMerchantCenter', 'on_activation' ] );
	register_deactivation_hook( __FILE__, [ 'XmlforGoogleMerchantCenter', 'on_deactivation' ] );
	add_action( 'plugins_loaded', [ 'XmlforGoogleMerchantCenter', 'init' ], 10 ); // активируем плагин
	define( 'XFGMC_ACTIVE', true );
}