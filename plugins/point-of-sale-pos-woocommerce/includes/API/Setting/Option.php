<?php

namespace ZPOS\API\Setting;

use WC_REST_Setting_Options_Controller;
use const ZPOS\REST_NAMESPACE;

class Option extends WC_REST_Setting_Options_Controller
{
	protected $namespace = REST_NAMESPACE;

	public function __construct()
	{
		do_action(__METHOD__, $this, $this->namespace, $this->rest_base);
		$this->register_plugins_settings_options();

		if (defined('\Zprint\ACTIVE') && \Zprint\ACTIVE) {
			$this->register_print_settings_options();
		}
		if (defined('\Zhours\ACTIVE') && \Zhours\ACTIVE) {
			$this->register_hours_settings_options();
		}
		if (defined('\UAP_ACTIVE') && \UAP_ACTIVE) {
			$this->register_uap_settings_options();
		}
	}

	public function register_routes()
	{
		parent::register_routes();
		do_action(__METHOD__, $this, $this->namespace, $this->rest_base);
	}

	protected function register_plugins_settings_options()
	{
		add_filter('pre_option_zprint_enabled', function () {
			return defined('\Zprint\ACTIVE') && \Zprint\ACTIVE ? 'yes' : 'no';
		});

		add_filter('pre_option_zhours_enabled', function () {
			return defined('\Zhours\ACTIVE') && \Zhours\ACTIVE ? 'yes' : 'no';
		});

		add_filter('pre_option_zaddons_enabled', function () {
			return defined('\ZAddons\ACTIVE') && \ZAddons\ACTIVE ? 'yes' : 'no';
		});

		add_filter('pre_option_uap_enabled', function () {
			return defined('\UAP_ACTIVE') && \UAP_ACTIVE ? 'yes' : 'no';
		});

		add_filter(
			'woocommerce_settings-plugins',
			function ($setting) {
				$setting[] = [
					'id' => 'zprint_enabled',
					'label' => 'zPrint Status',
					'type' => 'checkbox',
					'option_key' => 'zprint_enabled',
				];

				$setting[] = [
					'id' => 'zhours_enabled',
					'label' => 'zHours Status',
					'type' => 'checkbox',
					'option_key' => 'zhours_enabled',
				];

				$setting[] = [
					'id' => 'zaddons_enabled',
					'label' => 'zAddons Status',
					'type' => 'checkbox',
					'option_key' => 'zaddons_enabled',
				];

				$setting[] = [
					'id' => 'uap_enabled',
					'label' => 'UAP Status',
					'type' => 'checkbox',
					'option_key' => 'uap_enabled',
				];

				return $setting;
			},
			100
		);
	}

	protected function register_print_settings_options()
	{
		add_filter(
			'woocommerce_settings-zprint',
			function ($setting) {
				$setting[] = [
					'id' => 'zprint_print_pos',
					'label' => 'Print POS',
					'type' => 'checkbox',
					'option_key' => 'zprint_print_pos',
				];
				$setting[] = [
					'id' => 'zprint_print_pos_order_only',
					'label' => 'Print POS Order Only',
					'type' => 'checkbox',
					'option_key' => 'zprint_print_pos_order_only',
				];
				$setting[] = [
					'id' => 'zprint_print_web',
					'label' => 'Print WEB',
					'type' => 'checkbox',
					'option_key' => 'zprint_print_web',
				];
				return $setting;
			},
			100
		);
	}

	protected function register_hours_settings_options()
	{
		add_filter(
			'woocommerce_settings-zhours',
			function ($setting) {
				$setting[] = [
					'id' => 'zhours_current_status',
					'label' => 'zHours Status',
					'type' => 'checkbox',
					'option_key' => 'zhours_current_status',
				];
				return $setting;
			},
			100
		);
	}

	protected function register_uap_settings_options()
	{
		add_filter(
			'woocommerce_settings-uap',
			function ($setting) {
				$setting[] = [
					'id' => 'uap_enable_pricing_models',
					'label' => 'Enable Pricing models',
					'type' => 'checkbox',
					'option_key' => 'uap_enable_pricing_models',
				];
				return $setting;
			},
			100
		);
	}

	public function get_items_permissions_check($request)
	{
		if (current_user_can('read_woocommerce_pos_setting')) {
			return true;
		}

		return parent::get_items_permissions_check($request);
	}
}
