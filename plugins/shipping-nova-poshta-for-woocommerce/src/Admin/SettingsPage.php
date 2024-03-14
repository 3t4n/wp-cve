<?php
/**
 * Settings page
 *
 * @package   Shipping-Nova-Poshta-For-Woocommerce
 * @author    WP Unit
 * @link      http://wp-unit.com/
 * @copyright Copyright (c) 2020
 * @license   GPL-2.0+
 * @wordpress-plugin
 */

namespace NovaPoshta\Admin;

use Exception;
use NovaPoshta\Main;
use NovaPoshta\Api\Api;
use NovaPoshta\Notice\Notice;
use NovaPoshta\License\License;
use NovaPoshta\Settings\Settings;
use NovaPoshta\Cache\FactoryCache;

/**
 * Class SettingsPage
 *
 * @package NovaPoshta\Admin
 */
class SettingsPage {

	/**
	 * API for Nova Poshta API.
	 *
	 * @var Api
	 */
	private $api;

	/**
	 * Plugin settings.
	 *
	 * @var Settings
	 */
	private $settings;

	/**
	 * Plugin license.
	 *
	 * @var License
	 */
	private $license;

	/**
	 * Cache.
	 *
	 * @var FactoryCache
	 */
	private $factory_cache;


	/**
	 * Admin constructor.
	 *
	 * @param Api          $api           API for Nova Poshta API.
	 * @param Settings     $settings      Plugin settings.
	 * @param License      $license       License.
	 * @param FactoryCache $factory_cache Cache.
	 */
	public function __construct( Api $api, Settings $settings, License $license, FactoryCache $factory_cache ) {

		$this->api           = $api;
		$this->settings      = $settings;
		$this->license       = $license;
		$this->factory_cache = $factory_cache;
	}

	/**
	 * Hooks.
	 */
	public function hooks() {

		add_action( 'admin_menu', [ $this, 'add_menu' ] );
		add_action( 'admin_init', [ $this, 'register_setting' ] );
		add_filter( 'pre_update_option_shipping-nova-poshta-for-woocommerce', [ $this, 'merge_settings' ], 1000, 2 );
		add_action( 'shipping_nova_poshta_for_woocommerce_settings_page_general_tab', [ $this, 'general_tab' ] );
		add_action( 'shipping_nova_poshta_for_woocommerce_settings_page_sender_tab', [ $this, 'sender_tab' ] );
		add_action( 'shipping_nova_poshta_for_woocommerce_print_notice', [ $this, 'notices' ] );
		add_action( 'shipping_nova_poshta_for_woocommerce_upgraded', [ $this, 'load_cities' ] );
	}

	/**
	 * Register settings
	 */
	public function register_setting() {

		register_setting( Main::PLUGIN_SLUG, Main::PLUGIN_SLUG );
	}

	/**
	 * Register page option in menu
	 */
	public function add_menu() {

		add_menu_page(
			Main::PLUGIN_NAME,
			Main::PLUGIN_NAME,
			'manage_options',
			Main::PLUGIN_SLUG,
			[
				$this,
				'page_options',
			],
			NOVA_POSHTA_URL . 'assets/build/img/nova-poshta.svg',
			58
		);
	}

	/**
	 * View for page options
	 *
	 * @throws Exception Invalid DateTime.
	 */
	public function page_options() {

		$url = get_admin_url( null, 'admin.php?page=' . Main::PLUGIN_SLUG );

		$active_tab = $this->get_active_tab();
		$tabs       = (array) apply_filters(
			'shipping_nova_poshta_for_woocommerce_admin_settings_page_tabs',
			[
				'general' => esc_html__( 'General Settings', 'shipping-nova-poshta-for-woocommerce' ),
				'sender'  => esc_html__( 'Sender Information', 'shipping-nova-poshta-for-woocommerce' ),
			]
		);

		// Redirect to general tab if active tab doesn't exists.
		if ( empty( $tabs[ $active_tab ] ) ) {
			wp_safe_redirect( remove_query_arg( 'tab', $url ), 301 );
		}

		require NOVA_POSHTA_PATH . 'templates/admin/page-options/page-options.php';
	}

	/**
	 * Get active tab slug.
	 *
	 * @return string
	 */
	private function get_active_tab(): string {
		// phpcs:ignore WordPress.Security.NonceVerification.Recommended
		return ! empty( $_GET['tab'] ) ? sanitize_key( $_GET['tab'] ) : 'general';
	}

	/**
	 * View for general tab.
	 *
	 * @param string $tab_label Current tab label.
	 */
	public function general_tab( string $tab_label ) {

		$settings = $this->settings;

		require NOVA_POSHTA_PATH . 'templates/admin/page-options/tab-general.php';
	}

	/**
	 * View for sender information tab.
	 *
	 * @param string $tab_label Current tab label.
	 */
	public function sender_tab( string $tab_label ) {

		$settings             = $this->settings;
		$current_city_id      = $settings->city_id();
		$current_city         = $current_city_id ? $this->api->city( $current_city_id ) : '';
		$warehouses           = $current_city_id ? $this->api->warehouses( $current_city_id ) : [];
		$current_warehouse_id = $settings->warehouse_id();

		require NOVA_POSHTA_PATH . 'templates/admin/page-options/tab-sender.php';
	}

	/**
	 * Merge settings.
	 *
	 * @param array $value     Option value.
	 * @param array $old_value Old option value.
	 *
	 * @return array
	 */
	public function merge_settings( array $value, $old_value ): array {

		if ( empty( $old_value ) ) {
			return $value;
		}

		$object_cache    = $this->factory_cache->object();
		$transient_cache = $this->factory_cache->transient();

		$object_cache->flush();
		$transient_cache->flush();

		if ( ! empty( $value['api_key'] ) ) {
			$value['api_key'] = trim( $value['api_key'] );

			if ( ! $this->api->validate( $value['api_key'] ) ) {
				unset( $value['api_key'], $old_value['api_key'] );
			}
		}

		return array_replace( $old_value, $value );
	}

	/**
	 * Load cities on plugin upgraded.
	 */
	public function load_cities() {

		$object_cache    = $this->factory_cache->object();
		$transient_cache = $this->factory_cache->transient();

		$object_cache->flush();
		$transient_cache->flush();

		$this->api->cities();
	}

	/**
	 * Register notices.
	 *
	 * @param Notice $notice Notice.
	 */
	public function notices( Notice $notice ) {

		global $current_screen;

		if ( empty( $current_screen ) || 0 !== strpos( $current_screen->base, 'toplevel_page_' . Main::PLUGIN_SLUG ) ) {
			return;
		}

		if ( ! $this->settings->api_key() ) {
			$notice->add(
				'error',
				sprintf( /* translators: 1: link on page option */
					__(
						'For the plugin to work, you must enter the API key on the <a href="%s">plugin settings page</a>',
						'shipping-nova-poshta-for-woocommerce'
					),
					get_admin_url( null, 'admin.php?page=' . Main::PLUGIN_SLUG )
				)
			);

			return;
		}

		if ( ! $this->api->validate( $this->settings->api_key() ) ) {
			$notice->add(
				'error',
				esc_html__( 'Invalid api key', 'shipping-nova-poshta-for-woocommerce' )
			);
		}

		if ( $this->get_active_tab() === 'shipping-cost' && $this->settings->city_id() ) {
			$notice->add(
				'error',
				sprintf( /* translators: %s - link to the sender data settings page. */
					__( 'Please fill in the <a href="%1$s">sender\'s information</a> without this information, the cost calculation will not work.', 'shipping-nova-poshta-for-woocommerce' ),
					add_query_arg(
						[
							'page' => Main::PLUGIN_SLUG,
							'tab'  => 'sender',
						],
						admin_url( 'admin.php' )
					)
				)
			);
		}
	}
}
