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

if ( ! class_exists( 'ModuloBox_Slider_Field' ) ) {

	class ModuloBox_Slider_Field extends ModuloBox_Settings_field {

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

			$digitNb = strlen( substr( strrchr( $args['step'], '.' ) , 1 ) );

			echo '<div class="mobx-ui-slider" data-value="' . esc_attr( $args['value'] ) . '" data-min="' . esc_attr( $args['min'] ) . '" data-max="' . esc_attr( $args['max'] ) . '" data-step="' . esc_attr( $args['step'] ) . '" data-unit="' . esc_attr( $args['unit'] ) . '"></div>';
			echo '<input type="text" class="mobx-ui-slider-value" id="' . esc_attr( $args['ID'] )  . '" name="' . esc_attr( $args['name'] ) . '" value="' . esc_attr( number_format( $args['value'], $digitNb, '.', '' ) ) . esc_attr( $args['unit'] ) . '" autocomplete="off">';

		}

		/**
		 * Enqueue scripts and styles
		 *
		 * @since 1.0.0
		 * @access static
		 */
		static function scripts() {

			wp_enqueue_script( 'jquery-ui-slider' );

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
				'unit'    => '',
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

			return ModuloBox_Number_Field::sanitize( $val, $args );

		}
	}

}
