<?php

/**
 * Admin Menus
 * Registers all admin menus and submenus for Easy Video Reviews
 *
 * @since 1.1.0
 * @package EasyVideoReviews
 */

// Namespace.
namespace EasyVideoReviews\Admin;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit( 1 );

if ( ! class_exists( __NAMESPACE__ . '\Menus' ) ) {

	/**
	 * Admin Menus
	 *
	 * @since 1.1.0
	 */
	class Menus extends \EasyVideoReviews\Base\Controller {

		// Use Utilities trait.
		use \EasyVideoReviews\Traits\Utilities;

		/**
		 * Registers all admin menus
		 *
		 * @return void
		 */
		public function register_hooks() {

			add_action( 'admin_menu', [ $this, 'admin_menu' ] );
		}

		/**
		 * Returns the permissions for the admin menu
		 *
		 * @return string
		 */
		public function get_permissions() {
			return 'edit_others_posts';
		}

		/**
		 * Adds admin menu
		 *
		 * @return void
		 */
		public function admin_menu() {

			if ( $this->client()->has_valid_token() ) {
				// Dashboard template.
				add_menu_page( __( 'Easy Video Reviews', 'easy-video-reviews' ), __( 'Easy Video Reviews', 'easy-video-reviews' ), $this->get_permissions(), 'easy-video-reviews', function () {
				}, ' ' );

				// Reviews template.
				add_submenu_page( 'easy-video-reviews', __( 'Easy Video Reviews Reviews', 'easy-video-reviews' ), __( 'Reviews', 'easy-video-reviews' ), $this->get_permissions(), 'easy-video-reviews', function () {

					$this->render_template( 'admin/base' );
				} );

				// Gallery template.
				add_submenu_page( 'easy-video-reviews', __( 'Easy Video Reviews Gallery', 'easy-video-reviews' ), __( 'Gallery', 'easy-video-reviews' ), $this->get_permissions(), 'easy-video-reviews-gallery', function () {

					$this->render_template( 'admin/base' );
				} );

				// Integrations template.
				add_submenu_page( 'easy-video-reviews', __( 'Easy Video Reviews Integrations', 'easy-video-reviews' ), __( 'Integrations', 'easy-video-reviews' ), $this->get_permissions(), 'easy-video-reviews-integrations', function () {

					$this->render_template( 'admin/base' );
				} );

				// Settings template.
				add_submenu_page( 'easy-video-reviews', __( 'Easy Video Reviews Settings', 'easy-video-reviews' ), __( 'Settings', 'easy-video-reviews' ), $this->get_permissions(), 'easy-video-reviews-settings', function () {

					$this->render_template( 'admin/base' );
				}, 999 );

				// Utilities template.
				add_submenu_page( 'easy-video-reviews', __( 'Easy Video Reviews Utility', 'easy-video-reviews' ), __( 'Utility', 'easy-video-reviews' ), $this->get_permissions(), 'easy-video-reviews-utility', function () {

					$this->render_template( 'admin/base' );
				}, 999 );

				// Profile template.
				add_submenu_page( 'easy-video-reviews', __( 'Easy Video Reviews Profile', 'easy-video-reviews' ), __( 'Profile & Plan', 'easy-video-reviews' ), $this->get_permissions(), 'easy-video-reviews-profile', function () {

					$this->render_template( 'admin/base' );
				} );

				// Documentation template.
				add_submenu_page( 'easy-video-reviews', __( 'Easy Video Reviews Documentation', 'easy-video-reviews' ), __( 'Docs & Help', 'easy-video-reviews' ), $this->get_permissions(), 'easy-video-reviews-documentation', function () {

					$this->render_template( 'admin/base' );
				} );

				if ( ! $this->option()->get('is_pro') ) {
					add_submenu_page( 'easy-video-reviews', __( 'Easy Video Reviews Pro', 'easy-video-reviews' ), __( '<div class="evr-upgrade-plan"><span class="evr-upgrade-icon"><svg xmlns="http://www.w3.org/2000/svg" width="21" height="21" viewBox="0 0 21 21" fill="none">
					<circle cx="10.5" cy="10.5" r="10.5" fill="white"/>
					<path d="M10.5 21C7.71523 21 5.04451 19.8938 3.07538 17.9246C1.10625 15.9555 0 13.2848 0 10.5C0 7.71523 1.10625 5.04451 3.07538 3.07538C5.04451 1.10625 7.71523 0 10.5 0C13.2848 0 15.9555 1.10625 17.9246 3.07538C19.8938 5.04451 21 7.71523 21 10.5C21 13.2848 19.8938 15.9555 17.9246 17.9246C15.9555 19.8938 13.2848 21 10.5 21ZM14.4782 5.25C12.9536 5.25009 11.4914 5.85582 10.4134 6.93394L10.1115 7.2345C9.72024 7.62827 9.19816 7.87762 8.64306 7.87762H7.41563C7.21927 7.87765 7.02619 7.92797 6.85479 8.02378C6.68339 8.11958 6.53939 8.25769 6.4365 8.42494L5.313 10.2467C5.2739 10.3109 5.24995 10.3832 5.24298 10.4581C5.23601 10.533 5.24619 10.6085 5.27277 10.6788C5.29934 10.7492 5.34159 10.8125 5.39632 10.8641C5.45106 10.9157 5.51682 10.9541 5.58862 10.9764L6.42749 11.2343C7.21428 11.4761 7.92992 11.9068 8.51195 12.4888C9.09348 13.0704 9.52398 13.7853 9.76591 14.5713L10.0249 15.4127C10.0466 15.4848 10.0846 15.5509 10.136 15.6059C10.1874 15.661 10.2507 15.7035 10.3211 15.7301C10.3916 15.7567 10.4672 15.7668 10.5421 15.7596C10.617 15.7523 10.6893 15.728 10.7533 15.6883L12.5764 14.5661C12.7434 14.4631 12.8812 14.319 12.9768 14.1476C13.0724 13.9762 13.1225 13.7832 13.1224 13.587V12.3556C13.1224 11.8005 13.373 11.2797 13.7655 10.8872L14.0674 10.5853C15.1448 9.50747 15.7501 8.04583 15.75 6.52181V6.39975C15.75 6.09482 15.6289 5.80237 15.4132 5.58675C15.1976 5.37113 14.9052 5.25 14.6002 5.25H14.4782ZM7.77525 14.6265C7.87697 14.3971 7.89287 14.1392 7.82253 13.8983C7.75219 13.6575 7.59884 13.4494 7.38967 13.3108C7.1805 13.1723 6.92903 13.1122 6.67983 13.1414C6.43064 13.1706 6.19982 13.2871 6.02831 13.4702C5.50987 13.9873 5.28806 15.1213 5.21456 15.5964C5.20992 15.622 5.21169 15.6484 5.21971 15.6731C5.22772 15.6978 5.24174 15.7202 5.2605 15.7382C5.2785 15.757 5.30086 15.771 5.3256 15.779C5.35033 15.787 5.37667 15.7888 5.40225 15.7841C5.87738 15.7106 7.01137 15.4888 7.5285 14.9717C7.6333 14.8745 7.71725 14.7571 7.77525 14.6265Z" fill="url(#paint0_linear_1542_3081)"/>
					<defs>
					  <linearGradient id="paint0_linear_1542_3081" x1="12.25" y1="-4.08334" x2="24.0158" y2="13.914" gradientUnits="userSpaceOnUse">
						<stop stop-color="#C66AFE"/>
						<stop offset="1" stop-color="#768EFC"/>
					  </linearGradient>
					</defs>
				  </svg></span><sapn class="evr-upgrade-icon-text">Upgrade Plan</span></div>', 'easy-video-reviews' ), $this->get_permissions(), admin_url( 'admin.php?page=easy-video-reviews-profile' ), '' );
				}
			} else {
				// Loads get started page if the user is NOT logged in EVR.
				add_menu_page( __( 'Get Started With Easy Video Reviews', 'easy-video-reviews' ), __( 'Easy Video Reviews', 'easy-video-reviews' ), $this->get_permissions(), 'easy-video-reviews', function () {
					echo "<div class='easy-video-reviews-wrapper' id='evr-onboarding'></div>";
				}, '', 99 );
			}
		}
	}

	// Instantiate.
	Menus::init();
}
