<?php

namespace EasyBooking;

/**
*
* Compatibility with third-party plugins.
* @version 3.1.0
*
**/

defined( 'ABSPATH' ) || exit;

class Third_Party_Plugins {

	private static $active_plugins;

	public function __construct() {

		self::$active_plugins = (array) get_option( 'active_plugins', array() );

		if ( is_multisite() ) {
			self::$active_plugins = array_merge( self::$active_plugins, get_site_option( 'active_sitewide_plugins', array() ) );
		}

        // Maybe load WC Product Add-Ons files.
        if ( true === self::wc_pao_is_active() ) {
            include_once( 'plugins/class-wc-product-addons-functions.php' );
            include_once( 'plugins/wc-product-addons.php' );
        }

        // Maybe load WC Product Bundles files.
        if ( true === self::wc_pb_is_active() ) {
            include_once( 'plugins/class-wc-product-bundles-functions.php' );
        }

        // Maybe load WC Deposits files.
        if ( true === self::wc_deposits_is_active() ) {
            include_once( 'plugins/wc-deposits.php' );
        }

        // Maybe load Polylang files.
        if ( true === self::polylang_is_active() ) {
            include_once( 'plugins/polylang.php' );
        }

	}

	/**
    *
    * Check if WooCommerce Product Add-Ons is active.
    * @return bool
    *
    **/
    public static function wc_pao_is_active() {

        if ( ! self::$active_plugins ) {
			self::init();
		}

        return ( array_key_exists( 'woocommerce-product-addons/woocommerce-product-addons.php', self::$active_plugins ) || in_array( 'woocommerce-product-addons/woocommerce-product-addons.php', self::$active_plugins ) );

    }

    /**
    *
    * Check if WooCommerce Product Bundles is active.
    * @return bool
    *
    **/
    public static function wc_pb_is_active() {

        if ( ! self::$active_plugins ) {
			self::init();
		}

        return ( array_key_exists( 'woocommerce-product-bundles/woocommerce-product-bundles.php', self::$active_plugins ) || in_array( 'woocommerce-product-bundles/woocommerce-product-bundles.php', self::$active_plugins ) );

    }

    /**
    *
    * Check if WooCommerce Deposits is active.
    * @return bool
    *
    **/
    public static function wc_deposits_is_active() {

        if ( ! self::$active_plugins ) {
            self::init();
        }

        return ( array_key_exists( 'woocommerce-deposits/woocommerce-deposits.php', self::$active_plugins ) || in_array( 'woocommerce-deposits/woocommerce-deposits.php', self::$active_plugins ) );

    }

    /**
    *
    * Check if Polylang is active.
    * @return bool
    *
    **/
    public static function polylang_is_active() {

        if ( ! self::$active_plugins ) {
            self::init();
        }

        return ( array_key_exists( 'polylang/polylang.php', self::$active_plugins ) || in_array( 'polylang/polylang.php', self::$active_plugins ) || array_key_exists( 'polylang-pro/polylang.php', self::$active_plugins ) || in_array( 'polylang-pro/polylang.php', self::$active_plugins ) );

    }

    /**
    *
    * Check if WPML is active.
    * @return bool
    *
    **/
    public static function wpml_is_active() {

        if ( ! self::$active_plugins ) {
            self::init();
        }

        return ( array_key_exists( 'sitepress-multilingual-cms/sitepress.php', self::$active_plugins ) || in_array( 'sitepress-multilingual-cms/sitepress.php', self::$active_plugins ) );

    }

}

new Third_Party_Plugins();