<?php

namespace WPDesk\FlexibleWishlist\Repository;

use WPDesk\FlexibleWishlist\Model\WishlistItem;
use WPDesk\FlexibleWishlist\PluginConstants;

/**
 * Saves and reads the wishlist items.
 *
 * phpcs:disable WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
 */
class WishlistItemRepository {

	/**
	 * @var string
	 */
	private $table_name;

	public function __construct() {
		global $wpdb;
		$this->table_name = $wpdb->prefix . PluginConstants::SQL_TABLE_ITEMS;
	}

	/**
	 * @param int $item_id .
	 *
	 * @return WishlistItem|null
	 */
	public function get_by_id( int $item_id ) {
		global $wpdb;
		$result = $wpdb->get_row(
			$wpdb->prepare(
				"SELECT * FROM {$this->table_name} WHERE id = %s LIMIT 1", // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
				esc_sql( (string) $item_id )
			)
		);
		return ( $result ) ? $this->get_object( $result ) : null;
	}

	/**
	 * @param int $wishlist_id .
	 *
	 * @return WishlistItem[]
	 */
	public function get_by_wishlist( int $wishlist_id ): array {
		global $wpdb;
		$results = $wpdb->get_results(
			$wpdb->prepare(
				"SELECT * FROM {$this->table_name} WHERE list_id = %s ORDER BY created_at ASC", // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
				esc_sql( (string) $wishlist_id )
			)
		);

		$items = [];
		foreach ( $results as $result ) {
			$items[] = $this->get_object( $result );
		}
		return $items;
	}

	/**
	 * @param WishlistItem $wishlist_item .
	 *
	 * @return int|null
	 */
	public function save( WishlistItem $wishlist_item ) {
		$user_items = $this->get_by_wishlist( $wishlist_item->get_list_id() );
		foreach ( $user_items as $user_item ) {
			if ( $user_item->get_id() === null ) {
				continue;
			}

			if ( ( ( $wishlist_item->get_product_id() === null )
					&& ( $user_item->get_product_desc() === $wishlist_item->get_product_desc() ) )
				|| ( ( $wishlist_item->get_product_desc() === null )
					&& ( $user_item->get_product_id() === $wishlist_item->get_product_id() ) ) ) {
				$wishlist_item->set_id( $user_item->get_id() );
			}
		}

		$wishlist_item->set_updated_at( new \DateTime() );
		return $this->save_object( $wishlist_item );
	}

	public function remove( WishlistItem $wishlist_item ): bool {
		if ( $wishlist_item->get_id() === null ) {
			return false;
		}

		global $wpdb;
		$status = $wpdb->delete(
			$this->table_name,
			[
				'id' => esc_sql( (string) $wishlist_item->get_id() ),
			]
		);
		return (bool) $status;
	}

	public function remove_all( int $wishlist_id ): bool {
		global $wpdb;
		$status = $wpdb->delete(
			$this->table_name,
			[
				'list_id' => esc_sql( (string) $wishlist_id ),
			]
		);
		return (bool) $status;
	}

	/**
	 * @param object $result .
	 *
	 * @return WishlistItem
	 */
	private function get_object( $result ): WishlistItem {
		return new WishlistItem(
			$result->id,
			$result->list_id,
			$result->product_id,
			$result->product_desc,
			$result->quantity,
			new \DateTime( $result->created_at ),
			new \DateTime( $result->updated_at )
		);
	}

	/**
	 * @param WishlistItem $wishlist_item .
	 *
	 * @return int|null
	 */
	public function save_object( WishlistItem $wishlist_item ) {
		global $wpdb;
		if ( $wishlist_item->get_id() === null ) {
			$status = $wpdb->insert(
				$this->table_name,
				[
					'list_id'      => esc_sql( (string) $wishlist_item->get_list_id() ),
					'product_id'   => ( $wishlist_item->get_product_id() !== null )
						? esc_sql( (string) $wishlist_item->get_product_id() )
						: null,
					'product_desc' => ( $wishlist_item->get_product_desc() !== null )
						? esc_sql( (string) $wishlist_item->get_product_desc() )
						: null,
					'quantity'     => esc_sql( (string) $wishlist_item->get_quantity() ),
					'created_at'   => esc_sql( $wishlist_item->get_created_at()->format( 'Y-m-d H:i:s' ) ),
					'updated_at'   => esc_sql( $wishlist_item->get_updated_at()->format( 'Y-m-d H:i:s' ) ),
				]
			);
			return ( $status ) ? $wpdb->insert_id : null;
		} else {
			$status = $wpdb->update(
				$this->table_name,
				[
					'quantity'   => esc_sql( (string) $wishlist_item->get_quantity() ),
					'updated_at' => esc_sql( $wishlist_item->get_updated_at()->format( 'Y-m-d H:i:s' ) ),
				],
				[
					'id' => esc_sql( (string) $wishlist_item->get_id() ),
				]
			);
			return ( $status ) ? $wishlist_item->get_id() : null;
		}
	}
}
