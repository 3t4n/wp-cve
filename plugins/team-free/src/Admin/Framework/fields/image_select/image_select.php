<?php
/**
 * Framework image_select field file.
 *
 * @link https://shapedplugin.com
 * @since 2.0.0
 *
 * @package team-free
 * @subpackage team-free/framework
 */

if ( ! defined( 'ABSPATH' ) ) {
	die; } // Cannot access directly.

if ( ! class_exists( 'TEAMFW_Field_image_select' ) ) {
	/**
	 *
	 * Field: image_select
	 *
	 * @since 1.0.0
	 * @version 1.0.0
	 */
	class TEAMFW_Field_image_select extends TEAMFW_Fields {

		/**
		 * Field constructor.
		 *
		 * @param array  $field The field type.
		 * @param string $value The values of the field.
		 * @param string $unique The unique ID for the field.
		 * @param string $where To where show the output CSS.
		 * @param string $parent The parent args.
		 */
		public function __construct( $field, $value = '', $unique = '', $where = '', $parent = '' ) {
			parent::__construct( $field, $value, $unique, $where, $parent );
		}

		/**
		 * Render field
		 *
		 * @return void
		 */
		public function render() {

			$args = wp_parse_args(
				$this->field,
				array(
					'multiple' => false,
					'inline'   => false,
					'options'  => array(),
				)
			);

			$inline = ( $args['inline'] ) ? ' spf--inline-list' : '';

			$value = ( is_array( $this->value ) ) ? $this->value : array_filter( (array) $this->value );

			echo wp_kses_post( $this->field_before() );

			if ( ! empty( $args['options'] ) ) {

				echo '<div class="spf-siblings spf--image-group' . esc_attr( $inline ) . '" data-multiple="' . esc_attr( $args['multiple'] ) . '">';

				$num = 1;

				foreach ( $args['options'] as $key => $option ) {

					$type     = ( $args['multiple'] ) ? 'checkbox' : 'radio';
					$extra    = ( $args['multiple'] ) ? '[]' : '';
					$active   = ( in_array( $key, $value, true ) ) ? ' spf--active' : '';
					$checked  = ( in_array( $key, $value, true ) ) ? ' checked' : '';
					$pro_only = ( isset( $option['pro_only'] ) && $option['pro_only'] ) ? ' spf-pro-only' : '';

					echo '<div class="spf--sibling spf--image' . esc_attr( $active . $pro_only ) . '" value="' . ( isset( $option['option_name'] ) ? esc_html( $option['option_name'] ) : '' ) . '">';
					echo '<figure>';
					echo '<img src="' . esc_url( $option['image'] ) . '" alt="img-' . esc_attr( $num++ ) . '" />';
					  echo '<input type="' . esc_attr( $type ) . '" name="' . esc_attr( $this->field_name( $extra ) ) . '" value="' . esc_attr( $key ) . '"' . $this->field_attributes() . esc_attr( $checked ) . '/>'; // phpcs:ignore
					// ShapedPlugin.
					if ( isset( $option['option_name'] ) && ! isset( $option['option_demo_url'] ) ) {
						echo '<p>' . esc_html( $option['option_name'] ) . '</p>';
					}
					if ( isset( $option['option_demo_url'] ) ) {
						echo '<p class="sptp-img-title">' . esc_html( $option['option_name'] ) . '<a href="' . esc_url( $option['option_demo_url'] ) . '" tooltip="Demo" class="sptp-live-demo-icon" target="_blank"><i class="spteam-icon-external_link"></i></a></p>';
					}
					echo '</figure>';
					echo '</div>';

				}

				echo '</div>';

			}

			echo wp_kses_post( $this->field_after() );

		}
	}
}
