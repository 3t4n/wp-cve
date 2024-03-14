<?php
/*
 * Elementor Medical Addon for Elementor About Us Widget
 * Author & Copyright: NicheAddon
*/

namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Medical_Elementor_Addon_AboutUs extends Widget_Base{

	/**
	 * Retrieve the widget name.
	*/
	public function get_name(){
		return 'namedical_basic_about_us';
	}

	/**
	 * Retrieve the widget title.
	*/
	public function get_title(){
		return esc_html__( 'About Us', 'medical-addon-for-elementor' );
	}

	/**
	 * Retrieve the widget icon.
	*/
	public function get_icon() {
		return 'eicon-info-circle-o';
	}

	/**
	 * Retrieve the list of categories the widget belongs to.
	*/
	public function get_categories() {
		return ['namedical-basic-category'];
	}

	/**
	 * Register Medical Addon for Elementor About Us widget controls.
	 * Adds different input fields to allow the user to change and customize the widget settings.
	*/
	protected function register_controls(){

		$this->start_controls_section(
			'section_aboutus',
			[
				'label' => __( 'About Us Options', 'medical-addon-for-elementor' ),
			]
		);
		$this->add_responsive_control(
			'content_position',
			[
				'label' => esc_html__( 'Content Position', 'medical-addon-for-elementor' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'unset' => [
						'title' => esc_html__( 'Top', 'medical-addon-for-elementor' ),
						'icon' => 'fa fa-arrow-circle-up',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'medical-addon-for-elementor' ),
						'icon' => 'fa fa-circle',
					],
					'flex-end' => [
						'title' => esc_html__( 'Bottom', 'medical-addon-for-elementor' ),
						'icon' => 'fa fa-arrow-circle-down',
					],
				],
				'default' => 'center',
				'selectors' => [
					'{{WRAPPER}} .namep-aboutus-item' => 'align-items: {{VALUE}};',
				],
			]
		);
		$this->add_control(
			'toggle_align',
			[
				'label' => esc_html__( 'Toggle Align?', 'medical-addon-for-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Yes', 'medical-addon-for-elementor' ),
				'label_off' => esc_html__( 'No', 'medical-addon-for-elementor' ),
				'return_value' => 'true',
			]
		);
		$this->add_control(
			'aboutus_image',
			[
				'label' => esc_html__( 'Upload Image', 'medical-addon-for-elementor' ),
				'type' => Controls_Manager::MEDIA,
				'frontend_available' => true,
				'default' => [
					'url' => Utils::get_placeholder_image_src(),
				],
				'description' => esc_html__( 'Set your image.', 'medical-addon-for-elementor'),
			]
		);
		$this->add_control(
			'need_popup',
			[
				'label' => esc_html__( 'Need Image Popup?', 'medical-addon-for-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Yes', 'medical-addon-for-elementor' ),
				'label_off' => esc_html__( 'No', 'medical-addon-for-elementor' ),
				'return_value' => 'true',
			]
		);
		$this->add_responsive_control(
			'image_alignment',
			[
				'label' => esc_html__( 'Image Alignment', 'medical-addon-for-elementor' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => esc_html__( 'Left', 'medical-addon-for-elementor' ),
						'icon' => 'fa fa-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'medical-addon-for-elementor' ),
						'icon' => 'fa fa-align-center',
					],
					'right' => [
						'title' => esc_html__( 'Right', 'medical-addon-for-elementor' ),
						'icon' => 'fa fa-align-right',
					],
				],
				'default' => 'center',
				'selectors' => [
					'{{WRAPPER}} .namep-aboutus-item .namep-image' => 'text-align: {{VALUE}};',
				],
			]
		);
		$this->add_control(
			'aboutus_title',
			[
				'label' => esc_html__( 'Title Text', 'medical-addon-for-elementor' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'Entrepreneur solutions', 'medical-addon-for-elementor' ),
				'placeholder' => esc_html__( 'Type title text here', 'medical-addon-for-elementor' ),
				'label_block' => true,
			]
		);
		$this->add_control(
			'aboutus_title_link',
			[
				'label' => esc_html__( 'Title Link', 'medical-addon-for-elementor' ),
				'type' => Controls_Manager::URL,
				'placeholder' => 'https://your-link.com',
				'default' => [
					'url' => '',
				],
				'label_block' => true,
			]
		);
		$this->add_control(
			'aboutus_subtitle',
			[
				'label' => esc_html__( 'Sub Title Text', 'medical-addon-for-elementor' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'About Us', 'medical-addon-for-elementor' ),
				'placeholder' => esc_html__( 'Type title text here', 'medical-addon-for-elementor' ),
				'label_block' => true,
			]
		);
		$repeater = new Repeater();
		$repeater->add_control(
			'social_icon',
			[
				'label' => esc_html__( 'Social Icon', 'medical-addon-for-elementor' ),
				'type' => Controls_Manager::ICON,
				'options' => NAMEP_Controls_Helper_Output::get_include_icons(),
				'frontend_available' => true,
				'default' => 'fa fa-facebook-square',
			]
		);
		$repeater->add_control(
			'icon_link',
			[
				'label' => esc_html__( 'Icon Link', 'medical-addon-for-elementor' ),
				'type' => Controls_Manager::URL,
				'placeholder' => 'https://your-link.com',
				'default' => [
					'url' => '',
				],
				'label_block' => true,
			]
		);
		$this->add_control(
			'listItems_groups',
			[
				'label' => esc_html__( 'Social Iocns', 'medical-addon-for-elementor' ),
				'type' => Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
				'title_field' => '{{{ social_icon }}}',
				'prevent_empty' => false,
			]
		);
		$this->add_control(
			'aboutus_content',
			[
				'label' => esc_html__( 'Content', 'medical-addon-for-elementor' ),
				'type' => Controls_Manager::WYSIWYG,
				'label_block' => true,
				'default' => esc_html__( 'This is Content text', 'medical-addon-for-elementor' ),
				'placeholder' => esc_html__( 'Type your content text here', 'medical-addon-for-elementor' ),
			]
		);
		$this->add_control(
			'aboutus_btn_text',
			[
				'label' => esc_html__( 'Button Text', 'medical-addon-for-elementor' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'Read More', 'medical-addon-for-elementor' ),
				'placeholder' => esc_html__( 'Type button text here', 'medical-addon-for-elementor' ),
				'label_block' => true,
			]
		);
		$this->add_control(
			'aboutus_btn_link',
			[
				'label' => esc_html__( 'Button Link', 'medical-addon-for-elementor' ),
				'type' => Controls_Manager::URL,
				'placeholder' => 'https://your-link.com',
				'default' => [
					'url' => '',
				],
				'label_block' => true,
			]
		);
		$this->add_control(
			'aboutus_sign_image',
			[
				'label' => esc_html__( 'Upload Sign', 'medical-addon-for-elementor' ),
				'type' => Controls_Manager::MEDIA,
				'frontend_available' => true,
				'default' => [
					'url' => Utils::get_placeholder_image_src(),
				],
				'description' => esc_html__( 'Set your image.', 'medical-addon-for-elementor'),
			]
		);
		$this->add_responsive_control(
			'content_alignment',
			[
				'label' => esc_html__( 'Content Alignment', 'medical-addon-for-elementor' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => esc_html__( 'Left', 'medical-addon-for-elementor' ),
						'icon' => 'fa fa-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'medical-addon-for-elementor' ),
						'icon' => 'fa fa-align-center',
					],
					'right' => [
						'title' => esc_html__( 'Right', 'medical-addon-for-elementor' ),
						'icon' => 'fa fa-align-right',
					],
				],
				'default' => 'left',
			]
		);
		$this->end_controls_section();// end: Section

		// Style
		// Section
		$this->start_controls_section(
			'section_box_style',
			[
				'label' => esc_html__( 'Section', 'medical-addon-for-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_control(
			'section_padding',
			[
				'label' => __( 'Padding', 'medical-addon-for-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .aboutus-info-wrap' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_control(
			'section_bg_color',
			[
				'label' => esc_html__( 'Background Color', 'medical-addon-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .namep-aboutus-item' => 'background-color: {{VALUE}};',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'section_box_border',
				'label' => esc_html__( 'Border', 'medical-addon-for-elementor' ),
				'selector' => '{{WRAPPER}} .namep-aboutus-item',
			]
		);
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'section_box_shadow',
				'label' => esc_html__( 'Image Box Shadow', 'medical-addon-for-elementor' ),
				'selector' => '{{WRAPPER}} .namep-aboutus-item',
			]
		);
		$this->end_controls_section();// end: Section

		// Image
		$this->start_controls_section(
			'sectn_style',
			[
				'label' => esc_html__( 'Image', 'medical-addon-for-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_responsive_control(
			'aboutus_image_padding',
			[
				'label' => __( 'Padding', 'medical-addon-for-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'selectors' => [
					'{{WRAPPER}} .namep-aboutus-item .namep-image' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_responsive_control(
			'sign_image_padding',
			[
				'label' => __( 'Sign Image Padding', 'medical-addon-for-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'selectors' => [
					'{{WRAPPER}} .namep-aboutus-item .sign-image' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_control(
			'image_border_radius',
			[
				'label' => __( 'Border Radius', 'medical-addon-for-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .namep-aboutus-item .namep-image img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'image_border',
				'label' => esc_html__( 'Border', 'medical-addon-for-elementor' ),
				'selector' => '{{WRAPPER}} .namep-aboutus-item .namep-image img',
			]
		);
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'image_box_shadow',
				'label' => esc_html__( 'Section Box Shadow', 'medical-addon-for-elementor' ),
				'selector' => '{{WRAPPER}} .namep-aboutus-item .namep-image img',
			]
		);
		$this->end_controls_section();// end: Section

		// Title
		$this->start_controls_section(
			'section_title_style',
			[
				'label' => esc_html__( 'Title', 'medical-addon-for-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_control(
			'title_padding',
			[
				'label' => __( 'Padding', 'medical-addon-for-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .aboutus-info h3' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'label' => esc_html__( 'Typography', 'medical-addon-for-elementor' ),
				'name' => 'sasstp_title_typography',
				'selector' => '{{WRAPPER}} .aboutus-info h3',
			]
		);
		$this->start_controls_tabs( 'title_style' );
			$this->start_controls_tab(
				'title_normal',
				[
					'label' => esc_html__( 'Normal', 'medical-addon-for-elementor' ),
				]
			);
			$this->add_control(
				'title_color',
				[
					'label' => esc_html__( 'Color', 'medical-addon-for-elementor' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .aboutus-info h3, {{WRAPPER}} .aboutus-info h3 a' => 'color: {{VALUE}};',
					],
				]
			);
			$this->add_control(
				'title_bdr_color',
				[
					'label' => esc_html__( 'Border Color', 'medical-addon-for-elementor' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .aboutus-info h3:after' => 'background-color: {{VALUE}};',
					],
				]
			);
			$this->end_controls_tab();  // end:Normal tab
			$this->start_controls_tab(
				'title_hover',
				[
					'label' => esc_html__( 'Hover', 'medical-addon-for-elementor' ),
				]
			);
			$this->add_control(
				'title_hover_color',
				[
					'label' => esc_html__( 'Color', 'medical-addon-for-elementor' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .aboutus-info h3 a:hover' => 'color: {{VALUE}};',
					],
				]
			);
			$this->end_controls_tab();  // end:Hover tab
		$this->end_controls_tabs(); // end tabs
		$this->end_controls_section();// end: Section

		// Sub Title
		$this->start_controls_section(
			'section_subtitle_style',
			[
				'label' => esc_html__( 'Sub Title', 'medical-addon-for-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_control(
			'subtitle_padding',
			[
				'label' => __( 'Padding', 'medical-addon-for-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .aboutus-info h5' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'label' => esc_html__( 'Typography', 'medical-addon-for-elementor' ),
				'name' => 'subtitle_typography',
				'selector' => '{{WRAPPER}} .aboutus-info h5',
			]
		);
		$this->add_control(
			'subtitle_color',
			[
				'label' => esc_html__( 'Color', 'medical-addon-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .aboutus-info h5' => 'color: {{VALUE}};',
				],
			]
		);
		$this->end_controls_section();// end: Section

		// Content
		$this->start_controls_section(
			'section_content_style',
			[
				'label' => esc_html__( 'Content', 'medical-addon-for-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_control(
			'content_padding',
			[
				'label' => __( 'Padding', 'medical-addon-for-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .aboutus-info p' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'label' => esc_html__( 'Typography', 'medical-addon-for-elementor' ),
				'name' => 'content_typography',
				'selector' => '{{WRAPPER}} .aboutus-info p',
			]
		);
		$this->add_control(
			'content_color',
			[
				'label' => esc_html__( 'Color', 'medical-addon-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .aboutus-info p' => 'color: {{VALUE}};',
				],
			]
		);
		$this->end_controls_section();// end: Section

		// Icon
		$this->start_controls_section(
			'section_icon_style',
			[
				'label' => esc_html__( 'Social Icons', 'medical-addon-for-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_control(
			'icon_border_radius',
			[
				'label' => __( 'Border Radius', 'medical-addon-for-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .namep-social a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->start_controls_tabs( 'icon_style' );
			$this->start_controls_tab(
					'icon_normal',
					[
						'label' => esc_html__( 'Normal', 'medical-addon-for-elementor' ),
					]
				);
			$this->add_control(
				'icon_color',
				[
					'label' => esc_html__( 'Color', 'medical-addon-for-elementor' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .namep-social a' => 'color: {{VALUE}};',
					],
				]
			);
			$this->add_control(
				'icon_bg',
				[
					'label' => esc_html__( 'Background Color', 'medical-addon-for-elementor' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .namep-social a' => 'background-color: {{VALUE}};',
					],
				]
			);
			$this->add_group_control(
				Group_Control_Border::get_type(),
				[
					'name' => 'icon_border',
					'label' => esc_html__( 'Border', 'medical-addon-for-elementor' ),
					'selector' => '{{WRAPPER}} .namep-social a',
				]
			);
			$this->end_controls_tab();  // end:Normal tab
			$this->start_controls_tab(
				'icon_hover',
				[
					'label' => esc_html__( 'Hover', 'medical-addon-for-elementor' ),
				]
			);
			$this->add_control(
				'icon_hover_color',
				[
					'label' => esc_html__( 'Color', 'medical-addon-for-elementor' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .namep-social a:hover' => 'color: {{VALUE}};',
					],
				]
			);
			$this->add_control(
				'icon_bg_hov',
				[
					'label' => esc_html__( 'Background Hover Color', 'medical-addon-for-elementor' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .namep-social a:hover' => 'background-color: {{VALUE}};',
					],
				]
			);
			$this->add_group_control(
				Group_Control_Border::get_type(),
				[
					'name' => 'icon_hover_border',
					'label' => esc_html__( 'Border', 'medical-addon-for-elementor' ),
					'selector' => '{{WRAPPER}} .namep-social a:hover',
				]
			);
			$this->end_controls_tab();  // end:Hover tab
		$this->end_controls_tabs(); // end tabs

		$this->add_responsive_control(
			'icon_size',
			[
				'label' => esc_html__( 'Size', 'medical-addon-for-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1000,
						'step' => 1,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .namep-social a' => 'font-size:{{SIZE}}{{UNIT}};',
				],
			]
		);
		$this->add_responsive_control(
			'icon_width',
			[
				'label' => esc_html__( 'Width', 'medical-addon-for-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1000,
						'step' => 1,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .namep-social a' => 'width:{{SIZE}}{{UNIT}};height:{{SIZE}}{{UNIT}};line-height:{{SIZE}}{{UNIT}};',
				],
			]
		);
		$this->add_control(
			'icon_margin',
			[
				'label' => __( 'Margin', 'medical-addon-for-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .namep-social a' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->end_controls_section();// end: Section

		// Link
		$this->start_controls_section(
			'section_btn_style',
			[
				'label' => esc_html__( 'Link', 'medical-addon-for-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_control(
			'link_padding',
			[
				'label' => __( 'Padding', 'medical-addon-for-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .namep-link-wrap' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'label' => esc_html__( 'Typography', 'medical-addon-for-elementor' ),
				'name' => 'btn_typography',
				'selector' => '{{WRAPPER}} .namep-link',
			]
		);
		$this->start_controls_tabs( 'btn_style' );
			$this->start_controls_tab(
				'btn_normal',
				[
					'label' => esc_html__( 'Normal', 'medical-addon-for-elementor' ),
				]
			);
			$this->add_control(
				'btn_color',
				[
					'label' => esc_html__( 'Color', 'medical-addon-for-elementor' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .namep-link' => 'color: {{VALUE}};',
					],
				]
			);
			$this->end_controls_tab();  // end:Normal tab
			$this->start_controls_tab(
				'btn_hover',
				[
					'label' => esc_html__( 'Hover', 'medical-addon-for-elementor' ),
				]
			);
			$this->add_control(
				'btn_hover_color',
				[
					'label' => esc_html__( 'Color', 'medical-addon-for-elementor' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .namep-link:hover' => 'color: {{VALUE}};',
					],
				]
			);
			$this->add_control(
				'btn_bg_hover_color',
				[
					'label' => esc_html__( 'Line Color', 'medical-addon-for-elementor' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .namep-link:before' => 'background-color: {{VALUE}};',
					],
				]
			);
			$this->end_controls_tab();  // end:Hover tab
		$this->end_controls_tabs(); // end tabs
		$this->end_controls_section();// end: Section

	}

	/**
	 * Render App Works widget output on the frontend.
	 * Written in PHP and used to generate the final HTML.
	*/
	protected function render() {
		$settings = $this->get_settings_for_display();
		$aboutus_image = !empty( $settings['aboutus_image']['id'] ) ? $settings['aboutus_image']['id'] : '';
		$aboutus_title = !empty( $settings['aboutus_title'] ) ? $settings['aboutus_title'] : '';
		$aboutus_title_link = !empty( $settings['aboutus_title_link']['url'] ) ? $settings['aboutus_title_link']['url'] : '';
		$aboutus_title_link_external = !empty( $settings['aboutus_title_link']['is_external'] ) ? 'target="_blank"' : '';
		$aboutus_title_link_nofollow = !empty( $settings['aboutus_title_link']['nofollow'] ) ? 'rel="nofollow"' : '';
		$aboutus_title_link_attr = !empty( $aboutus_title_link ) ?  $aboutus_title_link_external.' '.$aboutus_title_link_nofollow : '';
		$aboutus_subtitle = !empty( $settings['aboutus_subtitle'] ) ? $settings['aboutus_subtitle'] : '';
		$aboutus_content = !empty( $settings['aboutus_content'] ) ? $settings['aboutus_content'] : '';
		$aboutus_btn_text = !empty( $settings['aboutus_btn_text'] ) ? $settings['aboutus_btn_text'] : '';
		$aboutus_btn_link = !empty( $settings['aboutus_btn_link']['url'] ) ? $settings['aboutus_btn_link']['url'] : '';
		$aboutus_btn_link_external = !empty( $settings['aboutus_btn_link']['is_external'] ) ? 'target="_blank"' : '';
		$aboutus_btn_link_nofollow = !empty( $settings['aboutus_btn_link']['nofollow'] ) ? 'rel="nofollow"' : '';
		$aboutus_btn_link_attr = !empty( $aboutus_btn_link ) ?  $aboutus_btn_link_external.' '.$aboutus_btn_link_nofollow : '';
		$listItems_groups = !empty( $settings['listItems_groups'] ) ? $settings['listItems_groups'] : '';
		$toggle_align = !empty( $settings['toggle_align'] ) ? $settings['toggle_align'] : '';
		$aboutus_sign_image = !empty( $settings['aboutus_sign_image']['id'] ) ? $settings['aboutus_sign_image']['id'] : '';
		$need_popup = !empty( $settings['need_popup'] ) ? $settings['need_popup'] : '';
		$content_alignment = !empty( $settings['content_alignment'] ) ? $settings['content_alignment'] : '';

		if ($toggle_align) {
			$f_class = ' nich-order-1';
			$s_class = ' nich-order-2';
		} else {
			$f_class = '';
			$s_class = '';
		}

		if ($content_alignment === 'center') {
			$align_class = ' center';
		} elseif ($content_alignment === 'right') {
			$align_class = ' right';
		} else {
			$align_class = '';
		}

		// Image
		$image_url = wp_get_attachment_url( $aboutus_image );
		if ($need_popup) {
			$popup_class = ' namep-popup';
			$popup_image = '<a href="'. esc_url($image_url) .'"><img src="'.esc_url($image_url).'" alt="'.esc_attr($aboutus_title).'"></a>';
		} else {
			$popup_class = '';
			$popup_image = '<img src="'.esc_url($image_url).'" alt="'.esc_attr($aboutus_title).'">';
		}

		$image = $image_url ? '<div class="namep-image'.esc_attr($popup_class).'">'.$popup_image.'</div>' : '';

		$sign_url = wp_get_attachment_url( $aboutus_sign_image );
		$sign_image = $sign_url ? '<div class="sign-image"><img src="'.esc_url($sign_url).'" alt="'.esc_attr($aboutus_title).'"></div>' : '';

		$title_link = $aboutus_title_link ? '<a href="'.esc_url($aboutus_title_link).'" '.$aboutus_title_link_attr.'>'.esc_html($aboutus_title).'</a>' : esc_html($aboutus_title);
		$title = $aboutus_title ? '<h3 class="aboutus-title">'.$title_link.'</h3>' : '';
		$subtitle = $aboutus_subtitle ? '<h5>'.esc_html($aboutus_subtitle).'</h5>' : '';
		$content = $aboutus_content ? $aboutus_content : '';
		$aboutus_btn = $aboutus_btn_link ? '<div class="namep-link-wrap"><a href="'.esc_url($aboutus_btn_link).'" class="namep-link" '.$aboutus_btn_link_attr.'>'.esc_html($aboutus_btn_text).'</a></div>' : '';

		$output = '<div class="namep-aboutus-item">
								<div class="aboutus-image'.esc_attr($s_class).'">'.$image.'</div>
								<div class="aboutus-info'.esc_attr($f_class.$align_class).'"><div class="aboutus-info-wrap">
									'.$subtitle.$title;
		$output .= $content;
									// Group Param Output
									if ( is_array( $listItems_groups ) && !empty( $listItems_groups ) ){
										$output .= '<div class="namep-social rounded">';
									  foreach ( $listItems_groups as $each_list ) {
									  $icon_link = !empty( $each_list['icon_link'] ) ? $each_list['icon_link'] : '';

										$link_url = !empty( $icon_link['url'] ) ? esc_url($icon_link['url']) : '';
										$link_external = !empty( $icon_link['is_external'] ) ? 'target="_blank"' : '';
										$link_nofollow = !empty( $icon_link['nofollow'] ) ? 'rel="nofollow"' : '';
										$link_attr = !empty( $icon_link['url'] ) ?  $link_external.' '.$link_nofollow : '';

									  $social_icon = !empty( $each_list['social_icon'] ) ? $each_list['social_icon'] : '';
										$icon = $social_icon ? '<i class="'.esc_attr($social_icon).'" aria-hidden="true"></i>' : '';

									  $output .= '<a href="'.esc_url($link_url).'" '.$link_attr.'>'.$icon.'</a>';
										}
										$output .= '</div>';
									}
		$output .= $aboutus_btn.$sign_image.'</div>
							</div>
						</div>';
		echo $output;

	}

}
Plugin::instance()->widgets_manager->register_widget_type( new Medical_Elementor_Addon_AboutUs() );
