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
use Shop_Ready\base\elementor\style_controls\common\Widget_Form;

/**
 * WooCommerce Product Add To cart
 * @see https://docs.woocommerce.com/document/managing-products/
 * @author quomodosoft.com
 */
class Add_To_Cart extends \Shop_Ready\extension\elewidgets\Widget_Base
{
	use Widget_Form;

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
			'layouts_product_add_to_cart_section',
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
				'default' => 'default',
				'options' => [
					'default' => esc_html__('Default', 'shopready-elementor-addon'),
					//'wready-rating-two'   => esc_html__('Style 2','shopready-elementor-addon'),

				]
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'content_product_add_to_cart_section',
			[
				'label' => esc_html__('Simple Product Type', 'shopready-elementor-addon'),
				'tab' => \Elementor\Controls_Manager::TAB_SETTINGS,
			]
		);

		$this->add_control(
			'show_stock',
			[
				'label' => __('Stock?', 'shopready-elementor-addon'),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => __('Show', 'shopready-elementor-addon'),
				'label_off' => __('Hide', 'shopready-elementor-addon'),
				'return_value' => 'yes',
				'default' => 'yes',

			]
		);

		$this->add_control(
			'add_to_cart_input',
			[
				'label' => __('Product Cart Count', 'shopready-elementor-addon'),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => '',
				'options' => [
					'block' => __('Show', 'shopready-elementor-addon'),
					'none' => __('Hide', 'shopready-elementor-addon'),
				],

				'selectors' => [
					'{{WRAPPER}} .product-quantity' => 'display: {{VALUE}}',
					'{{WRAPPER}} .wooready_product_quantity' => 'display: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'simple_qty_label',
			[
				'label' => __('Show Qty Label', 'shopready-elementor-addon'),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => __('Show', 'shopready-elementor-addon'),
				'label_off' => __('Hide', 'shopready-elementor-addon'),
				'return_value' => 'yes',
				'default' => '',
			]
		);

		$this->add_control(
			'simple_qty_label_text',
			[
				'label' => __('Quantity', 'shopready-elementor-addon'),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => __('Quantity', 'shopready-elementor-addon'),
				'placeholder' => __('Type your Quantity label', 'shopready-elementor-addon'),
			]
		);

		$this->end_controls_section();
		// Settings Tab --> Simple Product Ends


		// Settings Tab --> Variable Product Starts
		$this->start_controls_section(
			'content_product_variabe_pro_section',
			[
				'label' => esc_html__('Variable Product Type', 'shopready-elementor-addon'),
				'tab' => \Elementor\Controls_Manager::TAB_SETTINGS,
			]
		);

		$this->add_control(
			'variable_stock_input',
			[
				'label' => __('Stock', 'shopready-elementor-addon'),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => '',
				'options' => [
					'block' => __('Show', 'shopready-elementor-addon'),
					'none' => __('Hide', 'shopready-elementor-addon'),
				],

				'selectors' => [
					'{{WRAPPER}} .woocommerce-variation-availability' => 'display: {{VALUE}}',
				],
			]
		);


		$this->add_control(
			'variable_qty_label',
			[
				'label' => __('Show Qty Label', 'shopready-elementor-addon'),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => __('Show', 'shopready-elementor-addon'),
				'label_off' => __('Hide', 'shopready-elementor-addon'),
				'return_value' => 'yes',
				'default' => 'yes',
			]
		);

		$this->add_control(
			'variable_qty_label_text',
			[
				'label' => __('Quantity', 'shopready-elementor-addon'),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => __('Quantity', 'shopready-elementor-addon'),
				'placeholder' => __('Type your Quantity label', 'shopready-elementor-addon'),
			]
		);

		$this->add_control(
			'variable_desc_input',
			[
				'label' => __('Description', 'shopready-elementor-addon'),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => '',
				'options' => [
					'block' => __('Show', 'shopready-elementor-addon'),
					'none' => __('Hide', 'shopready-elementor-addon'),
				],

				'selectors' => [
					'{{WRAPPER}} .woocommerce-variation-description' => 'display: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'add_to_cart_attr_select_fld',
			[
				'label' => __('Variable Product Select Field', 'shopready-elementor-addon'),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => '',
				'options' => [
					'block' => __('Show', 'shopready-elementor-addon'),
					'none' => __('Hide', 'shopready-elementor-addon'),
				],

				'selectors' => [
					'{{WRAPPER}} .woo-ready-product-var-table .value select' => 'display: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'add_to_cart_var_attr_select_fld',
			[
				'label' => __('Variable Product Label', 'shopready-elementor-addon'),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => '',
				'options' => [
					'block' => __('Show', 'shopready-elementor-addon'),
					'none' => __('Hide', 'shopready-elementor-addon'),
				],

				'selectors' => [
					'{{WRAPPER}} .woo-ready-product-var-table .wready-row label' => 'display: {{VALUE}} !important;',
				],
			]
		);

		$this->add_control(
			'w_ready_table_layout',
			[
				'label' => __('Table Layout', 'shopready-elementor-addon'),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'table',
				'options' => [
					'table' => __('Table', 'shopready-elementor-addon'),
					'list' => __('List', 'shopready-elementor-addon'),
				],

			]
		);

		$this->end_controls_section();
		// Settings Tab --> Variable Product Ends


		/**
		 * Layouts
		 */
		$this->box_layout(
			[
				'title' => esc_html__('Container Wrapper', 'shopready-elementor-addon'),
				'slug' => 'wready_wc__product_form',
				'element_name' => '_form_wrapper',
				'selector' => '{{WRAPPER}} .woo-ready-product-add-to-cart-layout form',
				'disable_controls' => [
					'position',
					'box-size',
				]

			]
		);

		$this->box_layout(
			[
				'title' => esc_html__('Simple Quantity Wrapper', 'shopready-elementor-addon'),
				'slug' => 'wready_wc__product_form_qty',
				'element_name' => 'wr__wrapper_qty',
				'selector' => '{{WRAPPER}} .woo-ready-product-add-to-cart-layout .shop-ready-quantity-warapper',
				'disable_controls' => [
					'position',
					'box-size',
				]
			]
		);
		/* Layouts End */


		$this->text_minimum_css(
			[
				'title' => esc_html__('Stock', 'shopready-elementor-addon'),
				'slug' => 'wready_wc_product_stock',
				'element_name' => 'wrating_prt_stock',
				'selector' => '{{WRAPPER}} p.stock,{{WRAPPER}} .sku_wrapper :not(span)',
				'hover_selector' => false,
			]
		);

		$this->text_minimum_css(
			[
				'title' => esc_html__('QTY Label', 'shopready-elementor-addon'),
				'slug' => 'wready_wc_product_qty_label',
				'element_name' => 'wrating_prt_qty_label',
				'selector' => '{{WRAPPER}} .shop-ready-product-qty-label',
				'hover_selector' => false
			]
		);



		$this->start_controls_section(
			'shop_ready_part_style_QTY_section',
			[
				'label' => esc_html__('QTY field', 'shopready-elementor-addon'),
				'tab' => Controls_Manager::TAB_STYLE,

			]
		);



		$this->add_control(
			'shop_ready_qty_display_block',
			[
				'label' => esc_html__('Display', 'shopready-elementor-addon'),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => '',
				'options' => [
					'block' => esc_html__('Block', 'shopready-elementor-addon'),
					'inline-flex' => esc_html__('Inline Flex', 'shopready-elementor-addon'),
					'inline-block' => esc_html__('Inline Block', 'shopready-elementor-addon'),
					'flex' => esc_html__('Flex', 'shopready-elementor-addon'),
					'' => esc_html__('None', 'shopready-elementor-addon'),
				],
				'selectors' => [
					'{{WRAPPER}} .elementor-widget-container .woo-ready-product-add-to-cart-layout.default .wooready_product_quantity .product-quantity' => 'display: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'shop_ready_qty_coheight',
			[
				'label' => __('Height', 'shopready-elementor-addon'),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px', '%'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1000,
						'step' => 5,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],

				'selectors' => [
					'{{WRAPPER}} .wooready_product_quantity .product-quantity,{{WRAPPER}} .shop-ready-quantity-warapper .quantity' => 'height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'shop_ready_qty_cowidth',
			[
				'label' => __('Width', 'shopready-elementor-addon'),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px', '%'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1000,
						'step' => 5,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],

				'selectors' => [
					'{{WRAPPER}} .wooready_product_quantity .product-quantity, {{WRAPPER}} .shop-ready-quantity-warapper .quantity' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'shop_ready_qty_color',
			[
				'label' => esc_html__('Color', 'shopready-elementor-addon'),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wooready_product_quantity .product-quantity, {{WRAPPER}} .wooready_product_quantity .product-quantity input, {{WRAPPER}} .shop-ready-quantity-warapper .quantity, {{WRAPPER}} .shop-ready-quantity-warapper .quantity input' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'shop_ready_qty_sub_color',
			[
				'label' => esc_html__('Counter Color', 'shopready-elementor-addon'),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wooready_product_quantity .product-quantity .woo-ready-qty-sub, {{WRAPPER}} .shop-ready-quantity-warapper .quantity .woo-ready-qty-sub' => 'color: {{VALUE}}',
					'{{WRAPPER}} .wooready_product_quantity .product-quantity .woo-ready-qty-add,  {{WRAPPER}} .shop-ready-quantity-warapper .quantity .woo-ready-qty-sub' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'shop_ready_quantity_input_typography',
				'selector' => '{{WRAPPER}} .wooready_product_quantity .product-quantity input,{{WRAPPER}} .wooready_product_quantity .product-quantity .woo-ready-qty-sub, {{WRAPPER}} .shop-ready-quantity-warapper .quantity input, {{WRAPPER}} .shop-ready-quantity-warapper .quantity .woo-ready-qty-sub',
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'shop_ready_quantity_input_background',
				'label' => esc_html__('Background', 'shopready-elementor-addon'),
				'types' => ['classic', 'gradient'],
				'selector' => '{{WRAPPER}} .wooready_product_quantity .product-quantity button,
					{{WRAPPER}} .product-quantity button, {{WRAPPER}} .wooready_product_quantity .product-quantity, {{WRAPPER}} .wooready_product_quantity .product-quantity input,
					{{WRAPPER}} .woo-ready-product-add-to-cart-layout.default .wooready_product_quantity .product-quantity input, {{WRAPPER}} .shop-ready-quantity-warapper .quantity, {{WRAPPER}} .shop-ready-quantity-warapper .quantity input',
			]
		);


		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'shop_ready_qty_fld_border',
				'label' => esc_html__('Border', 'shopready-elementor-addon'),
				'selector' => '{{WRAPPER}} .wooready_product_quantity .product-quantity, {{WRAPPER}} .shop-ready-quantity-warapper .quantity',
			]
		);

		$this->add_responsive_control(
			'shop_ready_qty_border_radius',
			[
				'label' => esc_html__('Border Radius', 'shopready-elementor-addon'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%', 'em'],
				'selectors' => [
					'{{WRAPPER}} .wooready_product_quantity .product-quantity, {{WRAPPER}} .shop-ready-quantity-warapper .quantity' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',

					'{{WRAPPER}} .wr-checkout-cart-product-qty .product-quantity, {{WRAPPER}} .shop-ready-quantity-warapper .quantity' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'shop_ready_qty_margin',
			[
				'label' => esc_html__('Margin', 'shopready-elementor-addon'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%', 'em'],
				'selectors' => [
					'{{WRAPPER}} .wooready_product_quantity .product-quantity, {{WRAPPER}} .shop-ready-quantity-warapper .quantity' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'shop_ready_qty_padding',
			[
				'label' => esc_html__('Padding', 'shopready-elementor-addon'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%', 'em'],
				'selectors' => [
					'{{WRAPPER}} .wooready_product_quantity .product-quantity, {{WRAPPER}} .shop-ready-quantity-warapper .quantity' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'shop_ready_qty_paddle_coheight',
			[
				'label' => __('Paddle Height', 'shopready-elementor-addon'),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px', '%'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1000,
						'step' => 5,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],

				'selectors' => [
					'{{WRAPPER}} .wooready_product_quantity .product-quantity button' => 'height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'shop_ready_qty_paddle_cowidth',
			[
				'label' => __('Paddle Width', 'shopready-elementor-addon'),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px', '%'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1000,
						'step' => 5,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],

				'selectors' => [
					'{{WRAPPER}} .wooready_product_quantity .product-quantity button' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'shop_ready_qty_paddle_bgcolor',
			[
				'label' => esc_html__('BGColor', 'shopready-elementor-addon'),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wooready_product_quantity .product-quantity button' => 'background: {{VALUE}}',
				],
			]
		);


		$this->add_control(
			'shop_ready_qty_paddle_color',
			[
				'label' => esc_html__('Color', 'shopready-elementor-addon'),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wooready_product_quantity .product-quantity button' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_responsive_control(
			'shop_ready_qty_paddle_margin',
			[
				'label' => esc_html__('Paddle Margin', 'shopready-elementor-addon'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%', 'em'],
				'selectors' => [
					'{{WRAPPER}} .wooready_product_quantity .product-quantity button' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		$this->text_minimum_css(
			[
				'title' => esc_html__('Simple Button', 'shopready-elementor-addon'),
				'slug' => 'wr_product_s_button',
				'element_name' => 'wr_style_button',
				'selector' => '{{WRAPPER}} .woo-ready-product-add-to-cart-layout form button',
				'hover_selector' => '{{WRAPPER}} .woo-ready-product-add-to-cart-layout form button:hover',
				'disable_controls' => [
					'display'
				]
			]
		);

		$this->text_minimum_css(
			[
				'title' => esc_html__('Variation Select Field', 'shopready-elementor-addon'),
				'slug' => 'wready_wc_product_variation_select',
				'element_name' => 'wr_product_select_input',
				'selector' => '{{WRAPPER}} .woo-ready-product-add-to-cart-layout form select',
				'hover_selector' => false,
				'disable_controls' => ['display']

			]
		);

		$this->text_minimum_css(
			[
				'title' => esc_html__('Variation Label', 'shopready-elementor-addon'),
				'slug' => 'wready_groupc_product_label_ty',
				'element_name' => 'wready_prt_varia_label_',
				'selector' => '{{WRAPPER}} .woo-ready-product-var-table .wready-row label',
				'hover_selector' => false,
				'tab' => Controls_Manager::TAB_STYLE,
				'disable_controls' => ['bg']

			]
		);

		$this->box_css(
			[
				'title' => esc_html__('Variation Color Item', 'shopready-elementor-addon'),
				'slug' => 'wready_groupc_product_label_ty_color',
				'element_name' => 'wready_prt_varia_label_color',
				'selector' => '{{WRAPPER}} .woo-ready-product-var-table .variation_color',
				'hover_selector' => false,
				'tab' => Controls_Manager::TAB_STYLE,
				'disable_controls' => ['position', 'size', 'alignment']
			]
		);

		$this->text_minimum_css(
			[
				'title' => esc_html__('Variation Reset Button', 'shopready-elementor-addon'),
				'slug' => 'wready_groupc_product_reset_btn',
				'element_name' => 'wready_prt_varia_reset_btn',
				'selector' => '{{WRAPPER}} .woo-ready-product-add-to-cart-layout form .reset_variations',
				'hover_selector' => '{{WRAPPER}} .woo-ready-product-add-to-cart-layout form .reset_variations:hover',
				'tab' => Controls_Manager::TAB_STYLE,
				'disable_controls' => ['display']
			]
		);

		$this->text_minimum_css(
			[
				'title' => esc_html__('Variable Button', 'shopready-elementor-addon'),
				'slug' => 'wr_product_variable_button',
				'element_name' => 'wr_style_variable_button',
				'selector' => '{{WRAPPER}} .wready-product-variation-wrapper .single_add_to_cart_button',
				'hover_selector' => '{{WRAPPER}} .wready-product-variation-wrapper .single_add_to_cart_button:hover',
				'disable_controls' => [
					'display',
				]
			]
		);


		$this->text_minimum_css(
			[
				'title' => esc_html__('Variation Price Style', 'shopready-elementor-addon'),
				'slug' => 'wready_groupc_productvariation_price',
				'element_name' => 'wready_prt_variation_price',
				'selector' => '{{WRAPPER}} .woocommerce-variation-price span',
				'hover_selector' => false,
				'tab' => Controls_Manager::TAB_STYLE

			]
		);

		$this->text_minimum_css(
			[
				'title' => esc_html__('Variation Regular Price Style', 'shopready-elementor-addon'),
				'slug' => 'wready_groupc_productvariation_regular_price',
				'element_name' => 'wready_prt_variation_regular_price',
				'selector' => '{{WRAPPER}} .woocommerce-variation-price span del .amount, {{WRAPPER}} .woocommerce-variation-price span del .amount .woocommerce-Price-currencySymbol, {{WRAPPER}} .wready-product-price del .amount .woocommerce-Price-currencySymbol',
				'hover_selector' => false,
				'tab' => Controls_Manager::TAB_STYLE

			]
		);

		$this->text_minimum_css(
			[
				'title' => esc_html__('Variation Sale Price Style', 'shopready-elementor-addon'),
				'slug' => 'wready_groupc_productvariation_sale_price',
				'element_name' => 'wready_prt_variation_sale_price',
				'selector' => '{{WRAPPER}} .woocommerce-variation-price span ins .amount, {{WRAPPER}} .woocommerce-variation-price span ins .amount .woocommerce-Price-currencySymbol, {{WRAPPER}} .wready-product-price ins .amount .woocommerce-Price-currencySymbol',
				'hover_selector' => false,
				'tab' => Controls_Manager::TAB_STYLE

			]
		);

		$this->text_minimum_css(
			[
				'title' => esc_html__('Variation Desc style', 'shopready-elementor-addon'),
				'slug' => 'wready_groupc_productvariation_desc',
				'element_name' => 'wready_prt_variation_desc',
				'selector' => '{{WRAPPER}} .woocommerce-variation-description p',
				'hover_selector' => false,
				'tab' => Controls_Manager::TAB_STYLE,
				'disable_controls' => [
					'display',
					'alignment',
					'border',
					'bg'
				]

			]
		);

		$this->text_minimum_css(
			[
				'title' => esc_html__('Variation Stock Style', 'shopready-elementor-addon'),
				'slug' => 'wready_groupc_productvariation_avai',
				'element_name' => 'wready_prt_variation_aca',
				'selector' => '{{WRAPPER}} .woocommerce-variation-availability p',
				'hover_selector' => false,
				'tab' => Controls_Manager::TAB_STYLE,
				'disable_controls' => [
					'display',
					'alignment',
				]

			]
		);


		// Group Product Controls
		$this->text_minimum_css(
			[
				'title' => esc_html__('Group Product Title', 'shopready-elementor-addon'),
				'slug' => 'wready_groupc_product_title',
				'element_name' => 'wready_prt_title',
				'selector' => '{{WRAPPER}} .woocommerce-grouped-product-list-item__label a',
				'hover_selector' => '{{WRAPPER}} .woocommerce-grouped-product-list-item__label a:hover',
				'disable_controls' => [
					'display',
					'alignment',
					'border',
					'bg'
				]
			]
		);

		$this->text_minimum_css(
			[
				'title' => esc_html__('Group Product Price', 'shopready-elementor-addon'),
				'slug' => 'wready_groupc_product_price',
				'element_name' => 'wready_prt_price',
				'selector' => '{{WRAPPER}} .woocommerce-grouped-product-list-item__price .woocommerce-Price-amount',
				'hover_selector' => false,
				'disable_controls' => [
					'display',
					'alignment',
					'border',
					'bg'
				]

			]
		);

		$this->text_minimum_css(
			[
				'title' => esc_html__('Group Product Sale Price', 'shopready-elementor-addon'),
				'slug' => 'wready_groupc_product_sale_price',
				'element_name' => 'wready_prt_sale_price',
				'selector' => '{{WRAPPER}} .woocommerce-grouped-product-list-item__price ins .woocommerce-Price-amount',
				'hover_selector' => false,
				'disable_controls' => [
					'display',
					'alignment',
					'border',
					'bg'
				]
			]
		);

		$this->text_minimum_css(
			[
				'title' => esc_html__('Group Product Regular Price', 'shopready-elementor-addon'),
				'slug' => 'wready_groupc_product_del_price',
				'element_name' => 'wready_prt_del_price',
				'selector' => '{{WRAPPER}} .woocommerce-grouped-product-list-item__price del .woocommerce-Price-amount',
				'hover_selector' => false,
				'disable_controls' => [
					'display',
					'alignment',
					'border',
					'bg'
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
				'class' => ['woo-ready-product-add-to-cart-layout', $settings['style']],
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

		if (file_exists(dirname(__FILE__) . '/template-parts/add_to_cart/' . $settings['style'] . '.php')) {
			shop_ready_widget_template_part(
				'product/template-parts/add_to_cart/' . $settings['style'] . '.php',
				array(

					'settings' => $settings,

				)
			);
		} else {
			shop_ready_widget_template_part(
				'product/template-parts/add_to_cart/default.php',
				array(
					'settings' => $settings,
				)
			);
		}

		echo wp_kses_post('</div>');
	}
}