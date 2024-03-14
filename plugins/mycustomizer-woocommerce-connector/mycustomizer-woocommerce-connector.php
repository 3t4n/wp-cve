<?php
/*
Plugin Name: Kickflip Product Customizer
Plugin URI: http://plugins.svn.wordpress.org/mycustomizer-woocommerce-connector/
description: Build an outstanding product configurator for your ecommerce
Version: 1.0.20
Author: Kickflip
Author URI: https://gokickflip.com/
License: GPL3
 */
// This is executed on install only
require __DIR__ . '/src/Install/MczrInstall.php';
$vendor      = __DIR__ . '/vendor';
$install     = new \MyCustomizer\WooCommerce\Connector\Install\MczrInstall();

require_once __DIR__ . '/vendor/autoload.php';

use MyCustomizer\WooCommerce\Connector\Auth\MczrAccess;

MczrAccess::isAuthorized();

function initMczrPlugin() {
	try {
		$plugin = new \MyCustomizer\WooCommerce\Connector\MczrPlugin();

		register_activation_hook( __FILE__, array( $plugin, 'onActivate' ) );

		// Could not init : TODO : log
		if ( ! in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
			return;
		}
		// GENERAL
		add_action( 'plugins_loaded', array( $plugin, 'init' ) );
		add_action( 'plugins_loaded', array( new \MyCustomizer\WooCommerce\Connector\Controller\MczrCartController(), 'init' ) );

		// ADMIN
		add_action( 'plugins_loaded', array( new \MyCustomizer\WooCommerce\Connector\Libs\MczrFlashMessage(), 'init' ) );
		add_action( 'plugins_loaded', array( new \MyCustomizer\WooCommerce\Connector\Api\MczrShopApi(), 'init' ) );
		add_action( 'plugins_loaded', array( new \MyCustomizer\WooCommerce\Connector\Controller\MczrAssetController(), 'init' ) );
		add_action( 'plugins_loaded', array( new \MyCustomizer\WooCommerce\Connector\Controller\MczrIframeController(), 'init' ) );
		add_action( 'plugins_loaded', array( new \MyCustomizer\WooCommerce\Connector\Controller\MczrOrderController(), 'init' ) );
		add_action( 'plugins_loaded', array( new \MyCustomizer\WooCommerce\Connector\Controller\MczrTemplateController(), 'init' ) );
		add_action( 'plugins_loaded', array( new \MyCustomizer\WooCommerce\Connector\Controller\MczrSocialController(), 'init' ) );

		// FRONT
		add_action( 'plugins_loaded', array( new \MyCustomizer\WooCommerce\Connector\Controller\Admin\MczrOrderController(), 'init' ) );
		add_action( 'plugins_loaded', array( new \MyCustomizer\WooCommerce\Connector\Controller\Admin\MczrSettingController(), 'init' ) );

		// Woocommerce needs to be loaded first, then load WC_Product_Mczr
		add_action(
			'plugins_loaded',
			function () {
				$load = new \MyCustomizer\WooCommerce\Connector\Types\WC_Product_Mczr();
				$load->init();
			}
		);
	} catch ( \Exception $ex ) {
		error_log( $ex->getMessage() );
		echo esc_attr( $ex->getMessage() );
		exit;
	}
}

initMczrPlugin();
