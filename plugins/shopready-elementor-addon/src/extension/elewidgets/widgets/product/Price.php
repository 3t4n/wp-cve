<?php

namespace Shop_Ready\extension\elewidgets\widgets\product;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Css_Filter;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Image_Size;
use Elementor\Modules\DynamicTags\Module as TagsModule;
use Elementor\Utils;
use Elementor\Plugin;

/**
 * WooCommerce Product Title | Name
 * @see https://docs.woocommerce.com/document/managing-products/
 * @author quomodosoft.com
 */
class Price extends \Shop_Ready\extension\elewidgets\Widget_Base
{


	/**
	 * Html Wrapper Class of html 
	 */
	public $wrapper_class = false;

	protected function register_controls()
	{

		// Notice 
		$this->start_controls_section(
			'notice_content_section',
			[
				'label' => esc_html__('Notice', 'shopready-elementor-addon'),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'woo_ready_usage_direction_notice',
			[
				'label' => esc_html__('Important Note', 'shopready-elementor-addon'),
				'type' => \Elementor\Controls_Manager::RAW_HTML,
				'raw' => esc_html__('Use This Widget in WooCommerce Product Details page  Template.', 'shopready-elementor-addon'),
				'content_classes' => 'woo-ready-product-page-notice',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'editor_content_section',
			[
				'label' => esc_html__('Editor Refresh', 'shopready-elementor-addon'),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'show_product_content',
			[
				'label' => esc_html__('Content Refresh?', 'shopready-elementor-addon'),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => esc_html__('Yes', 'shopready-elementor-addon'),
				'label_off' => esc_html__('No', 'shopready-elementor-addon'),
				'return_value' => 'yes',
				'default' => '',
			]
		);

		$this->add_control(
			'wready_product_id',
			[
				'label' => esc_html__('Demo Product', 'shopready-elementor-addon'),
				'type' => \Elementor\Controls_Manager::SELECT2,
				'multiple' => false,
				'default' => shop_ready_get_single_product_key(),
				'options' => shop_ready_get_latest_products_id(50)
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'layouts_cart_content_section',
			[
				'label' => esc_html__('Layout', 'shopready-elementor-addon'),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'style',
			[
				'label' => esc_html__('Layout', 'shopready-elementor-addon'),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'wready-price-one',
				'options' => [
					'wready-price-one' => esc_html__('Default', 'shopready-elementor-addon'),
					'wready-price-two' => esc_html__('Discount Style', 'shopready-elementor-addon'),
					'wready-price-three' => esc_html__('Sale Price Only Style', 'shopready-elementor-addon'),
					'wready-price-four' => esc_html__('Price Only', 'shopready-elementor-addon'),
					'wready-price-five' => esc_html__('Regular Price Only', 'shopready-elementor-addon'),

				]
			]
		);


		$this->end_controls_section();
		$this->start_controls_section(
			'product_discount_content_section',
			[
				'label' => esc_html__('Discount', 'shopready-elementor-addon'),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
				'condition' => [
					'style' => ['wready-price-two']
				]
			]
		);

		$this->add_control(
			'price_in_percent',
			[
				'label' => esc_html__('Discount in % ?', 'shopready-elementor-addon'),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => esc_html__('Yes', 'shopready-elementor-addon'),
				'label_off' => esc_html__('No', 'shopready-elementor-addon'),
				'return_value' => 'yes',
				'default' => 'yes',
			]
		);

		$this->add_control(
			'discount_prefix',
			[
				'label' => esc_html__('Discount Prefix', 'shopready-elementor-addon'),
				'type' => Controls_Manager::TEXTAREA,
				'default' => 'Save',
				'placeholder' => esc_html__('Save', 'shopready-elementor-addon'),
				'separator' => 'before',
			]
		);

		$this->add_control(
			'discount_suffix',
			[
				'label' => esc_html__('Discount Suffix', 'shopready-elementor-addon'),
				'type' => Controls_Manager::TEXTAREA,
				'default' => '',
				'placeholder' => esc_html__('Save', 'shopready-elementor-addon'),
				'separator' => 'before',
			]
		);

		$this->end_controls_section();

		/**
		 * Layouts Total Table
		 */
		$this->box_layout(
			[
				'title' => esc_html__('Container Wrapper', 'shopready-elementor-addon'),
				'slug' => 'wready_wc_default_product_layout_wrapper',
				'element_name' => 'cart_product_title_wrapper',
				'selector' => '{{WRAPPER}} .woo-ready-product-price-layout .wready-product-price',

			]
		);

		/* Layouts End*/


		$this->text_minimum_css(
			[
				'title' => esc_html__('Price', 'shopready-elementor-addon'),
				'slug' => 'wready_wc_default_product_price_c',
				'element_name' => '_product_price_',
				'hover_selector' => false,
				'selector' => '{{WRAPPER}} .woo-ready-product-price-layout .wready-product-price bdi',
				'condition' => [
					'style' => ['wready-price-two']
				],

			]
		);

		$this->text_minimum_css(
			[
				'title' => esc_html__('Percent Price', 'shopready-elementor-addon'),
				'slug' => 'wready_wc_default_product_price_cs',
				'element_name' => '_product_price_s',
				'hover_selector' => false,
				'disable_controls' => [
					//'position','size', 'display','dimensions','alignment','box-shadow','border','bg'
					'display'
				],
				'selector' => '{{WRAPPER}} .woo-ready-product-price-layout .wready-product-price',


			]
		);

		$this->text_wrapper_css(
			[
				'title' => esc_html__('Currency', 'shopready-elementor-addon'),
				'slug' => 'wready_wc_default_product_price_currency',
				'element_name' => '_product_price_curren',
				'hover_selector' => false,
				'selector' => '{{WRAPPER}} .woo-ready-product-price-layout .wready-product-price .woocommerce-Price-currencySymbol,{{WRAPPER}} .woo-ready-product-price-layout .wready-product-price bdi > span',
				'disable_controls' => [
					//'position','size', 'display','dimensions','alignment','box-shadow','border','bg'
					'display',
					'size'
				]

			]
		);

		$this->text_wrapper_css(
			[
				'title' => esc_html__('Regular Price', 'shopready-elementor-addon'),
				'slug' => 'wready_wc_default_product_regular_price_del',
				'element_name' => '_product_price_reg',
				'hover_selector' => false,
				'selector' => '{{WRAPPER}} .woo-ready-product-price-layout .wready-product-price del span',
				'condition' => [
					'style' => ['wready-price-one']
				],
				'disable_controls' => [
					//'position','size', 'display','dimensions','alignment','box-shadow','border','bg'
					'display',
					'size'
				]
			]
		);

		// $this->text_wrapper_css(
		// 	[
		// 		'title'          => esc_html__('Sale Price Wrapper', 'shopready-elementor-addon'),
		// 		'slug'           => 'wready_wc__product_regular_price_ins',
		// 		'element_name'   => '_product_price_ins',
		// 		'hover_selector' =>  false,
		// 		'selector'       => '{{WRAPPER}} .woo-ready-product-price-layout .wready-product-price ins,{{WRAPPER}} .woo-ready-product-price-layout .wready-product-price ins',
		// 		'condition' => [
		// 			'style' => ['wready-price-one']
		// 		]
		// 	]
		// );

		$this->text_wrapper_css(
			[
				'title' => esc_html__('Sale Price', 'shopready-elementor-addon'),
				'slug' => 'wready_wc__product_sales_price_ins',
				'element_name' => '_product_Sales_price_ins',
				'hover_selector' => false,
				'selector' => '{{WRAPPER}} .woo-ready-product-price-layout .wready-product-price ins span',
				'condition' => [
					'style' => ['wready-price-one']
				]
			]
		);

		$this->text_wrapper_css(
			[
				'title' => esc_html__('Sale Price Currency', 'shopready-elementor-addon'),
				'slug' => 'wready_wc__product_sales_cur_ins',
				'element_name' => '_product_Sales_price_ins',
				'hover_selector' => false,
				'selector' => '{{WRAPPER}} .woo-ready-product-price-layout .wready-product-price ins .woocommerce-Price-currencySymbol,{{WRAPPER}} .woo-ready-product-price-layout .wready-product-price ins span.woocommerce-Price-currencySymbol',
				'condition' => [
					'style' => ['wready-price-one']
				],
				'disable_controls' => [
					//'position','size', 'display','dimensions','alignment','box-shadow','border','bg'
					'size',
					'display',
					'alignment',
				]
			]
		);

		$this->text_wrapper_css(
			[
				'title' => esc_html__('Suffix / Prefix Text', 'shopready-elementor-addon'),
				'slug' => 'wready_wc_product_sales_suffic_prefox',
				'element_name' => '_product_Sales_price_sf',
				'hover_selector' => false,
				'selector' => '{{WRAPPER}} .woo-ready-product-price-layout .wready-product-price',
				'condition' => [
					'style' => ['wready-price-two']
				]
			]
		);
	}

	/**
	 * Override By elementor render method
	 * @return void
	 * 
	 */
	protected function html()
	{

		$settings = $this->get_settings_for_display();

		$this->add_render_attribute(
			'wrapper_style',
			[
				'class' => ['woo-ready-product-price-layout', $settings['style']],
			]
		);

		if (shop_ready_is_elementor_mode()) {

			$temp_id = WC()->session->get('sr_single_product_id');
			if ($settings['show_product_content'] == 'yes' && is_numeric($settings['wready_product_id'])) {
				$temp_id = $settings['wready_product_id'];
			}
			if (is_numeric($temp_id)) {
				setup_postdata($temp_id);
			} else {
				setup_postdata(shop_ready_get_single_product_key());
			}
		}

		echo wp_kses_post(sprintf("<div %s>", $this->get_render_attribute_string('wrapper_style')));

		if (file_exists(dirname(__FILE__) . '/template-parts/price/' . $settings['style'] . '.php')) {
			shop_ready_widget_template_part(
				'product/template-parts/price/' . $settings['style'] . '.php',
				array(
					'settings' => $settings,
				)
			);
		} else {
			shop_ready_widget_template_part(
				'product/template-parts/price/wready-price-one.php',
				array(
					'settings' => $settings,
				)
			);
		}

		echo wp_kses_post('</div>');
	}
}
