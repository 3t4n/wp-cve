<?php

namespace ZPOS\Admin\Stations\Tabs;

use ZPOS\Admin\Setting\Box;
use ZPOS\Admin\Setting\Input\AssocArray;
use ZPOS\Admin\Setting\Input\Checkbox;
use ZPOS\Admin\Setting\Input\ColorPicker;
use ZPOS\Admin\Setting\Input\Number;
use ZPOS\Admin\Setting\Input\Radio;
use ZPOS\Admin\Setting\Input\Select;
use ZPOS\Admin\Setting\PostTab;
use ZPOS\Admin\Setting\Sanitize\Boolean;

class Products extends PostTab
{
	use Boolean;

	public $name;
	public $path = '/products';

	public function __construct()
	{
		parent::__construct();
		$this->name = __('Products', 'zpos-wp-api');
	}

	public function getBoxes()
	{
		return [
			new Box(
				__('Inventory Management', 'zpos-wp-api'),
				null,
				new Radio(
					__('Stock Management Functionality', 'zpos-wp-api'),
					'pos_inventory_management',
					$this->getValue('pos_inventory_management'),
					[
						[
							'value' => 'block',
							'label' => __(
								'Reduce stock and not allow product to be added to cart',
								'zpos-wp-api'
							),
						],
					],
					[
						'description' => __(
							'Enabled only when Shop Base Inventory Management is active',
							'zpos-wp-api'
						),
						'descriptionPosition' => 'nextToLabel',
					]
				),
				new Checkbox(
					null,
					'pos_hide_out_of_stock_products',
					$this->getValue('pos_hide_out_of_stock_products'),
					__('Hide out of stock products from POS product list', 'zpos-wp-api')
				),
				new Radio(
					null,
					'pos_inventory_management',
					$this->getValue('pos_inventory_management'),
					[
						[
							'value' => 'allow',
							'label' => __(
								'Reduce stock but allow product to be added to cart for backorder',
								'zpos-wp-api'
							),
						],
					],
					['savePost' => null]
				),
				new Number(null, 'pos_hold_stock', $this->getValue('pos_hold_stock'), [
					'title' => __('Hold stock (minutes)', 'zpos-wp-api'),
					'inputDescription' => __(
						'Hold stock (for products placed in cart) for x minutes. When this limit is reached, the stock will be released. Leave blank to disable.',
						'zpos-wp-api'
					),
				])
			),
			new Box(
				__('Coupons', 'zpos-wp-api'),
				null,
				new Checkbox(
					null,
					'pos_coupons_manual',
					$this->getValue('pos_coupons_manual'),
					__('Enable Manual Coupons', 'zpos-wp-api'),
					[
						'sanitize' => [$this, 'sanitizeBoolean'],
					]
				)
			),
			new Box(
				__('Product Search Default', 'zpos-wp-api'),
				null,
				new Radio(
					null,
					'pos_default_product_search',
					$this->getValue('pos_default_product_search'),
					[
						[
							'value' => 'name',
							'label' => __('Search mode', 'zpos-wp-api'),
						],
						[
							'value' => 'barcode',
							'label' => __('Barcode mode', 'zpos-wp-api'),
						],
					]
				)
			),
			new Box(
				__('Tiles', 'zpos-wp-api'),
				null,
				new Checkbox(
					null,
					'pos_show_photo_in_tile',
					$this->getValue('pos_show_photo_in_tile'),
					__('Show Photo in Tile', 'zpos-wp-api'),
					[
						'afterHeading' => true,
						'description' => __(
							'Note: Product and Category photo tile display may impact POS loading performance',
							'zpos-wp-api'
						),
						'sanitize' => [$this, 'sanitizeBoolean'],
					]
				)
			),
			new Box(
				__('Tile Sorting', 'zpos-wp-api'),
				null,
				new Select(
					__('Sort products in tabs by', 'zpos-wp-api'),
					'pos_products_sorting',
					$this->getValue('pos_products_sorting'),
					self::get_sort_values()
				)
			),
			new Box(
				__('Tile Display', 'zpos-wp-api'),
				null,
				new Select(
					__('Default Display Style', 'zpos-wp-api'),
					'pos_default_display_style',
					$this->getValue('pos_default_display_style'),
					self::get_display_style_values()
				)
			),
			new Box(
				__('Global Tile Styling', 'zpos-wp-api'),
				[
					'description' => __(
						'Note: Custom style settings on specific Product or Categories will override Global Color Styling',
						'zpos-wp-api'
					),
				],
				new ColorPicker(
					__('Product Tiles', 'zpos-wp-api'),
					'pos_product_name_color',
					$this->getValue('pos_product_name_color'),
					[
						'inputLabel' => __('Product Name', 'zpos-wp-api'),
					]
				),
				new ColorPicker(
					null,
					'pos_product_sub_text_color',
					$this->getValue('pos_product_sub_text_color'),
					[
						'inputLabel' => __('Product Sub Text', 'zpos-wp-api'),
					]
				),
				new Checkbox(
					null,
					'pos_hide_sub_text',
					$this->getValue('pos_hide_sub_text'),
					__('Hide Sub Text', 'zpos-wp-api'),
					[
						'sanitize' => [$this, 'sanitizeBoolean'],
					]
				),
				new ColorPicker(
					null,
					'pos_product_price_color',
					$this->getValue('pos_product_price_color'),
					[
						'inputLabel' => __('Product Price', 'zpos-wp-api'),
					]
				),
				new Checkbox(
					null,
					'pos_hide_product_price',
					$this->getValue('pos_hide_product_price'),
					__('Hide Product Price', 'zpos-wp-api'),
					[
						'sanitize' => [$this, 'sanitizeBoolean'],
					]
				),
				new ColorPicker(
					null,
					'pos_out_of_stock_text_color',
					$this->getValue('pos_out_of_stock_text_color'),
					[
						'inputLabel' => __('Out Of Stock Text', 'zpos-wp-api'),
					]
				),
				new ColorPicker(
					null,
					'pos_tile_background_color',
					$this->getValue('pos_tile_background_color'),
					[
						'inputLabel' => __('Tile background', 'zpos-wp-api'),
					]
				),
				new ColorPicker(
					__('Category Tiles', 'zpos-wp-api'),
					'pos_category_name_color',
					$this->getValue('pos_category_name_color'),
					[
						'inputLabel' => __('Category Name', 'zpos-wp-api'),
					]
				),
				new ColorPicker(
					null,
					'pos_category_count_color',
					$this->getValue('pos_category_count_color'),
					[
						'inputLabel' => __('Category Count', 'zpos-wp-api'),
					]
				),
				new ColorPicker(
					null,
					'pos_category_tile_background_color',
					$this->getValue('pos_category_tile_background_color'),
					[
						'inputLabel' => __('Tile Background', 'zpos-wp-api'),
					]
				)
			),

			new Box(
				__('Product Tabs', 'zpos-wp-api'),
				[
					'description' => __(
						'Supported syntax for products to display in tabs: all, cat-list, cat:[category name], blank, onsale, featured, tag:[tag slug],
						 att:[attribute name], type:[product type], product-id:[product id], taxable:[true | false], stock:[instock | outofstock | onbackorder].
						 Can be combined with ", " (e.g. [all, blank, cat-list]). Add [group] to group products as a single tile (e.g. featured[group]',
						'zpos-wp-api'
					),
				],
				new AssocArray(null, 'pos_tabs', $this->getValue('pos_tabs'), [
					'sanitize' => [$this, 'sanitizeTabs'],
				])
			),
		];
	}

	public static function getDefaultValue($value, $post, $name)
	{
		switch ($name) {
			case 'pos_tabs':
				return [
					'all' => 'Products',
					'cat-list' => 'Categories',
				];
			case 'pos_show_photo_in_tile':
				return true;
			case 'pos_coupons_manual':
			case 'pos_hide_sub_text':
			case 'pos_hide_product_price':
				return false;
			case 'pos_product_name_color':
			case 'pos_product_price_color':
			case 'pos_category_name_color':
				return '#1E1E1E';
			case 'pos_product_sub_text_color':
			case 'pos_category_count_color':
				return '#737373';
			case 'pos_out_of_stock_text_color':
				return '#FF4221';
			case 'pos_tile_background_color':
			case 'pos_category_tile_background_color':
				return '#FFFFFF';
			case 'pos_default_product_search':
				return 'name';
			case 'pos_products_sorting':
				$keys = self::extract_option_values(self::get_sort_values());
				return $keys[0];
			case 'pos_default_display_style':
				$keys = self::extract_option_values(self::get_display_style_values());
				return $keys[0];
			default:
				return $value;
		}
	}

	public static function extract_option_values($options)
	{
		return array_map(function ($option) {
			return $option['value'];
		}, $options);
	}

	public static function sanitizeTabs($raw_data)
	{
		$data = [];

		foreach ($raw_data as $el) {
			$data[$el['key']] = $el['value'];
		}

		return $data;
	}

	public static function get_sort_values()
	{
		return [
			[
				'value' => 'price_desc',
				'label' => __('Sort by price: High to low', 'zpos-wp-api'),
			],
			[
				'value' => 'price_asc',
				'label' => __('Sort by price: Low to high', 'zpos-wp-api'),
			],
			[
				'value' => 'name_asc',
				'label' => __('Sort by A to Z', 'zpos-wp-api'),
			],
			[
				'value' => 'name_desc',
				'label' => __('Sort by Z to A', 'zpos-wp-api'),
			],
		];
	}

	public static function get_display_style_values()
	{
		return [
			[
				'value' => 'tiles',
				'label' => __('Display products as Tiles', 'zpos-wp-api'),
			],
			[
				'value' => 'list',
				'label' => __('Display products as List', 'zpos-wp-api'),
			],
		];
	}
}
