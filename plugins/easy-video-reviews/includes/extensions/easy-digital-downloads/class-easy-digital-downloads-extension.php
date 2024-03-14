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


if ( ! class_exists(__NAMESPACE__ . '\EasyDigitalDownloads') ) {
	/**
	 * Class EasyDigitalDownloads
	 *
	 * @package EasyVideoReviews
	 */
	class EasyDigitalDownloads extends \EasyVideoReviews\Base\Extension {

		// Use Utilities trait.
		use \EasyVideoReviews\Traits\Utilities;

		/**
		 * Return the extension ID.
		 *
		 * @return string
		 */
		public function id() {
			return 'edd';
		}

		/**
		 * Return the extension name.
		 *
		 * @return string
		 */
		public function name() {
			return __('Easy Digital Downloads', 'easy-video-reviews');
		}

		/**
		 * Return the extension icon URL.
		 *
		 * @return string
		 */
		public function icon_url() {
			return EASY_VIDEO_REVIEWS_PUBLIC . 'images/icons/edd.png';
		}

		/**
		 * Return the extension form.
		 *
		 * @return void
		 */
		public function form() {
			echo wp_kses_post($this->render_template('admin/integrations/built-in/easy-digital-downloads'));
			echo wp_kses_post($this->render_template('admin/integrations/built-in/email-footer', []));
		}


		/**
		 * Return the extension options.
		 *
		 * @return array
		 */
		public function options() {
			return [
				'order_complete_button' => true,
				'order_complete_email'  => true,
			];
		}

		/**
		 * Return the extension script.
		 *
		 * @return array
		 */
		public function script() {
			return [
				'is_active' => class_exists('\Easy_Digital_Downloads'),
			];
		}
	}

}
