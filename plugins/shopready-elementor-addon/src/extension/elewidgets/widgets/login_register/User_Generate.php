<?php

namespace Shop_Ready\extension\elewidgets\widgets\login_register;

use Shop_Ready\base\elementor\style_controls\common\Widget_Form;
/*
* User Register
*/

class User_Generate extends \Shop_Ready\extension\elewidgets\Widget_Base
{

	use Widget_Form;
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
				'label'           => esc_html__('Important Note', 'shopready-elementor-addon'),
				'type'            => \Elementor\Controls_Manager::RAW_HTML,
				'raw'             => esc_html__('Use This Widget in WooCommerce Account Login Register Template.', 'shopready-elementor-addon'),
				'content_classes' => 'woo-ready-account-notice',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'lost_password_section',
			[
				'label' => esc_html__('User register', 'shopready-elementor-addon'),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);



		$this->add_control(
			'woocommerce_registration_generate_username',
			[
				'label'        => esc_html__('Username', 'shopready-elementor-addon'),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'label_on'     => esc_html__('Yes', 'shopready-elementor-addon'),
				'label_off'    => esc_html__('No', 'shopready-elementor-addon'),
				'return_value' => 'yes',
				'default'      => 'yes',
			]
		);

		$this->add_control(
			'woocommerce_registration_generate_password',
			[
				'label'        => esc_html__('Password', 'shopready-elementor-addon'),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'label_on'     => esc_html__('Yes', 'shopready-elementor-addon'),
				'label_off'    => esc_html__('No', 'shopready-elementor-addon'),
				'return_value' => 'yes',
				'default'      => 'yes',
			]
		);

		$this->add_control(
			'generate_pass_message',
			[
				'label'       => esc_html__('Password alert Message', 'shopready-elementor-addon'),
				'type'        => \Elementor\Controls_Manager::TEXTAREA,
				'default'     => esc_html__('A password will be sent to your email address', 'shopready-elementor-addon'),
				'placeholder' => esc_html__('Password reset email has been sent.', 'shopready-elementor-addon'),
				'condition' => [
					'woocommerce_registration_generate_password' => ['']
				],
				'label_block' => true
			]
		);

		$this->add_control(
			'button_text',
			[
				'label'       => esc_html__('Button Text', 'shopready-elementor-addon'),
				'type'        => \Elementor\Controls_Manager::TEXTAREA,
				'default'     => esc_html__('Submit', 'shopready-elementor-addon'),
				'placeholder' => esc_html__('Register', 'shopready-elementor-addon'),
				'label_block' => true
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

		$this->start_controls_section(
			'input_fld_content_section',
			[
				'label' => __('input / label', 'shopready-elementor-addon'),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'show_label',
			[
				'label' => esc_html__('Show Label', 'shopready-elementor-addon'),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => esc_html__('Yes', 'shopready-elementor-addon'),
				'label_off' => esc_html__('No', 'shopready-elementor-addon'),
				'return_value' => 'yes',
				'default' => 'yes',
				'label_block' => true
			]
		);

		$this->add_control(
			'username_label',
			[
				'label' => esc_html__('Username Label', 'shopready-elementor-addon'),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => esc_html__('Username ', 'shopready-elementor-addon'),
				'placeholder' => esc_html__('Type your username label here', 'shopready-elementor-addon'),
				'condition' => [
					'show_label' => ['yes']
				],
				'label_block' => true
			]
		);

		$this->add_control(
			'email_label',
			[
				'label' => esc_html__('Email Label', 'shopready-elementor-addon'),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => esc_html__('Email address', 'shopready-elementor-addon'),
				'placeholder' => esc_html__('Type your email label here', 'shopready-elementor-addon'),
				'condition' => [
					'show_label' => ['yes']
				],
				'label_block' => true
			]
		);

		$this->add_control(
			'password_label',
			[
				'label' => esc_html__('Password Label', 'shopready-elementor-addon'),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => esc_html__('Password', 'shopready-elementor-addon'),
				'placeholder' => esc_html__('Type your password label here', 'shopready-elementor-addon'),
				'condition' => [
					'show_label' => ['yes']
				],
				'label_block' => true
			]
		);

		// placeholder
		$this->add_control(
			'username_placeholder',
			[
				'label' => esc_html__('Username Placeholder', 'shopready-elementor-addon'),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => esc_html__('Username', 'shopready-elementor-addon'),
				'placeholder' => esc_html__('Type your username  here', 'shopready-elementor-addon'),
				'label_block' => true
			]
		);

		$this->add_control(
			'email_placeholder',
			[
				'label' => esc_html__('Email Placeholder', 'shopready-elementor-addon'),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => esc_html__('Email address', 'shopready-elementor-addon'),
				'placeholder' => esc_html__('Type your email  here', 'shopready-elementor-addon'),
				'label_block' => true
			]
		);

		$this->add_control(
			'password_placeholder',
			[
				'label' => esc_html__('Password Placeholder', 'shopready-elementor-addon'),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => esc_html__('Password', 'shopready-elementor-addon'),
				'placeholder' => esc_html__('Type your password here', 'shopready-elementor-addon'),
				'label_block' => true
			]
		);



		$this->end_controls_section();



		$this->box_css(
			[

				'title'        => esc_html__('Email Wrapper', 'shopready-elementor-addon'),
				'slug'         => 'email_box_style',
				'element_name' => 'email__woo_ready__',
				'selector'     => '{{WRAPPER}} .woo-ready-register-email',
				'hover_selector' => false,
				'disable_controls' => [
					'position', 'size'
				]
			]
		);

		$this->input_field(
			[

				'title'          => esc_html__('Username Input', 'shopready-elementor-addon'),
				'slug'           => 'username_input_box_styles',
				'element_name'   => 'usermabtn_linput_woo_ready__',
				'selector'       => '{{WRAPPER}} .woo-ready-register-username input',
				'hover_selector' => '{{WRAPPER}} .woo-ready-register-username input:hover',
				'condition' => [
					'woocommerce_registration_generate_username' => ['yes']
				]
			]
		);

		$this->input_field(
			[

				'title'          => esc_html__('Email Input', 'shopready-elementor-addon'),
				'slug'           => 'email_input_box_styles',
				'element_name'   => 'pemail_linput_woo_ready__',
				'selector'       => '{{WRAPPER}} .woo-ready-register-email input',
				'hover_selector' => '{{WRAPPER}} .woo-ready-register-email input:hover'
			]
		);

		$this->box_css(
			[

				'title'        => esc_html__('Password Wrapper', 'shopready-elementor-addon'),
				'slug'         => 'password_box_style',
				'element_name' => 'password__woo_ready__',
				'selector'     => '{{WRAPPER}} .woo-ready-register-password',
				'condition' => [
					'woocommerce_registration_generate_password' => ['yes']
				],
				'hover_selector' => false,
				'disable_controls' => [
					'position', 'size'
				]
			]
		);

		$this->input_field(
			[

				'title'          => esc_html__('Password Input', 'shopready-elementor-addon'),
				'slug'           => 'password_input_box_styles',
				'element_name'   => 'password_linput_woo_ready__',
				'selector'       => '{{WRAPPER}} .woo-ready-register-password input',
				'hover_selector' => '{{WRAPPER}} .woo-ready-register-password input:hover',
				'condition' => [
					'woocommerce_registration_generate_password' => ['yes']
				]

			]
		);

		$this->text_css(
			[

				'title'        => esc_html__('Password info text', 'shopready-elementor-addon'),
				'slug'         => 'privacy_on_box_style',
				'element_name' => 'privacy_icon_woo_ready__',
				'selector'     => '{{WRAPPER}} .woo-ready-when-password-auto',
				'hover_selector' => false,
				'condition' => [
					'woocommerce_registration_generate_password' => ['']
				]

			]
		);

		$this->text_css(
			[

				'title'        => esc_html__('Username Label', 'shopready-elementor-addon'),
				'slug'         => 'usermname_lbl_box_style',
				'element_name' => 'uname_lbl_woo_ready__',
				'selector'     => '{{WRAPPER}} .woo-ready-register-username label',
				'hover_selector' => false,
				'condition' => [
					'woocommerce_registration_generate_username' => ['yes']
				]

			]
		);

		$this->text_css(
			[

				'title'        => esc_html__('Email Label', 'shopready-elementor-addon'),
				'slug'         => 'email_lbl_style',
				'element_name' => 'email_lbl_woo_ready__',
				'selector'     => '{{WRAPPER}} .woo-ready-register-email label',
				'hover_selector' => false,
				'disable_controls' => [
					'display', 'bg', 'border', 'box-shadow'
				],
			]
		);


		$this->text_css(
			[

				'title'        => esc_html__('Password Label', 'shopready-elementor-addon'),
				'slug'         => 'pass_lbl_box_style',
				'element_name' => 'pass_lblwoo_ready__',
				'selector'     => '{{WRAPPER}} .woo-ready-register-password label',
				'hover_selector' => false,
				'condition' => [
					'woocommerce_registration_generate_password' => ['yes']
				],
				'disable_controls' => [
					'display', 'bg', 'border', 'box-shadow'
				],

			]
		);

		$this->box_css(
			[

				'title'        => esc_html__('Username Wrapper', 'shopready-elementor-addon'),
				'slug'         => 'usr_box_style',
				'element_name' => 'name__woo_ready__',
				'selector'     => '{{WRAPPER}} .woo-ready-register-username',
				'hover_selector' => false,
				'condition' => [
					'woocommerce_registration_generate_username' => ['yes']
				],
				'disable_controls' => [
					'position', 'size'
				]
			]
		);


		$this->box_css(
			[

				'title'        => esc_html__('Button Wrapper', 'shopready-elementor-addon'),
				'slug'         => 'button_wrapper_style',
				'element_name' => 'buton__wrapper_ready__',
				'selector'     => '{{WRAPPER}} .woo-ready-form-register .woo-ready-register-btn-wrp',
				'hover_selector' => false,
				'disable_controls' => [
					'position', 'size', 'dimensions'
				]
			]
		);

		$this->text_css(
			[

				'title'        => esc_html__('Button', 'shopready-elementor-addon'),
				'slug'         => 'btn_i_box_style',
				'element_name' => 'btmn_i_woo_ready__',
				'selector'     => '{{WRAPPER}} .woo-ready-register-btn-wrp button',
				'hover_selector' => '{{WRAPPER}} .woo-ready-register-btn-wrp button:hover',
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
				'selector'     => '{{WRAPPER}} .woo-ready-register-btn-wrp button i',
				'hover_selector' => '{{WRAPPER}} .woo-ready-register-btn-wrp button:hover i',
				'disable_controls' => [
					'display',
				]
			]
		);
	}

	protected function html()
	{

		$settings = $this->get_settings_for_display();

		include('style/register/style1.php');
	}
}
