<?php

/**
*
* Deprecated settings.
* @version 2.2.0
*
**/

defined( 'ABSPATH' ) || exit;

/**
*
* Backward compatibility for new installations.
* If old settings are still used (add-ons, third-party, etc.) and haven't been saved, return new settings or default.
*
*/

add_filter( 'default_option_easy_booking_settings', 'wceb_settings_legacy', 10, 2 );

function wceb_settings_legacy( $value, $option ) {

	$settings = array(
		'easy_booking_calc_mode'               => get_option( 'wceb_booking_mode' ) ? get_option( 'wceb_booking_mode' ) : 'nights',
		'easy_booking_all_bookable'            => get_option( 'wceb_all_bookable' ) ? get_option( 'wceb_all_bookable' ) : 'no',
		'easy_booking_dates'                   => get_option( 'wceb_number_of_dates' ) ? get_option( 'wceb_number_of_dates' ) : 'two',
		'easy_booking_duration'                => get_option( 'wceb_booking_duration' ) ? get_option( 'wceb_booking_duration' ) : '1',
		'easy_booking_custom_duration'         => get_option( 'wceb_custom_booking_duration' ) ? get_option( 'wceb_custom_booking_duration' ) : '1',
		'easy_booking_booking_min'             => get_option( 'wceb_booking_min' ) ? get_option( 'wceb_booking_min' ) : '0',
		'easy_booking_booking_max'             => get_option( 'wceb_booking_max' ) ? get_option( 'wceb_booking_max' ) : '0',
		'easy_booking_first_available_date'    => get_option( 'wceb_first_available_date' ) ? get_option( 'wceb_first_available_date' ) : '0',
		'easy_booking_last_available_date'     => get_option( 'wceb_last_available_date' ) ? get_option( 'wceb_last_available_date' ) : '1825',
		'easy_booking_first_day'               => get_option( 'start_of_week' ) ? get_option( 'start_of_week' ) : '1',
		'easy_booking_calendar_theme'          => get_option( 'wceb_calendar_theme' ) ? get_option( 'wceb_calendar_theme' ) : 'default',
		'easy_booking_background_color'        => get_option( 'wceb_background_color' ) ? get_option( 'wceb_background_color' ) : '#FFFFFF',
		'easy_booking_main_color'              => get_option( 'wceb_main_color' ) ? get_option( 'wceb_main_color' ) : '#0089EC',
		'easy_booking_text_color'              => get_option( 'wceb_text_color' ) ? get_option( 'wceb_text_color' ) : '#000000',
		'easy_booking_start_status'            => get_option( 'wceb_set_start_booking_status' ) ? get_option( 'wceb_set_start_booking_status' ) : 'automatic',
		'easy_booking_start_status_change'     => get_option( 'wceb_keep_start_status_for' ) ? get_option( 'wceb_keep_start_status_for' ) : '0',
		'easy_booking_processing_status'       => get_option( 'wceb_set_processing_booking_status' ) ? get_option( 'wceb_set_processing_booking_status' ) : 'automatic',
		'easy_booking_completed_status'        => get_option( 'wceb_set_completed_booking_status' ) ? get_option( 'wceb_set_completed_booking_status' ) : 'automatic',
		'easy_booking_completed_status_change' => get_option( 'wceb_keep_end_status_for' ) ? get_option( 'wceb_keep_end_status_for' ) : '0'
    );

	return $settings;

}