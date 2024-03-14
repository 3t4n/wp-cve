<?php
/**
 * Framework select fields.
 *
 * @link https://shapedplugin.com
 * @since 2.0.0
 *
 * @package Woo_Product_Slider.
 * @subpackage Woo_Product_Slider/admin.
 */

use ShapedPlugin\WooProductSlider\Admin\views\models\classes\SPF_WPSP;

if ( ! defined( 'ABSPATH' ) ) {
	die;
} // Cannot access directly.

if ( ! class_exists( 'SPF_WPSP_Field_select' ) ) {
	/**
	 *
	 * Field: select
	 *
	 * @since 1.0.0
	 * @version 1.0.0
	 */
	class SPF_WPSP_Field_select extends SPF_WPSP_Fields {
		/**
		 * Constructor function.
		 *
		 * @param array  $field field.
		 * @param string $value field value.
		 * @param string $unique field unique.
		 * @param string $where field where.
		 * @param string $parent field parent.
		 * @since 2.0
		 */
		public function __construct( $field, $value = '', $unique = '', $where = '', $parent = '' ) {
			parent::__construct( $field, $value, $unique, $where, $parent );
		}

		/**
		 * Render
		 *
		 * @return void
		 */
		public function render() {

			$args = wp_parse_args(
				$this->field,
				array(
					'placeholder' => '',
					'chosen'      => false,
					'multiple'    => false,
					'sortable'    => false,
					'ajax'        => false,
					'preview'     => false,
					'nav-preview' => false,
					'settings'    => array(),
					'query_args'  => array(),
				)
			);

			$this->value = ( is_array( $this->value ) ) ? $this->value : array_filter( (array) $this->value );

			echo wp_kses_post( $this->field_before() );

			if ( isset( $this->field['options'] ) ) {

				if ( ! empty( $args['ajax'] ) ) {
					$args['settings']['data']['type']  = $args['options'];
					$args['settings']['data']['nonce'] = wp_create_nonce( 'spwps_chosen_ajax_nonce' );
					if ( ! empty( $args['query_args'] ) ) {
						$args['settings']['data']['query_args'] = $args['query_args'];
					}
				}

				$chosen_rtl       = ( is_rtl() ) ? ' chosen-rtl' : '';
				$multiple_name    = ( $args['multiple'] ) ? '[]' : '';
				$multiple_attr    = ( $args['multiple'] ) ? ' multiple="multiple"' : '';
				$chosen_sortable  = ( $args['chosen'] && $args['sortable'] ) ? ' spwps-chosen-sortable' : '';
				$chosen_ajax      = ( $args['chosen'] && $args['ajax'] ) ? ' spwps-chosen-ajax' : '';
				$placeholder_attr = ( $args['chosen'] && $args['placeholder'] ) ? ' data-placeholder="' . esc_attr( $args['placeholder'] ) . '"' : '';
				$field_class      = ( $args['chosen'] ) ? ' class="spwps-chosen' . esc_attr( $chosen_rtl . $chosen_sortable . $chosen_ajax ) . '"' : '';
				$field_name       = $this->field_name( $multiple_name );
				$field_attr       = $this->field_attributes();
				$maybe_options    = $this->field['options'];
				$chosen_data_attr = ( $args['chosen'] && ! empty( $args['settings'] ) ) ? ' data-chosen-settings="' . esc_attr( wp_json_encode( $args['settings'] ) ) . '"' : '';

				if ( is_string( $maybe_options ) && ! empty( $args['chosen'] ) && ! empty( $args['ajax'] ) ) {
					$options = $this->field_wp_query_data_title( $maybe_options, $this->value );
				} elseif ( is_string( $maybe_options ) ) {
					$options = $this->field_data( $maybe_options, false, $args['query_args'] );
				} else {
					$options = $maybe_options;
				}

				if ( ( is_array( $options ) && ! empty( $options ) ) || ( ! empty( $args['chosen'] ) && ! empty( $args['ajax'] ) ) ) {

					if ( ! empty( $args['chosen'] ) && ! empty( $args['multiple'] ) ) {
						// Ignore phpcs errors because already field_attr escaped.
						// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
						echo '<select name="' . $field_name . '" class="spwps-hide-select hidden"' . $multiple_attr . $field_attr . '>';
						foreach ( $this->value as $option_key ) {
							echo '<option value="' . esc_attr( $option_key ) . '" selected>' . esc_attr( $option_key ) . '</option>';
						}
						echo '</select>';

						$field_name = '_pseudo';
						$field_attr = '';

					}

					// These attributes has been escaped above.
          			// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
					echo '<select name="' . esc_attr( $field_name ) . '"' . $field_class . $multiple_attr . $placeholder_attr . $field_attr . $chosen_data_attr . '>';

					if ( $args['placeholder'] && empty( $args['multiple'] ) ) {
						if ( ! empty( $args['chosen'] ) ) {
							echo '<option value=""></option>';
						} else {
							echo '<option value="">' . esc_attr( $args['placeholder'] ) . '</option>';
						}
					}
					$selected_value = '';

					foreach ( $options as $option_key => $option ) {
						$pro_only = isset( $option['pro_only'] ) ? ' disabled' : '';
						$selected = ( in_array( $option_key, $this->value ) ) ? ' selected' : '';
						if ( $pro_only || isset( $option['name'] ) ) {
							$selected = ( in_array( $option_key, $this->value ) ) ? ' selected' : '';
							if ( in_array( $option_key, $this->value ) ) {
								var_dump($option_key);
								$selected_value = $option_key;
							}
							echo '<option value="' . esc_attr( $option_key ) . '" ' . esc_attr( $selected ) . esc_attr( $pro_only ) . '>' . esc_attr( $option['name'] ) . '</option>';
						} else {
							echo '<option value="' . esc_attr( $option_key ) . '" ' . esc_attr( $selected ) . '>' . esc_attr( $option ) . '</option>';
						}
					}
					echo '</select>';
					if ( isset( $args['preview'] ) && $args['preview'] ) {
						echo '<img src="' . SPF_WPSP::include_plugin_url( 'assets/images/theme/' . $selected_value . '.png' ) . '" class="theme_preview">'; // phpcs:ignore
					}
					if ( isset( $args['nav-preview'] ) && $args['nav-preview'] ) {
						echo '<img src="' . esc_url( SPF_WPSP::include_plugin_url( 'assets/images/navigation-preview/' . $selected_value . '.svg' ) ) . '" class="nav_preview">';
						echo '<div class="wps-pro-notice"></div>';
					}
				} else {
					echo ( ( ! empty( $this->field['empty_message'] ) ) ? esc_attr( $this->field['empty_message'] ) : esc_html__( 'No data available.', 'woo-product-slider' ) );
				}
			}

			echo wp_kses_post( $this->field_after() );

		}

		/**
		 * Enqueue
		 *
		 * @return void
		 */
		public function enqueue() {

			if ( ! wp_script_is( 'jquery-ui-sortable' ) ) {
				wp_enqueue_script( 'jquery-ui-sortable' );
			}

		}

	}
}
