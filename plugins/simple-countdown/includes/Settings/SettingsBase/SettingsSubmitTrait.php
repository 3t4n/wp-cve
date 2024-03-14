<?php
namespace GPLSCore\GPLS_PLUGIN_WPSCTR\Settings\SettingsBase;

use GPLSCore\GPLS_PLUGIN_WPSCTR\Settings\SettingsFields\Field;

defined( 'ABSPATH' ) || exit;

/**
 * Settings Submit Handle.
 */
trait SettingsSubmitTrait {

	/**
	 * Save Settings for CPT.
	 *
	 * @param int $post_id
	 * @return void
	 */
	public function submit_save_cpt_settings( $post_id ) {
		$this->save_settings( $post_id );
	}

	/**
	 * Save Settings using Submit.
	 *
	 * @param int $post_id
	 *
	 * @return void
	 */
	public function submit_save_settings() {
		// 2) Check tab nonce.
		if ( ! empty( $_POST[ $this->id . '-settings-nonce' ] ) && wp_verify_nonce( wp_unslash( $_POST[ $this->id . '-settings-nonce' ] ), $this->id . '-settings-nonce' ) ) {

			// Check user cap.
			if ( ! current_user_can( $this->cap ) ) {
				$this->add_error( esc_html__( 'You need a higher level of permission.' ) );
				return;
			}

			$this->save_settings();
		}

		$this->add_error( esc_html__( 'The link is expired, please refresh the page' ) );
	}

	/**
	 * AJAX Save Settings.
	 *
	 * @return void
	 */
	public function ajax_save_settings() {
		if ( wp_doing_ajax() && is_admin() && ! empty( $_POST['context'] ) ) {
			// Nonce Check.
			check_ajax_referer( $this->page_nonce, 'nonce' );

			// Cap Check.
			if ( ! current_user_can( $this->cap ) ) {
				wp_die(
					'<h1>' . esc_html__( 'You need a higher level of permission.' ) . '</h1>',
					403
				);
			}

			$this->save_settings();
		}

		wp_die( -1, 403 );
	}

	/**
	 * Save Settings.
	 *
	 * @return void
	 */
	private function save_settings( $post_id = null ) {
		$tab = $this->get_current_tab();
		if ( ! $tab ) {
			return;
		}

		$settings     = $this->get_settings();
		$old_settings = $settings;
		$fields       = $this->get_fields_for_save( $tab );

		if ( ! empty( $_post[ $this->id ] ) ) {
			return;
		}

		// Before tab Save.
		do_action( $this->id . '-before-settings-save', $settings, $fields );

		foreach ( $fields as $field_key => $field_arr ) {
			$value                  = self::sanitize_submitted_field( $this->id, $field_key, $field_arr, $settings[ $field_key ] );
			$settings[ $field_key ] = is_null( $value ) ? $settings[ $field_key ] : $value;
		}

		$settings = apply_filters( $this->id . '-filter-settings-before-saving', $settings, $old_settings, $this, $tab );

		$saving = apply_filters( $this->id . '-just-before-saving', true, $settings, $this, $tab );

		if ( $saving ) {
			if ( is_null( $post_id ) ) {
				update_option( $this->settings_key, $settings, $this->autoload );
			} else {
				update_post_meta( $post_id, $this->settings_key, $settings );
			}
		}

		// after tab save.
		do_action( $this->id . '-after-settings-save', $settings, $this, $tab, $saving );

		$this->refresh_settings();

		if ( $saving ) {
			$this->add_message( esc_html__( 'Settings have been saved.' ) );
		}
	}

	/**
	 * Get Fields for Save.
	 *
	 * @param string $tab
	 * @return array
	 */
	public function get_fields_for_save( $tab ) {
		$fields                   = $this->get_fields();
		$prepared_settings_fields = array();

		if ( empty( $fields[ $tab ] ) ) {
			return array();
		}

		foreach ( $fields[ $tab ] as $section_name => $section_settings ) {
			if ( ! empty( $section_settings['settings_list'] ) ) {
				foreach ( $section_settings['settings_list'] as $setting_name => $setting_arr ) {
					$prepared_settings_fields[ $setting_name ]           = $setting_arr;
					$prepared_settings_fields[ $setting_name ]['name']   = $setting_arr['name'] ?? $setting_name;
					$prepared_settings_fields[ $setting_name ]['key']    = $setting_name;
					$prepared_settings_fields[ $setting_name ]['filter'] = $setting_name;
				}
			}
		}

		return $prepared_settings_fields;
	}

	/**
	 * Sanitize Submitted Settings Field Value.
	 *
	 * @param string $key
	 * @param mixed  $value
	 * @return mixed
	 */
	public static function sanitize_submitted_field( $id, $posted_key, $field, $old_value ) {
		$field_obj = Field::new_field( $id, $field, false );

		// Resolve the posted value key.
		if ( is_string( $posted_key ) ) {
			$value = wp_unslash( $_POST[ $id ][ $posted_key ] ?? null );
		} else {
			$value = wp_unslash( $_POST[ $id ] );
			foreach ( $posted_key as $key ) {
				$value = $value[ $key ] ?? null;
				if ( is_null( $value ) ) {
					break;
				}
			}
		}

		// fallback to old value if null, except checkboxes.
		if ( is_null( $value ) && ( 'checkbox' !== $field_obj->get_type() ) ) {
			$value = $old_value;
		}

		// Accept array only if field is multiple.
		if ( is_array( $value ) && empty( $field['multiple'] ) ) {
			$value = array_values( $value )[0];
		}

		// Sanitize the value.
		$value = $field_obj->sanitize_field( $value );

		// Fix numeric values.
		return is_numeric( $value ) ? $value + 0 : $value;
	}
}
