<?php

// No direct access allowed.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class WPIMStatus extends WPIMDB {

	private static $instance;

	private static $statuses;

	/**
	 * Constructor magic method.
	 */
	public function __construct() {
		parent::__construct();
		$this->load();
	}

	public static function getInstance() {
		if ( ! self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	/**
	 * This is here purely to prevent someone from cloning the class
	 */
	private function __clone() {
	}

	/**
	 * Get a listing of all custom statuses.
	 */
	public function get_all() {

		if ( ! self::$statuses ) {
			$this->load();
		}

		return self::$statuses;
	}

	public function get_active() {
		$statuses = self::get_all();

		foreach ( $statuses AS $index => $status ) {
			if ( ! $status['is_active'] ) {
				unset( $statuses[ $index ] );
			}
		}

		return $statuses;
	}

	public function get_inactive() {
		$statuses = self::get_all();

		foreach ( $statuses AS $index => $status ) {
			if ( $status['is_active'] ) {
				unset( $statuses[ $index ] );
			}
		}

		return $statuses;
	}

	/**
	 * Get specific status
	 *
	 * @param string $field_name
	 *
	 * @return object
	 */
	public function get( $status_id ) {
		if ( ! empty( self::$statuses[ $status_id ] ) ) {
			return self::$statuses[ $status_id ];
		}

		echo '<!-- ERROR: Status ' . $status_id . ' NOT DEFINED! -->';
	}

	private function load() {
		$status_data = $this->wpdb->get_results( 'SELECT * FROM ' . $this->status_table . ' AS s ORDER BY `status_sort_order`' );

		$statuses = [];

		// Organize by ID
		foreach ( $status_data AS $status ) {
			$statuses[ $status->status_id ] = (array) $status;
		}

		self::$statuses = $statuses;
	}

	public function save( $data ) {

		// We have two - "Active" and "Inactive" that must always exist
		$never_delete = [ 1, 2 ];

		$data = apply_filters( 'wpim_save_statuses', $data );

		do_action( 'wpim_pre_save_statuses', $data );

		if ( ! self::$statuses ) {
			self::load();
		}

		$saved = [];
		foreach ( $data AS $index => $status ) {
			$status_id = (int) $status['status_id'];
			unset( $status['status_id'] );
			// Only save if there is a status name
			if ( $status['status_name'] ) {
			    if ( $status_id ) {
				    $this->wpdb->update( $this->status_table, $status, [ 'status_id' => $status_id ] );
					$saved[] = $status_id;
				} else {
					$this->wpdb->insert( $this->status_table, $status );
					$saved[] = $this->wpdb->insert_id;
				}
			}
		}

		if ( $saved ) {
			$never_delete = array_merge( $never_delete, $saved );
			// These are the id's that should NOT be deleted
			$never_delete = implode( ',', $never_delete );
			$this->wpdb->query( "DELETE FROM " . $this->status_table . " WHERE status_id NOT IN(" . $never_delete . ")" );
		}

		self::load();

		do_action( 'wpim_post_save_statuses', self::$statuses );

		return ( ! $this->wpdb->last_error ) ? TRUE : FALSE;
	}

	public function dropdown( $name, $selected, $class = '' ) {
		$select = '<select name="' . esc_attr($name) . '"';
		$select .= ( $class ) ? ' class="' . esc_attr($class) . '"' : '';
		$select .= '>' . PHP_EOL;
		foreach ( self::$statuses AS $status ) {
			$select .= '<option value="' . $status['status_id'] . '"';
			$select .= ( $status['status_id'] == $selected ) ? ' selected' : '';
			$select .= '>' . esc_attr($status['status_name']) . '</option>' . PHP_EOL;
		}
		$select .= '</select>' . PHP_EOL;
		return $select;
	}
}
