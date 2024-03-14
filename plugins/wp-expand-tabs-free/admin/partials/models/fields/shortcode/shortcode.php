<?php
/**
 * Framework shortcode field file.
 *
 * @link http://shapedplugin.com
 * @since 2.0.0
 *
 * @package wp-expand-tabs-free
 * @subpackage wp-expand-tabs-free/Framework
 */

if ( ! defined( 'ABSPATH' ) ) {
	die; } // Cannot access directly.

if ( ! class_exists( 'SP_WP_TABS_Field_shortcode' ) ) {
	/**
	 *
	 * Field: shortcode
	 *
	 * @since 1.0.0
	 * @version 1.0.0
	 */
	class SP_WP_TABS_Field_shortcode extends SP_WP_TABS_Fields {

		/**
		 * Shortcode field constructor.
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

			// Get the Post ID.
			$post_id = get_the_ID();

			echo ( ! empty( $post_id ) ) ? '<div class="sp-tab__scode-wrap"><span class="sp-tab__sc-title">Shortcode:</span><span class="sp-tab__shortcode-selectable">[wptabs id="' . esc_attr( $post_id ) . '"]</span></div><div class="sp-tab__scode-wrap"><span class="sp-tab__sc-title">PHP Code:</span><span class="sp-tab__shortcode-selectable">&lt;?php echo do_shortcode(\'[wptabs id="' . esc_attr( $post_id ) . '"]\'); ?&gt;</span></div><div class="eap-scode-wrap"><div class="sp_tab-after-copy-text"><i class="fa fa-check-circle"></i>  Shortcode  Copied to Clipboard! </div>' : '';
		}

	}
}
