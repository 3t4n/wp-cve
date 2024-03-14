<?php
/**
 * Framework gallery field file.
 *
 * @link https://shapedplugin.com
 * @since 2.0.0
 *
 * @package team-free
 * @subpackage team-free/framework
 */

if ( ! defined( 'ABSPATH' ) ) {
	die; } // Cannot access directly.

if ( ! class_exists( 'TEAMFW_Field_gallery' ) ) {
	/**
	 *
	 * Field: gallery
	 *
	 * @since 1.0.0
	 * @version 1.0.0
	 */
	class TEAMFW_Field_gallery extends TEAMFW_Fields {

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
					'add_title'   => esc_html__( 'Add Gallery', 'team-free' ),
					'edit_title'  => esc_html__( 'Edit Gallery', 'team-free' ),
					'clear_title' => esc_html__( 'Clear', 'team-free' ),
				)
			);

			$hidden = ( empty( $this->value ) ) ? ' hidden' : '';

			echo wp_kses_post( $this->field_before() );

			echo '<ul>';
			if ( ! empty( $this->value ) ) {

				$values = explode( ',', $this->value );

				foreach ( $values as $id ) {
					$attachment = wp_get_attachment_image_src( $id, 'thumbnail' );
					$attachment = is_array( $attachment ) ? $attachment : array( '' );
					echo '<li><img src="' . esc_url( $attachment[0] ) . '" /></li>';
				}
			}
			echo '</ul>';

			echo '<a href="#" class="button button-primary spf-button">' . wp_kses_post( $args['add_title'] ) . '</a>';
			echo '<a href="#" class="button spf-edit-gallery' . esc_attr( $hidden ) . '">' . wp_kses_post( $args['edit_title'] ) . '</a>';
			echo '<a href="#" class="button spf-warning-primary spf-clear-gallery' . esc_attr( $hidden ) . '">' . wp_kses_post( $args['clear_title'] ) . '</a>';
			echo '<input type="text" name="' . esc_attr( $this->field_name() ) . '" value="' . esc_attr( $this->value ) . '"' . $this->field_attributes() . '/>'; // phpcs:ignore

			echo wp_kses_post( $this->field_after() );

		}

	}
}
