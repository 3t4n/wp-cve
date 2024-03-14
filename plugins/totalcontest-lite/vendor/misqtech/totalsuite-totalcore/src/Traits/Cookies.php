<?php

namespace TotalContestVendors\TotalCore\Traits;


/**
 * Trait Cookies
 * @package TotalContestVendors\TotalCore\Traits
 */
trait Cookies {
	/**
	 * Set cookie.
	 *
	 * @param     $name
	 * @param     $value
	 * @param int $minutes
	 */
	public function setCookie( $name, $value, $minutes = 3600 ) {
		// 2147483647 for 2038.
		$cookieTimeoutTimestamp = ( $minutes === 0 ) ? 2147483647 : time() + ( MINUTE_IN_SECONDS * $minutes );
		if ( ! headers_sent() ):
			setcookie( $name, $value, $cookieTimeoutTimestamp, COOKIEPATH, COOKIE_DOMAIN );
		endif;
	}

	/**
	 * Get cookie.
	 *
	 * @param      $name
	 * @param null $default
	 *
	 * @return null
	 */
	public function getCookie( $name, $default = null ) {
		return isset( $_COOKIE[ $name ] ) ? $_COOKIE[ $name ] : $default;
	}

	/**
	 * Generate cookie name.
	 *
	 * @param $name
	 *
	 * @return string
	 */
	public function generateCookieName( $name ) {
		return \TotalContestVendors\TotalCore\Application::getInstance()->env( 'short-prefix' ) . md5( $name );
	}
}