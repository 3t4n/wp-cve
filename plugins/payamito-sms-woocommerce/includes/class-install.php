<?php

namespace Payamito\Woocommerce;

// don't call the file directly
if ( ! defined( 'ABSPATH' ) ) {
	die();
}

if ( ! class_exists( 'Install' ) ) :

	class Install
	{
		public static $core_version;
		public static $__FILE__;
		public static $core_path;

		public static function install( $core_version = PAYAMITO_WC_COR_VER, $__FILE__ = PAYAMITO_WC_PLUGIN_FILE, $core_path = PAYAMITO_WC_COR_DIR )
		{
			if ( ! is_blog_installed() ) {
				wp_die( 'WordPress is not already installed' );
			}
			self::$core_version = $core_version;
			self::$__FILE__     = $__FILE__;
			self::$core_path    = $core_path;
			set_transient( 'payamito_wc_installing', 'yes' );

			self::set_core_version();
		}

		private static function set_core_version()
		{
			$core_version = get_option( "payamito_core_version" );
			$dir_name     = self::get_fil_name( __DIR__ );
			$file_name    = basename( self::$__FILE__ );

			$update = [
				'version'       => self::$core_version,
				'absolute_path' => $dir_name . '/' . $file_name,
				'core_path'     => self::$core_path,
			];

			if ( $core_version === false ) {
				update_option( "payamito_core_version", serialize( $update ) );
			} else {
				$self_version  = self::$core_version;
				$other_version = unserialize( $core_version )['version'];

				if ( $self_version > $other_version ) {
					update_option( "payamito_core_version", serialize( $update ) );
				}
			}
		}

		private static function get_fil_name( $__DIR__ )
		{
			$dir_name = basename( dirname( $__DIR__, 1 ) );

			if ( $dir_name === 'plugins' ) {
				$dir_name = dirname( plugin_basename( __FILE__ ) );
			}

			return $dir_name;
		}
	}
endif;
