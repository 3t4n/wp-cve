<?php
/**
 * Metabox page configuration.
 *
 * @since      2.2.0
 * @package    Woo_Product_Slider
 * @subpackage Woo_Product_Slider/Admin/view
 * @author     ShapedPlugin <support@shapedplugin.com>
 */

use ShapedPlugin\WooProductSlider\Admin\views\models\classes\SPF_WPSP;

if ( ! defined( 'ABSPATH' ) ) {
	die; }
// Cannot access pages directly.

/**
 * Product slider metabox prefix.
 */
$prefix = 'sp_wps_shortcode_options';

$smart_brand_plugin_link = 'smart-brands-for-woocommerce/smart-brands-for-woocommerce.php';
$smart_brand_plugin_data = SPF_WPSP::plugin_installation_activation(
	$smart_brand_plugin_link,
	'Install Now',
	'activate_plugin',
	array(
		'ShapedPlugin\SmartBrands\SmartBrands',
		'ShapedPlugin\SmartBrandsPro\SmartBrandsPro',
	),
	'smart-brands-for-woocommerce'
);

// Woo quick view Plugin.
$quick_view_plugin_link = 'woo-quickview/woo-quick-view.php';
$quick_view_plugin_data = SPF_WPSP::plugin_installation_activation(
	$quick_view_plugin_link,
	'Install Now',
	'activate_plugin',
	array(
		'SP_Woo_Quick_View',
		'SP_Woo_Quick_View_Pro',
	),
	'woo-quickview'
);

/**
 * Create a metabox for product slider.
 */
SPF_WPSP::createMetabox(
	$prefix,
	array(
		'title'     => __( 'Slider Options', 'woo-product-slider' ),
		'post_type' => 'sp_wps_shortcodes',
		'context'   => 'normal',
		'class'     => 'wpsp-shortcode-options',
		'nav'       => 'inline',
		'preview'   => true,
	)
);

/**
 * General Settings section.
 */
SPF_WPSP::createSection(
	$prefix,
	array(
		'title'  => __( 'Filtering', 'woo-product-slider' ),
		'icon'   => 'fa fa-cog',
		'fields' => array(
			array(
				'id'       => 'product_type',
				'type'     => 'select',
				'title'    => __( 'Filter Products', 'woo-product-slider' ),
				'subtitle' => __( 'Filter the products you want to show.', 'woo-product-slider' ),
				'options'  => array(
					'latest_products'                  => array(
						'name' => __( 'Latest', 'woo-product-slider' ),
					),
					'featured_products'                => array(
						'name' => __( 'Featured', 'woo-product-slider' ),
					),
					'products_from_categories'         => array(
						'name'     => __( 'Category (Pro)', 'woo-product-slider' ),
						'pro_only' => true,
					),
					'products_from_tags'               => array(
						'name'     => __( 'Tag (Pro)', 'woo-product-slider' ),
						'pro_only' => true,
					),
					'best_selling_products'            => array(
						'name'     => __( 'Best Selling (Pro)', 'woo-product-slider' ),
						'pro_only' => true,
					),
					'related_products'                 => array(
						'name'     => __( 'Related (Pro)', 'woo-product-slider' ),
						'pro_only' => true,
					),
					'up_sells'                         => array(
						'name'     => __( 'Upsells (Pro)', 'woo-product-slider' ),
						'pro_only' => true,
					),
					'cross_sells'                      => array(
						'name'     => __( 'Cross-sells (Pro)', 'woo-product-slider' ),
						'pro_only' => true,
					),
					'top_rated_products'               => array(
						'name'     => __( 'Top Rated (Pro)', 'woo-product-slider' ),
						'pro_only' => true,
					),
					'on_sell_products'                 => array(
						'name'     => __( 'On Sale (Pro)', 'woo-product-slider' ),
						'pro_only' => true,
					),
					'specific_products'                => array(
						'name'     => __( 'Specific (Pro)', 'woo-product-slider' ),
						'pro_only' => true,
					),
					'most_viewed_products'             => array(
						'name'     => __( 'Most Viewed (Pro)', 'woo-product-slider' ),
						'pro_only' => true,
					),
					'recently_viewed_products'         => array(
						'name'     => __( 'Recently Viewed (Pro)', 'woo-product-slider' ),
						'pro_only' => true,
					),
					'products_from_sku'                => array(
						'name'     => __( 'SKU (Pro)', 'woo-product-slider' ),
						'pro_only' => true,
					),
					'products_from_attribute'          => array(
						'name'     => __( 'Attribute (Pro)', 'woo-product-slider' ),
						'pro_only' => true,
					),
					'products_from_free'               => array(
						'name'     => __( 'Free (Pro)', 'woo-product-slider' ),
						'pro_only' => true,
					),
					'products_from_exclude_categories' => array(
						'name'     => __( 'Exclude Category (Pro)', 'woo-product-slider' ),
						'pro_only' => true,
					),
					'products_from_exclude_tags'       => array(
						'name'     => __( 'Exclude Tag (Pro)', 'woo-product-slider' ),
						'pro_only' => true,
					),

				),
				'default'  => 'latest_products',
			),
			array(
				'id'       => 'hide_out_of_stock_product',
				'type'     => 'checkbox',
				'title'    => __( 'Hide Out of Stock Products', 'woo-product-slider' ),
				'subtitle' => __( 'Check to hide out of stock products.', 'woo-product-slider' ),
				'default'  => false,
			),
			array(
				'id'         => 'hide_on_sale_product',
				'type'       => 'checkbox',
				'class'      => 'pro_only_field',
				'attributes' => array( 'disabled' => 'disabled' ),
				'title'      => __( 'Hide On Sale Products', 'woo-product-slider' ),
				'subtitle'   => __( 'Check to hide on sale products.', 'woo-product-slider' ),
				'default'    => false,
			),
			array(
				'id'       => 'product_order_by',
				'type'     => 'select',
				'title'    => __( 'Order By', 'woo-product-slider' ),
				'subtitle' => __( 'Set a order by option.', 'woo-product-slider' ),
				'options'  => array(
					'ID'       => array(
						'name' => __( 'ID', 'woo-product-slider' ),
					),
					'date'     => array(
						'name' => __( 'Date', 'woo-product-slider' ),
					),
					'rand'     => array(
						'name' => __( 'Random', 'woo-product-slider' ),
					),
					'title'    => array(
						'name' => __( 'Title', 'woo-product-slider' ),
					),
					'modified' => array(
						'name' => __( 'Modified', 'woo-product-slider' ),
					),
				),
				'default'  => 'date',
			),
			array(
				'id'       => 'product_order',
				'type'     => 'select',
				'title'    => __( 'Order', 'woo-product-slider' ),
				'subtitle' => __( 'Set product order.', 'woo-product-slider' ),
				'options'  => array(
					'ASC'  => array(
						'name' => __( 'Ascending', 'woo-product-slider' ),
					),
					'DESC' => array(
						'name' => __( 'Descending', 'woo-product-slider' ),
					),
				),
				'default'  => 'DESC',
			),
			array(
				'id'       => 'number_of_total_products',
				'type'     => 'spinner',
				'title'    => __( 'Limit', 'woo-product-slider' ),
				'subtitle' => __( 'Set number of total products to show.', 'woo-product-slider' ),
				'sanitize' => 'spwps_sanitize_number_field',
				'default'  => 16,
				'max'      => 60000,
				'min'      => -1,
			),
			array(
				'type'    => 'notice',
				'content' => __( 'Want to increase your sales by highlighting and filtering specific product types? <a  href="https://wooproductslider.io/pricing/?ref=1" target="_blank"><b>Upgrade To Pro!</b></a>', 'woo-product-slider' ),
			),
		),
	)
);
SPF_WPSP::createSection(
	$prefix,
	array(
		'title'  => __( 'Templates', 'woo-product-slider' ),
		'icon'   => 'wps-icon-swatchbook-solid',
		'fields' => array(
			array(
				'id'         => 'layout_preset',
				'class'      => 'layout_preset',
				'type'       => 'image_select',
				'title'      => __( 'Layout Preset', 'woo-product-slider' ),
				'subtitle'   => __( 'Choose a layout preset.', 'woo-product-slider' ),
				'desc'       => __( 'Upgrade your shop with exclusive layouts and design freedom. <a href="https://wooproductslider.io/pricing/?ref=1" target="_blank"><b>Get Pro Now!</b></a>', 'woo-product-slider' ),
				'image_name' => true,
				'options'    => array(
					'slider'  => array(
						'img' => SPF_WPSP::include_plugin_url( 'assets/images/slider.svg' ),
					),
					'grid'    => array(
						'img' => SPF_WPSP::include_plugin_url( 'assets/images/grid.svg' ),
					),
					'masonry' => array(
						'img'      => SPF_WPSP::include_plugin_url( 'assets/images/masonry.svg' ),
						'pro_only' => true,
					),
					'table'   => array(
						'img'      => SPF_WPSP::include_plugin_url( 'assets/images/table.svg' ),
						'pro_only' => true,
					),
				),
				'default'    => 'slider',
			),
			array(
				'id'         => 'carousel_ticker_mode',
				'type'       => 'image_select',
				'class'      => 'hide-active-sign',
				'title'      => __( 'Slider Mode', 'woo-product-slider' ),
				'subtitle'   => __( 'Set slider mode.', 'woo-product-slider' ),
				'image_name' => true,
				'options'    => array(
					'standard' => array(
						'img' => SPF_WPSP::include_plugin_url( 'assets/images/standard.svg' ),
					),
					'ticker'   => array(
						'img'      => SPF_WPSP::include_plugin_url( 'assets/images/ticker.svg' ),
						'pro_only' => true,
					),
				),
				'default'    => 'standard',
				'title_info' => __( '<div class="spwps-info-label">Carousel Mode</div> <div class="spwps-short-content">This feature allows you to select the most suitable carousel mode between Standard, or Ticker (continuous scrolling).</div>', 'woo-product-slider' ),
				'dependency' => array( 'layout_preset', '==', 'slider', true ),
			),
			array(
				'id'       => 'number_of_column',
				'type'     => 'column',
				'title'    => __( 'Column(s)', 'woo-product-slider' ),
				'subtitle' => __( 'Set products column(s) in different devices.', 'woo-product-slider' ),
				'sanitize' => 'spwps_sanitize_number_array_field',
				'default'  => array(
					'number1' => '4',
					'number2' => '3',
					'number3' => '2',
					'number4' => '1',
				),
			),
			array(
				'id'            => 'product_margin',
				'type'          => 'spacing',
				'class'         => 'wps_item_margin_between',
				'title'         => __( 'Space', 'woo-product-slider' ),
				'subtitle'      => __( 'Set a space or margin between products.', 'woo-product-slider' ),
				'units'         => array(
					__( 'px', 'woo-product-slider' ),
				),
				'show_title'    => true,
				'all'           => true,
				'vertical'      => true,
				'all_icon'      => '<i class="fa fa-arrows-h" aria-hidden="true"></i>',
				'vertical_icon' => '<i class="fa fa-arrows-v" aria-hidden="true"></i>',
				'default'       => array(
					'all'      => '20',
					'vertical' => '20',
				),
				'attributes'    => array(
					'min' => 0,
				),
				'title_info'    => '<div class="spwps-img-tag"><img src="' . SPF_WPSP::include_plugin_url( 'assets/images/visual-preview/wps_space.svg' ) . '" alt="space between"></div><div class="spwps-info-label img">' . __( 'Space Between', 'woo-product-slider' ) . '</div>',
			),
			array(
				'id'         => 'template_style',
				'class'      => 'template_style',
				'type'       => 'button_set',
				'title'      => __( 'Template Type', 'woo-product-slider' ),
				'subtitle'   => __( 'Choose a template whether custom or pre-made.', 'woo-product-slider' ),
				'options'    => array(
					'custom'   => array(
						'name' => __( 'Custom', 'woo-product-slider' ),
					),
					'pre-made' => array(
						'name' => __( 'Pre-made Templates', 'woo-product-slider' ),
					),
				),
				'default'    => 'pre-made',
				'dependency' => array( 'layout_preset', '!=', 'table', true ),
			),
			array(
				'id'         => 'theme_style',
				'class'      => 'theme_style',
				'type'       => 'select',
				'title'      => __( 'Template Style', 'woo-product-slider' ),
				'subtitle'   => __( 'Select which template style you want to display. See <a href="https://wooproductslider.io/28-pre-made-product-templates/" target="_blank">templates</a> in action!', 'woo-product-slider' ),
				'desc'       => __( 'To unlock <strong>28+ Pre-made beautiful templates</strong>, <a href="https://wooproductslider.io/pricing/?ref=1" target="_blank"><b>Upgrade To Pro!</b></a>', 'woo-product-slider' ),
				'options'    => array(
					'theme_one'   => array(
						'name' => __( 'Template One', 'woo-product-slider' ),
					),
					'theme_two'   => array(
						'name' => __( 'Template Two', 'woo-product-slider' ),
					),
					'theme_three' => array(
						'name' => __( 'Template Three', 'woo-product-slider' ),
					),
					'theme_four'  => array(
						'name'     => __( '28+ Templates (Pro)', 'woo-product-slider' ),
						'pro_only' => true,
					),
				),
				'default'    => 'theme_one',
				'preview'    => true,
				'dependency' => array( 'template_style|layout_preset', '==|!=', 'pre-made|table', true ),
			),

			array(
				'id'         => 'content_position',
				'type'       => 'image_select',
				'class'      => 'grid_style',
				'title'      => __( 'Product Content Position', 'woo-product-slider' ),
				'subtitle'   => __( 'Select a position for the product name, content, meta etc.', 'woo-product-slider' ),
				'image_name' => true,
				'options'    => array(
					'bottom'  => array(
						'img' => SPF_WPSP::include_plugin_url( 'assets/images/content-position/bottom.svg' ),
					),
					'top'     => array(
						'img'      => SPF_WPSP::include_plugin_url( 'assets/images/content-position/top.svg' ),
						'pro_only' => true,
					),
					'right'   => array(
						'img'      => SPF_WPSP::include_plugin_url( 'assets/images/content-position/right.svg' ),
						'pro_only' => true,
					),
					'left'    => array(
						'img'      => SPF_WPSP::include_plugin_url( 'assets/images/content-position/left.svg' ),
						'pro_only' => true,
					),
					'overlay' => array(
						'img'      => SPF_WPSP::include_plugin_url( 'assets/images/content-position/overlay.svg' ),
						'pro_only' => true,

					),
				),
				'title_info' => __( '<div class="spwps-info-label">Product Content Position</div> <div class="spwps-short-content">This feature allows you to select the placement of the product content position.</div><div class="info-button"><a class="spwps-open-live-demo" href="https://wooproductslider.io/5-product-content-positions/" target="_blank">Live Demo</a></div>', 'woo-product-slider' ),
				'default'    => 'bottom',
				'dependency' => array( 'template_style|layout_preset', '==|!=', 'custom|table', true ),
			),
			array(
				'id'         => 'product_content_padding',
				'type'       => 'spacing',
				'title'      => __( 'Content Padding', 'woo-product-slider' ),
				'subtitle'   => __( 'Set padding for the product content.', 'woo-product-slider' ),
				'style'      => false,
				'color'      => false,
				'all'        => false,
				'units'      => array( 'px' ),
				'default'    => array(
					'top'    => '18',
					'right'  => '20',
					'bottom' => '20',
					'left'   => '20',
				),
				'attributes' => array(
					'min' => 0,
				),
				'dependency' => array( 'template_style|layout_preset', '==|!=', 'custom|table', true ),
			),
			array(
				'id'          => 'product_border',
				'type'        => 'border',
				'title'       => __( 'Border', 'woo-product-slider' ),
				'subtitle'    => __( 'Set product border.', 'woo-product-slider' ),
				'all'         => true,
				'hover_color' => true,
				'default'     => array(
					'all'         => '1',
					'style'       => 'solid',
					'color'       => '#dddddd',
					'hover_color' => '#dddddd',
				),
				'dependency'  => array( 'template_style|layout_preset', '==|!=', 'custom|table', true ),
			),
			array(
				'id'         => 'carousel_same_height',
				'type'       => 'switcher',
				'class'      => 'pro_only_field ',
				'title'      => __( 'Equalize Products Height', 'woo-product-slider' ),
				'subtitle'   => __( 'Enable to equalize products same height.', 'woo-product-slider' ),
				'text_on'    => __( 'Enabled', 'woo-product-slider' ),
				'text_off'   => __( 'Disabled', 'woo-product-slider' ),
				'text_width' => 100,
				'default'    => false,
				'title_info' => '<div class="spwps-img-tag"><img src="' . SPF_WPSP::include_plugin_url( 'assets/images/visual-preview/wps_equalize_products_height.svg' ) . '" alt="Equalize Products Height"></div><div class="spwps-info-label img">' . __( 'Equalize Products Height', 'woo-product-slider' ) . '</div>',
				'dependency' => array( 'layout_preset', 'any', 'grid,slider', true ),
			),
			array(
				'type'       => 'subheading',
				'content'    => __( 'Pagination', 'woo-product-slider' ),
				'dependency' => array( 'layout_preset', '==', 'grid', true ),
			),
			array(
				'id'         => 'grid_pagination',
				'type'       => 'switcher',
				'title'      => __( 'Pagination', 'woo-product-slider' ),
				'subtitle'   => __( 'Enable/Disable pagination.', 'woo-product-slider' ),
				'text_on'    => __( 'Enabled', 'woo-product-slider' ),
				'text_off'   => __( 'Disabled', 'woo-product-slider' ),
				'text_width' => 100,
				'default'    => true,
				'dependency' => array( 'layout_preset', '==', 'grid', true ),
			),
			array(
				'id'         => 'grid_pagination_type',
				'class'      => 'pagination_pro_field ',
				'type'       => 'radio',
				'title'      => __( 'Pagination Type', 'woo-product-slider' ),
				'subtitle'   => __( 'Choose a pagination type.', 'woo-product-slider' ),
				'desc'       => __( 'To unlock Ajax Number, Load More & Load More on Scroll, <a href="https://wooproductslider.io/pricing/?ref=1" target="_blank"><b>Upgrade To Pro!</b></a>', 'woo-product-slider' ),
				'options'    => array(
					'normal'           => __( 'Normal', 'woo-product-slider' ),
					'ajax_number'      => __( 'Ajax Number (Pro)', 'woo-product-slider' ),
					'load_more_btn'    => __( 'Ajax Load More Button (Pro)', 'woo-product-slider' ),
					'load_more_scroll' => __( 'Ajax Load More on Scroll (Pro)', 'woo-product-slider' ),
				),
				'title_info' => '<div class="spwps-img-tag"><img src="' . SPF_WPSP::include_plugin_url( 'assets/images/visual-preview/wps_pagination_type.svg' ) . '" alt="Pagination Type"></div><div class="spwps-info-label img">' . __( 'Pagination Type', 'woo-product-slider' ) . '</div>',
				'default'    => 'normal',
				'dependency' => array( 'grid_pagination|layout_preset', '==|==', 'true|grid' ),
			),
			array(
				'id'         => 'grid_pagination_alignment',
				'type'       => 'button_set',
				'title'      => __( 'Alignment', 'woo-product-slider' ),
				'subtitle'   => __( 'Select pagination alignment.', 'woo-product-slider' ),
				'options'    => array(
					'wpspro-align-left'   => array(
						'name' => '<i title="Left" class="fa fa-align-left"></i>',
					),
					'wpspro-align-center' => array(
						'name' => '<i title="Left" class="fa fa-align-center"></i>',
					),
					'wpspro-align-right'  => array(
						'name' => '<i title="Left" class="fa fa-align-right"></i>',
					),
				),
				'default'    => 'wpspro-align-center',
				'dependency' => array( 'grid_pagination|layout_preset', '==|==', 'true|grid' ),
			),

			array(
				'id'         => 'products_per_page',
				'type'       => 'spinner',
				'title'      => __( 'Product(s) To Show Per Page', 'woo-product-slider' ),
				'subtitle'   => __( 'Set number of product(s) to show in per page.', 'woo-product-slider' ),
				'default'    => 8,
				'dependency' => array( 'grid_pagination|layout_preset', '==|==', 'true|grid' ),
			),
			array(
				'id'         => 'grid_pagination_colors',
				'type'       => 'color_group',
				'title'      => __( 'Pagination Color', 'woo-product-slider' ),
				'subtitle'   => __( 'Set color for the pagination.', 'woo-product-slider' ),
				'options'    => array(
					'color'            => __( 'Color', 'woo-product-slider' ),
					'hover_color'      => __( 'Hover Color', 'woo-product-slider' ),
					'background'       => __( 'Background', 'woo-product-slider' ),
					'hover_background' => __( 'Hover Background', 'woo-product-slider' ),
					'border'           => __( 'Border', 'woo-product-slider' ),
					'hover_border'     => __( 'Hover Border', 'woo-product-slider' ),
				),
				'default'    => array(
					'color'            => '#5e5e5e',
					'hover_color'      => '#ffffff',
					'background'       => 'transparent',
					'hover_background' => '#5e5e5e',
					'border'           => '#dddddd',
					'hover_border'     => '#5e5e5e',
				),
				'dependency' => array( 'grid_pagination|layout_preset', '==|==', 'true|grid' ),
			),
		),
	)
);

/**
 * Display Options section.
 */
SPF_WPSP::createSection(
	$prefix,
	array(
		'title'  => __( 'Display Settings', 'woo-product-slider' ),
		'icon'   => 'fa fa-th-large',
		'fields' => array(
			array(
				'id'         => 'slider_title',
				'type'       => 'switcher',
				'title'      => __( 'Product Showcase Section Title', 'woo-product-slider' ),
				'subtitle'   => __( 'Show/Hide product showcase section title.', 'woo-product-slider' ),
				'text_on'    => __( 'Show', 'woo-product-slider' ),
				'text_off'   => __( 'Hide', 'woo-product-slider' ),
				'text_width' => 80,
				'default'    => false,
			),
			array(
				'id'         => 'ajax_search',
				'type'       => 'switcher',
				'class'      => 'pro_only_field ',
				'title'      => __( 'Ajax Product Search', 'woo-product-slider' ),
				'subtitle'   => __( 'Enable/Disable ajax search for product.', 'woo-product-slider' ),
				'text_on'    => __( 'Enabled', 'woo-product-slider' ),
				'text_off'   => __( 'Disabled', 'woo-product-slider' ),
				'default'    => false,
				'text_width' => 100,
				'title_info' => '<div class="spwps-img-tag"><img src="' . SPF_WPSP::include_plugin_url( 'assets/images/visual-preview/wps_ajax_product_search.svg' ) . '" alt="Ajax Product Search"></div><div class="spwps-info-label img">' . __( 'Ajax Product Search', 'woo-product-slider' ) . '</div>',
			),
			array(
				'id'         => 'preloader',
				'type'       => 'switcher',
				'title'      => __( 'Preloader', 'woo-product-slider' ),
				'subtitle'   => __( 'Products showcase will be hidden until page load completed.', 'woo-product-slider' ),
				'text_on'    => __( 'Enabled', 'woo-product-slider' ),
				'text_off'   => __( 'Disabled', 'woo-product-slider' ),
				'text_width' => 100,
				'default'    => true,
			),
			array(
				'type'    => 'notice',
				'content' => __( '<a  href="https://wooproductslider.io/pricing/?ref=1" target="_blank"><b>Upgrade to Pro</b></a> to show and customize Ajax Product Search, Category, Description, Badge, Rating, and more.', 'woo-product-slider' ),
			),
			array(
				'type'    => 'subheading',
				'content' => __( 'Product Name', 'woo-product-slider' ),
			),
			array(
				'id'         => 'product_name',
				'type'       => 'switcher',
				'title'      => __( 'Name', 'woo-product-slider' ),
				'subtitle'   => __( 'Show/Hide product name.', 'woo-product-slider' ),
				'text_on'    => __( 'Show', 'woo-product-slider' ),
				'text_off'   => __( 'Hide', 'woo-product-slider' ),
				'text_width' => 80,
				'default'    => true,
			),
			array(
				'id'              => 'product_name_limit',
				'type'            => 'spacing',
				'class'           => 'pro_only_field',
				'title'           => __( 'Name Length', 'woo-product-slider' ),
				'subtitle'        => __( 'Leave it empty to show full product name.', 'woo-product-slider' ),
				'all'             => true,
				'all_placeholder' => '',
				'all_icon'        => '',
				'default'         => array(
					'all'  => '10',
					'unit' => 'words',
				),
				'units'           => array( 'words', 'characters', 'lines' ),
				'attributes'      => array(
					'min' => 1,
				),
				'dependency'      => array(
					'product_name',
					'==',
					'true',
					true,
				),
			),
			/**
			 * Product Description Settings
			 */
			array(
				'type'    => 'subheading',
				'content' => __( 'Product Description', 'woo-product-slider' ),
			),
			array(
				'id'       => 'product_content_type',
				'type'     => 'button_set',
				'class'    => 'pro_only_field pro_only_field_group',
				'title'    => __( 'Description Display Type', 'woo-product-slider' ),
				'subtitle' => __( 'Select a product description display type.', 'woo-product-slider' ),
				'options'  => array(
					'short_description' => array(
						'name'     => __( 'Short', 'woo-product-slider' ),
						'pro_only' => true,
					),
					'full_description'  => array(
						'name'     => __( 'Full', 'woo-product-slider' ),
						'pro_only' => true,
					),
					'hide'              => array(
						'name' => __( 'Hide', 'woo-product-slider' ),
					),
				),
				'default'  => 'hide',
			),
			array(
				'id'              => 'product_content_limit',
				'type'            => 'spacing',
				'class'           => 'pro_only_field pro_only_field_group',
				'title'           => __( 'Description Length', 'woo-product-slider' ),
				'subtitle'        => __( 'Set a length for product description. Leave it empty to show  the short/full description', 'woo-product-slider' ),
				'all'             => true,
				'all_placeholder' => '',
				'all_icon'        => '',
				'default'         => array(
					'all'  => 19,
					'unit' => 'words',
				),
				'units'           => array( 'words', 'characters' ),
				'attributes'      => array(
					'min' => 1,
					'max' => 1000,
				),
			),
			array(
				'id'         => 'product_content_more_button',
				'type'       => 'switcher',
				'class'      => 'pro_only_field pro_only_field_group',
				'title'      => __( 'Read More Button', 'woo-product-slider' ),
				'subtitle'   => __( 'Show/Hide product description read more button.', 'woo-product-slider' ),
				'text_on'    => __( 'Show', 'woo-product-slider' ),
				'text_off'   => __( 'Hide', 'woo-product-slider' ),
				'text_width' => 80,
				'default'    => false,
			),
			array(
				'type'    => 'subheading',
				'content' => __( 'Product Price', 'woo-product-slider' ),
			),
			array(
				'id'         => 'product_price',
				'type'       => 'switcher',
				'title'      => __( 'Price', 'woo-product-slider' ),
				'subtitle'   => __( 'Show/Hide product price.', 'woo-product-slider' ),
				'text_on'    => __( 'Show', 'woo-product-slider' ),
				'text_off'   => __( 'Hide', 'woo-product-slider' ),
				'text_width' => 80,
				'default'    => true,
			),
			array(
				'id'         => 'product_del_price_color',
				'type'       => 'color',
				'title'      => __( 'Discount Color', 'woo-product-slider' ),
				'subtitle'   => __( 'Set discount price color.', 'woo-product-slider' ),
				'default'    => '#888888',
				'dependency' => array( 'product_price', '==', 'true' ),
			),
			array(
				'type'    => 'subheading',
				'content' => __( 'Product Rating', 'woo-product-slider' ),
			),
			array(
				'id'         => 'product_rating',
				'type'       => 'switcher',
				'title'      => __( 'Rating', 'woo-product-slider' ),
				'subtitle'   => __( 'Show/Hide product rating.', 'woo-product-slider' ),
				'text_on'    => __( 'Show', 'woo-product-slider' ),
				'text_off'   => __( 'Hide', 'woo-product-slider' ),
				'text_width' => 80,
				'default'    => true,
			),
			array(
				'id'         => 'product_rating_colors',
				'type'       => 'color_group',
				'title'      => __( 'Color', 'woo-product-slider' ),
				'subtitle'   => __( 'Set rating star color.', 'woo-product-slider' ),
				'options'    => array(
					'color'       => __( 'Star Color', 'woo-product-slider' ),
					'empty_color' => __( 'Empty Star Color', 'woo-product-slider' ),
				),
				'default'    => array(
					'color'       => '#F4C100',
					'empty_color' => '#C8C8C8',
				),
				'dependency' => array( 'product_rating', '==', 'true' ),
			),
			array(
				'type'    => 'subheading',
				'content' => __( 'Add to Cart Button', 'woo-product-slider' ),
			),
			array(
				'id'         => 'add_to_cart_button',
				'type'       => 'switcher',
				'title'      => __( 'Add to Cart Button', 'woo-product-slider' ),
				'subtitle'   => __( 'Show/Hide product add to cart button.', 'woo-product-slider' ),
				'text_on'    => __( 'Show', 'woo-product-slider' ),
				'text_off'   => __( 'Hide', 'woo-product-slider' ),
				'text_width' => 80,
				'default'    => true,
			),
			array(
				'id'         => 'add_to_cart_button_colors',
				'type'       => 'color_group',
				'title'      => __( 'Color', 'woo-product-slider' ),
				'subtitle'   => __( 'Set product add to cart button color.', 'woo-product-slider' ),
				'options'    => array(
					'color'            => __( 'Text Color', 'woo-product-slider' ),
					'hover_color'      => __( 'Text Hover', 'woo-product-slider' ),
					'background'       => __( 'Background', 'woo-product-slider' ),
					'hover_background' => __( 'Hover BG', 'woo-product-slider' ),
				),
				'default'    => array(
					'color'            => '#444444',
					'hover_color'      => '#ffffff',
					'background'       => 'transparent',
					'hover_background' => '#222222',
				),
				'dependency' => array( 'add_to_cart_button', '==', 'true' ),
			),
			array(
				'id'          => 'add_to_cart_border',
				'type'        => 'border',
				'title'       => __( 'Border', 'woo-product-slider' ),
				'subtitle'    => __( 'Set add to cart button border.', 'woo-product-slider' ),
				'all'         => true,
				'hover_color' => true,
				'default'     => array(
					'all'         => '1',
					'style'       => 'solid',
					'color'       => '#222222',
					'hover_color' => '#222222',
				),
				'dependency'  => array( 'add_to_cart_button', '==', 'true' ),
			),
			array(
				'id'         => 'quantity_button',
				'type'       => 'switcher',
				'class'      => 'pro_only_field ',
				'title'      => __( 'Quantities', 'woo-product-slider' ),
				'subtitle'   => __( 'Show/hide quantities selector before the add to cart.', 'woo-product-slider' ),
				'text_on'    => __( 'Show', 'woo-product-slider' ),
				'text_off'   => __( 'Hide', 'woo-product-slider' ),
				'text_width' => 80,
				'default'    => false,
				'dependency' => array(
					'add_to_cart_button',
					'==',
					'true',
					true,
				),
			),

			array(
				'type'    => 'subheading',
				'content' => __( 'Product Badge', 'woo-product-slider' ),
			),
			array(
				'id'         => 'sale_ribbon',
				'type'       => 'switcher',
				'class'      => 'pro_only_field ',
				'title'      => __( 'Sale Ribbon', 'woo-product-slider' ),
				'subtitle'   => __( 'Show/Hide product sale ribbon.', 'woo-product-slider' ),
				'text_on'    => __( 'Show', 'woo-product-slider' ),
				'text_off'   => __( 'Hide', 'woo-product-slider' ),
				'text_width' => 80,
				'default'    => true,
				'title_info' => '<div class="spwps-img-tag"><img src="' . SPF_WPSP::include_plugin_url( 'assets/images/visual-preview/wps_sale_ribbon.svg' ) . '" alt="Sale Ribbon"></div><div class="spwps-info-label img">' . __( 'Sale Ribbon', 'woo-product-slider' ) . '</div>',
			),
			array(
				'id'       => 'show_on_sale_product_discount',
				'type'     => 'checkbox',
				'class'    => 'pro_only_field ',
				'title'    => __( 'Show On Sale Product Discount', 'woo-product-slider' ),
				'subtitle' => __( 'Check to show on sale products discount percentage(%).', 'woo-product-slider' ),
				'default'  => false,
			),
			array(
				'id'       => 'sale_ribbon_text',
				'type'     => 'text',
				'class'    => 'pro_only_field ',
				'title'    => __( 'Sale Label', 'woo-product-slider' ),
				'subtitle' => __( 'Set product sale ribbon label.', 'woo-product-slider' ),
				'default'  => 'On Sale!',
			),
			array(
				'id'         => 'out_of_stock_ribbon',
				'type'       => 'switcher',
				'class'      => 'pro_only_field ',
				'title'      => __( 'Out of Stock Ribbon', 'woo-product-slider' ),
				'subtitle'   => __( 'Show/Hide product out of stock ribbon.', 'woo-product-slider' ),
				'text_on'    => __( 'Show', 'woo-product-slider' ),
				'text_off'   => __( 'Hide', 'woo-product-slider' ),
				'text_width' => 80,
				'default'    => true,
			),
			array(
				'id'       => 'out_of_stock_ribbon_text',
				'type'     => 'text',
				'class'    => 'pro_only_field ',
				'title'    => __( 'Out of Stock Label', 'woo-product-slider' ),
				'subtitle' => __( 'Set product out of stock ribbon label.', 'woo-product-slider' ),
				'default'  => 'Out of Stock',
			),
			array(
				'type'    => 'subheading',
				'content' => __( 'Product Brands', 'woo-product-slider' ),
			),
			array(
				'id'         => 'show_product_brands',
				'type'       => 'switcher',
				'title'      => __( 'Show Brands', 'woo-product-slider' ),
				'subtitle'   => __( 'Show/Hide product brands.', 'woo-product-slider' ),
				'text_on'    => __( 'Show', 'woo-product-slider' ),
				'text_off'   => __( 'Hide', 'woo-product-slider' ),
				'text_width' => 80,
				'default'    => false,
			),
			array(
				'type'       => 'submessage',
				'style'      => 'info',
				'content'    => __( 'To Enable Product Brands feature, you must Install and Activate the <a class="thickbox open-plugin-details-modal" href="' . esc_url( $smart_brand_plugin_data['plugin_link'] ) . '">Smart Brands for WooCommerce</a> plugin. <a href="#" class="brand-plugin-install' . $smart_brand_plugin_data['has_plugin'] . '" data-url="' . $smart_brand_plugin_data['activate_plugin_url'] . '" data-nonce="' . wp_create_nonce( 'updates' ) . '" > ' . $smart_brand_plugin_data['button_text'] . ' <i class="fa fa-angle-double-right"></i></a>', 'woo-product-slider' ),
				'dependency' => array( 'show_product_brands', '==', 'true', true ),
			),
			array(
				'type'    => 'subheading',
				'content' => __( 'Quick View Button', 'woo-product-slider' ),
			),
			array(
				'id'         => 'quick_view',
				'type'       => 'switcher',
				'title'      => __( 'Show Quick View Button', 'woo-product-slider' ),
				'subtitle'   => __( 'Show/Hide quick view button.', 'woo-product-slider' ),
				'text_on'    => __( 'Show', 'woo-product-slider' ),
				'text_off'   => __( 'Hide', 'woo-product-slider' ),
				'text_width' => 80,
				'default'    => false,
			),
			array(
				'type'       => 'submessage',
				'style'      => 'info',
				'content'    => __( 'To Enable Quick view feature, you must Install and Activate the <a class="thickbox open-plugin-details-modal" href="' . esc_url( $quick_view_plugin_data['plugin_link'] ) . '">Quick View for WooCommerce</a> plugin. <a href="#" class="quick-view-install' . $quick_view_plugin_data['has_plugin'] . '" data-url="' . $quick_view_plugin_data['activate_plugin_url'] . '" data-nonce="' . wp_create_nonce( 'updates' ) . '" > ' . $quick_view_plugin_data['button_text'] . ' <i class="fa fa-angle-double-right"></i></a> ', 'woo-product-slider' ),
				'dependency' => array( 'quick_view', '==', 'true', true ),
			),

		),
	)
);

	/**
	 * Image Settings section.
	 */
	SPF_WPSP::createSection(
		$prefix,
		array(
			'title'  => __( 'Image Settings', 'woo-product-slider' ),
			'icon'   => 'fa fa-image',
			'fields' => array(
				array(
					'id'         => 'product_image',
					'type'       => 'switcher',
					'title'      => __( 'Product Image', 'woo-product-slider' ),
					'subtitle'   => __( 'Show/Hide product image.', 'woo-product-slider' ),
					'text_on'    => __( 'Show', 'woo-product-slider' ),
					'text_off'   => __( 'Hide', 'woo-product-slider' ),
					'text_width' => 80,
					'default'    => true,
				),
				array(
					'id'          => 'product_image_border',
					'type'        => 'border',
					'title'       => __( 'Border', 'woo-product-slider' ),
					'subtitle'    => __( 'Set product image border.', 'woo-product-slider' ),
					'all'         => true,
					'hover_color' => true,
					'default'     => array(
						'all'         => '1',
						'style'       => 'solid',
						'color'       => '#dddddd',
						'hover_color' => '#dddddd',
					),
					'dependency'  => array( 'product_image|theme_style|template_style', '==|==|!=', 'true|theme_one|custom', true ),
				),
				array(
					'id'         => 'product_image_flip',
					'type'       => 'switcher',
					'class'      => 'pro_only_field',
					'title'      => __( 'Image Flip', 'woo-product-slider' ),
					'subtitle'   => __( 'Enable/Disable product image flipping. Flipping image will be the first image of product gallery.', 'woo-product-slider' ),
					'text_on'    => __( 'Enabled', 'woo-product-slider' ),
					'text_off'   => __( 'Disabled', 'woo-product-slider' ),
					'text_width' => 100,
					'default'    => false,
					'dependency' => array(
						'product_image',
						'==',
						'true',
						true,
					),
				),
				array(
					'id'         => 'image_sizes',
					'type'       => 'image_sizes',
					'title'      => __( 'Dimensions', 'woo-product-slider' ),
					'subtitle'   => __( 'Select a size for product image.', 'woo-product-slider' ),
					'default'    => 'medium',
					'dependency' => array(
						'product_image',
						'==',
						'true',
					),
				),
				array(
					'id'         => 'custom_image_size',
					'class'      => 'spwps_custom_image_option',
					'type'       => 'fieldset',
					'title'      => __( 'Custom Dimensions', 'woo-product-slider' ),
					'subtitle'   => __( 'Set a custom width and height of the product image.', 'woo-product-slider' ),
					'dependency' => array(
						'product_image|image_sizes',
						'==|==',
						'true|custom',
						true,
					),
					'fields'     => array(
						array(
							'id'       => 'image_custom_width',
							'type'     => 'spinner',
							'title'    => __( 'Width*', 'woo-product-slider' ),
							'default'  => 250,
							'unit'     => __( 'px', 'woo-product-slider' ),
							'max'      => 10000,
							'min'      => 1,
							'sanitize' => 'spwps_sanitize_number_field',

						),
						array(
							'id'       => 'image_custom_height',
							'type'     => 'spinner',
							'title'    => __( 'Height*', 'woo-product-slider' ),
							'default'  => 300,
							'unit'     => __( 'px', 'woo-product-slider' ),
							'max'      => 10000,
							'min'      => 1,
							'sanitize' => 'spwps_sanitize_number_field',

						),
						array(
							'id'       => 'image_custom_crop',
							'type'     => 'switcher',
							'class'    => 'pro_only_field',
							'title'    => __( 'Hard Crop', 'woo-product-slider' ),
							'text_on'  => __( 'Yes', 'woo-product-slider' ),
							'text_off' => __( 'No', 'woo-product-slider' ),
							'default'  => false,
						),
					),
				),
				array(
					'id'         => 'load_2x_image',
					'type'       => 'switcher',
					'class'      => 'pro_only_field',
					'title'      => __( 'Load 2x Resolution Image in Retina Display', 'woo-product-slider' ),
					'subtitle'   => __( 'You should upload 2x sized images to show in retina display.', 'woo-product-slider' ),
					'text_on'    => __( 'Enabled', 'woo-product-slider' ),
					'text_off'   => __( 'Disabled', 'woo-product-slider' ),
					'text_width' => 100,
					'default'    => false,
					'dependency' => array( 'product_image|image_sizes', '==|==', 'true|custom', true ),
				),
				array(
					'id'         => 'image_lightbox',
					'type'       => 'switcher',
					'class'      => 'pro_only_field',
					'title'      => __( 'Lightbox', 'woo-product-slider' ),
					'subtitle'   => __( 'Enable/Disable lightbox gallery for product image.', 'woo-product-slider' ),
					'text_on'    => __( 'Enabled', 'woo-product-slider' ),
					'text_off'   => __( 'Disabled', 'woo-product-slider' ),
					'text_width' => 100,
					'default'    => false,
					'dependency' => array(
						'product_image',
						'==',
						'true',
						true,
					),
				),
				array(
					'id'         => 'zoom_effect_types',
					'type'       => 'select',
					'title'      => __( 'Zoom', 'woo-product-slider' ),
					'subtitle'   => __( 'Select a zoom effect for the product image.', 'woo-product-slider' ),
					'options'    => array(
						'off'      => __( 'None', 'woo-product-slider' ),
						'zoom_in'  => __( 'Zoom In', 'woo-product-slider' ),
						'zoom_out' => __( 'Zoom Out', 'woo-product-slider' ),
					),
					'default'    => 'off',
					'dependency' => array(
						'product_image|template_style',
						'==|==',
						'true|custom',
						true,
					),
				),
				// array(
				// 'id'         => 'image_gray_scale',
				// 'type'       => 'select',
				// 'title'      => __( 'Image mode', 'woo-product-slider' ),
				// 'subtitle'   => __( 'Set a mode for image.', 'woo-product-slider' ),
				// 'options'    => array(
				// ''                      => array(
				// 'name' => __( 'Normal', 'woo-product-slider' ),
				// ),
				// 'sp-wpsp-gray-with-normal-on-hover' => array(
				// 'name'     => __( 'Grayscale with normal on hover(Pro)', 'woo-product-slider' ),
				// 'pro_only' => true,
				// ),
				// 'sp-wpsp-gray-on-hover' => array(
				// 'name'     => __( 'Grayscale on hover(Pro)', 'woo-product-slider' ),
				// 'pro_only' => true,
				// ),
				// 'sp-wpsp-always-gray'   => array(
				// 'name'     => __( 'Always grayscale(Pro)', 'woo-product-slider' ),
				// 'pro_only' => true,
				// ),
				// ),
				// 'default'    => '',
				// 'dependency' => array(
				// 'product_image',
				// '==',
				// 'true',
				// ),
				// ),
				array(
					'id'         => 'image_gray_scale',
					'class'      => 'pro_only_field_group',
					'type'       => 'button_set',
					'title'      => __( 'Image Mode', 'woo-product-slider' ),
					'subtitle'   => __( 'Set a mode for image.', 'woo-product-slider' ),
					'options'    => array(
						''                     => array(
							'name' => __( 'Original', 'woo-product-slider' ),
						),
						'sp-wpsp-always-gray'  => array(
							'name'     => __( 'Grayscale', 'woo-product-slider' ),
							'pro_only' => true,
						),
						'sp-wpsp-custom-color' => array(
							'name'     => __( 'Custom Color', 'woo-product-slider' ),
							'pro_only' => true,
						),
					),
					'default'    => '',
					'dependency' => array(
						'product_image',
						'==',
						'true',
					),
				),
				array(
					'id'         => 'image_grayscale_on_hover',
					'type'       => 'checkbox',
					'class'      => 'pro_only_field',
					'title'      => __( 'Grayscale on Hover', 'woo-product-slider' ),
					'subtitle'   => __( 'Check to grayscale product image on hover.', 'woo-product-slider' ),
					'default'    => false,
					'dependency' => array(
						'product_image',
						'==',
						'true',
					),
				),
				array(
					'type'    => 'notice',
					'content' => __( 'Want to fine-tune control over product image dimensions, retina, flipping, lightbox, grayscale, and more?  <a  href="https://wooproductslider.io/pricing/?ref=1" target="_blank"><b>Upgrade To Pro!</b></a>', 'woo-product-slider' ),
				),

			),
		)
	);

	/**
	 * Slider Controls section.
	 */
	SPF_WPSP::createSection(
		$prefix,
		array(
			'title'  => __( 'Slider Settings', 'woo-product-slider' ),
			'icon'   => 'fa fa-sliders',
			'fields' => array(
				array(
					'type'  => 'tabbed',
					'class' => 'wps-carousel-tabs',
					'tabs'  => array(
						array(
							'title'  => __( 'General', 'woo-product-slider' ),
							'icon'   => '<span><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none"><g clip-path="url(#A)"><path fill-rule="evenodd" d="M1.224 1.224c-.009.009-.024.03-.024.076v13.4c0 .046.015.067.024.076s.03.024.076.024h13.4c.02-.017.019-.043.012-.082l-.012-.058V14.6 1.3c0-.046-.015-.067-.024-.076s-.03-.024-.076-.024H1.3c-.046 0-.067.015-.076.024zM0 1.3A1.28 1.28 0 0 1 1.3 0h13.3a1.28 1.28 0 0 1 1.3 1.3v13.247c.058.368-.014.734-.248 1.02-.244.299-.602.433-.952.433H1.3A1.28 1.28 0 0 1 0 14.7V1.3zm12.4 3h-.9c-.3-.7-1.1-1.2-1.9-1.2-.9 0-1.6.5-1.9 1.2H3.6c-.5 0-.9.4-.9.9s.4.9.9.9h4.1c.3.8 1 1.3 1.9 1.3s1.6-.5 1.9-1.2h.9c.5 0 .9-.4.9-.9s-.4-1-.9-1zm-7.9 7.4h-.9c-.5 0-.9-.4-.9-.9s.4-.9.9-.9h.9c.3-.8 1-1.3 1.9-1.3s1.6.5 1.9 1.3h4.1c.5 0 .9.4.9.9s-.4.9-.9.9H8.3c-.3.7-1 1.2-1.9 1.2-.8 0-1.6-.5-1.9-1.2z" fill="#000"/></g><defs><clipPath id="A"><path fill="#fff" d="M0 0h16v16H0z"/></clipPath></defs></svg></span>',
							'fields' => array(
								array(
									'id'       => 'carousel_orientation',
									'type'     => 'button_set',
									'title'    => __( 'Carousel Orientation', 'woo-product-slider' ),
									'subtitle' => __( 'Choose a carousel orientation.', 'woo-product-slider' ),
									'options'  => array(
										'horizontal' => array(
											'name' => __( 'Horizontal', 'woo-product-slider' ),
										),
										'vertical'   => array(
											'name'     => __( 'Vertical', 'woo-product-slider' ),
											'pro_only' => true,
										),
									),
									'only_pro' => true,
									'default'  => 'horizontal',
								),
								array(
									'id'         => 'carousel_auto_play',
									'type'       => 'switcher',
									'title'      => __( 'AutoPlay', 'woo-product-slider' ),
									'subtitle'   => __( 'Enable/Disable auto play.', 'woo-product-slider' ),
									'text_on'    => __( 'Enabled', 'woo-product-slider' ),
									'text_off'   => __( 'Disabled', 'woo-product-slider' ),
									'text_width' => 100,
									'default'    => true,
									'dependency' => array( 'carousel_ticker_mode', '==', 'standard', true ),
								),
								array(
									'id'         => 'carousel_auto_play_speed',
									'type'       => 'slider',
									'class'      => 'carousel_auto_play_ranger',
									'title'      => __( 'AutoPlay Delay Time', 'woo-product-slider' ),
									'subtitle'   => __( 'Set autoplay delay time in millisecond.', 'woo-product-slider' ),
									'unit'       => __( 'ms', 'woo-product-slider' ),
									'step'       => 100,
									'min'        => 100,
									'max'        => 30000,
									'default'    => 3000,
									'title_info' => __( '<div class="spwps-info-label">AutoPlay Delay Time</div> <div class="spwps-short-content">Set autoplay delay or interval time. The amount of time to delay between automatically cycling a product item. e.g. 1000 milliseconds(ms) = 1 second.</div>', 'woo-product-slider' ),
									'dependency' => array(
										'carousel_auto_play|carousel_ticker_mode',
										'==|==',
										'true|standard',
										true,
									),
								),
								array(
									'id'         => 'carousel_scroll_speed',
									'type'       => 'slider',
									'class'      => 'carousel_auto_play_ranger',
									'title'      => __( 'Slider Speed', 'woo-product-slider' ),
									'subtitle'   => __( 'Set slider scroll speed. Default value is 600 milliseconds.', 'woo-product-slider' ),
									'unit'       => __( 'ms', 'woo-product-slider' ),
									'step'       => 100,
									'min'        => 1,
									'max'        => 20000,
									'default'    => 600,
									'title_info' => __( '<div class="spwps-info-label">Carousel Speed</div> <div class="spwps-short-content">Set carousel scrolling speed. e.g. 1000 milliseconds(ms) = 1 second.</div>', 'woo-product-slider' ),
									'dependency' => array( 'carousel_ticker_mode', '==', 'standard', true ),
								),
								array(
									'id'         => 'slides_to_scroll',
									'type'       => 'column',
									'title'      => __( 'Slide To Scroll', 'woo-product-slider' ),
									'class'      => 'ps_pro_only_field',
									'subtitle'   => __( 'Number of product(s) to scroll at a time.', 'woo-product-slider' ),
									'default'    => array(
										'number1' => '1',
										'number2' => '1',
										'number3' => '1',
										'number4' => '1',
									),
									'dependency' => array( 'carousel_ticker_mode', '==', 'standard', true ),
								),
								array(
									'id'         => 'carousel_pause_on_hover',
									'type'       => 'switcher',
									'title'      => __( 'Pause on Hover', 'woo-product-slider' ),
									'subtitle'   => __( 'Enable/Disable pause on hover.', 'woo-product-slider' ),
									'text_on'    => __( 'Enabled', 'woo-product-slider' ),
									'text_off'   => __( 'Disabled', 'woo-product-slider' ),
									'text_width' => 100,
									'default'    => true,
								),
								array(
									'id'         => 'carousel_infinite',
									'type'       => 'switcher',
									'title'      => __( 'Infinite Loop', 'woo-product-slider' ),
									'subtitle'   => __( 'Enable/Disable infinite loop mode.', 'woo-product-slider' ),
									'text_on'    => __( 'Enabled', 'woo-product-slider' ),
									'text_off'   => __( 'Disabled', 'woo-product-slider' ),
									'text_width' => 100,
									'default'    => true,
									'dependency' => array( 'carousel_ticker_mode', '==', 'standard', true ),
								),
								array(
									'id'         => 'fade_slider_effect',
									'type'       => 'switcher',
									'title'      => __( 'Fade Effect', 'woo-product-slider' ),
									'class'      => 'pro_only_field',
									'subtitle'   => __( 'Enable/Disable fade effect for the carousel.', 'woo-product-slider' ),
									'text_on'    => __( 'Enabled', 'woo-product-slider' ),
									'text_off'   => __( 'Disabled', 'woo-product-slider' ),
									'text_width' => 95,
									'default'    => false,
									'dependency' => array( 'carousel_ticker_mode|carousel_orientation', '==|==', 'standard|horizontal', true ),
								),
								array(
									'id'       => 'rtl_mode',
									'type'     => 'button_set',
									'title'    => __( 'Slider Direction', 'woo-product-slider' ),
									'subtitle' => __( 'Set slider direction as you need.', 'woo-product-slider' ),
									'options'  => array(
										false => array(
											'name' => __( 'Right to Left', 'woo-product-slider' ),
										),
										true  => array(
											'name' => __( 'Left to Right', 'woo-product-slider' ),
										),
									),
									'default'  => false,
								),
								array(
									'id'         => 'slider_row',
									'type'       => 'column',
									'title'      => __( 'Row', 'woo-product-slider' ),
									'class'      => 'ps_pro_only_field',
									'subtitle'   => __( 'Number of row(s) to scroll at a time.', 'woo-product-slider' ),
									'default'    => array(
										'number1' => '1',
										'number2' => '1',
										'number3' => '1',
										'number4' => '1',
									),
									'title_info' => '<div class="spwps-img-tag"><img src="' . SPF_WPSP::include_plugin_url( 'assets/images/row.svg' ) . '" alt="Multi-row"></div><div class="spwps-info-label img">' . __( 'Multi Row', 'woo-product-slider' ) . '</div>',
									'dependency' => array( 'carousel_ticker_mode|carousel_orientation', '==|==', 'standard|horizontal', true ),
								),
								array(
									'type'    => 'notice',
									'content' => __( 'To unlock product Vertical Slider, Slide to Scroll, Fade Slide, and Multi-row Slider, <a  href="https://wooproductslider.io/pricing/?ref=1" target="_blank"><b>Upgrade To Pro!</b></a>', 'woo-product-slider' ),
								),
							),
						),
						array(
							'title'  => __( 'Navigation', 'woo-product-slider' ),
							'icon'   => '<span><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="#343434" ><path d="M2.2 8l4.1-4.1a.85.85 0 0 0 0-1.3c-.4-.3-1-.3-1.3.1L.3 7.4a.85.85 0 0 0 0 1.3L5 13.3c.3.3.9.3 1.2 0a.85.85 0 0 0 0-1.3l-4-4zM11 2.7l4.7 4.7c.4.3.4.9-.1 1.3l-4.7 4.7c-.4.4-1 .2-1.2 0a.85.85 0 0 1 0-1.3L13.8 8l-4-4.1c-.4-.3-.4-.9-.1-1.2a.85.85 0 0 1 1.3 0zM6.5 6a1 1 0 0 0-1 1v2a1 1 0 0 0 1 1h3a1 1 0 0 0 1-1V7a1 1 0 0 0-1-1h-3z"/></svg></span>',
							'fields' => array(
								array(
									'id'     => 'wps_carousel_navigation',
									'class'  => 'wps-navigation-and-pagination-style',
									'type'   => 'fieldset',
									'fields' => array(
										array(
											'id'         => 'navigation_arrow',
											'type'       => 'switcher',
											'title'      => __( 'Navigation', 'woo-product-slider' ),
											'class'      => 'wps_navigation',
											'subtitle'   => __( 'Show/hide navigation.', 'woo-product-slider' ),
											'text_on'    => __( 'Show', 'woo-product-slider' ),
											'text_off'   => __( 'Hide', 'woo-product-slider' ),
											'text_width' => 77,
											'default'    => true,
											'dependency' => array( 'carousel_ticker_mode', '==', 'standard', true ),
										),
										array(
											'id'         => 'nav_hide_on_mobile',
											'type'       => 'checkbox',
											'class'      => 'wps_hide_on_mobile',
											'title'      => __( 'Hide on Mobile', 'woo-product-slider' ),
											'default'    => false,
											'dependency' => array( 'carousel_ticker_mode|navigation_arrow', '==|==', 'standard|true', true ),
										),
									),
								),
								array(
									'id'          => 'navigation_position',
									'type'        => 'select',
									'class'       => 'wps-navigation-position',
									'title'       => __( 'Position', 'woo-product-slider' ),
									'subtitle'    => __( 'Position of the navigation arrows.', 'woo-product-slider' ),
									'options'     => array(
										'top_right'       => array(
											'name' => __( 'Top Right', 'woo-product-slider' ),
										),
										'top_center'      => array(
											'name' => __( 'Top Center', 'woo-product-slider' ),
										),
										'top_left'        => array(
											'name' => __( 'Top Left', 'woo-product-slider' ),
										),
										'bottom_left'     => array(
											'name' => __( 'Bottom Left', 'woo-product-slider' ),
										),
										'bottom_center'   => array(
											'name' => __( 'Bottom Center', 'woo-product-slider' ),
										),
										'bottom_right'    => array(
											'name' => __( 'Bottom Right', 'woo-product-slider' ),
										),
										'vertical_center' => array(
											'name' => __( 'Vertical Center', 'woo-product-slider' ),
										),
										'vertical_outer'  => array(
											'name' => __( 'Vertical Outer', 'woo-product-slider' ),
										),
										'vertical_center_inner' => array(
											'name' => __( 'Vertical Inner', 'woo-product-slider' ),
										),
									),
									'default'     => 'top_right',
									'nav-preview' => true,
									'only_pro'    => true,
									'dependency'  => array( 'navigation_arrow|carousel_ticker_mode', '==|==', 'true|standard', true ),
								),
								array(
									'id'         => 'nav_visible_on_hover',
									'type'       => 'checkbox',
									'title'      => __( 'Visible On Hover', 'woo-product-slider' ),
									'class'      => 'pro_only_field',
									'subtitle'   => __( 'Check to show navigation on hover in the carousel or slider area.', 'woo-product-slider' ),
									'default'    => false,
									'dependency' => array( 'navigation_arrow|carousel_ticker_mode|navigation_position', '==|==|any', 'true|standard|vertical_center,vertical_center_inner,vertical_outer', true ),
								),
								array(
									'id'            => 'navigation_border',
									'type'          => 'border',
									'title'         => __( 'Border', 'woo-product-slider' ),
									'subtitle'      => __( 'Set border for the navigation.', 'woo-product-slider' ),
									'all'           => true,
									'hover_color'   => true,
									'border_radius' => false,
									'show_units'    => true,
									'units'         => array( 'px', '%', 'em' ),
									'default'       => array(
										'all'         => '1',
										'style'       => 'solid',
										'color'       => '#aaaaaa',
										'hover_color' => '#444444',
									),
									'dependency'    => array( 'navigation_arrow|carousel_ticker_mode', '==|==', 'true|standard', true ),
								),
								array(
									'id'         => 'navigation_arrow_colors',
									'type'       => 'color_group',
									'title'      => __( 'Color', 'woo-product-slider' ),
									'subtitle'   => __( 'Set color for the slider navigation.', 'woo-product-slider' ),
									'options'    => array(
										'color'            => __( 'Color', 'woo-product-slider' ),
										'hover_color'      => __( 'Hover Color', 'woo-product-slider' ),
										'background'       => __( 'Background', 'woo-product-slider' ),
										'hover_background' => __( 'Hover Background', 'woo-product-slider' ),
									),
									'default'    => array(
										'color'            => '#444444',
										'hover_color'      => '#ffffff',
										'background'       => 'transparent',
										'hover_background' => '#444444',
									),
									'dependency' => array( 'navigation_arrow|carousel_ticker_mode', '==|==', 'true|standard', true ),
								),
							),
						),
						array(
							'title'  => __( 'Pagination', 'woo-product-slider' ),
							'icon'   => '<span><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" ><g clip-path="url(#A)" fill="#343434"><path d="M5.2 10.2a2.2 2.2 0 1 0 0-4.4 2.2 2.2 0 1 0 0 4.4zm6.2-.5a1.7 1.7 0 0 0 0-3.4 1.7 1.7 0 0 0 0 3.4z"/><path fill-rule="evenodd" d="M0 4a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V4zm2-.5h12a.5.5 0 0 1 .5.5v8a.5.5 0 0 1-.5.5H2a.5.5 0 0 1-.5-.5V4a.5.5 0 0 1 .5-.5z"/></g><defs><clipPath id="A"><path fill="#fff" d="M0 0h16v16H0z"/></clipPath></defs></svg></span>',
							'fields' => array(
								array(
									'id'     => 'wps_carousel_pagination',
									'class'  => 'wps-navigation-and-pagination-style',
									'type'   => 'fieldset',
									'fields' => array(
										array(
											'id'         => 'pagination',
											'type'       => 'switcher',
											'title'      => __( 'Pagination', 'woo-product-slider' ),
											'class'      => 'wps_pagination',
											'subtitle'   => __( 'Show/hide navigation.', 'woo-product-slider' ),
											'text_on'    => __( 'Show', 'woo-product-slider' ),
											'text_off'   => __( 'Hide', 'woo-product-slider' ),
											'text_width' => 77,
											'default'    => true,
											'dependency' => array( 'carousel_ticker_mode', '==', 'standard', true ),
										),
										array(
											'id'         => 'wps_pagination_hide_on_mobile',
											'type'       => 'checkbox',
											'class'      => 'wps_hide_on_mobile',
											'title'      => __( 'Hide on Mobile', 'woo-product-slider' ),
											'default'    => false,
											'dependency' => array( 'carousel_ticker_mode|pagination', '==|==', 'standard|true', true ),
										),
									),
								),
								array(
									'id'         => 'pagination_type',
									'type'       => 'image_select',
									'class'      => 'hide-active-sign',
									'title'      => __( 'Pagination Type', 'woo-product-slider' ),
									'subtitle'   => __( 'Select pagination type.', 'woo-product-slider' ),
									'image_name' => true,
									'options'    => array(
										'dots'      => array(
											'img'  => SP_WPS_URL . 'Admin/assets/images/pagination-type/bullets.svg',
											'name' => __( 'Bullets', 'woo-product-slider' ),
										),
										'dynamic'   => array(
											'img'      => SP_WPS_URL . 'Admin/assets/images/pagination-type/dynamic.svg',
											'name'     => __( 'Dynamic', 'woo-product-slider' ),
											'pro_only' => true,
										),
										'strokes'   => array(
											'img'      => SP_WPS_URL . 'Admin/assets/images/pagination-type/strokes.svg',
											'name'     => __( 'Strokes', 'woo-product-slider' ),
											'pro_only' => true,
										),
										'scrollbar' => array(
											'img'      => SP_WPS_URL . 'Admin/assets/images/pagination-type/scrollbar.svg',
											'name'     => __( 'Scrollbar', 'woo-product-slider' ),
											'pro_only' => true,
										),
										'number'    => array(
											'img'      => SP_WPS_URL . 'Admin/assets/images/pagination-type/numbers.svg',
											'name'     => __( 'Numbers', 'woo-product-slider' ),
											'pro_only' => true,
										),
									),
									'default'    => 'dots',
									'dependency' => array( 'pagination|carousel_ticker_mode', '==|==', 'true|standard', true ),
								),
								array(
									'id'         => 'pagination_dots_color',
									'type'       => 'color_group',
									'title'      => __( 'Color', 'woo-product-slider' ),
									'subtitle'   => __( 'Set color for the slider pagination dots and scrollbar.', 'woo-product-slider' ),
									'options'    => array(
										'color'        => __( 'Color', 'woo-product-slider' ),
										'active_color' => __( 'Active Color', 'woo-product-slider' ),
									),
									'default'    => array(
										'color'        => '#cccccc',
										'active_color' => '#333333',
									),
									'dependency' => array(
										'pagination|pagination_type|carousel_ticker_mode',
										'==|!=|==',
										'true|number|standard',
										true,
									),
								),
							),
						),
						array(
							'title'  => __( 'Miscellaneous', 'woo-product-slider' ),
							'icon'   => '<span><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none"><g clip-path="url(#A)" fill="#343434"><path d="M12.4 3.9h-6c-.4 0-.8.4-.8.8s.4.8.8.8h6c.4 0 .8-.3.8-.8 0-.4-.3-.8-.8-.8zm0 3.3h-6c-.4 0-.8.4-.8.8s.4.8.8.8h6c.4 0 .8-.3.8-.8 0-.4-.3-.8-.8-.8zm-6 3.2h6c.5 0 .8.4.8.8 0 .5-.4.8-.8.8h-6c-.4 0-.8-.4-.8-.8s.4-.8.8-.8zM4.9 4.8a.94.94 0 0 1-1 1c-.5 0-1-.4-1-1a.94.94 0 0 1 1-1 .94.94 0 0 1 1 1zM3.9 9a.94.94 0 0 0 1-1 .94.94 0 0 0-1-1 .94.94 0 0 0-1 1c0 .6.5 1 1 1zm1 2.2a.94.94 0 0 1-1 1c-.5 0-1-.4-1-1a.94.94 0 0 1 1-1 .94.94 0 0 1 1 1z"/><path fill-rule="evenodd" d="M13.2 0H2.9C1.3 0 0 1.3 0 2.9v10.2C0 14.7 1.3 16 2.9 16h10.2c1.6 0 2.9-1.3 2.9-2.8V2.9C16 1.3 14.7 0 13.2 0zm1.4 13.2c0 .8-.6 1.4-1.4 1.4H2.9c-.8 0-1.4-.6-1.4-1.4V2.9c0-.8.6-1.4 1.4-1.4h10.3c.8 0 1.4.6 1.4 1.4v10.3z"/></g><defs><clipPath id="A"><path fill="#fff" d="M0 0h16v16H0z"/></clipPath></defs></svg></span>',
							'fields' => array(
								array(
									'id'         => 'carousel_adaptive_height',
									'type'       => 'switcher',
									'title'      => __( 'Adaptive Height', 'woo-product-slider' ),
									'subtitle'   => __( 'Enable/Disable adaptive height to set fixed height for the carousel.', 'woo-product-slider' ),
									'text_on'    => __( 'Enabled', 'woo-product-slider' ),
									'text_off'   => __( 'Disabled', 'woo-product-slider' ),
									'text_width' => 100,
									'default'    => false,
									'dependency' => array( 'carousel_ticker_mode', '==', 'standard', true ),
								),
								array(
									'id'         => 'carousel_tab_key_nav',
									'type'       => 'switcher',
									'title'      => __( 'Tab & Key Navigation', 'woo-product-slider' ),
									'subtitle'   => __( 'Enable/Disable carousel scroll with tab and keyboard.', 'woo-product-slider' ),
									'text_on'    => __( 'Enabled', 'woo-product-slider' ),
									'text_off'   => __( 'Disabled', 'woo-product-slider' ),
									'text_width' => 100,
									'default'    => false,
									'dependency' => array( 'carousel_ticker_mode', '==', 'standard', true ),
								),
								array(
									'id'         => 'carousel_swipe',
									'type'       => 'switcher',
									'title'      => __( 'Touch Swipe', 'woo-product-slider' ),
									'subtitle'   => __( 'Enable/Disable touch swipe mode.', 'woo-product-slider' ),
									'text_on'    => __( 'Enabled', 'woo-product-slider' ),
									'text_off'   => __( 'Disabled', 'woo-product-slider' ),
									'text_width' => 100,
									'default'    => true,
									'dependency' => array( 'carousel_ticker_mode', '==', 'standard', true ),
								),
								array(
									'id'         => 'carousel_mouse_wheel',
									'type'       => 'switcher',
									'title'      => __( 'Mouse Wheel', 'woo-product-slider' ),
									'subtitle'   => __( 'Enable/Disable mouse wheel mode.', 'woo-product-slider' ),
									'text_on'    => __( 'Enabled', 'woo-product-slider' ),
									'text_off'   => __( 'Disabled', 'woo-product-slider' ),
									'text_width' => 100,
									'default'    => false,
									'dependency' => array(
										'carousel_swipe|carousel_ticker_mode',
										'==|==',
										'true|standard',
										true,
									),
								),
								array(
									'id'         => 'carousel_draggable',
									'type'       => 'switcher',
									'title'      => __( 'Mouse Draggable', 'woo-product-slider' ),
									'subtitle'   => __( 'Enable/Disable mouse draggable mode.', 'woo-product-slider' ),
									'text_on'    => __( 'Enabled', 'woo-product-slider' ),
									'text_off'   => __( 'Disabled', 'woo-product-slider' ),
									'text_width' => 100,
									'default'    => true,
									'dependency' => array(
										'carousel_swipe|carousel_ticker_mode',
										'==|==',
										'true|standard',
										true,
									),
								),
								array(
									'id'         => 'carousel_free_mode',
									'type'       => 'switcher',
									'title'      => __( 'Free Mode', 'woo-product-slider' ),
									'subtitle'   => __( 'Enable/Disable free mode.', 'woo-product-slider' ),
									'text_on'    => __( 'Enabled', 'woo-product-slider' ),
									'text_off'   => __( 'Disabled', 'woo-product-slider' ),
									'text_width' => 100,
									'default'    => false,
									'dependency' => array(
										'carousel_swipe|carousel_ticker_mode|carousel_draggable',
										'==|==|==',
										'true|standard|true',
										true,
									),
								),
							),
						),
					),
				),
			),
		)
	);

	/**
	 * Typography section.
	 */
	SPF_WPSP::createSection(
		$prefix,
		array(
			'title'  => __( 'Typography', 'woo-product-slider' ),
			'icon'   => 'fa fa-font',
			'fields' => array(
				array(
					'type'    => 'notice',
					'content' => __( 'Want to customize everything <b>(Colors and Typography)</b> easily? <a href="https://wooproductslider.io/pricing/?ref=1" target="_blank"><b>Upgrade To Pro!</b></a> Note: The Slider Section Title, Product Name, Product Price Font size and color fields work.', 'woo-product-slider' ),
				),
				array(
					'id'           => 'slider_title_typography',
					'type'         => 'typography',
					'title'        => __( 'Slider Section Title Font', 'woo-product-slider' ),
					'subtitle'     => __( 'Set slider section title font properties.', 'woo-product-slider' ),
					'default'      => array(
						'font-family'    => 'Open Sans',
						'font-weight'    => '600',
						'type'           => 'google',
						'font-size'      => '22',
						'line-height'    => '23',
						'text-align'     => 'left',
						'text-transform' => 'none',
						'letter-spacing' => '',
						'color'          => '#444444',
					),
					'preview_text' => 'Slider Section Title', // Replace preview text with any text you like.
				),
				array(
					'id'           => 'product_name_typography',
					'type'         => 'typography',
					'title'        => __( 'Product Name Font', 'woo-product-slider' ),
					'subtitle'     => __( 'Set product name font properties.', 'woo-product-slider' ),
					'default'      => array(
						'font-family'    => 'Open Sans',
						'font-weight'    => '600',
						'type'           => 'google',
						'font-size'      => '15',
						'line-height'    => '20',
						'text-align'     => 'center',
						'text-transform' => 'none',
						'letter-spacing' => '',
						'color'          => '#444444',
						'hover_color'    => '#955b89',
					),
					'hover_color'  => true,
					'preview_text' => 'Product Name', // Replace preview text with any text you like.
				),
				array(
					'id'       => 'product_description_typography',
					'type'     => 'typography',
					'title'    => __( 'Product Description Font', 'woo-product-slider' ),
					'subtitle' => __( 'Set product description font properties.', 'woo-product-slider' ),
					'class'    => 'product-description-typography',
					'default'  => array(
						'font-family'    => 'Open Sans',
						'font-weight'    => 'regular',
						'type'           => 'google',
						'font-size'      => '14',
						'line-height'    => '20',
						'text-align'     => 'center',
						'text-transform' => 'none',
						'letter-spacing' => '',
						'color'          => '#333333',
					),
				),
				array(
					'id'       => 'product_price_typography',
					'type'     => 'typography',
					'title'    => __( 'Product Price Font', 'woo-product-slider' ),
					'subtitle' => __( 'Set product price font properties.', 'woo-product-slider' ),
					'class'    => 'product-price-typography',
					'default'  => array(
						'font-family'    => 'Open Sans',
						'font-weight'    => '700',
						'type'           => 'google',
						'font-size'      => '14',
						'line-height'    => '19',
						'text-align'     => 'center',
						'text-transform' => 'none',
						'letter-spacing' => '',
						'color'          => '#222222',
					),
				),
				array(
					'id'       => 'sale_ribbon_typography',
					'type'     => 'typography',
					'title'    => __( 'Sale Ribbon Font', 'woo-product-slider' ),
					'subtitle' => __( 'Set product sale ribbon font properties.', 'woo-product-slider' ),
					'class'    => 'sale-ribbon-typography',
					'default'  => array(
						'font-family'    => 'Open Sans',
						'font-weight'    => 'regular',
						'type'           => 'google',
						'font-size'      => '10',
						'line-height'    => '10',
						'text-align'     => 'center',
						'text-transform' => 'uppercase',
						'letter-spacing' => '1',
						'color'          => '#ffffff',
					),
				),
				array(
					'id'       => 'out_of_stock_ribbon_typography',
					'type'     => 'typography',
					'title'    => __( 'Out of Stock Ribbon Font', 'woo-product-slider' ),
					'subtitle' => __( 'Set product out of stock ribbon font properties.', 'woo-product-slider' ),
					'class'    => 'out-of-stock-ribbon-typography',
					'default'  => array(
						'font-family'    => 'Open Sans',
						'font-weight'    => 'regular',
						'type'           => 'google',
						'font-size'      => '10',
						'line-height'    => '10',
						'text-align'     => 'center',
						'text-transform' => 'uppercase',
						'letter-spacing' => '1',
						'color'          => '#ffffff',
					),
				),
				array(
					'id'          => 'product_category_typography',
					'type'        => 'typography',
					'title'       => __( 'Product Category Font', 'woo-product-slider' ),
					'subtitle'    => __( 'Set product category font properties.', 'woo-product-slider' ),
					'class'       => 'product-category-typography',
					'default'     => array(
						'font-family'    => 'Open Sans',
						'font-weight'    => 'regular',
						'type'           => 'google',
						'font-size'      => '14',
						'line-height'    => '19',
						'text-align'     => 'center',
						'text-transform' => 'none',
						'letter-spacing' => '',
						'color'          => '#444444',
						'hover_color'    => '#955b89',
					),
					'hover_color' => true,
				),
				array(
					'id'       => 'compare_wishlist_typography',
					'type'     => 'typography',
					'title'    => __( 'Compare & Wishlist Font', 'woo-product-slider' ),
					'subtitle' => __( 'Set compare and wishlist font properties.', 'woo-product-slider' ),
					'class'    => 'compare-wishlist-typography',
					'default'  => array(
						'font-family'    => 'Open Sans',
						'font-weight'    => 'regular',
						'type'           => 'google',
						'font-size'      => '14',
						'line-height'    => '19',
						'text-align'     => 'center',
						'text-transform' => 'none',
						'letter-spacing' => '',
					),
					'color'    => false,
				),
				array(
					'id'       => 'add_to_cart_typography',
					'type'     => 'typography',
					'title'    => __( 'Add to Cart & View Details Font', 'woo-product-slider' ),
					'subtitle' => __( 'Set add to cart and view details font properties.', 'woo-product-slider' ),
					'class'    => 'add-to-cart-typography',
					'default'  => array(
						'font-family'    => 'Open Sans',
						'font-weight'    => '600',
						'type'           => 'google',
						'font-size'      => '14',
						'line-height'    => '19',
						'text-align'     => 'center',
						'text-transform' => 'none',
						'letter-spacing' => '',
					),
					'color'    => false,
				),

			),
		)
	);
