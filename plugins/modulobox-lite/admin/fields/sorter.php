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

if ( ! class_exists( 'ModuloBox_Sorter_Field' ) ) {

	class ModuloBox_Sorter_Field extends ModuloBox_Settings_field {

		/**
		 * Render HTML field
		 *
		 * @since 1.0.0
		 * @access static
		 *
		 * @param array $args Contains all field parameters
		 */
		static function render( $args ) {

			// Filter svg markup
			$allowed = array(
				'svg' => array(
					'class' => array(),
				),
				'use' => array(
					'class' => array(),
					'xlink:href' => array(),
				),
			);

			$args['value'] = (array) $args['value'];

			echo $args['desc'];
			echo $args['premium'];

			$display = $args['value'] ? ' mobx-hide-msg' : '';
			echo '<ul class="mobx-sort-list mobx-' . sanitize_html_class( $args['ID'] ) . $display . '" data-msg="' . esc_attr( $args['enable_msg'] ) . '" data-active="true">';

			foreach ( $args['value'] as $val ) {

				if ( isset( $args['options'][ $val ] ) ) {

					echo '<li data-value="' . esc_attr( $val ) . '" title="' . esc_attr( $args['options'][ $val ]['title'] ) . '"><input type="hidden" name="' . esc_attr( $args['name'] )  . '[]" value="' . esc_attr( $val ) . '">';
						echo wp_kses( $args['options'][ $val ]['html'], $allowed );
					echo '</li>';

				}
			}

			echo '</ul>';

			$display = count( array_diff( array_keys( (array) $args['options'] ), $args['value'] ) ) ? ' mobx-hide-msg' : '';
			echo '<ul class="mobx-sort-list mobx-' . sanitize_html_class( $args['ID'] ) . $display . '" data-msg="' . esc_attr( $args['disable_msg'] ) . '" data-active="false">';

			foreach ( $args['options'] as $val => $attr ) {

				if ( ! in_array( $val, $args['value'] ) ) {

					echo '<li data-value="' . esc_attr( $val ) . '" title="' . esc_attr( $attr['title'] ) . '"><input type="hidden" name="' . esc_attr( $args['name'] )  . '[]" value="' . esc_attr( $val ) . '" disabled>';
						echo wp_kses( $attr['html'], $allowed );
					echo '</li>';

				}
			}

			echo '</ul>';

		}

		/**
		 * Enqueue scripts and styles
		 *
		 * @since 1.0.0
		 * @access static
		 */
		static function scripts() {

			wp_enqueue_script( 'jquery-ui-sortable' );

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
				'default'     => '',
				'enable_msg'  => __( 'Drag &amp; drop here', 'modulobox' ),
				'disable_msg' => __( 'No element available', 'modulobox' ),
				'options'     => array(),
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
		static function sanitize( $vals, $args ) {

			$new_val = array();

			foreach ( (array) $vals as $val ) {

				if ( array_key_exists( $val, $args['options'] ) ) {

					array_push( $new_val, sanitize_text_field( $val ) );

				}
			}

			return $new_val;

		}
	}

}
