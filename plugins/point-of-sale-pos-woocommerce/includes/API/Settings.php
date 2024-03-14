<?php

namespace ZPOS\API;

use WC_REST_Settings_Controller;
use const ZPOS\REST_NAMESPACE;

class Settings extends WC_REST_Settings_Controller
{
	protected $namespace = REST_NAMESPACE;

	public function __construct()
	{
		do_action(__METHOD__, $this, $this->namespace, $this->rest_base);

		$this->register_plugin_settings();
		if (defined('\Zprint\ACTIVE') && \Zprint\ACTIVE) {
			$this->register_print_settings();
		}
		if (defined('\Zhours\ACTIVE') && \Zhours\ACTIVE) {
			$this->register_hours_settings();
		}
		if (defined('\UAP_ACTIVE') && \UAP_ACTIVE) {
			$this->register_uap_settings();
		}
	}

	protected function register_plugin_settings()
	{
		do_action(__METHOD__, $this, $this->namespace, $this->rest_base);
		add_filter(
			'woocommerce_settings_groups',
			function ($groups) {
				$groups[] = [
					'id' => 'plugins',
					'label' => 'Plugins State',
				];

				return $groups;
			},
			100
		);
	}

	protected function register_print_settings()
	{
		add_filter(
			'woocommerce_settings_groups',
			function ($groups) {
				$groups[] = [
					'id' => 'zprint',
					'label' => 'zPrint',
				];

				return $groups;
			},
			100
		);
	}

	protected function register_hours_settings()
	{
		add_filter(
			'woocommerce_settings_groups',
			function ($groups) {
				$groups[] = [
					'id' => 'zhours',
					'label' => 'zHours',
				];

				return $groups;
			},
			100
		);
	}

	protected function register_uap_settings()
	{
		add_filter(
			'woocommerce_settings_groups',
			function ($groups) {
				$groups[] = [
					'id' => 'uap',
					'label' => 'UAP',
				];

				return $groups;
			},
			100
		);
	}

	public function get_items_permissions_check($request)
	{
		return current_user_can('read_woocommerce_pos_setting');
	}
}
