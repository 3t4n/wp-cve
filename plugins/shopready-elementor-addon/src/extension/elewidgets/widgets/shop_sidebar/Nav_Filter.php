<?php

namespace Shop_Ready\extension\elewidgets\widgets\shop_sidebar;

use Automattic\Jetpack\Constants;

/**
 * WooCommerce Shop Sidebar Layer Nav
 * @see https://docs.woocommerce.com/document/woosidebars-2/
 * @see https://wordpress.org/support/article/wordpress-widgets/
 * @author quomodosoft.com
 */
class Nav_Filter extends \Shop_Ready\extension\elewidgets\Widget_Base
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



		$this->end_controls_section();

		$this->box_layout(
			[
				'title' => esc_html__('Cart Content', 'shopready-elementor-addon'),
				'slug' => 'wready_wc_default_containern',
				'element_name' => 'cart_product_c',
				'selector' => '{{WRAPPER}} .woocommerce-mini-cart.cart_list',
			]
		);

		$this->box_layout(
			[
				'title' => esc_html__('Cart Item', 'shopready-elementor-addon'),
				'slug' => 'wready_layout_cart_item_lay',
				'element_name' => 'layout_product_item_layout',
				'selector' => '{{WRAPPER}} .woocommerce-mini-cart.cart_list .woocommerce-mini-cart-item',
			]
		);

		$this->box_layout(
			[
				'title' => esc_html__('Cart Total', 'shopready-elementor-addon'),
				'slug' => 'wready_layout_cart_sub_total',
				'element_name' => 'layout_subtotl_layout',
				'selector' => '{{WRAPPER}} .widget_shopping_cart_content .woocommerce-mini-cart__total',
			]
		);
	}


	protected function html()
	{

		global $woocommerce;
		if (!isset($woocommerce->query)) {
			return;
		}

		$settings = $this->get_settings_for_display();

		$this->add_render_attribute(
			'wrapper_style',
			[
				'class' => ['display:block', 'woo-ready-shop-layer-nav', $settings['style']],
			]
		);


		wp_kses_post(sprintf("<div %s>", wp_kses_post($this->get_render_attribute_string('wrapper_style'))));

		if (file_exists(dirname(__FILE__) . '/template-parts/layer-nav/' . $settings['style'] . '.php')) {

			shop_ready_widget_template_part(
				'shop_sidebar/template-parts/layer-nav/' . $settings['style'] . '.php',
				array(
					'settings' => $settings,
					'base_url' => $this->get_current_page_url(),

				)
			);
		} else {

			shop_ready_widget_template_part(
				'shop_sidebar/template-parts/layer-nav/style1.php',
				array(
					'settings' => $settings,
					'base_url' => $this->get_current_page_url(),

				)
			);
		}

		echo wp_kses_post('</div>');
	}
}