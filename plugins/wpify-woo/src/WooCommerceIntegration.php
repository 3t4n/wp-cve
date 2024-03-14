<?php

namespace WpifyWoo;

use WpifyWoo\Admin\Settings;
use WpifyWooDeps\Wpify\Core\Abstracts\AbstractComponent;

/**
 * Class WooCommerceIntegration
 *
 * @package WpifyWoo
 * @property Plugin $plugin
 */
class WooCommerceIntegration extends AbstractComponent {

	const OPTION_NAME = 'wpify-woo-settings';

	/**
	 * Setup
	 *
	 * @return bool|void
	 */
	public function setup() {
		add_action( 'woocommerce_init', array( $this, 'register_settings' ) );
	}

	public function register_settings() {
		/** @var Settings $admin_settings */
		$admin_settings = $this->plugin->create_component( Settings::class );
		$admin_settings->init();
	}

	/**
	 * Check if a module is enabled
	 *
	 * @param string $module Module name.
	 *
	 * @return bool
	 */
	public function is_module_enabled( string $module ): bool {
		return in_array( $module, $this->get_enabled_modules(), true );
	}

	/**
	 * Get an array of enabled modules
	 *
	 * @return array
	 */
	public function get_enabled_modules(): array {
		return $this->get_settings( 'general' )['enabled_modules'] ?? array();
	}

	/**
	 * Get settings for a specific module
	 *
	 * @param string $module Module name.
	 *
	 * @return array
	 */
	public function get_settings( string $module ): array {
		return get_option( $this->get_settings_name( $module ), array() );
	}

	public function get_settings_name( string $module ): string {
		return sprintf( '%s-%s', $this::OPTION_NAME, $module );
	}

	/**
	 * Get available modules
	 */
	public function get_modules(): array {
		$modules = array(
			array(
				'label' => sprintf( '%1$s | <a href="%2$s" target="_blank">%3$s</a>', __( 'Async emails', 'wpify-woo' ), 'https://wpify.io/cs/knowledge-base/asynchronni-odesilani-e-mailu/', __( 'Documentation', 'wpify-woo' ) ),
				'value' => 'async_emails',
			),
			array(
				'label' => sprintf( '%1$s | <a href="%2$s" target="_blank">%3$s</a>', __( 'Packeta shipping', 'wpify-woo' ), 'https://wpify.io/cs/knowledge-base/zasilkovna/', __( 'Documentation', 'wpify-woo' ) ),
				'value' => 'packeta_shipping',
			),
			array(
				'label' => sprintf( '%1$s | <a href="%2$s" target="_blank">%3$s</a>', __( 'Checkout IČ and DIČ', 'wpify-woo' ), 'https://wpify.io/cs/knowledge-base/ic-a-dic-v-pokladne/', __( 'Documentation', 'wpify-woo' ) ),
				'value' => 'ic_dic',
			),
			array(
				'label' => sprintf( '%1$s | <a href="%2$s" target="_blank">%3$s</a>', __( 'Heureka ověřeno zákazníky', 'wpify-woo' ), 'https://wpify.io/cs/knowledge-base/heureka-overeno-zakazniky/', __( 'Documentation', 'wpify-woo' ) ),
				'value' => 'heureka_overeno_zakazniky',
			),
			array(
				'label' => sprintf( '%1$s | <a href="%2$s" target="_blank">%3$s</a>', __( 'Heureka měření konverzí', 'wpify-woo' ), 'https://wpify.io/cs/knowledge-base/heureka-mereni-konverzi/', __( 'Documentation', 'wpify-woo' ) ),
				'value' => 'heureka_mereni_konverzi',
			),
			array(
				'label' => sprintf( '%1$s | <a href="%2$s" target="_blank">%3$s</a>', __( 'XML Feed Heureka', 'wpify-woo' ), 'https://wpify.io/cs/knowledge-base/xml-feed-heureka/', __( 'Documentation', 'wpify-woo' ) ),
				'value' => 'xml_feed_heureka',
			),
			array(
				'label' => sprintf( '%1$s | <a href="%2$s" target="_blank">%3$s</a>', __( 'Free shipping notice', 'wpify-woo' ), 'https://wpify.io/cs/knowledge-base/notifikace-pro-dopravu-zdarma/', __( 'Documentation', 'wpify-woo' ) ),
				'value' => 'free_shipping_notice',
			),
			array(
				'label' => sprintf( '%1$s | <a href="%2$s" target="_blank">%3$s</a>', __( 'Emails Vocative', 'wpify-woo' ), 'https://wpify.io/cs/knowledge-base/paty-pad-v-e-mailech/', __( 'Documentation', 'wpify-woo' ) ),
				'value' => 'vocative',
			),
			array(
				'label' => sprintf( '%1$s | <a href="%2$s" target="_blank">%3$s</a>', __( 'QR Payment', 'wpify-woo' ), 'https://wpify.io/cs/knowledge-base/qr-platba/', __( 'Documentation', 'wpify-woo' ) ),
				'value' => 'qr_payment',
			),
			array(
				'label' => sprintf( '%1$s | <a href="%2$s" target="_blank">%3$s</a>', __( 'Sklik retargeting', 'wpify-woo' ), 'https://wpify.io/cs/knowledge-base/sklik-retargeting/', __( 'Documentation', 'wpify-woo' ) ),
				'value' => 'sklik_retargeting',
			),
			array(
				'label' => sprintf( '%1$s | <a href="%2$s" target="_blank">%3$s</a>', __( 'Zbozi.cz/Sklik Conversions Limited', 'wpify-woo' ), 'https://wpify.io/cs/knowledge-base/zbozi-sklik-konverze/', __( 'Documentation', 'wpify-woo' ) ),
				'value' => 'zbozi_conversions_lite',
			),
			array(
				'label' => sprintf( '%1$s | <a href="%2$s" target="_blank">%3$s</a>', __( 'Template', 'wpify-woo' ), 'https://wpify.io/cs/knowledge-base/sablona/', __( 'Documentation', 'wpify-woo' ) ),
				'value' => 'template',
			),
			array(
				'label' => sprintf( '%1$s | <a href="%2$s" target="_blank">%3$s</a>', __( 'Email attachments', 'wpify-woo' ), 'https://wpify.io/cs/knowledge-base/prilohy-emailu/', __( 'Documentation', 'wpify-woo' ) ),
				'value' => 'email_attachments',
			),
			array(
				'label' => sprintf( '%1$s | <a href="%2$s" target="_blank">%3$s</a>', __( 'Prices', 'wpify-woo' ), 'https://wpify.io/cs/knowledge-base/ceny/', __( 'Documentation', 'wpify-woo' ) ),
				'value' => 'prices',
			),
			array(
				'label' => sprintf( '%1$s | <a href="%2$s" target="_blank">%3$s</a>', __( 'Prices log', 'wpify-woo' ), 'https://wpify.io/cs/knowledge-base/historie-cen/', __( 'Documentation', 'wpify-woo' ) ),
				'value' => 'prices_log',
			),
			array(
				'label' => sprintf( '%1$s | <a href="%2$s" target="_blank">%3$s</a>', __( 'Comments', 'wpify-woo' ), 'https://wpify.io/cs/knowledge-base/komentare/', __( 'Documentation', 'wpify-woo' ) ),
				'value' => 'comments',
			),
			array(
				'label' => sprintf( '%1$s | <a href="%2$s" target="_blank">%3$s</a>', __( 'Delivery dates', 'wpify-woo' ), 'https://wpify.io/cs/knowledge-base/terminy-doruceni/', __( 'Documentation', 'wpify-woo' ) ),
				'value' => 'delivery_dates',
			),
		);

		$modules = apply_filters( 'wpify_woo_modules', $modules );

		foreach ( $this->plugin->get_premium()->get_extensions() as $extension ) {
			$exists = array_filter( $modules, function ( $module ) use ( $extension ) {
				return $extension['id'] === $module['value'];
			} );
			if ( empty( $exists ) ) {
				$modules[] = [
					'label'    => sprintf( '<a href="%1$s"><strong>%2$s</strong></a> - %3$s <span class="wpify-woo-settings__premium"><a href="%1$s">%4$s</a></span>', $extension['url'], $extension['title'], $extension['short_description'], __( 'Get the addon', 'wpify-woo' ) ),
					'value'    => $extension['id'],
					'disabled' => true,
				];
			}
		}


		return $modules;
	}

	public function get_avaliable_shipping_methods() {
		$shipping_methods = array();

		$zones = \WC_Shipping_Zones::get_zones();
		//$default_zone = \WC_Shipping_Zones::get_zone_by('zone_id',0);

		foreach ( $zones as $zone ) {
			$name = $zone['zone_name'];
			foreach ( $zone['shipping_methods'] as $shipping ) {
				$shipping_methods[ $shipping->id . ':' . $shipping->instance_id ] = $name . ': ' . $shipping->method_title;
			}
		}

		return $shipping_methods;
	}

	public function get_gateways() {
		$gateways           = array();
		$available_gateways = WC()->payment_gateways()->payment_gateways();
		foreach ( $available_gateways as $key => $gateway ) {
			$gateways[] = array(
				'label' => $gateway->title,
				'value' => $key,
			);
		}

		return $gateways;
	}

	public function get_order_statuses() {
		$statuses = wc_get_order_statuses();
		$result   = [];
		foreach ( $statuses as $id => $label ) {
			$result[] = [
				'label' => $label,
				'value' => str_replace( 'wc-', '', $id ),
			];
		}

		return $result;
	}
}
