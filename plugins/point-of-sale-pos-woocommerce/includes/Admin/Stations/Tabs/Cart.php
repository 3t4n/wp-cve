<?php

namespace ZPOS\Admin\Stations\Tabs;

use ZPOS\Admin\Setting\Box;
use ZPOS\Admin\Setting\Input\Checkbox;
use ZPOS\Admin\Setting\Input\Select;
use ZPOS\Admin\Setting\PostTab;
use ZPOS\Admin\Setting\Sanitize\Boolean;

class Cart extends PostTab
{
	use Boolean;

	public $name;
	public $path = '/cart';

	public function __construct()
	{
		parent::__construct();
		$this->name = __('Cart', 'zpos-wp-api');
	}

	public function getBoxes()
	{
		return [
			new Box(
				__('Cart', 'zpos-wp-api'),
				null,
				new Checkbox(
					__('Customer', 'zpos-wp-api'),
					'pos_cart_customer',
					$this->getValue('pos_cart_customer'),
					__('Customer is Required to be Added to the Order for Checkout', 'zpos-wp-api'),
					['sanitize' => [$this, 'sanitizeBoolean']]
				),
				new Checkbox(
					__('Menu Display', 'zpos-wp-api'),
					'pos_cart_menu_display',
					$this->getValue('pos_cart_menu_display'),
					__('Show the Cart menu expanded open by default', 'zpos-wp-api'),
					['sanitize' => [$this, 'sanitizeBoolean']]
				),
				new Checkbox(
					__('Customer Tips', 'zpos-wp-api'),
					'pos_tips',
					$this->getValue('pos_tips'),
					__('Enable Tip on Checkout', 'zpos-wp-api')
				),
				new Select(
					__('Sort products in cart by', 'zpos-wp-api'),
					'pos_cart_sorting',
					$this->getValue('pos_cart_sorting'),
					self::get_sort_values()
				),
				new Checkbox(
					__('Barcode Scanning Cart Action', 'zpos-wp-api'),
					'pos_barcode_automatically_add_to_cart',
					$this->getValue('pos_barcode_automatically_add_to_cart'),
					__('Barcode scan automatically adds item to cart', 'zpos-wp-api'),
					['sanitize' => [$this, 'sanitizeBoolean']]
				),
				new Checkbox(
					'',
					'pos_barcode_repeat_barcode_scans',
					$this->getValue('pos_barcode_repeat_barcode_scans'),
					__('Enable repeat Barcode scans functionality', 'zpos-wp-api'),
					['sanitize' => [$this, 'sanitizeBoolean']]
				)
			),
		];
	}

	public static function getDefaultValue($value, $post, $name)
	{
		switch ($name) {
			case 'pos_cart_sorting':
				$keys = array_map(function ($option) {
					return $option['value'];
				}, self::get_sort_values());
				return $keys[0];
			case 'pos_tips':
			case 'pos_cart_menu_display':
			case 'pos_cart_customer':
			case 'pos_barcode_automatically_add_to_cart':
			case 'pos_barcode_repeat_barcode_scans':
				return false;
			default:
				return $value;
		}
	}

	public static function get_sort_values()
	{
		return [
			[
				'value' => 'price_desc',
				'label' => __('Sort by price: high to low', 'zpos-wp-api'),
			],
			[
				'value' => 'price_asc',
				'label' => __('Sort by price: low to high', 'zpos-wp-api'),
			],
			[
				'value' => 'time_desc',
				'label' => __('Sort by items added to cart: newer is higher', 'zpos-wp-api'),
			],
			[
				'value' => 'time_asc',
				'label' => __('Sort by items added to cart: older is higher', 'zpos-wp-api'),
			],
		];
	}
}
