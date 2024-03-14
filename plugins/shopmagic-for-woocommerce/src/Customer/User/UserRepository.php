<?php
declare( strict_types=1 );

namespace WPDesk\ShopMagic\Customer\User;

use WPDesk\ShopMagic\Components\Collections\ArrayCollection;
use WPDesk\ShopMagic\Components\Collections\Collection;
use WPDesk\ShopMagic\Components\Database\Abstraction\DAO\ObjectRepository;

/**
 * UserRepository is quite limited due to WP_User_Query design. Actually, you can query users
 * only by email. This is fine, unless you need more complex queries.
 * When necessary, this class should be refactorized to use raw SQL queries with better
 * flexibility. Until the time comes, it's just WP_User_Query wrapper.
 *
 * @implements ObjectRepository<\WP_User>
 */
class UserRepository implements ObjectRepository {
	const DEFAULT_LIMIT = 25;

	/** @var \WP_User_Query */
	private $query;

	public function __construct( \WP_User_Query $query ) {
		$this->query = $query;
	}

	public function find( $id ): ?object {
		$user = get_user_by( 'id', $id );

		if ( $user instanceof \WP_User ) {
			return $user;
		}

		return null;
	}

	public function find_all(): Collection {
		return $this->find_by( [] );
	}

	public function find_by( array $criteria, array $order = [], int $offset = 0, ?int $limit = null ): Collection {
		$query = [
			'number' => $limit ?? self::DEFAULT_LIMIT,
			'offset' => $offset,
		];

		if ( ! empty( $criteria ) ) {
			$query = array_merge(
				$query,
				[
					'search'         => array_values( $criteria )[0],
					'search_columns' => [ 'user_email' ],
				]
			);
		}

		$this->query->prepare_query( $query );
		$this->query->query();

		return new ArrayCollection( $this->query->get_results() );
	}

	public function find_one_by( array $criteria, ?array $order = null ): ?object {
		$query = [ 'number' => 1 ];

		if ( ! empty( $criteria ) ) {
			$query = array_merge(
				$query,
				[
					'search'         => array_values( $criteria )[0],
					'search_columns' => [ 'user_email' ],
				]
			);
		}

		$this->query->prepare_query( $query );
		$this->query->query();

		$results = $this->query->get_results();

		return $results[0] ?? null;
	}
}
