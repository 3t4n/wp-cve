<?php

namespace Shop_Ready\extension\elewidgets\widgets\shop_sidebar;

/**
 * WooCommerce Shop Sidebar Cart Content
 * @see https://docs.woocommerce.com/document/woosidebars-2/
 * @see https://wordpress.org/support/article/wordpress-widgets/
 * @author quomodosoft.com
 */
class Cart extends \Shop_Ready\extension\elewidgets\Widget_Base
{

	public $wrapper_class = true;

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
				'raw' => esc_html__('Use This Widget in WooCommerce Shop Sidebar', 'shopready-elementor-addon'),
				'content_classes' => 'woo-ready-shop-notice',
			]
		);

		$this->add_control(
			'disable_cart_notice',
			[
				'label' => esc_html__('Disable Notice?', 'shopready-elementor-addon'),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => esc_html__('Yes', 'shopready-elementor-addon'),
				'label_off' => esc_html__('No', 'shopready-elementor-addon'),
				'return_value' => 'yes',
				'default' => '',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'layout_contents_section',
			[
				'label' => esc_html__('Layout', 'shopready-elementor-addon'),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'style',
			[
				'label' => esc_html__('Preset', 'shopready-elementor-addon'),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'style1',
				'options' => [
					'style1' => esc_html__('Preset 1', 'shopready-elementor-addon'),
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'wready_content_cart_section',
			[
				'label' => esc_html__('Settings', 'shopready-elementor-addon'),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'hide_image',
			[
				'label' => esc_html__('Hide Image?', 'shopready-elementor-addon'),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => '',
				'options' => [
					'none' => esc_html__('Yes', 'shopready-elementor-addon'),
					'inherit' => esc_html__('No', 'shopready-elementor-addon'),
				],
				'selectors' => [
					'{{WRAPPER}} .woocommerce-mini-cart.cart_list .woocommerce-mini-cart-item a img' => 'display: {{VALUE}}',
				],
			]
		);

		$this->end_controls_section();

		// $this->box_layout(
		// 	[
		// 		'title'          => esc_html__('Cart Content','shopready-elementor-addon'),
		// 		'slug'           => 'wready_wc_default_containern',
		// 		'element_name'   => 'cart_product_c',
		// 		'selector'       => '{{WRAPPER}} .woocommerce-mini-cart.cart_list',
		// 	]
		// );

		// $this->box_layout(
		// 	[
		// 		'title'          => esc_html__('Cart Item','shopready-elementor-addon'),
		// 		'slug'           => 'wready_layout_cart_item_lay',
		// 		'element_name'   => 'layout_product_item_layout',
		// 		'selector'       => '{{WRAPPER}} .woocommerce-mini-cart.cart_list .woocommerce-mini-cart-item',
		// 	]
		// );

		// $this->box_layout(
		// 	[
		// 		'title'          => esc_html__('Cart Total','shopready-elementor-addon'),
		// 		'slug'           => 'wready_layout_cart_sub_total',
		// 		'element_name'   => 'layout_subtotl_layout',
		// 		'selector'       => '{{WRAPPER}} .widget_shopping_cart_content .woocommerce-mini-cart__total',
		// 	]
		// );

		// $this->box_layout(
		// 	[
		// 		'title'          => esc_html__('Cart Button','shopready-elementor-addon'),
		// 		'slug'           => 'wready_layout_cart_button',
		// 		'element_name'   => 'layout_button_layout',
		// 		'selector'       => '{{WRAPPER}} .widget_shopping_cart_content .woocommerce-mini-cart__buttons',
		// 	]
		// );


		$this->box_css(
			[
				'title' => esc_html__('Box Style', 'shopready-elementor-addon'),
				'slug' => 'woo_box_style',
				'element_name' => 's__woo_ready__',
				'selector' => '{{WRAPPER}} .widget_shopping_cart_content'
			]
		);

		$this->box_css(
			[
				'title' => esc_html__('Cart Item Container', 'shopready-elementor-addon'),
				'slug' => 'wready_wc_default_cart_item_container',
				'element_name' => 'cart_product_item_container',
				'selector' => '{{WRAPPER}} .woocommerce-mini-cart.cart_list',
			]
		);

		$this->box_css(
			[
				'title' => esc_html__('Cart Item', 'shopready-elementor-addon'),
				'slug' => 'wready_wc_default_cart_item',
				'element_name' => 'cart_product_item',
				'selector' => '{{WRAPPER}} .woocommerce-mini-cart.cart_list .woocommerce-mini-cart-item',
			]
		);

		$this->text_minimum_css(
			[
				'title' => esc_html__('Remove Icon', 'shopready-elementor-addon'),
				'slug' => 'wready_wc_default_cart_item_remove_cion',
				'element_name' => 'cart_product_icon',
				'selector' => '{{WRAPPER}} .woocommerce-mini-cart.cart_list .woocommerce-mini-cart-item .remove_from_cart_button',
				'hover_selector' => '{{WRAPPER}} .woocommerce-mini-cart.cart_list .woocommerce-mini-cart-item .remove_from_cart_button:hover',


			]
		);

		$this->text_minimum_css(
			[
				'title' => esc_html__('Name', 'shopready-elementor-addon'),
				'slug' => 'wready_wc_default_cart_item_name',
				'element_name' => 'cart_product_name',
				'hover_selector' => '{{WRAPPER}} .woocommerce-mini-cart.cart_list .woocommerce-mini-cart-item:hover a',
				'selector' => '{{WRAPPER}} .woocommerce-mini-cart.cart_list .woocommerce-mini-cart-item a',


			]
		);

		$this->text_minimum_css(
			[
				'title' => esc_html__('Quentity', 'shopready-elementor-addon'),
				'slug' => 'wready_wc_default_cart_item_quantity',
				'element_name' => 'cart_product_item_quantity',
				'hover_selector' => false,
				'selector' => '{{WRAPPER}} .woocommerce-mini-cart.cart_list .woocommerce-mini-cart-item .quantity',


			]
		);

		$this->text_minimum_css(
			[
				'title' => esc_html__('Price', 'shopready-elementor-addon'),
				'slug' => 'wready_wc_default_cart_item_price',
				'element_name' => 'cart_product_item_price',
				'hover_selector' => false,
				'selector' => '{{WRAPPER}} .woocommerce-mini-cart.cart_list .woocommerce-mini-cart-item .quantity .woocommerce-Price-amount',
			]
		);

		$this->text_minimum_css(
			[
				'title' => esc_html__('SubTotal Label', 'shopready-elementor-addon'),
				'slug' => 'wready_wc_default_cart_item_subtotal',
				'element_name' => 'cart_product_item_total',
				'hover_selector' => false,
				'selector' => '{{WRAPPER}} .woocommerce-mini-cart__total.total strong',
			]
		);

		$this->text_minimum_css(
			[
				'title' => esc_html__('SubTotal Value', 'shopready-elementor-addon'),
				'slug' => 'wready_wc_default_sub_value_',
				'element_name' => 'cart_product_item_value',
				'hover_selector' => false,
				'selector' => '{{WRAPPER}} .woocommerce-mini-cart__total.total .woocommerce-Price-amount',
			]
		);

		$this->text_css(
			[
				'title' => esc_html__('View Cart', 'shopready-elementor-addon'),
				'slug' => 'wready_wc_default_view_cart',
				'element_name' => 'cart_product_view_btn',
				'hover_selector' => '{{WRAPPER}} .woocommerce-mini-cart__buttons .wc-forward:hover',
				'selector' => '{{WRAPPER}} .woocommerce-mini-cart__buttons .wc-forward',
			]
		);


		$this->text_css(
			[
				'title' => esc_html__('Checkout', 'shopready-elementor-addon'),
				'slug' => 'wready_wc_default_checkout',
				'element_name' => 'cart_product_checkout',
				'hover_selector' => '{{WRAPPER}} .woocommerce-mini-cart__buttons .checkout:hover',
				'selector' => '{{WRAPPER}} .woocommerce-mini-cart__buttons .checkout',
			]
		);
	}


	protected function html()
	{

		$settings = $this->get_settings_for_display();

		$this->add_render_attribute(
			'wrapper_style',
			[
				'class' => ['display:block', 'woo-ready-shop-cart-content-layout', $settings['style']],
			]
		);



		wp_kses_post(sprintf("<div %s>", wp_kses_post($this->get_render_attribute_string('wrapper_style'))));

		if (file_exists(dirname(__FILE__) . '/template-parts/cart/' . $settings['style'] . '.php')) {

			shop_ready_widget_template_part(
				'shop_sidebar/template-parts/cart/' . $settings['style'] . '.php',
				array(
					'settings' => $settings,

				)
			);
		} else {

			shop_ready_widget_template_part(
				'shop_sidebar/template-parts/cart/style1.php',
				array(
					'settings' => $settings,
				)
			);
		}

		echo wp_kses_post('</div>');
	}
}