<?php
/**
 * Class to handle data migration
 *
 * @package SurferSEO
 */

namespace SurferSEO\Surfer\GSC;

use stdClass;

/**
 * Class to handle data migration
 */
class Surfer_GSC_Data_Migration {

	/**
	 * Object construct.
	 */
	public function __construct() {
		add_action( 'init', array( $this, 'init' ) );
	}

	/**
	 * Init function.
	 */
	public function init() {
		add_action( 'wp_ajax_surfer_transfer_gsc_data_to_new_format', array( $this, 'transfer_gsc_data_to_new_format_force' ) );
	}

	/**
	 * Ajax action to force data transfer from config.
	 */
	public function transfer_gsc_data_to_new_format_force() {

		if ( ! surfer_validate_ajax_request() ) {
			echo wp_json_encode( array( 'message' => 'Security check failed.' ) );
			wp_die();
		}

		$debug_data = $this->transfer_gsc_data_to_new_format();

		echo wp_json_encode( $debug_data );
		wp_die();
	}

	/**
	 * Transfers data from GSC from old format to new one.
	 */
	public function transfer_gsc_data_to_new_format() {

		global $wpdb;
		$archives = $wpdb->get_results( 'SELECT * FROM ' . $wpdb->prefix . 'postmeta WHERE meta_key = "surfer_post_traffic_archive"' );

		if ( ! $archives || count( $archives ) < 1 ) {
			return 'No data to transfer.';
		}

		$debug_data                   = array();
		$previous_record              = new stdClass();
		$previous_record->clicks      = false;
		$previous_record->impressions = false;
		$previous_record->position    = false;

		foreach ( $archives as $archove_row ) {
			$archive = json_decode( $archove_row->meta_value, false );
			$post_id = $archove_row->post_id;

			foreach ( $archive as $single_record ) {

				$clicks      = $single_record->clicks;
				$impressions = $single_record->impressions;
				$position    = false;

				$data_to_insert = array(
					'post_id'             => $post_id,
					'clicks'              => $clicks,
					'clicks_change'       => ( false === $previous_record->clicks ) ? null : $clicks - $previous_record->clicks,
					'impressions'         => $impressions,
					'impressions_change'  => ( false === $previous_record->impressions ) ? null : $impressions - $previous_record->impressions,
					'position'            => $position,
					'position_change'     => ( false === $previous_record->position ) ? null : $position - $previous_record->position,
					'data_gathering_date' => gmdate( 'Y-m-d', strtotime( 'this week monday', strtotime( $single_record->date ) ) ),
					'period_start_date'   => gmdate( 'Y-m-d', strtotime( 'previous monday', strtotime( $single_record->date ) ) ),
					'period_end_date'     => gmdate( 'Y-m-d', strtotime( 'previous sunday', strtotime( $single_record->date ) ) ),
				);

				$test         = $wpdb->insert( $wpdb->prefix . 'surfer_gsc_traffic', $data_to_insert );
				$debug_data[] = 'Post with id: ' . $post_id . ' was transferred with result: ' . $test;

				$previous_record->clicks      = $clicks;
				$previous_record->impressions = $impressions;
				$previous_record->position    = $position;
			}
		}

		return $debug_data;
	}
}
