<?php

namespace WPDesk\ShopMagic\Components\Database\Abstraction\DAO;

use WPDesk\ShopMagic\Components\Collections\Collection;

/**
 * @template T of object
 */
interface ObjectRepository {
	/**
	 * @param string|int $id
	 *
	 * @return T|null
	 */
	public function find( $id );

	/**
	 * @return \WPDesk\ShopMagic\Components\Collections\Collection<int, T>
	 * @deprecated 3.0.9 This method shouldn't belong to shared interface as it may imply huge
	 * performance drawbacks (or system break) if repository would be queried for ALL instances.
	 * We are not able to determine how large is entities volumes upfront.
	 *
	 * @see find_by Prefer find_by usage, as it allows better control over query limits
	 */
	public function find_all(): Collection;

	/**
	 * @param array    $criteria
	 * @param array    $order
	 * @param int      $offset
	 * @param int|null $limit
	 *
	 * @return \WPDesk\ShopMagic\Components\Collections\Collection<int, T>
	 */
	public function find_by( array $criteria, array $order = [], int $offset = 0, ?int $limit = null ): Collection;

	/**
	 * @param array      $criteria
	 * @param array|null $order
	 *
	 * @return T|null
	 */
	public function find_one_by( array $criteria, ?array $order = null );

	//public function count( array $criteria ): int;
}
