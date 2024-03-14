<?php
/**
 * Change widget item's setting.
 *
 * @package Better_Admin_Bar
 */

namespace SwiftControl\Ajax;

/**
 * Class to manage ajax request of changing widget item's setting.
 */
class Change_Widget_Settings {
	/**
	 * Available fields.
	 *
	 * @var array
	 */
	private $fields = array( 'widget_key', 'icon_class', 'text', 'url', 'new_tab', 'redirect_url' );

	/**
	 * Allowed empty fields.
	 *
	 * @var array
	 */
	private $empty_allowed = array( 'url', 'new_tab', 'redirect_url' );

	/**
	 * Setting keys.
	 *
	 * @var array
	 */
	private $setting_keys = array( 'icon_class', 'text', 'url', 'new_tab', 'redirect_url' );

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
			if ( isset( $_POST[ $field ] ) ) {
				$this->data[ $field ] = sanitize_text_field( $_POST[ $field ] );
				$this->data[ $field ] = 'new_tab' === $field ? absint( $this->data[ $field ] ) : $this->data[ $field ];
			}
		}
	}

	/**
	 * Validate the data.
	 */
	public function validate() {
		// Check if nonce is incorrect.
		if ( ! check_ajax_referer( 'change_widget_settings', 'nonce', false ) ) {
			wp_send_json_error( __( 'Invalid token', 'better-admin-bar' ) );
		}

		// Check if there is empty field.
		foreach ( $this->fields as $field ) {
			if ( ! in_array( $field, $this->empty_allowed ) ) {
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
		$widget_key      = $this->data['widget_key'];
		$widget_settings = swift_control_get_saved_widget_settings();

		if ( ! isset( $widget_settings[ $widget_key ] ) ) {
			$widget_settings[ $widget_key ] = array();
		}

		foreach ( $this->setting_keys as $setting_key ) {
			if ( isset( $this->data[ $setting_key ] ) ) {
				$widget_settings[ $widget_key ][ $setting_key ] = $this->data[ $setting_key ];
			}
		}

		update_option( 'swift_control_widget_settings', $widget_settings );

		wp_send_json_success(
			array(
				'widget_key' => $widget_key,
				'message'    => __( 'Widget setting is saved', 'better-admin-bar' ),
			)
		);
	}
}
