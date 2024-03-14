<?php
/**
 * WooCommerce checkout widget class
 *
 * @package Skt_Addons_Elementor
 */
namespace Skt_Addons_Elementor\Elementor\Widget;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;

defined( 'ABSPATH' ) || die();

class WC_Checkout extends Base {

	/**
	 * Retrieve toggle widget title.
	 *
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'WC Checkout', 'skt-addons-elementor' );
	}

	/**
	 * Get widget icon.
	 *
	 * @since 1.0
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'skti skti-checkout-2';
	}

	public function get_keywords() {
		return [ 'woo', 'commerce', 'ecommerce', 'cart', 'checkout', 'shop' ];
	}

	public function is_reload_preview_required() {
		return true;
	}

	/**
     * Register widget content controls
     */
	protected function register_content_controls() {

		$this->start_controls_section(
			'_section_general',
			[
				'label' => __( 'General', 'skt-addons-elementor' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'layout',
			[
				'label'   => __( 'Layout', 'skt-addons-elementor' ),
				'type'    => Controls_Manager::CHOOSE,
				'default' => '1',
				'options' => [
					'1'   => [
						'title' => __( 'One Column', 'skt-addons-elementor' ),
						'icon'  => 'eicon-section',
					],
					'2' => [
						'title' => __( 'Two Columns', 'skt-addons-elementor' ),
						'icon'  => 'eicon-column',
					],
				],
				'prefix_class' => 'skt-wc-checkout--col-',
			]
		);

		$this->add_control(
			'columns_stack',
			[
				'label'   => __( 'Stack On', 'skt-addons-elementor' ),
				'type'    => Controls_Manager::CHOOSE,
				'default' => 'tablet',
				'options' => [
					'tablet'   => [
						'title' => __( 'Stack on tablet', 'skt-addons-elementor' ),
						'icon'  => 'eicon-device-tablet',
					],
					'mobile' => [
						'title' => __( 'Stack on mobile', 'skt-addons-elementor' ),
						'icon'  => 'eicon-device-mobile',
					],
				],
				'prefix_class' => 'skt-wc-checkout--stack-',
				'condition' => [
					'layout' => '2',
				],
			]
		);

		$this->add_responsive_control(
			'column_gap',
			[
				'label'      => __( 'Columns Gap', 'skt-addons-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'devices'    => [ 'desktop', 'tablet' ],
				'default'    => [
					'size' => 35,
				],
				'selectors' => [
					'{{WRAPPER}}.skt-wc-checkout--col-2 .woocommerce .col2-set' => 'margin-right: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'layout' => '2',
				],
			]
		);

		$this->add_responsive_control(
			'first_column_width',
			[
				'label'      => __( 'First Column Width', 'skt-addons-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ '%' ],
				'devices'    => [ 'desktop', 'tablet' ],
				'default'    => [
					'size' => 50,
				],
				'selectors' => [
					'{{WRAPPER}}.skt-wc-checkout--col-2 .woocommerce .col2-set' => 'width: calc({{SIZE}}% - ({{column_gap.size}}px / 2));',
					'{{WRAPPER}}.skt-wc-checkout--col-2 #order_review_heading, {{WRAPPER}}.skt-wc-checkout--col-2 #order_review' => 'width: calc((100% - {{SIZE}}%) - ({{column_gap.size}}px / 2));',
				],
				'condition' => [
					'layout' => '2',
				],
			]
		);

		$this->end_controls_section();
	}

	/**
     * Register widget style controls
     */
	protected function register_style_controls() {
		$this->__sections_style_controls();
		$this->__inputs_style_controls();
		$this->__coupon_bar_style_controls();
		$this->__coupon_apply_box_style_controls();
		$this->__headings_style_controls();
		$this->__billing_details_style_controls();
	}

	protected function __sections_style_controls() {
		$this->start_controls_section(
			'_section_style_sections',
			[
				'label' => __( 'Sections', 'skt-addons-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'sections_padding',
			[
				'label'      => __( 'Padding', 'skt-addons-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .woocommerce .woocommerce-billing-fields__field-wrapper, {{WRAPPER}} .woocommerce .woocommerce-additional-fields__field-wrapper, {{WRAPPER}} .woocommerce .woocommerce-checkout-review-order-table, {{WRAPPER}} .woocommerce .woocommerce-checkout-payment' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'sections_gap',
			[
				'label'   => __( 'Spacing', 'skt-addons-elementor' ),
				'type'    => Controls_Manager::SLIDER,
				'default' => [
					'size' => 35,
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}}.skt-wc-checkout--col-2 .woocommerce .col2-set .col-1,
					{{WRAPPER}}.skt-wc-checkout--col-2 .woocommerce-checkout-review-order-table' => 'margin-bottom: {{SIZE}}{{UNIT}};',

					'(desktop){{WRAPPER}}.skt-wc-checkout--col-2.skt-wc-checkout--stack-tablet .woocommerce .col2-set .col-2 .woocommerce-additional-fields' => 'margin-bottom: 0;',
					'(tablet){{WRAPPER}}.skt-wc-checkout--col-2.skt-wc-checkout--stack-tablet .woocommerce .col2-set .col-2 .woocommerce-additional-fields' => 'margin-bottom: {{sections_gap_tablet.SIZE}}{{sections_gap_tablet.UNIT}};',
					'(mobile){{WRAPPER}}.skt-wc-checkout--col-2.skt-wc-checkout--stack-tablet .woocommerce .col2-set .col-2 .woocommerce-additional-fields' => 'margin-bottom: {{sections_gap_mobile.SIZE}}{{sections_gap_mobile.UNIT}};',


					'(mobile){{WRAPPER}}.skt-wc-checkout--col-2.skt-wc-checkout--stack-mobile .woocommerce .col2-set .col-2 .woocommerce-additional-fields' => 'margin-bottom: {{sections_gap_mobile.SIZE}}{{sections_gap_mobile.UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'     => 'sections_bg',
				'types'    => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .woocommerce .woocommerce-billing-fields__field-wrapper, {{WRAPPER}} .woocommerce .woocommerce-shipping-fields__field-wrapper, {{WRAPPER}} .woocommerce .woocommerce-additional-fields__field-wrapper, {{WRAPPER}} .woocommerce .woocommerce-checkout-review-order-table, {{WRAPPER}} .woocommerce .woocommerce-checkout-payment',
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'        => 'sections_border',
				'selector'    => '{{WRAPPER}} .woocommerce .woocommerce-billing-fields__field-wrapper, {{WRAPPER}} .woocommerce .woocommerce-shipping-fields__field-wrapper, {{WRAPPER}} .woocommerce .woocommerce-additional-fields__field-wrapper, {{WRAPPER}} .woocommerce .woocommerce-checkout-review-order-table, {{WRAPPER}} .woocommerce .woocommerce-checkout-payment',
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'      => 'sections_box_shadow',
				'selector'  => '{{WRAPPER}} .woocommerce .woocommerce-billing-fields__field-wrapper, {{WRAPPER}} .woocommerce .woocommerce-shipping-fields__field-wrapper, {{WRAPPER}} .woocommerce .woocommerce-additional-fields__field-wrapper, {{WRAPPER}} .woocommerce .woocommerce-checkout-review-order-table, {{WRAPPER}} .woocommerce .woocommerce-checkout-payment',
			]
		);

		$this->end_controls_section();
	}

	protected function __inputs_style_controls() {
		$this->start_controls_section(
			'_section_style_inputs',
			[
				'label' => __( 'Inputs', 'skt-addons-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'inputs_padding',
			[
				'label'      => __( 'Padding', 'skt-addons-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .woocommerce form .input-text' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'inputs_height',
			[
				'label'   => __( 'Input Height', 'skt-addons-elementor' ),
				'type'    => Controls_Manager::SLIDER,
				'default' => [
					'size' => '',
				],
				'selectors' => [
					'{{WRAPPER}} .woocommerce .form-row input.input-text, {{WRAPPER}} .woocommerce .form-row select' => 'height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'textarea_height',
			[
				'label'   => __( 'Textarea Height', 'skt-addons-elementor' ),
				'type'    => Controls_Manager::SLIDER,
				'selectors' => [
					'{{WRAPPER}} .woocommerce form .form-row textarea' => 'height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'inputs_gap',
			[
				'label'   => __( 'Spacing', 'skt-addons-elementor' ),
				'type'    => Controls_Manager::SLIDER,
				'selectors' => [
					'{{WRAPPER}} .woocommerce form .input-text, {{WRAPPER}} .woocommerce form select' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'        => 'inputs_border',
				'selector'    => '{{WRAPPER}} .woocommerce form .input-text, {{WRAPPER}} .woocommerce form select',
			]
		);

		$this->add_control(
			'inputs_text_align',
			[
				'label'       => __( 'Text Alignment', 'skt-addons-elementor' ),
				'type'        => Controls_Manager::CHOOSE,
				'label_block' => false,
				'options'     => [
					'left'   => [
						'title' => __( 'Left', 'skt-addons-elementor' ),
						'icon'  => 'eicon-text-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'skt-addons-elementor' ),
						'icon'  => 'eicon-text-align-center',
					],
					'right'  => [
						'title' => __( 'Right', 'skt-addons-elementor' ),
						'icon'  => 'eicon-text-align-right',
					],
				],
				'default'   => 'left',
				'selectors' => [
					'{{WRAPPER}} .woocommerce form .input-text, {{WRAPPER}} .woocommerce form select' => 'text-align: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'input_text_color',
			[
				'label'     => __( 'Color', 'skt-addons-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .woocommerce form .input-text, {{WRAPPER}} .woocommerce form select' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'input_background_color',
			[
				'label'     => __( 'Background Color', 'skt-addons-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .woocommerce form .input-text, {{WRAPPER}} .woocommerce form select' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'inputs_typography',
				'selector' => '{{WRAPPER}} .woocommerce form .input-text, {{WRAPPER}} .woocommerce form select',
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'      => 'inputs_box_shadow',
				'selector'  => '{{WRAPPER}} .woocommerce form .input-text, {{WRAPPER}} .woocommerce form select',
			]
		);

		$this->end_controls_section();
	}

	protected function __coupon_bar_style_controls() {
		$this->start_controls_section(
			'_section_style_coupon_bar',
			[
				'label' => __( 'Coupon Bar', 'skt-addons-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'					=> 'coupon_bar_typography',
				'label'					=> __( 'Typography', 'skt-addons-elementor' ),
				'selector'				=> '{{WRAPPER}} .woocommerce .woocommerce-form-coupon-toggle .woocommerce-info',
			]
		);

		$this->add_control(
			'coupon_bar_text_color',
			[
				'label'     => __( 'Text Color', 'skt-addons-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .woocommerce .woocommerce-form-coupon-toggle .woocommerce-info' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'coupon_bar_icon_color',
			[
				'label'     => __( 'Icon Color', 'skt-addons-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .woocommerce .woocommerce-form-coupon-toggle .woocommerce-info:before' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'coupon_bar_links_color',
			[
				'label'     => __( 'Links Color', 'skt-addons-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .woocommerce .woocommerce-form-coupon-toggle .woocommerce-info a' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'coupon_bar_links_color_hover',
			[
				'label'     => __( 'Links Hover Color', 'skt-addons-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .woocommerce .woocommerce-form-coupon-toggle .woocommerce-info a:hover' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'      => 'coupon_bar_background',
				'types'     => [ 'classic', 'gradient' ],
				'separator' => 'before',
				'selector'  => '{{WRAPPER}} .woocommerce .woocommerce-form-coupon-toggle .woocommerce-info',
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'        => 'coupon_bar_border',
				'label'       => __( 'Border', 'skt-addons-elementor' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} .woocommerce .woocommerce-form-coupon-toggle .woocommerce-info',
			]
		);

		$this->add_control(
			'coupon_bar_border_radius',
			[
				'label'      => __( 'Border Radius', 'skt-addons-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .woocommerce .woocommerce-form-coupon-toggle .woocommerce-info' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'      => 'coupon_bar_box_shadow',
				'selector'  => '{{WRAPPER}} .woocommerce .woocommerce-form-coupon-toggle .woocommerce-info',
			]
		);

		$this->end_controls_section();
	}

	protected function __coupon_apply_box_style_controls() {

		$this->start_controls_section(
			'form_coupon_style',
			[
				'label'                 => __( 'Coupon Box', 'skt-addons-elementor' ),
				'tab'                   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'form_coupon_padding',
			[
				'label'      => __( 'Padding', 'skt-addons-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .woocommerce form.checkout_coupon' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'form_coupon_text_color',
			[
				'label'     => __( 'Text Color', 'skt-addons-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .woocommerce form.checkout_coupon' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'      => 'form_coupon_background',
				'types'     => [ 'classic', 'gradient' ],
				'selector'  => '{{WRAPPER}} .woocommerce form.checkout_coupon',
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'        => 'form_coupon_border',
				'label'       => __( 'Border', 'skt-addons-elementor' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} .woocommerce form.checkout_coupon',
			]
		);

		$this->add_control(
			'form_coupon_border_radius',
			[
				'label'      => __( 'Border Radius', 'skt-addons-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .woocommerce form.checkout_coupon' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'					=> 'form_coupon_typography',
				'label'					=> __( 'Typography', 'skt-addons-elementor' ),
				'selector'				=> '{{WRAPPER}} .woocommerce form.checkout_coupon, {{WRAPPER}} .woocommerce form.checkout_coupon .input-text',
				// 'selector'				=> '{{WRAPPER}} .woocommerce form.checkout_coupon, {{WRAPPER}} .woocommerce form.checkout_coupon .input-text, {{WRAPPER}} .woocommerce form.checkout_coupon .button',
			]
		);

		$this->add_control(
			'form_coupon_input_heading',
			[
				'label'                 => __( 'Input', 'skt-addons-elementor' ),
				'type'                  => Controls_Manager::HEADING,
				'separator'				=> 'before',
			]
		);

		$this->add_responsive_control(
			'form_coupon_input_width',
			[
				'label'                 => __( 'Input Width', 'skt-addons-elementor' ),
				'type'                  => Controls_Manager::SLIDER,
				'default'               => [
					'size' => '',
				],
				'range'					=> [
					'px' => [
						'min' => 0,
						'max' => 500,
					],
				],
				'selectors'             => [
					'{{WRAPPER}} .woocommerce form.checkout_coupon .input-text' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'form_coupon_input_height',
			[
				'label'                 => __( 'Input Height', 'skt-addons-elementor' ),
				'type'                  => Controls_Manager::SLIDER,
				'default'               => [
					'size' => '',
				],
				'range'					=> [
					'px' => [
						'min' => 0,
						'max' => 500,
					],
				],
				'selectors'             => [
					'{{WRAPPER}} .woocommerce form.checkout_coupon .input-text' => 'height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'form_coupon_input_padding',
			[
				'label'                 => __( 'Padding', 'skt-addons-elementor' ),
				'type'                  => Controls_Manager::DIMENSIONS,
				'size_units'            => [ 'px', 'em', '%' ],
				'selectors'             => [
					'{{WRAPPER}} .woocommerce form.checkout_coupon .input-text' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'                  => 'form_coupon_input_border',
				'label'                 => __( 'Border', 'skt-addons-elementor' ),
				'selector'              => '{{WRAPPER}} .woocommerce form.checkout_coupon .input-text',
			]
		);

		$this->add_control(
			'form_coupon_input_border_radius',
			[
				'label'                 => __( 'Border Radius', 'skt-addons-elementor' ),
				'type'                  => Controls_Manager::DIMENSIONS,
				'size_units'            => [ 'px', '%' ],
				'selectors'             => [
					'{{WRAPPER}} .woocommerce form.checkout_coupon .input-text' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'					=> 'form_coupon_input_box_shadow',
				'separator'				=> 'before',
				'selector'				=> '{{WRAPPER}} .woocommerce form.checkout_coupon .input-text',
			]
		);

		$this->start_controls_tabs( 'tabs_form_coupon_input_style' );

		$this->start_controls_tab(
			'tab_form_coupon_input_normal',
			[
				'label'                 => __( 'Normal', 'skt-addons-elementor' ),
			]
		);

		$this->add_control(
			'form_coupon_input_text_color',
			[
				'label'                 => __( 'Text Color', 'skt-addons-elementor' ),
				'type'                  => Controls_Manager::COLOR,
				'selectors'             => [
					'{{WRAPPER}} .woocommerce form.checkout_coupon .input-text' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'form_coupon_input_background_color',
			[
				'label'                 => __( 'Background Color', 'skt-addons-elementor' ),
				'type'                  => Controls_Manager::COLOR,
				'selectors'             => [
					'{{WRAPPER}} .woocommerce form.checkout_coupon .input-text' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_form_coupon_input_hover',
			[
				'label'                 => __( 'Hover', 'skt-addons-elementor' ),
			]
		);

		$this->add_control(
			'form_coupon_input_text_color_hover',
			[
				'label'                 => __( 'Text Color', 'skt-addons-elementor' ),
				'type'                  => Controls_Manager::COLOR,
				'selectors'             => [
					'{{WRAPPER}} .woocommerce form.checkout_coupon .input-text:hover' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'form_coupon_input_background_color_hover',
			[
				'label'                 => __( 'Background Color', 'skt-addons-elementor' ),
				'type'                  => Controls_Manager::COLOR,
				'selectors'             => [
					'{{WRAPPER}} .woocommerce form.checkout_coupon .input-text:hover' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'form_coupon_input_border_color_hover',
			[
				'label'                 => __( 'Border Color', 'skt-addons-elementor' ),
				'type'                  => Controls_Manager::COLOR,
				'selectors'             => [
					'{{WRAPPER}} .woocommerce form.checkout_coupon .input-text:hover' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_form_coupon_input_focus',
			[
				'label'                 => __( 'Focus', 'skt-addons-elementor' ),
			]
		);

		$this->add_control(
			'form_coupon_input_text_color_focus',
			[
				'label'                 => __( 'Text Color', 'skt-addons-elementor' ),
				'type'                  => Controls_Manager::COLOR,
				'selectors'             => [
					'{{WRAPPER}} .woocommerce form.checkout_coupon .input-text:focus' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'form_coupon_input_background_color_focus',
			[
				'label'                 => __( 'Background Color', 'skt-addons-elementor' ),
				'type'                  => Controls_Manager::COLOR,
				'selectors'             => [
					'{{WRAPPER}} .woocommerce form.checkout_coupon .input-text:focus' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'form_coupon_input_border_color_focus',
			[
				'label'                 => __( 'Border Color', 'skt-addons-elementor' ),
				'type'                  => Controls_Manager::COLOR,
				'selectors'             => [
					'{{WRAPPER}} .woocommerce form.checkout_coupon .input-text:focus' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'form_coupon_button_label_heading',
			[
				'label'                 => __( 'Coupon Button', 'skt-addons-elementor' ),
				'type'                  => Controls_Manager::HEADING,
				'separator'				=> 'before',
			]
		);

		$this->add_responsive_control(
			'form_coupon_button_width',
			[
				'label'                 => __( 'Width', 'skt-addons-elementor' ),
				'type'                  => Controls_Manager::SLIDER,
				'size_units'            => [ 'px' ],
				'default'               => [
					'size' => '',
				],
				'range'                => [
					'px' => [
						'min' => 50,
						'max' => 500,
					],
				],
				'selectors'             => [
					'{{WRAPPER}} .woocommerce form.checkout_coupon .button' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'                  => 'form_coupon_button_border_normal',
				'label'                 => __( 'Border', 'skt-addons-elementor' ),
				'selector'              => '{{WRAPPER}} .woocommerce form.checkout_coupon .button',
			]
		);

		$this->add_control(
			'form_coupon_button_border_radius',
			[
				'label'                 => __( 'Border Radius', 'skt-addons-elementor' ),
				'type'                  => Controls_Manager::DIMENSIONS,
				'size_units'            => [ 'px', '%' ],
				'selectors'             => [
					'{{WRAPPER}} .woocommerce form.checkout_coupon .button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'form_coupon_button_padding',
			[
				'label'                 => __( 'Padding', 'skt-addons-elementor' ),
				'type'                  => Controls_Manager::DIMENSIONS,
				'size_units'            => [ 'px', 'em', '%' ],
				'selectors'             => [
					'{{WRAPPER}} .woocommerce form.checkout_coupon .button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

        $this->start_controls_tabs( 'tabs_form_coupon_button_style' );

        $this->start_controls_tab(
            'tab_form_coupon_button_normal',
            [
                'label'                 => __( 'Normal', 'skt-addons-elementor' ),
            ]
        );

        $this->add_control(
            'form_coupon_button_bg_color_normal',
            [
                'label'                 => __( 'Background Color', 'skt-addons-elementor' ),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '',
                'selectors'             => [
                    '{{WRAPPER}} .woocommerce form.checkout_coupon .button' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'form_coupon_button_text_color_normal',
            [
                'label'                 => __( 'Text Color', 'skt-addons-elementor' ),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '',
                'selectors'             => [
                    '{{WRAPPER}} .woocommerce form.checkout_coupon .button' => 'color: {{VALUE}}',
                ],
            ]
        );

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'                  => 'form_coupon_button_box_shadow',
				'selector'              => '{{WRAPPER}} .woocommerce form.checkout_coupon .button',
			]
		);

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_form_coupon_button_hover',
            [
                'label'                 => __( 'Hover', 'skt-addons-elementor' ),
            ]
        );

        $this->add_control(
            'form_coupon_button_bg_color_hover',
            [
                'label'                 => __( 'Background Color', 'skt-addons-elementor' ),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '',
                'selectors'             => [
                    '{{WRAPPER}} .woocommerce form.checkout_coupon .button:hover' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'form_coupon_button_text_color_hover',
            [
                'label'                 => __( 'Text Color', 'skt-addons-elementor' ),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '',
                'selectors'             => [
                    '{{WRAPPER}} .woocommerce form.checkout_coupon .button:hover' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'form_coupon_button_border_color_hover',
            [
                'label'                 => __( 'Border Color', 'skt-addons-elementor' ),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '',
                'selectors'             => [
                    '{{WRAPPER}} .woocommerce form.checkout_coupon .button:hover' => 'border-color: {{VALUE}}',
                ],
            ]
        );

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'                  => 'form_coupon_button_box_shadow_hover',
				'selector'              => '{{WRAPPER}} .woocommerce form.checkout_coupon .button:hover',
			]
		);

        $this->end_controls_tab();
        $this->end_controls_tabs();

		$this->end_controls_section();
	}

	protected function __headings_style_controls() {
		$this->start_controls_section(
			'_section_style_headings',
			[
				'label' => __( 'Headings', 'skt-addons-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'headings_text_color',
			[
				'label'     => __( 'Color', 'skt-addons-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .woocommerce .woocommerce-billing-fields > h3, {{WRAPPER}} .woocommerce .woocommerce-shipping-fields > h3, {{WRAPPER}} .woocommerce .woocommerce-additional-fields > h3, {{WRAPPER}} .woocommerce #order_review_heading' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'headings_typography',
				'selector' => '{{WRAPPER}} .woocommerce .woocommerce-billing-fields > h3, {{WRAPPER}} .woocommerce .woocommerce-shipping-fields > h3, {{WRAPPER}} .woocommerce .woocommerce-additional-fields > h3, {{WRAPPER}} .woocommerce #order_review_heading',
			]
		);

		$this->add_responsive_control(
			'headings_spacing',
			[
				'label'   => __( 'Spacing', 'skt-addons-elementor' ),
				'type'    => Controls_Manager::SLIDER,
				'selectors'	=> [
					'{{WRAPPER}} .woocommerce .woocommerce-billing-fields > h3, {{WRAPPER}} .woocommerce .woocommerce-shipping-fields > h3, {{WRAPPER}} .woocommerce .woocommerce-additional-fields > h3, {{WRAPPER}} .woocommerce #order_review_heading' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();
	}

	protected function __billing_details_style_controls() {
		$this->start_controls_section(
			'_section_style_billing_details',
			[
				'label' => __( 'Billing Details', 'skt-addons-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'_heading_billing_details_section',
			[
				'label' => __( 'Section', 'skt-addons-elementor' ),
				'type'  => Controls_Manager::HEADING,
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'      => 'section_billing_details_background',
				'types'     => [ 'classic', 'gradient' ],
				'selector'  => '{{WRAPPER}} .woocommerce .woocommerce-billing-fields__field-wrapper, {{WRAPPER}} .woocommerce .woocommerce-shipping-fields__field-wrapper',
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'        => 'section_billing_details_border',
				'selector'    => '{{WRAPPER}} .woocommerce .woocommerce-billing-fields__field-wrapper, {{WRAPPER}} .woocommerce .woocommerce-shipping-fields__field-wrapper',
			]
		);

		$this->add_control(
			'section_billing_details_border_radius',
			[
				'label'      => __( 'Border Radius', 'skt-addons-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .woocommerce .woocommerce-billing-fields__field-wrapper, {{WRAPPER}} .woocommerce .woocommerce-shipping-fields__field-wrapper' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'      => 'section_billing_details_box_shadow',
				'selector'  => '{{WRAPPER}} .woocommerce .woocommerce-billing-fields__field-wrapper, {{WRAPPER}} .woocommerce .woocommerce-shipping-fields__field-wrapper',
			]
		);

		$this->add_control(
			'_heading_billing_details_inputs',
			[
				'label'     => __( 'Inputs', 'skt-addons-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'section_billing_details_inputs_typography',
				'selector' => '{{WRAPPER}} .woocommerce .woocommerce-billing-fields__field-wrapper input.input-text, {{WRAPPER}} .woocommerce .woocommerce-billing-fields__field-wrapper select, {{WRAPPER}} .woocommerce .woocommerce-shipping-fields__field-wrapper input.input-text, {{WRAPPER}} .woocommerce .woocommerce-shipping-fields__field-wrapper select',
			]
		);

		$this->add_responsive_control(
			'section_billing_details_inputs_height',
			[
				'label'   => __( 'Input Height', 'skt-addons-elementor' ),
				'type'    => Controls_Manager::SLIDER,
				'default' => [
					'size' => '',
				],
				'selectors' => [
					'{{WRAPPER}} .woocommerce .woocommerce-billing-fields__field-wrapper input.input-text, {{WRAPPER}} .woocommerce .woocommerce-billing-fields__field-wrapper select, {{WRAPPER}} .woocommerce .woocommerce-shipping-fields__field-wrapper input.input-text, {{WRAPPER}} .woocommerce .woocommerce-shipping-fields__field-wrapper select' => 'height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'section_billing_details_inputs_padding',
			[
				'label'      => __( 'Padding', 'skt-addons-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .woocommerce .woocommerce-billing-fields__field-wrapper input.input-text, {{WRAPPER}} .woocommerce .woocommerce-billing-fields__field-wrapper select, {{WRAPPER}} .woocommerce .woocommerce-shipping-fields__field-wrapper input.input-text, {{WRAPPER}} .woocommerce .woocommerce-shipping-fields__field-wrapper select' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->start_controls_tabs( 'tabs_billing_details_inputs_style' );

		$this->start_controls_tab(
			'tab_billing_details_inputs_normal',
			[
				'label' => __( 'Normal', 'skt-addons-elementor' ),
			]
		);

		$this->add_control(
			'section_billing_details_inputs_text_color',
			[
				'label'     => __( 'Text Color', 'skt-addons-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .woocommerce .woocommerce-billing-fields__field-wrapper input.input-text, {{WRAPPER}} .woocommerce .woocommerce-billing-fields__field-wrapper select, {{WRAPPER}} .woocommerce .woocommerce-shipping-fields__field-wrapper input.input-text, {{WRAPPER}} .woocommerce .woocommerce-shipping-fields__field-wrapper select' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'section_billing_details_inputs_background_color',
			[
				'label'     => __( 'Background Color', 'skt-addons-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .woocommerce .woocommerce-billing-fields__field-wrapper input.input-text, {{WRAPPER}} .woocommerce .woocommerce-billing-fields__field-wrapper select, {{WRAPPER}} .woocommerce .woocommerce-shipping-fields__field-wrapper input.input-text, {{WRAPPER}} .woocommerce .woocommerce-shipping-fields__field-wrapper select' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'     => 'section_billing_details_inputs_border',
				'selector' => '{{WRAPPER}} .woocommerce .woocommerce-billing-fields__field-wrapper input.input-text, {{WRAPPER}} .woocommerce .woocommerce-billing-fields__field-wrapper select, {{WRAPPER}} .woocommerce .woocommerce-shipping-fields__field-wrapper input.input-text, {{WRAPPER}} .woocommerce .woocommerce-shipping-fields__field-wrapper select',
			]
		);

		$this->add_control(
			'section_billing_details_inputs_border_radius',
			[
				'label'      => __( 'Border Radius', 'skt-addons-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .woocommerce .woocommerce-billing-fields__field-wrapper input.input-text, {{WRAPPER}} .woocommerce .woocommerce-shipping-fields__field-wrapper input.input-text' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'      => 'section_billing_details_inputs_box_shadow',
				'selector'  => '{{WRAPPER}} .woocommerce .woocommerce-billing-fields__field-wrapper input.input-text, {{WRAPPER}} .woocommerce .woocommerce-billing-fields__field-wrapper select, {{WRAPPER}} .woocommerce .woocommerce-shipping-fields__field-wrapper input.input-text, {{WRAPPER}} .woocommerce .woocommerce-shipping-fields__field-wrapper select',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_billing_details_inputs_hover',
			[
				'label' => __( 'Hover', 'skt-addons-elementor' ),
			]
		);

		$this->add_control(
			'section_billing_details_inputs_text_color_hover',
			[
				'label'     => __( 'Text Color', 'skt-addons-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .woocommerce .woocommerce-billing-fields__field-wrapper input.input-text:hover, {{WRAPPER}} .woocommerce .woocommerce-billing-fields__field-wrapper select:hover, {{WRAPPER}} .woocommerce .woocommerce-shipping-fields__field-wrapper input.input-text:hover, {{WRAPPER}} .woocommerce .woocommerce-shipping-fields__field-wrapper select:hover' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'section_billing_details_inputs_background_color_hover',
			[
				'label'     => __( 'Background Color', 'skt-addons-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .woocommerce .woocommerce-billing-fields__field-wrapper input.input-text:hover, {{WRAPPER}} .woocommerce .woocommerce-billing-fields__field-wrapper select:hover, {{WRAPPER}} .woocommerce .woocommerce-shipping-fields__field-wrapper input.input-text:hover, {{WRAPPER}} .woocommerce .woocommerce-shipping-fields__field-wrapper select:hover' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'section_billing_details_inputs_border_color_hover',
			[
				'label'     => __( 'Border Color', 'skt-addons-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .woocommerce .woocommerce-billing-fields__field-wrapper input.input-text:hover, {{WRAPPER}} .woocommerce .woocommerce-billing-fields__field-wrapper select:hover, {{WRAPPER}} .woocommerce .woocommerce-shipping-fields__field-wrapper input.input-text:hover, {{WRAPPER}} .woocommerce .woocommerce-shipping-fields__field-wrapper select:hover' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'      => 'section_billing_details_inputs_box_shadow_hover',
				'separator' => 'before',
				'selector'  => '{{WRAPPER}} .woocommerce .woocommerce-billing-fields__field-wrapper input.input-text:hover, {{WRAPPER}} .woocommerce .woocommerce-billing-fields__field-wrapper select:hover, {{WRAPPER}} .woocommerce .woocommerce-shipping-fields__field-wrapper input.input-text:hover, {{WRAPPER}} .woocommerce .woocommerce-shipping-fields__field-wrapper select:hover',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_billing_details_inputs_focus',
			[
				'label' => __( 'Focus', 'skt-addons-elementor' ),
			]
		);

		$this->add_control(
			'section_billing_details_inputs_text_color_focus',
			[
				'label'     => __( 'Text Color', 'skt-addons-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .woocommerce .woocommerce-billing-fields__field-wrapper input.input-text:focus, {{WRAPPER}} .woocommerce .woocommerce-billing-fields__field-wrapper select:focus, {{WRAPPER}} .woocommerce .woocommerce-shipping-fields__field-wrapper input.input-text:focus, {{WRAPPER}} .woocommerce .woocommerce-shipping-fields__field-wrapper select:focus' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'section_billing_details_inputs_background_color_focus',
			[
				'label'     => __( 'Background Color', 'skt-addons-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .woocommerce .woocommerce-billing-fields__field-wrapper input.input-text:focus, {{WRAPPER}} .woocommerce .woocommerce-billing-fields__field-wrapper select:focus, {{WRAPPER}} .woocommerce .woocommerce-shipping-fields__field-wrapper input.input-text:focus, {{WRAPPER}} .woocommerce .woocommerce-shipping-fields__field-wrapper select:focus' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'section_billing_details_inputs_border_color_focus',
			[
				'label'     => __( 'Border Color', 'skt-addons-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .woocommerce .woocommerce-billing-fields__field-wrapper input.input-text:focus, {{WRAPPER}} .woocommerce .woocommerce-billing-fields__field-wrapper select:focus, {{WRAPPER}} .woocommerce .woocommerce-shipping-fields__field-wrapper input.input-text:focus, {{WRAPPER}} .woocommerce .woocommerce-shipping-fields__field-wrapper select:focus' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'      => 'section_billing_details_inputs_box_shadow_focus',
				'selector'  => '{{WRAPPER}} .woocommerce .woocommerce-billing-fields__field-wrapper input.input-text:focus, {{WRAPPER}} .woocommerce .woocommerce-billing-fields__field-wrapper select:focus, {{WRAPPER}} .woocommerce .woocommerce-shipping-fields__field-wrapper input.input-text:focus, {{WRAPPER}} .woocommerce .woocommerce-shipping-fields__field-wrapper select:focus',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'section_billing_details_inputs_label_heading',
			[
				'label'     => __( 'Input Label', 'skt-addons-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'section_billing_details_inputs_label_color',
			[
				'label'     => __( 'Color', 'skt-addons-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .woocommerce .woocommerce-billing-fields__field-wrapper label, {{WRAPPER}} .woocommerce .woocommerce-shipping-fields__field-wrapper label' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'section_billing_details_inputs_label_typography',
				'selector' => '{{WRAPPER}} .woocommerce .woocommerce-billing-fields__field-wrapper label, {{WRAPPER}} .woocommerce .woocommerce-shipping-fields__field-wrapper label',
			]
		);

		$this->add_responsive_control(
			'section_billing_details_inputs_label_spacing',
			[
				'label'   => __( 'Spacing', 'skt-addons-elementor' ),
				'type'    => Controls_Manager::SLIDER,
				'default' => [
					'size' => 5,
				],
				'selectors'	=> [
					'{{WRAPPER}} .woocommerce .woocommerce-billing-fields__field-wrapper label, {{WRAPPER}} .woocommerce .woocommerce-shipping-fields__field-wrapper label' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_additional_fields_style',
			[
				'label' => __( 'Additional Information', 'skt-addons-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'section_additional_fields_heading',
			[
				'label' => __( 'Section', 'skt-addons-elementor' ),
				'type'  => Controls_Manager::HEADING,
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'      => 'section_additional_fields_background',
				'types'     => [ 'classic', 'gradient' ],
				'separator' => 'before',
				'selector'  => '{{WRAPPER}} .woocommerce .woocommerce-additional-fields__field-wrapper',
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'        => 'section_additional_fields_border',
				'label'       => __( 'Border', 'skt-addons-elementor' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} .woocommerce .woocommerce-additional-fields__field-wrapper',
			]
		);

		$this->add_control(
			'section_additional_fields_border_radius',
			[
				'label'      => __( 'Border Radius', 'skt-addons-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .woocommerce .woocommerce-additional-fields__field-wrapper' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'      => 'section_additional_fields_box_shadow',
				'separator' => 'before',
				'selector'  => '{{WRAPPER}} .woocommerce .woocommerce-additional-fields__field-wrapper',
			]
		);

		$this->add_control(
			'section_additional_fields_textarea_heading',
			[
				'label'     => __( 'Textarea', 'skt-addons-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'section_additional_fields_textarea_typography',
				'label'    => __( 'Typography', 'skt-addons-elementor' ),
				'selector' => '{{WRAPPER}} .woocommerce .woocommerce-additional-fields__field-wrapper textarea',
			]
		);

		$this->start_controls_tabs( 'tabs_additional_fields_textarea_style' );

		$this->start_controls_tab(
			'tab_additional_fields_textarea_normal',
			[
				'label' => __( 'Normal', 'skt-addons-elementor' ),
			]
		);

		$this->add_control(
			'section_additional_fields_textarea_text_color',
			[
				'label'     => __( 'Text Color', 'skt-addons-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .woocommerce .woocommerce-additional-fields__field-wrapper textarea' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'section_additional_fields_textarea_background_color',
			[
				'label'     => __( 'Background Color', 'skt-addons-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .woocommerce .woocommerce-additional-fields__field-wrapper textarea' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'        => 'section_additional_fields_textarea_border',
				'label'       => __( 'Border', 'skt-addons-elementor' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} .woocommerce .woocommerce-additional-fields__field-wrapper textarea',
			]
		);

		$this->add_control(
			'section_additional_fields_textarea_border_radius',
			[
				'label'      => __( 'Border Radius', 'skt-addons-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .woocommerce .woocommerce-additional-fields__field-wrapper textarea' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'      => 'section_additional_fields_textarea_box_shadow',
				'separator' => 'before',
				'selector'  => '{{WRAPPER}} .woocommerce .woocommerce-additional-fields__field-wrapper textarea',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_additional_fields_textarea_hover',
			[
				'label' => __( 'Hover', 'skt-addons-elementor' ),
			]
		);

		$this->add_control(
			'section_additional_fields_textarea_text_color_hover',
			[
				'label'     => __( 'Text Color', 'skt-addons-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .woocommerce .woocommerce-additional-fields__field-wrapper textarea:hover' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'section_additional_fields_textarea_background_color_hover',
			[
				'label'     => __( 'Background Color', 'skt-addons-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .woocommerce .woocommerce-additional-fields__field-wrapper textarea:hover' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'section_additional_fields_textarea_border_color_hover',
			[
				'label'     => __( 'Border Color', 'skt-addons-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .woocommerce .woocommerce-additional-fields__field-wrapper textarea:hover' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'      => 'section_additional_fields_textarea_box_shadow_hover',
				'separator' => 'before',
				'selector'  => '{{WRAPPER}} .woocommerce .woocommerce-additional-fields__field-wrapper textarea:hover',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_additional_fields_textarea_focus',
			[
				'label' => __( 'Focus', 'skt-addons-elementor' ),
			]
		);

		$this->add_control(
			'section_additional_fields_textarea_text_color_focus',
			[
				'label'     => __( 'Text Color', 'skt-addons-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .woocommerce .woocommerce-additional-fields__field-wrapper textarea:focus' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'section_additional_fields_textarea_background_color_focus',
			[
				'label'     => __( 'Background Color', 'skt-addons-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .woocommerce .woocommerce-additional-fields__field-wrapper textarea:focus' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'section_additional_fields_textarea_border_color_focus',
			[
				'label'     => __( 'Border Color', 'skt-addons-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .woocommerce .woocommerce-additional-fields__field-wrapper textarea:focus' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'      => 'section_additional_fields_textarea_box_shadow_focus',
				'selector'  => '{{WRAPPER}} .woocommerce .woocommerce-additional-fields__field-wrapper textarea:focus',
			]
		);

		$this->end_controls_tab();
		$this->end_controls_tabs();

		$this->add_control(
			'section_additional_fields_textarea_label_heading',
			[
				'label'     => __( 'Textarea Label', 'skt-addons-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'section_additional_fields_textarea_label_color',
			[
				'label'     => __( 'Color', 'skt-addons-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .woocommerce .woocommerce-additional-fields__field-wrapper label' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'section_additional_fields_textarea_label_typography',
				'selector' => '{{WRAPPER}} .woocommerce .woocommerce-additional-fields__field-wrapper label',
			]
		);

		$this->add_responsive_control(
			'section_additional_fields_textarea_label_spacing',
			[
				'label'   => __( 'Spacing', 'skt-addons-elementor' ),
				'type'    => Controls_Manager::SLIDER,
				'default' => [
					'size' => 5,
				],
				'selectors'	=> [
					'{{WRAPPER}} .woocommerce .woocommerce-additional-fields__field-wrapper label' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		/**
		 * Review order
		 */
		$this->start_controls_section(
			'section_review_order_style',
			[
				'label' => __( 'Review Order', 'skt-addons-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'section_review_order_typography',
				'selector' => '{{WRAPPER}} .woocommerce .woocommerce-checkout-review-order-table',
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'      => 'section_review_order_background',
				'types'     => [ 'classic', 'gradient' ],
				'selector'  => '{{WRAPPER}} .woocommerce .woocommerce-checkout-review-order-table',
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'        => 'section_review_order_border',
				'selector'    => '{{WRAPPER}} .woocommerce .woocommerce-checkout-review-order-table',
			]
		);

		$this->add_control(
			'section_review_order_border_radius',
			[
				'label'      => __( 'Border Radius', 'skt-addons-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .woocommerce .woocommerce-checkout-review-order-table' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'      => 'section_review_order_box_shadow',
				'selector'  => '{{WRAPPER}} .woocommerce .woocommerce-checkout-review-order-table',
			]
		);

		$this->add_responsive_control(
			'section_review_order_padding',
			[
				'label'      => __( 'Padding', 'skt-addons-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .woocommerce .woocommerce-checkout-review-order-table' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'section_review_order_table_head_heading',
			[
				'label'     => __( 'Table Head', 'skt-addons-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'section_review_order_table_head_text_color',
			[
				'label'     => __( 'Text Color', 'skt-addons-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .woocommerce .woocommerce-checkout-review-order-table thead th' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'section_review_order_table_head_background_color',
			[
				'label'     => __( 'Background Color', 'skt-addons-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .woocommerce .woocommerce-checkout-review-order-table thead th' => 'background-color: {{VALUE}};',
				],
			]
		);

		//Table Body
		$this->add_control(
			'section_review_order_table_body_heading',
			[
				'label'     => __( 'Table Body', 'skt-addons-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->start_controls_tabs( 'section_review_order_tbody_rows_tabs_style' );

		$this->start_controls_tab(
			'tab_section_review_order_even_row',
			[
				'label' => __( 'Even Row', 'skt-addons-elementor' ),
			]
		);

		$this->add_control(
			'section_review_order_even_row_text_color',
			[
				'label'     => __( 'Text Color', 'skt-addons-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .woocommerce .woocommerce-checkout-review-order-table .cart_item:nth-child(2n)' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'section_review_order_even_row_background_color',
			[
				'label'     => __( 'Background Color', 'skt-addons-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .woocommerce .woocommerce-checkout-review-order-table .cart_item:nth-child(2n) > td' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_section_review_order_odd_row',
			[
				'label' => __( 'Odd Row', 'skt-addons-elementor' ),
			]
		);

		$this->add_control(
			'section_review_order_odd_row_text_color',
			[
				'label'     => __( 'Text Color', 'skt-addons-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .woocommerce .woocommerce-checkout-review-order-table .cart_item:nth-child(2n+1)' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'section_review_order_odd_row_background_color',
			[
				'label'     => __( 'Background Color', 'skt-addons-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .woocommerce .woocommerce-checkout-review-order-table .cart_item:nth-child(2n+1) > td' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();
		$this->end_controls_tabs();

		//Table Footer
		$this->add_control(
			'section_review_order_table_foot_heading',
			[
				'label'     => __( 'Table Footer', 'skt-addons-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'section_review_order_table_foot_text_color',
			[
				'label'     => __( 'Text Color', 'skt-addons-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .woocommerce .woocommerce-checkout-review-order-table tfoot tr' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'section_review_order_table_foot_background_color',
			[
				'label'     => __( 'Background Color', 'skt-addons-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .woocommerce .woocommerce-checkout-review-order-table tfoot tr' => 'background-color: {{VALUE}};',
				],
			]
		);

		//Table Border
		$this->add_control(
			'section_review_order_row_separator_heading',
			[
				'label'     => __( 'Table Border', 'skt-addons-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'section_review_order_row_separator_type',
			[
				'label'   => __( 'Type', 'skt-addons-elementor' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'solid',
				'options' => [
					'none'   => __( 'None', 'skt-addons-elementor' ),
					'solid'  => __( 'Solid', 'skt-addons-elementor' ),
					'dotted' => __( 'Dotted', 'skt-addons-elementor' ),
					'dashed' => __( 'Dashed', 'skt-addons-elementor' ),
					'double' => __( 'Double', 'skt-addons-elementor' ),
				],
				'selectors'             => [
					'{{WRAPPER}} .woocommerce table.shop_table.woocommerce-checkout-review-order-table thead th,
					{{WRAPPER}} .woocommerce table.shop_table.woocommerce-checkout-review-order-table td,
					{{WRAPPER}} .woocommerce table.shop_table.woocommerce-checkout-review-order-table tfoot th' => 'border-style: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'section_review_order_row_separator_color',
			[
				'label'     => __( 'Color', 'skt-addons-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .woocommerce table.shop_table.woocommerce-checkout-review-order-table thead th,
					{{WRAPPER}} .woocommerce table.shop_table.woocommerce-checkout-review-order-table td,
					{{WRAPPER}} .woocommerce table.shop_table.woocommerce-checkout-review-order-table tfoot th' => 'border-color: {{VALUE}};',
				],
				'condition'             => [
					'section_review_order_row_separator_type!' => 'none',
				],
			]
		);

		$this->add_responsive_control(
			'section_review_order_row_separator_size',
			[
				'label'   => __( 'Size', 'skt-addons-elementor' ),
				'type'    => Controls_Manager::SLIDER,
				'default' => [
					'size' => '',
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 20,
					],
				],
				'selectors'             => [
					'{{WRAPPER}} .woocommerce table.shop_table.woocommerce-checkout-review-order-table thead th,
					{{WRAPPER}} .woocommerce table.shop_table.woocommerce-checkout-review-order-table td,
					{{WRAPPER}} .woocommerce table.shop_table.woocommerce-checkout-review-order-table tfoot th' => 'border-width: {{SIZE}}{{UNIT}};',
				],
				'condition'             => [
					'section_review_order_row_separator_type!' => 'none',
				],
			]
		);

		$this->end_controls_section();

		/**
		 * Payment method
		 */
		$this->start_controls_section(
			'section_payment_method_style',
			[
				'label' => __( 'Payment Method', 'skt-addons-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'section_payment_method_heading',
			[
				'label' => __( 'Section', 'skt-addons-elementor' ),
				'type'  => Controls_Manager::HEADING,
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'      => 'section_payment_method_background',
				'types'     => [ 'classic', 'gradient' ],
				'selector'  => '{{WRAPPER}} .woocommerce .woocommerce-checkout #payment',
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'        => 'section_payment_method_border',
				'selector'    => '{{WRAPPER}} .woocommerce .woocommerce-checkout #payment',
			]
		);

		$this->add_control(
			'section_payment_method_border_radius',
			[
				'label'      => __( 'Border Radius', 'skt-addons-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .woocommerce .woocommerce-checkout #payment' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'      => 'section_payment_method_box_shadow',
				'selector'  => '{{WRAPPER}} .woocommerce .woocommerce-checkout #payment',
			]
		);

		$this->add_control(
			'section_payment_method_label_heading',
			[
				'label'     => __( 'Label', 'skt-addons-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'payment_method_label_typography',
				'selector' => '{{WRAPPER}} .woocommerce .woocommerce-checkout .payment_methods label',
			]
		);

		$this->add_control(
			'payment_method_label_text_color',
			[
				'label'     => __( 'Color', 'skt-addons-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .woocommerce .woocommerce-checkout .payment_methods label' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'section_payment_method_message_heading',
			[
				'label'     => __( 'Message', 'skt-addons-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'payment_method_message_typography',
				'selector' => '{{WRAPPER}} .woocommerce-checkout #payment .payment_box',
			]
		);

		$this->add_control(
			'payment_method_message_text_color',
			[
				'label'     => __( 'Color', 'skt-addons-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .woocommerce-checkout #payment .payment_box' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'payment_method_message_background_color',
			[
				'label'     => __( 'Background Color', 'skt-addons-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .woocommerce-checkout #payment .payment_box'        => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .woocommerce-checkout #payment .payment_box:before' => 'border-bottom-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_section();

		/**
		 * Privacy policy
		 */
		$this->start_controls_section(
			'section_privacy_policy_style',
			[
				'label' => __( 'Privacy Policy', 'skt-addons-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'privacy_policy_color',
			[
				'label'     => __( 'Text Color', 'skt-addons-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .woocommerce .woocommerce-terms-and-conditions-wrapper .woocommerce-privacy-policy-text' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'privacy_policy_link_color',
			[
				'label'     => __( 'Link Color', 'skt-addons-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .woocommerce .woocommerce-terms-and-conditions-wrapper .woocommerce-privacy-policy-text .woocommerce-privacy-policy-link' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'privacy_policy_link_hover_color',
			[
				'label'     => __( 'Link Hover Color', 'skt-addons-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .woocommerce .woocommerce-terms-and-conditions-wrapper .woocommerce-privacy-policy-text .woocommerce-privacy-policy-link:hover' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'privacy_policy_typography',
				'selector' => '{{WRAPPER}} .woocommerce .woocommerce-terms-and-conditions-wrapper .woocommerce-privacy-policy-text',
			]
		);

		$this->end_controls_section();

		/**
		 * Button
		 */
		$this->start_controls_section(
			'section_checkout_button_style',
			[
				'label' => __( 'Button', 'skt-addons-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'button_typography',
				'selector' => '{{WRAPPER}} .woocommerce .woocommerce-checkout #place_order',
			]
		);

		$this->add_control(
			'button_width',
			[
				'label'   => __( 'Width', 'skt-addons-elementor' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'auto',
				'options' => [
					'auto'   => __( 'Auto', 'skt-addons-elementor' ),
					'full'   => __( 'Full Width', 'skt-addons-elementor' ),
					'custom' => __( 'Custom', 'skt-addons-elementor' ),
				],
				'prefix_class' => 'skt-wc-checkout--btn-',
			]
		);

		$this->add_responsive_control(
			'button_custom_width',
			[
				'label'      => __( 'Custom Width', 'skt-addons-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range' => [
					'px' => [
						'min' => 100,
						'max' => 500,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .woocommerce .woocommerce-checkout #place_order' => 'width: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'button_width' => 'custom',
				],
			]
		);

		$this->add_responsive_control(
			'button_margin',
			[
				'label'              => __( 'Margin', 'skt-addons-elementor' ),
				'type'               => Controls_Manager::DIMENSIONS,
				'size_units'         => [ 'px', 'em', '%' ],
				'allowed_dimensions' => 'vertical',
				'placeholder'        => [
					'top'    => '',
					'right'  => 'auto',
					'bottom' => '',
					'left'   => 'auto',
				],
				'selectors' => [
					'{{WRAPPER}} .woocommerce .woocommerce-checkout #place_order' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->start_controls_tabs( 'tabs_button_style' );

		$this->start_controls_tab(
			'tab_button_normal',
			[
				'label' => __( 'Normal', 'skt-addons-elementor' ),
			]
		);

		$this->add_control(
			'button_bg_color_normal',
			[
				'label'     => __( 'Background Color', 'skt-addons-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .woocommerce .woocommerce-checkout #place_order' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'button_text_color_normal',
			[
				'label'     => __( 'Text Color', 'skt-addons-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .woocommerce .woocommerce-checkout #place_order' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'        => 'button_border_normal',
				'selector'    => '{{WRAPPER}} .woocommerce .woocommerce-checkout #place_order',
			]
		);

		$this->add_control(
			'button_border_radius',
			[
				'label'      => __( 'Border Radius', 'skt-addons-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .woocommerce .woocommerce-checkout #place_order' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'button_padding',
			[
				'label'      => __( 'Padding', 'skt-addons-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .woocommerce .woocommerce-checkout #place_order' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'button_box_shadow',
				'selector' => '{{WRAPPER}} .woocommerce .woocommerce-checkout #place_order',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_button_hover',
			[
				'label' => __( 'Hover', 'skt-addons-elementor' ),
			]
		);

		$this->add_control(
			'button_bg_color_hover',
			[
				'label'     => __( 'Background Color', 'skt-addons-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .woocommerce .woocommerce-checkout #place_order:hover' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'button_text_color_hover',
			[
				'label'     => __( 'Text Color', 'skt-addons-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .woocommerce .woocommerce-checkout #place_order:hover' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'button_border_color_hover',
			[
				'label'     => __( 'Border Color', 'skt-addons-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .woocommerce .woocommerce-checkout #place_order:hover' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'button_box_shadow_hover',
				'selector' => '{{WRAPPER}} .woocommerce .woocommerce-checkout #place_order:hover',
			]
		);

		$this->end_controls_tab();
		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	protected static function _setup_env( $settings ) {
		if ( ! skt_addons_elementor()->editor->is_edit_mode() ||
			! function_exists( 'WC' ) ||
			empty( WC()->cart ) ) {
			return;
		}

		if ( WC()->cart->get_cart_contents_count() < 1 ) {
			$products = wc_get_products( [
				'status' => [ 'publish' ],
				'type'   => [ 'simple' ],
				'return' => 'ids',
				'limit'  => 1,
			] );

			if ( ! empty( $products ) ) {
				WC()->cart->add_to_cart( $products[0], 1 );
			}
		}
	}

	public static function show_wc_missing_alert() {
		if ( current_user_can( 'activate_plugins' ) ) {
			printf(
				'<div %s>%s</div>',
				'style="margin: 1rem;padding: 1rem 1.25rem;border-left: 5px solid #f5c848;color: #856404;background-color: #fff3cd;"',
				__( 'WooCommerce is missing! Please install and activate WooCommerce.', 'skt-addons-elementor' )
				);
		}
	}

	protected function render() {
		if ( ! function_exists( 'WC' ) ) {
			self::show_wc_missing_alert();
			return;
		}

		$settings = $this->get_settings_for_display();

		self::_setup_env( $settings );

		echo skt_addons_elementor_do_shortcode( 'woocommerce_checkout' );
	}
}