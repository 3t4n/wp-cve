<?php
declare( strict_types=1 );

namespace WPDesk\ShopMagic\Customer\Guest;

use WPDesk\ShopMagic\Components\Collections\ArrayCollection;
use WPDesk\ShopMagic\Components\Collections\Collection;
use WPDesk\ShopMagic\Components\Database\Abstraction\DAO\ObjectPersister;
use WPDesk\ShopMagic\Components\Database\Abstraction\DAO\ObjectRepository;
use WPDesk\ShopMagic\Components\Database\Abstraction\EntityNotFound;
use WPDesk\ShopMagic\Customer\CustomerFactory;
use WPDesk\ShopMagic\Database\DatabaseTable;

/**
 * @implements ObjectRepository<Guest>
 * @implements ObjectPersister<Guest>
 */
class GuestDataAccess implements ObjectRepository, ObjectPersister {

	/** @var \wpdb */
	private $wpdb;

	/** @var array<string, Guest> */
	private $identity_map = [];

	public function __construct( \wpdb $wpdb ) {
		$this->wpdb = $wpdb;
	}

	/**
	 * Find single guest from database by id.
	 */
	public function find( $id ): object {
		$id = CustomerFactory::convert_customer_guest_id_to_number( $id );

		if ( isset( $this->identity_map[ $id ] ) ) {
			return $this->identity_map[ $id ];
		}

		$guest_data = $this->wpdb->get_row(
			$this->wpdb->prepare(
				$this->select_sql() . ' WHERE id = %d LIMIT 1',
				$id
			),
			ARRAY_A
		);

		if ( ! $guest_data ) {
			throw EntityNotFound::with_id( $id );
		}

		$guest = $this->create_guest_from_data( $guest_data );

		$guest->set_meta( $this->load_guest_meta( $guest ) );

		$this->identity_map[ $guest->get_id() ] = $guest;

		return $guest;
	}

	public function find_one_by_email(
		#[\SensitiveParameter]
		string $email
	): Guest {
		foreach ( $this->identity_map as $guest ) {
			if ( $guest->get_email() === $email ) {
				return $guest;
			}
		}

		$guest_data = $this->wpdb->get_row(
			$this->wpdb->prepare(
				$this->select_sql() . ' WHERE email LIKE %s LIMIT 1',
				sanitize_email( $email )
			),
			ARRAY_A
		);

		if ( ! $guest_data ) {
			throw EntityNotFound::failing_criteria( [ 'email' => $email ] );
		}

		$guest = $this->create_guest_from_data( $guest_data );

		$guest->set_meta( $this->load_guest_meta( $guest ) );

		$this->identity_map[ $guest->get_id() ] = $guest;

		return $guest;
	}

	/**
	 * Insert or update guest in database.
	 *
	 * @param Guest $guest
	 *
	 * @return bool
	 */
	public function save( object $guest ): bool {
		$guest_data = [
			'email'        => $guest->get_email(),
			'updated'      => $guest->get_updated()->format( 'Y-m-d H:i:s' ),
			'created'      => $guest->get_created()->format( 'Y-m-d H:i:s' ),
			'tracking_key' => $guest->get_tracking_key(),
		];

		$this->wpdb->query( 'START TRANSACTION' );
		// If guest already exists, update it.
		if ( $guest->exists() ) {
			$guest_data['id'] = $guest->get_raw_id();
			$updated          = $this->wpdb->update(
				DatabaseTable::guest(),
				$guest_data,
				[ 'id' => $guest->get_raw_id(), ],
				[ '%s', '%s', '%s', '%s' ],
				[ '%d' ]
			);
		} else {
			$updated = $this->wpdb->insert(
				DatabaseTable::guest(),
				$guest_data,
				[ '%s', '%s', '%s', '%s' ]
			);
		}

		if ( $updated === false ) {
			$this->wpdb->query( 'ROLLBACK' );

			return false;
		}

		if ( ! $guest->exists() ) {
			$guest->set_id( $this->wpdb->insert_id );
		}

		if ( ! $guest->get_meta()->is_empty() ) {
			$update_meta = $this->insert_guest_meta( $guest );
			if ( ! $update_meta ) {
				$this->wpdb->query( 'ROLLBACK' );

				return false;
			}
			// Refresh guest in-memory metadata.
			$guest->set_meta( $this->load_guest_meta( $guest ) );
		}

		$this->wpdb->query( 'COMMIT' );

		$this->identity_map[ $guest->get_id() ] = $guest;

		return (bool) $updated;
	}

	/**
	 * @param Guest $guest
	 *
	 * @return void
	 */
	public function delete( object $guest ): void {
		$this->wpdb->query( 'START TRANSACTION' );
		$result = $this->wpdb->delete(
			DatabaseTable::guest(),
			[ 'id' => $guest->get_raw_id() ],
			[ '%d' ]
		);

		if ( $result === false ) {
			$this->wpdb->query( 'ROLLBACK' );

			return;
		}

		$result = $this->wpdb->delete(
			DatabaseTable::guest_meta(),
			[ 'guest_id' => $guest->get_raw_id() ],
			[ '%d' ]
		);

		if ( $result === false ) {
			$this->wpdb->query( 'ROLLBACK' );

			return;
		}

		$this->wpdb->query( 'COMMIT' );
		unset( $this->identity_map[ $guest->get_id() ] );
	}

	public function can_handle( object $item ): bool {
		return true;
	}

	public function refresh( object $item ): object {
		return $item;
	}

	public function get_repository(): ObjectRepository {
		return $this;
	}

	public function find_all(): Collection {
		// TODO: Implement find_all() method.
		return new ArrayCollection();
	}

	public function find_by( array $criteria, array $order = [], int $offset = 0, ?int $limit = null ): Collection {
		// TODO: Implement find_by() method.
		return new ArrayCollection();
	}

	public function find_one_by( array $criteria, ?array $order = null ) {
		return $this->find_one_by_email( $criteria['email'] );
	}

	/**
	 * @param $guest_data
	 *
	 * @return Guest
	 * @throws \Exception
	 */
	private function create_guest_from_data( array $guest_data ): Guest {
		$guest = new Guest();
		$guest->set_id( (int) $guest_data['id'] );
		$guest->set_email( $guest_data['email'] );
		$guest->set_updated( new \DateTimeImmutable( $guest_data['updated'] ) );
		$guest->set_created( new \DateTimeImmutable( $guest_data['created'] ) );
		$guest->set_tracking_key( $guest_data['tracking_key'] );

		return $guest;
	}

	private function select_sql(): string {
		return sprintf( 'SELECT id, email, tracking_key, created, updated FROM %s', DatabaseTable::guest() );
	}

	/**
	 * @param $meta_data
	 *
	 * @return Collection<string, GuestMeta>
	 */
	private function get_metadata_collection( array $meta_data ): Collection {
		$meta_collection = new ArrayCollection();
		foreach ( $meta_data as $meta ) {
			$guest_meta = new GuestMeta( $meta['meta_key'], $meta['meta_value'] );
			$guest_meta->set_meta_id( (int) $meta['meta_id'] );
			$guest_meta->set_guest_id( (int) $meta['guest_id'] );
			$meta_collection[ $meta['meta_key'] ] = $guest_meta;
		}

		return $meta_collection;
	}

	/**
	 * @param Guest $guest
	 *
	 * @return Collection<string, GuestMeta>
	 */
	private function load_guest_meta( Guest $guest ): Collection {
		$raw_meta = $this->wpdb->get_results(
			$this->wpdb->prepare(
				'SELECT meta_id, guest_id, meta_key, meta_value FROM %1$s WHERE guest_id = %2$d',
				DatabaseTable::guest_meta(),
				$guest->get_raw_id()
			),
			ARRAY_A
		);

		return $this->get_metadata_collection( $raw_meta );
	}

	/**
	 * @param $guest
	 *
	 * @return bool
	 */
	public function insert_guest_meta( Guest $guest ): bool {
		$table = DatabaseTable::guest_meta();
		foreach ( array_unique( $guest->get_meta()->to_array() ) as $meta ) {
			if ( $this->has_meta( $guest->get_raw_id(), $meta->get_meta_key() ) ) {
				$this->wpdb->update(
					$table,
					[ 'meta_value' => $meta->get_meta_value() ],
					[
						'guest_id' => $guest->get_raw_id(),
						'meta_key' => $meta->get_meta_key(),
					]
				);
			} else {
				$this->wpdb->insert(
					$table,
					[
						'guest_id'   => $guest->get_raw_id(),
						'meta_key'   => $meta->get_meta_key(),
						'meta_value' => $meta->get_meta_value(),
					]
				);
			}
		}

		return true;
	}

	private function has_meta( int $id, string $meta_key ): bool {
		return ! is_null(
			$this->wpdb->get_row(
				$this->wpdb->prepare(
					'SELECT meta_id FROM %1$s WHERE guest_id = %2$d AND meta_key = "%3$s"',
					DatabaseTable::guest_meta(),
					$id,
					$meta_key
				)
			)
		);
	}

}
