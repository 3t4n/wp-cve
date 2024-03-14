<?php
/**
 * Handle the settings saving.
 *
 * @link       http://bootstrapped.ventures
 * @since      1.0.0
 *
 * @package    BV_Settings
 * @author     Brecht Vandersmissen <brecht@bootstrapped.ventures>
 */

class BV_Saver {
	private $bvs;

	/**
	 * Store main instance and initialize.
	 *
	 * @since   1.0.0
	 */
	public function __construct( $bvs ) {
		$this->bvs = $bvs;
	}

	/**
	 * Update the settings.
	 *
	 * @since	1.0.0
	 * @param	array $settings_to_update Settings toB update.
	 */
	public function update_settings( $settings_to_update ) {
		$old_settings = $this->bvs->get_settings();

		if ( is_array( $settings_to_update ) ) {
			$settings_to_update = $this->sanitize_settings( $settings_to_update );
			$new_settings = array_merge( $old_settings, $settings_to_update );

			$new_settings = apply_filters( $this->bvs->atts['uid'] . '_settings_update', $new_settings, $old_settings );

			update_option( $this->bvs->atts['uid'] . '_settings', $new_settings );
			$this->bvs->helpers['structure']->set_settings( $new_settings );
		}

		return $this->bvs->get_settings();
	}

	/**
	 * Sanitize the settings.
	 *
	 * @since	1.0.0
	 * @param	array $settings Settings to sanitize.
	 */
	public function sanitize_settings( $settings ) {
		$sanitized_settings = array();
		$settings_details = $this->bvs->helpers['structure']->get_details();

		foreach ( $settings as $id => $value ) {
			if ( array_key_exists( $id, $settings_details ) ) {
				$details = $settings_details[ $id ];

				$sanitized_value = NULL;

				// Check for custom sanitization function.
				if ( isset( $details['sanitize'] ) && is_callable( $details['sanitize'] ) ) {
					$sanitized_value = call_user_func( $details['sanitize'], $value );
				}

				// Options callback.
				if ( isset( $details['optionsCallback'] ) ) {
					$details['options'] = call_user_func( $details['optionsCallback'], $details );
				}

				// Default sanitization based on type.
				if ( is_null( $sanitized_value ) && isset( $details['type'] ) ) {	
					switch ( $details['type'] ) {
						case 'code':
							$sanitized_value = wp_kses_post( $value );

							// Fix for CSS code.
							$sanitized_value = str_replace( '&gt;', '>', $sanitized_value );
							break;
						case 'color':
							$sanitized_value = sanitize_text_field( $value );
							break;
						case 'dropdown':
							if ( array_key_exists( $value, $details['options'] ) ) {
								$sanitized_value = $value;
							}
							break;
						case 'dropdownMultiselect':
							$sanitized_value = array();

							if ( is_array( $value ) ) {
								foreach ( $value as $option ) {
									if ( array_key_exists( $option, $details['options'] ) ) {
										$sanitized_value[] = $option;
									}
								}
							}
							break;
						case 'email':
							$sanitized_value = sanitize_email( $value );
							break;
						case 'number':
							$sanitized_value = sanitize_text_field( $value );
							break;
						case 'richTextarea':
							$sanitized_value = wp_kses_post( $value );
							break;
						case 'text':
							$sanitized_value = sanitize_text_field( $value );
							break;
						case 'textarea':
							$sanitized_value = wp_kses_post( $value );
							break;
						case 'toggle':
							$sanitized_value = $value ? true : false;
							break;
					}
				}

				$sanitized_value = apply_filters( $this->bvs->atts['uid'] . '_sanitized', $sanitized_value, $value, $id, $details );

				if ( ! is_null( $sanitized_value ) ) {
					$sanitized_settings[ $id ] = $sanitized_value;
				}
			}
		}

		return $sanitized_settings;
	}
}
