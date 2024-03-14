<?php


namespace BigCommerce\Pages;

use BigCommerce\Shortcodes;

class Login_Page extends Required_Page {
	const NAME = 'bigcommerce_login_page_id';
	const SLUG = 'login';

	protected function get_title() {
		return _x( 'Sign-In', 'title of the login page', 'bigcommerce' );
	}

	public function get_slug() {
		return _x( self::SLUG, 'slug of the login page', 'bigcommerce' );
	}

	public function get_content() {
		return sprintf( '[%s]', Shortcodes\Login_Form::NAME );
	}

	public function get_post_state_label() {
		return __( 'Login Page', 'bigcommerce' );
	}

}
