<?php
/**
 * @package WPDesk\FlexibleWishlist
 */

namespace WPDesk\FlexibleWishlist\Endpoint;

use WPDesk\FlexibleWishlist\Archive\UserDataGenerator;
use WPDesk\FlexibleWishlist\Exception\InvalidFormRequestId;
use WPDesk\FlexibleWishlist\Exception\InvalidSettingsOptionKey;
use WPDesk\FlexibleWishlist\Exception\UnauthorizedRequest;
use WPDesk\FlexibleWishlist\Form\ToggleWishlistItemForm;
use WPDesk\FlexibleWishlist\Repository\SettingsRepository;
use WPDesk\FlexibleWishlist\Repository\UserRepository;
use WPDesk\FlexibleWishlist\Repository\WishlistItemRepository;
use WPDesk\FlexibleWishlist\Repository\WishlistRepository;
use WPDesk\FlexibleWishlist\Service\UserAuthManager;

/**
 * {@inheritdoc}
 */
class WishlistItemToggleEndpoint extends EndpointBase {

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

	/**
	 * @var UserDataGenerator
	 */
	private $user_data_generator;

	public function __construct(
		UserAuthManager $user_auth_manager,
		UserRepository $user_repository,
		SettingsRepository $settings_repository,
		WishlistRepository $wishlist_repository,
		WishlistItemRepository $wishlist_item_repository,
		UserDataGenerator $user_data_generator = null
	) {
		$this->user_auth_manager        = $user_auth_manager;
		$this->user_repository          = $user_repository;
		$this->wishlist_repository      = $wishlist_repository;
		$this->wishlist_item_repository = $wishlist_item_repository;
		$this->user_data_generator      = $user_data_generator ?: new UserDataGenerator( $user_auth_manager, $settings_repository, $wishlist_repository );
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
		return 'items-toggle';
	}

	/**
	 * {@inheritdoc}
	 */
	public function get_route_args(): array {
		return [
			ToggleWishlistItemForm::PARAM_WISHLIST_ID => [
				'description'       => null,
				'required'          => true,
				'default'           => '',
				'sanitize_callback' => function ( $value ) {
					return ( $value === '' ) ? null : (int) $value;
				},
			],
			ToggleWishlistItemForm::PARAM_ITEM_ID     => [
				'description'       => null,
				'default'           => '',
				'sanitize_callback' => function ( $value ) {
					return (int) $value;
				},
			],
			ToggleWishlistItemForm::PARAM_ITEM_IDEA   => [
				'description'       => null,
				'default'           => '',
				'sanitize_callback' => function ( $value ) {
					return sanitize_text_field( $value );
				},
			],
			ToggleWishlistItemForm::PARAM_ITEM_STATUS => [
				'description'       => null,
				'required'          => true,
				'sanitize_callback' => function ( $value ) {
					return (bool) $value;
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
		( new ToggleWishlistItemForm( $this->user_auth_manager, $this->user_repository, $this->wishlist_repository, $this->wishlist_item_repository ) )
			->process_request( $request->get_params() );

		return new \WP_REST_Response(
			[
				'data'   => $this->user_data_generator->get_user_data(),
				'status' => true,
			],
			200
		);
	}
}
