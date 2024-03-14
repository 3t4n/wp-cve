<?php

declare( strict_types=1 );

namespace WPDesk\ShopMagic\Customer;

use WPDesk\ShopMagic\Components\Collections\ArrayCollection;
use WPDesk\ShopMagic\Components\Collections\Collection;
use WPDesk\ShopMagic\Components\Database\Abstraction\DAO\ObjectRepository;
use WPDesk\ShopMagic\Components\Database\Abstraction\EntityNotFound;
use WPDesk\ShopMagic\Customer\Guest\Guest;
use WPDesk\ShopMagic\Exception\CustomerNotFound;

/**
 * @implements ObjectRepository<Customer>
 */
final class CustomerRepository implements ObjectRepository {

	/** @var ObjectRepository<Guest> */
	private $guest_repository;

	/** @var ObjectRepository<\WP_User> */
	private $user_repository;

	/**
	 * @param ObjectRepository<\WP_User> $user_repository
	 * @param ObjectRepository<Guest>    $guest_repository
	 */
	public function __construct(
		ObjectRepository $user_repository,
		ObjectRepository $guest_repository
	) {
		$this->user_repository  = $user_repository;
		$this->guest_repository = $guest_repository;
	}

	public function find_all(): Collection {
		return $this->find_by( [] );
	}

	/**
	 * Look for website customers. Users take higher precedence and guests will be used
	 * sparingly, i.e. if you define a query limit and that limit is filled by registered users,
	 * no guests will be queried.
	 */
	public function find_by( array $criteria, array $order = [], int $offset = 0, ?int $limit = null ): Collection {
		$users  = $this->user_repository->find_by( $criteria, $order, $offset, $limit );
		$result = [];
		foreach ( $users as $user ) {
			$result[] = new UserAsCustomer( $user );
		}

		if ( $limit && ( $limit - count( $users ) ) > 0 ) {
			foreach (
				$this->guest_repository->find_by(
					$criteria,
					$order,
					$offset,
					$limit - count( $users )
				) as $guest
			) {
				$result[] = $guest;
			}
		}

		return new ArrayCollection( $result );
	}

	public function find_by_email(
		#[\SensitiveParameter]
		string $email
	): Customer {
		try {
			return $this->find_one_by( [ 'email' => $email ] );
		} catch ( \Exception $e ) {
			throw new CustomerNotFound( 'Couldn\'t find matching email in sites\' users and guests.' );
		}
	}

	/**
	 * @param \WP_User|numeric $user
	 * @throws CustomerNotFound
	 */
	public function fetch_user( $user ): Customer {
		$wp_user = new \WP_User( $user );
		if ( $wp_user->exists() ) {
			return new UserAsCustomer( $wp_user );
		}

		throw new CustomerNotFound( sprintf( 'Failed to fetch user with ID `%d`.', $user instanceof \WP_User ? $user->ID : $user ) );
	}

	/**
	 * When searching for guest by ID we MUST pass string prefixed form, i.e. `g_1`. Searching
	 * by integer works only for registered customers and will bypass querying any guests.
	 *
	 * @param string|int $id ID in guest form `g_1` or integer
	 *
	 * @return Customer
	 * @throws CustomerNotFound
	 */
	public function find( $id ): object {
		if ( CustomerFactory::is_customer_guest_id( $id ) ) {
			try {
				return $this->guest_repository->find( $id );
			} catch ( EntityNotFound $e ) {
				throw CustomerNotFound::with_id( $id );
			}
		}

		$user = $this->user_repository->find( $id );
		if ( $user ) {
			return new UserAsCustomer( $user );
		}

		throw CustomerNotFound::with_id( $id );
	}

	public function find_one_by( array $criteria, ?array $order = null ): object {
		$user = $this->user_repository->find_one_by( $criteria, $order );

		if ( $user instanceof \WP_User ) {
			return new UserAsCustomer( $user );
		}

		$guest = $this->guest_repository->find_one_by( $criteria, $order );

		if ( $guest === null ) {
			throw new CustomerNotFound( 'Failed to find single customer matching criteria.' );
		}

		return $guest;
	}
}
