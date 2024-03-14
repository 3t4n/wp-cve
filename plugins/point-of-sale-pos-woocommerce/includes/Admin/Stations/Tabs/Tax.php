<?php

namespace ZPOS\Admin\Stations\Tabs;

use ZPOS\Admin\Setting\Box;
use ZPOS\Admin\Setting\Input\Checkbox;
use ZPOS\Admin\Setting\Input\Description;
use ZPOS\Admin\Setting\Input\Select;
use ZPOS\Admin\Setting\Input\TaxArray;
use ZPOS\Admin\Setting\PostTab;
use ZPOS\Admin\Stations\Setting;
use ZPOS\Station;

class Tax extends PostTab
{
	public $name = 'Tax';
	public $path = '/tax';

	public function __construct()
	{
		parent::__construct();
		$this->name = __('Tax', 'zpos-wp-api');
		add_filter(PostTab::class . '::getValueByPost', [$this, 'getValueForWCStation'], 10, 3);
	}

	public function getBoxes()
	{
		return [
			new Box(
				__('Tax Options', 'zpos-wp-api'),
				['ignore' => !Setting::isWCStationEdit()],
				new Description(
					sprintf(
						'%s <a href="%s">%s</a>',
						__('Station uses', 'zpos-wp-api'),
						add_query_arg(['page' => 'wc-settings', 'tab' => 'tax'], admin_url('admin.php')),
						__('Shop Base Store Tax Settings', 'zpos-wp-api')
					),
					__('Tax Calculation', 'zpos-wp-api')
				)
			),
			new Box(
				__('Tax Options', 'zpos-wp-api'),
				['ignore' => Setting::isWCStationEdit()],
				new Select(
					__('Tax Options', 'zpos-wp-api'),
					'pos_tax_enabled',
					$this->getValue('pos_tax_enabled'),
					self::get_enabled_values()
				)
			),
			new Box(
				null,
				['withoutBreak' => true],
				new Checkbox(
					null,
					'pos_tax_vat_number',
					$this->getValue('pos_tax_vat_number'),
					__('Enable Tax/VAT ID Type Settings', 'zpos-wp-api')
				),
				new Select(
					__('Tax/VAT ID Behavior', 'zpos-wp-api'),
					'pos_tax_vat_behavior',
					$this->getValue('pos_tax_vat_behavior'),
					self::get_tax_vat_behaviour()
				)
			),
			new Box(
				null,
				['withoutBreak' => true],
				new Select(
					__('Display prices in the POS', 'zpos-wp-api'),
					'pos_display_prices_include_tax_in_shop',
					$this->getValue('pos_display_prices_include_tax_in_shop'),
					self::get_display_price_values()
				),
				new Select(
					__('Display prices during POS cart', 'zpos-wp-api'),
					'pos_display_prices_include_tax_in_cart',
					$this->getValue('pos_display_prices_include_tax_in_cart'),
					self::get_display_price_values()
				)
			),
			new Box(
				null,
				[
					'ignore' => Setting::isWCStationEdit(),
					'withoutBreak' => true,
				],
				new TaxArray(
					__('Calculate Tax Based on', 'zpos-wp-api'),
					'pos_tax_based_on_order',
					$this->get_tax_based_on(),
					[
						'sanitize' => [$this, 'sanitizeTax'],
					]
				)
			),
			new Box(
				null,
				['ignore' => !Setting::isWCStationEdit(), 'withoutBreak' => true],
				new Description(
					sprintf(
						'%s <a href="%s">%s</a>',
						__('Station uses', 'zpos-wp-api'),
						add_query_arg(['page' => 'wc-settings', 'tab' => 'tax'], admin_url('admin.php')),
						__('Shop Base Store Tax Settings', 'zpos-wp-api')
					),
					'Calculate Tax Based on'
				)
			),
		];
	}

	protected function get_tax_based_on()
	{
		return function ($post) {
			$values = self::get_calculate_on_values();
			return array_map(function ($value) use ($values) {
				return ['value' => $value, 'label' => $values[$value]];
			}, $this->getValue('pos_tax_based_on_order', $post));
		};
	}

	public static function getDefaultValue($value, $post, $name)
	{
		switch ($name) {
			case 'pos_tax_enabled':
				return 'on';
			case 'pos_display_prices_include_tax_in_shop':
			case 'pos_display_prices_include_tax_in_cart':
				return 'yes';
			case 'pos_tax_based_on_order':
				return ['shipping', 'billing', 'pos', 'wc'];
			case 'pos_tax_vat_behavior':
				return 'default';
			default:
				return $value;
		}
	}

	public function getValueForWCStation($value, $post, $name)
	{
		if (Station::isWCStation($post->ID)) {
			switch ($name) {
				case 'pos_tax_enabled':
					return get_option('woocommerce_calc_taxes') ? 'on' : 'off';
				case 'pos_tax_based_on_order':
					$tax_based_on = get_option('woocommerce_tax_based_on');
					if ($tax_based_on === 'base') {
						return ['wc'];
					} else {
						return [$tax_based_on, 'wc'];
					}
				default:
					return $value;
			}
		}
		return $value;
	}

	public static function sanitizeTax($raw_data)
	{
		$keys = array_keys(self::get_calculate_on_values());
		$data = array_filter($raw_data, function ($element) use ($keys) {
			return in_array($element, $keys);
		});
		return $data;
	}

	public static function get_enabled_values()
	{
		return [
			[
				'value' => 'on',
				'label' => __('Enabled (using Shop Base configurations)', 'zpos-wp-api'),
			],
			[
				'value' => 'off',
				'label' => __('Disabled', 'zpos-wp-api'),
			],
		];
	}

	public function get_display_price_values()
	{
		return [
			['value' => 'yes', 'label' => __('Including tax', 'zpos-wp-api')],
			['value' => 'no', 'label' => __('Excluding tax', 'zpos-wp-api')],
		];
	}

	public static function get_calculate_on_values()
	{
		return [
			'pos' => __('POS Station Base Address', 'zpos-wp-api'),
			'wc' => __('Default Shop Base', 'zpos-wp-api'),
			'shipping' => __('Customer Shipping Address', 'zpos-wp-api'),
			'billing' => __('Customer Billing Address', 'zpos-wp-api'),
		];
	}

	public static function get_tax_vat_behaviour()
	{
		return [
			['value' => 'default', 'label' => __('Tax/VAT not allowed to be removed', 'zpos-wp-api')],
			[
				'value' => 'remove_manually',
				'label' => __('Allow Tax/VAT removed manually', 'zpos-wp-api'),
			],
			['value' => 'always_remove', 'label' => __('Remove automatically Tax/VAT', 'zpos-wp-api')],
			[
				'value' => 'remove_if_customer',
				'label' => __('Remove automatically if Customer has Tax/VAT ID', 'zpos-wp-api'),
			],
			[
				'value' => 'remove_if_order',
				'label' => __('Remove automatically if Order has Tax/VAT ID', 'zpos-wp-api'),
			],
			[
				'value' => 'remove_if_both',
				'label' => __('Remove automatically if Customer and Order has Tax/VAT ID', 'zpos-wp-api'),
			],
		];
	}
}
