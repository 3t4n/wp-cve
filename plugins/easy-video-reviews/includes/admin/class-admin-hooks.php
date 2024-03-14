<?php

/**
 * Admin Hooks
 * Registers all admin action hooks and filters
 *
 * @since 1.1.0
 * @package EasyVideoReviews
 */

// Namespace.
namespace EasyVideoReviews\Admin;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit( 1 );

if ( ! class_exists( __NAMESPACE__ . '\Hooks' ) ) {

	/**
	 * Admin Hooks
	 *
	 * @since 1.1.0
	 */
	class Hooks extends \EasyVideoReviews\Base\Controller {

		/**
		 * Registers all admin hooks
		 *
		 * @return void
		 */
		public function register_hooks() {
			// admin init.
			$this->init_appsero();
		}

		/**
		 * Init Appsero
		 *
		 * @return void
		 */
		public function init_appsero() {

			add_filter( 'appsero_is_local', '__return_false' );

			if ( ! class_exists( '\Appsero\Client' ) ) {
				require_once EASY_VIDEO_REVIEWS_INCLUDES . 'appsero/src/Client.php';
			}

			$client = new \Appsero\Client( '98bea24e-5324-4ccf-932a-6f1c77b67ad6', 'Easy Video Reviews', EASY_VIDEO_REVIEWS_FILE );
			$client->set_textdomain( 'easy-video-reviews' );
			$client->insights()->init();

			if ( function_exists( 'wppool_plugin_init' ) ) {
				$evr_plugin = wppool_plugin_init( 'easy_video_reviews', EASY_VIDEO_REVIEWS_URL . 'includes/wppool/background-image.png' );

				// Campain.
				$campain_image = EASY_VIDEO_REVIEWS_URL . 'includes/wppool/background-image.png';

				if ( $evr_plugin && is_object( $evr_plugin ) && method_exists( $evr_plugin, 'set_campaign' ) ) {
					$to = '2023-11-27';
					$from = '2023-11-16';
					$evr_plugin->set_campaign($campain_image, $to, $from);
				}
			}
		}
	}

	// Initialize the class.
	Hooks::init();
}
