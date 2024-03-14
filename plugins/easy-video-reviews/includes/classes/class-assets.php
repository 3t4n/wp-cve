<?php

/**
 * Frontend assets
 * Registers all frontend assets and scripts
 *
 * @package EasyVideoReviews
 * @since 1.3.0
 */

// Namespace.
namespace EasyVideoReviews;

// Exit if accessed directly.
defined('ABSPATH') || exit(1);

if ( ! class_exists(__NAMESPACE__ . '\Assets') ) {

	/**
	 * Frontend assets
	 * Registers all frontend assets and scripts
	 */
	class Assets extends \EasyVideoReviews\Base\Controller {

		// Use Utilities trait.
		use \EasyVideoReviews\Traits\Utilities;

		/**
		 * Adds enqueue scripts
		 */
		public function register_hooks() {
			// Register action hooks.
			add_action('wp_enqueue_scripts', [ $this, 'enqueue_scripts' ]);
		}

		/**
		 * Default showcase config
		 *
		 * @return array
		 */
		public function default_showcase_config() {
			$configs = [
				'id'             => '',
				'view'           => 'grid',
				'limit'          => 9,
				'order'          => 'DESC',
				'columns_mobile' => 1,
				'columns_tablet' => 2,
				'columns'        => 3,
				'gap_mobile'     => 2,
				'gap_tablet'     => 4,
				'gap'            => 6,
				'pagination'     => 0,
				'navigation'     => 1,
				'scrollbar'      => 0,
				'infinity'       => 1,
				'autoplay'       => 1,
				'delay'          => 5000,
				'rounded'        => true,
				'date'           => false,
				'folder'         => '',
				'in'             => '',
				'not_in'         => '',
			];

			return apply_filters('evr_showcase_default_config', $configs);
		}

		/**
		 * Enqueues frontend scripts
		 *
		 * @return void
		 */
		public function enqueue_scripts() {

			$translation = new Translation();
			// Frontend localized script.
			$frontend_footer_script = [
				'ajax_url'         => admin_url('admin-ajax.php'),
				'home_url'         => home_url(),
				'nonce'            => wp_create_nonce( 'evr_nonce' ),
				'server_url'       => $this->client()->get_server(),
				'host'             => $this->client()->get_host(),
				'access_token'     => $this->client()->get_access_token(),

				'preference'       => $this->option()->get('recorder'),
				'forms'            => $this->option()->get('forms'),
				'review_options'   => $this->option()->get('review_option'),
				'translations'     => $translation->get_all(),
				'default_showcase' => $this->default_showcase_config(),
				'debug'            => defined('WP_DEBUG') && WP_DEBUG,
			];

			wp_register_script('evr_options_scripts', '', [], EASY_VIDEO_REVIEWS_VERSION, true);
			wp_localize_script('evr_options_scripts', '_evr', apply_filters('evr_localize_script', $frontend_footer_script));
			wp_enqueue_script('evr_options_scripts');

			wp_enqueue_script('evr-frontend', plugin_dir_url(EASY_VIDEO_REVIEWS_FILE) . 'public/js/app.min.js', [ 'jquery' ], EASY_VIDEO_REVIEWS_VERSION, true);
			wp_enqueue_style('evr-frontend', plugin_dir_url(EASY_VIDEO_REVIEWS_FILE) . 'public/css/app.min.css', null, EASY_VIDEO_REVIEWS_VERSION);
		}
	}

	// Instance.
	Assets::init();
}
