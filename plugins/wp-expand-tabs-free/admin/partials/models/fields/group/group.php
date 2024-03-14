<?php
/**
 * Framework group field file.
 *
 * @link http://shapedplugin.com
 * @since 2.0.0
 *
 * @package wp-expand-tabs-free
 * @subpackage wp-expand-tabs-free/Framework
 */

if ( ! defined( 'ABSPATH' ) ) {
	die; } // Cannot access directly.

if ( ! class_exists( 'SP_WP_TABS_Field_group' ) ) {
	/**
	 *
	 * Field: group
	 *
	 * @since 1.0.0
	 * @version 1.0.0
	 */
	class SP_WP_TABS_Field_group extends SP_WP_TABS_Fields {

		/**
		 * Group field constructor.
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
		 * Render field.
		 *
		 * @return void
		 */
		public function render() {

			$args = wp_parse_args(
				$this->field,
				array(
					'max'                    => 0,
					'min'                    => 0,
					'fields'                 => array(),
					'button_title'           => esc_html__( 'Add New', 'wp-expand-tabs-free' ),
					'accordion_title_prefix' => '',
					'accordion_title_number' => false,
					'accordion_title_auto'   => true,
				)
			);

			$title_prefix = ( ! empty( $args['accordion_title_prefix'] ) ) ? $args['accordion_title_prefix'] : '';
			$title_number = ( ! empty( $args['accordion_title_number'] ) ) ? true : false;
			$title_auto   = ( ! empty( $args['accordion_title_auto'] ) ) ? true : false;

			if ( ! empty( $this->parent ) && preg_match( '/' . preg_quote( '[' . $this->field['id'] . ']' ) . '/', $this->parent ) ) {

				echo '<div class="wptabspro-notice wptabspro-notice-danger">' . esc_html__( 'Error: Nested field id can not be same with another nested field id.', 'wp-expand-tabs-free' ) . '</div>';

			} else {

				// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				echo $this->field_before();

				echo '<div class="wptabspro-cloneable-item wptabspro-cloneable-hidden">';

				echo '<div class="wptabspro-cloneable-helper">';
				echo '<i class="wptabspro-cloneable-sort fa fa-arrows-alt"></i>';
				echo '<i class="wptabspro-cloneable-clone fa fa-clone"></i>';
				echo '<i class="wptabspro-cloneable-remove wptabspro-confirm fa fa-times" data-confirm="' . esc_html__( 'Are you sure to delete this item?', 'wp-expand-tabs-free' ) . '"></i>';
				echo '</div>';

				echo '<h4 class="wptabspro-cloneable-title">';
				echo '<span class="wptabspro-cloneable-text">';
				echo ( $title_number ) ? '<span class="wptabspro-cloneable-title-number"></span>' : '';
				echo ( $title_prefix ) ? '<span class="wptabspro-cloneable-title-prefix">' . esc_attr( $title_prefix ) . '</span>' : '';
				echo ( $title_auto ) ? '<span class="wptabspro-cloneable-value"><span class="wptabspro-cloneable-placeholder"></span></span>' : '';
				echo '</span>';
				echo '</h4>';

				echo '<div class="wptabspro-cloneable-content">';
				foreach ( $this->field['fields'] as $field ) {

					$field_parent  = $this->parent . '[' . $this->field['id'] . ']';
					$field_default = ( isset( $field['default'] ) ) ? $field['default'] : '';

					SP_WP_TABS::field( $field, $field_default, '_nonce', 'field/group', $field_parent );

				}
				echo '</div>';

				echo '</div>';

				echo '<div class="wptabspro-cloneable-wrapper wptabspro-data-wrapper" data-title-number="' . esc_attr( $title_number ) . '" data-unique-id="' . esc_attr( $this->unique ) . '" data-field-id="[' . esc_attr( $this->field['id'] ) . ']" data-max="' . esc_attr( $args['max'] ) . '" data-min="' . esc_attr( $args['min'] ) . '">';

				if ( ! empty( $this->value ) ) {

					$num = 0;

					foreach ( $this->value as $value ) {

						$first_id    = ( isset( $this->field['fields'][0]['id'] ) ) ? $this->field['fields'][0]['id'] : '';
						$first_value = ( isset( $value[ $first_id ] ) ) ? $value[ $first_id ] : '';
						$first_value = ( is_array( $first_value ) ) ? reset( $first_value ) : $first_value;

						echo '<div class="wptabspro-cloneable-item">';

						echo '<div class="wptabspro-cloneable-helper">';
						echo '<i class="wptabspro-cloneable-sort fa fa-arrows-alt"></i>';
						echo '<i class="wptabspro-cloneable-clone fa fa-clone"></i>';
						echo '<i class="wptabspro-cloneable-remove wptabspro-confirm fa fa-times" data-confirm="' . esc_html__( 'Are you sure to delete this item?', 'wp-expand-tabs-free' ) . '"></i>';
						echo '</div>';

						echo '<h4 class="wptabspro-cloneable-title">';
						echo '<span class="wptabspro-cloneable-text">';
						echo ( $title_number ) ? '<span class="wptabspro-cloneable-title-number">' . esc_attr( $num + 1 ) . '.</span>' : '';
						echo ( $title_prefix ) ? '<span class="wptabspro-cloneable-title-prefix">' . esc_attr( $title_prefix ) . '</span>' : '';
						echo ( $title_auto ) ? '<span class="wptabspro-cloneable-value">' . esc_attr( $first_value ) . '</span>' : '';
						echo '</span>';
						echo '</h4>';

						echo '<div class="wptabspro-cloneable-content">';

						foreach ( $this->field['fields'] as $field ) {

							$field_parent = $this->parent . '[' . $this->field['id'] . ']';
							$field_unique = ( ! empty( $this->unique ) ) ? $this->unique . '[' . $this->field['id'] . '][' . $num . ']' : $this->field['id'] . '[' . $num . ']';
							$field_value  = ( isset( $field['id'] ) && isset( $value[ $field['id'] ] ) ) ? $value[ $field['id'] ] : '';

							SP_WP_TABS::field( $field, $field_value, $field_unique, 'field/group', $field_parent );

						}

						echo '</div>';

						echo '</div>';

						$num++;

					}
				}

				echo '</div>';

				echo '<div class="wptabspro-cloneable-alert wptabspro-cloneable-max">' . esc_html__( 'You can not add more than', 'wp-expand-tabs-free' ) . ' ' . esc_attr( $args['max'] ) . '</div>';
				echo '<div class="wptabspro-cloneable-alert wptabspro-cloneable-min">' . esc_html__( 'You can not remove less than', 'wp-expand-tabs-free' ) . ' ' . esc_attr( $args['min'] ) . '</div>';

				echo '<a href="#" class="button button-primary wptabspro-cloneable-add">' . wp_kses_post( $args['button_title'] ) . '</a>';

				// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				echo $this->field_after();

			}

		}

		/**
		 * Enqueue
		 *
		 * @return void
		 */
		public function enqueue() {

			if ( ! wp_script_is( 'jquery-ui-accordion' ) ) {
				wp_enqueue_script( 'jquery-ui-accordion' );
			}

			if ( ! wp_script_is( 'jquery-ui-sortable' ) ) {
				wp_enqueue_script( 'jquery-ui-sortable' );
			}

		}

	}
}
