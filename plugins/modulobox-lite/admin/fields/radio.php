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

if ( ! class_exists( 'ModuloBox_Radio_Field' ) ) {

	class ModuloBox_Radio_Field extends ModuloBox_Settings_field {

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

			foreach ( $args['options'] as $val => $title ) {

				echo '<input type="radio" class="mobx-radio" id="' . esc_attr( $val ) . '" name="' . esc_attr( $args['name'] )  . '" value="' . esc_attr( $val )  . '" ' . checked( esc_attr( $args['value'] ), esc_attr( $val ), 0 ) . '>';
				echo '<label for="' . esc_attr( $val )  . '">' . esc_html( $title ) . '</label>';

			}

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
				'default' => array(),
				'options' => array(),
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

			$val = sanitize_text_field( $val );

			return array_key_exists( $val, $args['options'] ) ? $val : $args['default'];

		}
	}

}
