<?php

namespace WPDesk\FlexibleWishlist\Settings;

use FlexibleWishlistVendor\WPDesk\PluginBuilder\Plugin\Hookable;
use FlexibleWishlistVendor\WPDesk_Plugin_Info;
use WPDesk\FlexibleWishlist\Exception\TemplateLoadingFailed;
use WPDesk\FlexibleWishlist\Repository\SettingsRepository;
use WPDesk\FlexibleWishlist\Service\TemplateLoader;

/**
 * Generates a plugin settings page.
 */
class SettingsPageGenerator implements Hookable {

	const MENU_PAGE_SLUG    = 'flexible-wishlist-settings';
	const SUBMIT_VALUE      = '_fw_save_settings';
	const NONCE_PARAM_KEY   = '_fw_nonce';
	const NONCE_PARAM_VALUE = 'fw_save_settings';

	/**
	 * @var WPDesk_Plugin_Info
	 */
	private $plugin_info;

	/**
	 * @var TemplateLoader
	 */
	private $template_loader;

	/**
	 * @var SettingsRepository
	 */
	private $settings_repository;

	public function __construct(
		WPDesk_Plugin_Info $plugin_info,
		TemplateLoader $template_loader,
		SettingsRepository $settings_repository
	) {
		$this->plugin_info         = $plugin_info;
		$this->template_loader     = $template_loader;
		$this->settings_repository = $settings_repository;
	}

	/**
	 * {@inheritdoc}
	 */
	public function hooks() {
		add_action( 'admin_menu', [ $this, 'create_admin_page' ] );
		add_action( 'in_admin_footer', [ $this, 'load_plugin_footer' ] );
		add_filter( 'admin_enqueue_scripts', [ $this, 'load_admin_assets' ] );
	}

	/**
	 * @return void
	 * @internal
	 */
	public function create_admin_page() {
		add_menu_page(
			'',
			__( 'Wishlists', 'flexible-wishlist' ),
			'manage_options',
			self::MENU_PAGE_SLUG,
			'',
			'data:image/svg+xml;base64,' . base64_encode( '<svg width="20" height="20" viewBox="0 0 174.7 175.9" xmlns="http://www.w3.org/2000/svg"><path fill="black" d="m100.6 0c-1 0-1.9 0.3-2.7 0.8-2.6 1.5-3.5 4.9-1.9 7.5l21.2 35.8h-71l20.8-35.2c1.5-2.6 0.7-6-1.9-7.5s-6-0.7-7.5 1.9l-24.1 40.8h-28c-3 0-5.5 2.5-5.5 5.5v21.2c0 3 2.5 5.5 5.5 5.5h16.4l12.5 79c0.4 2.6 2.7 4.6 5.4 4.6h59.2l-12-11.2-42.5-0.1-11.5-72.3h124.7c3 0 5.5-2.5 5.5-5.5v-21.2c0-3-2.5-5.5-5.5-5.5h-27.9c-0.1-0.1-0.1-0.3-0.2-0.4l-24.2-41c-0.7-1.3-2-2.2-3.4-2.5-0.5-0.2-1-0.2-1.4-0.2zm-89.6 55.1h141.3v10.1h-141.3v-10.1z"/><path fill="black" d="m92.8 87.2c-12.9 2.1-22.4 13.4-22.4 26.4v1.3c0 7.8 3.3 15.3 9 20.7l43.1 40.3 43.2-40.3c5.7-5.3 9-12.9 9-20.7v-1.3c0-13.1-9.5-24.3-22.4-26.4-8.5-1.4-17.2 1.3-23.3 7.2l-6.6 6.6-6.3-6.5-0.1-0.1c-6-5.9-14.6-8.6-23.2-7.2zm1.8 10.8c5.1-0.8 10.2 0.8 13.9 4.3l13.8 14.4 3.9-4 10.4-10.4c3.6-3.5 8.8-5.1 13.9-4.3 7.6 1.3 13.2 7.8 13.2 15.6v1.3c0 4.8-2 9.4-5.5 12.7l-35.7 33.3-35.6-33.3c-3.5-3.3-5.5-7.9-5.5-12.7v-1.3c0-7.8 5.6-14.3 13.2-15.6z"/></svg>' ), // phpcs:ignore WordPress.PHP.DiscouragedPHPFunctions.obfuscation_base64_encode
			58
		);

		global $admin_page_hooks;
		$admin_page_hooks[ self::MENU_PAGE_SLUG ] = 'flexible-wishlist';

		add_submenu_page(
			self::MENU_PAGE_SLUG,
			__( 'Flexible Wishlist Settings', 'flexible-wishlist' ),
			__( 'Settings', 'flexible-wishlist' ),
			'manage_options',
			self::MENU_PAGE_SLUG,
			[ $this, 'print_settings_page' ]
		);

		if ( ! is_plugin_active( 'flexible-wishlist-analytics/flexible-wishlist-analytics.php' ) ) {
			add_submenu_page(
				self::MENU_PAGE_SLUG,
				'',
				'<span style="color:#FF9743;font-weight: bold">' . esc_html__( 'Upgrade to PRO', 'flexible-wishlist' ) . '</span>',
				'manage_options',
				__( 'https://wpde.sk/fw-settings-admin-menu-upgrade', 'flexible-wishlist' )
			);
		}
	}

	/**
	 * @return void
	 * @throws TemplateLoadingFailed
	 */
	public function print_settings_page() {
		if ( isset( $_POST[ self::SUBMIT_VALUE ] ) ) {
			$nonce_value = sanitize_text_field( wp_unslash( $_POST[ self::NONCE_PARAM_KEY ] ?? '' ) );
			if ( ! wp_verify_nonce( $nonce_value, self::NONCE_PARAM_VALUE ) ) {
				wp_die( esc_html( __( 'Sorry, you are not allowed to save plugin settings.', 'flexible-wishlist' ) ) );
			}
			$this->settings_repository->save_values( $_POST );
		}

		$this->template_loader->load_template(
			'admin-settings',
			[
				'settings_groups' => $this->settings_repository->get_options(),
				'settings_values' => $this->settings_repository->get_values(),
				'submit_value'    => self::SUBMIT_VALUE,
				'nonce_key'       => self::NONCE_PARAM_KEY,
				'nonce_value'     => wp_create_nonce( self::NONCE_PARAM_VALUE ),
			]
		);
	}

	/**
	 * @return void
	 * @throws TemplateLoadingFailed
	 */
	public function load_plugin_footer() {
		if ( ! apply_filters( 'flexible_wishlist/load_plugin_footer', ( strpos( $_GET['page'] ?? '', 'flexible-wishlist-' ) !== false ) ) ) { // phpcs:ignore WordPress.Security
			return;
		}

		$this->template_loader->load_template(
			'admin-footer',
			[]
		);
	}

	/**
	 * @return void
	 * @internal
	 */
	public function load_admin_assets() {
		$version = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? time() : $this->plugin_info->get_version();

		wp_register_style(
			'flexible-wishlist-admin',
			untrailingslashit( $this->plugin_info->get_plugin_url() ) . '/assets/css/admin.css',
			[],
			(string) $version
		);
		wp_enqueue_style( 'flexible-wishlist-admin' );

		wp_register_script(
			'flexible-wishlist-admin',
			untrailingslashit( $this->plugin_info->get_plugin_url() ) . '/assets/js/admin.js',
			[],
			(string) $version,
			true
		);
		wp_enqueue_script( 'flexible-wishlist-admin' );
	}
}
