<?php
/**
 * Change widget order.
 *
 * @package Better_Admin_Bar
 */

namespace SwiftControl\Ajax;

/**
 * Class to manage ajax request of changing widget order.
 */
class Save_Position {
	/**
	 * Available fields.
	 *
	 * @var array
	 */
	private $fields = array( 'x', 'x_direction', 'y', 'y_direction', 'y_percentage' );

	/**
	 * Allowed empty fields.
	 *
	 * @var array
	 */
	private $empty_allowed = array( 'x', 'y', 'y_percentage' );

	/**
	 * Sanitized data.
	 *
	 * @var array
	 */
	private $data = array();

	/**
	 * Setup the flow.
	 */
	public function ajax() {
		$this->sanitize();
		$this->validate();
		$this->save();
	}

	/**
	 * Sanitize the data.
	 */
	public function sanitize() {
		foreach ( $this->fields as $field ) {
			$this->data[ $field ] = isset( $_POST[ $field ] ) ? sanitize_text_field( $_POST[ $field ] ) : 0;
			$this->data[ $field ] = in_array( $field, $this->empty_allowed, true ) ? (float) $this->data[ $field ] : $this->data[ $field ];
		}
	}

	/**
	 * Validate the data.
	 */
	public function validate() {
		// Check if nonce is incorrect.
		if ( ! check_ajax_referer( 'save_position', 'nonce', false ) ) {
			wp_send_json_error( __( 'Invalid token', 'better-admin-bar' ) );
		}

		// Check if there is empty field.
		foreach ( $this->fields as $field ) {
			if ( ! in_array( $field, $this->empty_allowed, true ) ) {
				if ( ! isset( $this->data[ $field ] ) || empty( $this->data[ $field ] ) ) {
					$field_name = str_ireplace( '_', ' ', $field );
					$field_name = ucfirst( $field_name );

					wp_send_json_error( $field_name . ' ' . __( 'is empty', 'better-admin-bar' ) );
				}
			}
		}
	}

	/**
	 * Save the data.
	 */
	public function save() {
		update_user_meta( get_current_user_id(), 'swift_control_position', $this->data );
		wp_send_json_success( __( 'Widget position is saved' ), 'better-admin-bar' );
	}
}
