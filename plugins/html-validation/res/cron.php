<?php
/**
 * CRON FUNCTIONS
 **/

if ( ! defined( 'ABSPATH' ) ) {
	die;
}

// daily cron for scanning content.
add_action( 'html_validation_auto_scan_cron_hook', 'html_validation_auto_scan_cron', 10, 2 );

// initial scan should run in the background every 5 minutes.
add_action( 'html_validation_initial_scan_cron_hook', 'html_validation_auto_scan_cron', 10, 2 );


/**
 * Run cron scan
 **/
function html_validation_auto_scan_cron() {

	$progress = html_validation_check_progress();

	// create link inventory.
	if ( 0 == $progress ) {

		$completed = get_option( 'html_validation_completed_scan', '' );
		if ( '' == $completed ) {
			update_option( 'html_validation_completed_scan', '0' );
		} else {
			update_option( 'html_validation_completed_scan', '1' );
			// clear initial scan cron.
			wp_clear_scheduled_hook( 'html_validation_initial_scan_cron_hook' );
		}

		html_validation_inventory_links();
		html_validation_reset_scan_progress();
	}

	// scan links.
	html_validation_scan_links();
}

/**
 * Check scan progress
 ***/
function html_validation_check_progress() {
	global $wpdb;
	$results = $wpdb->get_results( $wpdb->prepare( 'SELECT * FROM ' . $wpdb->prefix . 'html_validation_links where scanflag = %d and linkignre = %d', 0, 0 ), ARRAY_A );

	if ( is_array( $results ) && count( $results ) == 0 ) {
		return 0;
	}
	return 1;
}

/**
 * Display scan progress
 **/
function html_validation_display_progress() {
	global $wpdb;
	$results = $wpdb->get_results( $wpdb->prepare( 'SELECT * FROM ' . $wpdb->prefix . 'html_validation_links where scanflag = %d and linkignre = %d', 1, 0 ), ARRAY_A );

	if ( is_array( $results ) ) {
		$completed = count( $results );
	}

	$results = $wpdb->get_results( $wpdb->prepare( 'SELECT * FROM ' . $wpdb->prefix . 'html_validation_links where linkignre = %d', 0 ), ARRAY_A );

	if ( is_array( $results ) ) {
		$total = count( $results );
	}

	$cron_scan_completed = get_option( 'html_validation_completed_scan', '' );

	if ( 0 != $total ) {
		echo '<div class="html_validation_progress"><i class="fas fa-chart-area" aria-hidden="true"></i> ';

		if ( '1' == $cron_scan_completed ) {
			echo '<span class="html_validation_completed">';
			esc_html_e( 'Initial Scan Completed  ', 'html-validation' );
			echo '</span>';
		} else {
			echo '<span class="html_validation_completed">';
			esc_html_e( ' Initial scan is progressing, please check back later.  ', 'html-validation' );
			echo '</span>';
		}

		esc_html_e( 'Progress: ', 'html-validation' );
		echo number_format( ( $completed / $total * 100 ), 0 );
		esc_html_e( '% complete ', 'html-validation' );

		echo '</div>';

	}
}



/**
 * Reset scan progress
 **/
function html_validation_reset_scan_progress() {
	global $wpdb;

	$wpdb->query( $wpdb->prepare( 'UPDATE ' . $wpdb->prefix . 'html_validation_links set scanflag = %d', 0 ) );
}


/**
 * Add new cron intervals
 **/
function html_validation_cron_intervals( $schedules ) {
	// add a 'weekly' interval.
	if ( ! array_key_exists( 'weekly', $schedules ) ) {
		$schedules['weekly'] = array(
			'interval' => 604800,
			'display'  => __( 'Once Weekly', 'html-validation' ),
		);
	}
	// add a 'monthly' interval.
	if ( ! array_key_exists( 'monthly', $schedules ) ) {
		$schedules['monthly'] = array(
			'interval' => 2635200,
			'display'  => __( 'Once a Month', 'html-validation' ),
		);
	}
	// add a '5 minute' interval.
	if ( ! array_key_exists( 'htmlvalidation5minutes', $schedules ) ) {
		$schedules['htmlvalidation5minutes'] = array(
			'interval' => 300,
			'display'  => __( 'Every 5 Minutes', 'html-validation' ),
		);
	}
	// add a '15 minute' interval.
	if ( ! array_key_exists( 'htmlvalidation15minutes', $schedules ) ) {
		$schedules['htmlvalidation15minutes'] = array(
			'interval' => 900,
			'display'  => __( 'Every 15 Minutes', 'html-validation' ),
		);
	}
	// add a '30 minute' interval.
	if ( ! array_key_exists( 'htmlvalidation30minutes', $schedules ) ) {
		$schedules['htmlvalidation30minutes'] = array(
			'interval' => 1800,
			'display'  => __( 'Every 30 Minutes', 'html-validation' ),
		);
	}
	return $schedules;
}
add_filter( 'cron_schedules', 'html_validation_cron_intervals' );

/**
 * Set cron frequency for cron scans
 **/
function html_validation_set_auto_scan_cron() {
	$setting = get_option( 'html_validation_cron_frequency', 'daily' );
	wp_clear_scheduled_hook( 'html_validation_auto_scan_cron_hook' );

	if ( 'false' != $setting ) {

		wp_schedule_event( time() + ( 60 * 5 ), $setting, 'html_validation_auto_scan_cron_hook' );
	}
}
