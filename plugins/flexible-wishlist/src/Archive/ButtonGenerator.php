<?php

namespace WPDesk\FlexibleWishlist\Archive;

use FlexibleWishlistVendor\WPDesk\PluginBuilder\Plugin\Hookable;
use WPDesk\FlexibleWishlist\Exception\InvalidSettingsOptionKey;
use WPDesk\FlexibleWishlist\Exception\TemplateLoadingFailed;
use WPDesk\FlexibleWishlist\Repository\SettingsRepository;
use WPDesk\FlexibleWishlist\Service\TemplateLoader;
use WPDesk\FlexibleWishlist\Service\UserAuthManager;
use WPDesk\FlexibleWishlist\Settings\Option\IconPositionListOption;
use WPDesk\FlexibleWishlist\Settings\Option\IconPositionProductOption;
use WPDesk\FlexibleWishlist\Settings\Option\IconTypeOption;
use WPDesk\FlexibleWishlist\Settings\Option\TextAddedItemOption;
use WPDesk\FlexibleWishlist\Settings\Option\TextAddItemOption;

/**
 * Displays an "Add to wishlist" button for WooCommerce product.
 */
class ButtonGenerator implements Hookable {

	/**
	 * @var TemplateLoader
	 */
	private $template_loader;

	/**
	 * @var UserAuthManager
	 */
	private $user_auth_manager;

	/**
	 * @var SettingsRepository
	 */
	private $settings_repository;

	public function __construct(
		TemplateLoader $template_loader,
		UserAuthManager $user_auth_manager,
		SettingsRepository $settings_repository
	) {
		$this->template_loader     = $template_loader;
		$this->user_auth_manager   = $user_auth_manager;
		$this->settings_repository = $settings_repository;
	}

	/**
	 * {@inheritdoc}
	 *
	 * @throws InvalidSettingsOptionKey
	 */
	public function hooks() {
		$shop_loop_position    = $this->settings_repository->get_value( IconPositionListOption::FIELD_NAME );
		$product_page_position = $this->settings_repository->get_value( IconPositionProductOption::FIELD_NAME );

		switch ( $shop_loop_position ) {
			case IconPositionListOption::VALUE_ON_IMAGE:
				add_action( 'woocommerce_before_shop_loop_item', [ $this, 'add_button_on_product_image' ], 0 );
				break;
			case IconPositionListOption::VALUE_LEFT_BUTTON:
				add_action( 'woocommerce_after_shop_loop_item', [ $this, 'add_button_on_left_of_add_to_cart_button' ], 6 );
				break;
			case IconPositionListOption::VALUE_RIGHT_BUTTON:
				add_action( 'woocommerce_after_shop_loop_item', [ $this, 'add_button_on_right_of_add_to_cart_button' ], 11 );
				break;
			case IconPositionListOption::VALUE_ABOVE_BUTTON:
				add_action( 'woocommerce_after_shop_loop_item', [ $this, 'add_button_above_add_to_cart_button' ], 5 );
				break;
			case IconPositionListOption::VALUE_BELOW_BUTTON:
				add_action( 'woocommerce_after_shop_loop_item', [ $this, 'add_button_below_add_to_cart_button' ], 12 );
				break;
		}

		switch ( $product_page_position ) {
			case IconPositionProductOption::VALUE_ABOVE_BUTTON:
				add_action( 'woocommerce_before_add_to_cart_form', [ $this, 'add_button_above_add_to_cart_form' ] );
				break;
			case IconPositionProductOption::VALUE_BELOW_BUTTON:
				add_action( 'woocommerce_after_add_to_cart_form', [ $this, 'add_button_below_add_to_cart_form' ] );
				break;
		}
	}

	/**
	 * @return void
	 * @throws InvalidSettingsOptionKey
	 * @throws TemplateLoadingFailed
	 * @internal
	 */
	public function add_button_on_product_image() {
		$this->load_button_template( 'shop-loop/on-image' );
	}

	/**
	 * @return void
	 * @throws InvalidSettingsOptionKey
	 * @throws TemplateLoadingFailed
	 * @internal
	 */
	public function add_button_on_left_of_add_to_cart_button() {
		$this->load_button_template( 'shop-loop/add-to-cart-left' );
	}

	/**
	 * @return void
	 * @throws InvalidSettingsOptionKey
	 * @throws TemplateLoadingFailed
	 * @internal
	 */
	public function add_button_on_right_of_add_to_cart_button() {
		$this->load_button_template( 'shop-loop/add-to-cart-right' );
	}

	/**
	 * @return void
	 * @throws InvalidSettingsOptionKey
	 * @throws TemplateLoadingFailed
	 * @internal
	 */
	public function add_button_above_add_to_cart_button() {
		$this->load_button_template( 'shop-loop/add-to-cart-above' );
	}

	/**
	 * @return void
	 * @throws InvalidSettingsOptionKey
	 * @throws TemplateLoadingFailed
	 * @internal
	 */
	public function add_button_below_add_to_cart_button() {
		$this->load_button_template( 'shop-loop/add-to-cart-below' );
	}

	/**
	 * @return void
	 * @throws InvalidSettingsOptionKey
	 * @throws TemplateLoadingFailed
	 * @internal
	 */
	public function add_button_above_add_to_cart_form() {
		$this->load_button_template( 'product-page/add-to-cart-above' );
	}

	/**
	 * @return void
	 * @throws InvalidSettingsOptionKey
	 * @throws TemplateLoadingFailed
	 * @internal
	 */
	public function add_button_below_add_to_cart_form() {
		$this->load_button_template( 'product-page/add-to-cart-below' );
	}

	/**
	 * @param string $template_path .
	 *
	 * @return void
	 * @throws InvalidSettingsOptionKey
	 * @throws TemplateLoadingFailed
	 */
	private function load_button_template( string $template_path ) {
		global $product;
		$status            = $this->is_product_added( $product->get_id() );
		$icon_type         = $this->settings_repository->get_value( IconTypeOption::FIELD_NAME );
		$text_status_add   = $this->settings_repository->get_value( TextAddItemOption::FIELD_NAME );
		$text_status_added = $this->settings_repository->get_value( TextAddedItemOption::FIELD_NAME );

		$this->template_loader->load_template(
			$template_path,
			[
				'product'           => $product,
				'status'            => $status,
				'text_status_add'   => $text_status_add,
				'text_status_added' => $text_status_added,
				'icon_type'         => $icon_type,
			]
		);
	}

	private function is_product_added( int $product_id ): bool {
		foreach ( $this->user_auth_manager->get_user()->get_wishlists() as $wishlist ) {
			foreach ( $wishlist->get_items() as $wishlist_item ) {
				if ( $wishlist_item->get_product_id() === $product_id ) {
					return true;
				}
			}
		}
		return false;
	}
}
