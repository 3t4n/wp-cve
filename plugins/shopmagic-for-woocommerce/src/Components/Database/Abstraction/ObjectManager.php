<?php

declare( strict_types=1 );

namespace WPDesk\ShopMagic\Components\Database\Abstraction;

use ShopMagicVendor\Psr\Log\LoggerAwareTrait;
use WPDesk\ShopMagic\Components\Database\Abstraction\DAO\ObjectPersister;
use WPDesk\ShopMagic\Components\Database\Abstraction\DAO\ObjectRepository;

/**
 * @template T of object
 * @template-implements ObjectPersister<T>
 */
abstract class ObjectManager implements DAO\ObjectPersister {
	use LoggerAwareTrait;

	/** @var \wpdb */
	protected $wpdb;

	/** @var DAO\ObjectHydrator<T> */
	protected $normalizer;

	/** @var DAO\ObjectRepository<T> */
	private $repository;

	/**
	 * @param ObjectRepository<T>   $repository
	 * @param DAO\ObjectHydrator<T> $normalizer
	 * @param \wpdb|null            $wpdb
	 */
	public function __construct(
		DAO\ObjectRepository $repository,
		DAO\ObjectHydrator $normalizer,
		?\wpdb $wpdb = null
	) {
		$this->repository = $repository;
		$this->normalizer = $normalizer;
		$this->wpdb       = $wpdb ?? $this->get_wpdb();
	}

	private function get_wpdb(): \wpdb {
		global $wpdb;

		return $wpdb;
	}

	public function get_repository(): ObjectRepository {
		return $this->repository;
	}

	public function can_handle( object $item ): bool {
		return false;
	}

	/**
	 * TODO: handle object identity, to update or insert
	 */
	public function save( object $item ): bool {
		$saved_count = null;
		//if ( ! $item->has_changed() ) {
		//	return false;
		//}

		$item_data = $this->normalizer->normalize( $item );
		//if ( $item->get_changed_fields() !== [] ) {
		//	$item_data = array_intersect_key( $item_data, $item->get_changed_fields() );
		//}

		$item_data = array_intersect_key( $item_data, array_flip( $this->get_columns() ) );

		$insert_required = \count( $this->retrieve_primary_key_value_from_item( $item_data ) ) !== \count( $this->get_primary_key() );
		if ( ! $insert_required ) {
			$saved_count = $this->wpdb->update(
				$this->get_name(),
				$item_data,
				array_combine( $this->get_primary_key(), $this->retrieve_primary_key_value_from_item( $item_data ) )
			);
			if ( $saved_count === false || $saved_count === null ) {
				$insert_required = true;
			}
		}

		if ( $insert_required ) {
			$saved_count = $this->wpdb->insert(
				$this->get_name(),
				$item_data
			);
			if ( $saved_count === 1 ) {
				if ( method_exists( $item, 'set_last_inserted_id' ) ) {
					$item->set_last_inserted_id( $this->wpdb->insert_id );
				} elseif ( method_exists( $item, 'set_id' ) ) {
					$item->set_id( $this->wpdb->insert_id );
				}
			}
		}

		return $saved_count > 0;
	}

	/**
	 * @return string[]
	 */
	abstract protected function get_columns(): array;

	protected function retrieve_primary_key_value_from_item( array $item ): array {
		$keys = [];
		foreach ( $this->get_primary_key() as $key_index ) {
			$value = (string) $item[ $key_index ];
			if ( $value !== '' ) {
				$keys[] = $value;
			}
		}

		return $keys;
	}

	/**
	 * By default, we treat first table's column as primary key.
	 *
	 * @return string[] It can be compound primary key.
	 */
	protected function get_primary_key(): array {
		return [ $this->get_columns()[0] ];
	}

	/**
	 * Table name.
	 */
	abstract protected function get_name(): string;

	public function delete_by_where( array $where = [] ): int {
		return (int) $this->wpdb->delete( $this->get_name(), $where );
	}

	public function delete( object $item ) {
		return (int) $this->wpdb->delete(
			$this->get_name(),
			$this->get_primary_key_from_object( $item )
		);
	}

	public function refresh( object $item ): object {
		return $this->repository->find( $this->get_primary_key_from_object( $item ) );
	}

	/**
	 * @param T $item
	 *
	 * @return array<string, string> Array in shape of ['primary_key' => 'value']
	 */
	protected function get_primary_key_from_object( object $item ): array {
		$primary_key = $this->get_primary_key();
		$pk_value = [];
		foreach ( $primary_key as $pk ) {
			$get_pk = "get_$pk";
			if ( method_exists( $item, $get_pk ) ) {
				$pk_value[ $pk ] = $item->$get_pk();
			}
		}
		return $pk_value;
	}

	public function find( $id ): object {
		return $this->repository->find( $id );
	}

}
