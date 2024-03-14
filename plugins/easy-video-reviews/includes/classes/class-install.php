<?php
/**
 * Handles plugin activation and deactivation.
 *
 * @since 1.3.0
 * @package EasyVideoReviews
 */

// Namespace.
namespace EasyVideoReviews;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit( 1 );

if ( ! class_exists( __NAMESPACE__ . '\Install' ) ) {

	/**
	 * Class Adjustment
	 */
	class Install extends \EasyVideoReviews\Base\Controller {

		// Use helper traits.
		use \EasyVideoReviews\Traits\Utilities;

		/**
		 * Register hooks
		 *
		 * @return void
		 */
		public function register_hooks() {
			// Plugin activation.
			register_activation_hook( EASY_VIDEO_REVIEWS_FILE, [ $this, 'activate' ] );

			// Plugin deactivation.
			register_deactivation_hook( EASY_VIDEO_REVIEWS_FILE, [ $this, 'deactivate' ] );

			// redirect to admin page on activation this plugin.
			add_action( 'admin_init', [ $this, 'redirect_to_admin_page' ] );

			// plugin action links.
			add_action( 'plugin_action_links_' . plugin_basename( EASY_VIDEO_REVIEWS_FILE ), [ $this, 'plugin_action_links' ] );

			// author uri.
			add_filter( 'plugin_row_meta', [ $this, 'plugin_row_meta' ], 10, 2 );
		}

		/**
		 * Calls on plugin activation
		 *
		 * @return void
		 */
		public function activate() {
			// Init options.
			$this->init_options();

			// Init recording page.
			$this->init_recording_page();
		}

		/**
		 * Calls on plugin deactivation
		 *
		 * @return void
		 */
		public function deactivate() {
			// Removed redirect option.
			$this->option()->delete( 'redirected' );
		}

		/**
		 * Init options
		 */
		public function init_options() {
			$default_options = $this->option()->get_defaults();

			foreach ( $default_options as $key => $value ) {
				add_option( $this->option()->prefix . $key, $value );
			}
		}

		/**
		 * Init recording page
		 */
		public function init_recording_page() {

			if ( ! $this->is_wc_active() ) {
				// Check if recording page is already created.
				$recording_page_id = $this->option()->get( 'recording_page_id', false );

				// Creates recording page.
				$recording_page_id = wp_insert_post( [
					'post_title'   => esc_html__( 'Leave a Review', 'easy-video-reviews' ),
					'post_content' => '[recorder]Leave a Review[/recorder]',
					'post_status'  => 'publish',
					'post_type'    => 'page',
				] );

				// update recording page id.
				$this->option()->update( 'recording_page_id', $recording_page_id );
			}

			// check if review page id is already created.
			$review_page_id = $this->option()->get( 'review_page_id', false );

			if ( ! $review_page_id ) {
				// create review page.
				$review_page_id = wp_insert_post( [
					'post_title'   => esc_html__( 'Video Review', 'easy-video-reviews' ),
					'post_content' => 'This will NOT be displayed. This is just a placeholder page.',
					'post_status'  => 'publish',
					'post_type'    => 'page',
				] );

				// Updates review page id.
				$this->option()->update( 'review_page_id', $review_page_id );
			}
		}

		/**
		 * Redirects user to admin page right after plugin activation
		 *
		 * @return void
		 */
		public function redirect_to_admin_page() {
			$redirected_after_activation = $this->option()->get( 'redirected', false );

			if ( ! $redirected_after_activation ) {
				// Updates option.
				$this->option()->update( 'redirected', 1 );

				// Redirects to admin page.
				wp_safe_redirect( admin_url( 'admin.php?page=easy-video-reviews' ) );
				exit;
			}
		}

		/**
		 * Plugin action links
		 *
		 * @param array $links Plugin action links.
		 * @return array
		 */
		public function plugin_action_links( $links ) {

			if ( $this->client()->has_valid_token() ) {
				$reviews_link = wp_sprintf('<a href="%s">%s</a>', esc_url( add_query_arg( 'page', 'easy-video-reviews', get_admin_url() . 'admin.php' ) ), esc_html__( 'Reviews', 'easy-video-reviews' ));
				$setting_link = wp_sprintf('<a href="%s">%s</a>', esc_url( add_query_arg( 'page', 'easy-video-reviews-settings', get_admin_url() . 'admin.php' ) ), esc_html__( 'Settings', 'easy-video-reviews' ));

				array_unshift( $links, wp_kses_post( $setting_link ) );
				array_unshift( $links, wp_kses_post( $reviews_link ) );
			}

			return $links;
		}

		/**
		 * Plugin row meta links
		 *
		 * @param array  $links Plugin row meta links.
		 * @param string $file Plugin file.
		 * @return array
		 */
		public function plugin_row_meta( $links, $file ) {
			if ( plugin_basename( EASY_VIDEO_REVIEWS_FILE ) === $file ) {
				$row_meta = [
					'docs' => wp_sprintf( '<a href="%s" target="_blank">%s</a>', esc_url( 'https://wppool.dev/docs/' ), esc_html__( 'Docs', 'easy-video-reviews' ) ),
				];

				if ( $this->client()->has_valid_token() && ! $this->client()->has_premium_access() ) {
					$row_meta['upgrade'] = '<a style="color: #e74c3c; font-weight: 500" href="' . esc_url( admin_url( 'admin.php?page=easy-video-reviews-billing#plans' ) ) . '">' . __( 'Upgrade', 'easy-video-reviews' ) . '</a>';
				}

				return array_merge( $links, $row_meta );
			}

			return $links;
		}
	}

	// Instantiate the class.
	Install::init();
}
