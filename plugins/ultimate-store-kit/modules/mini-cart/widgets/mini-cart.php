<?php

namespace UltimateStoreKit\Modules\MiniCart\Widgets;

use UltimateStoreKit\Base\Module_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Background;

use Elementor\Plugin;
use Elementor\Utils;
use UltimateStoreKit\Modules\Woocommerce\Module;

if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly.
}

class Mini_Cart extends Module_Base {

	public function get_name() {
		return 'usk-mini-cart';
	}

	public function get_title() {
		return  esc_html__('Mini Cart', 'ultimate-store-kit');
	}

	public function get_icon() {
		return 'usk-widget-icon usk-icon-mini-cart';
	}

	public function get_categories() {
		return ['ultimate-store-kit'];
	}

	public function get_keywords() {
		return ['mini cart', 'cart', 'wc', 'woocommerce', 'add to cart'];
	}

	public function get_style_depends() {
		if ($this->usk_is_edit_mode()) {
			return ['usk-all-styles'];
		} else {
			return ['ultimate-store-kit-font', 'usk-mini-cart', 'toolslide-css'];
		}
	}

	public function get_script_depends() {
		if ($this->usk_is_edit_mode()) {
			return ['toolslide-js', 'usk-all-styles'];
		} else {
			return ['toolslide-js', 'usk-mini-cart'];
		}
	}

	protected function render_layout_controls_offcanvas() {
		$this->start_controls_section(
			'section_content_layout',
			[
				'label' => esc_html__('Offcanvas', 'ultimate-store-kit'),
			]
		);

		$this->add_control(
			'custom_widget_cart_title',
			[
				'label'   => esc_html__('Cart Title', 'ultimate-store-kit'),
				'type'    => Controls_Manager::TEXT,
				'dynamic' => ['active' => true],
				'default' => esc_html__('Shopping Cart', 'ultimate-store-kit'),
			]
		);
		$this->end_controls_section();
	}
	protected function render_controls_settings() {
		$this->start_controls_section(
			'section_content_mini_cart_settings',
			[
				'label' => esc_html__('Settings', 'ultimate-store-kit'),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);
		$this->add_control(
			'usk_mini_cart_position',
			[
				'label'      => esc_html__('Position', 'ultimate-store-kit'),
				'type'       => Controls_Manager::SELECT,
				'options'    => [
					'left'  => esc_html__('Left', 'ultimate-store-kit'),
					'right' => esc_html__('Right (Default)', 'ultimate-store-kit'),
					'bottom' => esc_html__('Bottom', 'ultimate-store-kit'),
					'top' => esc_html__('Top', 'ultimate-store-kit'),
				],
				'default'    => 'right',
				'dynamic'    => ['active' => true],
				'render_type' => 'template',
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'usk_mini_cart_height',
			[
				'label'   => esc_html__('Height', 'ultimate-store-kit'),
				'type'    => Controls_Manager::SLIDER,
				'size_units'    => ['px', '%', 'vh'],
				'range'         => [
					'px'        => [
						'min'   => 0,
						'max'   => 1200,
						'step'  => 1,
					],
				],
				'default'       => [
					'unit'      => '%',
					'size'      => 100,
				],
				'render_type' => 'none',
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'usk_mini_cart_width',
			[
				'label'   => esc_html__('Width', 'ultimate-store-kit'),
				'type'    => Controls_Manager::SLIDER,
				'size_units'    => ['px', '%', 'vh'],
				'range'         => [
					'px'        => [
						'min'   => 0,
						'max'   => 1200,
						'step'  => 1,
					],
				],
				'default'       => [
					'unit'      => 'px',
					'size'      => 340,
				],
				'render_type' => 'none',
				'frontend_available' => true,
			]
		);
		// $this->add_control(
		// 	'usk_mini_cart_closeable',
		// 	[
		// 		'label'         => esc_html__('Closeable', 'ultimate-store-kit'),
		// 		'type'          => Controls_Manager::SWITCHER,
		// 		'label_on'      => esc_html__('Yes', 'ultimate-store-kit'),
		// 		'label_off'     => esc_html__('No', 'ultimate-store-kit'),
		// 		'render_type' => 'none',
		// 		'frontend_available' => true,
		// 	]
		// );

		$this->add_control(
			'usk_mini_cart_startOpen',
			[
				'label'         => esc_html__('Start Open', 'ultimate-store-kit'),
				'type'          => Controls_Manager::SWITCHER,
				'label_on'      => esc_html__('Yes', 'ultimate-store-kit'),
				'label_off'     => esc_html__('No', 'ultimate-store-kit'),
				'render_type' => 'none',
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'usk_mini_cart_autoclose',
			[
				'label'         => esc_html__('Auto close', 'ultimate-store-kit'),
				'type'          => Controls_Manager::SWITCHER,
				'label_on'      => esc_html__('Yes', 'ultimate-store-kit'),
				'label_off'     => esc_html__('No', 'ultimate-store-kit'),
				'render_type' => 'none',
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'usk_mini_cart_clickOutsideToClose',
			[
				'label'         => esc_html__('Click Outside To Close', 'ultimate-store-kit'),
				'type'          => Controls_Manager::SWITCHER,
				'label_on'      => esc_html__('Yes', 'ultimate-store-kit'),
				'label_off'     => esc_html__('No', 'ultimate-store-kit'),
				'render_type' => 'none',
				'default' => 'yes',
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'usk_mini_cart_autocloseDelay',
			[
				'label'         => esc_html__('Auto Close Delay', 'ultimate-store-kit'),
				'description'   => esc_html__('Description', 'ultimate-store-kit'),
				'type'          => Controls_Manager::NUMBER,
				'min'           => 0,
				'max'           => 10000,
				'step'          => 1,
				'default'       => 5000,
				'dynamic'       => ['active' => true],
				'render_type' => 'none',
				'frontend_available' => true,
			]
		);
		$this->end_controls_section();
	}
	protected function render_controls_mini_cart() {
		$this->start_controls_section(
			'section_content_mini_cart',
			[
				'label' => esc_html__('Mini Cart', 'ultimate-store-kit'),
			]
		);

		$this->add_control(
			'show_price_amount',
			[
				'label'   => esc_html__('Show Price Amount', 'ultimate-store-kit'),
				'type'    => Controls_Manager::SWITCHER,
			]
		);

		$this->add_control(
			'show_cart_icon',
			[
				'label'   => esc_html__('Show Cart Icon', 'ultimate-store-kit'),
				'type'    => Controls_Manager::SWITCHER,
				'default' => 'yes',

			]
		);
		$this->add_responsive_control(
			'mini_cart_align',
			[
				'label'   => esc_html__('Alignment', 'ultimate-store-kit'),
				'type'    => Controls_Manager::CHOOSE,
				'options' => [
					'left'    => [
						'title' => esc_html__('Left', 'ultimate-store-kit'),
						'icon'  => 'eicon-h-align-left',
					],
					'center' => [
						'title' => esc_html__('Center', 'ultimate-store-kit'),
						'icon'  => 'eicon-h-align-center',
					],
					'right' => [
						'title' => esc_html__('Right', 'ultimate-store-kit'),
						'icon'  => 'eicon-h-align-right',
					],
				],
				'prefix_class' => 'elementor%s-align-',
				'default'      => 'left',
			]
		);

		$this->add_control(
			'mini_cart_icon_indent',
			[
				'label'   => esc_html__('Icon Spacing', 'ultimate-store-kit'),
				'type'    => Controls_Manager::SLIDER,
				'default' => [
					'size' => 8,
				],
				'range' => [
					'px' => [
						'max' => 50,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .usk-mini-cart-toggle-btn .bdt-mini-cart-inner' => 'grid-gap: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'show_cart_icon' => 'yes',
					'show_price_amount' => 'yes'
				]
			]
		);

		$this->end_controls_section();
	}

	public function render_style_controls_mini_cart() {
		$this->start_controls_section(
			'section_style_mini_cart_content',
			[
				'label' => esc_html__('Mini Cart', 'ultimate-store-kit'),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'mini_cart_price_amount_color',
			[
				'label'     => esc_html__('Amount Color', 'ultimate-store-kit'),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .usk-mini-cart-toggle-btn .bdt-mini-cart-inner .bdt-mini-cart-price-amount *' => 'color: {{VALUE}};',
				],
				'condition' => [
					'show_price_amount' => 'yes'
				]
			]
		);

		$this->add_control(
			'mini_cart_icon_color',
			[
				'label'     => esc_html__('Icon Color', 'ultimate-store-kit'),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .usk-mini-cart-toggle-btn .bdt-mini-cart-inner .usk-cart-icon i' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'mini_cart_background_color',
			[
				'label'     => esc_html__('Background Color', 'ultimate-store-kit'),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .usk-mini-cart-toggle-btn .bdt-mini-cart-inner' => 'background: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'        => 'mini_cart_border',
				'label'       => esc_html__('Border', 'ultimate-store-kit'),
				'selector'    => '{{WRAPPER}} .usk-mini-cart-toggle-btn .bdt-mini-cart-inner',
			]
		);

		$this->add_control(
			'mini_cart_border_radius',
			[
				'label'      => esc_html__('Border Radius', 'ultimate-store-kit'),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%'],
				'selectors'  => [
					'{{WRAPPER}} .usk-mini-cart-toggle-btn .bdt-mini-cart-inner' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'mini_cart_padding',
			[
				'label'      => esc_html__('Padding', 'ultimate-store-kit'),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', 'em', '%'],
				'selectors'  => [
					'{{WRAPPER}} .usk-mini-cart-toggle-btn .bdt-mini-cart-inner' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'price_amount_typography',
				'selector' => '{{WRAPPER}} .bdt-mini-cart-inner .woocommerce-Price-amount.amount, {{WRAPPER}} .usk-mini-cart-toggle-btn .bdt-mini-cart-inner .usk-cart-icon i',
			]
		);

		$this->add_control(
			'mini_cart_badge_style',
			[
				'label' 	=> esc_html__('Cart Badge', 'ultimate-store-kit'),
				'type' 		=> Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'mini_cart_badge_color',
			[
				'label'     => esc_html__('Color', 'ultimate-store-kit'),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .usk-mini-cart-toggle-btn .bdt-mini-cart-inner .usk-cart-badge' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'mini_cart_badge_background_color',
			[
				'label'     => esc_html__('Background', 'ultimate-store-kit'),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .usk-mini-cart-toggle-btn .bdt-mini-cart-inner .usk-cart-badge' => 'background: {{VALUE}};',
				],
			]
		);
		$this->add_responsive_control(
			'mini_cart_badge_padding',
			[
				'label'                 => esc_html__('Padding', 'ultimate-store-kit'),
				'type'                  => Controls_Manager::DIMENSIONS,
				'size_units'            => ['px', '%', 'em'],
				'selectors'             => [
					'{{WRAPPER}} .usk-mini-cart-toggle-btn .bdt-mini-cart-inner .usk-cart-badge'    => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'cart_badge_typography',
				'selector' => '{{WRAPPER}} .usk-mini-cart-toggle-btn .bdt-mini-cart-inner .usk-cart-badge',
			]
		);

		$this->end_controls_section();
	}
	protected function render_style_controls_offcanvas() {
		$this->start_controls_section(
			'section_style_offcanvas_content',
			[
				'label' => esc_html__('Offcanvas', 'ultimate-store-kit'),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->start_controls_tabs('tabs_style_offcanvas_content');

		$this->start_controls_tab(
			'tab_style_product_cart',
			[
				'label' => esc_html__('Product List', 'ultimate-store-kit'),
			]
		);

		$this->add_control(
			'product_cart_main_title_color',
			[
				'label'     => esc_html__('Cart Title Color', 'ultimate-store-kit'),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'#usk-mini-cart-{{ID}}.usk-mini-cart .ts-content-container .usk-widget-title' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'product_cart_main_title_border_color',
			[
				'label'     => esc_html__('Cart Border Color', 'ultimate-store-kit'),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'#usk-mini-cart-{{ID}}.usk-mini-cart .usk-widget-title' => 'border-color: {{VALUE}}',
					'#usk-mini-cart-{{ID}}.usk-mini-cart .bdt-mini-cart-product-item' => 'border-bottom-color: {{VALUE}}'
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'product_cart_main_title_typography',
				'selector' => '#usk-mini-cart-{{ID}}.usk-mini-cart .usk-widget-title',
			]
		);

		$this->add_control(
			'product_cart_style',
			[
				'label' 	=> esc_html__('Product Cart', 'ultimate-store-kit'),
				'type' 		=> Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'product_cart_title_color',
			[
				'label'     => esc_html__('Title Color', 'ultimate-store-kit'),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'#usk-mini-cart-{{ID}}.usk-mini-cart .ts-content-container .bdt-mini-cart-product-name a' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'product_cart_title_hover_color',
			[
				'label'     => esc_html__('Title Hover Color', 'ultimate-store-kit'),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'#usk-mini-cart-{{ID}}.usk-mini-cart .ts-content-container .bdt-mini-cart-product-name a:hover' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'product_cart_title_typography',
				'selector' => '#usk-mini-cart-{{ID}}.usk-mini-cart .ts-content-container .bdt-mini-cart-product-name a',
			]
		);

		// $this->add_control(
		// 	'product_cart_item_border_color',
		// 	[
		// 		'label'     => esc_html__('Item Border Color', 'ultimate-store-kit'),
		// 		'type'      => Controls_Manager::COLOR,
		// 		'selectors' => [
		// 			'#usk-mini-cart-{{ID}}.usk-mini-cart .ts-content-container .bdt-mini-cart-product-item' => 'border-color: {{VALUE}};',
		// 		],
		// 		'separator'   => 'before',
		// 	]
		// );

		$this->add_control(
			'product_cart_quantity_price_style',
			[
				'label' 	=> esc_html__('Price', 'ultimate-store-kit'),
				'type' 		=> Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'product_cart_quantity_color',
			[
				'label'     => esc_html__('Quantity Color', 'ultimate-store-kit'),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'#usk-mini-cart-{{ID}}.usk-mini-cart .ts-content-container .bdt-mini-cart-product-item .bdt-mini-cart-product-price' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'product_cart_price_color',
			[
				'label'     => esc_html__('Amount Color', 'ultimate-store-kit'),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'#usk-mini-cart-{{ID}}.usk-mini-cart .ts-content-container .bdt-mini-cart-product-item .woocommerce-Price-amount .amount *' => 'color: {{VALUE}} !important;',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'product_cart_price_typography',
				'selector' => '#usk-mini-cart-{{ID}}.usk-mini-cart .ts-content-container .bdt-mini-cart-product-item .bdt-mini-cart-product-price',
			]
		);

		$this->add_control(
			'product_cart_image_style',
			[
				'label' 	=> esc_html__('Image', 'ultimate-store-kit'),
				'type' 		=> Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'        => 'product_cart_image_border',
				'label'       => esc_html__('Image Border', 'ultimate-store-kit'),
				'selector'    => '#usk-mini-cart-{{ID}}.usk-mini-cart .ts-content-container .bdt-mini-cart-product-item .bdt-mini-cart-product-thumbnail img',
			]
		);

		$this->add_responsive_control(
			'product_cart_image_radius',
			[
				'label'      => esc_html__('Image Border Radius', 'ultimate-store-kit'),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%'],
				'selectors'  => [
					'#usk-mini-cart-{{ID}}.usk-mini-cart .ts-content-container .bdt-mini-cart-product-item .bdt-mini-cart-product-thumbnail img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; overflow: hidden;',
				],
			]
		);

		$this->add_control(
			'product_cart_subtotal_style',
			[
				'label' 	=> esc_html__('Subtotal', 'ultimate-store-kit'),
				'type' 		=> Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'product_cart_subtotal_color',
			[
				'label'     => esc_html__('Subtotal Color', 'ultimate-store-kit'),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'#usk-mini-cart-{{ID}}.usk-mini-cart .bdt-mini-cart-subtotal strong' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'product_cart_subtotal_tax_color',
			[
				'label'     => esc_html__('Tax Color', 'ultimate-store-kit'),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'#usk-mini-cart-{{ID}}.usk-mini-cart .bdt-mini-cart-subtotal .woocommerce-Price-amount.amount' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'product_cart_subtotal_typography',
				'selector' => '#usk-mini-cart-{{ID}}.usk-mini-cart .bdt-mini-cart-subtotal',
			]
		);

		$this->add_control(
			'product_cart_viewcart_button_style',
			[
				'label' 	=> esc_html__('View Cart Button', 'ultimate-store-kit'),
				'type' 		=> Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'pc_viewcart_text_color',
			[
				'label'     => esc_html__('Color', 'ultimate-store-kit'),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'#usk-mini-cart-{{ID}}.usk-mini-cart .bdt-mini-cart-footer-buttons a.bdt-button-view-cart ' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'pc_viewcart_button_hover_color',
			[
				'label'     => esc_html__('Hover Color', 'ultimate-store-kit'),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'#usk-mini-cart-{{ID}}.usk-mini-cart .bdt-mini-cart-footer-buttons a.bdt-button-view-cart :hover' => 'color: {{VALUE}};',
				],
			]
		);
		$this->add_control(
			'heading_viewcart_background_color',
			[
				'label'     => __('Background', 'ultimate-store-kit'),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'      => 'pc_viewcart_background_color',
				'label'     => __('Background', 'ultimate-store-kit'),
				'types'     => ['classic', 'gradient'],
				'selector'  => '#usk-mini-cart-{{ID}}.usk-mini-cart .bdt-mini-cart-footer-buttons .bdt-button-view-cart'
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'      => 'pc_viewcart_background_hover_color',
				'label'     => __('Background', 'ultimate-store-kit'),
				'types'     => ['classic', 'gradient'],
				'selector'  => '#usk-mini-cart-{{ID}}.usk-mini-cart .bdt-mini-cart-footer-buttons .bdt-button-view-cart:hover'
			]
		);

		// $this->add_control(
		// 	'pc_viewcart_background_hover_color',
		// 	[
		// 		'label'     => esc_html__('Hover Background Color', 'ultimate-store-kit'),
		// 		'type'      => Controls_Manager::COLOR,
		// 		'selectors' => [
		// 			'#usk-mini-cart-{{ID}}.usk-mini-cart .ts-content-container .bdt-mini-cart-footer-buttons .bdt-button-view-cart:hover' => 'background-color: {{VALUE}};',
		// 		],
		// 	]
		// );

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'        => 'pc_viewcart_border',
				'label'       => esc_html__('Border', 'ultimate-store-kit'),
				'selector'    => '#usk-mini-cart-{{ID}}.usk-mini-cart .ts-content-container .bdt-mini-cart-footer-buttons .bdt-button-view-cart',
			]
		);

		$this->add_control(
			'pc_viewcart_hover_border_color',
			[
				'label'     => esc_html__('Hover Border Color', 'ultimate-store-kit'),
				'type'      => Controls_Manager::COLOR,
				'condition' => [
					'pc_viewcart_border_border!' => '',
				],
				'selectors' => [
					'#usk-mini-cart-{{ID}}.usk-mini-cart .ts-content-container .bdt-mini-cart-footer-buttons .bdt-button-view-cart:hover' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'pc_viewcart_border_radius',
			[
				'label'      => esc_html__('Border Radius', 'ultimate-store-kit'),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%'],
				'selectors'  => [
					'#usk-mini-cart-{{ID}}.usk-mini-cart .ts-content-container .bdt-mini-cart-footer-buttons .bdt-button-view-cart' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'pc_viewcart_padding',
			[
				'label'      => esc_html__('Padding', 'ultimate-store-kit'),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', 'em', '%'],
				'selectors'  => [
					'#usk-mini-cart-{{ID}}.usk-mini-cart .ts-content-container .bdt-mini-cart-footer-buttons .bdt-button-view-cart' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'pc_viewcart_shadow',
				'selector' => '#usk-mini-cart-{{ID}}.usk-mini-cart .ts-content-container .bdt-mini-cart-footer-buttons .bdt-button-view-cart',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'      => 'pc_viewcart_typography',
				'label'     => esc_html__('Typography', 'ultimate-store-kit'),
				'selector'  => '#usk-mini-cart-{{ID}}.usk-mini-cart .ts-content-container .bdt-mini-cart-footer-buttons .bdt-button-view-cart .bdt-button-text',
			]
		);

		$this->add_control(
			'product_cart_checkout_button_style',
			[
				'label' 	=> esc_html__('Checkout Button', 'ultimate-store-kit'),
				'type' 		=> Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'pc_checkout_text_color',
			[
				'label'     => esc_html__('Color', 'ultimate-store-kit'),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'#usk-mini-cart-{{ID}}.usk-mini-cart .bdt-mini-cart-footer-buttons .bdt-button-checkout' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'pc_checkout_button_hover_color',
			[
				'label'     => esc_html__('Hover Color', 'ultimate-store-kit'),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'#usk-mini-cart-{{ID}}.usk-mini-cart .bdt-mini-cart-footer-buttons .bdt-button-checkout:hover' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'      => 'pc_checkout_background_color',
				'label'     => __('Background', 'ultimate-store-kit'),
				'types'     => ['classic', 'gradient'],
				'selector'  => '#usk-mini-cart-{{ID}}.usk-mini-cart .bdt-mini-cart-footer-buttons .bdt-button-checkout',
			]
		);

		$this->add_control(
			'pc_checkout_background_hover_color',
			[
				'label'     => esc_html__('Hover Background Color', 'ultimate-store-kit'),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'#usk-mini-cart-{{ID}}.usk-mini-cart .ts-content-container .bdt-mini-cart-footer-buttons .bdt-button-checkout:hover::before' => 'background-color: {{VALUE}};',
					// '.usk-mini-cart .bdt-mini-cart-footer-buttons .bdt-button-checkout::before'
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'        => 'pc_checkout_border',
				'label'       => esc_html__('Border', 'ultimate-store-kit'),
				'selector'    => '#usk-mini-cart-{{ID}}.usk-mini-cart .ts-content-container .bdt-mini-cart-footer-buttons .bdt-button-checkout',
			]
		);

		$this->add_control(
			'pc_checkout_hover_border_color',
			[
				'label'     => esc_html__('Hover Border Color', 'ultimate-store-kit'),
				'type'      => Controls_Manager::COLOR,
				'condition' => [
					'pc_checkout_border_border!' => '',
				],
				'selectors' => [
					'#usk-mini-cart-{{ID}}.usk-mini-cart .ts-content-container .bdt-mini-cart-footer-buttons .bdt-button-checkout:hover' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'pc_checkout_border_radius',
			[
				'label'      => esc_html__('Border Radius', 'ultimate-store-kit'),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%'],
				'selectors'  => [
					'#usk-mini-cart-{{ID}}.usk-mini-cart .ts-content-container .bdt-mini-cart-footer-buttons .bdt-button-checkout' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'pc_checkout_padding',
			[
				'label'      => esc_html__('Padding', 'ultimate-store-kit'),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', 'em', '%'],
				'selectors'  => [
					'#usk-mini-cart-{{ID}}.usk-mini-cart .ts-content-container .bdt-mini-cart-footer-buttons .bdt-button-checkout' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'pc_checkout_shadow',
				'selector' => '#usk-mini-cart-{{ID}}.usk-mini-cart .ts-content-container .bdt-mini-cart-footer-buttons .bdt-button-checkout',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'      => 'pc_checkout_typography',
				'label'     => esc_html__('Typography', 'ultimate-store-kit'),
				'selector'  => '#usk-mini-cart-{{ID}}.usk-mini-cart .ts-content-container .bdt-mini-cart-footer-buttons .bdt-button-checkout .bdt-button-text',
			]
		);

		$this->add_control(
			'product_cart_remove_button_style',
			[
				'label' 	=> esc_html__('Product Remove Button', 'ultimate-store-kit'),
				'type' 		=> Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'pc_remove_text_color',
			[
				'label'     => esc_html__('Color', 'ultimate-store-kit'),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'#usk-mini-cart-{{ID}}.usk-mini-cart .ts-content-container .bdt-mini-cart-product-item .bdt-mini-cart-product-remove a svg *' => 'stroke: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'pc_remove_button_hover_color',
			[
				'label'     => esc_html__('Hover Color', 'ultimate-store-kit'),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'#usk-mini-cart-{{ID}}.usk-mini-cart .ts-content-container .bdt-mini-cart-product-item .bdt-mini-cart-product-remove a:hover svg *' => 'stroke: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'pc_remove_background_color',
			[
				'label'     => esc_html__('Background Color', 'ultimate-store-kit'),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'#usk-mini-cart-{{ID}}.usk-mini-cart .ts-content-container .bdt-mini-cart-product-item .bdt-mini-cart-product-remove a' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'pc_remove_background_hover_color',
			[
				'label'     => esc_html__('Hover Background Color', 'ultimate-store-kit'),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'#usk-mini-cart-{{ID}}.usk-mini-cart .ts-content-container .bdt-mini-cart-product-item .bdt-mini-cart-product-remove a:hover' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'        => 'pc_remove_border',
				'label'       => esc_html__('Border', 'ultimate-store-kit'),
				'selector'    => '#usk-mini-cart-{{ID}}.usk-mini-cart .ts-content-container .bdt-mini-cart-product-item .bdt-mini-cart-product-remove a',
			]
		);

		$this->add_control(
			'pc_remove_hover_border_color',
			[
				'label'     => esc_html__('Hover Border Color', 'ultimate-store-kit'),
				'type'      => Controls_Manager::COLOR,
				'condition' => [
					'pc_remove_border_border!' => '',
				],
				'selectors' => [
					'#usk-mini-cart-{{ID}}.usk-mini-cart .ts-content-container .bdt-mini-cart-product-item .bdt-mini-cart-product-remove a:hover' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'pc_remove_border_radius',
			[
				'label'      => esc_html__('Border Radius', 'ultimate-store-kit'),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%'],
				'selectors'  => [
					'#usk-mini-cart-{{ID}}.usk-mini-cart .ts-content-container .bdt-mini-cart-product-item .bdt-mini-cart-product-remove a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'pc_remove_padding',
			[
				'label'      => esc_html__('Padding', 'ultimate-store-kit'),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', 'em', '%'],
				'selectors'  => [
					'#usk-mini-cart-{{ID}}.usk-mini-cart .ts-content-container .bdt-mini-cart-product-item .bdt-mini-cart-product-remove a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'pc_remove_shadow',
				'selector' => '#usk-mini-cart-{{ID}}.usk-mini-cart .ts-content-container .bdt-mini-cart-product-item .bdt-mini-cart-product-remove a',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_style_offcanvas_content',
			[
				'label' => esc_html__('Offcanvas', 'ultimate-store-kit'),
			]
		);
		$this->add_control(
			'heading_offcanvas_content_background',
			[
				'label'     => __('Content Background', 'ultimate-store-kit'),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'      => 'offcanvas_content_background_color',
				'label'     => __('Background', 'ultimate-store-kit'),
				'types'     => ['classic', 'gradient'],
				'selector'  => '#usk-mini-cart-{{ID}}.usk-mini-cart .ts-content-container',
			]
		);
		$this->add_control(
			'heading_offcanvas_item_background',
			[
				'label'     => __('Item Background', 'ultimate-store-kit'),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'      => 'offcanvas_item_background_color',
				'label'     => __('Background', 'ultimate-store-kit'),
				'types'     => ['classic', 'gradient'],
				'selector'  => '#usk-mini-cart-{{ID}}.usk-mini-cart .ts-content-container .bdt-mini-cart-product-item',
			]
		);

		$this->add_control(
			'heading_offcanvas_item_hover_background',
			[
				'label'     => __('Item Hover Background', 'ultimate-store-kit'),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'      => 'offcanvas_item_hover_background_color',
				'label'     => __('Background', 'ultimate-store-kit'),
				'types'     => ['classic', 'gradient'],
				'selector'  => '#usk-mini-cart-{{ID}}.usk-mini-cart .ts-content-container .bdt-mini-cart-product-item:hover',
			]
		);
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'      => 'offcanvas_content_shadow',
				'selector'  => '#usk-mini-cart-{{ID}}.usk-mini-cart .ts-content-container',
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'offcanvas_content_padding',
			[
				'label'      => esc_html__('Padding', 'ultimate-store-kit'),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', 'em', '%'],
				'selectors'  => [
					'#usk-mini-cart-{{ID}}.usk-mini-cart .ts-content-container' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'offcanvas_margin',
			[
				'label'      => esc_html__('Margin', 'ultimate-store-kit'),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', 'em', '%'],
				'selectors'  => [
					'#usk-mini-cart-{{ID}}.usk-mini-cart .ts-content-container' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'after'
			]
		);
		$this->add_control(
			'offcanvas_heading_footer',
			[
				'label'     => __('Footer', 'ultimate-store-kit'),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'      => 'footer_backgrouind',
				'label'     => __('Background', 'ultimate-store-kit'),
				'types'     => ['classic', 'gradient'],
				'selector'  => '#usk-mini-cart-{{ID}}.usk-mini-cart .usk-mini-cart-content-footer',
			]
		);
		$this->end_controls_tab();
		$this->end_controls_tabs();

		$this->end_controls_section();
	}
	protected function register_controls() {
		$this->render_layout_controls_offcanvas();
		$this->render_controls_mini_cart();
		$this->render_controls_settings();

		//Style
		$this->render_style_controls_mini_cart();
		$this->render_style_controls_offcanvas();
	}

	public function render() {

		$settings 		= $this->get_settings_for_display();
		$id       		= 'usk-mini-cart-' . $this->get_id();
		$toggleID       = 'usk-mini-cart-toggle-' . $this->get_id();
		if (null === WC()->cart) {
			return;
		}
		$product_count = WC()->cart->get_cart_contents_count();
		// $this->add_render_attribute('mini-cart', 'type', 'button');
		$this->add_render_attribute('mini-cart', 'id', $toggleID);
		$this->add_render_attribute('mini-cart', [
			'class' => ['usk-mini-cart-toggle-btn'],
			'data-settings' => [
				wp_json_encode(array_filter([
					'toggleButton' => '#' . $toggleID . '',
					// 'position' => $settings['cart_position'],
					// 'height' => "100%",
					// 'width' => "340px",
					// 'startOpen' => true,
					// 'closeable' => true,
					// 'autoclose' => false,
					// 'autocloseDelay' => 5000,
					// 'clickOutsideToClose' => true,
				]))
			]
		], null, true);
		$this->add_render_attribute('offcanvas', [
			'id' => $id,
			'class' => ['usk-mini-cart'],
		], null, true);
?>
		<div class="ultimate-store-kit">
			<div <?php $this->print_render_attribute_string('mini-cart'); ?>>
				<span class="bdt-mini-cart-inner">
					<?php if ($settings['show_price_amount'] === 'yes') : ?>
						<span class="bdt-cart-button-text">
							<span class="bdt-mini-cart-price-amount">
								<?php echo WC()->cart->get_cart_subtotal(); ?>
							</span>
						</span>
					<?php endif; ?>

					<?php if ($settings['show_cart_icon'] === 'yes') : ?>
						<span class="bdt-mini-cart-button-icon">
							<?php //if (($product_count != 0)) :
							?>
							<span class="usk-cart-badge"><?php echo esc_html($product_count); ?></span>
							<?php //endif;
							?>
							<span class="usk-cart-icon">
								<i class="eicon-cart" aria-hidden="true"></i>
							</span>
						</span>
					<?php
					endif; ?>
				</span>
			</div>

			<!-- OFFCANVAS -->
			<div <?php $this->print_render_attribute_string('offcanvas'); ?>>
				<div class="ts-container">
					<div class="ts-nav-container"></div>
					<div class="ts-content-container">
						<div id="first" class="ts-content-item">
							<div class="usk-widget-title">
								<?php echo wp_kses_post($settings['custom_widget_cart_title']); ?>
							</div>
							<div class="widget_shopping_cart_content"></div>
						</div>
					</div>
				</div>
			</div>
		</div>
<?php
	}
}
