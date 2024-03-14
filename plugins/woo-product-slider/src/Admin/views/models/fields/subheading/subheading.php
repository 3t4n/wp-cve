<?php
/**
 * Framework subheading fields.
 *
 * @link https://shapedplugin.com
 * @since 2.0.0
 *
 * @package Woo_Product_Slider.
 * @subpackage Woo_Product_Slider/admin.
 */

if ( ! defined( 'ABSPATH' ) ) {
	die;
} // Cannot access directly.

if ( ! class_exists( 'SPF_WPSP_Field_subheading' ) ) {
	/**
	 *
	 * Field: subheading
	 *
	 * @since 1.0.0
	 * @version 1.0.0
	 */
	class SPF_WPSP_Field_subheading extends SPF_WPSP_Fields {
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
			$check_replace_layout = ! empty( $this->field['replace_layout'] ) ? true : false;

			if ( $check_replace_layout ) {
				echo '<div class="spwps-replace-layout"><img src="' . esc_url( SP_WPS_URL ) . 'Admin/assets/images/replace-layout.webp" alt="replace layout"/></div>';
			} else {
				echo ( ! empty( $this->field['content'] ) ) ? wp_kses_post( $this->field['content'] ) : '';
			}
		}

	}
}
