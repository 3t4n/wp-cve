<?php
/**
 * WC Dependency Checker
 *
 * Checks if WooCommerce is enabled
 */
class Enhanced_Ajax_Add_To_Cart_Wc_Dependencies {

	private static $active_plugins;

	public static function init() {

		self::$active_plugins = (array) get_option( 'active_plugins', array() );

		if ( is_multisite() )
			self::$active_plugins = array_merge( self::$active_plugins, get_site_option( 'active_sitewide_plugins', array() ) );
	}

	public static function woocommerce_active_check() {

		if ( ! self::$active_plugins ) self::init();

		return in_array( 'woocommerce/woocommerce.php', self::$active_plugins ) || array_key_exists( 'woocommerce/woocommerce.php', self::$active_plugins );
	}

	public static function add_to_cart_pro_active_check() {

		if ( ! self::$active_plugins ) self::init();

		return in_array( 'add-to-cart-pro/add-to-cart-pro.php', self::$active_plugins ) || array_key_exists( 'add-to-cart-pro/add-to-cart-pro.php', self::$active_plugins );
	}
}