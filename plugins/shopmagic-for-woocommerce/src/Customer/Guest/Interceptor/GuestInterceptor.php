<?php
declare( strict_types=1 );

namespace WPDesk\ShopMagic\Customer\Guest\Interceptor;

use WPDesk\ShopMagic\Customer\Guest\Guest;

/**
 * Intercept guest based on parameter passes as interception argument.
 */
interface GuestInterceptor {

	/**
	 * @param object $provider
	 *
	 * @return Guest
	 * @throws InterceptionFailure
	 * @throws \InvalidArgumentException When provider doesn't match implementation
	 */
	public function intercept( object $provider ): Guest;

}
