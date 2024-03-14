<?php
/**
 * Framework fieldset fields.
 *
 * @link https://shapedplugin.com
 * @since 2.0.0
 *
 * @package Woo_Product_Slider.
 * @subpackage Woo_Product_Slider/models.
 */

use ShapedPlugin\WooProductSlider\Admin\views\models\classes\SPF_WPSP;

if ( ! defined( 'ABSPATH' ) ) {
	die;
} // Cannot access directly.

if ( ! class_exists( 'SPF_WPSP_Field_fieldset' ) ) {
	/**
	 *
	 * Field: fieldset
	 *
	 * @since 1.0.0
	 * @version 1.0.0
	 */
	class SPF_WPSP_Field_fieldset extends SPF_WPSP_Fields {
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

			echo wp_kses_post( $this->field_before() );

			echo '<div class="spwps-fieldset-content" data-depend-id="' . esc_attr( $this->field['id'] ) . '">';

			foreach ( $this->field['fields'] as $field ) {

				$field_id      = ( isset( $field['id'] ) ) ? $field['id'] : '';
				$field_default = ( isset( $field['default'] ) ) ? $field['default'] : '';
				$field_value   = ( isset( $this->value[ $field_id ] ) ) ? $this->value[ $field_id ] : $field_default;
				$unique_id     = ( ! empty( $this->unique ) ) ? $this->unique . '[' . $this->field['id'] . ']' : $this->field['id'];

				SPF_WPSP::field( $field, $field_value, $unique_id, 'field/fieldset' );

			}

			echo '</div>';

			echo wp_kses_post( $this->field_after() );

		}

	}
}
