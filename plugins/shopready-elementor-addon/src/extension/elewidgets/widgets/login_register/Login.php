<?php

namespace Shop_Ready\extension\elewidgets\widgets\login_register;

use Shop_Ready\base\elementor\style_controls\common\Widget_Form;

class Login extends \Shop_Ready\extension\elewidgets\Widget_Base
{

	use Widget_Form;
	public $wrapper_class = true;


	protected function register_controls()
	{

		$this->start_controls_section(
			'layout_content_section',
			[
				'label' => __('Layout', 'shopready-elementor-addon'),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'preset',
			[
				'label' => __('Preset', 'shopready-elementor-addon'),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'woo-ready-login-form-preset-1',
				'options' => [
					'woo-ready-login-form-preset-1'  => __('Preset 1', 'shopready-elementor-addon'),
					'woo-ready-login-form-preset-2' => __('Preset 2', 'shopready-elementor-addon'),
				],
			]
		);

		$this->end_controls_section();


		$this->start_controls_section(
			'content_section',
			[
				'label' => __('input / label', 'shopready-elementor-addon'),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'show_label',
			[
				'label'        => esc_html__('Show Label', 'shopready-elementor-addon'),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'label_on'     => esc_html__('Yes', 'shopready-elementor-addon'),
				'label_off'    => esc_html__('No', 'shopready-elementor-addon'),
				'return_value' => 'yes',
				'default'      => 'yes',
			]
		);

		$this->add_control(
			'username_label',
			[
				'label'       => esc_html__('Username Label', 'shopready-elementor-addon'),
				'type'        => \Elementor\Controls_Manager::TEXT,
				'default'     => esc_html__('Username or email address', 'shopready-elementor-addon'),
				'placeholder' => esc_html__('Type your username label here', 'shopready-elementor-addon'),
				'condition'   => [
					'show_label' => ['yes']
				]
			]
		);

		$this->add_control(
			'password_label',
			[
				'label' => esc_html__('Password Label', 'shopready-elementor-addon'),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => esc_html__('Username or email address', 'shopready-elementor-addon'),
				'placeholder' => esc_html__('Type your password label here', 'shopready-elementor-addon'),
				'condition' => [
					'show_label' => ['yes']
				]
			]
		);

		// placeholder
		$this->add_control(
			'username_placeholder',
			[
				'label' => esc_html__('Username Placeholder', 'shopready-elementor-addon'),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => esc_html__('Username or email address', 'shopready-elementor-addon'),
				'placeholder' => esc_html__('Type your username placeholder here', 'shopready-elementor-addon'),

			]
		);

		$this->add_control(
			'password_placeholder',
			[
				'label' => esc_html__('Password Placeholder', 'shopready-elementor-addon'),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => esc_html__('Username or email address', 'shopready-elementor-addon'),
				'placeholder' => esc_html__('Type your password placeholder here', 'shopready-elementor-addon'),

			]
		);



		$this->end_controls_section();



		$this->start_controls_section(
			'_remember_content_section',
			[
				'label' => esc_html__('Remember Option', 'shopready-elementor-addon'),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'show_remember_checkbox',
			[
				'label' => esc_html__('Show Remember', 'shopready-elementor-addon'),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => esc_html__('Yes', 'shopready-elementor-addon'),
				'label_off' => esc_html__('No', 'shopready-elementor-addon'),
				'return_value' => 'yes',
				'default' => 'yes',
			]
		);

		$this->add_control(
			'remember_text',
			[
				'label' => esc_html__('Remember Text', 'shopready-elementor-addon'),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => esc_html__('Remember Me', 'shopready-elementor-addon'),
				'placeholder' => esc_html__('Type your remember title here', 'shopready-elementor-addon'),
				'condition' => [
					'show_remember_checkbox' => ['yes']
				]

			]
		);

		$this->end_controls_section();


		$this->start_controls_section(
			'_submit_btn_section',
			[
				'label' => esc_html__('Button', 'shopready-elementor-addon'),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'button_text',
			[
				'label' => esc_html__('Button text', 'shopready-elementor-addon'),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => esc_html__('Submit', 'shopready-elementor-addon'),
				'placeholder' => esc_html__('Type your button label here', 'shopready-elementor-addon'),

			]
		);

		$this->add_control(
			'button_icon',
			[
				'label' => __('Icon', 'shopready-elementor-addon'),
				'type' => \Elementor\Controls_Manager::ICONS,

			]
		);

		$this->add_control(
			'icon_align',
			[
				'label' => __('Icon Position', 'shopready-elementor-addon'),
				'type' => \Elementor\Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => __('Left', 'shopready-elementor-addon'),
						'icon' => 'fa fa-angle-left',
					],

					'right' => [
						'title' => __('Right', 'shopready-elementor-addon'),
						'icon' => 'fa fa-angle-right',
					],
				],
				'default' => 'right',
				'toggle' => true,
			]
		);



		$this->end_controls_section();



		// text_css


		$this->text_wrapper_css(
			[

				'title'        => esc_html__('Username Label', 'shopready-elementor-addon'),
				'slug'         => 'usr_label_box_style',
				'element_name' => 'name_label_woo_ready__',
				'selector'     => '{{WRAPPER}} .woo-ready-account-form .woo-ready-username-wrapper label',
				'hover_selector'     => '{{WRAPPER}} .woo-ready-account-form .woo-ready-username-wrapper:hover label',
				'condition' => [
					'show_label' => ['yes']
				]
			]
		);

		$this->input_field(
			[

				'title'        => esc_html__('Username input', 'shopready-elementor-addon'),
				'slug'         => 'usr_input_box_styles',
				'element_name' => 'uname_linput_woo_ready__',
				'selector'     => '{{WRAPPER}} .woo-ready-account-form .woo-ready-username-wrapper input',
				'hover_selector'     => '{{WRAPPER}} .woo-ready-account-form .woo-ready-username-wrapper input:focus'

			]
		);

		// pass

		$this->text_wrapper_css(
			[

				'title'          => esc_html__('Password Label', 'shopready-elementor-addon'),
				'slug'           => 'pass_label_box_style',
				'element_name'   => 'pass_label_woo_ready__',
				'selector'       => '{{WRAPPER}} .woo-ready-account-form .woo-ready-password-wrapper label',
				'hover_selector' => '{{WRAPPER}} .woo-ready-account-form .woo-ready-password-wrapper:hover label',
				'condition' => [
					'show_label' => ['yes']
				]
			]
		);

		$this->input_field(
			[

				'title'          => esc_html__('Password input', 'shopready-elementor-addon'),
				'slug'           => 'pass_input_box_styles',
				'element_name'   => 'pass_linput_woo_ready__',
				'selector'       => '{{WRAPPER}} .woo-ready-account-form .woo-ready-password-wrapper input',
				'hover_selector' => '{{WRAPPER}} .woo-ready-account-form .woo-ready-password-wrapper input:focus'

			]
		);

		$this->box_css(
			[

				'title'        => esc_html__('Remember Me', 'shopready-elementor-addon'),
				'slug'         => 'check_box_style',
				'element_name' => 'checkbox__woo_ready__',
				'selector'     => '{{WRAPPER}} .woo-ready-account-form .woo-ready-checkbox-wrapper',
				'condition' => [
					'show_remember_checkbox' => ['yes']
				],
				'disable_controls' => [
					'position', 'size', 'border', 'bg', 'display'
				]
			]
		);

		$this->text_wrapper_css(
			[

				'title'          => esc_html__('Remember Label', 'shopready-elementor-addon'),
				'slug'           => 'checkbox_label_box_style',
				'element_name'   => 'checkbox_label_woo_ready__',
				'selector'       => '{{WRAPPER}} .woo-ready-account-form .woo-ready-form-checkbox span',
				'hover_selector' => false,
				'condition' => [
					'show_remember_checkbox' => ['yes']
				],
				'disable_controls' => ['position', 'border', 'bg', 'box-shadow']
			]
		);

		$this->checkbox_field(
			[

				'title'          => esc_html__('Remember checkbox', 'shopready-elementor-addon'),
				'slug'           => 'checkbox_input_box_styles',
				'element_name'   => 'checkbox_linput_woo_ready__',
				'selector'       => '{{WRAPPER}} .woo-ready-account-form .woo-ready-form-checkbox input',
				'hover_selector' => false,
				'condition' => [
					'show_remember_checkbox' => ['yes']
				],
				'disable_controls' => ['border', 'bg', 'box-shadow']
			]
		);


		$this->box_css(
			[

				'title'        => esc_html__('Button Wrapper', 'shopready-elementor-addon'),
				'slug'         => 'button_box_style',
				'element_name' => 'buton__woo_ready__',
				'selector'     => '{{WRAPPER}} .woo-ready-account-form .woo-ready-btn-wrapper',
				'disable_controls' => [
					'position', 'size', 'display', 'border', 'bg'
				]
			]
		);

		$this->text_css(
			[

				'title'          => esc_html__('Button', 'shopready-elementor-addon'),
				'slug'           => 'btn_input_box_styles',
				'element_name'   => 'btn_linput_woo_ready__',
				'selector'       => '{{WRAPPER}} .woo-ready-account-form .woo-ready-btn-wrapper button',
				'hover_selector' => '{{WRAPPER}} .woo-ready-account-form .woo-ready-btn-wrapper button:hover',
				'disable_controls' => [
					'position',
				]
			]
		);

		$this->text_minimum_css(
			[

				'title'        => esc_html__('Button Icon', 'shopready-elementor-addon'),
				'slug'         => 'btn_icon_box_style',
				'element_name' => 'name_icon_woo_ready__',
				'selector'     => '{{WRAPPER}} .woo-ready-account-form .woo-ready-btn-wrapper button i',
				'hover_selector' => '{{WRAPPER}} .woo-ready-account-form .woo-ready-btn-wrapper button:hover i',
				'disable_controls' => [
					'display',
				]
			]
		);
	}


	protected function html()
	{
		$settings = $this->get_settings_for_display();

		include('style/login/style1.php');
	}
}
