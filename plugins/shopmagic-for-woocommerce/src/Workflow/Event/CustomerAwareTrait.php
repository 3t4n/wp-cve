<?php
declare( strict_types=1 );

namespace WPDesk\ShopMagic\Workflow\Event;

use WPDesk\ShopMagic\Customer\CustomerRepository;

trait CustomerAwareTrait {

	/** @var CustomerRepository|null */
	protected $customer_repository;

	public function set_customer_repository( CustomerRepository $customer_repository ): void {
		$this->customer_repository = $customer_repository;
	}

}
