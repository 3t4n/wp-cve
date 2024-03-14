<?php

declare( strict_types=1 );

namespace WPDesk\ShopMagic\Integration\ContactForms;

use ShopMagicVendor\WPDesk\PluginBuilder\Plugin\Hookable;
use WPDesk\ShopMagic\Customer\Customer;
use WPDesk\ShopMagic\Customer\CustomerProvider;
use WPDesk\ShopMagic\Customer\CustomerRepository;
use WPDesk\ShopMagic\Exception\CannotProvideCustomerException;
use WPDesk\ShopMagic\Exception\CustomerNotFound;
use WPDesk\ShopMagic\Exception\FieldNotFound;
use WPDesk\ShopMagic\Guest\GuestFactory;
use WPDesk\ShopMagic\Guest\GuestRepository;

/**
 * @deprecated 3.0.9 Use FormGuestInterceptor as service required to persist guest.
 * @codeCoverageIgnore
 */
abstract class Interceptor implements CustomerProvider, Hookable {

	/** @var Customer */
	protected $customer;

	/** @var FormEntry */
	protected $form;

	/** @var GuestRepository */
	protected $guest_repository;

	/** @var GuestFactory */
	protected $guest_factory;

	/** @var CustomerRepository */
	private $customer_repository;

	final public function __construct(
		CustomerRepository $customer_repository,
		GuestRepository $guest_repository,
		GuestFactory $guest_factory,
		$deprecated = null
	) {
		$this->customer_repository = $customer_repository;
		$this->guest_repository    = $guest_repository;
		$this->guest_factory       = $guest_factory;
	}

	final public function is_customer_provided(): bool {
		try {
			return $this->get_customer() instanceof Customer;
		} catch ( \Throwable $e ) {
			return false;
		}
	}

	final public function get_customer(): Customer {
		if ( ! $this->form instanceof FormEntry ) {
			throw new \LogicException(
				sprintf(
					'%s::$form needs to be the type of %s. %s given',
					self::class,
					FormEntry::class,
					\is_object( $this->form ) ? \get_class( $this->form ) : \gettype( $this->form )
				)
			);
		}

		try {
			$email = $this->form->get_email();
		} catch ( FieldNotFound $e ) {
			throw new CannotProvideCustomerException( "Form entry misses email field, then it's impossible to search for customer." );
		}

		try {
			return $this->customer_repository->find_by_email( $email );
		} catch ( CustomerNotFound $e ) {
			$guest = $this->guest_factory->from_email( $email );

			return $guest;
//			$this->guest_repository->save( $guest );
		}
	}
}
