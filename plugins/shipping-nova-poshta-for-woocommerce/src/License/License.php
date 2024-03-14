<?php
/**
 * License class.
 *
 * @package   Shipping-Nova-Poshta-For-Woocommerce
 * @author    WP Unit
 * @link      http://wp-unit.com/
 * @copyright Copyright (c) 2020
 * @license   GPL-2.0+
 * @wordpress-plugin
 */

namespace NovaPoshta\License;

use NovaPoshta\Main;
use NovaPoshta\Notice\Notice;
use NovaPoshta\Cache\FactoryCache;

/**
 * Class License
 *
 * @package NovaPoshta\License
 */
class License {

	/**
	 * Client
	 *
	 * @var Client
	 */
	protected $client;

	/**
	 * Notice
	 *
	 * @var Notice
	 */
	protected $notice;

	/**
	 * Factory cache
	 *
	 * @var FactoryCache
	 */
	protected $factory_cache;

	/**
	 * License constructor.
	 *
	 * @param Client       $client        Client.
	 * @param FactoryCache $factory_cache Factory cache.
	 * @param Notice       $notice        Notice.
	 */
	public function __construct( Client $client, FactoryCache $factory_cache, Notice $notice ) {

		$this->client        = $client;
		$this->notice        = $notice;
		$this->factory_cache = $factory_cache;
	}

	/**
	 * Hooks.
	 */
	public function hooks() {

		add_action( 'admin_init', [ $this, 'controller' ] );
		add_action( 'admin_init', [ $this, 'validate' ], 11 );
	}

	/**
	 * Controller.
	 */
	public function controller() {

		// phpcs:ignore WordPress.Security.NonceVerification.Recommended
		if ( empty( $_GET['page'] ) || Main::PLUGIN_SLUG !== sanitize_key( $_GET['page'] ) ) {
			return;
		}

		if ( empty( $_POST['_wpnonce'] ) || empty( $_POST['method'] ) || empty( $_POST['license_key'] ) ) {
			return;
		}

		$nonce   = sanitize_key( $_POST['_wpnonce'] );
		$method  = sanitize_key( $_POST['method'] );
		$api_key = sanitize_text_field( wp_unslash( $_POST['license_key'] ) );

		if ( ! wp_verify_nonce( $nonce, Main::PLUGIN_SLUG . '-license' ) ) {
			return;
		}

		if ( ! method_exists( $this, $method ) ) {
			return;
		}

		$this->{$method}( $api_key );
	}

	/**
	 * Activate license.
	 *
	 * @param string $api_key Api key.
	 */
	private function activate( string $api_key ) {

		$old_key     = get_option( Main::PLUGIN_SLUG . '-license' );
		$is_valid    = $this->client->activate( $api_key );
		$current_key = $is_valid ? $api_key : $old_key;

		if (
			! $is_valid &&
			( empty( $old_key ) || $this->client->activate( $old_key ) )
		) {
			$this->notice->add(
				'error',
				wp_kses(
					sprintf( /* translators: %s - link to the personal account */
						__( 'Failed to activate the plugin, try again or check your key in <a href="%s" target="_blank">your personal account</a>.', 'shipping-nova-poshta-for-woocommerce' ),
						'https://wp-unit.com/my-account'
					),
					[
						'a' => [
							'href'   => true,
							'target' => true,
						],
					]
				)
			);

			return;
		}

		$cache = $this->factory_cache->transient();
		$cache->set( 'license', true, HOUR_IN_SECONDS );

		if ( $current_key !== $old_key ) {
			update_option( Main::PLUGIN_SLUG . '-license', $current_key );
		}

		$this->upgrade( $current_key );

		wp_safe_redirect( get_admin_url( null, 'admin.php?page=' . Main::PLUGIN_SLUG ) );
	}

	/**
	 * Upgrade plugin to pro plugin.
	 *
	 * @param string $api_key Api key.
	 */
	private function upgrade( string $api_key ) {

		$package = $this->client->update( $api_key );

		require_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';
		require_once ABSPATH . 'wp-admin/includes/class-plugin-upgrader.php';

		$plugin_upgrader = new \Plugin_Upgrader( new \Automatic_Upgrader_Skin() );
		if ( empty( $package['package'] ) || ! $plugin_upgrader->install( $package['package'] ) ) {
			$this->notice->add(
				'error',
				wp_kses(
					sprintf( /* translators: %s - link to the personal account. */
						__( 'Automatic update failed. Try again or download the plugin from <a href="%s">your personal account.</a>', 'shipping-nova-poshta-for-woocommerce' ),
						'https://wp-unit.com/my-account/'
					),
					[
						'a' => [
							'href' => true,
						],
					]
				)
			);

			do_action( 'shipping_nova_poshta_for_woocommerce_upgraded' );

			return;
		}

		activate_plugin( sprintf( '%1$s-pro/%1$s-pro.php', Main::PLUGIN_SLUG ) );

		wp_safe_redirect( get_admin_url( null, 'admin.php?page=' . Main::PLUGIN_SLUG ) );
	}

	/**
	 * Deactivate license.
	 *
	 * @param string $api_key Api key.
	 */
	private function deactivate( string $api_key ) {

		$this->client->deactivate( $api_key );
		delete_option( Main::PLUGIN_SLUG . '-license' );
		wp_safe_redirect( get_admin_url( null, 'admin.php?page=' . Main::PLUGIN_SLUG ) );
	}

	/**
	 * Validate license twice a day.
	 */
	public function validate() {

		static $showed_notice = false;

		if ( $showed_notice ) {
			return;
		}

		if ( ! $this->get_api_key() ) {
			return;
		}

		if ( $this->is_valid_license() ) {
			return;
		}

		$showed_notice = true;

		$this->notice->add(
			'error',
			sprintf( /* translators: %s - link to the website. */
				__( 'We noticed you\'re using an invalid license for the <strong>Shipping Nova Poshta for WooCommerce</strong> plugin. Please, update your license on <a href="%s" target="_blank" rel="noopener">our website</a>.', 'shipping-nova-poshta-for-woocommerce' ),
				'https://wp-unit.com/product/nova-poshta-pro/'
			)
		);
	}

	/**
	 * Is valid license.
	 *
	 * @return bool
	 */
	public function is_valid_license(): bool {

		$api_key = $this->get_api_key();
		$cache   = $this->factory_cache->transient();

		if ( ! $api_key ) {
			return false;
		}

		if ( $cache->get( 'license' ) ) {
			return true;
		}

		if ( $cache->get( 'invalid_license' ) ) {
			return false;
		}

		$response = $this->client->check( $api_key );

		if ( ! empty( $response['success'] ) && ! empty( $response['status_check'] ) && 'active' === $response['status_check'] ) {
			$cache->set( 'license', true, 24 * HOUR_IN_SECONDS );

			return true;
		}

		$cache->set( 'invalid_license', true, 4 * HOUR_IN_SECONDS );

		return false;
	}

	/**
	 * Get api key.
	 *
	 * @return string
	 */
	protected function get_api_key(): string {

		if ( defined( 'NOVA_POSHTA_API_KEY' ) ) {
			return (string) NOVA_POSHTA_API_KEY;
		}

		return get_option( Main::PLUGIN_SLUG . '-license', '' );
	}

}
