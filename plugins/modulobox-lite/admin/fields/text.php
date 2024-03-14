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

if ( ! class_exists( 'ModuloBox_Text_Field' ) ) {

	class ModuloBox_Text_Field extends ModuloBox_Settings_field {

		/**
		 * Render HTML field
		 *
		 * @since 1.0.0
		 * @access static
		 *
		 * @param array $args Contains all field parameters
		 */
		static function render( $args ) {

			$width = $args['width'] ? ' style="width:' . esc_attr( $args['width'] ) . 'px"' : '';

			echo $args['desc'];
			echo $args['premium'];

			echo '<input type="text" class="mobx-text" id="' . esc_attr( $args['ID'] )  . '" name="' . esc_attr( $args['name'] )  . '" value="' . esc_attr( $args['value'] ) . '" autocomplete="off"' . $width . '>';

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
				'width'   => '',
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

			// Because of data serialization for the ajax request
			if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
				// Un-quotes quoted strings
				$val = wp_unslash( $val );
			}

			return sanitize_text_field( $val );

		}
	}

}
