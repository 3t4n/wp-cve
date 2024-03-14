<?php
// to check whether accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
//class to check dependency
class ELEX_HS_BASIC_Dependencies {

	private static $active_plugins;
	//fucntion to get all active plugins
	public static function init() {
		 self::$active_plugins = (array) get_option( 'active_plugins', array() );
		if ( is_multisite() ) {
			self::$active_plugins = array_merge( self::$active_plugins, get_site_option( 'active_sitewide_plugins' ), array() );
		}
	}
	
	//function to check woocommerce is active
	public static function woocommerce_active_check() {
		if ( ! self::$active_plugins ) {
			self::init();
		}
		return in_array( 'woocommerce/woocommerce.php', self::$active_plugins ) || array_key_exists( 'woocommerce/woocommerce.php', self::$active_plugins );
	}
}
