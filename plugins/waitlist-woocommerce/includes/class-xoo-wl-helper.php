<?php

class Xoo_Wl_Helper extends Xoo_Helper{

	protected static $_instance = null;

	public static function get_instance( $slug, $path ){
		if ( is_null( self::$_instance ) ) {

			self::$_instance = new self( $slug, $path );

			self::$_instance->capability = 'administrator';

		}
		return self::$_instance;
	}

	public function get_general_option( $subkey = '' ){
		return $this->get_option( 'xoo-wl-general-options', $subkey );
	}

	public function get_style_option( $subkey = '' ){
		return $this->get_option( 'xoo-wl-style-options', $subkey );
	}

	public function get_email_option( $subkey = '' ){
		return $this->get_option( 'xoo-wl-email-options', $subkey );
	}

}

function xoo_wl_helper(){
	return Xoo_Wl_Helper::get_instance( 'waitlist-woocommerce', XOO_WL_PATH );
}
xoo_wl_helper();

?>