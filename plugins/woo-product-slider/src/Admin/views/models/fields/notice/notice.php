<?php
/**
 * Framework notice fields.
 *
 * @link https://shapedplugin.com
 * @since 2.0.0
 *
 * @package Woo_Product_Slider_pro.
 * @subpackage Woo_Product_Slider_pro/models.
 */

if ( ! defined( 'ABSPATH' ) ) {
	die;
} // Cannot access directly.

if ( ! class_exists( 'SPF_WPSP_Field_notice' ) ) {
	/**
	 *
	 * Field: notice
	 *
	 * @since 1.0.0
	 * @version 1.0.0
	 */
	class SPF_WPSP_Field_notice extends SPF_WPSP_Fields {
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
			$style = ( ! empty( $this->field['style'] ) ) ? $this->field['style'] : 'normal';
			echo ( ! empty( $this->field['content'] ) ) ? '<div class="spwps-notice spwps-notice-' . esc_attr( $style ) . '">' . wp_kses_post( $this->field['content'] ) . '</div>' : '';
		}

	}
}
