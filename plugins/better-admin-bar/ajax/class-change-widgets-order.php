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
class Change_Widgets_Order {
	/**
	 * Available fields.
	 *
	 * @var array
	 */
	private $fields = array( 'active_widgets' );

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
			if ( 'active_widgets' === $field ) {
				$active_widgets = $_POST['active_widgets'];
				$active_widgets = ! is_array( $active_widgets ) ? array() : $active_widgets;

				$this->data['active_widgets'] = array();

				foreach ( $active_widgets as $widget_key ) {
					array_push( $this->data['active_widgets'], sanitize_text_field( $widget_key ) );
				}
			} else {
				$this->data[ $field ] = isset( $_POST[ $field ] ) ? sanitize_text_field( $_POST[ $field ] ) : '';
			}
		}
	}

	/**
	 * Validate the data.
	 */
	public function validate() {
		// Check if nonce is incorrect.
		if ( ! check_ajax_referer( 'change_widgets_order', 'nonce', false ) ) {
			wp_send_json_error( __( 'Invalid token', 'better-admin-bar' ) );
		}
	}

	/**
	 * Save the data.
	 */
	public function save() {
		update_option( 'swift_control_active_widgets', $this->data['active_widgets'] );
		wp_send_json_success( __( 'Widget order is changed' ), 'better-admin-bar' );
	}
}
