<?php
/**
 * @package   ModuloBox
 * @author    Themeone <themeone.master@gmail.com>
 * @copyright 2017 Themeone
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'ModuloBox_Color_Field' ) ) {

	class ModuloBox_Color_Field extends ModuloBox_Settings_field {

		/**
		 * Render HTML field
		 *
		 * @since 1.0.0
		 * @access static
		 *
		 * @param array $args Contains all field parameters
		 */
		static function render( $args ) {

			echo $args['desc'];
			echo $args['premium'];

			echo '<input type="text" class="mobx-color-picker" id="' . esc_attr( $args['ID'] )  . '" name="' . esc_attr( $args['name'] )  . '" value="' . esc_attr( $args['value'] ) . '" data-alpha="' . esc_attr( $args['alpha'] ) . '">';

		}

		/**
		 * Enqueue scripts and styles
		 *
		 * @since 1.0.0
		 * @access static
		 */
		static function scripts() {

			wp_enqueue_script( 'wp-color-picker' );
			wp_enqueue_style( 'wp-color-picker' );
			wp_localize_script( 'wp-color-picker', 'wpColorPickerL10n', array(
		        'clear'			=> __( 'Clear', 'modulobox' ),
		        'defaultString'	=> __( 'Default', 'modulobox' ),
		        'pick'			=> __( 'Select Color', 'modulobox' ),
		        'current'		=> __( 'Current Color', 'modulobox' )
		    ) ); 

		}

		/**
		 * Normalize field parameters
		 *
		 * @since 1.0.0
		 * @access static
		 *
		 * @param array $field
		 * @return array
		 */
		static function normalize( $field ) {

			return wp_parse_args( $field, array(
				'default' => '',
				'alpha'   => false,
			));

		}

		/**
		 * Sanitize field value
		 *
		 * @since 1.0.0
		 * @access static
		 *
		 * @param mixed $val
		 * @param array $args
		 * @return string
		 */
		static function sanitize( $val, $args ) {

			// Authorize empty value
			if ( empty( $val ) ) {
				return '';
			}

			// Sanitize the hex color and return default if empty
			if ( strpos( $val, 'rgba' ) === false || ! $args['alpha'] ) {

				$val = sanitize_hex_color( $val );
				return empty( $val ) ? $args['default'] : $val;

			}

			// Match additive colors and alpha canal
			$val = str_replace( ' ', '', $val );
			sscanf( $val, 'rgba(%d,%d,%d,%f)', $r, $g, $b, $a );

			// If all values are numerics
			if ( is_numeric( $r ) && is_numeric( $g ) && is_numeric( $b ) && is_numeric( $a ) ) {
				return 'rgba(' . abs( $r ) . ',' . abs( $g ) . ',' . abs( $b ) . ',' . max( min( abs( $a ), 1 ), 0 ) . ')';
			} else {
				return $args['default'];
			}

		}
	}

}
