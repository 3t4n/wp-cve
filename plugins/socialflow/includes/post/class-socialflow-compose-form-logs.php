<?php
/**
 * Compose Form Log.
 *
 * @package class-post-compose
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit( 'No direct script access allowed' );
}

/**
 * Log statistics
 * Log data handler
 *
 * @since 2.7.4
 */
class SocialFlow_Compose_Form_Logs {
	/**
	 * Post ID
	 *
	 * @since 2.7.4
	 * @var int
	 */
	protected $post_id;

	/**
	 * Logs array
	 *
	 * @since 2.7.4
	 * @var array
	 */
	protected $logs = null;

	/**
	 * Default log fields
	 *
	 * @since 2.7.4
	 * @var array
	 */
	protected $default_log = array(
		'status'          => '',
		'is_published'    => 0,
		'content_item_id' => 0,
	);

	/**
	 * PHP5 constructor
	 *
	 * @access public
	 * @param int $post_id id current post.
	 */
	public function __construct( $post_id ) {
		$this->post_id = absint( $post_id );

		$this->init_logs();
	}

	/**
	 * Load post compose logs to object
	 *
	 * @since 2.7.4
	 */
	protected function init_logs() {
		if ( ! is_null( $this->logs ) ) {
			return;
		}
		$data_2 = get_post_meta( $this->post_id, 'sf_success', true );
		$data   = $data_2;

		if ( empty( $data ) ) {
			$this->logs = array();
			return;
		}

		$item = array_shift( $data_2 );
		$item = array_shift( $item );

		// If is new data format,
		// update after add duplicate advanced stttings.
		if ( ! isset( $item['status'] ) ) {
			$this->logs = $data;
			return;
		}

		$this->logs = $this->restructure_old_format( $data );
	}

	/**
	 * Gel post logs
	 *
	 * @since 2.7.4
	 */
	public function get() {
		return $this->logs;
	}

	/**
	 * Restructure success array to new format:
	 * [ time ][ account_id ][] = log
	 *
	 * @since 2.7.4
	 * @param  array $data .
	 * @return array
	 */
	protected function restructure_old_format( $data ) {
		$new = array();

		foreach ( (array) $data as $time => $accounts ) {
			$new[ $time ] = array();

			foreach ( $accounts as $account_id => $log ) {
				$new[ $time ][ $account_id ] = array( $log );
			}
		}

		return $new;
	}

	/**
	 * Add new log data
	 *
	 *  Function array_merge_recursive() is not used,
	 * because account_id is integer
	 * It is like natural array key number
	 *
	 * @since 2.7.4
	 * @param int   $account_id .
	 * @param array $log .
	 */
	public function add( $account_id, $log ) {
		$time = current_time( 'mysql' );

		$log = shortcode_atts( $this->default_log, $log );

		$this->logs[ $time ][ $account_id ][] = array_map( 'sanitize_text_field', $log );
	}

	/**
	 * Save data to post meta
	 *
	 * @since 2.7.4
	 */
	public function save() {
		if ( ! $this->defined() ) {
			return;
		}

		update_post_meta( $this->post_id, 'sf_success', $this->logs );
	}

	/**
	 * Check is not empty logs
	 *
	 * @since 2.7.4
	 */
	public function defined() {
		return ! empty( $this->logs );
	}

	/**
	 * Update log by its data
	 *
	 * @since 2.7.4
	 *
	 * @param  string $time            [description].
	 * @param  int    $account_id      [description].
	 * @param  int    $content_item_id [description].
	 * @param  array  $log             [description].
	 * @return bool                    [description]
	 */
	public function update_by_content_item_id( $time, $account_id, $content_item_id, $log ) {
		if ( ! $this->defined() ) {
			return false;
		}

		if ( ! isset( $this->logs[ $time ][ $account_id ] ) ) {
			return false;
		}

		$logs = $this->logs[ $time ][ $account_id ];

		$key = array_search( $content_item_id, wp_list_pluck( $logs, 'content_item_id' ), true );

		if ( false === $key ) {
			return false;
		}

		$old = $logs[ $key ];

		$this->logs[ $time ][ $account_id ][ $key ] = shortcode_atts( $old, $log );

		$this->save();

		return true;
	}
}
