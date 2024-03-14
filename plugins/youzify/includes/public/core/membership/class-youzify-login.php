<?php

class Youzify_Membership_Login {

	/**
	 * Init Shortcode & Actions & Filters
	 */
	public function __construct() {

		// Add "[youzify_login]" Shortcode.
		add_shortcode( 'youzify_login_page', array( $this, 'get_login_form' ) );

        if ( apply_filters( 'youzify_enable_login_hooks', true ) ) {

			// Redirects.
			add_filter( 'authenticate', array( $this, 'maybe_redirect_at_authenticate' ), 101, 3 );
			add_filter( 'login_redirect', array( $this, 'redirect_after_login' ), 10, 3 );
			add_action( 'wp_login_failed', array( $this, 'login_failed' ), 9999 );

		}

	}

	/**
	 * Redirect on Login failed.
	 */
	function login_failed( $username ) {

		if ( wp_doing_ajax() ) {
			return false;
		}

		// Get Login Page Url.
		$login_url = youzify_membership_page_url( 'login' );

		// Get Redirect Url.
		if ( isset( $_REQUEST['redirect_to'] ) ) {
		    $login_url = add_query_arg( 'redirect_to', esc_url( $_REQUEST['redirect_to'] ), $login_url );
		}

		// Redirect User.
		wp_redirect( $login_url );
		exit;
	}

	/**
	 * Returns the URL to which the user should be redirected after the successful login.
	 */
	public function redirect_after_login( $redirect_to, $requested_redirect_to , $user ) {

		if ( $requested_redirect_to && $redirect_to == home_url() ) {
			$requested_redirect_to = false;
		}

		$requested_redirect_to = apply_filters( 'youzify_requested_redirect_to', $requested_redirect_to );

		// Use the redirect_to parameter if one is set, otherwise redirect to custom page.
		if ( ! $requested_redirect_to && isset( $user->ID ) ) {
			if ( user_can( $user, 'manage_options' ) ) {
				// Get Admin Redirect Page
				$admin_redirect_page = youzify_option( 'youzify_admin_after_login_redirect', 'dashboard' );
				$redirect_to = $this->get_redirect_page( $admin_redirect_page, $user->ID );
			} else {
				// Get User Redirect Page
				$user_redirect_page  = youzify_option( 'youzify_user_after_login_redirect', 'home' );
				$redirect_to = $this->get_redirect_page( $user_redirect_page, $user->ID );
			}
		}

		return $redirect_to;

	}

	/**
	 * Redirect the user after authentication if there were any errors.
	 */
	public function maybe_redirect_at_authenticate( $user, $username, $password ) {

		// Filters whether the given user can be authenticated with the provided $password.
		$user = apply_filters( 'wp_authenticate_user', $user, $password );

		if ( is_wp_error( $user ) && ! wp_doing_ajax() ) {

			// Get Errors
			$errors = youzify_membership_get_error_messages( $user->get_error_messages() );

			// Add Errors.
			youzify_membership_add_message( $errors );

		}

		return $user;
	}

	/**
	 * A shortcode for rendering the login form.
	 */
	public function get_login_form( $attributes = null ) {

		if ( is_user_logged_in() ) {
			return false;
		}

		global $Youzify_Membership;

		// Render the login form.
		return $Youzify_Membership->form->get_page( 'login', $attributes );

	}

	/**
	 * Attributes
	 */
	function attributes() {

		global $Youzify_Membership;

		// Get Attributes
		$attrs = $this->messages_attributes();

		// Add Form Type & Action to generate form class later.
		$attrs['form_type']   = 'login';
		$attrs['form_action'] = 'login';

		// Get Login Box Classes.
		$attrs['action_class'] = $this->get_actions_class();
		$attrs['form_class'] = $Youzify_Membership->form->get_form_class( $attrs );

		// Form Elements Visibilty Settings.
		$attrs['use_labels'] = ( false !== strpos( $attrs['form_class'], 'form-with-labels' ) ) ? true : false;
		$attrs['use_icons']	 = ( false !== strpos( $attrs['form_class'], 'form-fields-icon' ) ) ? true : false;

		// Form Actions Elements Visibilty Settings.
		$attrs['actions_lostpswd'] = ( false !== strpos( $attrs['action_class'], 'form-lost-pswd' ) ) ? true : false;
		$attrs['actions_icons']	= ( false !== strpos( $attrs['action_class'], 'form-buttons-icons' ) ) ? true : false;

		return $attrs;
	}

	/**
	 * Get Redirect Page Url
	 */
	function get_redirect_page( $page, $user_id = null ) {

		
		$token = get_user_meta( $user_id, 'google2fa_ts', true);
		$two_fa_login = get_user_meta( $user_id, 'youzify_enable_2fa_login', true);
		// If Page ID is numeric Return Page Url.
			if ( is_numeric( $page ) ) {

				// Get Page Url.
				$page_link = get_the_permalink( $page );

				// Return Page Link.
				if ( ! empty( $page_link ) ) {
					return $page_link;
				}

			}

			switch( $page ) {
				case 'home':
					$page_url = home_url( '/' );
					break;
				case 'dashboard':
					$page_url = admin_url();
					break;
				case 'profile':
					$page_url = bp_members_get_user_url( $user_id );
					break;
				default:
					$page_url = home_url( '/' );
				break;
			}

        return apply_filters( 'youzify_after_login_redirect_url', esc_url( $page_url ), $page, $user_id );
	}

}