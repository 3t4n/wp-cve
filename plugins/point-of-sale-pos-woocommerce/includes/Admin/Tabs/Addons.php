<?php

namespace ZPOS\Admin\Tabs;

use ZPOS\Structure\ArrayObject;
use ZPOS\Admin\Setting\PageTab;
use ZPOS\Admin\Setting\Box;
use ZPOS\Admin\Setting\Input\PluginWidgets;

class Addons extends PageTab
{
	public $exact = true;
	public $name;
	public $path = '/addons';

	public function __construct()
	{
		parent::__construct();
		$this->name = __('Add-ons', 'zpos-wp-api');
	}

	public function getBoxes()
	{
		return [
			new Box(
				null,
				null,
				new PluginWidgets(__('Plugins', 'zpos-wp-api'), 'pos_regular_free_plugins', [
					$this,
					'get_plugins',
				]),
				new PluginWidgets(__('Pro Plugin', 'zpos-wp-api'), 'pos_professional_plugin', [
					$this,
					'get_pro',
				]),
				new PluginWidgets(
					__('Plugins by Other Developers', 'zpos-wp-api'),
					'pos_third_party_plugin',
					[$this, 'get_other_plugins']
				)
			),
		];
	}

	public function get_default_plugins()
	{
		return [
			'order-hours-scheduler-woocommerce' => [
				'Title' => __('Order Hours', 'zpos-wp-api'),
				'TextDomain' => 'order-hours-scheduler-woocommerce',
				'Description' => __(
					'Add the ability to configure the WooCommerce store hours toggling the ability to block customers during checkout from an online and offline mode.',
					'zpos-wp-api'
				),
				'URI' => 'http://www.bizswoop.com/pos/hours',
				'Installed' => false,
				'Status' => false,
			],
			'Product-Add-Ons-WooCommerce' => [
				'Title' => __('Product Add-ons', 'zpos-wp-api'),
				'TextDomain' => 'Product-Add-Ons-WooCommerce',
				'Description' => __(
					'Enable the ability to add product add-ons and modifiers to each product listing',
					'zpos-wp-api'
				),
				'URI' => ' https://www.bizswoop.com/wp/productaddons',
				'Installed' => false,
				'Status' => false,
			],
			'Print-Google-Cloud-Print-GCP-WooCommerce' => [
				'Title' => __('POS Print for Google Cloud Print', 'zpos-wp-api'),
				'TextDomain' => 'Print-Google-Cloud-Print-GCP-WooCommerce',
				'Description' => __(
					'Enable remote printing to multiple printers using Google Cloud Print technology',
					'zpos-wp-api'
				),
				'URI' => 'http://www.bizswoop.com/pos/print',
				'Installed' => false,
				'Status' => false,
			],
		];
	}

	public function get_plugins()
	{
		$plugins = $this->get_default_plugins();

		return (new ArrayObject(get_plugins()))
			->filter(function ($plugin) use ($plugins) {
				return in_array($plugin['TextDomain'], array_keys($plugins));
			})
			->map(function ($plugin, $key) {
				return [
					'Title' => $plugin['Title'],
					'TextDomain' => $plugin['TextDomain'],
					'Description' => $plugin['Description'],
					'URI' => $plugin['PluginURI'],
					'Installed' => true,
					'Status' => is_plugin_active($key),
					'Enable' => ['type' => 'link', 'to' => admin_url('plugins.php')],
				];
			})
			->setKeys(function ($el) {
				return $el['TextDomain'];
			})
			->merge($plugins, ArrayObject::BEFORE)
			->map(function ($plugin) {
				return array_combine(array_map('strtolower', array_keys($plugin)), $plugin);
			})
			->values()
			->get();
	}

	public function get_pro()
	{
		$pro_plugins = [
			[
				'Title' => __('Multiple Users Assignments', 'zpos-wp-api'),
				'TextDomain' => 'Multiple-Users-Assignments-POS-WooCommerce',
				'Description' => __('Buy Multiple Users Assignments for the POS', 'zpos-wp-api'),
				'URI' => admin_url('plugin-install.php'),
				'BUY' => 'https://www.bizswoop.com/wp/pos/users/#buy',
				'MORE' => 'https://www.bizswoop.com/wp/pos/users',
				'Installed' => false,
				'Status' => false,
			],
			[
				'Title' => __('WC Payment Gateway', 'zpos-wp-api'),
				'TextDomain' => 'wc-pos-gateways',
				'Description' => __('Add WooCommerce payment gateway support to POS', 'zpos-wp-api'),
				'URI' => admin_url('plugin-install.php'),
				'BUY' => 'https://www.bizswoop.com/wp/pos/payments/#buy',
				'MORE' => 'https://www.bizswoop.com/wp/pos/payments',
				'Installed' => false,
				'Status' => false,
			],
		];

		$pro_plugins = apply_filters('ZPOS\Admin\Tabs\Addons::pre-get_pro', $pro_plugins);

		return (new ArrayObject($pro_plugins))
			->map(function ($plugin) {
				return array_combine(array_map('strtolower', array_keys($plugin)), $plugin);
			})
			->get();
	}

	public function get_default_other_plugins()
	{
		return [
			'woocommerce-gateway-stripe' => [
				'Title' => __('Payment Gateway Stripe', 'zpos-wp-api'),
				'TextDomain' => 'woocommerce-gateway-stripe',
				'Description' => __('POS support for Stripe payment processing', 'zpos-wp-api'),
				'URI' => 'https://wordpress.org/plugins/woocommerce-gateway-stripe/',
				'Installed' => false,
				'Status' => false,
			],
		];
	}

	public function get_other_plugins()
	{
		$plugins = $this->get_default_other_plugins();

		return (new ArrayObject(get_plugins()))
			->filter(function ($plugin) use ($plugins) {
				return in_array($plugin['TextDomain'], array_keys($plugins));
			})
			->map(function ($plugin, $key) {
				return [
					'Title' => $plugin['Title'],
					'TextDomain' => $plugin['TextDomain'],
					'Description' => $plugin['Description'],
					'URI' => $plugin['PluginURI'],
					'Installed' => true,
					'Status' => is_plugin_active($key),
					'Enable' => ['type' => 'link', 'to' => admin_url('plugins.php')],
				];
			})
			->setKeys(function ($el) {
				return $el['TextDomain'];
			})
			->merge($plugins, ArrayObject::BEFORE)
			->map(function ($plugin) {
				return array_combine(array_map('strtolower', array_keys($plugin)), $plugin);
			})
			->values()
			->get();
	}
}
