<?php
declare( strict_types=1 );

namespace WPDesk\ShopMagic\Customer\Guest\Interceptor;

use WPDesk\ShopMagic\Components\Database\Abstraction\DAO\ObjectPersister;
use WPDesk\ShopMagic\Components\Database\Abstraction\DAO\ObjectRepository;
use WPDesk\ShopMagic\Customer\Guest\Guest;
use WPDesk\ShopMagic\Customer\Guest\GuestFactory;

class CommentGuestInterceptor implements GuestInterceptor {

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
		if ( ! $provider instanceof \WP_Comment ) {
			throw new \InvalidArgumentException(
				sprintf(
					esc_html__( 'Interceptor expected argument of `WP_Comment` class. `%s` passed', 'shopmagic-for-woocommerce' ),
					get_class( $provider )
				)
			);
		}

		if ( $provider->user_id !== 0 ) {
			throw InterceptionFailure::intercepting_user();
		}

		if ( empty( $provider->comment_author_email ) ) {
			throw InterceptionFailure::missing_email();
		}

		try {
			return $this->repository->find_one_by( [ 'email' => $provider->comment_author_email ] );
		} catch ( \Exception $e ) {
			// If we have found guest, terminate in try block.
		}

		$guest   = $this->factory->from_email( $provider->comment_author_email );
		$success = $this->persister->save( $guest );

		if ( $success ) {
			return $guest;
		}

		throw InterceptionFailure::saving_failure( $provider );
	}
}
