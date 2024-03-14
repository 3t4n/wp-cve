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

if ( ! class_exists( 'ModuloBox_Select_Field' ) ) {

	class ModuloBox_Select_Field extends ModuloBox_Settings_field {

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

			echo '<select class="mobx-select" id="' . esc_attr( $args['ID'] )  . '" name="' . esc_attr( $args['name'] )  . '">';

			foreach ( $args['options'] as $val => $title ) {
				echo '<option value="' . esc_attr( $val ) . '" ' . selected( esc_attr( $args['value'] ), esc_attr( $val ), 0 ) . '>' . esc_html( $title ) . '</option>';
			}

			echo '</select>';

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

			if ( $args['options'] ) {
				return array_key_exists( $val, $args['options'] ) ? $val : $args['default'];
			} else {
				// Validate if dynamic options (set with ajax for example)
				return $val;
			}

		}
	}

}
