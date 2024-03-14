<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class WFACP_Plugin_Compatibilities
 * Loads all the compatibilities files we have to provide compatibility with each plugin
 */
class WFACP_Plugin_Compatibilities {
	public static $plugin_compatibilities = array();

	public static function load_all_compatibilities() {
		// load all the WFACP_Compatibilities files automatically

		add_action( 'plugins_loaded', [ __CLASS__, 'plugin_loaded' ], - 1 );
		add_action( 'after_setup_theme', [ __CLASS__, 'setup_theme' ], - 1 );
		add_action( 'wfacp_template_load', [ __CLASS__, 'template_found' ], - 1 );
	}

	public static function register( $object, $slug ) {
		self::$plugin_compatibilities[ $slug ] = $object;
	}

	public static function get_compatibility_class( $slug ) {
		return ( isset( self::$plugin_compatibilities[ $slug ] ) ) ? self::$plugin_compatibilities[ $slug ] : false;
	}

	public static function get_fixed_currency_price( $price, $currency = null ) {
		if ( ! empty( self::$plugin_compatibilities ) ) {
			foreach ( self::$plugin_compatibilities as $plugins_class ) {
				if ( $plugins_class->is_enable() && is_callable( array( $plugins_class, 'alter_fixed_amount' ) ) ) {
					return call_user_func( array( $plugins_class, 'alter_fixed_amount' ), $price, $currency );
				}
			}
		}

		return $price;
	}

	public static function include_files( $compatibilities_folder = [] ) {
		foreach ( $compatibilities_folder as $folder ) {
			foreach ( glob( plugin_dir_path( WFACP_PLUGIN_FILE ) . 'compatibilities/' . $folder . '/*.php' ) as $_field_filename ) {
				$basename = basename( $_field_filename );
				if ( false !== strpos( $basename, 'class-kirki.php' ) || false !== strpos( $basename, 'index.php' ) ) {
					continue;
				}
				require_once( $_field_filename );
			}
		}
	}

	public static function plugin_loaded() {
		$compatibilities_folder = [ 'gateways', 'plugins', 'others', 'library' ];
		self::include_files( $compatibilities_folder );
	}


	public static function setup_theme() {
		$compatibilities_folder = [ 'setup-theme', 'themes', 'ecrm', 'fields' ];
		self::include_files( $compatibilities_folder );
	}

	public static function template_found() {
		$compatibilities_folder = [ 'template-found' ];
		self::include_files( $compatibilities_folder );
	}
}

WFACP_Plugin_Compatibilities::load_all_compatibilities();
