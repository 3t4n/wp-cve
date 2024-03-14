<?php
/**
 * Compatibility with MailPoet.
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

function include_newsletters( $events ) {
	global $wpdb;

	if ( ! is_plugin_active( 'mailpoet/mailpoet.php' ) ) {
		return $events;
	}//end if

	$sql_query =
		"SELECT *
		FROM
			{$wpdb->prefix}mailpoet_newsletters n,
			{$wpdb->prefix}mailpoet_newsletter_option o
		WHERE
			n.type = 'standard' AND
			n.deleted_at IS NULL AND
			o.newsletter_id = n.id AND
			o.option_field_id = 2";

	$results = $wpdb->get_results( $sql_query ); // phpcs:ignore

	$newsletters = array_map(
		function ( $data ) {
			$date = 'draft' === $data->status ? null : gmdate( 'c', strtotime( $data->value ) );
			$date = ! empty( $data->sent_at ) ? gmdate( 'c', strtotime( $data->sent_at ) ) : $date;
			return array(
				'id'              => 'mp-newsletter-' . absint( $data->newsletter_id ),
				'date'            => $date,
				'description'     => $data->preheader,
				'color'           => '#fff',
				'backgroundColor' => '#ff6900',
				'editLink'        => admin_url( 'admin.php?page=mailpoet-newsletter-editor&id=' . absint( $data->newsletter_id ) ),
				'isDayEvent'      => false,
				'title'           => $data->subject,
				'type'            => 'mailpoet-newsletter',
			);
		},
		$results
	);

	return array_merge( $events, $newsletters );
}//end include_newsletters()
add_filter( 'nelio_content_internal_events', 'include_newsletters' );

