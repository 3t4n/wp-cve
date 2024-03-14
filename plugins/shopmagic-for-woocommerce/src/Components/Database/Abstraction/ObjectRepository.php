<?php

declare( strict_types=1 );

namespace WPDesk\ShopMagic\Components\Database\Abstraction;

use ShopMagicVendor\Psr\Log\LoggerAwareTrait;
use WPDesk\ShopMagic\Components\Collections\Collection;
use WPDesk\ShopMagic\Components\Database\Abstraction\DAO\ObjectDehydrator;
use WPDesk\ShopMagic\Components\Database\Collections\LazyObjectCollection;

/**
 * @template T of object
 * @template-implements DAO\ObjectRepository<T>
 */
abstract class ObjectRepository implements DAO\ObjectRepository {
	use LoggerAwareTrait;

	/** @var ObjectDehydrator<T> */
	protected $denormalizer;

	/** @var \wpdb */
	protected $wpdb;

	/**
	 * @param ObjectDehydrator<T> $denormalizer
	 * @param \wpdb|null          $wpdb
	 */
	public function __construct(
		ObjectDehydrator $denormalizer,
		?\wpdb $wpdb = null
	) {
		$this->denormalizer = $denormalizer;
		$this->wpdb         = $wpdb ?? $this->get_wpdb();
	}

	private function get_wpdb(): \wpdb {
		global $wpdb;

		return $wpdb;
	}

	/**
	 * @return T
	 */
	public function find( $id ): object {
		$sql    = sprintf(
			'SELECT * FROM %s WHERE %s LIMIT 1',
			$this->get_name(),
			$this->build_primary_key_value_for_prepare( $this->get_primary_key() )
		);
		$result = $this->wpdb->get_row( $this->wpdb->prepare( $sql, $id ), ARRAY_A );

		if ( is_array( $result ) ) {
			return $this->denormalizer->denormalize( $result );
		}

		throw new EntityNotFound(
			sprintf(
				'Failed to fetch item by identifier. ID: %s on `%s`',
				$id,
				static::class
			)
		);
	}

	/**
	 * Table name.
	 */
	abstract protected function get_name(): string;

	/**
	 * @param string[] $primary_key_value
	 *
	 * @return string
	 */
	private function build_primary_key_value_for_prepare( $primary_key_value ): string {
		return implode(
			' AND ',
			array_map(
				static function ( $key_part ): string {
					return sprintf( "%s = '%%s'", $key_part );
				},
				$primary_key_value
			)
		);
	}

	/**
	 * @return string[] It can be compound primary key.
	 */
	protected function get_primary_key(): array {
		return [ 'id' ];
	}

	/**
	 * @return Collection<T>
	 */
	public function find_all(): Collection {
		return $this->find_by( [] );
	}

	/**
	 * @return Collection<T>
	 */
	public function find_by( array $criteria, array $order = [], int $offset = 0, ?int $limit = null ): Collection {
		$where_sql = $this->where_array_to_sql( $criteria );
		$order_sql = $this->order_array_to_sql( $order );
		$sql       = sprintf( 'SELECT * FROM %s WHERE %s %s', $this->get_name(), $where_sql, $order_sql );
		if ( $limit !== null ) {
			$sql .= sprintf( ' LIMIT %d OFFSET %d', $limit, $offset );
		}

		return new LazyObjectCollection( $this->wpdb->get_results( $sql, ARRAY_A ), $this->denormalizer );
	}

	protected function where_array_to_sql( array $where ): string {
		if ( empty( $where ) ) {
			return ' 1 = 1 ';
		}

		$where_clauses = [];
		$where_values  = [];
		foreach ( $where as $key => $val ) {
			if ( \is_array( $val ) ) {
				$where_clauses[] = sprintf( '%s %s %%s', $val['field'], $val['condition'] );
				$where_values[]  = $val['value'];
			} else {
				$where_clauses[] = sprintf( '%s = %%s', $key );
				$where_values[]  = $val;
			}
		}

		return $this->wpdb->prepare( ' ' . implode( ' AND ', $where_clauses ), $where_values );
	}

	/**
	 * @param array<string, string> $order Order clauses in format [ field => asc|desc, field2 => asc|desc ]
	 */
	protected function order_array_to_sql( array $order ): string {
		if ( empty( $order ) ) {
			return '';
		}

		$order_clauses = [];
		foreach ( $order as $key => $val ) {
			$order_clauses[] = sprintf( '%s %s', $key, $val );
		}

		return ' ORDER BY ' . $this->sanitize_sql_orderby( implode( ',', $order_clauses ) );
	}

	/**
	 * @see sanitize_sql_orderby()
	 */
	private function sanitize_sql_orderby( string $value ): string {
		if ( preg_match(
			'#^\s*(([a-z0-9_.]+|`[a-z0-9_.]+`)(\s+(ASC|DESC))?\s*(,\s*(?=[a-z0-9_`.])|$))+$#i',
			$value
		) ) {
			return $value;
		}
		if ( preg_match( '#^\s*RAND\(\s*\)\s*$#i', $value ) ) {
			return $value;
		}
		if ( $this->logger ) {
			$this->logger->alert( sprintf( 'Invalid ORDER BY sanitization for value: %s', $value ) );
		}

		return '1=1';
	}

	/**
	 * @return T
	 */
	public function find_one_by( array $criteria, ?array $order = null ): object {
		$where_sql = $this->where_array_to_sql( $criteria );
		$order_sql = $order ? $this->order_array_to_sql( $order ) : '';
		$sql       = sprintf( 'SELECT * FROM %s WHERE %s %s LIMIT 1', $this->get_name(), $where_sql, $order_sql );
		$result    = $this->wpdb->get_row( $sql, ARRAY_A );

		if ( ! empty( $result ) ) {
			return $this->denormalizer->denormalize( $result );
		}

		throw new EntityNotFound(
			sprintf(
				'Failed to fetch singular item from database. Query: %s on `%s`',
				json_encode( $criteria ),
				static::class
			)
		);
	}

	public function get_count( array $where = [] ): int {
		$where_sql = $this->where_array_to_sql( $where );
		$sql       = sprintf( 'SELECT COUNT(*) FROM %s WHERE %s', $this->get_name(), $where_sql );

		return (int) $this->wpdb->get_var( $sql );
	}
}
