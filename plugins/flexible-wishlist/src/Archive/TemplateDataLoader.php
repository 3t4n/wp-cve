<?php

namespace WPDesk\FlexibleWishlist\Archive;

use WPDesk\FlexibleWishlist\Endpoint\WishlistItemRemoveEndpoint;
use WPDesk\FlexibleWishlist\Endpoint\WishlistItemUpdateEndpoint;
use WPDesk\FlexibleWishlist\Endpoint\WishlistUpdateEndpoint;
use WPDesk\FlexibleWishlist\Exception\InvalidSettingsOptionKey;
use WPDesk\FlexibleWishlist\Form\CreateWishlistForm;
use WPDesk\FlexibleWishlist\Form\CreateWishlistItemForm;
use WPDesk\FlexibleWishlist\Form\RemoveWishlistForm;
use WPDesk\FlexibleWishlist\Form\ToggleDefaultWishlistForm;
use WPDesk\FlexibleWishlist\Model\Wishlist;
use WPDesk\FlexibleWishlist\Repository\SettingsRepository;
use WPDesk\FlexibleWishlist\Repository\WishlistRepository;
use WPDesk\FlexibleWishlist\Service\UrlGenerator;
use WPDesk\FlexibleWishlist\Service\UserAuthManager;
use WPDesk\FlexibleWishlist\Settings\Option\ActiveSocialIconsOption;
use WPDesk\FlexibleWishlist\Settings\Option\ItemQuantityEnabledOption;
use WPDesk\FlexibleWishlist\Settings\Option\TextArchiveBackOption;
use WPDesk\FlexibleWishlist\Settings\Option\TextArchiveUrlOption;
use WPDesk\FlexibleWishlist\Settings\Option\TextCreateIdeaButtonOption;
use WPDesk\FlexibleWishlist\Settings\Option\TextCreateIdeaInputOption;
use WPDesk\FlexibleWishlist\Settings\Option\TextCreateWishlistButtonOption;
use WPDesk\FlexibleWishlist\Settings\Option\TextCreateWishlistInputOption;

/**
 * Generates data displayed on wishlist pages.
 */
class TemplateDataLoader {

	/**
	 * @var UserAuthManager
	 */
	private $user_auth_manager;

	/**
	 * @var SettingsRepository
	 */
	private $settings_repository;

	/**
	 * @var WishlistRepository
	 */
	private $wishlist_repository;

	/**
	 * @var UrlGenerator
	 */
	private $url_generator;

	public function __construct(
		UserAuthManager $user_auth_manager,
		SettingsRepository $settings_repository,
		WishlistRepository $wishlist_repository,
		UrlGenerator $url_generator = null
	) {
		$this->user_auth_manager   = $user_auth_manager;
		$this->settings_repository = $settings_repository;
		$this->wishlist_repository = $wishlist_repository;
		$this->url_generator       = $url_generator ?? new UrlGenerator( $settings_repository );
	}

	/**
	 * @return mixed[]
	 * @throws InvalidSettingsOptionKey
	 */
	public function get_archive_args(): array {
		$user      = $this->user_auth_manager->get_user( true );
		$wishlists = $this->wishlist_repository->get_by_user( $user->get_id(), true );

		$args = [
			'action_toggle_default'  => ToggleDefaultWishlistForm::ACTION_NAME,
			'action_remove_wishlist' => RemoveWishlistForm::ACTION_NAME,
			'action_create_wishlist' => CreateWishlistForm::ACTION_NAME,
			'i18n'                   => [
				'create_wishlist_input'  => $this->settings_repository->get_value( TextCreateWishlistInputOption::FIELD_NAME ),
				'create_wishlist_button' => $this->settings_repository->get_value( TextCreateWishlistButtonOption::FIELD_NAME ),
			],
			'wishlists'              => [],
		];

		foreach ( $wishlists as $wishlist ) {
			$args['wishlists'][] = [
				'id'                   => $wishlist->get_id(),
				'name'                 => $wishlist->get_name(),
				'url'                  => $this->url_generator->generate( $wishlist->get_list_token() ),
				'is_default'           => $wishlist->get_default_status(),
				'items_count'          => count( $wishlist->get_items() ),
				'created_at'           => $wishlist->get_created_at()->format( 'F j, Y' ),
				'endpoint_update_name' => WishlistUpdateEndpoint::get_route_url( $wishlist->get_id() ),
			];
		}
		return $args;
	}

	/**
	 * @param Wishlist $wishlist .
	 *
	 * @return mixed[]
	 * @throws InvalidSettingsOptionKey
	 */
	public function get_single_args( Wishlist $wishlist ): array {
		$args = [
			'single_url'         => $this->url_generator->generate( $wishlist->get_list_token() ),
			'archive_url'        => $this->url_generator->generate(),
			'wishlist_id'        => $wishlist->get_id(),
			'is_author'          => ( $this->user_auth_manager->get_user()->get_id() === $wishlist->get_user_id() ),
			'quantity_enabled'   => $this->settings_repository->get_value( ItemQuantityEnabledOption::FIELD_NAME ),
			'action_create_item' => CreateWishlistItemForm::ACTION_NAME,
			'allowed_socials'    => $this->settings_repository->get_value( ActiveSocialIconsOption::FIELD_NAME ),
			'i18n'               => [
				'create_idea_input'  => $this->settings_repository->get_value( TextCreateIdeaInputOption::FIELD_NAME ),
				'create_idea_button' => $this->settings_repository->get_value( TextCreateIdeaButtonOption::FIELD_NAME ),
				'back_to_archive'    => $this->settings_repository->get_value( TextArchiveBackOption::FIELD_NAME ),
			],
			'items'              => [],
		];

		foreach ( $wishlist->get_items() as $item ) {
			$product_id      = $item->get_product_id();
			$product         = ( $product_id !== null ) ? wc_get_product( $product_id ) : null;
			$add_to_cart_url = ( $product ) ? $product->add_to_cart_url() : null;
			if ( ( $add_to_cart_url !== null ) && strpos( $add_to_cart_url, 'add-to-cart' ) ) {
				$add_to_cart_url = add_query_arg( 'quantity', $item->get_quantity(), $add_to_cart_url );
			}

			$args['items'][] = [
				'id'                       => $item->get_id(),
				'product_id'               => $item->get_product_id(),
				'product_desc'             => $item->get_product_desc(),
				'product'                  => ( $item->get_product_id() !== null ) ? wc_get_product( $item->get_product_id() ) : null,
				'add_to_cart_url'          => $add_to_cart_url,
				'quantity'                 => $item->get_quantity(),
				'created_at'               => $item->get_created_at()->format( 'F j, Y' ),
				'endpoint_update_quantity' => WishlistItemUpdateEndpoint::get_route_url( $item->get_id() ),
				'endpoint_remove_item'     => WishlistItemRemoveEndpoint::get_route_url( $item->get_id() ),
			];
		}
		return $args;
	}
}
