<?php

if ( ! function_exists( 'plugLab_customizer_sanitize_callback' ) ) {

	function plugLab_customizer_sanitize_callback( $wp_customize ) {

		if ( ! function_exists( 'pluglab_customizer_switch_sanitization' ) ) {

			function pluglab_customizer_switch_sanitization( $input ) {
				if ( true === $input ) {
					return 1;
				} else {
					return 0;
				}
			}
		}

		if ( ! function_exists( 'customizer_text_sanitization' ) ) {

			function customizer_text_sanitization() {
				if ( strpos( $input, ',' ) !== false ) {
					$input = explode( ',', $input );
				}
				if ( is_array( $input ) ) {
					foreach ( $input as $key => $value ) {
						$input[ $key ] = sanitize_text_field( $value );
					}
					$input = implode( ',', $input );
				} else {
					$input = sanitize_text_field( $input );
				}
				return $input;
			}
		}

		if ( ! function_exists( 'customizer_repeater_sanitize' ) ) {

			function customizer_repeater_sanitize( $input ) {
				$input_decoded = json_decode( $input, true );
				if ( ! empty( $input_decoded ) ) {
					foreach ( $input_decoded as $boxk => $box ) {
						foreach ( $box as $key => $value ) {

							$input_decoded[ $boxk ][ $key ] = wp_kses_post( force_balance_tags( $value ) );
						}
					}
					return json_encode( $input_decoded );
				}
				return $input;
			}
		}

		if ( ! function_exists( 'customizer_repeater_sanitize' ) ) {

			function pluglab_sanitize_range_value( $number, $setting ) {

				// Ensure input is an absolute integer.
				$number = absint( $number );

				// Get the input attributes associated with the setting.
				$atts = $setting->manager->get_control( $setting->id )->input_attrs;

				// Get minimum number in the range.
				$min = ( isset( $atts['min'] ) ? $atts['min'] : $number );

				// Get maximum number in the range.
				$max = ( isset( $atts['max'] ) ? $atts['max'] : $number );

				// Get step.
				$step = ( isset( $atts['step'] ) ? $atts['step'] : 1 );

				// If the number is within the valid range, return it; otherwise, return the default
				return ( $min <= $number && $number <= $max && is_int( $number / $step ) ? $number : $setting->default );
			}
		}
	}
}
add_action( 'customize_register', 'plugLab_customizer_sanitize_callback', 12 );
