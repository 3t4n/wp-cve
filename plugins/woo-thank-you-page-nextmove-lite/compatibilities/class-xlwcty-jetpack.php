<?php
defined( 'ABSPATH' ) || exit;

class XLWCTY_Jetpack {
	private static $ins = null;

	public function __construct() {
		add_filter( 'grunion_contact_form_form_action', array( $this, 'change_contact_form_url' ), 10, 3 );
	}

	public static function get_instance() {
		if ( self::$ins == null ) {
			self::$ins = new self;
		}

		return self::$ins;
	}

	public function actions() {

	}

	public function change_contact_form_url( $url, $post, $id ) {
		if ( $post instanceof WP_Post && $post->post_type === 'xlwcty_thankyou' ) {
			$parts    = explode( "#contact-form-{$id}", $url );
			$mail_url = $parts[0];
			if ( isset( $_GET['wlmdebug'] ) ) {
				unset( $_GET['wlmdebug'] );
			}
			$url = add_query_arg( $_GET, $mail_url );
			$url .= "#contact-form-{$id}";
		}

		return $url;

	}

}

XLWCTY_Jetpack::get_instance();
