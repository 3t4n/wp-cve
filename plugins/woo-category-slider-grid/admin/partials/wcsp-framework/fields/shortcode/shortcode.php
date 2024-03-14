<?php
/**
 * Framework shortcode field file.
 *
 * @link       https://shapedplugin.com/
 * @since      1.0.0
 *
 * @package    Woo_Category_Slider
 * @subpackage Woo_Category_Slider/admin/partials/section/settings
 * @author     ShapedPlugin <support@shapedplugin.com>
 */

if ( ! defined( 'ABSPATH' ) ) {
	die;
} // Cannot access directly.

if ( ! class_exists( 'SP_WCS_Field_shortcode' ) ) {
	/**
	 *
	 * Field: shortcode
	 *
	 * @since 1.0.0
	 * @version 1.0.0
	 */
	class SP_WCS_Field_shortcode extends SP_WCS_Fields {
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
		 * Render
		 *
		 * @return void
		 */
		public function render() {

			// Get the Post ID.
			$post_id = get_the_ID();

			echo ( ! empty( $post_id ) ) ? '<div class="wcsp-scode-wrap">
				<div class="wcsp-col-lg-3">
					<div class="wcsp-scode-content">
						<h2 class="wcsp-scode-title">Shortcode</h2>
						<p>Copy and paste this shortcode into your posts or pages:</p>
						<div class="shortcode-wrap"><div class="selectable">[woocatslider id="' . esc_attr( $post_id ) . '"]</div></div><div class="wcsp-after-copy-text"><i class="fa fa-check-circle"></i>  Shortcode  Copied to Clipboard! </div>
					</div>
				</div>
				<div class="wcsp-col-lg-3">
					<div class="wcsp-scode-content">
						<h2 class="wcsp-scode-title">Template Include</h2>
						<p>Paste the PHP code into your template file:</p>
						<div class="shortcode-wrap"><div class="selectable">&lt;?php echo do_shortcode(\'[woocatslider id="' . esc_attr( $post_id ) . '"]\'); ?&gt;</div>
						</div>
					</div>
				</div>
			</div>' : '';
		}

	}
}
