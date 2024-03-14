<?php

/**
 * Base boot class file.
 * Loads all the core files and initializes the plugin.
 *
 * @package EasyVideoReviews
 * @since 1.0.0
 */

// Namespace.
namespace EasyVideoReviews;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit( 1 );


if ( ! class_exists( __NAMESPACE__ . '\Boot' ) ) {
	/**
	 * Base boot class file.
	 * Loads all the core files and initializes the plugin.
	 *
	 * @package EasyVideoReviews
	 * @since 1.0.0
	 */
	class Boot {

		/**
		 * Contains instance of the class
		 *
		 * @var object
		 */
		private static $instance;

		/**
		 * Checks if access token is set
		 *
		 * @return bool
		 */
		public static function is_access_token_set() {
			$access_token = get_option( 'evr_access_token' );
			$access_token = apply_filters( 'evr_access_token', $access_token );
			return ! empty( $access_token );
		}


		/**
		 * Initializes the class
		 *
		 * @return object
		 */
		public static function init() {
			if ( ! self::$instance ) {
				self::$instance = new self();
			}

			// Load all the files.
			self::$instance->load_files();

			return self::$instance;
		}

		/**
		 * Loads all the core files
		 *
		 * @return void
		 */
		public function load_files() {
			// Core files.s
			$this->define_constants();

			// check version for onboarding
			$this->redirect_onboarding_by_version();

			// Loads always.
			$this->load_common_files();

			// Loads only if access token is set.
			if ( $this->is_access_token_set() ) {
				$this->load_public_files();
			}

			//load extensions
			$this->load_extensions();

			// Loads only in admin.
			if ( is_admin() ) {
				$this->load_admin_files();
			}
		}

		/**
		 * Defines all the constants
		 *
		 * @return void
		 */
		public function define_constants() {

			// Helper constants .
			define( 'EASY_VIDEO_REVIEWS_PATH', plugin_dir_path( EASY_VIDEO_REVIEWS_FILE ) );
			define( 'EASY_VIDEO_REVIEWS_URL', plugin_dir_url( EASY_VIDEO_REVIEWS_FILE ) );

			// For quick access.
			define( 'EASY_VIDEO_REVIEWS_INCLUDES', EASY_VIDEO_REVIEWS_PATH . 'includes/' );
			define( 'EASY_VIDEO_REVIEWS_TEMPLATES', EASY_VIDEO_REVIEWS_PATH . 'templates/' );
			define( 'EASY_VIDEO_REVIEWS_PUBLIC', EASY_VIDEO_REVIEWS_URL . 'public/' );
		}

		/**
		 * Redirect to onboarding
		 *
		 * @return void
		 */
		public function redirect_onboarding_by_version() {
			if ( get_option( 'evr_onbarding_on_update', false ) ) {
				return;
			}

			$plugin_data = get_file_data( EASY_VIDEO_REVIEWS_FILE, array( 'Version' => 'Version' ), 'plugin' );
    		$vesion = $plugin_data['Version'];

			if ( 1 === version_compare($vesion,'1.7.7') ) {
				update_option( 'evr_access_token', '' );
				update_option( 'evr_onbarding_on_update', true );
			}
		}

		/**
		 * Loads all the common files
		 *
		 * @return void
		 */
		public function load_common_files() {

			// Admin helper classes.
			require_once EASY_VIDEO_REVIEWS_INCLUDES . 'helper/class-io.php';

			// Helper classes..
			require_once EASY_VIDEO_REVIEWS_INCLUDES . 'helper/class-option.php';

			require_once EASY_VIDEO_REVIEWS_INCLUDES . 'traits/trait-utility.php';
			require_once EASY_VIDEO_REVIEWS_INCLUDES . 'abstracts/class-abstract-controller.php';
			require_once EASY_VIDEO_REVIEWS_INCLUDES . 'helper/class-client.php';
			require_once EASY_VIDEO_REVIEWS_INCLUDES . 'classes/class-translation.php';

			require_once EASY_VIDEO_REVIEWS_INCLUDES . 'classes/class-globals.php';
			require_once EASY_VIDEO_REVIEWS_INCLUDES . 'classes/class-remote.php';
		}

		/**
		 * Loads all the admin files
		 *
		 * @return void
		 */
		public function load_admin_files() {
			//Google Api
			//require_once EASY_VIDEO_REVIEWS_INCLUDES . 'google-api/vendor/autoload.php';
			// Plugin SDK.
			require_once EASY_VIDEO_REVIEWS_INCLUDES . 'wppool/class-plugin.php';
			// Admin cores.
			require_once EASY_VIDEO_REVIEWS_INCLUDES . 'classes/class-install.php';

			require_once EASY_VIDEO_REVIEWS_INCLUDES . 'admin/class-admin-menus.php';
			require_once EASY_VIDEO_REVIEWS_INCLUDES . 'admin/class-admin-assets.php';
			require_once EASY_VIDEO_REVIEWS_INCLUDES . 'admin/class-admin-events.php';
			require_once EASY_VIDEO_REVIEWS_INCLUDES . 'admin/class-admin-notices.php';
			require_once EASY_VIDEO_REVIEWS_INCLUDES . 'admin/class-admin-hooks.php';

			if ( wp_doing_ajax() ) {
				require_once EASY_VIDEO_REVIEWS_INCLUDES . 'admin/class-admin-ajax.php';
			}
		}

		/**
		 * Loads all the public files
		 *
		 * @return void
		 */
		public function load_public_files() {
			require_once EASY_VIDEO_REVIEWS_INCLUDES . 'classes/class-ajax.php';
			require_once EASY_VIDEO_REVIEWS_INCLUDES . 'classes/class-assets.php';
			require_once EASY_VIDEO_REVIEWS_INCLUDES . 'classes/class-form.php';
			require_once EASY_VIDEO_REVIEWS_INCLUDES . 'classes/class-recorder.php';
			require_once EASY_VIDEO_REVIEWS_INCLUDES . 'classes/class-review.php';
			require_once EASY_VIDEO_REVIEWS_INCLUDES . 'classes/class-translation.php';

			if ( wp_doing_ajax() ) {
				require_once EASY_VIDEO_REVIEWS_INCLUDES . 'classes/class-ajax.php';
			}

			// Block editor.
			require_once EASY_VIDEO_REVIEWS_INCLUDES . 'blocks/class-blocks.php';

			// Elementor.
			require_once EASY_VIDEO_REVIEWS_INCLUDES . 'elementor/class-widget.php';

			// Shortcodes.
			require_once EASY_VIDEO_REVIEWS_INCLUDES . 'shortcodes/class-shortcode-button.php';
			require_once EASY_VIDEO_REVIEWS_INCLUDES . 'shortcodes/class-shortcode-reviews.php';
		}

		/**
		 * Loads all the extensions
		 *
		 * @return void
		 */
		public function load_extensions() {

			require_once EASY_VIDEO_REVIEWS_INCLUDES . 'abstracts/class-abstract-extension.php';
			require_once EASY_VIDEO_REVIEWS_INCLUDES . 'extensions/woocommerce/class-woocommerce.php';
			require_once EASY_VIDEO_REVIEWS_INCLUDES . 'extensions/easy-digital-downloads/class-easy-digital-downloads.php';
		}
	}

	// Initialize the class.
	Boot::init();
}
