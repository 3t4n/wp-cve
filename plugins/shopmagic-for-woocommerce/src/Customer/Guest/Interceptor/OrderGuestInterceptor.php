<?php
declare( strict_types=1 );

namespace WPDesk\ShopMagic\Customer\Guest\Interceptor;

use WPDesk\ShopMagic\Components\Database\Abstraction\DAO\ObjectPersister;
use WPDesk\ShopMagic\Components\Database\Abstraction\DAO\ObjectRepository;
use WPDesk\ShopMagic\Customer\Guest\Guest;
use WPDesk\ShopMagic\Customer\Guest\GuestFactory;

class OrderGuestInterceptor implements GuestInterceptor {

	/** @var ObjectPersister<Guest> */
	private $persister;

	/** @var ObjectRepository<Guest> */
	private $repository;

	/** @var GuestFactory */
	private $factory;

	/**
	 * @param ObjectPersister<Guest> $persister
	 * @param GuestFactory           $factory
	 */
	public function __construct(
		ObjectPersister $persister,
		GuestFactory $factory
	) {
		$this->persister  = $persister;
		$this->repository = $persister->get_repository();
		$this->factory    = $factory;
	}

	public function intercept( object $provider ): Guest {
		if ( ! $provider instanceof \WC_Order ) {
			throw new \InvalidArgumentException( sprintf( 'Expected argument of type %s. Passed: %s',
				\WC_Order::class,
				get_class( $provider )
			) );
		}

		if ( $provider->get_user_id() !== 0 ) {
			throw InterceptionFailure::intercepting_user();
		}

		if ( empty( $provider->get_billing_email() ) ) {
			throw InterceptionFailure::missing_email();
		}

		try {
			$guest = $this->repository->find_one_by(
				[ 'email' => $provider->get_billing_email() ]
			);
		} catch ( \Exception $e ) {
			$guest = null;
		}

		$updated_guest = $this->factory->from_order( $provider, $guest );
		$success       = $this->persister->save( $updated_guest );

		if ( $success ) {
			return $updated_guest;
		}

		throw InterceptionFailure::saving_failure( $provider );
	}
}
