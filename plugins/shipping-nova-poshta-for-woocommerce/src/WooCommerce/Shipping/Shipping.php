<?php
/**
 * Shipping
 *
 * @package   Shipping-Nova-Poshta-For-Woocommerce
 * @author    WP Unit
 * @link      http://wp-unit.com/
 * @copyright Copyright (c) 2020
 * @license   GPL-2.0+
 * @wordpress-plugin
 */

namespace NovaPoshta\WooCommerce\Shipping;

use NovaPoshta\Notice\Notice;
use NovaPoshta\Cache\FactoryCache;
use NovaPoshta\WooCommerce\Shipping\Methods\NovaPoshta\NovaPoshta;

/**
 * Class Shipping
 *
 * @package NovaPoshta\WooCommerce\Shippings
 */
class Shipping {

	/**
	 * Plugin notices
	 *
	 * @var Notice
	 */
	private $notice;

	/**
	 * Cache
	 *
	 * @var FactoryCache
	 */
	private $factory_cache;

	/**
	 * Shipping constructor.
	 *
	 * @param Notice       $notice        Plugin notices.
	 * @param FactoryCache $factory_cache Cache.
	 */
	public function __construct( Notice $notice, FactoryCache $factory_cache ) {

		$this->notice        = $notice;
		$this->factory_cache = $factory_cache;
		$this->notices();
	}

	/**
	 * Register notices.
	 */
	private function notices() {

		if ( $this->is_active() ) {
			return;
		}

		$this->notice->add(
			'error',
			sprintf( /* translators: 1: link on WooCommerce settings */
				__(
					'You must add the "Nova Poshta" shipping method <a href="%s">in the WooCommerce settings</a>',
					'shipping-nova-poshta-for-woocommerce'
				),
				get_admin_url( null, 'admin.php?page=wc-settings&tab=shipping' )
			)
		);
	}

	/**
	 * Add hooks
	 */
	public function hooks() {

		add_filter( 'woocommerce_shipping_methods', [ $this, 'register_methods' ] );
	}

	/**
	 * Register shipping method
	 *
	 * @param array $methods Shipping methods.
	 *
	 * @return array
	 */
	public function register_methods( array $methods ): array {

		$methods[ NovaPoshta::ID ] = '\NovaPoshta\WooCommerce\Shipping\Methods\NovaPoshta\NovaPoshta';

		return $methods;
	}

	/**
	 * Is shipping method active
	 *
	 * @return bool
	 */
	private function is_active(): bool {

		global $wpdb;
		$cache = $this->factory_cache->object();
		//phpcs:disable WordPress.DB.DirectDatabaseQuery.NoCaching
		$is_active = $cache->get( 'shipping_nova_poshta_for_woocommerce_active' );

		if ( ! $is_active ) {
			//phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery
			$is_active = (bool) $wpdb->get_var(
				$wpdb->prepare(
					'SELECT `instance_id` FROM ' . $wpdb->prefix . 'woocommerce_shipping_zone_methods
			WHERE `method_id` = %s AND `is_enabled` = 1 LIMIT 1',
					'shipping_nova_poshta_for_woocommerce'
				)
			);

			$cache->set( 'shipping_nova_poshta_for_woocommerce_active', $is_active, constant( 'DAY_IN_SECONDS' ) );
		}
		//phpcs:enable WordPress.DB.DirectDatabaseQuery.NoCaching

		return $is_active;
	}

}
