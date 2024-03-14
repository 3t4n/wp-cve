<?php
/**
 * Handle upstream frontend ajax calls
 *
 * @package UpStream
 */

// Prevent direct access.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use UpStream\Traits\Singleton;

/**
 * Class UpStream_Ajax
 *
 * @since   1.15.0
 */
class UpStream_Ajax {

	use Singleton;

	/**
	 * UpStream_Ajax constructor.
	 */
	public function __construct() {
		$this->set_hooks();
	}

	/**
	 * Set the hooks.
	 */
	public function set_hooks() {
		add_action( 'wp_ajax_upstream_ordering_update', array( $this, 'ordering_update' ) );
		add_action( 'wp_ajax_upstream_collapse_update', array( $this, 'collapse_update' ) );
		add_action( 'wp_ajax_upstream_panel_order_update', array( $this, 'panel_order_update' ) );
		add_action( 'wp_ajax_upstream_report_data', array( $this, 'report_data' ) );
	}

	/**
	 * Report Data
	 *
	 * @return void
	 */
	public function report_data() {
		$post_data = isset( $_POST ) ? wp_unslash( $_POST ) : array();

		if ( ! isset( $post_data['nonce'] ) ) {
			$this->output( 'security_error' );
			return;
		}

		if ( ! wp_verify_nonce( sanitize_text_field( $post_data['nonce'] ), 'upstream-nonce' ) ) {
			$this->output( 'security_error' );
			return;
		}

		$urg    = UpStream_Report_Generator::get_instance();
		$report = $urg->get_report( sanitize_text_field( $post_data['report'] ) );
		$data   = $urg->execute_report( $report );

		$this->output( $data );
	}

	/**
	 * Update ordering state.
	 */
	public function ordering_update() {
		$post_data = isset( $_POST ) ? wp_unslash( $_POST ) : array();

		if ( ! isset( $post_data['nonce'] ) ) {
			$this->output( 'security_error' );
			return;
		}

		if ( ! wp_verify_nonce( sanitize_text_field( $post_data['nonce'] ), 'upstream-nonce' ) ) {
			$this->output( 'security_error' );
			return;
		}

		if ( ! isset( $post_data['column'] ) ) {
			$this->output( 'column_not_found' );
			return;
		}

		if ( ! isset( $post_data['orderDir'] ) ) {
			$this->output( 'order_dir_not_found' );
			return;
		}

		if ( ! isset( $post_data['tableId'] ) ) {
			$this->output( 'table_id_not_found' );
			return;
		}

		// Sanitize data.
		$table_id  = sanitize_text_field( $post_data['tableId'] );
		$column    = sanitize_text_field( $post_data['column'] );
		$order_dir = sanitize_text_field( $post_data['orderDir'] );

		if ( empty( $column ) || empty( $order_dir ) || empty( $table_id ) ) {
			$this->output( 'error' );
			return;
		}

		\UpStream\Frontend\upstream_update_table_order( $table_id, $column, $order_dir );

		$this->output( 'success' );
	}

	/**
	 * Update the collapse state.
	 */
	public function collapse_update() {
		$post_data = isset( $_POST ) ? wp_unslash( $_POST ) : array();

		if ( ! isset( $post_data['nonce'] ) ) {
			$this->output( 'security_error' );
			return;
		}

		if ( ! wp_verify_nonce( sanitize_text_field( $post_data['nonce'] ), 'upstream-nonce' ) ) {
			$this->output( 'security_error' );
			return;
		}

		if ( ! isset( $post_data['section'] ) ) {
			$this->output( 'invalid_section' );
			return;
		}

		if ( ! isset( $post_data['state'] ) || ! in_array( sanitize_text_field( $post_data['state'] ), array( 'opened', 'closed' ), true ) ) {
			$this->output( 'invalid_state' );
			return;
		}

		// already checked for validity.
		$state = sanitize_text_field( $post_data['state'] );

		// Sanitize data.
		$section = sanitize_text_field( $post_data['section'] );

		if ( empty( $state ) || empty( $section ) ) {
			$this->output( 'error' );
			return;
		}

		\UpStream\Frontend\upstream_update_section_collapse_state( $section, $state );

		$this->output( 'success' );
	}

	/**
	 * Update the panel ordering.
	 */
	public function panel_order_update() {
		$post_data = isset( $_POST ) ? wp_unslash( $_POST ) : array();

		if ( ! isset( $post_data['nonce'] ) ) {
			$this->output( 'security_error' );
			return;
		}

		if ( ! wp_verify_nonce( sanitize_text_field( $post_data['nonce'] ), 'upstream-nonce' ) ) {
			$this->output( 'security_error' );
			return;
		}

		if ( ! isset( $post_data['rows'] ) ) {
			$this->output( 'invalid_rows' );
			return;
		}

		$rows = array();
		if ( is_array( $post_data['rows'] ) ) {
			$rows = array_map( 'sanitize_text_field', $post_data['rows'] );
		}

		if ( empty( $rows ) ) {
			$this->output( 'error' );
			return;
		}

		\UpStream\Frontend\upstream_update_panel_order( $rows );

		$this->output( 'success' );
	}

	/**
	 * Function output.
	 *
	 * @param mixed $return Return data.
	 */
	protected function output( $return ) {
		echo wp_json_encode( $return );
		wp_die();
	}
}

