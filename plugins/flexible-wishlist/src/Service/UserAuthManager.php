<?php
/**
 * @package WPDesk\FlexibleWishlist
 */

namespace WPDesk\FlexibleWishlist\Service;

use FlexibleWishlistVendor\WPDesk\PluginBuilder\Plugin\Hookable;
use WPDesk\FlexibleWishlist\Exception\InvalidSettingsOptionKey;
use WPDesk\FlexibleWishlist\Model\User;
use WPDesk\FlexibleWishlist\Repository\UserRepository;
use WPDesk\FlexibleWishlist\Repository\WishlistRepository;

/**
 * Manages user authorization.
 */
class UserAuthManager implements Hookable {

	const AUTH_COOKIE_NAME = 'flexible_wishlist_user_token';

	/**
	 * @var UserRepository
	 */
	private $user_repository;

	/**
	 * @var WishlistRepository
	 */
	private $wishlist_repository;

	/**
	 * @var User
	 */
	private $user;

	public function __construct(
		UserRepository $user_repository,
		WishlistRepository $wishlist_repository
	) {
		$this->user_repository     = $user_repository;
		$this->wishlist_repository = $wishlist_repository;
	}

	/**
	 * {@inheritdoc}
	 */
	public function hooks() {
		add_action( 'init', [ $this, 'setup_user' ] );
	}

	/**
	 * @throws InvalidSettingsOptionKey
	 */
	public function get_user( bool $force_refresh = false ): User {
		if ( $force_refresh && ( $this->user->get_id() === null ) ) {
			$this->user = $this->user_repository->get_by_user_token( $this->user->get_user_token() ) ?: $this->user;
		}

		return $this->user;
	}

	/**
	 * @return void
	 * @throws InvalidSettingsOptionKey
	 * @internal
	 */
	public function setup_user() {
		$user_token = sanitize_text_field( wp_unslash( $_COOKIE[ self::AUTH_COOKIE_NAME ] ?? '' ) ) ?: null;
		$user_id    = get_current_user_id() ?: null;
		$user       = null;

		if ( $user_id !== null ) {
			$user = $this->user_repository->get_by_user_id( $user_id );
			if ( $user ) {
				$user = $this->migrate_user_wishlists( $user, $user_token );
				$this->save_user_token( null );
			}
		}
		if ( ( $user === null ) && ( $user_token !== null ) ) {
			$user = $this->user_repository->get_by_user_token( $user_token );
		}

		if ( $user === null ) {
			$user = $this->create_new_user( $user_id );
		} elseif ( ( $user_id !== null ) && ( $user->get_user_id() === null ) ) {
			$user->set_user_id( $user_id );
			$this->user_repository->save( $user );
		}

		$this->user = $user;
	}

	private function create_new_user( int $logged_user_id = null ): User {
		$user_token = bin2hex( random_bytes( 16 ) );
		$this->save_user_token( $user_token );

		return new User( null, $user_token, $logged_user_id, new \DateTime() );
	}

	/**
	 * @param string|null $user_token .
	 *
	 * @return void
	 */
	private function save_user_token( string $user_token = null ) {
		setcookie( self::AUTH_COOKIE_NAME, $user_token ?: '', 0, '/' );
		$_COOKIE[ self::AUTH_COOKIE_NAME ] = $user_token;
	}

	/**
	 * @throws InvalidSettingsOptionKey
	 */
	private function migrate_user_wishlists( User $new_user_owner, string $old_user_token = null ): User {
		$user_by_token = ( $old_user_token !== null ) ? $this->user_repository->get_by_user_token( $old_user_token ) : null;
		if ( ( $user_by_token === null ) || ( $new_user_owner->get_id() === null ) ) {
			return $new_user_owner;
		}

		foreach ( $user_by_token->get_wishlists() as $wishlist ) {
			if ( $wishlist->get_id() === null ) {
				continue;
			}

			$wishlist->set_user_id( $new_user_owner->get_id() );
			$wishlist->set_default_status( false );

			$this->wishlist_repository->save( $wishlist );
			$new_user_owner->add_wishlist( $wishlist );
		}
		return $new_user_owner;
	}
}
