<?php

/**
 * Admin assets class
 * Registers all admin assets and scripts
 *
 * @since 1.1.0
 * @package EasyVideoReviews
 */

// Namespace.
namespace EasyVideoReviews\Admin;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit( 1 );

if ( ! class_exists( __NAMESPACE__ . '\Assets' ) ) {

	/**
	 * Admin Hooks
	 *
	 * @since 1.1.0
	 */
	class Assets extends \EasyVideoReviews\Base\Controller {

		// Use utilities trait.
		use \EasyVideoReviews\Traits\Utilities;

		/**
		 * Registers all admin hooks
		 *
		 * @return void
		 */
		public function register_hooks() {
			add_action( 'admin_enqueue_scripts', [ $this, 'admin_enqueue_scripts' ] );

			add_action( 'admin_footer', [ $this, 'admin_footer' ] );
		}



		/**
		 * Enqueues admin scripts
		 *
		 * @param string $hook Hook.
		 * @return void
		 */
		public function admin_enqueue_scripts( $hook ) {
			$current_screen  = get_current_screen();
			$is_block_editor = method_exists( $current_screen, 'is_block_editor' ) && $current_screen->is_block_editor();

			$current_page = '';

			try {
				$current_page = explode( '-', $hook );
				$current_page = end( $current_page );
			} catch ( \Exception $e ) {
				$current_page = '';
			}

			$all_pages = array_map(
				function ( $page ) {
					// Return ID as value, post_title as label and post_name as slug.
					return [
						'value' => $page->ID,
						'label' => $page->post_title,
						'slug'  => $page->post_name,
					];
				},
				get_pages()
			);

			$translation         = new \EasyVideoReviews\Translation();
			$admin_footer_script = [
				'ajax_url'     => admin_url( 'admin-ajax.php' ),
				'server_url'   => $this->client()->get_server(),
				'plugin_url'   => EASY_VIDEO_REVIEWS_URL,
				'home_url'     => home_url(),
				'host'         => $this->client()->get_host(),
				'nonce'        => wp_create_nonce( 'evr_admin_nonce' ),
				'current_page' => $current_page,
				'folders'      => $this->client()->get_access_token() ? $this->client()->folders() : [],
				'is_user_logogin' => $this->option()->get('is_user_logedin', false),
				'google_client_url' => '',
				'pages'        => $all_pages,
				'galleries'    => $this->option()->get('gallaries', false),
				'create_gallery_page_url' => get_site_url() . '/wp-admin/admin.php?page=easy-video-reviews-gallery',
				'extensions'   => apply_filters( 'evr_extensions', [] ),
				'is_debug'     => defined( 'WP_DEBUG' ) && WP_DEBUG,
				'preference'   => $this->option()->get_all(),
				'translations' => $translation->get_all(),
			];
			// Conditional elements.
			if ( $this->client()->has_valid_token() ) {
				$admin_footer_script['access_token']     = $this->client()->get_access_token();
				$admin_footer_script['is_wc_installed']  = $this->is_wc_installed();
				$admin_footer_script['is_wc_active']     = $this->is_wc_active();
				$admin_footer_script['is_edd_installed'] = $this->is_edd_installed();
				$admin_footer_script['is_edd_active']    = $this->is_edd_active();
			} else {
				$admin_footer_script['prefill'] = [
					'email'      => get_option( 'admin_email', '' ),
					'first_name' => get_user_meta( get_current_user_id(), 'first_name', true ),
					'last_name'  => get_user_meta( get_current_user_id(), 'last_name', true ),
				];
			}

			$admin_footer_script = apply_filters( 'evr_admin_localize_script', $admin_footer_script );

			wp_register_script( '_evr_inline', '', [], EASY_VIDEO_REVIEWS_VERSION, true );
			wp_localize_script( '_evr_inline', '_evr_admin', $admin_footer_script );
			wp_enqueue_script( '_evr_inline' );

			wp_enqueue_script( 'sizzle', EASY_VIDEO_REVIEWS_PUBLIC . 'js/sizzle.min.js', [ 'jquery' ], EASY_VIDEO_REVIEWS_VERSION, true );
			wp_enqueue_script( 'evr-events', EASY_VIDEO_REVIEWS_PUBLIC . 'js/events.min.js', [ 'jquery' ], EASY_VIDEO_REVIEWS_VERSION, true );
			wp_enqueue_style( 'evr-notice', EASY_VIDEO_REVIEWS_PUBLIC . 'css/notice.css', [], EASY_VIDEO_REVIEWS_VERSION );

			if ( $is_block_editor || in_array( $hook, [
				'toplevel_page_easy-video-reviews',
				'easy-video-reviews_page_easy-video-reviews-settings',
				'easy-video-reviews_page_easy-video-reviews-integrations',
				'easy-video-reviews_page_easy-video-reviews-profile',
				'easy-video-reviews_page_easy-video-reviews-gallery',
				'easy-video-reviews_page_easy-video-reviews-utility',
				'easy-video-reviews_page_easy-video-reviews-documentation',
			] ) ) {

				wp_enqueue_style( 'evr-admin', EASY_VIDEO_REVIEWS_PUBLIC . 'css/admin.min.css', [], EASY_VIDEO_REVIEWS_VERSION );
				wp_enqueue_script( 'evr-admin', EASY_VIDEO_REVIEWS_PUBLIC . 'js/admin.min.js', [ 'jquery' ], EASY_VIDEO_REVIEWS_VERSION, true );
				wp_enqueue_editor();
			}

			// Add custom styles.
			$custom_styles = '
			[href="admin.php?page=easy-video-reviews"] .dashicons-before:before {
				content: "" !important;
				background: url("' . EASY_VIDEO_REVIEWS_PUBLIC . 'images/evr.svg") no-repeat !important;
				background-size: 21px 21px !important;
				background-position: center center !important;
				width: 27px !important;
				height: 32px !important;
				padding: 8px 0 0 0 !important;
				margin: auto !important;
			}
			.evr-upgrade-plan{
				display: flex;
				align-items: center;
			}
			.evr-upgrade-icon{
				margin-right: 5px;
			}
			.evr-upgrade-icon-text{
				background: linear-gradient(92deg, #E1C1FB 5%, #C5CCFF 99.42%);
				font-size: 16px;
				font-weight: 700;
				background-clip: text;
				-webkit-background-clip: text;
				-webkit-text-fill-color: transparent;
			}
			';

			wp_enqueue_style( 'evr-admin-custom', EASY_VIDEO_REVIEWS_PUBLIC . 'css/blank.css', [], EASY_VIDEO_REVIEWS_VERSION );
			wp_add_inline_style( 'evr-admin-custom', $custom_styles );
		}




		/**
		 * Returns the allowed HTML tags for kses.
		 *
		 * @param array  $allowed Allowed HTML tags and attributes.
		 * @param string $context Context to judge allowed tags by. Allowed values are 'post'.
		 * @return array
		 */
		public function kses_filter_allowed_html( $allowed, $context ) {
			if ( is_array( $context ) ) {
				return $allowed;
			}

			if ( 'post' === $context ) {
				$allowed['a']['data-*']     = true;
				$allowed['table']['data-*'] = true;
			}

			return $allowed;
		}



		/**
		 * Add admin footer
		 *
		 * @return void
		 */
		public function admin_footer() {
			$this->render_template( 'admin/other/admin-footer' );
		}
	}

	// Instantiate.
	Assets::init();
}
