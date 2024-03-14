<?php
/**
 * Responsible for managing ajax endpoints for tabs.
 *
 * @since 3.0.0
 * @package SWPTLS
 */

namespace SWPTLS\Ajax;

// If direct access than exit the file.
defined( 'ABSPATH' ) || exit;

/**
 * Responsible for handling table operations.
 *
 * @since 3.0.0
 * @package SWPTLS
 */
class Tabs {

	/**
	 * Constructor.
	 */
	public function __construct() {
		add_action( 'wp_ajax_gswpts_ud_tab', [ $this, 'update_name' ] );
	}

	/**
	 * Delete table data from the DB.
	 *
	 * @param int $id The id to delete.
	 * @return void
	 */
	public function delete( $id ) {
		global $wpdb;

		$table    = $wpdb->prefix . 'gswpts_tabs';
		$response = swptls()->database->delete( $table, $id );

		if ( $response ) {
			wp_send_json_success([
				'output' => __( 'Tab deleted successfully', 'sheetstowptable' ),
				'type'   => 'deleted',
			]);
		}

		wp_send_json_error([
			'output' => __( 'Unable to delete tab.', 'sheetstowptable' ),
			'type'   => 'invalid_action',
		]);
	}

	/**
	 * Performs updates on tables and tabs.
	 *
	 * @param string $name    The name to update.
	 * @param int    $id      The id where to update.
	 */
	public function update_name( $name, $id ) {
		global $wpdb;

		$table  = $wpdb->prefix . 'gswpts_tabs';
		$data   = [ 'tab_name' => $name ];
		$output = __( 'Tab name updated successfully', 'sheetstowptable' );

		$response = $wpdb->update(
			$table,
			$data,
			[ 'id' => $id ],
			[ '%s' ],
			[ '%d' ]
		);

		if ( $response ) {
			wp_send_json_success([
				'output' => $output,
				'type'   => 'updated',
			]);
		}

		wp_send_json_success([
			'output' => __( 'Could not update the data.', 'sheetstowptable' ),
			'type'   => 'invalid_action',
		]);
	}
}
