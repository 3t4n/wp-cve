<?php
/**
 * Options helper class.
 *
 * @package RT_FoodMenu
 */

namespace RT\FoodMenu\Helpers;

use RT\FoodMenu\Helpers\Fns;

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'This script cannot be accessed directly.' );
}

/**
 * Options helper class.
 */
class Options {
	public static function scItemFields() {
		return [
			'fmp_item_fields'        => [
				'type'      => 'checkbox',
				'label'     => esc_html__( 'Field Selection', 'tlp-food-menu' ),
				'multiple'  => true,
				'alignment' => 'vertical',
				'default'   => array_keys( self::fmpItemFields() ),
				'options'   => self::fmpItemFields(),
			],
			'fmp_mobile_item_fields' => [
				'type'      => 'checkbox',
				'label'     => esc_html__( 'Field Selection on Mobile', 'tlp-food-menu' ),
				'multiple'  => true,
				'alignment' => 'vertical',
				'default'   => array_keys( self::fmpMobileItemFields() ),
				'options'   => self::fmpMobileItemFields(),
			],
		];
	}

	public static function fmpItemFields() {
		$items = [
			'title'   => esc_html__( 'Title', 'tlp-food-menu' ),
			'image'   => esc_html__( 'Image', 'tlp-food-menu' ),
			'price'   => esc_html__( 'Price', 'tlp-food-menu' ),
			'excerpt' => esc_html__( 'Excerpt', 'tlp-food-menu' ),
		];

		return apply_filters( 'fmp_item_fields', $items );
	}

	public static function fmpMobileItemFields() {
		$mobile_items = [
			'title'   => esc_html__( 'Title', 'tlp-food-menu' ),
			'image'   => esc_html__( 'Image', 'tlp-food-menu' ),
			'price'   => esc_html__( 'Price', 'tlp-food-menu' ),
			'excerpt' => esc_html__( 'Excerpt', 'tlp-food-menu' ),
		];

		return apply_filters( 'fmp_mobile_item_fields', $mobile_items );
	}

	public static function foodGeneralOptions() {
		return [
			'_fmp_type'      => [
				'label'   => esc_html__( 'Food menu type', 'tlp-food-menu' ),
				'type'    => 'select',
				'options' => self::get_fmp_type_list(),
			],
			'_regular_price' => [
				'label'       => 'Regular price (' . Fns::getCurrencySymbol() . ')',
				'type'        => 'price',
				'holderClass' => 'simple_menu_attr',
				'class'       => 'short fmp_input_price',
			],
			'_sale_price'    => [
				'label'       => 'Sale price (' . Fns::getCurrencySymbol() . ')',
				'type'        => 'price',
				'holderClass' => 'simple_menu_attr',
				'class'       => 'short fmp_input_price',
			],
			'_stock_status'  => [
				'label'       => esc_html__( 'Stock status', 'tlp-food-menu' ),
				'type'        => 'select',
				'class'       => 'short fmp_select',
				'holderClass' => 'simple_menu_attr',
				'options'     => [
					'instock'    => esc_html__( 'In stock', 'tlp-food-menu' ),
					'outofstock' => esc_html__( 'Out of stock', 'tlp-food-menu' ),
				],
			],
		];
	}

	public static function get_fmp_type_list() {
		return [
			'simple'   => esc_html__( 'Simple Menu', 'tlp-food-menu' ),
			'variable' => esc_html__( 'Variable Menu', 'tlp-food-menu' ),
		];
	}

	public static function foodAdvancedOptions() {
		return [
			'menu_order'         => [
				'label'   => esc_html__( 'Menu order', 'tlp-food-menu' ),
				'type'    => 'number',
				'default' => 0,
			],
			'_ingredient_status' => [
				'label'   => esc_html__( 'Enable ingredient', 'tlp-food-menu' ),
				'type'    => 'checkbox',
				'option'  => 1,
				'default' => 1,
			],
			'_nutrition_status'  => [
				'label'   => esc_html__( 'Enable nutrition', 'tlp-food-menu' ),
				'type'    => 'checkbox',
				'default' => 1,
				'option'  => 1,
			],
			'comment_status'     => [
				'label'   => esc_html__( 'Enable reviews', 'tlp-food-menu' ),
				'type'    => 'checkbox',
				'default' => 'open',
				'option'  => 'open',
			],
		];
	}

	public static function generalSettings() {
		$settings = get_option( TLPFoodMenu()->options['settings'] );

		$general = [
			'slug'              => [
				'label'       => esc_html__( 'Food Menu (Slug)', 'tlp-food-menu' ),
				'type'        => 'slug',
				'class'       => 'slug',
				'attr'        => 'max="20" required="true"',
				'value'       => ( ! empty( $settings['slug'] ) ? sanitize_title_with_dashes( $settings['slug'] ) : 'food-menu' ),
				'description' => sprintf(
					'%s <br>%s',
					esc_html__( "This option can't be blank, must have a maximum length of 20 characters and cannot contain capital letters or spaces.", 'tlp-food-menu' ),
					esc_html__( 'After each change, please re-save your permalinks (Go to Dashboard > Settings > Permalinks. Click save button).', 'tlp-food-menu' )
				),
			],
			'currency'          => [
				'label'   => esc_html__( 'Currency', 'tlp-food-menu' ),
				'type'    => 'select',
				'class'   => 'fmp-select2',
				'options' => Fns::getCurrencyList(),
				'blank'   => esc_html__( 'Select one', 'tlp-food-menu' ),
				'value'   => ( ! empty( $settings['currency'] ) ? $settings['currency'] : 'USD' ),
			],
			'currency_position' => [
				'label'   => esc_html__( 'Currency Position', 'tlp-food-menu' ),
				'type'    => 'select',
				'class'   => 'fmp-select2',
				'options' => self::currency_position_list(),
				'blank'   => esc_html__( 'Select one', 'tlp-food-menu' ),
				'value'   => ( ! empty( $settings['currency_position'] ) ? $settings['currency_position'] : 'left' ),
			],
			'fmp_preloader'     => [
				'type'        => 'checkbox',
				'label'       => esc_html__( 'Enable Preloader?', 'tlp-food-menu' ),
				'optionLabel' => esc_html__( 'Enable', 'tlp-food-menu' ),
				'description' => esc_html__( 'Switch on to enable preloader.', 'tlp-food-menu' ),
				'default'     => 1,
				'option'      => 1,
			],
		];

		if ( ! TLPFoodMenu()->has_pro() ) {
			$general['trailing_zeroes'] = [
				'label'   => esc_html__( 'Hide Trailing Zeroes', 'tlp-food-menu' ),
				'type'    => 'checkbox',
				'optionLabel' => esc_html__( 'Enable', 'tlp-food-menu' ),
				'description' => esc_html__( 'Switch on to hide trailing zeroes from the price.', 'tlp-food-menu' ),
				'default'     => 0,
				'option'      => 1,
				'value'       => ! empty( $settings['trailing_zeroes'] ) ? $settings['trailing_zeroes'] : 0,
			];
		}

		return apply_filters( 'fmp_general_settings', $general, $settings );
	}

	public static function detailPageSettings() {
		$settings = get_option( TLPFoodMenu()->options['settings'] );

		$detailPageSettings = [
			'hide_options'             => [
				'label'     => esc_html__( 'Hide Options', 'tlp-food-menu' ),
				'type'      => 'checkbox',
				'multiple'  => true,
				'alignment' => 'vertical',
				'options'   => self::detailsPageHiddenOptions(),
				'value'     => ! empty( $settings['hide_options'] ) ? $settings['hide_options'] : [],
			],

			'fmp_single_primary_color' => [
				'type'  => 'colorpicker',
				'label' => esc_html__( 'Primary Color', 'tlp-food-menu' ),
				'value' => ! empty( $settings['fmp_single_primary_color'] ) ? $settings['fmp_single_primary_color'] : null,
			],
		];

		return apply_filters( 'fmp_detail_page_settings', $detailPageSettings, $settings );
	}

	public static function detailsPageHiddenOptions() {
		$options = [
			'image' => esc_html__( 'Image', 'tlp-food-menu' ),
			'price' => esc_html__( 'Price', 'tlp-food-menu' ),
		];

		return apply_filters( 'tlp_fm_hidden_field_option', $options );
	}

	public static function promotionsFields() {
		$products = [
			'themes'  => [
				'food-cart' => [
					'price'     => 39,
					'title'     => 'FoodCart – Restaurant WordPress Theme',
					'image_url' => TLPFoodMenu()->assets_url() . 'images/food-cart.png',
					'url'       => 'https://www.radiustheme.com/downloads/foodcart-restaurant-wordpress-theme/',
					'demo_url'  => 'https://www.radiustheme.com/demo/wordpress/themes/foodcart/',
					'buy_url'   => 'https://www.radiustheme.com/downloads/foodcart-restaurant-wordpress-theme/',
					'doc_url'   => 'https://radiustheme.com/demo/wordpress/themes/foodcart/docs/',
				],
				'red-chili' => [
					'price'     => 39,
					'title'     => 'RedChili - Restaurant WordPress Theme',
					'image_url' => TLPFoodMenu()->assets_url() . 'images/red-chili.png',
					'url'       => 'https://themeforest.net/item/red-chili-restaurant-wordpress-theme/20166175',
					'demo_url'  => 'https://radiustheme.com/demo/wordpress/redchili/',
					'buy_url'   => 'https://themeforest.net/item/red-chili-restaurant-wordpress-theme/20166175',
					'doc_url'   => 'https://radiustheme.com/demo/wordpress/redchili/docs/',
				],
			],
			'plugins' => [
				'food-menu-pro' => [
					'price'     => 19,
					'title'     => 'Food Menu PRO Plugin for WordPress',
					'image_url' => TLPFoodMenu()->assets_url() . 'images/food-menu-pro.png',
					'url'       => 'https://www.radiustheme.com/downloads/food-menu-pro-wordpress/',
					'demo_url'  => 'https://www.radiustheme.com/demo/plugins/food-menu/',
					'buy_url'   => 'https://www.radiustheme.com/downloads/food-menu-pro-wordpress/',
					'doc_url'   => 'https://www.radiustheme.com/docs/food-menu/getting-started/installations/',
				],
			],
		];

		return apply_filters( 'tlp_fm_promotion_product_list', $products );
	}

	/**
	 * Style general field options
	 *
	 * @return array
	 */
	public static function scStyleGeneralFields() {
		$scStyleGeneralFields = [
			'fmp_parent_class' => [
				'type'        => 'text',
				'label'       => esc_html__( 'Custom Parent Class', 'tlp-food-menu' ),
				'class'       => 'medium-text',
				'description' => esc_html__( 'Please enter custom parent class for adding custom css.', 'tlp-food-menu' ),
			],
		];

		return apply_filters( 'fmp_sc_style', $scStyleGeneralFields );
	}

	/**
	 * Style button BG color field options
	 *
	 * @return array
	 */
	public static function scStyleButtonBgColorFields() {
		$scStyleButtonBgColorFields = [
			'fmp_button_bg_color'         => [
				'type'        => 'colorpicker',
				'label'       => esc_html__( 'Background Color', 'tlp-food-menu' ),
				'description' => __( '<b>Note:</b> You need to choose both colors for gradient background. If you need a single color, please choose only the 1st color.', 'tlp-food-menu' ),
			],
			'fmp_button_bg_color_2'       => [
				'type'  => 'colorpicker',
				'label' => esc_html__( 'Gradient Background (2nd Color)', 'tlp-food-menu' ),
			],
			'fmp_button_hover_bg_color'   => [
				'type'        => 'colorpicker',
				'label'       => esc_html__( 'Hover Background Color', 'tlp-food-menu' ),
				'description' => __( '<b>Note:</b> You need to choose both colors for gradient background. If you need a single color, please choose only the 1st color.', 'tlp-food-menu' ),
			],
			'fmp_button_hover_bg_color_2' => [
				'type'  => 'colorpicker',
				'label' => esc_html__( 'Hover Gradient Background (2nd Color)', 'tlp-food-menu' ),
			],
			'fmp_button_active_bg_color'   => [
				'type'        => 'colorpicker',
				'label'       => esc_html__( 'Active Background Color', 'tlp-food-menu' ),
				'description' => __( '<b>Note:</b> You need to choose both colors for gradient background. If you need a single color, please choose only the 1st color.', 'tlp-food-menu' ),
			],
			'fmp_button_active_bg_color_2' => [
				'type'  => 'colorpicker',
				'label' => esc_html__( 'Active Gradient Background (2nd Color)', 'tlp-food-menu' ),
			],
		];

		return apply_filters( 'fmp_sc_btn_bg_style', $scStyleButtonBgColorFields );
	}

	/**
	 * Style button color field options
	 *
	 * @return array
	 */
	public static function scStyleButtonColorFields() {
		$scStyleButtonColorFields = [
			'fmp_button_text_color'       => [
				'type'  => 'colorpicker',
				'label' => esc_html__( 'Text color', 'tlp-food-menu' ),
			],
			'fmp_button_hover_text_color' => [
				'type'  => 'colorpicker',
				'label' => esc_html__( 'Hover Text Color', 'tlp-food-menu' ),
			],
			'fmp_button_active_text_color' => [
				'type'  => 'colorpicker',
				'label' => esc_html__( 'Active Text Color', 'tlp-food-menu' ),
			],
			'fmp_button_typo'             => [
				'type'  => 'style',
				'label' => esc_html__( 'Typography', 'tlp-food-menu' ),
			],
		];

		return apply_filters( 'fmp_sc_btn_color_style', $scStyleButtonColorFields );
	}

	/**
	 * Style content field options
	 *
	 * @return array
	 */
	public static function scStyleContentFields() {
		$scStyleContentFields = [
			'fmp_title_style'    => [
				'type'  => 'style',
				'label' => esc_html__( 'Title', 'tlp-food-menu' ),
			],
			'fmp_price_style'    => [
				'type'  => 'style',
				'label' => esc_html__( 'Price', 'tlp-food-menu' ),
			],
			'fmp_border_color'   => [
				'type'        => 'colorpicker',
				'holderClass' => 'fmp-border-color-item rtfm-hidden',
				'label'       => esc_html__( 'Vertical Border Color', 'tlp-food-menu' ),
			],
			'fmp_category_style' => [
				'type'        => 'category-style',
				'label'       => esc_html__( 'Category Banner', 'tlp-food-menu' ),
				'description' => __( '<b>Note:</b> You need to choose both colors for gradient background. If you need a single color, please choose only the 1st color.', 'tlp-food-menu' ),
			],
		];

		return apply_filters( 'fmp_sc_content_style', $scStyleContentFields );
	}

	/**
	 * Style extra field options
	 *
	 * @return array
	 */
	public static function scStyleExtraFields() {
		$scStyleExtraFields = [
			'fmp_content_wrap' => [
				'type'        => 'group',
				'label'       => esc_html__( 'Element / Content Wrapper', 'tlp-food-menu' ),
				'description' => __( 'Please enter the content/element border radius. Please enter with unit. Example: 10px. <br>Leave it blank to keep layout default border radius.', 'tlp-food-menu' ),
			],
			'fmp_section_wrap' => [
				'type'        => 'group',
				'label'       => esc_html__( 'Full Section Wrapper', 'tlp-food-menu' ),
				'description' => __( 'Please enter the section border radius. Please enter with unit. Example: 10px. <br>Leave it blank to keep layout default border radius.', 'tlp-food-menu' ),
			],
		];

		return apply_filters( 'fmp_sc_extra_style', $scStyleExtraFields );
	}

	/**
	 * ShortCode Layout Options
	 *
	 * @return array
	 */
	public static function scLayoutMetaFields() {
		$scLayoutMetaFields = [
			'fmp_layout_type' => [
				'type'    => 'radio-image',
				'label'   => esc_html__( 'Select Layout Type', 'tlp-food-menu' ),
				'options' => self::scLayoutTypes(),
				'default' => 'grid',
			],

			'fmp_layout'      => [
				'type'    => 'radio-image',
				'label'   => esc_html__( 'Select Layout', 'tlp-food-menu' ),
				'class'   => 'fmp-select2',
				'options' => self::scLayouts(),
				'default' => 'layout-free',
			],

			'fmp_grid_style'  => [
				'type'        => 'radio',
				'label'       => esc_html__( 'Grid Style', 'tlp-food-menu' ),
				'alignment'   => 'vertical',
				'holderClass' => ! TLPFoodMenu()->has_pro() ? 'rt-pro-field' : '',
				'options'     => self::scGridStyle(),
				'default'     => 'even',
				'description' => esc_html__( 'Please select the grid style.', 'tlp-food-menu' ),
			],
		];

		return apply_filters( 'fmp_sc_layout_settings', $scLayoutMetaFields );
	}

	/**
	 * ShortCode Responsive Options
	 *
	 * @return array
	 */
	public static function scResponsiveMetaFields() {
		$scResponsiveMetaFields = [
			'fmp_desktop_column' => [
				'type'    => 'select',
				'label'   => __( 'Desktop Columns <i>(For devices > 991px)</i>', 'tlp-food-menu' ),
				'id'      => 'fmp_column',
				'class'   => 'fmp-select2',
				'default' => 0,
				'options' => self::scColumns(),
			],
			'fmp_tab_column'     => [
				'type'    => 'select',
				'label'   => __( 'Tab Columns <i>(For devices < 991px)</i>', 'tlp-food-menu' ),
				'id'      => 'fmp_column',
				'class'   => 'fmp-select2',
				'default' => 0,
				'options' => self::scColumns(),
			],
			'fmp_mobile_column'  => [
				'type'    => 'select',
				'label'   => __( 'Mobile Columns <i>(For devices < 768px)</i>', 'tlp-food-menu' ),
				'id'      => 'fmp_column',
				'class'   => 'fmp-select2',
				'default' => 0,
				'options' => self::scColumns(),
			],
		];

		return apply_filters( 'fmp_sc_responsive_settings', $scResponsiveMetaFields );
	}

	/**
	 * ShortCode Responsive Options
	 *
	 * @return array
	 */
	public static function scPaginationFields() {
		$scPaginationFields = [
			'fmp_pagination'      => [
				'type'        => 'checkbox',
				'label'       => esc_html__( 'Enable Pagination?', 'tlp-food-menu' ),
				'optionLabel' => esc_html__( 'Enable', 'tlp-food-menu' ),
				'description' => esc_html__( 'Switch on to enable pagination.', 'tlp-food-menu' ),
				'option'      => 1,
			],

			'fmp_pagination_type' => [
				'type'        => 'select',
				'label'       => esc_html__( 'Pagination type', 'tlp-food-menu' ),
				'class'       => 'fmp-select2',
				'holderClass' => 'fmp-pagination-item rtfm-hidden',
				'alignment'   => 'vertical',
				'default'     => 'pagination',
				'description' => sprintf(
					'%s%s',
					esc_html__( 'Please choose the pagination type.', 'tlp-food-menu' ),
					! TLPFoodMenu()->has_pro() ? __( '<br><b style="font-size: 13px"><a href="https://www.radiustheme.com/downloads/food-menu-pro-wordpress/" target="_blank" style="color: #de0000">Upgrade to PRO</a> to unlock Load More and Ajax Pagination.</b>', 'tlp-food-menu' ) : ''
				),
				'options'     => self::paginationType(),
			],

			'fmp_posts_per_page'  => [
				'type'        => 'number',
				'label'       => esc_html__( 'Number of Posts Per Page', 'tlp-food-menu' ),
				'description' => esc_html__( 'Please enter the number of posts to show per page.', 'tlp-food-menu' ),
				'holderClass' => 'rtfm-hidden',
				'default'     => 8,
			],
		];

		return apply_filters( 'fmp_sc_pagination_settings', $scPaginationFields );
	}

	/**
	 * ShortCode Image Options
	 *
	 * @return array
	 */
	public static function scImageMetaFields() {
		$scImageMetaFields = [
			'fmp_image_size'     => [
				'type'        => 'select',
				'label'       => esc_html__( 'Image Size', 'tlp-food-menu' ),
				'class'       => 'fmp-select2',
				'description' => sprintf(
					'%s%s',
					esc_html__( 'Please select the featured image dimension.', 'tlp-food-menu' ),
					! TLPFoodMenu()->has_pro() ? __( '<br><b style="font-size: 13px"><a href="https://www.radiustheme.com/downloads/food-menu-pro-wordpress/" target="_blank" style="color: #de0000">Upgrade to PRO</a> to unlock Custom Image Size.</b>', 'tlp-food-menu' ) : ''
				),
				'options'     => Fns::get_image_sizes(),
				'default'     => 'medium',
			],

			'fmp_image_radius'   => [
				'type'        => 'text',
				'label'       => esc_html__( 'Border Radius', 'tlp-food-menu' ),
				'class'       => 'fmp-select2',
				'description' => __( 'Please enter the featured image border radius. Please enter with unit. Example: 10px. <br>Leave it blank to keep layout default border radius.', 'tlp-food-menu' ),
			],

			'fmp_image_position' => [
				'type'        => 'radio',
				'label'       => esc_html__( 'Image Position', 'tlp-food-menu' ),
				'alignment'   => 'horizontal',
				'holderClass' => ! TLPFoodMenu()->has_pro() ? 'rt-pro-field' : '',
				'default'     => 'top',
				'description' => __( 'Please select the featured image position.', 'tlp-food-menu' ),
				'options'     => Fns::get_image_position(),
			],

			'fmp_image_hover'    => [
				'type'        => 'select',
				'label'       => esc_html__( 'Hover Animation', 'tlp-food-menu' ),
				'class'       => 'fmp-select2',
				'holderClass' => ! TLPFoodMenu()->has_pro() ? 'rt-pro-field' : '',
				'default'     => 'zoom_in',
				'description' => __( 'Please select the featured image hover animation.', 'tlp-food-menu' ),
				'options'     => Fns::get_image_hover(),
			],

			'fmp_hover_icon'     => [
				'type'        => 'checkbox',
				'label'       => esc_html__( 'Enable Hover Icon?', 'tlp-food-menu' ),
				'optionLabel' => esc_html__( 'Enable', 'tlp-food-menu' ),
				'description' => esc_html__( 'Switch on to enable hover icon.', 'tlp-food-menu' ),
				'default'     => 0,
				'option'      => 1,
			],
		];

		return apply_filters( 'fmp_sc_image_settings', $scImageMetaFields );
	}

	/**
	 * ShortCode Category Title Options
	 *
	 * @return array
	 */
	public static function scCategoryTitleFields() {

		$scCategoryTitleFields = [
			'fmp_category_title_type' => [
				'type'        => 'select',
				'label'       => esc_html__( 'Category Title Type', 'tlp-food-menu' ),
				'class'       => 'fmp-select2',
				'default'     => 'default',
				'description' => esc_html__( 'Please select the category title type.', 'tlp-food-menu' ),
				'options'     => Fns::get_category_title_types(),
			],
		];

		return apply_filters( 'fmp_sc_category_title_settings', $scCategoryTitleFields );
	}

	/**
	 * ShortCode Excerpt Options
	 *
	 * @return array
	 */
	public static function scExcerptMetaFields() {

		$scExcerptMetaFields = [
			'fmp_excerpt_limit'       => [
				'type'        => 'number',
				'label'       => esc_html__( 'Excerpt limit', 'tlp-food-menu' ),
				'description' => __( 'Limits the Excerpt text (letter limit). Leave it blank for full excerpt.<br> <strong>Please note that, HTML tags will not work if excerpt limit is applied.</strong>', 'tlp-food-menu' ),
			],

			'fmp_excerpt_custom_text' => [
				'label'       => __( 'Custom Text <br>After Excerpt', 'tlp-food-menu' ),
				'type'        => 'text',
				'class'       => 'full',
				'description' => esc_html__( 'Adds texts after excerpt.', 'tlp-food-menu' ),
			],
		];

		return apply_filters( 'fmp_sc_excerpt_settings', $scExcerptMetaFields );
	}

	/**
	 * ShortCode Details Options
	 *
	 * @return array
	 */
	public static function scDetailsMetaFields() {

		$scDetailsMetaFields = [
			'fmp_detail_page_link'   => [
				'type'        => 'checkbox',
				'label'       => esc_html__( 'Link to Detail Page?', 'tlp-food-menu' ),
				'optionLabel' => esc_html__( 'Enable', 'tlp-food-menu' ),
				'description' => esc_html__( 'Switch on to enable linking to detail page.', 'tlp-food-menu' ),
				'default'     => 1,
				'option'      => 1,
			],

			'fmp_single_food_popup'  => [
				'type'        => 'checkbox',
				'label'       => esc_html__( 'Enable Details Page Popup?', 'tlp-food-menu' ),
				'holderClass' => 'fmp_single_food_popup fmp-hidden',
				'optionLabel' => esc_html__( 'Enable', 'tlp-food-menu' ),
				'default'     => 1,
				'option'      => 1,
				'description' => esc_html__( 'Switch on to enable details page popup.', 'tlp-food-menu' ),
			],

			'fmp_detail_page_target' => [
				'type'        => 'radio',
				'label'       => esc_html__( 'Link Target', 'tlp-food-menu' ),
				'alignment'   => 'vertical',
				'holderClass' => 'rtfm-hidden',
				'default'     => '_self',
				'description' => esc_html__( 'Please select the detail page link target.', 'tlp-food-menu' ),
				'options'     => Fns::get_details_target(),
			],
		];

		return apply_filters( 'fmp_sc_details_settings', $scDetailsMetaFields );
	}

	public static function imageCropType() {
		return [
			'soft' => esc_html__( 'Soft Crop', 'tlp-food-menu' ),
			'hard' => esc_html__( 'Hard Crop', 'tlp-food-menu' ),
		];
	}

	/**
	 * Layout array
	 *
	 * @return array
	 */
	public static function scLayout() {
		$layouts = [
			'layout-free'      => esc_html__( 'Layout (Free)', 'tlp-food-menu' ),
			'grid-by-cat-free' => esc_html__( 'Grid By Category (Free)', 'tlp-food-menu' ),
		];

		return apply_filters( 'fmp_sc_layout_type', $layouts );
	}

	/**
	 * Layouts array
	 *
	 * @return array
	 */
	public static function scLayouts() {
		$layouts = [
			'layout-free'        => [
				'title'  => esc_html__( 'List Layout 1', 'tlp-food-menu' ),
				'layout' => 'list',
				'img'    => TLPFoodMenu()->assets_url() . 'images/layouts/list-layout-1.png',
				// 'layout_link' => esc_url( 'https://www.radiustheme.com/demo/plugins/food-menu/' ),
			],
			'layout-free-4'      => [
				'title'  => esc_html__( 'List Layout 2', 'tlp-food-menu' ),
				'layout' => 'list',
				'img'    => TLPFoodMenu()->assets_url() . 'images/layouts/list-layout-2.png',
				// 'layout_link' => esc_url( 'https://www.radiustheme.com/demo/plugins/food-menu/' ),
			],
			'layout-free-3'      => [
				'title'  => esc_html__( 'List Layout 3', 'tlp-food-menu' ),
				'layout' => 'list',
				'img'    => TLPFoodMenu()->assets_url() . 'images/layouts/list-layout-3.png',
				// 'layout_link' => esc_url( 'https://www.radiustheme.com/demo/plugins/food-menu/' ),
			],
			'layout-free-2'      => [
				'title'  => esc_html__( 'List Layout 4', 'tlp-food-menu' ),
				'layout' => 'list',
				'img'    => TLPFoodMenu()->assets_url() . 'images/layouts/list-layout-4.png',
				// 'layout_link' => esc_url( 'https://www.radiustheme.com/demo/plugins/food-menu/' ),
			],
			'grid-by-cat-free'   => [
				'title'  => esc_html__( 'Grid By Category 1', 'tlp-food-menu' ),
				'layout' => 'grid-by-cat',
				'img'    => TLPFoodMenu()->assets_url() . 'images/layouts/grid-by-category-1.png',
				// 'layout_link' => esc_url( 'https://www.radiustheme.com/demo/plugins/food-menu/menu-by-category/' ),
			],
			// 'grid-by-cat-free-2' => [
			// 	'title'  => esc_html__( 'Grid By Category 2', 'tlp-food-menu' ),
			// 	'layout' => 'grid-by-cat',
			// 	'img'    => TLPFoodMenu()->assets_url() . 'images/layouts/Grid-by-category-free.png',
			// 	// 'layout_link' => esc_url( 'https://www.radiustheme.com/demo/plugins/food-menu/menu-by-category/' ),
			// ],
			'grid-by-cat-free-3' => [
				'title'  => esc_html__( 'Grid By Category 2', 'tlp-food-menu' ),
				'layout' => 'grid-by-cat',
				'img'    => TLPFoodMenu()->assets_url() . 'images/layouts/grid-by-category-3.png',
				// 'layout_link' => esc_url( 'https://www.radiustheme.com/demo/plugins/food-menu/menu-by-category/' ),
			],
			'grid-by-cat-free-4' => [
				'title'  => esc_html__( 'Grid By Category 3', 'tlp-food-menu' ),
				'layout' => 'grid-by-cat',
				'img'    => TLPFoodMenu()->assets_url() . 'images/layouts/grid-by-category-4.png',
				// 'layout_link' => esc_url( 'https://www.radiustheme.com/demo/plugins/food-menu/menu-by-category/' ),
			],
			'grid-by-cat-free-5' => [
				'title'  => esc_html__( 'Grid By Category 4', 'tlp-food-menu' ),
				'layout' => 'grid-by-cat',
				'img'    => TLPFoodMenu()->assets_url() . 'images/layouts/grid-by-category-5.png',
				// 'layout_link' => esc_url( 'https://www.radiustheme.com/demo/plugins/food-menu/menu-by-category/' ),
			],
		];

		return apply_filters( 'fmp_sc_layouts', $layouts );
	}

	public static function scLayoutTypes() {
		$types = [
			'list'        => [
				'title' => esc_html__( 'List Layouts', 'tlp-food-menu' ),
				'img'   => TLPFoodMenu()->assets_url() . 'images/layouts/list-layouts.png',
			],
			'grid-by-cat' => [
				'title' => esc_html__( 'Grid by Category Layouts', 'tlp-food-menu' ),
				'img'   => TLPFoodMenu()->assets_url() . 'images/layouts/grid-by-category-layouts.png',
			],
		];

		return apply_filters( 'fmp_sc_layout_types', $types );
	}

	/**
	 * Grid Style Options
	 *
	 * @return array
	 */
	public static function scGridStyle() {
		$columns = [
			'even'    => esc_html__( 'Even Grid', 'tlp-food-menu' ),
			'masonry' => esc_html__( 'Masonry Grid', 'tlp-food-menu' ),
		];

		return apply_filters( 'fmp_grid_style', $columns );
	}

	/**
	 * Column Options
	 *
	 * @return array
	 */
	public static function scColumns() {
		$columns = [
			0 => esc_html__( 'Layout Default', 'tlp-food-menu' ),
			1 => esc_html__( '1 Columns', 'tlp-food-menu' ),
			2 => esc_html__( '2 Columns', 'tlp-food-menu' ),
			3 => esc_html__( '3 Columns', 'tlp-food-menu' ),
			4 => esc_html__( '4 Columns', 'tlp-food-menu' ),
			5 => esc_html__( '5 Columns', 'tlp-food-menu' ),
			6 => esc_html__( '6 Columns', 'tlp-food-menu' ),
		];

		return apply_filters( 'fmp_sc_columns', $columns );
	}

	/**
	 * Filter Options
	 *
	 * @return array
	 */
	public static function scFilterMetaFields() {

		$scFilterMetaFields = [
			'fmp_source'       => [
				'label'       => esc_html__( 'Food item data source', 'tlp-food-menu' ),
				'type'        => 'radio',
				'options'     => self::scProductSource(),
				'default'     => TLPFoodMenu()->post_type,
				'alignment'   => 'vertical',
				'description' => esc_html__( 'Please select a food item data source', 'tlp-food-menu' ),
			],
			'fmp_post__in'     => [
				'label'       => esc_html__( 'Include Posts', 'tlp-food-menu' ),
				'type'        => 'text',
				'class'       => 'full',
				'description' => __( 'List of post IDs to diplay (comma-separated values, for example: 1,2,3). <br>Set empty to show all posts.', 'tlp-food-menu' ),
			],
			'fmp_post__not_in' => [
				'label'       => esc_html__( 'Exclude Posts', 'tlp-food-menu' ),
				'type'        => 'text',
				'class'       => 'full',
				'description' => __( 'List of post IDs to exclude (comma-separated values, for example: 1,2,3) <br>Set empty to show all posts.', 'tlp-food-menu' ),
			],
			'fmp_limit'        => [
				'label'       => esc_html__( 'Posts Limit', 'tlp-food-menu' ),
				'type'        => 'number',
				'class'       => 'full',
				'description' => esc_html__( 'The number of posts to show. Set empty to show all posts.', 'tlp-food-menu' ),
			],
			'fmp_categories'   => [
				'label'       => esc_html__( 'Categories', 'tlp-food-menu' ),
				'type'        => 'select',
				'class'       => 'fmp-select2',
				'multiple'    => true,
				'description' => esc_html__( 'Select the category you want to filter, Leave it blank for all category.', 'tlp-food-menu' ),
				'options'     => Fns::getAllFmpCategoryList(),
			],
			'fmp_order_by'     => [
				'label'       => esc_html__( 'Order By', 'tlp-food-menu' ),
				'type'        => 'select',
				'class'       => 'fmp-select2',
				'default'     => 'date',
				'description' => esc_html__( 'Please choose to reorder posts.', 'tlp-food-menu' ),
				'options'     => self::scOrderBy(),
			],
			'fmp_order'        => [
				'label'       => esc_html__( 'Order', 'tlp-food-menu' ),
				'type'        => 'radio',
				'options'     => self::scOrder(),
				'description' => esc_html__( 'Please choose to reorder posts.', 'tlp-food-menu' ),
				'default'     => 'DESC',
				'alignment'   => 'vertical',
			],
		];

		return apply_filters( 'fmp_sc_filtering', $scFilterMetaFields );
	}

	public static function fmpCatOperators() {
		return [
			'IN'         => esc_html__( 'IN — show posts which associate with one or more of selected terms', 'tlp-food-menu' ),
			'NOT IN'     => esc_html__( 'NOT IN — show posts which do not associate with any of selected terms', 'tlp-food-menu' ),
			'AND'        => esc_html__( 'AND — show posts which associate with all of selected terms', 'tlp-food-menu' ),
			'EXISTS'     => esc_html__( 'EXISTS —', 'tlp-food-menu' ),
			'NOT EXISTS' => esc_html__( 'NOT EXISTS —', 'tlp-food-menu' ),
		];
	}

	/**
	 * Order By Options
	 *
	 * @return array
	 */
	private static function scOrderBy() {
		$order_by = [
			'menu_order' => esc_html__( 'Menu Order', 'tlp-food-menu' ),
			'title'      => esc_html__( 'Name', 'tlp-food-menu' ),
			'date'       => esc_html__( 'Date', 'tlp-food-menu' ),
			'price'      => esc_html__( 'Price', 'tlp-food-menu' ),
			'ID'         => esc_html__( 'ID', 'tlp-food-menu' ),
		];

		return apply_filters( 'fmp_sc_order_by', $order_by );
	}

	/**
	 * Order Options
	 *
	 * @return array
	 */
	private static function scOrder() {
		return [
			'ASC'  => esc_html__( 'Ascending', 'tlp-food-menu' ),
			'DESC' => esc_html__( 'Descending', 'tlp-food-menu' ),
		];
	}

	public static function scProductSource() {
		$source = [
			TLPFoodMenu()->post_type => esc_html__( 'Food Menu', 'tlp-food-menu' ),
		];

		if ( TLPFoodMenu()->isWcActive() ) {
			$source['product'] = esc_html__( 'WooCommerce', 'tlp-food-menu' );
		}

		return apply_filters( 'fmp_product_src', $source );
	}

	public static function currency_position_list() {
		return [
			'left'        => esc_html__( 'Left (£99.99)', 'tlp-food-menu' ),
			'right'       => esc_html__( 'Right (99.99£)', 'tlp-food-menu' ),
			'left_space'  => esc_html__( 'Left with space (£ 99.99)', 'tlp-food-menu' ),
			'right_space' => esc_html__( 'Right with space (99.99 £)', 'tlp-food-menu' ),
		];
	}

	public static function col_lists() {
		return [
			2 => esc_html__( 'Display in 2 column', 'tlp-food-menu' ),
			1 => esc_html__( 'Display in 1 column', 'tlp-food-menu' ),
		];
	}

	public static function currency_list() {
		return apply_filters( 'rtfm_currency_list', [
			'AED' => [
				'code'           => 'AED',
				'symbol'         => 'د.إ',
				'name'           => 'United Arab Emirates Dirham',
				'numeric_code'   => '784',
				'code_placement' => 'before',
				'minor_unit'     => 'Fils',
				'major_unit'     => 'Dirham',
			],
			'AFN' => [
				'code'         => 'AFN',
				'symbol'       => 'Af',
				'name'         => 'Afghan Afghani',
				'decimals'     => 0,
				'numeric_code' => '971',
				'minor_unit'   => 'Pul',
				'major_unit'   => 'Afghani',
			],
			'ANG' => [
				'code'         => 'ANG',
				'symbol'       => 'NAf.',
				'name'         => 'Netherlands Antillean Guilder',
				'numeric_code' => '532',
				'minor_unit'   => 'Cent',
				'major_unit'   => 'Guilder',
			],
			'AOA' => [
				'code'         => 'AOA',
				'symbol'       => 'Kz',
				'name'         => 'Angolan Kwanza',
				'numeric_code' => '973',
				'minor_unit'   => 'Cêntimo',
				'major_unit'   => 'Kwanza',
			],
			'ARM' => [
				'code'       => 'ARM',
				'symbol'     => 'm$n',
				'name'       => 'Argentine Peso Moneda Nacional',
				'minor_unit' => 'Centavos',
				'major_unit' => 'Peso',
			],
			'ARS' => [
				'code'         => 'ARS',
				'symbol'       => 'AR$',
				'name'         => 'Argentine Peso',
				'numeric_code' => '032',
				'minor_unit'   => 'Centavo',
				'major_unit'   => 'Peso',
			],
			'AUD' => [
				'code'             => 'AUD',
				'symbol'           => '$',
				'name'             => 'Australian Dollar',
				'numeric_code'     => '036',
				'symbol_placement' => 'before',
				'minor_unit'       => 'Cent',
				'major_unit'       => 'Dollar',
			],
			'AWG' => [
				'code'         => 'AWG',
				'symbol'       => 'Afl.',
				'name'         => 'Aruban Florin',
				'numeric_code' => '533',
				'minor_unit'   => 'Cent',
				'major_unit'   => 'Guilder',
			],
			'AZN' => [
				'code'       => 'AZN',
				'symbol'     => 'man.',
				'name'       => 'Azerbaijanian Manat',
				'minor_unit' => 'Qəpik',
				'major_unit' => 'New Manat',
			],
			'BAM' => [
				'code'         => 'BAM',
				'symbol'       => 'KM',
				'name'         => 'Bosnia-Herzegovina Convertible Mark',
				'numeric_code' => '977',
				'minor_unit'   => 'Fening',
				'major_unit'   => 'Convertible Marka',
			],
			'BBD' => [
				'code'         => 'BBD',
				'symbol'       => 'Bds$',
				'name'         => 'Barbadian Dollar',
				'numeric_code' => '052',
				'minor_unit'   => 'Cent',
				'major_unit'   => 'Dollar',
			],
			'BDT' => [
				'code'         => 'BDT',
				'symbol'       => 'Tk',
				'name'         => 'Bangladeshi Taka',
				'numeric_code' => '050',
				'minor_unit'   => 'Paisa',
				'major_unit'   => 'Taka',
			],
			'BGN' => [
				'code'                => 'BGN',
				'symbol'              => 'лв',
				'name'                => 'Bulgarian lev',
				'thousands_separator' => ' ',
				'decimal_separator'   => ',',
				'symbol_placement'    => 'after',
				'code_placement'      => 'hidden',
				'numeric_code'        => '975',
				'minor_unit'          => 'Stotinka',
				'major_unit'          => 'Lev',
			],
			'BHD' => [
				'code'         => 'BHD',
				'symbol'       => 'BD',
				'name'         => 'Bahraini Dinar',
				'decimals'     => 3,
				'numeric_code' => '048',
				'minor_unit'   => 'Fils',
				'major_unit'   => 'Dinar',
			],
			'BIF' => [
				'code'         => 'BIF',
				'symbol'       => 'FBu',
				'name'         => 'Burundian Franc',
				'decimals'     => 0,
				'numeric_code' => '108',
				'minor_unit'   => 'Centime',
				'major_unit'   => 'Franc',
			],
			'BMD' => [
				'code'         => 'BMD',
				'symbol'       => 'BD$',
				'name'         => 'Bermudan Dollar',
				'numeric_code' => '060',
				'minor_unit'   => 'Cent',
				'major_unit'   => 'Dollar',
			],
			'BND' => [
				'code'         => 'BND',
				'symbol'       => 'BN$',
				'name'         => 'Brunei Dollar',
				'numeric_code' => '096',
				'minor_unit'   => 'Sen',
				'major_unit'   => 'Dollar',
			],
			'BOB' => [
				'code'         => 'BOB',
				'symbol'       => 'Bs',
				'name'         => 'Bolivian Boliviano',
				'numeric_code' => '068',
				'minor_unit'   => 'Centavo',
				'major_unit'   => 'Bolivianos',
			],
			'BRL' => [
				'code'                => 'BRL',
				'symbol'              => 'R$',
				'name'                => 'Brazilian Real',
				'numeric_code'        => '986',
				'symbol_placement'    => 'before',
				'code_placement'      => 'hidden',
				'thousands_separator' => '.',
				'decimal_separator'   => ',',
				'minor_unit'          => 'Centavo',
				'major_unit'          => 'Reais',
			],
			'BSD' => [
				'code'         => 'BSD',
				'symbol'       => 'BS$',
				'name'         => 'Bahamian Dollar',
				'numeric_code' => '044',
				'minor_unit'   => 'Cent',
				'major_unit'   => 'Dollar',
			],
			'BTN' => [
				'code'         => 'BTN',
				'symbol'       => 'Nu.',
				'name'         => 'Bhutanese Ngultrum',
				'numeric_code' => '064',
				'minor_unit'   => 'Chetrum',
				'major_unit'   => 'Ngultrum',
			],
			'BWP' => [
				'code'         => 'BWP',
				'symbol'       => 'BWP',
				'name'         => 'Botswanan Pula',
				'numeric_code' => '072',
				'minor_unit'   => 'Thebe',
				'major_unit'   => 'Pulas',
			],
			'BYR' => [
				'code'                => 'BYR',
				'symbol'              => 'руб.',
				'name'                => 'Belarusian ruble',
				'numeric_code'        => '974',
				'symbol_placement'    => 'after',
				'code_placement'      => 'hidden',
				'decimals'            => 0,
				'thousands_separator' => ' ',
				'major_unit'          => 'Ruble',
			],
			'BZD' => [
				'code'         => 'BZD',
				'symbol'       => 'BZ$',
				'name'         => 'Belize Dollar',
				'numeric_code' => '084',
				'minor_unit'   => 'Cent',
				'major_unit'   => 'Dollar',
			],
			'CAD' => [
				'code'         => 'CAD',
				'symbol'       => 'CA$',
				'name'         => 'Canadian Dollar',
				'numeric_code' => '124',
				'minor_unit'   => 'Cent',
				'major_unit'   => 'Dollar',
			],
			'CDF' => [
				'code'         => 'CDF',
				'symbol'       => 'CDF',
				'name'         => 'Congolese Franc',
				'numeric_code' => '976',
				'minor_unit'   => 'Centime',
				'major_unit'   => 'Franc',
			],
			'CHF' => [
				'code'          => 'CHF',
				'symbol'        => 'Fr.',
				'name'          => 'Swiss Franc',
				'rounding_step' => '0.05',
				'numeric_code'  => '756',
				'minor_unit'    => 'Rappen',
				'major_unit'    => 'Franc',
			],
			'CLP' => [
				'code'         => 'CLP',
				'symbol'       => 'CL$',
				'name'         => 'Chilean Peso',
				'decimals'     => 0,
				'numeric_code' => '152',
				'minor_unit'   => 'Centavo',
				'major_unit'   => 'Peso',
			],
			'CNY' => [
				'code'                => 'CNY',
				'symbol'              => '¥',
				'name'                => 'Chinese Yuan Renminbi',
				'numeric_code'        => '156',
				'symbol_placement'    => 'before',
				'code_placement'      => 'hidden',
				'thousands_separator' => '',
				'minor_unit'          => 'Fen',
				'major_unit'          => 'Yuan',
			],
			'COP' => [
				'code'                => 'COP',
				'symbol'              => '$',
				'name'                => 'Colombian Peso',
				'decimals'            => 0,
				'numeric_code'        => '170',
				'symbol_placement'    => 'before',
				'code_placement'      => 'hidden',
				'thousands_separator' => '.',
				'decimal_separator'   => ',',
				'minor_unit'          => 'Centavo',
				'major_unit'          => 'Peso',
			],
			'CRC' => [
				'code'         => 'CRC',
				'symbol'       => '¢',
				'name'         => 'Costa Rican Colón',
				'decimals'     => 0,
				'numeric_code' => '188',
				'minor_unit'   => 'Céntimo',
				'major_unit'   => 'Colón',
			],
			'CUC' => [
				'code'       => 'CUC',
				'symbol'     => 'CUC$',
				'name'       => 'Cuban Convertible Peso',
				'minor_unit' => 'Centavo',
				'major_unit' => 'Peso',
			],
			'CUP' => [
				'code'         => 'CUP',
				'symbol'       => 'CU$',
				'name'         => 'Cuban Peso',
				'numeric_code' => '192',
				'minor_unit'   => 'Centavo',
				'major_unit'   => 'Peso',
			],
			'CVE' => [
				'code'         => 'CVE',
				'symbol'       => 'CV$',
				'name'         => 'Cape Verdean Escudo',
				'numeric_code' => '132',
				'minor_unit'   => 'Centavo',
				'major_unit'   => 'Escudo',
			],
			'CZK' => [
				'code'                => 'CZK',
				'symbol'              => 'Kč',
				'name'                => 'Czech Republic Koruna',
				'numeric_code'        => '203',
				'thousands_separator' => ' ',
				'decimal_separator'   => ',',
				'symbol_placement'    => 'after',
				'code_placement'      => 'hidden',
				'minor_unit'          => 'Haléř',
				'major_unit'          => 'Koruna',
			],
			'DJF' => [
				'code'         => 'DJF',
				'symbol'       => 'Fdj',
				'name'         => 'Djiboutian Franc',
				'numeric_code' => '262',
				'decimals'     => 0,
				'minor_unit'   => 'Centime',
				'major_unit'   => 'Franc',
			],
			'DKK' => [
				'code'                => 'DKK',
				'symbol'              => 'kr.',
				'name'                => 'Danish Krone',
				'numeric_code'        => '208',
				'thousands_separator' => ' ',
				'decimal_separator'   => ',',
				'symbol_placement'    => 'after',
				'code_placement'      => 'hidden',
				'minor_unit'          => 'Øre',
				'major_unit'          => 'Kroner',
			],
			'DOP' => [
				'code'         => 'DOP',
				'symbol'       => 'RD$',
				'name'         => 'Dominican Peso',
				'numeric_code' => '214',
				'minor_unit'   => 'Centavo',
				'major_unit'   => 'Peso',
			],
			'DZD' => [
				'code'         => 'DZD',
				'symbol'       => 'DA',
				'name'         => 'Algerian Dinar',
				'numeric_code' => '012',
				'minor_unit'   => 'Santeem',
				'major_unit'   => 'Dinar',
			],
			'EEK' => [
				'code'                => 'EEK',
				'symbol'              => 'Ekr',
				'name'                => 'Estonian Kroon',
				'thousands_separator' => ' ',
				'decimal_separator'   => ',',
				'numeric_code'        => '233',
				'minor_unit'          => 'Sent',
				'major_unit'          => 'Krooni',
			],
			'EGP' => [
				'code'         => 'EGP',
				'symbol'       => 'EG£',
				'name'         => 'Egyptian Pound',
				'numeric_code' => '818',
				'minor_unit'   => 'Piastr',
				'major_unit'   => 'Pound',
			],
			'ERN' => [
				'code'         => 'ERN',
				'symbol'       => 'Nfk',
				'name'         => 'Eritrean Nakfa',
				'numeric_code' => '232',
				'minor_unit'   => 'Cent',
				'major_unit'   => 'Nakfa',
			],
			'ETB' => [
				'code'         => 'ETB',
				'symbol'       => 'Br',
				'name'         => 'Ethiopian Birr',
				'numeric_code' => '230',
				'minor_unit'   => 'Santim',
				'major_unit'   => 'Birr',
			],
			'EUR' => [
				'code'                => 'EUR',
				'symbol'              => '€',
				'name'                => 'Euro',
				'thousands_separator' => ' ',
				'decimal_separator'   => ',',
				'symbol_placement'    => 'after',
				'code_placement'      => 'hidden',
				'numeric_code'        => '978',
				'minor_unit'          => 'Cent',
				'major_unit'          => 'Euro',
			],
			'FJD' => [
				'code'         => 'FJD',
				'symbol'       => 'FJ$',
				'name'         => 'Fijian Dollar',
				'numeric_code' => '242',
				'minor_unit'   => 'Cent',
				'major_unit'   => 'Dollar',
			],
			'FKP' => [
				'code'         => 'FKP',
				'symbol'       => 'FK£',
				'name'         => 'Falkland Islands Pound',
				'numeric_code' => '238',
				'minor_unit'   => 'Penny',
				'major_unit'   => 'Pound',
			],
			'GBP' => [
				'code'             => 'GBP',
				'symbol'           => '£',
				'name'             => 'British Pound Sterling',
				'numeric_code'     => '826',
				'symbol_placement' => 'before',
				'code_placement'   => 'hidden',
				'minor_unit'       => 'Penny',
				'major_unit'       => 'Pound',
			],
			'GHS' => [
				'code'       => 'GHS',
				'symbol'     => 'GH₵',
				'name'       => 'Ghanaian Cedi',
				'minor_unit' => 'Pesewa',
				'major_unit' => 'Cedi',
			],
			'GIP' => [
				'code'         => 'GIP',
				'symbol'       => 'GI£',
				'name'         => 'Gibraltar Pound',
				'numeric_code' => '292',
				'minor_unit'   => 'Penny',
				'major_unit'   => 'Pound',
			],
			'GMD' => [
				'code'         => 'GMD',
				'symbol'       => 'GMD',
				'name'         => 'Gambian Dalasi',
				'numeric_code' => '270',
				'minor_unit'   => 'Butut',
				'major_unit'   => 'Dalasis',
			],
			'GNF' => [
				'code'         => 'GNF',
				'symbol'       => 'FG',
				'name'         => 'Guinean Franc',
				'decimals'     => 0,
				'numeric_code' => '324',
				'minor_unit'   => 'Centime',
				'major_unit'   => 'Franc',
			],
			'GTQ' => [
				'code'         => 'GTQ',
				'symbol'       => 'GTQ',
				'name'         => 'Guatemalan Quetzal',
				'numeric_code' => '320',
				'minor_unit'   => 'Centavo',
				'major_unit'   => 'Quetzales',
			],
			'GYD' => [
				'code'         => 'GYD',
				'symbol'       => 'GY$',
				'name'         => 'Guyanaese Dollar',
				'decimals'     => 0,
				'numeric_code' => '328',
				'minor_unit'   => 'Cent',
				'major_unit'   => 'Dollar',
			],
			'HKD' => [
				'code'             => 'HKD',
				'symbol'           => 'HK$',
				'name'             => 'Hong Kong Dollar',
				'numeric_code'     => '344',
				'symbol_placement' => 'before',
				'code_placement'   => 'hidden',
				'minor_unit'       => 'Cent',
				'major_unit'       => 'Dollar',
			],
			'HNL' => [
				'code'         => 'HNL',
				'symbol'       => 'HNL',
				'name'         => 'Honduran Lempira',
				'numeric_code' => '340',
				'minor_unit'   => 'Centavo',
				'major_unit'   => 'Lempiras',
			],
			'HRK' => [
				'code'         => 'HRK',
				'symbol'       => 'kn',
				'name'         => 'Croatian Kuna',
				'numeric_code' => '191',
				'minor_unit'   => 'Lipa',
				'major_unit'   => 'Kuna',
			],
			'HTG' => [
				'code'         => 'HTG',
				'symbol'       => 'HTG',
				'name'         => 'Haitian Gourde',
				'numeric_code' => '332',
				'minor_unit'   => 'Centime',
				'major_unit'   => 'Gourde',
			],
			'HUF' => [
				'code'                => 'HUF',
				'symbol'              => 'Ft',
				'name'                => 'Hungarian Forint',
				'numeric_code'        => '348',
				'decimal_separator'   => ',',
				'thousands_separator' => ' ',
				'decimals'            => 0,
				'symbol_placement'    => 'after',
				'code_placement'      => 'hidden',
				'major_unit'          => 'Forint',
			],
			'IDR' => [
				'code'         => 'IDR',
				'symbol'       => 'Rp',
				'name'         => 'Indonesian Rupiah',
				'decimals'     => 0,
				'numeric_code' => '360',
				'minor_unit'   => 'Sen',
				'major_unit'   => 'Rupiahs',
			],
			'ILS' => [
				'code'             => 'ILS',
				'symbol'           => '₪',
				'name'             => 'Israeli New Shekel',
				'numeric_code'     => '376',
				'symbol_placement' => 'before',
				'code_placement'   => 'hidden',
				'minor_unit'       => 'Agora',
				'major_unit'       => 'New Shekels',
			],
			'INR' => [
				'code'         => 'INR',
				'symbol'       => 'Rs',
				'name'         => 'Indian Rupee',
				'numeric_code' => '356',
				'minor_unit'   => 'Paisa',
				'major_unit'   => 'Rupee',
			],
			'IRR' => [
				'code'             => 'IRR',
				'symbol'           => '﷼',
				'name'             => 'Iranian Rial',
				'numeric_code'     => '364',
				'symbol_placement' => 'after',
				'code_placement'   => 'hidden',
				'minor_unit'       => 'Rial',
				'major_unit'       => 'Toman',
			],
			'ISK' => [
				'code'                => 'ISK',
				'symbol'              => 'Ikr',
				'name'                => 'Icelandic Króna',
				'decimals'            => 0,
				'thousands_separator' => ' ',
				'numeric_code'        => '352',
				'minor_unit'          => 'Eyrir',
				'major_unit'          => 'Kronur',
			],
			'JMD' => [
				'code'             => 'JMD',
				'symbol'           => 'J$',
				'name'             => 'Jamaican Dollar',
				'numeric_code'     => '388',
				'symbol_placement' => 'before',
				'code_placement'   => 'hidden',
				'minor_unit'       => 'Cent',
				'major_unit'       => 'Dollar',
			],
			'JOD' => [
				'code'         => 'JOD',
				'symbol'       => 'JD',
				'name'         => 'Jordanian Dinar',
				'decimals'     => 3,
				'numeric_code' => '400',
				'minor_unit'   => 'Piastr',
				'major_unit'   => 'Dinar',
			],
			'JPY' => [
				'code'             => 'JPY',
				'symbol'           => '¥',
				'name'             => 'Japanese Yen',
				'decimals'         => 0,
				'numeric_code'     => '392',
				'symbol_placement' => 'before',
				'code_placement'   => 'hidden',
				'minor_unit'       => 'Sen',
				'major_unit'       => 'Yen',
			],
			'KES' => [
				'code'         => 'KES',
				'symbol'       => 'Ksh',
				'name'         => 'Kenyan Shilling',
				'numeric_code' => '404',
				'minor_unit'   => 'Cent',
				'major_unit'   => 'Shilling',
			],
			'KGS' => [
				'code'                => 'KGS',
				'code_placement'      => 'hidden',
				'symbol'              => 'сом',
				'symbol_placement'    => 'after',
				'name'                => 'Kyrgyzstani Som',
				'numeric_code'        => '417',
				'thousands_separator' => '',
				'major_unit'          => 'Som',
				'minor_unit'          => 'Tyiyn',
			],
			'KMF' => [
				'code'         => 'KMF',
				'symbol'       => 'CF',
				'name'         => 'Comorian Franc',
				'decimals'     => 0,
				'numeric_code' => '174',
				'minor_unit'   => 'Centime',
				'major_unit'   => 'Franc',
			],
			'KRW' => [
				'code'         => 'KRW',
				'symbol'       => '₩',
				'name'         => 'South Korean Won',
				'decimals'     => 0,
				'numeric_code' => '410',
				'minor_unit'   => 'Jeon',
				'major_unit'   => 'Won',
			],
			'KWD' => [
				'code'         => 'KWD',
				'symbol'       => 'KD',
				'name'         => 'Kuwaiti Dinar',
				'decimals'     => 3,
				'numeric_code' => '414',
				'minor_unit'   => 'Fils',
				'major_unit'   => 'Dinar',
			],
			'KYD' => [
				'code'         => 'KYD',
				'symbol'       => 'KY$',
				'name'         => 'Cayman Islands Dollar',
				'numeric_code' => '136',
				'minor_unit'   => 'Cent',
				'major_unit'   => 'Dollar',
			],
			'KZT' => [
				'code'                => 'KZT',
				'symbol'              => 'тг.',
				'name'                => 'Kazakhstani tenge',
				'numeric_code'        => '398',
				'thousands_separator' => ' ',
				'decimal_separator'   => ',',
				'symbol_placement'    => 'after',
				'code_placement'      => 'hidden',
				'minor_unit'          => 'Tiyn',
				'major_unit'          => 'Tenge',
			],
			'LAK' => [
				'code'         => 'LAK',
				'symbol'       => '₭N',
				'name'         => 'Laotian Kip',
				'decimals'     => 0,
				'numeric_code' => '418',
				'minor_unit'   => 'Att',
				'major_unit'   => 'Kips',
			],
			'LBP' => [
				'code'         => 'LBP',
				'symbol'       => 'LB£',
				'name'         => 'Lebanese Pound',
				'decimals'     => 0,
				'numeric_code' => '422',
				'minor_unit'   => 'Piastre',
				'major_unit'   => 'Pound',
			],
			'LKR' => [
				'code'         => 'LKR',
				'symbol'       => 'SLRs',
				'name'         => 'Sri Lanka Rupee',
				'numeric_code' => '144',
				'minor_unit'   => 'Cent',
				'major_unit'   => 'Rupee',
			],
			'LRD' => [
				'code'         => 'LRD',
				'symbol'       => 'L$',
				'name'         => 'Liberian Dollar',
				'numeric_code' => '430',
				'minor_unit'   => 'Cent',
				'major_unit'   => 'Dollar',
			],
			'LSL' => [
				'code'         => 'LSL',
				'symbol'       => 'LSL',
				'name'         => 'Lesotho Loti',
				'numeric_code' => '426',
				'minor_unit'   => 'Sente',
				'major_unit'   => 'Loti',
			],
			'LTL' => [
				'code'         => 'LTL',
				'symbol'       => 'Lt',
				'name'         => 'Lithuanian Litas',
				'numeric_code' => '440',
				'minor_unit'   => 'Centas',
				'major_unit'   => 'Litai',
			],
			'LVL' => [
				'code'         => 'LVL',
				'symbol'       => 'Ls',
				'name'         => 'Latvian Lats',
				'numeric_code' => '428',
				'minor_unit'   => 'Santims',
				'major_unit'   => 'Lati',
			],
			'LYD' => [
				'code'         => 'LYD',
				'symbol'       => 'LD',
				'name'         => 'Libyan Dinar',
				'decimals'     => 3,
				'numeric_code' => '434',
				'minor_unit'   => 'Dirham',
				'major_unit'   => 'Dinar',
			],
			'MAD' => [
				'code'             => 'MAD',
				'symbol'           => ' Dhs',
				'name'             => 'Moroccan Dirham',
				'numeric_code'     => '504',
				'symbol_placement' => 'after',
				'code_placement'   => 'hidden',
				'minor_unit'       => 'Santimat',
				'major_unit'       => 'Dirhams',
			],
			'MDL' => [
				'code'             => 'MDL',
				'symbol'           => 'MDL',
				'name'             => 'Moldovan leu',
				'symbol_placement' => 'after',
				'numeric_code'     => '498',
				'code_placement'   => 'hidden',
				'minor_unit'       => 'bani',
				'major_unit'       => 'Lei',
			],
			'MMK' => [
				'code'         => 'MMK',
				'symbol'       => 'MMK',
				'name'         => 'Myanma Kyat',
				'decimals'     => 0,
				'numeric_code' => '104',
				'minor_unit'   => 'Pya',
				'major_unit'   => 'Kyat',
			],
			'MNT' => [
				'code'         => 'MNT',
				'symbol'       => '₮',
				'name'         => 'Mongolian Tugrik',
				'decimals'     => 0,
				'numeric_code' => '496',
				'minor_unit'   => 'Möngö',
				'major_unit'   => 'Tugriks',
			],
			'MOP' => [
				'code'         => 'MOP',
				'symbol'       => 'MOP$',
				'name'         => 'Macanese Pataca',
				'numeric_code' => '446',
				'minor_unit'   => 'Avo',
				'major_unit'   => 'Pataca',
			],
			'MRO' => [
				'code'         => 'MRO',
				'symbol'       => 'UM',
				'name'         => 'Mauritanian Ouguiya',
				'decimals'     => 0,
				'numeric_code' => '478',
				'minor_unit'   => 'Khoums',
				'major_unit'   => 'Ouguiya',
			],
			'MTP' => [
				'code'       => 'MTP',
				'symbol'     => 'MT£',
				'name'       => 'Maltese Pound',
				'minor_unit' => 'Shilling',
				'major_unit' => 'Pound',
			],
			'MUR' => [
				'code'         => 'MUR',
				'symbol'       => 'MURs',
				'name'         => 'Mauritian Rupee',
				'decimals'     => 0,
				'numeric_code' => '480',
				'minor_unit'   => 'Cent',
				'major_unit'   => 'Rupee',
			],
			'MXN' => [
				'code'             => 'MXN',
				'symbol'           => '$',
				'name'             => 'Mexican Peso',
				'numeric_code'     => '484',
				'symbol_placement' => 'before',
				'code_placement'   => 'hidden',
				'minor_unit'       => 'Centavo',
				'major_unit'       => 'Peso',
			],
			'MYR' => [
				'code'             => 'MYR',
				'symbol'           => 'RM',
				'name'             => 'Malaysian Ringgit',
				'numeric_code'     => '458',
				'symbol_placement' => 'before',
				'code_placement'   => 'hidden',
				'minor_unit'       => 'Sen',
				'major_unit'       => 'Ringgits',
			],
			'MZN' => [
				'code'       => 'MZN',
				'symbol'     => 'MTn',
				'name'       => 'Mozambican Metical',
				'minor_unit' => 'Centavo',
				'major_unit' => 'Metical',
			],
			'NAD' => [
				'code'         => 'NAD',
				'symbol'       => 'N$',
				'name'         => 'Namibian Dollar',
				'numeric_code' => '516',
				'minor_unit'   => 'Cent',
				'major_unit'   => 'Dollar',
			],
			'NGN' => [
				'code'         => 'NGN',
				'symbol'       => '₦',
				'name'         => 'Nigerian Naira',
				'numeric_code' => '566',
				'minor_unit'   => 'Kobo',
				'major_unit'   => 'Naira',
			],
			'NIO' => [
				'code'         => 'NIO',
				'symbol'       => 'C$',
				'name'         => 'Nicaraguan Cordoba Oro',
				'numeric_code' => '558',
				'minor_unit'   => 'Centavo',
				'major_unit'   => 'Cordoba',
			],
			'NOK' => [
				'code'                => 'NOK',
				'symbol'              => 'Nkr',
				'name'                => 'Norwegian Krone',
				'thousands_separator' => ' ',
				'decimal_separator'   => ',',
				'numeric_code'        => '578',
				'minor_unit'          => 'Øre',
				'major_unit'          => 'Krone',
			],
			'NPR' => [
				'code'         => 'NPR',
				'symbol'       => 'NPRs',
				'name'         => 'Nepalese Rupee',
				'numeric_code' => '524',
				'minor_unit'   => 'Paisa',
				'major_unit'   => 'Rupee',
			],
			'NZD' => [
				'code'         => 'NZD',
				'symbol'       => 'NZ$',
				'name'         => 'New Zealand Dollar',
				'numeric_code' => '554',
				'minor_unit'   => 'Cent',
				'major_unit'   => 'Dollar',
			],
			'OMR' => [
				'code'         => 'OMR',
				'symbol'       => 'OMR',
				'name'         => 'Omani Rial',
				'numeric_code' => '512',
				'minor_unit'   => 'Baisa',
				'major_unit'   => 'Rials',
			],
			'PAB' => [
				'code'         => 'PAB',
				'symbol'       => 'B/.',
				'name'         => 'Panamanian Balboa',
				'numeric_code' => '590',
				'minor_unit'   => 'Centésimo',
				'major_unit'   => 'Balboa',
			],
			'PEN' => [
				'code'             => 'PEN',
				'symbol'           => 'S/.',
				'name'             => 'Peruvian Nuevo Sol',
				'numeric_code'     => '604',
				'symbol_placement' => 'before',
				'code_placement'   => 'hidden',
				'minor_unit'       => 'Céntimo',
				'major_unit'       => 'Nuevos Sole',
			],
			'PGK' => [
				'code'         => 'PGK',
				'symbol'       => 'PGK',
				'name'         => 'Papua New Guinean Kina',
				'numeric_code' => '598',
				'minor_unit'   => 'Toea',
				'major_unit'   => 'Kina ',
			],
			'PHP' => [
				'code'         => 'PHP',
				'symbol'       => '₱',
				'name'         => 'Philippine Peso',
				'numeric_code' => '608',
				'minor_unit'   => 'Centavo',
				'major_unit'   => 'Peso',
			],
			'PKR' => [
				'code'         => 'PKR',
				'symbol'       => 'PKRs',
				'name'         => 'Pakistani Rupee',
				'decimals'     => 0,
				'numeric_code' => '586',
				'minor_unit'   => 'Paisa',
				'major_unit'   => 'Rupee',
			],
			'PLN' => [
				'code'                => 'PLN',
				'symbol'              => 'zł',
				'name'                => 'Polish Złoty',
				'decimal_separator'   => ',',
				'thousands_separator' => ' ',
				'numeric_code'        => '985',
				'symbol_placement'    => 'after',
				'code_placement'      => 'hidden',
				'minor_unit'          => 'Grosz',
				'major_unit'          => 'Złotych',
			],
			'PYG' => [
				'code'         => 'PYG',
				'symbol'       => '₲',
				'name'         => 'Paraguayan Guarani',
				'decimals'     => 0,
				'numeric_code' => '600',
				'minor_unit'   => 'Céntimo',
				'major_unit'   => 'Guarani',
			],
			'QAR' => [
				'code'         => 'QAR',
				'symbol'       => 'QR',
				'name'         => 'Qatari Rial',
				'numeric_code' => '634',
				'minor_unit'   => 'Dirham',
				'major_unit'   => 'Rial',
			],
			'RHD' => [
				'code'       => 'RHD',
				'symbol'     => 'RH$',
				'name'       => 'Rhodesian Dollar',
				'minor_unit' => 'Cent',
				'major_unit' => 'Dollar',
			],
			'RON' => [
				'code'       => 'RON',
				'symbol'     => 'RON',
				'name'       => 'Romanian Leu',
				'minor_unit' => 'Ban',
				'major_unit' => 'Leu',
			],
			'RSD' => [
				'code'       => 'RSD',
				'symbol'     => 'din.',
				'name'       => 'Serbian Dinar',
				'decimals'   => 0,
				'minor_unit' => 'Para',
				'major_unit' => 'Dinars',
			],
			'RUB' => [
				'code'                => 'RUB',
				'symbol'              => 'руб.',
				'name'                => 'Russian Ruble',
				'thousands_separator' => ' ',
				'decimal_separator'   => ',',
				'numeric_code'        => '643',
				'symbol_placement'    => 'after',
				'code_placement'      => 'hidden',
				'minor_unit'          => 'Kopek',
				'major_unit'          => 'Ruble',
			],
			'SAR' => [
				'code'         => 'SAR',
				'symbol'       => 'SR',
				'name'         => 'Saudi Riyal',
				'numeric_code' => '682',
				'minor_unit'   => 'Hallallah',
				'major_unit'   => 'Riyals',
			],
			'SBD' => [
				'code'         => 'SBD',
				'symbol'       => 'SI$',
				'name'         => 'Solomon Islands Dollar',
				'numeric_code' => '090',
				'minor_unit'   => 'Cent',
				'major_unit'   => 'Dollar',
			],
			'SCR' => [
				'code'         => 'SCR',
				'symbol'       => 'SRe',
				'name'         => 'Seychellois Rupee',
				'numeric_code' => '690',
				'minor_unit'   => 'Cent',
				'major_unit'   => 'Rupee',
			],
			'SDD' => [
				'code'         => 'SDD',
				'symbol'       => 'LSd',
				'name'         => 'Old Sudanese Dinar',
				'numeric_code' => '736',
				'minor_unit'   => 'None',
				'major_unit'   => 'Dinar',
			],
			'SEK' => [
				'code'                => 'SEK',
				'symbol'              => 'kr',
				'name'                => 'Swedish Krona',
				'numeric_code'        => '752',
				'thousands_separator' => ' ',
				'decimal_separator'   => ',',
				'symbol_placement'    => 'after',
				'code_placement'      => 'hidden',
				'minor_unit'          => 'Öre',
				'major_unit'          => 'Kronor',
			],
			'SGD' => [
				'code'         => 'SGD',
				'symbol'       => 'S$',
				'name'         => 'Singapore Dollar',
				'numeric_code' => '702',
				'minor_unit'   => 'Cent',
				'major_unit'   => 'Dollar',
			],
			'SHP' => [
				'code'         => 'SHP',
				'symbol'       => 'SH£',
				'name'         => 'Saint Helena Pound',
				'numeric_code' => '654',
				'minor_unit'   => 'Penny',
				'major_unit'   => 'Pound',
			],
			'SLL' => [
				'code'         => 'SLL',
				'symbol'       => 'Le',
				'name'         => 'Sierra Leonean Leone',
				'decimals'     => 0,
				'numeric_code' => '694',
				'minor_unit'   => 'Cent',
				'major_unit'   => 'Leone',
			],
			'SOS' => [
				'code'         => 'SOS',
				'symbol'       => 'Ssh',
				'name'         => 'Somali Shilling',
				'decimals'     => 0,
				'numeric_code' => '706',
				'minor_unit'   => 'Cent',
				'major_unit'   => 'Shilling',
			],
			'SRD' => [
				'code'       => 'SRD',
				'symbol'     => 'SR$',
				'name'       => 'Surinamese Dollar',
				'minor_unit' => 'Cent',
				'major_unit' => 'Dollar',
			],
			'SRG' => [
				'code'         => 'SRG',
				'symbol'       => 'Sf',
				'name'         => 'Suriname Guilder',
				'numeric_code' => '740',
				'minor_unit'   => 'Cent',
				'major_unit'   => 'Guilder',
			],
			'STD' => [
				'code'         => 'STD',
				'symbol'       => 'Db',
				'name'         => 'São Tomé and Príncipe Dobra',
				'decimals'     => 0,
				'numeric_code' => '678',
				'minor_unit'   => 'Cêntimo',
				'major_unit'   => 'Dobra',
			],
			'SYP' => [
				'code'         => 'SYP',
				'symbol'       => 'SY£',
				'name'         => 'Syrian Pound',
				'decimals'     => 0,
				'numeric_code' => '760',
				'minor_unit'   => 'Piastre',
				'major_unit'   => 'Pound',
			],
			'SZL' => [
				'code'         => 'SZL',
				'symbol'       => 'SZL',
				'name'         => 'Swazi Lilangeni',
				'numeric_code' => '748',
				'minor_unit'   => 'Cent',
				'major_unit'   => 'Lilangeni',
			],
			'THB' => [
				'code'         => 'THB',
				'symbol'       => '฿',
				'name'         => 'Thai Baht',
				'numeric_code' => '764',
				'minor_unit'   => 'Satang',
				'major_unit'   => 'Baht',
			],
			'TND' => [
				'code'         => 'TND',
				'symbol'       => 'DT',
				'name'         => 'Tunisian Dinar',
				'decimals'     => 3,
				'numeric_code' => '788',
				'minor_unit'   => 'Millime',
				'major_unit'   => 'Dinar',
			],
			'TOP' => [
				'code'         => 'TOP',
				'symbol'       => 'T$',
				'name'         => 'Tongan Paʻanga',
				'numeric_code' => '776',
				'minor_unit'   => 'Senit',
				'major_unit'   => 'Paʻanga',
			],
			'TRY' => [
				'code'                => 'TRY',
				'symbol'              => 'TL',
				'name'                => 'Turkish Lira',
				'numeric_code'        => '949',
				'thousands_separator' => '.',
				'decimal_separator'   => ',',
				'symbol_placement'    => 'after',
				'code_placement'      => '',
				'minor_unit'          => 'Kurus',
				'major_unit'          => 'Lira',
			],
			'TTD' => [
				'code'         => 'TTD',
				'symbol'       => 'TT$',
				'name'         => 'Trinidad and Tobago Dollar',
				'numeric_code' => '780',
				'minor_unit'   => 'Cent',
				'major_unit'   => 'Dollar',
			],
			'TWD' => [
				'code'         => 'TWD',
				'symbol'       => 'NT$',
				'name'         => 'New Taiwan Dollar',
				'numeric_code' => '901',
				'minor_unit'   => 'Cent',
				'major_unit'   => 'New Dollar',
			],
			'TZS' => [
				'code'         => 'TZS',
				'symbol'       => 'TSh',
				'name'         => 'Tanzanian Shilling',
				'decimals'     => 0,
				'numeric_code' => '834',
				'minor_unit'   => 'Senti',
				'major_unit'   => 'Shilling',
			],
			'UAH' => [
				'code'                => 'UAH',
				'symbol'              => 'грн.',
				'name'                => 'Ukrainian Hryvnia',
				'numeric_code'        => '980',
				'thousands_separator' => '',
				'decimal_separator'   => '.',
				'symbol_placement'    => 'after',
				'code_placement'      => 'hidden',
				'minor_unit'          => 'Kopiyka',
				'major_unit'          => 'Hryvnia',
			],
			'UGX' => [
				'code'         => 'UGX',
				'symbol'       => 'USh',
				'name'         => 'Ugandan Shilling',
				'decimals'     => 0,
				'numeric_code' => '800',
				'minor_unit'   => 'Cent',
				'major_unit'   => 'Shilling',
			],
			'USD' => [
				'code'             => 'USD',
				'symbol'           => '$',
				'name'             => 'United States Dollar',
				'numeric_code'     => '840',
				'symbol_placement' => 'before',
				'code_placement'   => 'hidden',
				'minor_unit'       => 'Cent',
				'major_unit'       => 'Dollar',
			],
			'UYU' => [
				'code'         => 'UYU',
				'symbol'       => '$U',
				'name'         => 'Uruguayan Peso',
				'numeric_code' => '858',
				'minor_unit'   => 'Centésimo',
				'major_unit'   => 'Peso',
			],
			'VEF' => [
				'code'       => 'VEF',
				'symbol'     => 'Bs.F.',
				'name'       => 'Venezuelan Bolívar Fuerte',
				'minor_unit' => 'Céntimo',
				'major_unit' => 'Bolivares Fuerte',
			],
			'VND' => [
				'code'                => 'VND',
				'symbol'              => 'đ',
				'name'                => 'Vietnamese Dong',
				'decimals'            => 0,
				'thousands_separator' => '.',
				'symbol_placement'    => 'after',
				'symbol_spacer'       => '',
				'code_placement'      => 'hidden',
				'numeric_code'        => '704',
				'minor_unit'          => 'Hà',
				'major_unit'          => 'Dong',
			],
			'VUV' => [
				'code'         => 'VUV',
				'symbol'       => 'VT',
				'name'         => 'Vanuatu Vatu',
				'decimals'     => 0,
				'numeric_code' => '548',
				'major_unit'   => 'Vatu',
			],
			'WST' => [
				'code'         => 'WST',
				'symbol'       => 'WS$',
				'name'         => 'Samoan Tala',
				'numeric_code' => '882',
				'minor_unit'   => 'Sene',
				'major_unit'   => 'Tala',
			],
			'XAF' => [
				'code'         => 'XAF',
				'symbol'       => 'FCFA',
				'name'         => 'CFA Franc BEAC',
				'decimals'     => 0,
				'numeric_code' => '950',
				'minor_unit'   => 'Centime',
				'major_unit'   => 'Franc',
			],
			'XCD' => [
				'code'         => 'XCD',
				'symbol'       => 'EC$',
				'name'         => 'East Caribbean Dollar',
				'numeric_code' => '951',
				'minor_unit'   => 'Cent',
				'major_unit'   => 'Dollar',
			],
			'XOF' => [
				'code'         => 'XOF',
				'symbol'       => 'CFA',
				'name'         => 'CFA Franc BCEAO',
				'decimals'     => 0,
				'numeric_code' => '952',
				'minor_unit'   => 'Centime',
				'major_unit'   => 'Franc',
			],
			'XPF' => [
				'code'         => 'XPF',
				'symbol'       => 'CFPF',
				'name'         => 'CFP Franc',
				'decimals'     => 0,
				'numeric_code' => '953',
				'minor_unit'   => 'Centime',
				'major_unit'   => 'Franc',
			],
			'YER' => [
				'code'         => 'YER',
				'symbol'       => 'YR',
				'name'         => 'Yemeni Rial',
				'decimals'     => 0,
				'numeric_code' => '886',
				'minor_unit'   => 'Fils',
				'major_unit'   => 'Rial',
			],
			'ZAR' => [
				'code'             => 'ZAR',
				'symbol'           => 'R',
				'name'             => 'South African Rand',
				'numeric_code'     => '710',
				'symbol_placement' => 'before',
				'code_placement'   => 'hidden',
				'minor_unit'       => 'Cent',
				'major_unit'       => 'Rand',
			],
			'ZMK' => [
				'code'         => 'ZMK',
				'symbol'       => 'ZK',
				'name'         => 'Zambian Kwacha',
				'decimals'     => 0,
				'numeric_code' => '894',
				'minor_unit'   => 'Ngwee',
				'major_unit'   => 'Kwacha',
			],
			'MKD' => [
				'code'         => 'MKD',
				'symbol'       => 'ден',
				'name'         => 'Macedonian Denar',
				'decimals'     => 0,
				'minor_unit'   => 'Deni',
			],
		] );
	}

	public static function get_pro_feature_list() {
		$pro = 'https://www.radiustheme.com/downloads/food-menu-pro-wordpress/';
		return '<ol>
					<li>11 Amazing Layouts with Grid, Masonry, Isotope & Slider.</li>
					<li>Even and Masonry Grid for all Grid.</li>
					<li>Search field on Isotope</li>
					<li>Woocommerce Support</li>
					<li>Order by Id, Name, Create Date, Menu Order, Random & Price</li>
					<li>Display image size (thumbnail, medium, large, full and Custom Image Size)</li>
					<li>Ajax Pagination: Load more, Load on scroll and AJAX Number Pagination</li>
					<li>AJAX Number Pagination (only for Grid layouts)</li>
					<li>Single popup Menu Item Popup</li>
					<li>Overlay color and opacity control</li>
					<li>All Text color, size and Button Color control.</li>
				</ol>
				<a href="' . esc_url( $pro ) . '" class="rt-admin-btn" target="_blank">Get Pro Version</a>';
	}

	/**
	 * Pagination Type.
	 *
	 * @return array
	 */
	private static function paginationType() {
		return apply_filters(
			'fmp_sc_pagination_type',
			[
				'pagination' => esc_html__( 'Numbered Pagination', 'tlp-food-menu' ),
			]
		);
	}
}
