<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;


class MXMTZC_Basis_Plugin_Class
{

	private static $table_slug = MXMTZC_TABLE_SLUG;

	public static function activate()
	{

		// set set default options
		// self::create_option_for_activation();

	}

	public static function deactivate()
	{

		// Rewrite rules
		flush_rewrite_rules();

	}

	/*
	* This function sets the option in the table for CPT rewrite rules
	*/
	public static function create_option_for_activation()
	{

		// $array_of_default_options = array(

		// 	'clock_type' 		=> 'clock-face2.png',
		// 	'time_zone' 		=> 'Australia/Sydney',
		// 	'city_name' 		=> 'Wilton',
		// 	'time_format' 		=> '12',
		// 	'digital_clock' 	=> 'false',
		// 	'lang'				=> 'en',
		// 	'show_days' 		=> 'true',
		// 	'clock_font_size' 	=> ''

		// );

		// $time_zone_default_options = maybe_serialize( $array_of_default_options );

		// add_option( 'mxmtzc_time_zone_default_options', $time_zone_default_options );

	}

}