<?php

namespace Shop_Ready\extension\elewidgets\widgets\login_register;

use Shop_Ready\base\elementor\style_controls\common\Widget_Form;

class Lost_Pass_Error extends \Shop_Ready\extension\elewidgets\Widget_Base
{

	use Widget_Form;
	public $wrapper_class = true;

	protected function register_controls()
	{

		$this->start_controls_section(
			'lost_password_section',
			[
				'label' => esc_html__('Error Message', 'shopready-elementor-addon'),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'show_messege_in_editor',
			[
				'label'        => esc_html__('Show message in editor', 'shopready-elementor-addon'),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'label_on'     => esc_html__('Yes', 'shopready-elementor-addon'),
				'label_off'    => esc_html__('No', 'shopready-elementor-addon'),
				'return_value' => 'yes',
				'default'      => 'yes',
			]
		);

		$this->add_control(
			'enable_template_message',
			[
				'label'        => esc_html__('Template Message', 'shopready-elementor-addon'),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'label_on'     => esc_html__('Yes', 'shopready-elementor-addon'),
				'label_off'    => esc_html__('No', 'shopready-elementor-addon'),
				'return_value' => 'yes',
				'default'      => '',
			]
		);

		$this->add_control(
			'template_id',
			[
				'label'     => esc_html__('Template ', 'shopready-elementor-addon'),
				'type'      => \Elementor\Controls_Manager::SELECT,
				'default'   => '',
				'options'   => shop_ready_get_elementor_templates_arr(),
				'condition' => [
					'enable_template_message' => 'yes'
				]
			]
		);

		$this->end_controls_section();



		$this->text_css(
			[
				'title'        => esc_html__('Error Notice', 'shopready-elementor-addon'),
				'slug'         => 'notice_suc_box_style',
				'element_name' => 'notice_suc_ready__',
				'selector'     => '{{WRAPPER}} .woocommerce-error',
				'hover_selector'     => false,
				'disable_controls' => [
					'size', 'position', 'display'
				]
			]
		);

		$this->text_css(
			[
				'title'        => esc_html__('Error Notice Icon', 'shopready-elementor-addon'),
				'slug'         => 'error_notice_icon',
				'element_name' => 'error_notice_icon_',
				'selector'     => '{{WRAPPER}} .woocommerce-error:before, {{WRAPPER}} .woocommerce-error:after',
				'hover_selector'     => false,
				'disable_controls' => [
					'size', 'position', 'display'
				]
			]
		);
	}


	protected function html()
	{

		$settings = $this->get_settings_for_display();

		include('style/lost_pass/error.php');
	}
}
