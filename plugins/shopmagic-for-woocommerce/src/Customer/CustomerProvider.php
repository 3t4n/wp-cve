<?php
declare(strict_types=1);

namespace WPDesk\ShopMagic\Customer;

use WPDesk\ShopMagic\Exception\CannotProvideCustomerException;

interface CustomerProvider {

	/**
	 * @throws CannotProvideCustomerException;
	 */
	public function get_customer(): Customer;

	public function is_customer_provided(): bool;
}
