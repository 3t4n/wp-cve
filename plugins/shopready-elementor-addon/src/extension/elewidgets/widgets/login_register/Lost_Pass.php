<?php

namespace Shop_Ready\extension\elewidgets\widgets\login_register;

use Shop_Ready\base\elementor\style_controls\common\Widget_Form;

class Lost_Pass extends \Shop_Ready\extension\elewidgets\Widget_Base
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
				'default' => 'woo-ready-lost-pass-preset-1',
				'options' => [
					'woo-ready-lost-pass-preset-1'  => __('Preset 1', 'shopready-elementor-addon'),

				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'lost_password_section',
			[
				'label' => esc_html__('Lost Password', 'shopready-elementor-addon'),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'lost_password_title',
			[
				'label'       => esc_html__('Label', 'shopready-elementor-addon'),
				'type'        => \Elementor\Controls_Manager::TEXT,
				'default'     => esc_html__('Lost Password ?', 'shopready-elementor-addon'),
				'placeholder' => esc_html__('Type your lost password label here', 'shopready-elementor-addon'),
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
			'lost_password_form',
			[
				'label' => esc_html__('Form', 'shopready-elementor-addon'),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'show_form_in_editor',
			[
				'label' => __('Show form in editor', 'shopready-elementor-addon'),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => __('Show', 'shopready-elementor-addon'),
				'label_off' => __('Hide', 'shopready-elementor-addon'),
				'return_value' => 'yes',
				'default' => 'yes',
			]
		);


		$this->add_control(
			'lost_password_input_label',
			[
				'label'       => esc_html__('Username Field', 'shopready-elementor-addon'),
				'type'        => \Elementor\Controls_Manager::TEXT,
				'default'     => esc_html__('Username or Email', 'shopready-elementor-addon'),
				'placeholder' => esc_html__('Type your username label here', 'shopready-elementor-addon'),
			]
		);

		$this->add_control(
			'lost_password_btn_label',
			[
				'label'       => esc_html__('Button Label', 'shopready-elementor-addon'),
				'type'        => \Elementor\Controls_Manager::TEXTAREA,
				'default'     => esc_html__('Reset Password', 'shopready-elementor-addon'),
				'placeholder' => esc_html__('Type your button text here', 'shopready-elementor-addon'),
			]
		);

		$this->add_control(
			'lost_password_form_msg',
			[
				'label'       => esc_html__('Heading Content', 'shopready-elementor-addon'),
				'type'        => \Elementor\Controls_Manager::TEXTAREA,
				'default'     => esc_html__('Lost your password? Please enter your username or email address. You will receive a link to create a new password via email.', 'shopready-elementor-addon'),
				'placeholder' => esc_html__('Type your Heading content here', 'shopready-elementor-addon'),
			]
		);

		$this->end_controls_section();


		$this->box_css(

			[
				'title'        => esc_html__('Link Wrapper', 'shopready-elementor-addon'),
				'slug'         => 'woo_ready_lpass_style',
				'element_name' => 's__woo_ready__',
				'selector'     => '{{WRAPPER}} .woo-ready-LostPassword',
				'disable_controls' => [
					'position', 'display', 'bg', 'box-shadow', 'border', 'dimensions', 'size'
				]
			]

		);

		$this->text_wrapper_css(
			[
				'title'        => esc_html__('Password Link', 'shopready-elementor-addon'),
				'slug'         => 'woo_ready_lpassl',
				'element_name' => 's__woo_ready__',
				'selector'     => '{{WRAPPER}} .woo-ready-LostPassword a.woo-ready-lpass-link',
				'hover_selector'     => '{{WRAPPER}} .woo-ready-LostPassword a.woo-ready-lpass-link:hover',
				'disable_controls' => [
					'position', 'display', 'alignment', 'bg', 'box-shadow'
				]
			]
		);

		$this->text_minimum_css(
			[

				'title'        => esc_html__('Icon', 'shopready-elementor-addon'),
				'slug'         => 'btn_icon_box_style',
				'element_name' => 'name_icon_woo_ready__',
				'selector'     => '{{WRAPPER}} .woo-ready-LostPassword i',
				'hover_selector' => '{{WRAPPER}} .woo-ready-LostPassword a.woo-ready-lpass-link:hover i',
				'disable_controls' => [
					'display', 'border', 'bg'
				]
			]
		);

		$this->text_css(
			[

				'title'        => esc_html__('Form Heading', 'shopready-elementor-addon'),
				'slug'         => 'hading_box_style',
				'element_name' => 'heaidng_woo_ready__',
				'selector'     => '{{WRAPPER}} .woo-ready-lpass-heading',
				'hover_selector' => false,
				'condition' => [
					'show_form_in_editor' => ['yes']
				],
				'disable_controls' => [
					'display', 'border', 'bg'
				]
			]
		);

		$this->text_minimum_css(
			[

				'title'        => esc_html__('Input Label', 'shopready-elementor-addon'),
				'slug'         => 'inputs_label_style',
				'element_name' => 'input_s_woo_ready__',
				'selector'     => '{{WRAPPER}} .woo-ready-form-label label',
				'hover_selector' => false,
				'condition' => [
					'show_form_in_editor' => ['yes']
				],
				'disable_controls' => [
					'display', 'border', 'bg'
				]
			]
		);

		$this->input_field(
			[

				'title'          => esc_html__('Input', 'shopready-elementor-addon'),
				'slug'           => 'user_input_box_styles',
				'element_name'   => 'uname_linput_woo_ready__',
				'selector'       => '{{WRAPPER}} .woo-ready-form-username input',
				'hover_selector' => '{{WRAPPER}} .woo-ready-form-username input:focus',
				'condition' => [
					'show_form_in_editor' => ['yes']
				]

			]
		);

		$this->box_css(
			[

				'title'          => esc_html__('Button Wrapper', 'shopready-elementor-addon'),
				'slug'           => 'btn_input_box_styles',
				'element_name'   => 'btn_linput_woo_ready__',
				'selector'       => '{{WRAPPER}} .woo-ready-form-btn',
				'hover_selector' => false,
				'condition' => [
					'show_form_in_editor' => ['yes']
				],
				'disable_controls' => [
					'display', 'position', 'size', 'dimensions', 'bg', 'border', 'box-shadow'
				]
			]
		);


		$this->text_wrapper_css(
			[

				'title'          => esc_html__('Button', 'shopready-elementor-addon'),
				'slug'           => 'buttons_label_box_style',
				'element_name'   => 'buttons_label_woo_ready__',
				'selector'       => '{{WRAPPER}} .woo-ready-form-btn button',
				'hover_selector' => '{{WRAPPER}} .woo-ready-form-btn button:hover',
				'condition' => [
					'show_form_in_editor' => ['yes']
				],
				'disable_controls' => [
					'display', 'position',
				]
			]
		);

		$this->box_css(
			[

				'title'        => esc_html__('Form Area', 'shopready-elementor-addon'),
				'slug'         => 'fmain_box_style',
				'element_name' => 'form_woo_ready__',
				'selector'     => '{{WRAPPER}} .woo-ready-lost-reset-password',
				'condition' => [
					'show_form_in_editor' => ['yes']
				],
				'disable_controls' => [
					'size', 'position',
				]
			]
		);

		// $this->text_css(
		// 	[

		// 		'title'        => esc_html__('Error Notice', 'shopready-elementor-addon'),
		// 		'slug'         => 'notice_wr_box_style',
		// 		'element_name' => 'notice_woo_ready__',
		// 		'selector'     => '{{WRAPPER}} ul.woocommerce-error',
		// 		'disable_controls' => [
		// 			'size', 'position',
		// 		]
		// 	]
		// );

		// $this->text_css(
		// 	[

		// 		'title'        => esc_html__('Success Notice', 'shopready-elementor-addon'),
		// 		'slug'         => 'notice_suc_box_style',
		// 		'element_name' => 'notice_suc_ready__',
		// 		'selector'     => '{{WRAPPER}} .woocommerce-message',
		// 		'disable_controls' => [
		// 			'size', 'position',
		// 		]
		// 	]
		// );
	}


	protected function html()
	{

		$settings = $this->get_settings_for_display();

		include('style/lost_pass/style1.php');
	}
}
