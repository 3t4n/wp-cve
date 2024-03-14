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

if ( ! class_exists( 'ModuloBox_Checkbox_Field' ) ) {

	class ModuloBox_Checkbox_Field extends ModuloBox_Settings_field {

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

			echo '<div class="mobx-toggle">';

				echo '<input type="checkbox" class="mobx-checkbox" id="' . esc_attr( $args['ID'] )  . '" name="' . esc_attr( $args['name'] )  . '" value="1" ' . checked( esc_attr( $args['value'] ), 1, 0 ) . '>';
				echo '<label></label>';

			echo '</div>';

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
				'label'   => '',
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
		 * @return int
		 */
		static function sanitize( $val, $args ) {

			return ! empty( $val ) ? 1 : 0;

		}
	}

}
