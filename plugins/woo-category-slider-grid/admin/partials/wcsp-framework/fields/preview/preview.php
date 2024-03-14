<?php
/**
 * Framework preview field file.
 *
 * @link http://shapedplugin.com
 * @since 1.3.0
 *
 * @package Woo_Category_Slider
 * @subpackage Woo_Category_Slider/Framework
 */

if ( ! defined( 'ABSPATH' ) ) {
	die; } // Cannot access directly.

if ( ! class_exists( 'SP_WCS_Field_preview' ) ) {
	/**
	 *
	 * Field: shortcode
	 *
	 * @since 1.3.0
	 * @version 1.3.0
	 */
	class SP_WCS_Field_preview extends SP_WCS_Fields {
		/**
		 * Preview field constructor.
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
		 * Render
		 *
		 * @return void
		 */
		public function render() {
			echo '<div class="sp-wcs-preview-box"><div id="sp-wcs-preview-box"></div></div>';
		}

	}
}
