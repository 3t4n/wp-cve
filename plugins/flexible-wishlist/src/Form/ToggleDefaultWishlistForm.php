<?php
/**
 * @package WPDesk\FlexibleWishlist
 */

namespace WPDesk\FlexibleWishlist\Form;

use WPDesk\FlexibleWishlist\Exception\InvalidFormRequestId;
use WPDesk\FlexibleWishlist\Exception\InvalidSettingsOptionKey;
use WPDesk\FlexibleWishlist\Exception\UnauthorizedRequest;
use WPDesk\FlexibleWishlist\Repository\WishlistRepository;
use WPDesk\FlexibleWishlist\Service\UserAuthManager;

/**
 * {@inheritdoc}
 */
class ToggleDefaultWishlistForm implements Form {

	const ACTION_NAME       = 'wishlist_toggle_default';
	const PARAM_WISHLIST_ID = 'wishlist_id';

	/**
	 * @var UserAuthManager
	 */
	private $user_auth_manager;

	/**
	 * @var WishlistRepository
	 */
	private $wishlist_repository;

	public function __construct(
		UserAuthManager $user_auth_manager,
		WishlistRepository $wishlist_repository
	) {
		$this->user_auth_manager   = $user_auth_manager;
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
	 * @throws InvalidFormRequestId
	 * @throws InvalidSettingsOptionKey
	 * @throws UnauthorizedRequest
	 *
	 * phpcs:ignore Squiz.Commenting.FunctionCommentThrowTag.WrongNumber
	 */
	public function process_request( array $form_data ) {
		$wishlist = $this->wishlist_repository->get_by_id( $form_data[ self::PARAM_WISHLIST_ID ] );
		if ( $wishlist === null ) {
			throw new InvalidFormRequestId();
		} elseif ( $wishlist->get_user_id() !== $this->user_auth_manager->get_user()->get_id() ) {
			throw new UnauthorizedRequest();
		}

		$wishlist->set_default_status( ! $wishlist->get_default_status() );
		$this->wishlist_repository->save( $wishlist );
	}
}
