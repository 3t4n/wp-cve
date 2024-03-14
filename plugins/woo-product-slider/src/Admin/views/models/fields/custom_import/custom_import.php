<?php
/**
 * Framework custom import fields.
 *
 * @link https://shapedplugin.com
 * @since 2.0.0
 *
 * @package Woo_Product_Slider.
 * @subpackage Woo_Product_Slider/Admin.
 */

if ( ! defined( 'ABSPATH' ) ) {
	die;
} // Cannot access directly.

if ( ! class_exists( 'SPF_WPSP_Field_custom_import' ) ) {
	/**
	 *
	 * Field: Custom_import
	 *
	 * @since 1.0.0
	 * @version 1.0.0
	 */
	class SPF_WPSP_Field_custom_import extends SPF_WPSP_Fields {
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
			$wpsp_shortcodelink = admin_url( 'edit.php?post_type=sp_wps_shortcodes' );
			echo '<p><input type="file" id="import" accept=".json"></p>';
			echo '<p><button type="button" class="import">Import</button></p>';
			echo '<a id="wpsp_shortcode_link_redirect" href="' . esc_url( $wpsp_shortcodelink ) . '"></a>';
			echo wp_kses_post( $this->field_after() );
		}
	}
}
