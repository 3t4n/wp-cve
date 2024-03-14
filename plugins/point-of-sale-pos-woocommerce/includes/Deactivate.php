<?php

namespace ZPOS;

use ZPOS\Admin\Stations\Post;
use ZPOS\Admin\Tabs\Connection;
use ZPOS\Admin\Tabs\Debug;
use ZPOS\Admin\Tabs\Gateway;
use ZPOS\Admin\Tabs\General;
use ZPOS\Admin\Tabs\Users;

class Deactivate
{
	public function __construct()
	{
		register_deactivation_hook(PLUGIN_ROOT_FILE, [$this, 'deactivation']);
	}

	public function deactivation()
	{
		delete_option('rewrite_rules');

		if (get_option(Plugin::RESET_OPTION)) {
			$this->reset();
		}
	}

	private function reset()
	{
		$this->resetStations();
		$this->resetUsers();
		$this->resetSettings();

		delete_option(Plugin::VERSION_OPTION);
		delete_option(Plugin::RESET_OPTION);
	}

	private function resetStations()
	{
		Activate::disable_cache();

		$query = new \WP_Query(['post_type' => Post::TYPE]);
		while ($query->have_posts()) {
			$query->the_post();
			$meta = get_post_meta(get_the_ID());
			foreach ($meta as $key => $value) {
				delete_post_meta(get_the_ID(), $key);
			}
			wp_delete_post(get_the_ID(), true);
			if (!$query->have_posts()) {
				$query = new \WP_Query(['post_type' => Post::TYPE]);
			}
		}
		wp_reset_postdata();

		delete_option('pos_wc_station_id');
		delete_option('pos_legacy_station_id');
	}

	private function resetUsers()
	{
		$administrator = get_role('administrator');
		$administrator->remove_cap('manage_woocommerce_pos');
		$administrator->remove_cap('access_woocommerce_pos');
		$administrator->remove_cap('access_woocommerce_pos_addons');
		$administrator->remove_cap('read_woocommerce_pos_setting');
		$administrator->remove_cap('read_woocommerce_pos_categories');
		$administrator->remove_cap('read_woocommerce_pos_gateways');
		$administrator->remove_cap('delete_woocommerce_pos');
		$administrator->remove_cap('pay_for_order');

		$customer = get_role('customer');
		$customer->remove_cap('read_woocommerce_pos_setting');
		$customer->remove_cap('read_woocommerce_pos_categories');
		$customer->remove_cap('read_woocommerce_pos_gateways');

		$shop_manager = get_role('shop_manager');
		$shop_manager->remove_cap('manage_woocommerce_pos');
		$shop_manager->remove_cap('access_woocommerce_pos');
		$shop_manager->remove_cap('access_woocommerce_pos_addons');
		$shop_manager->remove_cap('read_woocommerce_pos_setting');
		$shop_manager->remove_cap('read_woocommerce_pos_categories');
		$shop_manager->remove_cap('read_woocommerce_pos_gateways');
		$shop_manager->remove_cap('pay_for_order');

		$users = get_users(['role__in' => ['cashier', 'kiosk']]);
		foreach ($users as $user) {
			/* @var $user \WP_User */
			$user->remove_role('cashier');
			$user->remove_role('kiosk');
		}

		remove_role('cashier');
		remove_role('kiosk');
	}

	private function resetSettings()
	{
		do_action(__METHOD__);

		General::reset();
		Users::reset();
		Gateway::reset();
		Connection::reset();
		Debug::reset();
	}
}
