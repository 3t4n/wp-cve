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

if ( ! class_exists( 'ModuloBox_Number_Field' ) ) {

	class ModuloBox_Number_Field extends ModuloBox_Settings_field {

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

			echo '<input type="number" class="mobx-number" id="' . esc_attr( $args['ID'] )  . '" name="' . esc_attr( $args['name'] )  . '" value="' . esc_attr( $args['value'] ) . '" min="' . esc_attr( $args['min'] ) . '" max="' . esc_attr( $args['max'] ) . '" step="' . esc_attr( $args['step'] ) . '" autocomplete="off">';

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
				'min'     => '',
				'max'     => '',
				'step'    => 1,
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

			// Get default arguments to validate number
			$val   = floatval( str_replace( ',', '.', $val ) );
			$min   = is_numeric( $args['min'] )  ? $args['min']  : $val;
			$max   = is_numeric( $args['max'] )  ? $args['max']  : $val;
			$step  = is_numeric( $args['step'] ) ? $args['step'] : 1;
			$digit = strlen( substr( strrchr( $step, '.' ), 1 ) );

			// Make sure number is comprised between min and max values with right number of decimal
			$val = ( $val - $min ) / $step * $step + $min;
			$val = max( min( $val, $max ), $min );
			$val = $digit ? number_format( $val, $digit ) : round( $val );

			return $val;

		}
	}

}
