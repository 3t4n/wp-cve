<?php


class KcSeoFunctions {
	static function isYoastActive() {
		if ( in_array( 'wordpress-seo/wp-seo.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
			return true;
		}

		return false;
	}

	static function isWcActive() {
		return class_exists( 'woocommerce' );
	}

	public static function isEddActive() {
		return class_exists( 'Easy_Digital_Downloads' );
	}

}