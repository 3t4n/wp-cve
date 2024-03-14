<?php

namespace WPDesk\FlexibleWishlist\Repository;

use WPDesk\FlexibleWishlist\Exception\InvalidSettingsOptionKey;
use WPDesk\FlexibleWishlist\Model\Wishlist;
use WPDesk\FlexibleWishlist\Model\WishlistItem;
use WPDesk\FlexibleWishlist\PluginConstants;
use WPDesk\FlexibleWishlist\Settings\Option\TextDefaultWishlistTitleOption;

/**
 * Saves and reads wishlists.
 *
 * phpcs:disable WordPress.DB.DirectDatabaseQuery.DirectQuery
 * phpcs:disable WordPress.DB.PreparedSQL.InterpolatedNotPrepared
 * phpcs:disable WordPress.DB.DirectDatabaseQuery.NoCaching
 */
class WishlistRepository {

	/**
	 * @var SettingsRepository
	 */
	private $settings_repository;

	/**
	 * @var string
	 */
	private $table_name;

	/**
	 * @var string
	 */
	private $item_table_name;

	/** @var string */
	private $user_table_name;

	public function __construct( SettingsRepository $settings_repository ) {
		$this->settings_repository = $settings_repository;
		global $wpdb;
		$this->table_name      = $wpdb->prefix . PluginConstants::SQL_TABLE_LISTS;
		$this->item_table_name = $wpdb->prefix . PluginConstants::SQL_TABLE_ITEMS;
		$this->user_table_name = $wpdb->prefix . PluginConstants::SQL_TABLE_USERS;
	}

	/**
	 * @return Wishlist[]
	 *
	 * @throws InvalidSettingsOptionKey
	 */
	public function get_by_user( int $user_id = null, bool $allow_empty_list = false ): array {
		global $wpdb;
		$results = ( $user_id !== null )
			? $wpdb->get_results(
				$wpdb->prepare(
					"SELECT list.*, user.user_id AS wp_user_id FROM {$this->table_name} AS list
					LEFT JOIN {$this->user_table_name} AS user ON user.id = list.user_id
					WHERE list.user_id = %s
					ORDER BY list.created_at ASC",
					esc_sql( (string) $user_id )
				)
			)
			: [];

		$items = [];
		foreach ( $results as $result ) {
			$this->get_by_id( $result->id );
			$items[] = $this->get_by_token( $result->list_token );
		}
		if ( ! $items && ! $allow_empty_list ) {
			$items[] = $this->create_new( $user_id, null, true );
		}

		return $items;
	}

	/**
	 * @throws InvalidSettingsOptionKey
	 */
	public function create_new( int $user_id = null, string $wishlist_name = null, bool $is_default = false ): Wishlist {
		return new Wishlist(
			null,
			$user_id,
			null,
			bin2hex( random_bytes( 16 ) ),
			( ( $wishlist_name !== null ) && ( $wishlist_name !== '' ) )
				? $wishlist_name
				: $this->settings_repository->get_value( TextDefaultWishlistTitleOption::FIELD_NAME ),
			$is_default,
			new \DateTime()
		);
	}

	/**
	 * @param int $wishlist_id .
	 *
	 * @return Wishlist|null
	 */
	public function get_by_id( int $wishlist_id ) {
		global $wpdb;
		$result = $wpdb->get_row(
			$wpdb->prepare(
				"SELECT list.*, user.user_id AS wp_user_id FROM {$this->table_name} AS list
					LEFT JOIN {$this->user_table_name} AS user ON user.id = list.user_id
					WHERE list.id = %s LIMIT 1",
				esc_sql( (string) $wishlist_id )
			)
		);
		return ( $result ) ? $this->get_object( $result ) : null;
	}

	/**
	 * @param string $wishlist_token .
	 *
	 * @return Wishlist|null
	 */
	public function get_by_token( string $wishlist_token ) {
		global $wpdb;
		$result = $wpdb->get_row(
			$wpdb->prepare(
				"SELECT list.*, user.user_id AS wp_user_id FROM {$this->table_name} AS list
					LEFT JOIN {$this->user_table_name} AS user ON user.id = list.user_id
					WHERE list.list_token = %s
					LIMIT 1",
				esc_sql( $wishlist_token )
			)
		);
		return ( $result ) ? $this->get_object( $result ) : null;
	}

	/**
	 * @return Wishlist[]
	 */
	public function get_all(): array {
		global $wpdb;
		$results = $wpdb->get_results(
			"SELECT list.*, user.user_id AS wp_user_id, item.id AS item_id, item.list_id AS item_list_id, item.product_id AS item_product_id, item.product_desc AS item_product_desc, item.quantity AS item_quantity, item.created_at AS item_created_at, item.updated_at AS item_updated_at FROM {$this->table_name} AS list
				LEFT JOIN {$this->item_table_name} AS item ON item.list_id = list.id
				LEFT JOIN {$this->user_table_name} AS user ON user.id = list.user_id"
		);

		$wishlists = [];
		foreach ( $results as $result ) {
			if ( ! isset( $wishlists[ $result->id ] ) ) {
				$wishlists[ $result->id ]        = $result;
				$wishlists[ $result->id ]->items = [];
			}
			if ( ! $result->item_id ) {
				continue;
			}

			$wishlists[ $result->id ]->items[] = new WishlistItem(
				$result->item_id,
				$result->item_list_id,
				$result->item_product_id,
				$result->item_product_desc,
				$result->item_quantity,
				new \DateTime( $result->item_created_at ),
				new \DateTime( $result->item_updated_at )
			);
		}

		return array_map(
			function ( $result ) {
				return $this->get_object( $result );
			},
			$wishlists
		);
	}

	/**
	 * @param Wishlist $wishlist .
	 *
	 * @return int|null
	 * @throws InvalidSettingsOptionKey
	 */
	public function save( Wishlist $wishlist ) {
		$user_wishlists = $this->get_by_user( $wishlist->get_user_id() );
		if ( $wishlist->get_default_status() && $user_wishlists ) {
			foreach ( $user_wishlists as $user_wishlist ) {
				if ( $user_wishlist->get_default_status() && ( $user_wishlist->get_id() !== $wishlist->get_id() ) ) {
					$user_wishlist->set_default_status( false );
					$this->save_object( $user_wishlist );
				}
			}
		} elseif ( ! $user_wishlists ) {
			$wishlist->set_default_status( true );
		}

		$wishlist->set_updated_at( new \DateTime() );
		return $this->save_object( $wishlist );
	}

	public function remove( Wishlist $wishlist ): bool {
		if ( $wishlist->get_default_status() ) {
			$user_wishlists = $this->get_by_user( $wishlist->get_user_id() );
			foreach ( $user_wishlists as $user_wishlist ) {
				if ( ( $user_wishlist->get_id() !== $wishlist->get_id() ) ) {
					$user_wishlist->set_default_status( true );
					$this->save( $user_wishlist );
					break;
				}
			}
		}

		return ( ( $wishlist->get_id() !== null ) && $this->remove_object( $wishlist->get_id() ) );
	}

	/**
	 * @param object $result .
	 *
	 * @return Wishlist
	 */
	private function get_object( $result ): Wishlist {
		return new Wishlist(
			$result->id,
			$result->user_id,
			$result->wp_user_id ?? null,
			$result->list_token,
			$result->name,
			$result->is_default,
			new \DateTime( $result->created_at ),
			new \DateTime( $result->updated_at ),
			( isset( $result->items ) ) ? $result->items : ( new WishlistItemRepository() )->get_by_wishlist( $result->id )
		);
	}

	/**
	 * @param Wishlist $wishlist .
	 *
	 * @return int|null
	 */
	private function save_object( Wishlist $wishlist ) {
		global $wpdb;
		if ( $wishlist->get_id() === null ) {
			if ( $wishlist->get_user_id() === null ) {
				return null;
			}

			try {
				$status = $wpdb->insert(
					$this->table_name,
					[
						'list_token' => bin2hex( random_bytes( 16 ) ),
						'user_id'    => esc_sql( (string) $wishlist->get_user_id() ),
						'name'       => esc_sql( (string) $wishlist->get_name() ),
						'is_default' => $wishlist->get_default_status(),
						'created_at' => esc_sql( $wishlist->get_created_at()->format( 'Y-m-d H:i:s' ) ),
						'updated_at' => esc_sql( $wishlist->get_updated_at()->format( 'Y-m-d H:i:s' ) ),
					]
				);
				return ( $status ) ? $wpdb->insert_id : null;
			} catch ( \Exception $e ) {
				return null;
			}
		} else {
			$status = $wpdb->update(
				$this->table_name,
				[
					'user_id'    => esc_sql( (string) $wishlist->get_user_id() ),
					'name'       => esc_sql( $wishlist->get_name() ),
					'is_default' => $wishlist->get_default_status(),
					'updated_at' => esc_sql( $wishlist->get_updated_at()->format( 'Y-m-d H:i:s' ) ),
				],
				[
					'id' => esc_sql( (string) $wishlist->get_id() ),
				]
			);
			return ( $status ) ? $wishlist->get_id() : null;
		}
	}

	private function remove_object( int $wishlist_id ): bool {
		global $wpdb;
		$status = $wpdb->delete(
			$this->table_name,
			[
				'id' => esc_sql( (string) $wishlist_id ),
			]
		);
		return (bool) $status;
	}
}
