<?php
/**
 * Church Tithe WP
 *
 * @package     Church Tithe WP
 * @subpackage  Classes/Church Tithe WP
 * @copyright   Copyright (c) 2019, Church Tithe WP
 * @license     https://opensource.org/licenses/GPL-3.0 GNU Public License
 * @since       1.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Health Check vars.
 *
 * @return   array $health_checks
 */
function church_tithe_wp_get_health_check_vars() {

	// Only send in the health check variables if the wizard is complete or set to 'later'. Otherwise it shows a bunch of red Xs in the background.
	$current_wizard_status = get_option( 'church_tithe_wp_wizard_status' );

	if ( 'completed' !== $current_wizard_status && 'later' !== $current_wizard_status ) {

		$health_check_data = array(
			'health_checks'          => church_tithe_wp_heath_do_wizard( array() ),
			'total_unhealthy_checks' => 0,
		);

		return $health_check_data;
	}

	$health_check_data = array(
		'health_checks'          => array(),
		'total_unhealthy_checks' => 0,
	);

	$health_checks_and_wizard_steps = apply_filters( 'church_tithe_wp_health_checks_and_wizard_vars', array() );

	// Loop through each health check and only extract the health checks.
	foreach ( $health_checks_and_wizard_steps as $key => $health_check_or_wizard_step ) {
		$health_check_data = church_tithe_wp_add_health_check( $health_check_data, $health_check_or_wizard_step, $key );
	}

	$unhealthy = array();
	$healthy   = array();

	// Extract the unhealthy and healthy checks into an array.
	foreach ( $health_check_data['health_checks'] as $health_check_key => $health_check ) {

		if ( ! $health_check['is_healthy'] ) {
			$unhealthy[ $health_check_key ] = $health_check;
		} else {
			$healthy[ $health_check_key ] = $health_check;
		}
	}

	// Sort the unhealth checks by priority.
	uasort( $unhealthy, 'church_tithe_wp_sort_health_checks_by_priority' );

	// Add the healthy chcks to the end of the unhealthy ones.
	$health_check_data['health_checks'] = $unhealthy + $healthy;

	return $health_check_data;
}

/**
 * Callback function for the usort function which sorts health checks by priority.
 *
 * @since    1.0.0
 * @param    array $x A Health Check array.
 * @param    array $y A Health Check array.
 * @return   int How much to shift the health check.
 */
function church_tithe_wp_sort_health_checks_by_priority( $x, $y ) {
	return $x['priority'] - $y['priority'];
}

/**
 * Add a health check to the start or end of the health checks, depending on its health.
 *
 * @since    1.0.0
 * @param    array  $health_check_data All of the health check data.
 * @param    array  $health_check This specific health check being added.
 * @param    string $key The key to use for this health check.
 * @return   array $health_checks
 */
function church_tithe_wp_add_health_check( $health_check_data, $health_check, $key ) {

	// Only add if this is a health check.
	if ( ! $health_check['is_health_check'] ) {
		return $health_check_data;
	}

	$health_check = array(
		$key => $health_check,
	);

	$health_check_data['health_checks'] = $health_check + $health_check_data['health_checks'];

	// Increment the healthy check counter.
	if ( ! $health_check[ $key ]['is_healthy'] ) {
		$health_check_data['total_unhealthy_checks'] = $health_check_data['total_unhealthy_checks'] + 1;
	}

	return $health_check_data;
}
