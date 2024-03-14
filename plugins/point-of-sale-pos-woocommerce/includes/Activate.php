<?php

namespace ZPOS;

use ZPOS\Admin\Stations\Post;

class Activate
{
	public function __construct()
	{
		add_action('plugins_loaded', [$this, 'activationProcess'], 1);
		register_activation_hook(PLUGIN_ROOT_FILE, [$this, 'activation']);
	}

	public function activation()
	{
		delete_option('rewrite_rules');

		$administrator = get_role('administrator');
		$administrator->add_cap('manage_woocommerce_pos');
	}

	public function activationProcess()
	{
		if (!class_exists('WooCommerce')) {
			return;
		}

		$version = Plugin::getVersion();

		if ($version !== PLUGIN_VERSION) {
			delete_option('rewrite_rules');
		}

		if (version_compare($version, PLUGIN_VERSION, '>=')) {
			return;
		}

		if (version_compare($version, '1.0.0', '<')) {
			$this->activationProcessV1();
		}

		if (version_compare($version, '2.0.0', '<')) {
			$this->activationProcessV2();
		}
		if (version_compare($version, '2.0.4', '<')) {
			$this->activationProcessV2_0_4();
		}
		if (version_compare($version, '2.0.5', '<')) {
			$this->activationProcessV2_0_5();
		}

		Plugin::setVersion(PLUGIN_VERSION);
	}

	public function activationProcessV1()
	{
		$administrator = get_role('administrator');
		$administrator->add_cap('manage_woocommerce_pos');
		$administrator->add_cap('access_woocommerce_pos');
		$administrator->add_cap('access_woocommerce_pos_addons');
		$administrator->add_cap('read_woocommerce_pos_setting');
		$administrator->add_cap('read_woocommerce_pos_categories');
		$administrator->add_cap('read_woocommerce_pos_gateways');

		$shop_manager = get_role('shop_manager');
		$shop_manager->add_cap('access_woocommerce_pos');
		$shop_manager->add_cap('access_woocommerce_pos_addons');
		$shop_manager->add_cap('read_woocommerce_pos_setting');
		$shop_manager->add_cap('read_woocommerce_pos_categories');
		$shop_manager->add_cap('read_woocommerce_pos_gateways');

		add_role('cashier', 'Cashier');
		$cashier = get_role('cashier');
		array_map(
			function ($el) use ($cashier) {
				$cashier->add_cap($el);
			},
			[
				'access_woocommerce_pos',
				'read_woocommerce_pos_setting',
				'read_woocommerce_pos_categories',
				'read_woocommerce_pos_gateways',
				'read',
				'read_product',
				'read_shop_coupon',
				'read_shop_order',
				'read_shop_webhook',
				'read_private_products',
				'read_private_shop_orders',
				'list_users',
				'edit_shop_orders',
				'publish_shop_orders',
				'delete_shop_orders',
				'delete_private_shop_orders',
				'delete_published_shop_orders',
				'read_private_shop_coupons',
			]
		);

		add_role('kiosk', 'Kiosk');
		$kiosk = get_role('kiosk');

		array_map(
			function ($el) use ($kiosk) {
				$kiosk->add_cap($el);
			},
			[
				'access_woocommerce_pos',
				'read_woocommerce_pos_setting',
				'read_woocommerce_pos_categories',
				'read_woocommerce_pos_gateways',
				'read',
				'read_private_products',
				'read_private_shop_orders',
				'read_product',
				'read_woocommerce_pos_single_coupons',
				'read_woocommerce_pos_single_customers',
				'read_shop_order',
				'read_shop_webhook',
				'publish_shop_orders', //to make order
				'edit_shop_orders',
			]
		);
	}

	public function activationProcessV2()
	{
		$administrator = get_role('administrator');
		$administrator->add_cap('delete_woocommerce_pos');
		$this->addBaseStations();
	}

	private function addBaseStations()
	{
		self::disable_cache();

		$curr_wc_station_id = get_option('pos_wc_station_id');
		if (
			empty($curr_wc_station_id) ||
			is_null(get_post($curr_wc_station_id)) ||
			Post::TYPE !== get_post_type($curr_wc_station_id)
		) {
			$wc_station_id = wp_insert_post([
				'post_type' => Post::TYPE,
				'post_title' => 'Online Storefront Station',
				'post_status' => 'publish',
			]);
			update_option('pos_wc_station_id', $wc_station_id);
		}

		$curr_station_id = get_option('pos_legacy_station_id');
		if (
			empty($curr_station_id) ||
			is_null(get_post($curr_station_id)) ||
			Post::TYPE !== get_post_type($curr_station_id)
		) {
			$station_id = wp_insert_post([
				'post_type' => Post::TYPE,
				'post_title' => 'Primary Station',
				'post_status' => 'publish',
			]);
			update_option('pos_legacy_station_id', $station_id);
		} else {
			$station_id = $curr_station_id;
		}

		$meta_keys = [
			// general
			'pos_notifications',
			'pos_address_1',
			'pos_address_2',
			'pos_city',
			'pos_postcode',
			'pos_country',
			'pos_state',
			// cart
			'pos_cart_customer',
			'pos_cart_menu_display',
			'pos_tips',
			'pos_cart_sorting',
			// products
			'pos_inventory_management',
			'pos_hide_out_of_stock_products',
			'pos_hold_stock',
			'pos_coupons_manual',
			'pos_show_photo_in_tile',
			'pos_tabs',
			// tax
			'pos_tax_enabled',
			'pos_display_prices_include_tax_in_shop',
			'pos_display_prices_include_tax_in_cart',
			'pos_tax_based_on_order',
		];

		foreach ($meta_keys as $meta_key) {
			$meta_value = get_option($meta_key);
			if ($meta_value) {
				update_post_meta($station_id, $meta_key, $meta_value);
			}
			delete_option($meta_key);
		}
	}

	public function activationProcessV2_0_4()
	{
		$shop_manager = get_role('shop_manager');
		$shop_manager->add_cap('manage_woocommerce_pos');
	}

	public function activationProcessV2_0_5()
	{
		$administrator = get_role('administrator');
		$shop_manager = get_role('shop_manager');
		$cashier = get_role('cashier');
		$roles = [$administrator, $shop_manager, $cashier];
		foreach ($roles as $role) {
			$role->add_cap('pay_for_order');
		}
	}

	// To temporarily disable known cache systems that cause issues in the activation or deactivation processes.
	public static function disable_cache()
	{
		// GoDaddy
		if (
			isset($GLOBALS['wpaas_cache_class']) &&
			$GLOBALS['wpaas_cache_class'] instanceof \WPaaS\Cache_V2
		) {
			remove_action('clean_post_cache', [$GLOBALS['wpaas_cache_class'], 'do_purge'], PHP_INT_MAX);
		}
	}
}
