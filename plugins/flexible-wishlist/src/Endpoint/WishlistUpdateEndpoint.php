<?php
/**
 * @package WPDesk\FlexibleWishlist
 */

namespace WPDesk\FlexibleWishlist\Endpoint;

use WPDesk\FlexibleWishlist\Exception\InvalidFormRequestId;
use WPDesk\FlexibleWishlist\Exception\InvalidSettingsOptionKey;
use WPDesk\FlexibleWishlist\Exception\UnauthorizedRequest;
use WPDesk\FlexibleWishlist\Form\UpdateWishlistNameForm;
use WPDesk\FlexibleWishlist\Repository\WishlistRepository;
use WPDesk\FlexibleWishlist\Service\UserAuthManager;

/**
 * {@inheritdoc}
 */
class WishlistUpdateEndpoint extends EndpointBase {

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
	 * @return string
	 */
	public function get_route_method_type(): string {
		return \WP_REST_Server::EDITABLE;
	}

	/**
	 * {@inheritdoc}
	 */
	public static function get_route_name(): string {
		return 'wishlists/(?P<' . UpdateWishlistNameForm::PARAM_WISHLIST_ID . '>\d+)';
	}

	/**
	 * {@inheritdoc}
	 */
	public function get_route_args(): array {
		return [
			UpdateWishlistNameForm::PARAM_WISHLIST_ID   => [
				'description'       => null,
				'required'          => true,
				'sanitize_callback' => function ( $value ) {
					return (int) $value;
				},
			],
			UpdateWishlistNameForm::PARAM_WISHLIST_NAME => [
				'description'       => null,
				'required'          => true,
				'default'           => '',
				'sanitize_callback' => function ( $value ) {
					return sanitize_text_field( $value );
				},
			],
		];
	}

	/**
	 * {@inheritdoc}
	 *
	 * @throws InvalidFormRequestId
	 * @throws InvalidSettingsOptionKey
	 * @throws UnauthorizedRequest
	 */
	public function get_route_response( \WP_REST_Request $request ) {
		( new UpdateWishlistNameForm( $this->user_auth_manager, $this->wishlist_repository ) )
			->process_request( $request->get_params() );

		return new \WP_REST_Response(
			[
				'status' => true,
			],
			200
		);
	}
}
