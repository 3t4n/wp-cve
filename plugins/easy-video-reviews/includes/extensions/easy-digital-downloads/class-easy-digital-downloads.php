<?php
/**
 * Handled all hooks related to EasyDigitalDownloads for Easy Video Reviews
 *
 * @package EasyVideoReviews
 */

namespace EasyVideoReviews;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit( 1 );


if ( ! class_exists( __NAMESPACE__ . '\EasyDigitalDownloads' ) ) {

	/**
	 * Handled all hooks related to EasyDigitalDownloads for Easy Video Reviews
	 *
	 * @package EasyVideoReviews
	 */
	class EasyDigitalDownloads extends \EasyVideoReviews\Base\Controller {

		// Use Utilities trait.
		use \EasyVideoReviews\Traits\Utilities;

		/**
		 * Register the hooks
		 *
		 * @return void
		 */
		public function register_hooks() {
			$this->init_extension();
			$this->add_actions();
			$this->add_filters();
		}

		/**
		 * Initialize the extension
		 *
		 * @return mixed
		 */
		public function init_extension() {
			require_once __DIR__ . '/class-easy-digital-downloads-extension.php';

			// Initialize the extension.
			\EasyVideoReviews\Extensions\EasyDigitalDownloads::init();
		}

		/**
		 * Add action hooks
		 *
		 * @return mixed
		 */
		public function add_actions() {
			// Check if the user has premium access.
			// Modifying this section will cause the plugin to break.
			if ( ! $this->client()->has_premium_access() ) {
				return false;
			}

			add_action('edd_payment_receipt_after', [ $this, 'add_recording_button' ], 12);
			add_action('edd_email_footer', [ $this, 'evr_edd_email_footer' ], 12);
		}

		/**
		 * Add record button after the order complete page
		 *
		 * @return mixed
		 */
		public function add_recording_button() {
			$edd_option = (array) $this->option()->get('edd');

			if ( '1' !== $edd_option['order_complete_button'] ) {
				return false;
			}

			echo wp_kses_post(do_shortcode('[evr-button folder="edd-review"]'));
		}


		/**
		 * Add record button to the email footer
		 *
		 * @return mixed
		 */
		public function evr_edd_email_footer() {
			$edd_option = (array) $this->option()->get('edd');
			if ( '1' !== $edd_option['order_complete_email'] ) {
				return false;
			}

			echo wp_kses_post($this->globals()->get_email_append_message());
		}
	}

	// Initialize the class.
	EasyDigitalDownloads::init();
}
