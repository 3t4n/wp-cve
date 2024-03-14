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
class Save_General_Settings {
	/**
	 * Available fields.
	 *
	 * @var array
	 */
	private $fields = array( 'widget_bg_color', 'widget_bg_color_hover', 'widget_icon_color', 'setting_button_bg_color', 'setting_button_icon_color', 'disable_swift_control', 'remove_indicator', 'expanded', 'delete_on_uninstall', 'remove_font_awesome', 'remove_by_roles', 'remove_top_gap', 'fix_menu_item_overflow', 'hide_below_screen_width', 'inactive_opacity', 'active_opacity', 'auto_hide', 'transition_duration', 'hiding_transition_delay' );

	/**
	 * Allowed empty fields.
	 *
	 * @var array
	 */
	private $empty_allowed = array( 'disable_swift_control', 'remove_indicator', 'expanded', 'delete_on_uninstall', 'remove_font_awesome', 'remove_by_roles', 'remove_top_gap', 'fix_menu_item_overflow', 'hide_below_screen_width', 'inactive_opacity', 'active_opacity', 'auto_hide', 'transition_duration', 'hiding_transition_delay' );

	/**
	 * Color setting fields.
	 *
	 * @var array
	 */
	private $color_setting_fields = array( 'widget_bg_color', 'widget_bg_color_hover', 'widget_icon_color', 'setting_button_bg_color', 'setting_button_icon_color' );

	/**
	 * Display setting fields.
	 *
	 * @var array
	 */
	private $display_setting_fields = array( 'disable_swift_control', 'remove_indicator', 'expanded' );

	/**
	 * Misc setting fields.
	 *
	 * @var array
	 */
	private $misc_setting_fields = array( 'delete_on_uninstall', 'remove_font_awesome' );

	/**
	 * Admin bar setting fields.
	 *
	 * @var array
	 */
	private $admin_bar_setting_fields = array( 'remove_by_roles', 'remove_top_gap', 'fix_menu_item_overflow', 'hide_below_screen_width', 'inactive_opacity', 'active_opacity', 'auto_hide', 'transition_duration', 'hiding_transition_delay' );

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
				if ( 'remove_by_roles' === $field ) {
					$roles = array();

					foreach ( $_POST[ $field ] as $role ) {
						array_push( $roles, sanitize_text_field( $role ) );
					}

					$this->data[ $field ] = $roles;
				} else {
					$this->data[ $field ] = sanitize_text_field( $_POST[ $field ] );
					$this->data[ $field ] = 'new_tab' === $field ? absint( $this->data[ $field ] ) : $this->data[ $field ];
				}
			}
		}
	}

	/**
	 * Validate the data.
	 */
	public function validate() {
		// Check if nonce is incorrect.
		if ( ! check_ajax_referer( 'save_general_settings', 'nonce', false ) ) {
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
		$color_settings     = array();
		$display_settings   = array();
		$misc_settings      = array();
		$admin_bar_settings = array();

		foreach ( $this->color_setting_fields as $field ) {
			if ( isset( $this->data[ $field ] ) ) {
				$color_settings[ $field ] = $this->data[ $field ];
			}
		}

		foreach ( $this->display_setting_fields as $field ) {
			if ( isset( $this->data[ $field ] ) ) {
				$display_settings[ $field ] = $this->data[ $field ];
			}
		}

		foreach ( $this->misc_setting_fields as $field ) {
			if ( isset( $this->data[ $field ] ) ) {
				$misc_settings[ $field ] = $this->data[ $field ];
			}
		}

		foreach ( $this->admin_bar_setting_fields as $field ) {
			if ( isset( $this->data[ $field ] ) ) {
				$admin_bar_settings[ $field ] = $this->data[ $field ];
			}
		}

		update_option( 'swift_control_color_settings', $color_settings );
		update_option( 'swift_control_display_settings', $display_settings );
		update_option( 'swift_control_misc_settings', $misc_settings );
		update_option( 'swift_control_admin_bar_settings', $admin_bar_settings );

		wp_send_json_success( __( 'The settings are saved' ), 'better-admin-bar' );
	}
}
