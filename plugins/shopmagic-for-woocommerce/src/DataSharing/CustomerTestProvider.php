<?php

declare( strict_types=1 );

namespace WPDesk\ShopMagic\DataSharing;

use WPDesk\ShopMagic\Customer\Customer;
use WPDesk\ShopMagic\Customer\CustomerRepository;
use WPDesk\ShopMagic\Customer\NullCustomer;
use WPDesk\ShopMagic\Exception\CustomerNotFound;
use WPDesk\ShopMagic\Workflow\Event\DataLayer;

class CustomerTestProvider implements DataProvider {

	/** @var CustomerRepository */
	private $repository;

	public function __construct( CustomerRepository $repository ) {
		$this->repository = $repository;
	}

	public function get_provided_data_domains(): array {
		return [ Customer::class ];
	}


	public function get_provided_data(): DataLayer {
		return new DataLayer( [ Customer::class => $this->get_customer() ] );
	}

	private function get_customer(): Customer {
		try {
			return $this->repository->find_one_by( [] );
		} catch ( CustomerNotFound $e ) {
			return new NullCustomer();
		}
	}
}
