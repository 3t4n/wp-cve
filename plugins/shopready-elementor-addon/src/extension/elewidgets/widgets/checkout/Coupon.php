<?php

namespace Shop_Ready\extension\elewidgets\widgets\checkout;

use Shop_Ready\base\elementor\style_controls\common\Widget_Form;

/**
 * WooCommerce Coupon Form
 * @author quomodosoft.com
 * @see woocommerce.com
 */
class Coupon extends \Shop_Ready\extension\elewidgets\Widget_Base
{

	use Widget_Form;
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
				'raw' => esc_html__('Use This Widget in WooCommerce CheckOut And Cart Template.', 'shopready-elementor-addon'),
				'content_classes' => 'woo-ready-account-notice',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'editor_content_section',
			[
				'label' => esc_html__('Editor Only', 'shopready-elementor-addon'),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'show_checkout_content',
			[
				'label' => esc_html__('Refresh?', 'shopready-elementor-addon'),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => esc_html__('Yes', 'shopready-elementor-addon'),
				'label_off' => esc_html__('No', 'shopready-elementor-addon'),
				'return_value' => 'yes',
				'default' => '',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'content_section',
			[
				'label' => esc_html__('Content Settings', 'shopready-elementor-addon'),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);


		$this->add_control(
			'wready_coupon_collapsible',
			[
				'label' => __('Collapsible?', 'shopready-elementor-addon'),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => __('Yes', 'shopready-elementor-addon'),
				'label_off' => __('No', 'shopready-elementor-addon'),
				'return_value' => 'yes',
				'default' => 'yes',
			]
		);

		$this->add_control(
			'show_coupon_heading',
			[
				'label' => __('Coupon Heading?', 'shopready-elementor-addon'),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => __('Show', 'shopready-elementor-addon'),
				'label_off' => __('Hide', 'shopready-elementor-addon'),
				'return_value' => 'yes',
				'default' => '',
			]
		);

		$this->add_control(
			'coupon_heading_text',
			[
				'label' => __('Coupon Heading', 'shopready-elementor-addon'),
				'type' => \Elementor\Controls_Manager::TEXTAREA,
				'default' => esc_html__('If you have a coupon code, please apply it below.', 'shopready-elementor-addon'),
				'condition' => [
					'show_coupon_heading' => ['yes']
				]
			]
		);



		$this->add_control(
			'coupon_heading_colapse_msg_text',
			[
				'label' => __('Coupon Collapse Message', 'shopready-elementor-addon'),
				'type' => \Elementor\Controls_Manager::TEXTAREA,
				'default' => esc_html__('Have a coupon?', 'shopready-elementor-addon'),
				'condition' => [
					'wready_coupon_collapsible' => ['yes']
				]
			]
		);

		$this->add_control(
			'coupon_heading_colapse_text',
			[
				'label' => __('Coupon Collapse Text', 'shopready-elementor-addon'),
				'type' => \Elementor\Controls_Manager::TEXTAREA,
				'default' => esc_html__('Click here to enter your code', 'shopready-elementor-addon'),
				'condition' => [
					'wready_coupon_collapsible' => ['yes']
				]
			]
		);




		$this->end_controls_section();


		$this->box_css(
			[
				'title' => esc_html__('Coupon Toggle Container', 'shopready-elementor-addon'),
				'slug' => 'wready_coupon_toggle_container',
				'element_name' => '__wrteady_toggle_',
				'selector' => '{{WRAPPER}} .woocommerce-form-coupon-toggle .woocommerce-info',
				'condition' => [
					'wready_coupon_collapsible' => ['yes']
				],
				'disable_controls' => [
					'position',
					'size',
				]
			]
		);

		$this->text_wrapper_css(
			[
				'title' => esc_html__('Message Content', 'shopready-elementor-addon'),
				'slug' => 'wready_ne_box_span_style',
				'element_name' => 'span__wr_content_span',
				'selector' => '{{WRAPPER}} .woocommerce-form-coupon-toggle span',
				'hover_selector' => false,
				'condition' => [
					'wready_coupon_collapsible' => ['yes']
				],
				'disable_controls' => [
					'position',
					'display',
				]
			]
		);

		$this->text_wrapper_css(
			[
				'title' => esc_html__('Toggle Link', 'shopready-elementor-addon'),
				'slug' => 'wready_ne_toggle_content_style',
				'element_name' => 'toggle__wr_content_anchor',
				'selector' => '{{WRAPPER}} .woocommerce-form-coupon-toggle a',
				'condition' => [
					'wready_coupon_collapsible' => ['yes']
				],
				'disable_controls' => [
					'position',
					'display',
				]
			]
		);

		$this->text_minimum_css(
			[
				'title' => esc_html__('Coupon Heading', 'shopready-elementor-addon'),
				'slug' => 'coupon_form_c_heading_style_',
				'element_name' => 's_wready_coupon_heading_',
				'selector' => '{{WRAPPER}} .wready-coupon-row-wrapper .wready-coupon-heading-col',
				'condition' => [
					'show_coupon_heading' => ['yes']
				]
			]
		);

		$this->input_field(
			[
				'title' => esc_html__('Coupon Input', 'shopready-elementor-addon'),
				'slug' => 'wready_wc_default_coupon_input',
				'element_name' => 'cart_shortcode_table_copupon_input',
				'selector' => '{{WRAPPER}} .wready-coupon-row-wrapper [name="coupon_code"]',
				'hover_selector' => '{{WRAPPER}} .wready-coupon-row-wrapper [name="coupon_code"]:focus'
			]
		);

		$this->text_wrapper_css(
			[
				'title' => esc_html__('Coupon Button', 'shopready-elementor-addon'),
				'slug' => 'wready_ntte_coupon_button_style',
				'element_name' => 'coupon__wr_buttons_anchor',
				'selector' => '{{WRAPPER}} .wready-coup-btn-col [name="apply_coupon"]',
				'hover_selector' => '{{WRAPPER}} .wready-coup-btn-col [name="apply_coupon"]:hover',
				'disable_controls' => [
					'position',
					'display',
				]
			]
		);

		/** Layouts */

		$this->box_layout(
			[
				'title' => esc_html__('Coupon Form', 'shopready-elementor-addon'),
				'slug' => 'coupon_form_container_box_style',
				'element_name' => 's_wready_coupon_form',
				'selector' => '{{WRAPPER}} .wready-coupon-row-wrapper',
				'disable_controls' => [
					'position',
					'box-size',
				]
			]
		);

		$this->box_layout_child(
			[
				'title' => esc_html__('Coupon Heading', 'shopready-elementor-addon'),
				'slug' => 'coupon_form_container_heading_style',
				'element_name' => 's_wready_coupon_heading',
				'selector' => '{{WRAPPER}} .wready-coupon-row-wrapper .wready-coupon-heading-col',
				'condition' => [
					'show_coupon_heading' => ['yes']
				]
			]
		);

		/** Layouts end */
	}


	protected function html()
	{

		$settings = $this->get_settings_for_display();

		if (is_null(WC()->cart)) {
			return;
		}

		shop_ready_widget_template_part(
			'checkout/template-part/coupon-form.php',
			array(
				'settings' => $settings,
			)
		);
	}
}
