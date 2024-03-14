<?php

namespace SG_Email_Marketing\Services\Cron;

/**
 * Class responsible for the Cron functionality.
 */
class Cron {

	/**
	 * Hook.
	 *
	 * @var string
	 *
	 * @since 1.0.0
	 */
	protected $hook = 'sg_email_marketing_send_data';

	/**
	 * Batch size.
	 *
	 * @var string
	 *
	 * @since 1.0.0
	 */
	const BATCH_SIZE = 50;

	/**
	 * The constructor.
	 *
	 * @since 1.0.0
	 *
	 * @param {Background_Process} $background_process Background_Process instance.
	 */
	public function __construct( $background_process ) {
		$this->background_process = $background_process;
	}

	/**
	 * Schedule a cron job to collect the data.
	 *
	 * @since  1.0.0
	 */
	public function schedule() {
		if ( ! wp_next_scheduled( $this->hook ) ) {
			wp_schedule_event( time(), 'twicedaily', $this->hook );
		}
	}

	/**
	 * Prepare the request for the bg processing.
	 *
	 * @since  1.0.0
	 */
	public function prepare_request() {
		$data = $this->get_data();

		if ( empty( $data ) ) {
			return;
		}

		foreach ( $data as $chunk ) {
			$chunk = array_map( 'unserialize', $chunk );

			$this->background_process->push_to_queue( $chunk );
			$this->background_process->save();
		}

		$this->background_process->dispatch();
	}

	/**
	 * Get data from the database.
	 *
	 * @since  1.0.0
	 *
	 * @return array The data to be send.
	 */
	private function get_data() {
		global $wpdb;

		$results = $wpdb->get_results(
			"
			SELECT `meta_value`
				FROM $wpdb->postmeta 
			WHERE `meta_key` = 'sg_email_marketing_user_data'
			UNION ALL
			SELECT `meta_value`
				FROM $wpdb->usermeta 
			WHERE `meta_key` = 'sg_email_marketing_user_data'
			UNION ALL
			SELECT `meta_value`
				FROM $wpdb->commentmeta 
			WHERE `meta_key` = 'sg_email_marketing_user_data'
			"
		);
		foreach( $results as $index => $result ) {
			if ( is_array( $result->meta_value ) ) {
				continue;
			}

			$result->meta_value = unserialize( $result->meta_value );

			if ( ! isset( $result->meta_value['timestamp'] ) ) {
				foreach( $result->meta_value as $entry ) {
					$results[] = array( 'meta_value' => $entry );
				}
				unset( $results[$index] );
			}
		}

		return array_chunk( array_map( 'serialize', array_column( $results, 'meta_value' ) ), self::BATCH_SIZE );
	}

	/**
	 * Delete the meta data.
	 *
	 * @since  1.0.0
	 */
	public static function delete_meta_data() {
		global $wpdb;

		$results = $wpdb->get_results( "DELETE FROM $wpdb->postmeta WHERE `meta_key` = 'sg_email_marketing_user_data';" );
		$results = $wpdb->get_results( "DELETE FROM $wpdb->usermeta WHERE `meta_key` = 'sg_email_marketing_user_data';" );
		$results = $wpdb->get_results( "DELETE FROM $wpdb->commentmeta WHERE `meta_key` = 'sg_email_marketing_user_data';" );
	}
}
