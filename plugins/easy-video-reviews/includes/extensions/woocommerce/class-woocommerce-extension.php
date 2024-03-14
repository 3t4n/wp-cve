<?php
/**
 * Built-in Easy Video Reviews Extension
 * WooCommerce Integration
 *
 * @package EasyVideoReviews
 */

namespace EasyVideoReviews\Extensions;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit( 1 );


if ( ! class_exists( __NAMESPACE__ . '\WooCommerce' ) ) {

	/**
	 * Class WooCommerce
	 *
	 * @package EasyVideoReviews
	 */
	class WooCommerce extends \EasyVideoReviews\Base\Extension {

		// Use utility trait.
		use \EasyVideoReviews\Traits\Utilities;

		/**
		 * Return the extension id
		 *
		 * @return string
		 */
		public function id() {
			return 'woocommerce';
		}

		/**
		 * Return the extension name
		 *
		 * @return string
		 */
		public function name() {
			return esc_html__( 'WooCommerce', 'easy-video-reviews' );
		}

		/**
		 * Returns the extension icon url
		 */
		public function icon_url() {
			return EASY_VIDEO_REVIEWS_PUBLIC . 'images/icons/woocommerce.png';
		}

		/**
		 * Return the extension body
		 *
		 * @return void
		 */
		public function form() {
			$this->render_template( 'admin/integrations/built-in/woocommerce' );
			$this->render_template( 'admin/integrations/built-in/email-footer' );
		}

		/**
		 * Return the extension options
		 *
		 * @return array
		 */
		public function options() {
			return [
				'single_tab'               => false,
				'hide_default_reviews_tab' => false,
				'single_button'            => false,
				'single_tab_showcase'      => false,
				'single_tab_label'         => esc_html__( 'Video Reviews', 'easy-video-reviews' ),
				'single_order_by'          => 'date',
				'single_order'             => 'desc',
				'order_complete_button'    => false,
				'order_complete_email'     => false,
			];
		}

		/**
		 * Return the extension script
		 */
		public function script() {
			return [
				'is_active' => class_exists( '\WooCommerce' ),
			];
		}
	}

}
