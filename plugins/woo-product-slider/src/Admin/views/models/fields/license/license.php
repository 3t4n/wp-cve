<?php
/**
 * Framework image select fields.
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

if ( ! class_exists( 'SPF_WPSP_Field_license' ) ) {
	/**
	 *
	 * Field: license
	 *
	 * @since 3.3.16
	 * @version 3.3.16
	 */
	class SPF_WPSP_Field_license extends SPF_WPSP_Fields {

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
			echo wp_kses_post( $this->field_before() );
			?>
				<div class="woo-product-slider-license text-center">
					<h3><?php esc_html_e( 'You\'re using Product Slider Lite - No License Needed. Enjoy', 'woo-product-slider' ); ?>! 🙂</h3>
					<p><?php esc_html_e( 'Upgrade to Product Slider Pro and unlock all the features.', 'woo-product-slider' ); ?></p>
					<div class="woo-product-slider-license-area">
						<div class="woo-product-slider-license-key">
							<div class="spwps-upgrade-button"><a href="https://wooproductslider.io/pricing/?ref=1" target="_blank"><?php esc_html_e( 'Upgrade To Pro Now', 'woo-product-slider' ); ?></a></div>
						</div>
					</div>
				</div>
				<?php
				echo wp_kses_post( $this->field_after() );
		}
	}
}
