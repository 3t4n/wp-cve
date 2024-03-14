<?php
/**
 * @package WPDesk\FlexibleWishlist
 */

namespace WPDesk\FlexibleWishlist\Form;

use WPDesk\FlexibleWishlist\Exception\InvalidFormData;
use WPDesk\FlexibleWishlist\Exception\InvalidSettingsOptionKey;
use WPDesk\FlexibleWishlist\Repository\UserRepository;
use WPDesk\FlexibleWishlist\Repository\WishlistRepository;
use WPDesk\FlexibleWishlist\Service\UserAuthManager;

/**
 * {@inheritdoc}
 */
class CreateWishlistForm implements Form {

	const ACTION_NAME         = 'wishlist_create';
	const PARAM_WISHLIST_NAME = 'wishlist_name';

	/**
	 * @var UserAuthManager
	 */
	private $user_auth_manager;

	/**
	 * @var UserRepository
	 */
	private $user_repository;

	/**
	 * @var WishlistRepository
	 */
	private $wishlist_repository;

	public function __construct(
		UserAuthManager $user_auth_manager,
		UserRepository $user_repository,
		WishlistRepository $wishlist_repository
	) {
		$this->user_auth_manager   = $user_auth_manager;
		$this->user_repository     = $user_repository;
		$this->wishlist_repository = $wishlist_repository;
	}

	/**
	 * {@inheritdoc}
	 */
	public function get_action_name(): string {
		return self::ACTION_NAME;
	}

	/**
	 * {@inheritdoc}
	 *
	 * @throws InvalidFormData
	 * @throws InvalidSettingsOptionKey
	 *
	 * phpcs:ignore Squiz.Commenting.FunctionCommentThrowTag.WrongNumber
	 */
	public function process_request( array $form_data ) {
		$wishlist_name = $form_data[ self::PARAM_WISHLIST_NAME ] ?? '';
		if ( $wishlist_name === '' ) {
			throw new InvalidFormData();
		}

		$wishlist_user = $this->user_auth_manager->get_user();
		$user_id       = $wishlist_user->get_id() ?: $this->user_repository->save( $wishlist_user );
		$wishlist      = $this->wishlist_repository->create_new(
			$user_id,
			$wishlist_name,
			( ! $wishlist_user->get_wishlists() || ( $wishlist_user->get_wishlists()[0]->get_id() === null ) )
		);
		$this->wishlist_repository->save( $wishlist );
	}
}
