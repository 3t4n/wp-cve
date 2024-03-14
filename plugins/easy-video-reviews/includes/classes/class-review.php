<?php

/**
 * Class Review
 * Handles the review functionality
 *
 * @since 1.3.8
 * @package EasyVideoReviews
 */

// Namespace.
namespace EasyVideoReviews;

// Exit if accessed directly.
defined('ABSPATH') || exit(1);

if ( ! class_exists( __NAMESPACE__ . '\Review' ) ) {

	/**
	 * Class Review
	 *
	 * @package EasyVideoReviews
	 */
	class Review extends \EasyVideoReviews\Base\Controller {

		// Use Utilities trait.
		use \EasyVideoReviews\Traits\Utilities;


		/**
		 * Register hooks
		 *
		 * @since 1.3.8
		 */
		public function register_hooks() {
			// Handle template redirect.
			add_action('template_redirect', [ $this, 'handle_template_redirect' ], 99);
		}

		/**
		 * Handle template redirect
		 *
		 * @since 1.3.8
		 */
		public function handle_template_redirect() {
			$review_page_id = intval( $this->option()->get('review_page_id') );

			if ( get_the_ID() !== $review_page_id ) {
				return;
			}

			$review = $this->load_review_from_parameter();
			$args   = [
				'review'      => $review,
				'is_headless' => isset( $_GET['is_headless'] ), // phpcs:ignore WordPress.Security.NonceVerification.Recommended
				'page_url'    => get_permalink( $review_page_id ) . ( isset( $_GET['v'] ) ? '?v=' . sanitize_text_field( wp_unslash( $_GET['v'] ) ) : '' ), // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			];

			$this->load_template( 'frontend/single-review', $args );
			exit();
		}

		/**
		 * Load review from parameter
		 *
		 * @since 1.3.8
		 *
		 * @return mixed
		 */
		public function load_review_from_parameter() {
			// Check if id is set.
			// phpcs:ignore WordPress.Security.NonceVerification.Recommended
			if ( ! isset( $_GET['v'] ) ) {
				return false;
			}

			// Get the id.
			// phpcs:ignore WordPress.Security.NonceVerification.Recommended
			$slug = isset( $_GET['v'] ) ? sanitize_text_field( wp_unslash( $_GET['v'] ) ) : false;

			// Bail if no id.
			if ( ! $slug ) {
				return false;
			}

			// Get the review.
			$remote = new Remote();
			$review = $remote->get_review_by_slug( $slug );

			// Bail if no review.
			if ( ! $review ) {
				return false;
			}

			// Return the review.
			return $review[0];
		}
	}

	// Initialize the class.
	Review::init();
}
