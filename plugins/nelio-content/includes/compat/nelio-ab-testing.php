<?php
/**
 * Compatibility with Nelio A/B Testing.
 *
 * @package    Nelio_Content
 * @subpackage Nelio_Content/includes/compat
 * @author     Antonio Villegas <antonio.villegas@neliosoftware.com>
 * @since      3.0.3
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}//end if

require_once ABSPATH . 'wp-admin/includes/plugin.php';

function include_nab_tests( $events ) {
	global $wpdb;

	if ( ! is_plugin_active( 'nelio-ab-testing/nelio-ab-testing.php' ) ) {
		return $events;
	}//end if

	$sql_query =
		"SELECT *
		FROM
			{$wpdb->prefix}posts p
		WHERE
			p.post_type = 'nab_experiment' AND
			p.post_status <> 'trash' AND
			p.post_status <> 'nab_paused'";

	$results = $wpdb->get_results( $sql_query ); // phpcs:ignore

	$found_exps = array_map(
		function( $data ) {
			$id = absint( $data->ID );
			return nab_get_experiment( $id );
		},
		$results
	);

	$found_events = array_values( array_filter( array_map( // phpcs:ignore
		function ( $exp ) {
			if ( empty( $exp->get_start_date() ) ) {
				return null;
			}//end if

			$end_date     = get_end_date( $exp );
			$is_day_event = 'future' === $end_date ? true : gmdate( 'Ymd', strtotime( $exp->get_start_date() ) ) === gmdate( 'Ymd', strtotime( $end_date ) );

			return array(
				'id'              => 'nab-' . $exp->get_id(),
				'date'            => $exp->get_start_date(),
				'start'           => $exp->get_start_date(),
				'end'             => $end_date,
				'description'     => $exp->get_description(),
				'color'           => '#fff',
				'backgroundColor' => '#ac3626',
				'editLink'        => $exp->get_url(),
				'isDayEvent'      => $is_day_event,
				'title'           => $exp->get_name(),
				'type'            => 'nelio-ab-testing',
			);
		},
		$found_exps
	) ) ); // phpcs:ignore

	return array_merge( $events, $found_events );
}//end include_nab_tests()
add_filter( 'nelio_content_internal_events', 'include_nab_tests' );

// =======
// HELPERS
// =======

// phpcs:ignore
function get_end_date( $exp ) {
	if ( ! empty( $exp->get_end_date() ) ) {
		return $exp->get_end_date();
	}//end if

	if ( ! empty( $exp->get_start_date() ) && $exp->get_end_mode() === 'duration' ) {
		$days = $exp->get_end_value();
		$time = strtotime( $exp->get_start_date() );
		return gmdate( 'c', $time + ( $days * DAY_IN_SECONDS ) );
	}//end if

	return 'future';
}//end get_end_date()

