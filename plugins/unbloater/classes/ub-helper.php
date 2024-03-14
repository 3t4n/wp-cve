<?php

Namespace Unbloater;

defined( 'ABSPATH' ) || die();

class Unbloater_Helper {
	
	/**
	 * Class constructor
	 */
	public function __construct() {
	}
	
	/**
	 * Check whether a minimum WordPress version is installed
	 */
	public static function is_wp_version_at_least( $version ) {
		return version_compare( get_bloginfo( 'version' ), $version, '>=' );
	}
	
	/**
	 * Check whether certain plugin(s) is active
	 * Returns true if any plugin in the given array is active
	 * Returns true if Unbloater is network activated and plugin is installed
	 */
	public static function is_plugin_active( $filenames ) {
		$plugins_to_check = Unbloater_Helper::is_ub_active_for_network() ? get_plugins() : apply_filters( 'active_plugins', get_option( 'active_plugins' ) );
		foreach( is_array( $filenames ) ? $filenames : (array)$filenames as $filename ) {
			if( array_key_exists( $filename, $plugins_to_check ) || in_array( $filename, $plugins_to_check ) )
				return true;
		}
		return false;
	}
	
	/**
	 * Check whether a certain Unbloater setting is active
	 */
	public static function is_option_activated( $option ) {
		if( Unbloater_Helper::is_ub_active_for_network() ) {
			return ( isset( get_network_option( null, 'unbloater_settings' )[$option] ) && get_network_option( null, 'unbloater_settings' )[$option] === '1' ) ? true : false;
		} else {
			return ( isset( get_option( 'unbloater_settings' )[$option] ) && get_option( 'unbloater_settings' )[$option] === '1' ) ? true : false;
		}
	}
	
	/**
	 * Check whether Unbloater is network-activated
	 */
	public static function is_ub_active_for_network() {
		if( !is_multisite() )
			return false;
		if( !function_exists( 'is_plugin_active_for_network' ) ) {
			require_once( ABSPATH . '/wp-admin/includes/plugin.php' );
		}
		return is_plugin_active_for_network( 'unbloater/unbloater.php' );
	}
	
}
