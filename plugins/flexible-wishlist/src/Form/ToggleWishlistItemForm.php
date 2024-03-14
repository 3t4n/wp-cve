<?php
/**
 * @package WPDesk\FlexibleWishlist
 */

namespace WPDesk\FlexibleWishlist\Form;

use WPDesk\FlexibleWishlist\Exception\InvalidFormRequestId;
use WPDesk\FlexibleWishlist\Exception\InvalidSettingsOptionKey;
use WPDesk\FlexibleWishlist\Exception\UnauthorizedRequest;
use WPDesk\FlexibleWishlist\Model\WishlistItem;
use WPDesk\FlexibleWishlist\Repository\UserRepository;
use WPDesk\FlexibleWishlist\Repository\WishlistItemRepository;
use WPDesk\FlexibleWishlist\Repository\WishlistRepository;
use WPDesk\FlexibleWishlist\Service\UserAuthManager;

/**
 * {@inheritdoc}
 */
class ToggleWishlistItemForm implements Form {

	const ACTION_NAME       = 'wishlist_item_toggle';
	const PARAM_WISHLIST_ID = 'wishlist_id';
	const PARAM_ITEM_ID     = 'item_id';
	const PARAM_ITEM_IDEA   = 'item_idea';
	const PARAM_ITEM_STATUS = 'item_status';

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

	/**
	 * @var WishlistItemRepository
	 */
	private $wishlist_item_repository;

	public function __construct(
		UserAuthManager $user_auth_manager,
		UserRepository $user_repository,
		WishlistRepository $wishlist_repository,
		WishlistItemRepository $wishlist_item_repository
	) {
		$this->user_auth_manager        = $user_auth_manager;
		$this->user_repository          = $user_repository;
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
	 * @throws InvalidFormRequestId
	 * @throws InvalidSettingsOptionKey
	 * @throws UnauthorizedRequest
	 *
	 * phpcs:ignore Squiz.Commenting.FunctionCommentThrowTag.WrongNumber
	 */
	public function process_request( array $form_data ) {
		$wishlist_id = $form_data[ self::PARAM_WISHLIST_ID ];
		$item_id     = (int) $form_data[ self::PARAM_ITEM_ID ];
		$item_idea   = $form_data[ self::PARAM_ITEM_IDEA ];
		if ( ( $form_data[ self::PARAM_WISHLIST_ID ] === null ) ) {
			$wishlist_user = $this->user_auth_manager->get_user();
			$user_id       = $wishlist_user->get_id() ?: $this->user_repository->save( $wishlist_user );
			$wishlist_id   = $this->wishlist_repository->save(
				$this->wishlist_repository->create_new(
					$user_id,
					null,
					( ! $wishlist_user->get_wishlists() || ( $wishlist_user->get_wishlists()[0]->get_id() === null ) )
				)
			);
		}

		$wishlist = $this->wishlist_repository->get_by_id( $wishlist_id );
		if ( ( $wishlist === null ) || ( $wishlist->get_id() === null ) ) {
			throw new InvalidFormRequestId();
		} elseif ( $wishlist->get_user_id() !== $this->user_auth_manager->get_user( true )->get_id() ) {
			throw new UnauthorizedRequest();
		}

		if ( $item_id ) {
			if ( ! $form_data[ self::PARAM_ITEM_STATUS ] ) {
				foreach ( $wishlist->get_items() as $wishlist_item ) {
					if ( $wishlist_item->get_product_id() === $item_id ) {
						$this->wishlist_item_repository->remove( $wishlist_item );
					}
				}
			} else {
				$this->wishlist_item_repository->save(
					new WishlistItem(
						null,
						$wishlist->get_id(),
						$item_id,
						null,
						1,
						new \DateTime()
					)
				);
			}
		} elseif ( $item_idea !== '' ) {
			if ( ! $form_data[ self::PARAM_ITEM_STATUS ] ) {
				foreach ( $wishlist->get_items() as $wishlist_item ) {
					if ( $wishlist_item->get_product_desc() === $item_idea ) {
						$this->wishlist_item_repository->remove( $wishlist_item );
					}
				}
			} else {
				$this->wishlist_item_repository->save(
					new WishlistItem(
						null,
						$wishlist->get_id(),
						null,
						$item_idea,
						1,
						new \DateTime()
					)
				);
			}
		}
	}
}
