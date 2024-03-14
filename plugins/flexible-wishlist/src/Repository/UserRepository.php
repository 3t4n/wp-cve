<?php

namespace WPDesk\FlexibleWishlist\Repository;

use WPDesk\FlexibleWishlist\Exception\InvalidSettingsOptionKey;
use WPDesk\FlexibleWishlist\Model\User;
use WPDesk\FlexibleWishlist\PluginConstants;

/**
 * Saves and reads wishlist users.
 *
 * phpcs:disable WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
 */
class UserRepository {

	/**
	 * @var WishlistRepository
	 */
	private $wishlist_repository;

	/**
	 * @var string
	 */
	private $table_name;

	public function __construct( WishlistRepository $wishlist_repository ) {
		$this->wishlist_repository = $wishlist_repository;
		global $wpdb;
		$this->table_name = $wpdb->prefix . PluginConstants::SQL_TABLE_USERS;
	}

	/**
	 * @param int $id .
	 *
	 * @return User|null
	 * @throws InvalidSettingsOptionKey
	 */
	public function get_by_id( int $id ) {
		global $wpdb;
		$result = $wpdb->get_row(
			$wpdb->prepare(
				"SELECT * FROM {$this->table_name} WHERE id = %s LIMIT 1", // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
				esc_sql( (string) $id )
			)
		);
		return ( $result ) ? $this->get_object( $result ) : null;
	}

	/**
	 * @param string $user_token .
	 *
	 * @return User|null
	 * @throws InvalidSettingsOptionKey
	 */
	public function get_by_user_token( string $user_token ) {
		global $wpdb;
		$result = $wpdb->get_row(
			$wpdb->prepare(
				"SELECT * FROM {$this->table_name} WHERE user_token = %s LIMIT 1", // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
				esc_sql( $user_token )
			)
		);
		return ( $result ) ? $this->get_object( $result ) : null;
	}

	/**
	 * @param int $user_id .
	 *
	 * @return User|null
	 * @throws InvalidSettingsOptionKey
	 */
	public function get_by_user_id( int $user_id ) {
		global $wpdb;
		$result = $wpdb->get_row(
			$wpdb->prepare(
				"SELECT * FROM {$this->table_name} WHERE user_id = %s LIMIT 1", // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
				esc_sql( (string) $user_id )
			)
		);
		return ( $result ) ? $this->get_object( $result ) : null;
	}

	/**
	 * @param User $user .
	 *
	 * @return int|null
	 */
	public function save( User $user ) {
		$user->set_updated_at( new \DateTime() );
		return $this->save_object( $user );
	}

	public function remove( User $user ): bool {
		return ( $user->get_id() !== null ) && $this->remove_object( $user->get_id() );
	}

	/**
	 * @param object $result .
	 *
	 * @return User
	 * @throws InvalidSettingsOptionKey
	 */
	private function get_object( $result ): User {
		return new User(
			$result->id,
			$result->user_token,
			$result->user_id,
			new \DateTime( $result->created_at ),
			new \DateTime( $result->updated_at ),
			$this->wishlist_repository->get_by_user( $result->id )
		);
	}

	/**
	 * @param User $user .
	 *
	 * @return int|null
	 */
	private function save_object( User $user ) {
		global $wpdb;
		if ( $user->get_id() === null ) {
			try {
				$status = $wpdb->insert(
					$this->table_name,
					[
						'user_token' => esc_sql( $user->get_user_token() ),
						'user_id'    => ( $user->get_user_id() !== null )
							? esc_sql( (string) $user->get_user_id() )
							: null,
						'created_at' => esc_sql( $user->get_created_at()->format( 'Y-m-d H:i:s' ) ),
						'updated_at' => esc_sql( $user->get_updated_at()->format( 'Y-m-d H:i:s' ) ),
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
					'user_id'    => esc_sql( (string) $user->get_user_id() ),
					'updated_at' => esc_sql( $user->get_updated_at()->format( 'Y-m-d H:i:s' ) ),
				],
				[
					'id' => esc_sql( (string) $user->get_id() ),
				]
			);
			return ( $status ) ? $user->get_id() : null;
		}
	}

	private function remove_object( int $user_id ): bool {
		global $wpdb;
		$status = $wpdb->delete(
			$this->table_name,
			[
				'id' => esc_sql( (string) $user_id ),
			]
		);
		return (bool) $status;
	}
}
