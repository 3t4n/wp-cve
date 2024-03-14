<?php

declare( strict_types=1 );

namespace WPDesk\ShopMagic\Admin;

use WPDesk\ShopMagic\Admin\Settings\GeneralSettings;
use WPDesk\ShopMagic\Admin\Settings\ModulesInfoContainer;
use WPDesk\ShopMagic\Admin\Welcome\Welcome;
use WPDesk\ShopMagic\Components\UrlGenerator\UrlGenerator;
use WPDesk\ShopMagic\Helper\PluginBag;

final class Admin {

	private const SCRIPT_HANDLE = 'shopmagic-spa';

	/** @var Manifest */
	private $manifest;

	/** @var ModulesInfoContainer */
	private $modules_container;

	/** @var PluginBag */
	private $plugin_bag;

	/** @var UrlGenerator */
	private $url_generator;

	public function __construct(
		ModulesInfoContainer $modules_container,
		PluginBag $plugin_bag,
		Manifest $manifest,
		UrlGenerator $url_generator
	) {
		$this->modules_container = $modules_container;
		$this->plugin_bag        = $plugin_bag;
		$this->manifest          = $manifest;
		$this->url_generator     = $url_generator;
	}

	public function hooks(): void {
		add_action( 'admin_init', [ new Welcome(), 'safe_welcome_redirect' ] );

		add_action( 'admin_menu', [ new GeneralAdminPage(), 'register' ] );

		add_action(
			'admin_enqueue_scripts',
			function (): void {
				$this->admin_scripts();
			}
		);

		add_filter(
			'script_loader_tag',
			function ( string $tag, string $handle ): string {
				return $this->load_js_as_module( $tag, $handle );
			},
			10,
			2
		);

		add_filter(
			'script_loader_src',
			static function ( $src ) {
				if ( ! is_string( $src ) ) {
					return $src;
				}

				if ( strpos( $src, '?ver=' ) && strpos( $src, 'localhost' ) ) {
					return remove_query_arg( 'ver', $src );
				}

				return $src;
			}
		);
	}

	/**
	 * Includes admin scripts in admin area
	 */
	private function admin_scripts(): void {
		if ( ! $this->should_enqueue_scripts() ) {
			return;
		}

		wp_register_style(
			'shopmagic-admin-spa',
			$this->manifest->get_css_for( 'src/main.ts' ),
			[],
			SHOPMAGIC_VERSION
		);

		wp_register_script(
			self::SCRIPT_HANDLE,
			getenv( 'SM_ASSETS_URL' ) ? getenv( 'SM_ASSETS_URL' ) . 'src/main.ts' : $this->manifest->get( 'src/main.ts' ),
			[ 'wp-i18n' ],
			SHOPMAGIC_VERSION,
			false
		);

		wp_set_script_translations( self::SCRIPT_HANDLE, 'shopmagic-for-woocommerce' );

		wp_localize_script(
			self::SCRIPT_HANDLE,
			'ShopMagic',
			[
				'pluginUrl'                => $this->plugin_bag->get_url(),
				'baseUrl'                  => $this->url_generator->generate(),
				'nonce'                    => wp_create_nonce( 'wp_rest' ),
				'user'                     => [
					'name'   => wp_get_current_user()->first_name,
					'email'  => wp_get_current_user()->user_email,
					'locale' => get_user_locale(),
				],
				'proEnabled'               => $this->plugin_bag->pro_enabled(),
				'emailTrackingEnabled'     => filter_var( GeneralSettings::get_option( 'enable_email_tracking', true ), FILTER_VALIDATE_BOOLEAN ) ? 1 : 0,
				'modules'                  => $this->find_active_modules(),
				// If permalink structure is plain, option is empty, i.e. falsy.
				'permalinkStructure'       => get_option( 'permalink_structure', '' ) ? 'slug' : 'plain',
				'requestCompatibilityMode' => GeneralSettings::get_option( 'request_compatibility_mode', false ),
			]
		);

		wp_enqueue_script( self::SCRIPT_HANDLE );
		wp_enqueue_style( 'shopmagic-admin-spa' );

		wp_enqueue_media();
	}

	private function should_enqueue_scripts(): bool {
		$current_screen = get_current_screen();
		if ( ! $current_screen instanceof \WP_Screen ) {
			return false;
		}

		if ( $current_screen->id === 'toplevel_page_shopmagic-admin' ) {
			return true;
		}

		return false;
	}

	private function find_active_modules(): array {
		return $this->modules_container->get_active_modules();
	}

	private function load_js_as_module( string $tag, string $handle ): string {
		if ( self::SCRIPT_HANDLE === $handle ) {
			return preg_replace( '#<script #', '<script type="module" ', $tag );
		}

		return $tag;
	}
}
