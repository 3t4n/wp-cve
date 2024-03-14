<?php

namespace Codemanas\InactiveLogout;

/**
 * All ajax calls are kept here.
 *
 * Class Ajax
 * @package Codemanas\InactiveLogout
 */
class Ajax {

	public function __construct() {
		//Acutually Logging out here
		add_action( 'wp_ajax_ina_logout_session', array( $this, 'logoutSession' ) );

		// Ajax for resetting.
		add_action( 'wp_ajax_ina_reset_adv_settings', array( $this, 'ina_reset_adv_settings' ) );

		//Ajax for dismissal of like notice
		add_action( 'wp_ajax_ina_dismiss_like_notice', [ $this, 'dismissLikeNotice' ] );

		add_action( 'wp_ajax_ina_get_pages_for_redirection', [ $this, 'getPostsAndPages' ] );
	}

	/**
	 * Get list of posts based on search
	 *
	 * @return void
	 */
	public function getPostsAndPages() {
		$q = filter_input( INPUT_GET, 'q' );

		$args = [
			's'           => $q,
			'post_type'   => apply_filters( 'ina_free_get_custom_post_types', array( 'post', 'page' ) ),
			'post_status' => 'publish',
		];
		// The Query
		$posts_query = new \WP_Query( $args );

		$posts = [];
		if ( ! empty( $posts_query->have_posts() ) ) {
			foreach ( $posts_query->get_posts() as $post ) {
				$posts[] = [ 'text' => get_permalink( $post->ID ), 'id' => get_permalink( $post->ID ) ];
			}
		}

		wp_send_json( $posts );

		wp_die();
	}

	/**
	 * Reset Advanced Settings
	 *
	 * @since  1.3.0
	 * @author Deepen
	 */
	public function ina_reset_adv_settings() {
		if ( ! current_user_can( 'manage_options' ) ) {
			exit;
		}

		check_ajax_referer( '_ina_security_nonce', 'security' );

		delete_option( '__ina_roles' );
		delete_option( '__ina_enable_timeout_multiusers' );
		delete_option( '__ina_multiusers_settings' );

		if ( wp_doing_ajax() && get_current_blog_id() == get_main_network_id() && is_multisite() ) {
			delete_site_option( '__ina_roles' );
			delete_site_option( '__ina_enable_timeout_multiusers' );
			delete_site_option( '__ina_multiusers_settings' );
		}

		Helpers::update_option( '__ina_saved_options', __( 'Role based settings reset.', 'inactive-logout' ) );

		wp_send_json( array(
			'code' => 1,
			'msg'  => esc_html__( 'Reset advanced settings successful.', 'inactive-logout' ),
		) );

		wp_die();
	}

	/**
	 * Logout the actual session from here
	 *
	 * @since 3.0.0
	 * @author Deepen
	 */
	public function logoutSession() {
		check_ajax_referer( '_inaajax', 'security' );

		//Logout Nows
		if ( is_user_logged_in() ) {
			wp_logout();
		}

		wp_send_json( array(
			'isLoggedIn' => is_user_logged_in()
		) );

		wp_die();
	}

	public function dismissLikeNotice() {
		Helpers::update_option( 'ina_dismiss_like_notice', true );
	}

	private static $_instance = null;

	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}
}