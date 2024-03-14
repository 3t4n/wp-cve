<?php
/**
 * Compatibility with The Events Calendar.
 *
 * @package    Nelio_Content
 * @subpackage Nelio_Content/includes/compat
 * @author     Antonio Villegas <antonio.villegas@neliosoftware.com>
 * @since      2.5.1
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}//end if

require_once ABSPATH . 'wp-admin/includes/plugin.php';

function include_events( $events ) {
	global $wpdb;

	if ( ! is_plugin_active( 'the-events-calendar/the-events-calendar.php' ) ) {
		return $events;
	}//end if

	$sql_query =
		"SELECT *
		FROM
			{$wpdb->prefix}posts p
		WHERE
			p.post_type = 'tribe_events'";

	$results = $wpdb->get_results( $sql_query ); // phpcs:ignore

	$found_events = array_map(
		function ( $data ) {
			$id = absint( $data->ID );
			return array(
				'id'              => 'tribe_event-' . $id,
				'date'            => 'draft' === $data->status ? null : $data->post_date_gmt,
				'start'           => 'draft' === $data->status ? null : get_post_meta( $id, '_EventStartDateUTC', true ) . ' +00:00',
				'end'             => 'draft' === $data->status ? null : get_post_meta( $id, '_EventEndDateUTC', true ) . ' +00:00',
				'description'     => $data->post_excerpt,
				'color'           => '#fff',
				'backgroundColor' => '#334aff',
				'editLink'        => admin_url( 'post.php?post=' . $id . '&action=edit' ),
				'isDayEvent'      => get_post_meta( $id, '_EventAllDay', true ) === 'yes',
				'title'           => $data->post_title,
				'type'            => 'the-events-calendar',
			);
		},
		$results
	);

	return array_merge( $events, $found_events );
}//end include_events()
add_filter( 'nelio_content_internal_events', 'include_events' );

