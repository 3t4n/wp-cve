<?php
/**
 * @package WPDesk\FlexibleWishlist
 */

namespace WPDesk\FlexibleWishlist\Form;

use WPDesk\FlexibleWishlist\Exception\FormRequestUnauthorized;
use WPDesk\FlexibleWishlist\Exception\InvalidFormData;
use WPDesk\FlexibleWishlist\Exception\InvalidFormRequestId;
use WPDesk\FlexibleWishlist\Model\WishlistItem;
use WPDesk\FlexibleWishlist\Repository\WishlistItemRepository;
use WPDesk\FlexibleWishlist\Repository\WishlistRepository;
use WPDesk\FlexibleWishlist\Service\UserAuthManager;

/**
 * {@inheritdoc}
 */
class CreateWishlistItemForm implements Form {

	const ACTION_NAME       = 'wishlist_item_create';
	const PARAM_WISHLIST_ID = 'wishlist_id';
	const PARAM_ITEM_ID     = 'item_id';
	const PARAM_ITEM_IDEA   = 'item_idea';

	/**
	 * @var UserAuthManager
	 */
	private $user_auth_manager;

	/**
	 * @var WishlistRepository
	 */
	private $wishlist_repository;

	/**
	 * @var WishlistItemRepository
	 */
	private $wishlist_item_repository;

	public function __construct(
		UserAuthManager $user_auth_manager,
		WishlistRepository $wishlist_repository,
		WishlistItemRepository $wishlist_item_repository
	) {
		$this->user_auth_manager        = $user_auth_manager;
		$this->wishlist_repository      = $wishlist_repository;
		$this->wishlist_item_repository = $wishlist_item_repository;
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
	 * @throws InvalidFormRequestId
	 * @throws FormRequestUnauthorized
	 */
	public function process_request( array $form_data ) {
		$wishlist = $this->wishlist_repository->get_by_id( $form_data[ self::PARAM_WISHLIST_ID ] ?? '' );
		if ( ( $wishlist === null ) || ( $wishlist->get_id() === null ) ) {
			throw new InvalidFormRequestId();
		}

		$wishlist_user = $this->user_auth_manager->get_user();
		if ( $wishlist_user->get_id() !== $wishlist->get_user_id() ) {
			throw new FormRequestUnauthorized();
		}

		$item_product_id   = $form_data[ self::PARAM_ITEM_ID ] ?? '';
		$item_product_desc = $form_data[ self::PARAM_ITEM_IDEA ] ?? '';
		if ( ( $item_product_id === '' ) && ( $item_product_desc === '' ) ) {
			throw new InvalidFormData();
		}

		$wishlist_item = new WishlistItem(
			null,
			$wishlist->get_id(),
			$item_product_id ?: null,
			$item_product_desc ?: null,
			1,
			new \DateTime()
		);
		$this->wishlist_item_repository->save( $wishlist_item );
	}
}
