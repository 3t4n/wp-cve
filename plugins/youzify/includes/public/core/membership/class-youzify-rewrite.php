<?php

class Youzify_Membership_Rewrite {

	function __construct() {
		// Redirect User to Login Page
		add_action( 'template_redirect', array( $this, 'redirect_to_login_page' ) );
	}

	/**
	 * Redirect Users to login Page.
	 */
	function redirect_to_login_page() {

		if ( ! is_user_logged_in() ) {

			// Get Login Page Url
			$login_page = youzify_membership_page_url( 'login' );

			// if the page is register & Registration is disabled, redirect user to the login page.
			$registration_enabled = get_option( 'users_can_register' );

			if ( ! $registration_enabled && youzify_is_membership_page( 'register' ) ) {
				wp_safe_redirect( $login_page );
				exit;
			}

			if ( youzify_is_membership_page( 'complete-registration' ) && ! youzify_is_registration_incomplete() ) {
				wp_safe_redirect( $login_page );
				exit;
			}
		}

	}

}

new Youzify_Membership_Rewrite();