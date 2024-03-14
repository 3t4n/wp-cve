<?php
/**
 * @package WPDesk\FlexibleWishlist
 */

namespace WPDesk\FlexibleWishlist\Endpoint;

use WPDesk\FlexibleWishlist\Exception\InvalidFormData;
use WPDesk\FlexibleWishlist\Exception\InvalidFormRequestId;
use WPDesk\FlexibleWishlist\Exception\UnauthorizedRequest;
use WPDesk\FlexibleWishlist\Form\UpdateWishlistItemQuantityForm;
use WPDesk\FlexibleWishlist\Repository\WishlistItemRepository;
use WPDesk\FlexibleWishlist\Repository\WishlistRepository;
use WPDesk\FlexibleWishlist\Service\UserAuthManager;

/**
 * {@inheritdoc}
 */
class WishlistItemUpdateEndpoint extends EndpointBase {

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
		return \WP_REST_Server::EDITABLE;
	}

	/**
	 * {@inheritdoc}
	 */
	public static function get_route_name(): string {
		return 'items/(?P<' . UpdateWishlistItemQuantityForm::PARAM_ITEM_ID . '>\d+)';
	}

	/**
	 * {@inheritdoc}
	 */
	public function get_route_args(): array {
		return [
			UpdateWishlistItemQuantityForm::PARAM_ITEM_ID       => [
				'description'       => null,
				'required'          => true,
				'sanitize_callback' => function ( $value ) {
					return (int) $value;
				},
			],
			UpdateWishlistItemQuantityForm::PARAM_ITEM_QUANTITY => [
				'description'       => null,
				'required'          => false,
				'validate_callback' => function ( $value ) {
					return ( preg_match( '/^[0-9]+$/', $value ) );
				},
			],
		];
	}

	/**
	 * {@inheritdoc}
	 *
	 * @throws InvalidFormRequestId
	 * @throws UnauthorizedRequest
	 * @throws InvalidFormData
	 */
	public function get_route_response( \WP_REST_Request $request ) {
		if ( $request->get_param( UpdateWishlistItemQuantityForm::PARAM_ITEM_QUANTITY ) !== null ) {
			( new UpdateWishlistItemQuantityForm( $this->user_auth_manager, $this->wishlist_repository, $this->wishlist_item_repository ) )
				->process_request( $request->get_params() );
		}

		return new \WP_REST_Response(
			[
				'status' => true,
			],
			200
		);
	}
}
