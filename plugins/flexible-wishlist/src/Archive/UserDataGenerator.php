<?php

namespace WPDesk\FlexibleWishlist\Archive;

use WPDesk\FlexibleWishlist\Exception\InvalidSettingsOptionKey;
use WPDesk\FlexibleWishlist\Repository\SettingsRepository;
use WPDesk\FlexibleWishlist\Repository\WishlistRepository;
use WPDesk\FlexibleWishlist\Service\UrlGenerator;
use WPDesk\FlexibleWishlist\Service\UserAuthManager;
use WPDesk\FlexibleWishlist\Settings\Option\TextArchiveUrlOption;

/**
 * Generates data displayed on shop pages needed to display pop-up wishlists.
 */
class UserDataGenerator {

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
	public function get_user_data(): array {
		$user      = $this->user_auth_manager->get_user();
		$wishlists = $this->wishlist_repository->get_by_user( $user->get_id() );
		$js_data   = [
			'wishlists' => [],
		];

		foreach ( $wishlists as $wishlist ) {
			$wishlist_data = [
				'id'         => $wishlist->get_id(),
				'url'        => $this->url_generator->generate( $wishlist->get_list_token() ),
				'name'       => $wishlist->get_name(),
				'is_default' => $wishlist->get_default_status(),
				'products'   => [],
				'ideas'      => [],
			];

			foreach ( $wishlist->get_items() as $wishlist_item ) {
				if ( $wishlist_item->get_product_id() !== null ) {
					$wishlist_data['products'][] = $wishlist_item->get_product_id();
				} elseif ( $wishlist_item->get_product_desc() !== null ) {
					$wishlist_data['ideas'][] = $wishlist_item->get_product_desc();
				}
			}

			$js_data['wishlists'][] = $wishlist_data;
		}

		return $js_data;
	}
}
