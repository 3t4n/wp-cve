<?php
declare( strict_types=1 );

namespace WPDesk\ShopMagic\Workflow\Event;

use WPDesk\ShopMagic\Customer\CustomerRepository;

/**
 * Many events needs access to Customer object.
 * With this interface we can inject Customer Factory object into event, so we can get Customer object.
 */
interface CustomerAwareInterface {

	public function set_customer_repository( CustomerRepository $customer_repository ): void;

}
