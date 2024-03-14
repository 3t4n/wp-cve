<?php
declare( strict_types=1 );

namespace WPDesk\ShopMagic\Customer\Interceptor;

use WPDesk\ShopMagic\Customer\Customer;
use WPDesk\ShopMagic\Customer\UserAsCustomer;
use WPDesk\ShopMagic\Exception\CannotProvideCustomerException;

/**
 * Can provide customer from registered user. This is used as fallback, when we cannot save data
 * to cookies. For this case we won't be able to provide any guest user dynamically.
 */
class RegisteredCustomerProvider implements \WPDesk\ShopMagic\Customer\CustomerProvider {

	public function get_customer(): Customer {
		if ( ! $this->is_customer_provided() ) {
			throw new CannotProvideCustomerException( 'No customer provided' );
		}

		return new UserAsCustomer( wp_get_current_user() );
	}

	public function is_customer_provided(): bool {
		return wp_get_current_user()->exists();
	}
}
