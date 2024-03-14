<?php

/**
 * Recorder class for Easy Video Reviews
 *
 * @since 1.3.0
 * @package EasyVideoReviews
 */

// Namespace.
namespace EasyVideoReviews;

// Exit if accessed directly.
defined('ABSPATH') || exit(1);


if ( ! class_exists( __NAMESPACE__ . '\Recorder' ) ) {

	/**
	 * Recorder class for Easy Video Reviews
	 */
	class Recorder extends \EasyVideoReviews\Base\Controller {

		// Use the utilities trait.
		use \EasyVideoReviews\Traits\Utilities;

		/**
		 * Register hooks
		 *
		 * @return void
		 */
		public function register_hooks() {
			// Register action hooks.
			add_action('wp_footer', [ $this, 'render_recorder' ]);
			add_action('template_redirect', [ $this, 'template_redirect' ]);
		}

		/**
		 * Renders the recorder template
		 *
		 * @return void
		 */
		public function render_recorder() {
			$this->render_template('frontend/evr-modal');
		}


		/**
		 * Navigates to the single review page
		 *
		 * @return void
		 */
		public function template_redirect() {
			$single_review_page_id = $this->option()->get('review_page_id');

			// Check if we are on the single review page.
			$current_page_id = absint( get_queried_object_id() );

			if ( ( $current_page_id === $single_review_page_id ) ) {

				define('EVR_SINGLE_REVIEW', true);

				$this->render_template('frontend/single-review');
				exit();
			}

			global $wp;
			$current_slug = add_query_arg(array(), $wp->request);

			if ( 'leave-a-review' === $current_slug ) {
				$this->render_template('frontend/recorder-template');
				exit();
			}
		}
	}

	// Instantiate the class.
	Recorder::init();

}
