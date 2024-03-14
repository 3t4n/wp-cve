<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;


/**
 * Ajax callback to refresh the calendar with new given data from JS
 *
 */
function wpsbc_refresh_calendar_editor() {

	if( empty( $_POST['action'] ) || $_POST['action'] != 'wpsbc_refresh_calendar_editor' ) {
		echo __( '', 'wp-simple-booking-calendar' );
		wp_die();
	}

	if( empty( $_POST['id'] ) ) {
		wp_die();
	}

	$_POST = stripslashes_deep( $_POST );

	/**
	 * The calendar data had to be JSON encoded due to the php input limit which limited
	 * the number of arrays that could be sent.
	 *
	 * If the calendar data would contain data for a number of years this would be chopped
	 *
	 */
	$_POST['calendar_data'] = ( ! empty( $_POST['calendar_data'] ) ? json_decode( $_POST['calendar_data'], true ) : array() );

	$calendar_id   = absint( $_POST['id'] );
	$calendar 	   = wpsbc_get_calendar( $calendar_id );

	$calendar_args = array();
	$calendar_data = ( ! empty( $_POST['calendar_data'] ) ? $_POST['calendar_data'] : array() );

	foreach( $_POST as $key => $val ) {

		if( in_array( $key, array_keys( wpsbc_get_calendar_output_default_args() ) ) )
			$calendar_args[$key] = sanitize_text_field( $val );

	}

	$calendar_outputter 	   = new WPSBC_Calendar_Outputter( $calendar, $calendar_args );
	$calendar_editor_outputter = new WPSBC_Calendar_Editor_Outputter( $calendar, $calendar_args, $calendar_data );

	$output = array(
		'calendar' 		  => $calendar_outputter->get_display(),
		'calendar_editor' => $calendar_editor_outputter->get_display()
	);

	echo json_encode( $output );
	wp_die();

}
add_action( 'wp_ajax_wpsbc_refresh_calendar_editor', 'wpsbc_refresh_calendar_editor' );


/**
 * Ajax callback to save the calendar events data in the database
 *
 */
function wpsbc_save_calendar_data() {

	$_POST = stripslashes_deep( $_POST );

	// Verify for nonce
    if (empty($_POST['wpsbc_token']) || !wp_verify_nonce($_POST['wpsbc_token'], 'wpsbc_save_calendar')) {
        return;
    }


	/**
	 * Parse the form data as name value pair
	 *
	 */
	if( empty( $_POST['form_data'] ) ) {

		echo add_query_arg( array( 'page' => 'wpsbc-calendars', 'wpsbc_message' => 'calendar_update_fail' ), admin_url( 'admin.php' ) );
		wp_die();

	}

	parse_str( $_POST['form_data'], $_POST['form_data'] );
	

	/**
	 * The calendar data had to be JSON encoded due to the php input limit which limited
	 * the number of arrays that could be sent.
	 *
	 * If the calendar data would contain data for a number of years this would be chopped
	 *
	 */
	$_POST['calendar_data'] = ( ! empty( $_POST['calendar_data'] ) ? json_decode( $_POST['calendar_data'], true ) : array() );

	if( empty( $_POST['form_data']['calendar_id'] ) ) {

		echo add_query_arg( array( 'page' => 'wpsbc-calendars', 'wpsbc_message' => 'calendar_update_fail' ), admin_url( 'admin.php' ) );
		wp_die();

	}

	/**
	 * Prepare variables
	 *
	 */
	$calendar_id   = absint( $_POST['form_data']['calendar_id'] );
	$calendar_name = sanitize_text_field( $_POST['form_data']['calendar_name'] );
	$calendar_data = ( ! empty( $_POST['calendar_data'] ) ? _wpsbc_array_wp_kses_post( $_POST['calendar_data'] ) : array() );

	/**
	 * Get default legend item
	 *
	 */
	$legend_items 		 = wpsbc_get_legend_items( array( 'calendar_id' => $calendar_id, 'is_default' => 1 ) );
	$default_legend_item = ( ! empty( $legend_items ) && is_array( $legend_items ) ? $legend_items[0] : null );

	// If there is no default legend item return with an error
	if( is_null( $default_legend_item ) ) {

		echo add_query_arg( array( 'page' => 'wpsbc-calendars', 'subpage' => 'edit-calendar', 'calendar_id' => $calendar_id, 'wpsbc_message' => 'calendar_update_fail' ), admin_url( 'admin.php' ) );
		wp_die();

	}


	/**
	 * Action hook to save extra calendar form data
	 *
	 * @param array $_POST
	 *
	 */
	do_action( 'wpsbc_save_calendar_data', $_POST );


	/**
	 * Handle calendar object data
	 *
	 */

	// Get calendar
	$calendar = wpsbc_get_calendar( $calendar_id );

	// Update calendar
	$update_data = array(
		'name' 			=> ( ! empty( $calendar_name ) ? $calendar_name : $calendar->get('name') ),
		'date_modified' => current_time( 'Y-m-d H:i:s' )
	);

	wpsbc_update_calendar( $calendar_id, $update_data );


	// Update Calendar Meta
	$settings = get_option( 'wpsbc_settings', array() );

	if(isset($settings['active_languages']) && count($settings['active_languages']) > 0){
		foreach($settings['active_languages'] as $language){
			wpsbc_update_calendar_meta($calendar_id, 'calendar_name_translation_' . $language, sanitize_text_field($_POST['form_data']['calendar_name_translation_' . $language]));
		}
	}
	

	/**
	 * Handle event objects data
	 *
	 */
	$events_args = array(
		'calendar_id' => $calendar_id
	);

	$events = wpsbc_get_events( $events_args );

	$events_data = array();

	// Prepare events data for saving
	foreach( $calendar_data as $year => $months ) {

		foreach( $months as $month => $days ) {

			foreach( $days as $day => $event_data ) {

				// Set event calendar id
				$event_data['calendar_id'] = $calendar_id;

				// Set event date data
				$event_data['date_year']  = $year;
				$event_data['date_month'] = $month;
				$event_data['date_day']   = $day;

				// Add event to events array
				if(checkdate($month, $day, $year)){
					$events_data[] = $event_data;
				}
			}
		}
	}
				

	// Save events data
	foreach( $events_data as $event_data ) {

		// Check to see if event exists
		$event = null;

		foreach( $events as $_event ) {

			if( $_event->get('date_year') == $event_data['date_year'] && $_event->get('date_month') == $event_data['date_month'] && $_event->get('date_day') == $event_data['date_day'] ) {
				$event = $_event;
				break;
			}

		}

		// Handle insert
		if( is_null( $event ) ) {

			if( $default_legend_item->get('id') == $event_data['legend_item_id'] && empty( $event_data['description'] ) && empty( $event_data['tooltip'] ) )
				continue;

			wpsbc_insert_event( $event_data );

		// Handle update
		} else {

			// For update we don't need the entire data set
			unset( $event_data['calendar_id'] );
			unset( $event_data['date_year'] );
			unset( $event_data['date_month'] );
			unset( $event_data['date_day'] );

			wpsbc_update_event( $event->get('id'), $event_data );

		}

	}

	/**
	 * Success redirect
	 *
	 */
	echo add_query_arg( array( 'page' => 'wpsbc-calendars', 'subpage' => 'edit-calendar', 'calendar_id' => $calendar_id, 'year' => (int)$_POST['current_year'], 'month' => (int)$_POST['current_month'], 'wpsbc_message' => 'calendar_update_success' ), admin_url( 'admin.php' ) );
	wp_die();

}
add_action( 'wp_ajax_wpsbc_save_calendar_data', 'wpsbc_save_calendar_data' );