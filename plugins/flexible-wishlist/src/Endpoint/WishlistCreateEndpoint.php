<?php
/**
 * @package WPDesk\FlexibleWishlist
 */

namespace WPDesk\FlexibleWishlist\Endpoint;

use WPDesk\FlexibleWishlist\Archive\UserDataGenerator;
use WPDesk\FlexibleWishlist\Exception\InvalidFormData;
use WPDesk\FlexibleWishlist\Exception\InvalidSettingsOptionKey;
use WPDesk\FlexibleWishlist\Form\CreateWishlistForm;
use WPDesk\FlexibleWishlist\Repository\SettingsRepository;
use WPDesk\FlexibleWishlist\Repository\UserRepository;
use WPDesk\FlexibleWishlist\Repository\WishlistRepository;
use WPDesk\FlexibleWishlist\Service\UserAuthManager;

/**
 * {@inheritdoc}
 */
class WishlistCreateEndpoint extends EndpointBase {

	/**
	 * @var UserAuthManager
	 */
	private $user_auth_manager;

	/**
	 * @var UserDataGenerator
	 */
	private $user_data_generator;

	/**
	 * @var WishlistRepository
	 */
	private $wishlist_repository;

	/**
	 * @var UserRepository
	 */
	private $user_repository;

	public function __construct(
		UserAuthManager $user_auth_manager,
		SettingsRepository $settings_repository,
		WishlistRepository $wishlist_repository,
		UserRepository $user_repository,
		UserDataGenerator $user_data_generator = null
	) {
		$this->user_auth_manager   = $user_auth_manager;
		$this->wishlist_repository = $wishlist_repository;
		$this->user_repository     = $user_repository;
		$this->user_data_generator = $user_data_generator ?: new UserDataGenerator( $user_auth_manager, $settings_repository, $wishlist_repository );
	}

	/**
	 * @return string
	 */
	public function get_route_method_type(): string {
		return \WP_REST_Server::CREATABLE;
	}

	/**
	 * {@inheritdoc}
	 */
	public static function get_route_name(): string {
		return 'wishlists';
	}

	/**
	 * {@inheritdoc}
	 */
	public function get_route_args(): array {
		return [
			CreateWishlistForm::PARAM_WISHLIST_NAME => [
				'description'       => null,
				'required'          => true,
				'sanitize_callback' => function ( $value ) {
					return sanitize_text_field( $value );
				},
				'validate_callback' => function ( $value ) {
					return ( $value !== '' );
				},
			],
		];
	}

	/**
	 * {@inheritdoc}
	 *
	 * @throws InvalidFormData
	 * @throws InvalidSettingsOptionKey
	 */
	public function get_route_response( \WP_REST_Request $request ) {
		( new CreateWishlistForm( $this->user_auth_manager, $this->user_repository, $this->wishlist_repository ) )
			->process_request( $request->get_params() );

		return new \WP_REST_Response(
			[
				'status' => true,
				'data'   => $this->user_data_generator->get_user_data(),
			],
			200
		);
	}
}
