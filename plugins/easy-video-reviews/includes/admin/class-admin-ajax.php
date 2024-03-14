<?php

/**
 * Admin Ajax
 * Handles all ajax requests for admin area
 *
 * @since 1.1.0
 * @package EasyVideoReviews
 */

// Namespace.
namespace EasyVideoReviews\Admin;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit( 1 );


if ( ! class_exists( __NAMESPACE__ . '\Ajax' ) ) {
	/**
	 * Admin Ajax
	 *
	 * @since 1.1.0
	 */
	class Ajax extends \EasyVideoReviews\Base\Controller {

		// Use utilities trait.
		use \EasyVideoReviews\Traits\Utilities;

		/**
		 * Prefix for all ajax actions
		 *
		 * @var string
		 */
		protected $prefix = 'evr_';

		/**
		 * Registers all ajax hooks
		 *
		 * @return void
		 */
		public function register_hooks() {
			$actions = [
				'update_access_token'  => [ $this, 'update_access_token' ],
				'update_accessibility' => [ $this, 'update_accessibility' ],
				'get_settings'         => [ $this, 'get_settings' ],
				'update_settings'      => [ $this, 'update_settings' ],
				'reset_settings'       => [ $this, 'reset_settings' ],
				'hide_notices'         => [ $this, 'hide_notices' ],
				'get_pages'            => [ $this, 'get_pages' ],
				'user_auth_kay'        => [ $this, 'user_auth_kay' ],
			];

			foreach ( $actions as $action => $callback ) {
				add_action( 'wp_ajax_' . $this->prefix . $action, $callback );
			}
		}

		/**
		 * Updates access token
		 *
		 * @return void
		 */
		public function update_access_token() {
			check_ajax_referer( 'evr_admin_nonce', 'nonce');

			// Check permissions.
			if ( ! current_user_can( 'manage_options' ) ) {
				$this->io()->send_json( false, __( 'You do not have permission to do this', 'easy-video-reviews' ) );
			}

			$access_token = $this->io()->get_input('access_token', null); // sanitized access token.

			$this->option()->update( 'access_token', $access_token );

			$this->io()->send_json( true, __( 'Access token updated', 'easy-video-reviews' ) );
		}


		/**
		 * Updates accessibility
		 *
		 * @return void
		 */
		public function update_accessibility() {
			// Check nonce.
			check_ajax_referer( 'evr_admin_nonce', 'nonce');

			// Check permissions.
			if ( ! current_user_can( 'manage_options' ) ) {
				$this->io()->send_json( false, __( 'You do not have permission to do this', 'easy-video-reviews' ) );
			}

			$is_pro = wp_validate_boolean( $this->io()->get_input('is_pro', false) ); // sanitized is pro.

			$this->option()->update( 'is_pro', $is_pro );

			$this->io()->send_json( true, __( 'Accessibility updated', 'easy-video-reviews' ) );
		}

		/**
		 * Get user settings
		 *
		 * @return void
		 */
		public function get_settings() {
			// Check nonce.
			check_ajax_referer( 'evr_admin_nonce', 'nonce');

			// Check permissions.
			if ( ! current_user_can( 'manage_options' ) ) {
				$this->io()->send_json( false, __( 'You do not have permission to do this', 'easy-video-reviews' ) );
			}

			$options = $this->option()->get_all();

			$this->io()->send_json( true, __( 'Settings retrieved', 'easy-video-reviews' ), $options );
		}

		/**
		 * Updates user settings
		 *
		 * @return void
		 */
		public function update_settings() {
			// Check nonce.
			check_ajax_referer( 'evr_admin_nonce', 'nonce');

			// Check permissions.
			if ( ! current_user_can( 'manage_options' ) ) {
				$this->io()->send_json( false, __( 'You do not have permission to do this', 'easy-video-reviews' ) );
			}

			$default_options = $this->option()->get_defaults();
			$inputs          = $this->io()->get_inputs();

			foreach ( $default_options as $key => $default ) {

				if ( isset( $inputs[ $key ] ) ) {
					$new_value = $inputs[ $key ];
					$this->option()->update( $key, $new_value );
				}
			}

			$this->io()->send_json( true, __( 'Settings updated', 'easy-video-reviews' ) );
		}

		/**
		 * Reset user settings
		 *
		 * @return void
		 */
		public function reset_settings() {
			// Check nonce.
			check_ajax_referer( 'evr_admin_nonce', 'nonce');

			// Check permissions.
			if ( ! current_user_can( 'manage_options' ) ) {
				$this->io()->send_json( false, __( 'You do not have permission to do this', 'easy-video-reviews' ) );
			}

			// Reset settings.
			$this->option()->reset();

			$this->io()->send_json( true, __( 'Settings reset', 'easy-video-reviews' ) );
		}

		/**
		 * Get all pages
		 *
		 * @return void
		 */
		public function get_pages() {
			// Check nonce.
			$nonce = $this->io()->get_input('nonce', null);
			if ( ! $nonce || empty( $nonce ) || ! wp_verify_nonce( $nonce, 'evr_admin_nonce' ) ) {
				$this->io()->send_json( false, __( 'Invalid nonce', 'easy-video-reviews' ) );
			}

			// Check permissions.
			if ( ! current_user_can( 'manage_options' ) ) {
				$this->io()->send_json( false, __( 'You do not have permission to do this', 'easy-video-reviews' ) );
			}

			$pages = get_posts( [
				'post_type'      => 'page',
				'post_status'    => 'publish',
				'posts_per_page' => -1,
				'orderby'        => 'title',
				'order'          => 'ASC',
				'fields'         => [ 'ID', 'post_title', 'post_name' ],
			] );

			$pages = array_map( function ( $page ) {
				return [
					'value' => $page->ID,
					'label' => ! empty( $page->post_title ) ? $page->post_title : __( '( no title )', 'easy-video-reviews' ),
					'slug'  => $page->post_name,
				];
			}, $pages );

			$this->io()->send_json( true, __( 'Pages retrieved', 'easy-video-reviews' ), $pages );
		}

		/**
		 * Hide notices
		 */
		public function hide_notices() {
			// Check nonce.
			$nonce = $this->io()->get_input('nonce', null);
			if ( ! $nonce || empty( $nonce ) || ! wp_verify_nonce( $nonce, 'evr_admin_nonce' ) ) {
				$this->io()->send_json( false, __( 'Invalid nonce', 'easy-video-reviews' ) );
			}

			// Check permissions.
			if ( ! current_user_can( 'manage_options' ) ) {
				$this->io()->send_json( false, __( 'You do not have permission to do this', 'easy-video-reviews' ) );
			}

			$id        = $this->io()->get_input('id','');
			$transient = $this->io()->get_input('transient', 7); // sanitized transient.

			$key = wp_sprintf('show_ % s_notice', $id);

			if ( 'hide' === $transient ) {
				// Hide key.
				$this->option()->update( $key, 'hide' );
			} else {
				// set transient value 'hide' for transient * days in seconds.
				$this->option()->set_transient( $key, 'hide', $transient * DAY_IN_SECONDS );
			}

			$this->io()->send_json( true, __( 'Notice hidden', 'easy-video-reviews' ), [
				'key'       => $key,
				'transient' => $transient,
			] );
		}

		/**
		 * Get user auth keys
		 *
		 * @return void
		 */
		public function user_auth_kay() {
			// Check nonce.
			check_ajax_referer( 'evr_admin_nonce', 'nonce');

			// Check permissions.
			if ( ! current_user_can( 'manage_options' ) ) {
				$this->io()->send_json( false, __( 'You do not have permission to do this', 'easy-video-reviews' ) );
			}

			$security_key = [ 'secure_auth_key' => SECURE_AUTH_KEY ];

			$this->io()->send_json( true, __( 'check response for auth key', 'easy-video-reviews' ), $security_key );
		}
	}

	// Instantiate.
	Ajax::init();
}
