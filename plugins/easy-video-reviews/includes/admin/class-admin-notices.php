<?php
/**
 * Handles all admin notices
 *
 * @since 1.3.0
 * @package EasyVideoReviews
 */

// Namespace.
namespace EasyVideoReviews\Admin;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit( 1 );

if ( ! class_exists( __NAMESPACE__ . '\Notices' ) ) {

	/**
	 * Handles all admin notices
	 */
	class Notices extends \EasyVideoReviews\Base\Controller {

		// Use helper traits.
		use \EasyVideoReviews\Traits\Utilities;

		/**
		 * Register hooks
		 *
		 * @return void
		 */
		public function register_hooks() {
			// redirect to admin page on activation this plugin.
			add_action( 'admin_init', [ $this, 'shake_admin_notices' ] );
		}
		/**
		 * Hide admin notices
		 */
		public function shake_admin_notices() {
			$show_review_notice = $this->option()->get_transient( 'show_review_notice', false ) && $this->option()->get( 'show_review_notice', true );

			// show after 7 days.
			if ( ! $show_review_notice ) {
				// set hide after 7 days.
				$this->option()->set_transient( 'show_review_notice', 'hide', 7 * DAY_IN_SECONDS );
			}

			$show_affiliate_notice = $this->option()->get_transient( 'show_affiliate_notice', false ) && $this->option()->get( 'show_affiliate_notice', true );

			// show after 14 days.
			if ( ! $show_affiliate_notice ) {
				// set hide after 14 days.
				$this->option()->set_transient( 'show_affiliate_notice', 'hide', 14 * DAY_IN_SECONDS );
			}
		}
	}

	// Instantiate the class.
	Notices::init();
}
