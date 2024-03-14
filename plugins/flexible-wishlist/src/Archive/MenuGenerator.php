<?php

namespace WPDesk\FlexibleWishlist\Archive;

use FlexibleWishlistVendor\WPDesk\PluginBuilder\Plugin\Hookable;
use WPDesk\FlexibleWishlist\Exception\InvalidSettingsOptionKey;
use WPDesk\FlexibleWishlist\Repository\SettingsRepository;
use WPDesk\FlexibleWishlist\Service\UrlGenerator;
use WPDesk\FlexibleWishlist\Settings\Option\IconTypeOption;
use WPDesk\FlexibleWishlist\Settings\Option\MenuSelectedOption;
use WPDesk\FlexibleWishlist\Settings\Option\TextArchiveTitleOption;
use WPDesk\FlexibleWishlist\Settings\Option\TextArchiveUrlOption;

/**
 * Adds new item from the wishlist archive to the menu.
 */
class MenuGenerator implements Hookable {

	const MENU_FRONT_ITEM_SLUG = 'fw-wishlist';
	const MENU_ADMIN_ITEM_SLUG = 'fw-wishlist-admin';
	const MENU_URL             = '#flexible-wishlist';

	/** @var SettingsRepository */
	private $settings_repository;

	/** @var UrlGenerator */
	private $url_generator;

	public function __construct(
		SettingsRepository $settings_repository,
		UrlGenerator $url_generator = null
	) {
		$this->settings_repository = $settings_repository;
		$this->url_generator       = $url_generator ?? new UrlGenerator( $settings_repository );
	}

	/**
	 * {@inheritdoc}
	 */
	public function hooks() {
		add_filter( 'woocommerce_account_menu_items', [ $this, 'add_menu_to_account' ] );
		add_filter( 'woocommerce_get_endpoint_url', [ $this, 'update_url_for_account_menu_item' ], 10, 2 );
		add_filter( 'woocommerce_account_menu_item_classes', [ $this, 'update_classes_for_account_menu_item' ], 10, 2 );
		add_filter( 'wp_get_nav_menu_items', [ $this, 'add_menu_item' ], 10, 3 );
		add_filter( 'wp_setup_nav_menu_item', [ $this, 'set_menu_item_status_publish' ] );
		add_filter( 'wp_get_nav_menu_items', [ $this, 'update_url_for_menu_item' ], 10, 3 );
	}

	/**
	 * @param object[] $menu_links .
	 *
	 * @return object[]
	 * @throws InvalidSettingsOptionKey
	 * @internal
	 */
	public function add_menu_to_account( array $menu_links ): array {
		if ( is_admin() ) {
			$menu_links[ self::MENU_ADMIN_ITEM_SLUG ] = $this->settings_repository->get_value( TextArchiveTitleOption::FIELD_NAME );
		} else {
			$menu_links = array_merge(
				array_slice( $menu_links, 0, ( count( $menu_links ) - 1 ) ),
				[
					self::MENU_FRONT_ITEM_SLUG => $this->settings_repository->get_value( TextArchiveTitleOption::FIELD_NAME ),
				],
				array_slice( $menu_links, ( count( $menu_links ) - 1 ) )
			);
		}

		return $menu_links;
	}

	/**
	 * @param string $url      .
	 * @param string $endpoint .
	 *
	 * @return string
	 * @throws InvalidSettingsOptionKey
	 * @internal
	 */
	public function update_url_for_account_menu_item( string $url, string $endpoint ): string {
		switch ( $endpoint ) {
			case self::MENU_FRONT_ITEM_SLUG:
				return $this->url_generator->generate();
			case self::MENU_ADMIN_ITEM_SLUG:
				return self::MENU_URL;
		}

		return $url;
	}

	/**
	 * @param string[] $classes  .
	 * @param string   $endpoint .
	 *
	 * @return string[]
	 * @throws InvalidSettingsOptionKey
	 * @internal
	 */
	public function update_classes_for_account_menu_item( array $classes, string $endpoint ): array {
		if ( $endpoint !== self::MENU_FRONT_ITEM_SLUG ) {
			return $classes;
		}

		$classes[] = 'fw-menu-item';
		$classes[] = 'fw-menu-item--' . $this->settings_repository->get_value( IconTypeOption::FIELD_NAME );

		return $classes;
	}

	/**
	 * @param object[] $menu_items .
	 * @param \WP_Term $menu       .
	 * @param mixed[]  $args       .
	 *
	 * @return object[]
	 * @throws InvalidSettingsOptionKey
	 * @internal
	 */
	public function add_menu_item( array $menu_items, \WP_Term $menu, array $args ): array {
		$allowed_menus = $this->settings_repository->get_value( MenuSelectedOption::FIELD_NAME );
		if ( ! in_array( $menu->term_id, $allowed_menus, false ) || isset( $_POST['menu-item-db-id'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Missing
			return $menu_items;
		}

		foreach ( $menu_items as $menu_item ) {
			if ( isset( $menu_item->url ) && ( $menu_item->url === self::MENU_URL ) ) {
				return $menu_items;
			}
		}

		$menu_items[] = (object) [
			'ID'                    => 0,
			'post_author'           => '0',
			'post_date'             => '0000-00-00 00:00:00',
			'post_date_gmt'         => '0000-00-00 00:00:00',
			'post_content'          => '',
			'post_title'            => $this->settings_repository->get_value( TextArchiveTitleOption::FIELD_NAME ),
			'post_excerpt'          => '',
			'post_status'           => 'publish',
			'comment_status'        => 'closed',
			'ping_status'           => 'closed',
			'post_password'         => '',
			'post_name'             => $this->settings_repository->get_value( TextArchiveUrlOption::FIELD_NAME ),
			'to_ping'               => '',
			'pinged'                => '',
			'post_modified'         => '0000-00-00 00:00:00',
			'post_modified_gmt'     => '0000-00-00 00:00:00',
			'post_content_filtered' => '',
			'post_parent'           => 0,
			'guid'                  => '',
			'menu_order'            => 0,
			'post_type'             => 'nav_menu_item',
			'post_mime_type'        => '',
			'comment_count'         => '0',
			'filter'                => 'raw',
			'db_id'                 => 0,
			'menu_item_parent'      => 0,
			'object_id'             => 0,
			'object'                => 'custom',
			'type'                  => 'custom',
			'type_label'            => '',
			'title'                 => $this->settings_repository->get_value( TextArchiveTitleOption::FIELD_NAME ),
			'url'                   => self::MENU_URL,
			'target'                => '',
			'attr_title'            => '',
			'description'           => '',
			'classes'               => [],
			'xfn'                   => '',
		];

		return $menu_items;
	}

	/**
	 * @param object $menu_item .
	 *
	 * @return object
	 * @internal
	 */
	public function set_menu_item_status_publish( $menu_item ) {
		if ( isset( $menu_item->url ) && ( $menu_item->url === self::MENU_URL ) ) {
			$menu_item->post_status = 'publish';
		}

		return $menu_item;
	}

	/**
	 * @param object[] $menu_items .
	 * @param \WP_Term $menu       .
	 * @param mixed[]  $args       .
	 *
	 * @return object[]
	 * @throws InvalidSettingsOptionKey
	 */
	public function update_url_for_menu_item( array $menu_items, \WP_Term $menu, array $args ): array {
		if ( ! isset( $args['post_status'] ) || ( $args['post_status'] !== 'publish' ) ) {
			return $menu_items;
		}

		foreach ( $menu_items as $item_index => $menu_item ) {
			if ( isset( $menu_item->url ) && ( $menu_item->url === self::MENU_URL ) ) {
				$menu_items[ $item_index ]->title = $this->settings_repository->get_value( TextArchiveTitleOption::FIELD_NAME );
				$menu_items[ $item_index ]->url   = $this->url_generator->generate();
			}
		}

		return $menu_items;
	}
}
