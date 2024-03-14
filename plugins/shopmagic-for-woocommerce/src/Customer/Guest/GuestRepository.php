<?php

declare( strict_types=1 );

namespace WPDesk\ShopMagic\Customer\Guest;

use WPDesk\ShopMagic\Components\Collections\Collection;
use WPDesk\ShopMagic\Components\Database\Abstraction\DAO\ObjectDehydrator;
use WPDesk\ShopMagic\Components\Database\Abstraction\ObjectRepository;
use WPDesk\ShopMagic\Customer\CustomerFactory;
use WPDesk\ShopMagic\Database\DatabaseTable;

/**
 * @extends ObjectRepository<Guest>
 */
class GuestRepository extends ObjectRepository {

	/** @var GuestMetaRepository */
	private $meta_repository;

	public function __construct(
		GuestMetaRepository $meta_repository,
		ObjectDehydrator $denormalizer,
		?\wpdb $wpdb = null
	) {
		$this->meta_repository = $meta_repository;
		parent::__construct( $denormalizer, $wpdb );
	}

	/**
	 * @param array      $criteria
	 * @param array|null $order
	 *
	 * @return Guest
	 */
	public function find_one_by( array $criteria, ?array $order = null ): object {
		/** @var Guest $guest */
		$guest = parent::find_one_by( $criteria, $order );
		$guest->set_meta( $this->meta_repository->find_by( [ 'guest_id' => $guest->get_raw_id() ] ) );

		return $guest;
	}

	public function find_by( array $criteria, array $order = [], int $offset = 0, ?int $limit = null ): Collection {
		$guest_collection = parent::find_by( $criteria, $order, $offset, $limit );

		return $guest_collection->map( function ( Guest $guest ) {
			$guest->set_meta(
				$this->meta_repository->find_by(
					[ 'guest_id' => $guest->get_raw_id() ]
				)
			);

			return $guest;
		} );
	}

	/**
	 * @param string|int $id Raw ID or `g_` prefixed ID
	 *
	 * @return object
	 */
	public function find( $id ): object {
		/** @var Guest $guest */
		$guest = parent::find( CustomerFactory::convert_customer_guest_id_to_number( $id ) );
		$guest->set_meta( $this->meta_repository->find_by( [ 'guest_id' => $guest->get_raw_id() ] ) );

		return $guest;
	}

	protected function get_name(): string {
		return DatabaseTable::guest();
	}
}
