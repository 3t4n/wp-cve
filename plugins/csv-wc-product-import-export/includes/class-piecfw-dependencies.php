<?php
/**
* Checks if WooCommerce is enabled
*/
if(!class_exists( 'PIECFW_Dependencies')){
	class PIECFW_Dependencies {
		private static $active_plugins;
		public static function init() {
			self::$active_plugins = (array) get_option( 'active_plugins', array() );
		}
		public static function woocommerce_active_check() {
			if ( ! self::$active_plugins ) self::init();
			return in_array( 'woocommerce/woocommerce.php', self::$active_plugins ) || array_key_exists( 'woocommerce/woocommerce.php', self::$active_plugins );
		}
	}
}