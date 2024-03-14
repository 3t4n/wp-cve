<?php
/**
 * DB upgrade function
 */


if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class WGDR_DB_Upgrade {

	protected $options_db_name = 'wgdr_plugin_options';

	public function __construct() {
	}

	public function run_options_db_upgrade() {

		// determine version and run version specific upgrade function
		// check if options db version zero by looking if the old entries are still there.
		if ( ( get_option( 'wgdr_plugin_options_1' ) ) or ( get_option( 'wgdr_plugin_options_2' ) ) or ( get_option( 'wgdr_plugin_options_3' ) ) ) {
			// error_log( 'current db version is zero ' );
			$this->upgrade_options_db_from_zero_to_1_point_zero();
		}
	}

	public function upgrade_options_db_from_zero_to_1_point_zero() {

		$option_name_1 = 'wgdr_plugin_options_1';
		$option_name_2 = 'wgdr_plugin_options_2';
		$option_name_3 = 'wgdr_plugin_options_3';

		// get current options

		// get option 1
		if ( ! ( get_option( $option_name_1 ) ) ) {
			$option_value_1 = "";
		} else {
			$option_value_1_array = get_option( $option_name_1 );
			$option_value_1       = $option_value_1_array['text_string'];
		}

		// get option 3
		if ( ! ( get_option( $option_name_3 ) ) ) {
			$option_value_3 = "";
		} else {
			$option_value_3_array = get_option( $option_name_3 );
			$option_value_3       = $option_value_3_array['text_string'];
		}

		// db version place options into new array

		$options_array = array(
			"conversion_id" => $option_value_1,
			"mc_prefix"     => $option_value_3,
		);

		// store new option array into the options table

		update_option( $this->options_db_name, $options_array );

		// delete old options
		// only on single site
		// we will run the multisite deletion only during uninstall

		// error_log( 'delete option name 1' );
		delete_option( $option_name_1 );
		// error_log( 'delete option name 2' );
		delete_option( $option_name_2 );
		// error_log( 'delete option name 3' );
		delete_option( $option_name_3 );

	}
}