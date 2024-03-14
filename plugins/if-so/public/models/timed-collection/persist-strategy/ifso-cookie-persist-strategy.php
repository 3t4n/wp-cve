<?php

/**
 * 
 * 
 *
 * @author Matan Green <matangrn@gmail.com>
 */

if (!class_exists('IfSo_CookiePersistStrategy')) {

	require_once('ifso-persist-strategy.php');

	class IfSo_CookiePersistStrategy extends IfSo_PersistStrategy {

		protected $cookie_name;

		public function __construct( $cookie_name ) {
			$this->cookie_name = $cookie_name;
		}

		public function get_items() {
			$items = null;

			if ( isset( $_COOKIE[$this->cookie_name] ) )
				$items = json_decode( 
					stripslashes( $_COOKIE[$this->cookie_name] ),
					true );
			else {
				$items = array();
			}

			return $items;
		}

		public function persist( $items ) {
			$encoded_items = json_encode( $items, JSON_UNESCAPED_UNICODE );
            \IfSo\PublicFace\Helpers\CookieConsent::get_instance()->set_cookie($this->cookie_name,
                $encoded_items,
                2147483647,
                '/');
		}
	}

}