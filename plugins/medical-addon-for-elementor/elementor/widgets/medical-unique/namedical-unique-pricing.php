<?php
/*
 * Elementor Medical Addon for Elementor Pricing Widget
 * Author & Copyright: NicheAddon
*/

namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Medical_Elementor_Addon_Unique_Pricing extends Widget_Base{

	/**
	 * Retrieve the widget name.
	*/
	public function get_name(){
		return 'namedical_unique_pricing';
	}

	/**
	 * Retrieve the widget title.
	*/
	public function get_title(){
		return esc_html__( 'Pricing', 'medical-addon-for-elementor' );
	}

	/**
	 * Retrieve the widget icon.
	*/
	public function get_icon() {
		return 'eicon-price-list';
	}

	/**
	 * Retrieve the list of categories the widget belongs to.
	*/
	public function get_categories() {
		return ['namedical-unique-category'];
	}

	/**
	 * Register Medical Addon for Elementor Pricing widget controls.
	 * Adds different input fields to allow the user to change and customize the widget settings.
	*/
	protected function register_controls(){

		$this->start_controls_section(
			'section_pricing',
			[
				'label' => __( 'Pricing Options', 'medical-addon-for-elementor' ),
			]
		);
		$this->add_control(
			'pricing_style',
			[
				'label' => esc_html__( 'Pricing Style', 'medical-addon-for-elementor' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'one' => esc_html__( 'Style One', 'medical-addon-for-elementor' ),
					'two' => esc_html__( 'Style Two', 'medical-addon-for-elementor' ),
					'three' => esc_html__( 'Style Three', 'medical-addon-for-elementor' ),
				],
				'default' => 'one',
				'description' => esc_html__( 'Select your pricing style.', 'medical-addon-for-elementor' ),
			]
		);
		$this->add_control(
			'popular',
			[
				'label' => esc_html__( 'Popular?', 'medical-addon-for-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Yes', 'medical-addon-for-elementor' ),
				'label_off' => esc_html__( 'No', 'medical-addon-for-elementor' ),
				'return_value' => 'true',
				'description' => esc_html__( 'Popular pricing box, if enabled.', 'medical-addon-for-elementor' ),
				'condition' => [
					'pricing_style' => 'one',
				],
			]
		);
		$this->add_control(
			'price_label',
			[
				'label' => esc_html__( 'Price Label', 'medical-addon-for-elementor' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'Popular', 'medical-addon-for-elementor' ),
				'placeholder' => esc_html__( 'Type title text here', 'medical-addon-for-elementor' ),
				'label_block' => true,
				'condition' => [
					'popular' => 'true',
					'pricing_style' => 'one',
				],
			]
		);
		$this->add_control(
			'price_image',
			[
				'label' => esc_html__( 'Image', 'medical-addon-for-elementor' ),
				'type' => Controls_Manager::MEDIA,
				'condition' => [
					'pricing_style' => 'three',
				],
				'frontend_available' => true,
				'default' => [
					'url' => Utils::get_placeholder_image_src(),
				],
				'description' => esc_html__( 'Set your image.', 'medical-addon-for-elementor'),
			]
		);
		$this->add_control(
			'price_title',
			[
				'label' => esc_html__( 'Title Text', 'medical-addon-for-elementor' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'Professional', 'medical-addon-for-elementor' ),
				'placeholder' => esc_html__( 'Type title text here', 'medical-addon-for-elementor' ),
				'label_block' => true,
			]
		);
		$this->add_control(
			'currency',
			[
				'label' => esc_html__( 'Currency', 'medical-addon-for-elementor' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( '$', 'medical-addon-for-elementor' ),
				'placeholder' => esc_html__( 'Type title text here', 'medical-addon-for-elementor' ),
				'label_block' => true,
				'condition' => [
					'pricing_style' => 'one',
				],
			]
		);
		$this->add_control(
			'price',
			[
				'label' => esc_html__( 'Price', 'medical-addon-for-elementor' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( '80', 'medical-addon-for-elementor' ),
				'placeholder' => esc_html__( 'Type title text here', 'medical-addon-for-elementor' ),
				'label_block' => true,
				'condition' => [
					'pricing_style' => 'one',
				],
			]
		);
		$this->add_control(
			'duration',
			[
				'label' => esc_html__( 'Duration', 'medical-addon-for-elementor' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( '/mo', 'medical-addon-for-elementor' ),
				'placeholder' => esc_html__( 'Type title text here', 'medical-addon-for-elementor' ),
				'label_block' => true,
				'condition' => [
					'pricing_style' => 'one',
				],
			]
		);
		$repeater = new Repeater();
		$repeater->add_control(
			'list_text',
			[
				'label' => esc_html__( 'List Text', 'medical-addon-for-elementor' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( '1 Account', 'medical-addon-for-elementor' ),
				'placeholder' => esc_html__( 'Type title text here', 'medical-addon-for-elementor' ),
				'label_block' => true,
			]
		);
		$repeater->add_control(
			'text_link',
			[
				'label' => esc_html__( 'Text Link', 'medical-addon-for-elementor' ),
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
				'label' => esc_html__( 'List', 'medical-addon-for-elementor' ),
				'type' => Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
				'title_field' => '{{{ list_text }}}',
				'prevent_empty' => false,
				'condition' => [
					'pricing_style' => 'one',
				],
			]
		);
		$repeaterTwo = new Repeater();
		$repeaterTwo->add_control(
			'list_text',
			[
				'label' => esc_html__( 'List Text', 'medical-addon-for-elementor' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'Bronchoscopy', 'medical-addon-for-elementor' ),
				'placeholder' => esc_html__( 'Type text here', 'medical-addon-for-elementor' ),
				'label_block' => true,
			]
		);
		$repeaterTwo->add_control(
			'text_price',
			[
				'label' => esc_html__( 'Price', 'medical-addon-for-elementor' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( '$250', 'medical-addon-for-elementor' ),
				'placeholder' => esc_html__( 'Type price text here', 'medical-addon-for-elementor' ),
				'label_block' => true,
			]
		);
		$this->add_control(
			'priceItems_groups',
			[
				'label' => esc_html__( 'Price List', 'medical-addon-for-elementor' ),
				'type' => Controls_Manager::REPEATER,
				'fields' => $repeaterTwo->get_controls(),
				'title_field' => '{{{ list_text }}}',
				'prevent_empty' => false,
				'condition' => [
					'pricing_style' => array('two', 'three'),
				],
			]
		);
		$this->add_control(
			'btn_text',
			[
				'label' => esc_html__( 'Button Text', 'medical-addon-for-elementor' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'Buy Now', 'medical-addon-for-elementor' ),
				'placeholder' => esc_html__( 'Type title text here', 'medical-addon-for-elementor' ),
				'label_block' => true,
			]
		);
		$this->add_control(
			'btn_link',
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
					'{{WRAPPER}} .namep-plan-item' => 'text-align: {{VALUE}};',
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
			$this->add_control(
				'section_padding',
				[
					'label' => __( 'Padding', 'medical-addon-for-elementor' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%', 'em' ],
					'selectors' => [
						'{{WRAPPER}} .namep-plan-item, {{WRAPPER}} .namep-price-item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);
			$this->add_control(
				'section_bg_color',
				[
					'label' => esc_html__( 'Background Color', 'medical-addon-for-elementor' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .namep-plan-item, {{WRAPPER}} .namep-price-item' => 'background-color: {{VALUE}};',
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
						'{{WRAPPER}} .namep-plan-item, {{WRAPPER}} .namep-price-item' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);
			$this->add_group_control(
				Group_Control_Border::get_type(),
				[
					'name' => 'section_box_border',
					'label' => esc_html__( 'Border', 'medical-addon-for-elementor' ),
					'selector' => '{{WRAPPER}} .namep-plan-item, {{WRAPPER}} .namep-price-item',
				]
			);
			$this->add_group_control(
				Group_Control_Box_Shadow::get_type(),
				[
					'name' => 'section_box_shadow',
					'label' => esc_html__( 'Box Shadow', 'medical-addon-for-elementor' ),
					'selector' => '{{WRAPPER}} .namep-plan-item, {{WRAPPER}} .namep-price-item',
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
						'{{WRAPPER}} .namep-plan-item h4' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
					'condition' => [
						'pricing_style' => 'one',
					],
				]
			);
			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'label' => esc_html__( 'Typography', 'medical-addon-for-elementor' ),
					'name' => 'sasstp_title_typography',
					'selector' => '{{WRAPPER}} .namep-plan-item h4, {{WRAPPER}} .namep-price-item h3',
				]
			);
			$this->add_control(
				'title_color',
				[
					'label' => esc_html__( 'Color', 'medical-addon-for-elementor' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .namep-plan-item h4, {{WRAPPER}} .namep-price-item h3' => 'color: {{VALUE}};',
					],
				]
			);
			$this->end_controls_section();// end: Section

		// Price
			$this->start_controls_section(
				'section_price_style',
				[
					'label' => esc_html__( 'Price', 'medical-addon-for-elementor' ),
					'tab' => Controls_Manager::TAB_STYLE,
					'condition' => [
						'pricing_style' => 'one',
					],
				]
			);
			$this->add_control(
				'price_padding',
				[
					'label' => __( 'Padding', 'medical-addon-for-elementor' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%', 'em' ],
					'selectors' => [
						'{{WRAPPER}} .namep-price' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);
			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'label' => esc_html__( 'Typography', 'medical-addon-for-elementor' ),
					'name' => 'price_typography',
					'selector' => '{{WRAPPER}} .namep-price',
				]
			);
			$this->add_control(
				'price_color',
				[
					'label' => esc_html__( 'Color', 'medical-addon-for-elementor' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .namep-price' => 'color: {{VALUE}};',
					],
				]
			);
			$this->end_controls_section();// end: Section

		// List
			$this->start_controls_section(
				'section_list_style',
				[
					'label' => esc_html__( 'List', 'medical-addon-for-elementor' ),
					'tab' => Controls_Manager::TAB_STYLE,
				]
			);
			$this->add_control(
				'list_padding',
				[
					'label' => __( 'Padding', 'medical-addon-for-elementor' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%', 'em' ],
					'selectors' => [
						'{{WRAPPER}} .namep-plan-item ul li, {{WRAPPER}} .namep-price-item ul li' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);
			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'label' => esc_html__( 'Typography', 'medical-addon-for-elementor' ),
					'name' => 'list_typography',
					'selector' => '{{WRAPPER}} .namep-plan-item ul li, {{WRAPPER}} .namep-price-item ul li',
				]
			);
			$this->add_control(
				'list_color',
				[
					'label' => esc_html__( 'Color', 'medical-addon-for-elementor' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .namep-plan-item ul li, {{WRAPPER}} .namep-price-item ul li' => 'color: {{VALUE}};',
					],
				]
			);
			$this->add_control(
				'list_bdr_color',
				[
					'label' => esc_html__( 'Border Color', 'medical-addon-for-elementor' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .namep-price-item ul li' => 'border-color: {{VALUE}};',
					],
					'condition' => [
						'pricing_style' => 'two',
					],
				]
			);
			$this->end_controls_section();// end: Section

		// Label
			$this->start_controls_section(
				'section_label_style',
				[
					'label' => esc_html__( 'Label', 'medical-addon-for-elementor' ),
					'tab' => Controls_Manager::TAB_STYLE,
					'condition' => [
						'popular' => 'true',
					],
				]
			);
			$this->add_control(
				'label_margin',
				[
					'label' => __( 'Margin', 'medical-addon-for-elementor' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%', 'em' ],
					'selectors' => [
						'{{WRAPPER}} .namep-price-label' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);
			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'label' => esc_html__( 'Typography', 'medical-addon-for-elementor' ),
					'name' => 'label_typography',
					'selector' => '{{WRAPPER}} .namep-price-label',
				]
			);
			$this->add_control(
				'label_bg_color',
				[
					'label' => esc_html__( 'Background Color', 'medical-addon-for-elementor' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .namep-price-label' => 'background-color: {{VALUE}};',
					],
				]
			);
			$this->add_control(
				'label_color',
				[
					'label' => esc_html__( 'Color', 'medical-addon-for-elementor' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .namep-price-label' => 'color: {{VALUE}};',
					],
				]
			);
			$this->end_controls_section();// end: Section

		// Link
			$this->start_controls_section(
				'section_link_style',
				[
					'label' => esc_html__( 'Link', 'medical-addon-for-elementor' ),
					'tab' => Controls_Manager::TAB_STYLE,
					'condition' => [
						'pricing_style' => array('two', 'three'),
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
							'{{WRAPPER}} .namep-link span:after' => 'background-color: {{VALUE}};',
						],
					]
				);
				$this->end_controls_tab();  // end:Hover tab
			$this->end_controls_tabs(); // end tabs
			$this->end_controls_section();// end: Section

		// Button
			$this->start_controls_section(
				'section_btn_style',
				[
					'label' => esc_html__( 'Button', 'medical-addon-for-elementor' ),
					'tab' => Controls_Manager::TAB_STYLE,
					'condition' => [
						'pricing_style' => 'one',
					],
				]
			);
			$this->add_responsive_control(
				'btn_width',
				[
					'label' => esc_html__( 'Button Width', 'medical-addon-for-elementor' ),
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
						'{{WRAPPER}} .namep-btn' => 'min-width:{{SIZE}}{{UNIT}};',
					],
				]
			);
			$this->add_responsive_control(
				'btn_margin',
				[
					'label' => __( 'Margin', 'medical-addon-for-elementor' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px' ],
					'selectors' => [
						'{{WRAPPER}} .namep-btn' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);
			$this->add_responsive_control(
				'btn_padding',
				[
					'label' => __( 'Padding', 'medical-addon-for-elementor' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px' ],
					'selectors' => [
						'{{WRAPPER}} .namep-btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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

	}

	/**
	 * Render App Works widget output on the frontend.
	 * Written in PHP and used to generate the final HTML.
	*/
	protected function render() {
		$settings = $this->get_settings_for_display();
		$pricing_style = !empty( $settings['pricing_style'] ) ? $settings['pricing_style'] : '';
		$popular = !empty( $settings['popular'] ) ? $settings['popular'] : '';
		$price_label = !empty( $settings['price_label'] ) ? $settings['price_label'] : '';
		$price_image = !empty( $settings['price_image']['id'] ) ? $settings['price_image']['id'] : '';
		$price_title = !empty( $settings['price_title'] ) ? $settings['price_title'] : '';
		$currency = !empty( $settings['currency'] ) ? $settings['currency'] : '';
		$price = !empty( $settings['price'] ) ? $settings['price'] : '';
		$duration = !empty( $settings['duration'] ) ? $settings['duration'] : '';
		$btn_text = !empty( $settings['btn_text'] ) ? $settings['btn_text'] : '';
		$btn_link = !empty( $settings['btn_link']['url'] ) ? $settings['btn_link']['url'] : '';
		$btn_link_external = !empty( $settings['btn_link']['is_external'] ) ? 'target="_blank"' : '';
		$btn_link_nofollow = !empty( $settings['btn_link']['nofollow'] ) ? 'rel="nofollow"' : '';
		$btn_link_attr = !empty( $btn_link ) ?  $btn_link_external.' '.$btn_link_nofollow : '';

		$listItems_groups = !empty( $settings['listItems_groups'] ) ? $settings['listItems_groups'] : '';
		$priceItems_groups = !empty( $settings['priceItems_groups'] ) ? $settings['priceItems_groups'] : '';

		$price_label = $price_label ? '<span class="namep-price-label">'.esc_html($price_label).'</span>' : '';
		$title = $price_title ? '<h4 class="namep-plan-title">'.esc_html($price_title).'</h4>' : '';
		$currency = $currency ? '<sup>'.esc_html($currency).'</sup>' : '';
		$duration = $duration ? '<sub>'.esc_html($duration).'</sub>' : '';
		$price = $price ? '<span class="namep-price">'.$currency.esc_html($price).$duration.'</span>' : '';
		$button = $btn_link ? '<a href="'.esc_url($btn_link).'" '.$btn_link_attr.' class="namep-btn">'. esc_html($btn_text) .'</a>' : '';
		$buttonTwo = $btn_link ? '<a href="'.esc_url($btn_link).'" '.$btn_link_attr.' class="namep-link"><span>'. esc_html($btn_text) .'</span> <i class="fa fa-chevron-right"></i></a>' : '';
		$titleTwo = $price_title ? '<h3 class="namep-price-title">'.esc_html($price_title).$buttonTwo.'</h3>' : '';
		$image_url = wp_get_attachment_url( $price_image );
		$image = $image_url ? ' style="background-image: url('.esc_url($image_url).');"' : '';
		$titleThree = $price_title ? '<h3 class="namep-price-title"'.$image.'><span>'.esc_html($price_title).'</span></h3>' : '';
		$buttonThree = $btn_link ? '<div class="namep-price-link"><a href="'.esc_url($btn_link).'" '.$btn_link_attr.' class="namep-link"><span>'. esc_html($btn_text) .'</span> <i class="fa fa-chevron-right"></i></a></div>' : '';

		if ($popular) {
			$popular_class = ' namep-plan-spacer';
		} else {
			$popular_class = '';
		}
		if ($pricing_style === 'two') {
			$output = '<div class="namep-price-item">
		              '.$titleTwo.'
		              <ul>';
		              if ( is_array( $priceItems_groups ) && !empty( $priceItems_groups ) ) {
									  foreach ( $priceItems_groups as $each_list ) {
									  	$list_text = !empty( $each_list['list_text'] ) ? $each_list['list_text'] : '';
									  	$text_price = !empty( $each_list['text_price'] ) ? $each_list['text_price'] : '';

									  	$price = $text_price ? ' <span class="main-price">'.$text_price.'</span>' : '';
	                  	$output .= '<li>'.$list_text.$price.'</li>';
		                }
		              }
          				$output .= '</ul>
		            </div>';
		} elseif ($pricing_style === 'three') {
			$output = '<div class="price-style-two">
									<div class="namep-price-item">
			              '.$titleThree.'
			              <ul>';
			              if ( is_array( $priceItems_groups ) && !empty( $priceItems_groups ) ) {
										  foreach ( $priceItems_groups as $each_list ) {
										  	$list_text = !empty( $each_list['list_text'] ) ? $each_list['list_text'] : '';
										  	$text_price = !empty( $each_list['text_price'] ) ? $each_list['text_price'] : '';

										  	$price = $text_price ? ' <span class="main-price">'.$text_price.'</span>' : '';
		                  	$output .= '<li>'.$list_text.$price.'</li>';
			                }
			              }
	          				$output .= '</ul>'.$buttonThree.'
			            </div>
	              </div>';
    } else {
			$output = '<div class="namep-plan-item'.esc_attr($popular_class).'">
	                '.$price_label.$title.$price.'
	                <ul>';
									if ( is_array( $listItems_groups ) && !empty( $listItems_groups ) ) {
									  foreach ( $listItems_groups as $each_list ) {
									  	$list_text = !empty( $each_list['list_text'] ) ? $each_list['list_text'] : '';
									  	$text_link = !empty( $each_list['text_link']['url'] ) ? $each_list['text_link']['url'] : '';
											$text_link_external = !empty( $each_list['text_link']['is_external'] ) ? 'target="_blank"' : '';
											$text_link_nofollow = !empty( $each_list['text_link']['nofollow'] ) ? 'rel="nofollow"' : '';
											$text_link_attr = !empty( $text_link ) ?  $text_link_external.' '.$text_link_nofollow : '';

									  	$text = $text_link ? '<li><a href="'.esc_url($text_link).'" '.$text_link_attr.'>'. esc_html($list_text) .'</a></li>' : '<li>'. esc_html($list_text) .'</li>';
	                  	$output .= $text;
		                }
		              }
	                $output .= '</ul>
	                '.$button.'
	              </div>';
    }
		echo $output;

	}

}
Plugin::instance()->widgets_manager->register_widget_type( new Medical_Elementor_Addon_Unique_Pricing() );
