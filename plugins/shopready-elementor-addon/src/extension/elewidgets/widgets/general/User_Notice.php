<?php

namespace Shop_Ready\extension\elewidgets\widgets\general;

use Elementor\Controls_Manager;

class User_Notice extends \Shop_Ready\extension\elewidgets\Widget_Base
{


	public $wrapper_class = true;
	/**
	 * WooCommerce Notice 
	 * Cart , Checkout where missing notice widgets by shop ready
	 * @see https://docs.woocommerce.com/document/woocommerce-shortcodes/#section-21
	 */
	public function global_wc_notice()
	{

		$this->box_minimum_css(
			[
				'title'        => esc_html__('Success Box', 'shopready-elementor-addon'),
				'slug'         => 'woo_ready_box_style_succ',
				'element_name' => 'woo_ready_noticee_succ_',
				'selector'     => '{{WRAPPER}} .woocommerce-message',
				'hover_selector'     => false,
				'disable_controls' => [
					'display',
				],
			]
		);

		$this->text_minimum_css(
			[
				'title'        => esc_html__('Success Box Icon', 'shopready-elementor-addon'),
				'slug'         => 'woo_ready_box_style_succ_icon',
				'element_name' => 'woo_ready_noticee_succ__icon',
				'selector'     => '{{WRAPPER}} .woocommerce-message::before',
				'hover_selector'     => false,
				'disable_controls' => [
					'display', 'bg', 'border'
				],
			]
		);

		$this->box_minimum_css(
			[
				'title'        => esc_html__('Error Box', 'shopready-elementor-addon'),
				'slug'         => 'woo_ready_box_style',
				'element_name' => 'woo_ready_noticee__',
				'selector'     => '{{WRAPPER}} .woocommerce-error',
				'disable_controls' => [
					'display',
				],
			]
		);

		$this->text_minimum_css(
			[
				'title'          => esc_html__('Error List', 'shopready-elementor-addon'),
				'slug'           => 'woo_ready_list_style',
				'element_name'   => 'woo_ready_notice_list_',
				'selector'       => '{{WRAPPER}} .woocommerce-error li',
				'hover_selector' => false,
				'disable_controls' => [
					'display',
				],
			]
		);
	}
	protected function register_controls()
	{


		$this->start_controls_section(
			'editor_content_section',
			[
				'label' => esc_html__('Editor Only', 'shopready-elementor-addon'),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'editor_show_non_user_notice',
			[
				'label'        => __('Refresh?', 'shopready-elementor-addon'),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'label_on'     => __('Show', 'shopready-elementor-addon'),
				'label_off'    => __('Hide', 'shopready-elementor-addon'),
				'return_value' => 'yes',
				'default'      => '',
			]
		);

		$this->end_controls_section();

		$this->global_wc_notice();
	}


	protected function html()
	{

		shop_ready_widget_template_part(
			'general/template-parts/notice.php'
		);
	}
}
