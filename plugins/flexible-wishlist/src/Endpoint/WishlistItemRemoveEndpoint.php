<?php
/**
 * @package WPDesk\FlexibleWishlist
 */

namespace WPDesk\FlexibleWishlist\Endpoint;

use WPDesk\FlexibleWishlist\Exception\InvalidFormRequestId;
use WPDesk\FlexibleWishlist\Exception\UnauthorizedRequest;
use WPDesk\FlexibleWishlist\Form\RemoveWishlistItemForm;
use WPDesk\FlexibleWishlist\Repository\WishlistItemRepository;
use WPDesk\FlexibleWishlist\Repository\WishlistRepository;
use WPDesk\FlexibleWishlist\Service\UserAuthManager;

/**
 * {@inheritdoc}
 */
class WishlistItemRemoveEndpoint extends EndpointBase {

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
	 * @return string
	 */
	public function get_route_method_type(): string {
		return \WP_REST_Server::DELETABLE;
	}

	/**
	 * {@inheritdoc}
	 */
	public static function get_route_name(): string {
		return 'items/(?P<' . RemoveWishlistItemForm::PARAM_ITEM_ID . '>\d+)';
	}

	/**
	 * {@inheritdoc}
	 */
	public function get_route_args(): array {
		return [
			RemoveWishlistItemForm::PARAM_ITEM_ID => [
				'description'       => null,
				'required'          => true,
				'sanitize_callback' => function ( $value ) {
					return (int) $value;
				},
			],
		];
	}

	/**
	 * {@inheritdoc}
	 *
	 * @throws InvalidFormRequestId
	 * @throws UnauthorizedRequest
	 */
	public function get_route_response( \WP_REST_Request $request ) {
		( new RemoveWishlistItemForm( $this->user_auth_manager, $this->wishlist_repository, $this->wishlist_item_repository ) )
			->process_request( $request->get_params() );

		return new \WP_REST_Response(
			[
				'status' => true,
			],
			200
		);
	}
}
