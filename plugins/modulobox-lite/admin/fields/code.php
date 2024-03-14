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

if ( ! class_exists( 'ModuloBox_Code_Field' ) ) {

	class ModuloBox_Code_Field extends ModuloBox_Settings_field {

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

			if ( 'javascript' === $args['mode'] ) {
				$content = html_entity_decode( wp_kses_decode_entities( $args['value']['original'] ) );
			} else {
				$content = wp_strip_all_tags( $args['value']['original'] );
			}

			$error = $args['value']['error'];
			echo '<span class="mobx-code-error" data-field="' . esc_attr( $args['ID'] )  . '">' . ( ! empty( $error ) ? htmlspecialchars_decode( esc_html( $error ) ) : null ) . '</span>';

			echo '<textarea rows="20" cols="50" class="mobx-code" id="' . esc_attr( $args['ID'] )  . '" name="' . esc_attr( $args['name'] )  . '" data-mode="' . esc_attr( $args['mode'] )  . '">' . esc_textarea( $content ) . '</textarea>';

		}

		/**
		 * Enqueue scripts and styles
		 *
		 * @since 1.0.0
		 * @access static
		 */
		static function scripts() {

			wp_enqueue_script( MOBX_SLUG . '-code-script', MOBX_ADMIN_URL . 'assets/js/codemirror.js', array( 'jquery' ), MOBX_VERSION, true );
			wp_enqueue_style( MOBX_SLUG . '-code-style', MOBX_ADMIN_URL . 'assets/css/codemirror.css', array(), MOBX_VERSION );

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

			$default = array(
				'mode' => 'css',
			);

			$std_props = array(
				'original' => '',
				'minified' => '',
				'error'    => '',
			);

			$field['default'] = isset( $field['default'] ) ? wp_parse_args( $field['default'], $std_props ) : $std_props;
			$field['value']   = isset( $field['value'] ) ? wp_parse_args( $field['value'], $std_props ) : $std_props;

			return wp_parse_args( $field, $default );

		}

		/**
		 * Sanitize field value
		 * JS: Source => Custom JavaScript Editor by Automattic
		 * CSS: Source => Jetpack by Automattic
		 *
		 * @since 1.0.0
		 * @access static
		 *
		 * @param mixed $val
		 * @param array $args
		 * @return string
		 */
		static function sanitize( $val, $args ) {

			return $args['default'];

		}
	}

}
