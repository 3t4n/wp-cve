<?php
/*
 * Elementor Medical Addon for Elementor Get Apps Widget
 * Author & Copyright: NicheAddon
*/

namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Medical_Elementor_Addon_GetApps extends Widget_Base{

	/**
	 * Retrieve the widget name.
	*/
	public function get_name(){
		return 'namedical_basic_get_apps';
	}

	/**
	 * Retrieve the widget title.
	*/
	public function get_title(){
		return esc_html__( 'Get Apps', 'medical-addon-for-elementor' );
	}

	/**
	 * Retrieve the widget icon.
	*/
	public function get_icon() {
		return 'eicon-apps';
	}

	/**
	 * Retrieve the list of categories the widget belongs to.
	*/
	public function get_categories() {
		return ['namedical-basic-category'];
	}

	/**
	 * Register Medical Addon for Elementor Get Apps widget controls.
	 * Adds different input fields to allow the user to change and customize the widget settings.
	*/
	protected function register_controls(){

		$this->start_controls_section(
			'section_apps',
			[
				'label' => __( 'Get Apps Options', 'medical-addon-for-elementor' ),
			]
		);
		$this->add_control(
			'apps_title',
			[
				'label' => esc_html__( 'Title Text', 'medical-addon-for-elementor' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'Download our app today!', 'medical-addon-for-elementor' ),
				'placeholder' => esc_html__( 'Type title text here', 'medical-addon-for-elementor' ),
				'label_block' => true,
			]
		);
		$this->add_control(
			'apps_subtitle',
			[
				'label' => esc_html__( 'Sub Title Text', 'medical-addon-for-elementor' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'Get Apps', 'medical-addon-for-elementor' ),
				'placeholder' => esc_html__( 'Type title text here', 'medical-addon-for-elementor' ),
				'label_block' => true,
			]
		);
		$this->add_control(
			'apps_content',
			[
				'label' => esc_html__( 'Content', 'medical-addon-for-elementor' ),
				'type' => Controls_Manager::TEXTAREA,
				'label_block' => true,
				'default' => esc_html__( 'We combine practice of managing and analyzing marketing performance to maximize its effectiveness.', 'medical-addon-for-elementor' ),
				'placeholder' => esc_html__( 'Type your content text here', 'medical-addon-for-elementor' ),
			]
		);
		$repeater = new Repeater();

		$repeater->add_control(
			'btn_style',
			[
				'label' => esc_html__( 'Button Style', 'medical-addon-for-elementor' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'one' => esc_html__( 'Style One (Button)', 'medical-addon-for-elementor' ),
					'two' => esc_html__( 'Style Two (Image)', 'medical-addon-for-elementor' ),
					'three' => esc_html__( 'Style Three (Link)', 'medical-addon-for-elementor' ),
				],
				'default' => 'one',
				'description' => esc_html__( 'Select your button style.', 'medical-addon-for-elementor' ),

			]
		);
		$repeater->add_control(
			'image_style',
			[
				'label' => esc_html__( 'Image Style', 'medical-addon-for-elementor' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'google' => esc_html__( 'Google Play', 'medical-addon-for-elementor' ),
					'apple' => esc_html__( 'Apple Store', 'medical-addon-for-elementor' ),
					'chrome' => esc_html__( 'Chrome Store', 'medical-addon-for-elementor' ),
					'select' => esc_html__( 'Select Image', 'medical-addon-for-elementor' ),
				],
				'default' => 'google',
				'condition' => [
					'btn_style' => array('two'),
				],
				'description' => esc_html__( 'Select your image style.', 'medical-addon-for-elementor' ),
			]
		);
		$repeater->add_control(
			'retina_img',
			[
				'label' => esc_html__( 'Retina Image?', 'medical-addon-for-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Yes', 'medical-addon-for-elementor' ),
				'label_off' => esc_html__( 'No', 'medical-addon-for-elementor' ),
				'return_value' => 'true',
				'condition' => [
					'btn_style' => array('two'),
				],
				'description' => esc_html__( 'If you want to resize your retina image, enable it.', 'medical-addon-for-elementor' ),
			]
		);
		$repeater->add_control(
			'google_image',
			[
				'label' => esc_html__( 'Button Image', 'medical-addon-for-elementor' ),
				'type' => Controls_Manager::MEDIA,
				'frontend_available' => true,
				'default' => [
					'url' => NAMEP_PLUGIN_IMGS.'/google.png',
				],
				'description' => esc_html__( 'Set your image.', 'medical-addon-for-elementor'),
				'condition' => [
					'btn_style' => array('two'),
					'image_style' => array('google'),
				],
			]
		);
		$repeater->add_control(
			'apple_image',
			[
				'label' => esc_html__( 'Button Image', 'medical-addon-for-elementor' ),
				'type' => Controls_Manager::MEDIA,
				'frontend_available' => true,
				'default' => [
					'url' => NAMEP_PLUGIN_IMGS.'/apple.png',
				],
				'description' => esc_html__( 'Set your image.', 'medical-addon-for-elementor'),
				'condition' => [
					'btn_style' => array('two'),
					'image_style' => array('apple'),
				],
			]
		);
		$repeater->add_control(
			'chrome_image',
			[
				'label' => esc_html__( 'Button Image', 'medical-addon-for-elementor' ),
				'type' => Controls_Manager::MEDIA,
				'frontend_available' => true,
				'default' => [
					'url' => NAMEP_PLUGIN_IMGS.'/chrome.png',
				],
				'description' => esc_html__( 'Set your image.', 'medical-addon-for-elementor'),
				'condition' => [
					'btn_style' => array('two'),
					'image_style' => array('chrome'),
				],
			]
		);
		$repeater->add_control(
			'btn_image',
			[
				'label' => esc_html__( 'Button Image', 'medical-addon-for-elementor' ),
				'type' => Controls_Manager::MEDIA,
				'frontend_available' => true,
				'default' => [
					'url' => '',
				],
				'description' => esc_html__( 'Set your image.', 'medical-addon-for-elementor'),
				'condition' => [
					'btn_style' => array('two'),
					'image_style' => array('select'),
				],
			]
		);
		$repeater->add_control(
			'btn_text',
			[
				'label' => esc_html__( 'Button Text', 'medical-addon-for-elementor' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'Get Apps', 'medical-addon-for-elementor' ),
				'placeholder' => esc_html__( 'Type title text here', 'medical-addon-for-elementor' ),
				'label_block' => true,
				'condition' => [
					'btn_style!' => array('two'),
				],
			]
		);
		$repeater->add_control(
			'btn_icon',
			[
				'label' => esc_html__( 'Button Icon', 'medical-addon-for-elementor' ),
				'type' => Controls_Manager::ICON,
				'options' => NAMEP_Controls_Helper_Output::get_include_icons(),
				'frontend_available' => true,
				'default' => 'fa fa-android',
				'condition' => [
					'btn_style!' => array('two'),
				],
			]
		);
		$repeater->add_control(
			'btn_link',
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
				'label' => esc_html__( 'Buttons', 'medical-addon-for-elementor' ),
				'type' => Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
				'title_field' => '{{{ btn_text }}}',
				'prevent_empty' => false,
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
				'default' => 'center',
				'selectors' => [
					'{{WRAPPER}} .namep-get-apps' => 'text-align: {{VALUE}};',
				],
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
			$this->add_responsive_control(
				'section_width',
				[
					'label' => esc_html__( 'Section Width', 'medical-addon-for-elementor' ),
					'type' => Controls_Manager::SLIDER,
					'range' => [
						'px' => [
							'min' => 0,
							'max' => 1500,
							'step' => 1,
						],
						'%' => [
							'min' => 0,
							'max' => 100,
						],
					],
					'size_units' => [ 'px', '%' ],
					'selectors' => [
						'{{WRAPPER}} .namep-get-apps' => 'max-width:{{SIZE}}{{UNIT}};',
					],
				]
			);
			$this->add_control(
				'section_padding',
				[
					'label' => __( 'Padding', 'medical-addon-for-elementor' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%', 'em' ],
					'selectors' => [
						'{{WRAPPER}} .namep-get-apps' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);
			$this->add_control(
				'section_bg_color',
				[
					'label' => esc_html__( 'Background Color', 'medical-addon-for-elementor' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .namep-get-apps' => 'background-color: {{VALUE}};',
					],
				]
			);
			$this->add_control(
				'section_border_radius',
				[
					'label' => __( 'Border Radius', 'medical-addon-for-elementor' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%', 'em' ],
					'selectors' => [
						'{{WRAPPER}} .namep-get-apps' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);
			$this->add_group_control(
				Group_Control_Border::get_type(),
				[
					'name' => 'section_box_border',
					'label' => esc_html__( 'Border', 'medical-addon-for-elementor' ),
					'selector' => '{{WRAPPER}} .namep-get-apps',
				]
			);
			$this->add_group_control(
				Group_Control_Box_Shadow::get_type(),
				[
					'name' => 'section_box_shadow',
					'label' => esc_html__( 'Box Shadow', 'medical-addon-for-elementor' ),
					'selector' => '{{WRAPPER}} .namep-get-apps',
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
						'{{WRAPPER}} .namep-get-apps h3' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);
			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'label' => esc_html__( 'Typography', 'medical-addon-for-elementor' ),
					'name' => 'sasstp_title_typography',
					'selector' => '{{WRAPPER}} .namep-get-apps h3',
				]
			);
			$this->add_control(
				'title_color',
				[
					'label' => esc_html__( 'Color', 'medical-addon-for-elementor' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .namep-get-apps h3' => 'color: {{VALUE}};',
					],
				]
			);
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
						'{{WRAPPER}} .namep-get-apps h5' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);
			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'label' => esc_html__( 'Typography', 'medical-addon-for-elementor' ),
					'name' => 'subtitle_typography',
					'selector' => '{{WRAPPER}} .namep-get-apps h5',
				]
			);
			$this->add_control(
				'subtitle_color',
				[
					'label' => esc_html__( 'Color', 'medical-addon-for-elementor' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .namep-get-apps h5' => 'color: {{VALUE}};',
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
						'{{WRAPPER}} .namep-get-apps p' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);
			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'label' => esc_html__( 'Typography', 'medical-addon-for-elementor' ),
					'name' => 'content_typography',
					'selector' => '{{WRAPPER}} .namep-get-apps p',
				]
			);
			$this->add_control(
				'content_color',
				[
					'label' => esc_html__( 'Color', 'medical-addon-for-elementor' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .namep-get-apps p' => 'color: {{VALUE}};',
					],
				]
			);
			$this->end_controls_section();// end: Section

		// Button
			$this->start_controls_section(
				'section_btn_style',
				[
					'label' => esc_html__( 'Button', 'medical-addon-for-elementor' ),
					'tab' => Controls_Manager::TAB_STYLE,
				]
			);
			$this->add_responsive_control(
				'btn_margin',
				[
					'label' => __( 'Margin', 'medical-addon-for-elementor' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px' ],
					'selectors' => [
						'{{WRAPPER}} .namep-get-apps .namep-btn-wrap a' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);
			$this->add_control(
				'btn_border_radius',
				[
					'label' => __( 'Border Radius', 'medical-addon-for-elementor' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%', 'em' ],
					'selectors' => [
						'{{WRAPPER}} .namep-btn' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);
			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'label' => esc_html__( 'Typography', 'medical-addon-for-elementor' ),
					'name' => 'btn_typography',
					'selector' => '{{WRAPPER}} .namep-btn',
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
							'{{WRAPPER}} .namep-btn' => 'color: {{VALUE}};',
						],
					]
				);
				$this->add_control(
					'btn_bg_color',
					[
						'label' => esc_html__( 'Background Color', 'medical-addon-for-elementor' ),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .namep-btn' => 'background-color: {{VALUE}};',
						],
					]
				);
				$this->add_group_control(
					Group_Control_Border::get_type(),
					[
						'name' => 'btn_border',
						'label' => esc_html__( 'Border', 'medical-addon-for-elementor' ),
						'selector' => '{{WRAPPER}} .namep-btn',
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
							'{{WRAPPER}} .namep-btn:hover' => 'color: {{VALUE}};',
						],
					]
				);
				$this->add_control(
					'btn_bg_hover_color',
					[
						'label' => esc_html__( 'Background Color', 'medical-addon-for-elementor' ),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .namep-btn:hover' => 'background-color: {{VALUE}};',
						],
					]
				);
				$this->add_group_control(
					Group_Control_Border::get_type(),
					[
						'name' => 'btn_hover_border',
						'label' => esc_html__( 'Border', 'medical-addon-for-elementor' ),
						'selector' => '{{WRAPPER}} .namep-btn:hover',
					]
				);
				$this->end_controls_tab();  // end:Hover tab
			$this->end_controls_tabs(); // end tabs
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
				'image_width',
				[
					'label' => esc_html__( 'Image Width', 'medical-addon-for-elementor' ),
					'type' => Controls_Manager::SLIDER,
					'range' => [
						'px' => [
							'min' => 0,
							'max' => 1500,
							'step' => 1,
						],
						'%' => [
							'min' => 0,
							'max' => 100,
						],
					],
					'size_units' => [ 'px', '%' ],
					'selectors' => [
						'{{WRAPPER}} .namep-get-apps .namep-btn-wrap a img' => 'max-width:{{SIZE}}{{UNIT}};',
					],
				]
			);
			$this->add_responsive_control(
				'btn_image_margin',
				[
					'label' => __( 'Margin', 'medical-addon-for-elementor' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px' ],
					'selectors' => [
						'{{WRAPPER}} .namep-get-apps .namep-btn-wrap a' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
						'{{WRAPPER}} .namep-get-apps .namep-btn-wrap a img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);
			$this->add_group_control(
				Group_Control_Border::get_type(),
				[
					'name' => 'image_border',
					'label' => esc_html__( 'Border', 'medical-addon-for-elementor' ),
					'selector' => '{{WRAPPER}} .namep-get-apps .namep-btn-wrap a img',
				]
			);
			$this->add_group_control(
				Group_Control_Box_Shadow::get_type(),
				[
					'name' => 'image_box_shadow',
					'label' => esc_html__( 'Section Box Shadow', 'medical-addon-for-elementor' ),
					'selector' => '{{WRAPPER}} .namep-get-apps .namep-btn-wrap a img',
				]
			);
			$this->end_controls_section();// end: Section

		// Link
			$this->start_controls_section(
				'section_link_style',
				[
					'label' => esc_html__( 'Link', 'medical-addon-for-elementor' ),
					'tab' => Controls_Manager::TAB_STYLE,
				]
			);
			$this->add_responsive_control(
				'link_margin',
				[
					'label' => __( 'Margin', 'medical-addon-for-elementor' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px' ],
					'selectors' => [
						'{{WRAPPER}} .namep-get-apps .namep-btn-wrap a' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
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
					'name' => 'link_typography',
					'selector' => '{{WRAPPER}} .namep-link',
				]
			);
			$this->start_controls_tabs( 'link_style' );
				$this->start_controls_tab(
					'link_normal',
					[
						'label' => esc_html__( 'Normal', 'medical-addon-for-elementor' ),
					]
				);
				$this->add_control(
					'link_color',
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
					'link_hover',
					[
						'label' => esc_html__( 'Hover', 'medical-addon-for-elementor' ),
					]
				);
				$this->add_control(
					'link_hover_color',
					[
						'label' => esc_html__( 'Color', 'medical-addon-for-elementor' ),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .namep-link:hover' => 'color: {{VALUE}};',
						],
					]
				);
				$this->add_control(
					'link_bg_hover_color',
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
		$apps_title = !empty( $settings['apps_title'] ) ? $settings['apps_title'] : '';
		$apps_subtitle = !empty( $settings['apps_subtitle'] ) ? $settings['apps_subtitle'] : '';
		$apps_content = !empty( $settings['apps_content'] ) ? $settings['apps_content'] : '';

		$listItems_groups = !empty( $settings['listItems_groups'] ) ? $settings['listItems_groups'] : '';

		$title = $apps_title ? '<h3 class="apps-title">'.esc_html($apps_title).'</h3>' : '';
		$subtitle = $apps_subtitle ? '<h5>'.esc_html($apps_subtitle).'</h5>' : '';
		$content = $apps_content ? '<p>'.esc_html($apps_content).'</p>' : '';

		$output = '<div class="namep-get-apps">
								'.$subtitle.$title.$content;
								// Group Param Output
								if ( is_array( $listItems_groups ) && !empty( $listItems_groups ) ) {
									$output .= '<div class="namep-btn-wrap">';
								  foreach ( $listItems_groups as $each_list ) {
								  $btn_style = !empty( $each_list['btn_style'] ) ? $each_list['btn_style'] : '';
								  $image_style = !empty( $each_list['image_style'] ) ? $each_list['image_style'] : '';
								  $retina_img = !empty( $each_list['retina_img'] ) ? $each_list['retina_img'] : '';

									$google_image = !empty( $each_list['google_image']['url'] ) ? $each_list['google_image']['url'] : '';
									$apple_image = !empty( $each_list['apple_image']['url'] ) ? $each_list['apple_image']['url'] : '';
									$chrome_image = !empty( $each_list['chrome_image']['url'] ) ? $each_list['chrome_image']['url'] : '';
									$btn_image = !empty( $each_list['btn_image']['url'] ) ? $each_list['btn_image']['url'] : '';

								  $btn_icon = !empty( $each_list['btn_icon'] ) ? $each_list['btn_icon'] : '';
								  $btn_text = !empty( $each_list['btn_text'] ) ? $each_list['btn_text'] : '';
								  $btn_link = !empty( $each_list['btn_link'] ) ? $each_list['btn_link'] : '';

									$link_url = !empty( $btn_link['url'] ) ? esc_url($btn_link['url']) : '';
									$link_external = !empty( $btn_link['is_external'] ) ? 'target="_blank"' : '';
									$link_nofollow = !empty( $btn_link['nofollow'] ) ? 'rel="nofollow"' : '';
									$link_attr = !empty( $btn_link['url'] ) ?  $link_external.' '.$link_nofollow : '';

									$btn_icon = $btn_icon ? '<i class="'.esc_attr($btn_icon).'" aria-hidden="true"></i>' : '';

									if ($image_style === 'chrome') {
										$image_url = $chrome_image ? $chrome_image : '';
									} elseif ($image_style === 'apple') {
										$image_url = $apple_image ? $apple_image : '';
									} elseif ($image_style === 'btn') {
										$image_url = $btn_image ? $btn_image : '';
									} else {
										$image_url = $google_image ? $google_image : '';
									}

									if ($image_url){
							      list($width, $height, $type, $attr) = getimagesize($image_url);
							    } else {
							      $width = '';
							      $height = '';
							    }
									if ($image_url && $retina_img) {
							      $logo_width = $width/2;
							      $logo_height = $height/2;
							    } else {
							      $logo_width = '';
							      $logo_height = '';
							    }
							    $logo_width = $logo_width ? 'max-width: '.namedical_core_check_px($logo_width).';' : '';
									$logo_height = $logo_height ? 'max-height: '.namedical_core_check_px($logo_height).';' : '';

									$image = $image_url ? '<img src="'.esc_url($image_url).'" alt="'.esc_html( 'Get Apps', 'medical-addon-for-elementor' ).'" style="'.esc_attr($logo_width).' '.esc_attr($logo_height).'">' : '';

									if ($btn_style === 'two') {
										$style_class = '';
										$btn = $image;
										$icon = '';
									} elseif ($btn_style === 'three') {
										$style_class = 'namep-link';
										$btn = $btn_text;
										$icon = $btn_icon;
									} else {
										$style_class = 'namep-btn';
										$btn = $btn_text;
										$icon = $btn_icon;
									}

								  $output .= '<a href="'.esc_url($link_url).'" '.$link_attr.' class="'.esc_attr($style_class).'"style="'.esc_attr($logo_width).' '.esc_attr($logo_height).'">'.$icon.$btn.'</a>';
									}
									$output .= '</div>';
								}
	$output .= '</div>';
		echo $output;

	}

}
Plugin::instance()->widgets_manager->register_widget_type( new Medical_Elementor_Addon_GetApps() );
