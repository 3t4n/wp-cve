<?php

declare( strict_types=1 );

namespace WPDesk\ShopMagic\Components\Database\Abstraction;

/**
 * Naive class capable of converting HTTP request parameters to repository criteria.
 * It lets you define whitelisted keys and values for WHERE query and whitelisted keys for ORDER query.
 * This functionality is able only to map simple queries, where HTTP parameter is the same value as queried column.
 * Besides, you can't define more complex relations, like !=, IN, LIKE, etc. from SQL.
 */
class RequestToCriteria {
	private const DEFAULT_PAGE_SIZE = 20;
	private const DEFAULT_PAGE      = 1;

	/** @var string[] */
	private $order_keys;

	/** @var array<string, string[]> */
	private $where_whitelist = [];

	public function set_order_keys( array $order_keys ): self {
		$self             = clone $this;
		$self->order_keys = $order_keys;

		return $self;
	}

	/**
	 * Expects to receive array, where key is the column name and value holds string list.
	 * Array values are allowed entries, safe to execute in SQL.
	 *
	 * @param array<string, string[]> $where
	 */
	public function set_where_whitelist( array $where ): self {
		$self                  = clone $this;
		$self->where_whitelist = $where;

		return $self;
	}

	/**
	 * @param \WP_REST_Request $request
	 *
	 * @return array{
	 *     0: array,
	 *     1: array,
	 *     2: int,
	 *     3: int
	 * }
	 */
	public function parse_request( \WP_REST_Request $request ): array {
		$page      = $request->get_param( 'page' ) ?? self::DEFAULT_PAGE;
		$page_size = $request->get_param( 'pageSize' ) ?? self::DEFAULT_PAGE_SIZE;
		$filters   = $request->get_param( 'filters' );
		$order     = $request->get_param( 'order' );

		return [
			$this->get_where_criteria( $filters ),
			$this->get_order_criteria( $order ),
			$this->get_offset( (int) $page, (int) $page_size ),
			(int) $page_size,
		];
	}

	/** @return array<string, string> */
	private function get_where_criteria( ?array $filters ): array {
		$criteria = [];
		foreach ( $this->where_whitelist as $property => $allowed_values ) {
			if ( isset( $filters[ $property ] ) &&
			     $this->filter_is_allowed( $filters[ $property ], $allowed_values )
			) {
				$criteria[ $property ] = $filters[ $property ];
			}
		}

		return $criteria;
	}

	private function filter_is_allowed( string $needle, array $allowed_values ): bool {
		return in_array( $needle, $allowed_values, true );
	}

	/** @return string[] */
	private function get_order_criteria( ?array $order ): array {
		if ( isset( $order ) && in_array( array_key_first( $order ), $this->order_keys, true ) ) {
			$order_key      = array_key_first( $order );
			$order_criteria = [
				$order_key => $order[ $order_key ] === 'descend' ? 'DESC' : 'ASC',
			];
		}

		return $order_criteria ?? [];
	}

	private function get_offset( int $page, int $page_size ): int {
		return ( $page - 1 ) * $page_size;
	}

}
