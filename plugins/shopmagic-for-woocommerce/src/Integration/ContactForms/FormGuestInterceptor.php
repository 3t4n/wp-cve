<?php
declare( strict_types=1 );

namespace WPDesk\ShopMagic\Integration\ContactForms;

use WPDesk\ShopMagic\Components\Database\Abstraction\DAO\ObjectPersister;
use WPDesk\ShopMagic\Components\Database\Abstraction\DAO\ObjectRepository;
use WPDesk\ShopMagic\Customer\Customer;
use WPDesk\ShopMagic\Customer\Guest\Guest;
use WPDesk\ShopMagic\Customer\Guest\GuestFactory;
use WPDesk\ShopMagic\Customer\Guest\Interceptor\GuestInterceptor;
use WPDesk\ShopMagic\Customer\Guest\Interceptor\InterceptionFailure;
use WPDesk\ShopMagic\Exception\ShopMagicException;

/**
 * Intercepts guests from unified contact form interface. Can be used to integrate such plugins
 * as Contact Form 7 or Gravity Forms.
 */
final class FormGuestInterceptor implements GuestInterceptor {

	/** @var ObjectRepository<Customer> */
	private $customer_repository;

	/** @var ObjectPersister<Guest> */
	private $persister;

	/** @var GuestFactory */
	private $factory;

	/**
	 * @param ObjectRepository<Customer> $customer_repository
	 * @param ObjectPersister<Guest>     $guest_manager
	 * @param GuestFactory               $guest_factory
	 */
	public function __construct(
		ObjectRepository $customer_repository,
		ObjectPersister $guest_manager,
		GuestFactory $guest_factory
	) {
		$this->customer_repository = $customer_repository;
		$this->persister           = $guest_manager;
		$this->factory             = $guest_factory;
	}

	public function intercept( object $provider ): Guest {
		if ( ! $provider instanceof FormEntry ) {
			throw new \InvalidArgumentException(
				sprintf(
					'Expected argument of type %s. Passed: %s',
					FormEntry::class,
					get_class( $provider )
				)
			);
		}

		try {
			$email = $provider->get_email();
		} catch ( ShopMagicException $e ) {
			throw InterceptionFailure::missing_email();
		}

		try {
			$customer = $this->customer_repository->find_one_by( [ 'email' => $email ] );
		} catch ( ShopMagicException $e ) {
			$customer = $this->factory->from_email( $email );
		}

		if ( ! $customer instanceof Guest ) {
			throw InterceptionFailure::intercepting_user();
		}

		$success = $this->persister->save( $customer );

		if ( $success ) {
			return $customer;
		}

		throw InterceptionFailure::saving_failure( $provider );
	}
}
