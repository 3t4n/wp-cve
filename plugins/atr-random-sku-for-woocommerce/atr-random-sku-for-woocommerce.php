<?php
/*
 * Plugin Name: ATR structured sku generator for Woocommerce
 * Version: 2.0.1
 * Plugin URI: http://atarimtr.com
 * Description: This plugin adds a button to product edit/new page in Woocommerce that creates a random new sku. The suggested sku is checked against the DB to make sure it is not already taken.
 * Author: Yehuda Tiram
 * Author URI: http://atarimtr.com
 * Requires at least: 3.8
 * Tested up to: 5.8.2
 * WC requires at least: 2.6.0
 * WC tested up to: 6.0.0
 * Text Domain: atr-random-sku-for-woocommerce
 * Domain Path: /lang/
 *
 * @package WordPress
 * @author Yehuda Tiram
 * @since 1.0.0
 * All right reserved to AtarimTr LTD
 */

if ( ! defined( 'ABSPATH' ) ) exit;

// Load plugin class files
require_once( 'includes/class-atr-random-sku-for-woocommerce.php' );
require_once( 'includes/class-atr-random-sku-for-woocommerce-settings.php' );

// Load plugin libraries
require_once( 'includes/lib/class-atr-random-sku-for-woocommerce-admin-api.php' );

/**
 * Returns the main instance of ATR_random_sku_for_Woocommerce to prevent the need to use globals.
 *
 * @since  1.0.0
 * @return object ATR_random_sku_for_Woocommerce
 */
function ATR_random_sku_for_Woocommerce () {
	$instance = ATR_random_sku_for_Woocommerce::instance( __FILE__, '1.0.0' );

	if ( is_null( $instance->settings ) ) {
		$instance->settings = ATR_random_sku_for_Woocommerce_Settings::instance( $instance );
	}

	return $instance;
}

ATR_random_sku_for_Woocommerce();