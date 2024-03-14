<?php
/*
 * Elementor Charity Elementor Addon Peoples Needs Widget
 * Author & Copyright: NicheAddon
*/

namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Charity_Elementor_Addon_Unique_Peoples_Needs extends Widget_Base{

	/**
	 * Retrieve the widget name.
	*/
	public function get_name(){
		return 'nacharity_unique_peoples_need';
	}

	/**
	 * Retrieve the widget title.
	*/
	public function get_title(){
		return esc_html__( 'Peoples Needs', 'charity-elementor-addon' );
	}

	/**
	 * Retrieve the widget icon.
	*/
	public function get_icon() {
		return 'eicon-help-o';
	}

	/**
	 * Retrieve the list of categories the widget belongs to.
	*/
	public function get_categories() {
		return ['nacharity-unique-category'];
	}

	/**
	 * Register Charity Elementor Addon Peoples Needs widget controls.
	 * Adds different input fields to allow the user to change and customize the widget settings.
	*/
	protected function _register_controls(){

		$this->start_controls_section(
			'section_needs',
			[
				'label' => __( 'Peoples Needs Item', 'charity-elementor-addon' ),
			]
		);
		$this->add_control(
			'needs_title',
			[
				'label' => esc_html__( 'Title', 'charity-elementor-addon' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'People\'s Needs', 'charity-elementor-addon' ),
				'placeholder' => esc_html__( 'Type title text here', 'charity-elementor-addon' ),
				'label_block' => true,
			]
		);
		$this->add_control(
			'needs_number',
			[
				'label' => esc_html__( 'Number', 'charity-elementor-addon' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( '120,555', 'charity-elementor-addon' ),
				'placeholder' => esc_html__( 'Type year text here', 'charity-elementor-addon' ),
				'label_block' => true,
			]
		);
		$this->add_control(
			'needs_content',
			[
				'label' => esc_html__( 'Content', 'charity-elementor-addon' ),
				'type' => Controls_Manager::TEXTAREA,
				'placeholder' => esc_html__( 'Type title text here', 'charity-elementor-addon' ),
				'label_block' => true,
			]
		);
		$this->add_control(
			'n_wi_he',
			[
				'label' => __( 'List', 'charity-addon-for-elementor' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);
		$repeaterList = new Repeater();
		$repeaterList->add_control(
			'needs_list',
			[
				'label' => esc_html__( 'List Content', 'charity-elementor-addon' ),
				'type' => Controls_Manager::TEXTAREA,
				'placeholder' => esc_html__( 'Type list text here', 'charity-elementor-addon' ),
				'label_block' => true,
			]
		);
		$this->add_control(
			'needsList_groups',
			[
				'label' => esc_html__( 'List Items', 'charity-elementor-addon' ),
				'type' => Controls_Manager::REPEATER,
				'fields' => $repeaterList->get_controls(),
				'title_field' => '{{{ needs_list }}}',
				'prevent_empty' => false,
			]
		);
		$this->add_control(
			'n_wsdfhe',
			[
				'label' => __( 'Slider Image', 'charity-addon-for-elementor' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);
		$repeater = new Repeater();
		$repeater->add_control(
			'needs_image',
			[
				'label' => esc_html__( 'Upload Image', 'charity-elementor-addon' ),
				'type' => Controls_Manager::MEDIA,
				'frontend_available' => true,
				'description' => esc_html__( 'Set your image.', 'charity-elementor-addon'),
			]
		);
		$this->add_control(
			'needsItem_groups',
			[
				'label' => esc_html__( 'Image Items', 'charity-elementor-addon' ),
				'type' => Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
				'title_field' => '{{{ needs_image }}}',
				'prevent_empty' => false,
			]
		);

		$this->end_controls_section();// end: Section

		$this->start_controls_section(
			'section_carousel',
			[
				'label' => esc_html__( 'Carousel Options', 'charity-elementor-addon' ),
			]
		);
		$this->add_responsive_control(
			'carousel_items',
			[
				'label' => esc_html__( 'How many items?', 'charity-elementor-addon' ),
				'type' => Controls_Manager::NUMBER,
				'min' => 1,
				'max' => 100,
				'step' => 1,
				'default' => 1,
				'description' => esc_html__( 'Enter the number of items to show.', 'charity-elementor-addon' ),
			]
		);
		$this->add_control(
			'carousel_margin',
			[
				'label' => __( 'Space Between Items', 'charity-elementor-addon' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' =>0,
				],
				'label_block' => true,
			]
		);
		$this->add_control(
			'carousel_autoplay_timeout',
			[
				'label' => __( 'Auto Play Timeout', 'charity-elementor-addon' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 5000,
			]
		);
		$this->add_control(
			'carousel_loop',
			[
				'label' => esc_html__( 'Disable Loop?', 'charity-elementor-addon' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Yes', 'charity-elementor-addon' ),
				'label_off' => esc_html__( 'No', 'charity-elementor-addon' ),
				'return_value' => 'true',
				'description' => esc_html__( 'Continuously moving carousel, if enabled.', 'charity-elementor-addon' ),
			]
		);
		$this->add_control(
			'carousel_dots',
			[
				'label' => esc_html__( 'Dots', 'charity-elementor-addon' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Yes', 'charity-elementor-addon' ),
				'label_off' => esc_html__( 'No', 'charity-elementor-addon' ),
				'return_value' => 'true',
				'description' => esc_html__( 'If you want Carousel Dots, enable it.', 'charity-elementor-addon' ),
				'default' => true,
			]
		);
		$this->add_control(
			'carousel_nav',
			[
				'label' => esc_html__( 'Navigation', 'charity-elementor-addon' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Yes', 'charity-elementor-addon' ),
				'label_off' => esc_html__( 'No', 'charity-elementor-addon' ),
				'return_value' => 'true',
				'description' => esc_html__( 'If you want Carousel Navigation, enable it.', 'charity-elementor-addon' ),
				'default' => true,
			]
		);
		$this->add_control(
			'carousel_autoplay',
			[
				'label' => esc_html__( 'Autoplay', 'charity-elementor-addon' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Yes', 'charity-elementor-addon' ),
				'label_off' => esc_html__( 'No', 'charity-elementor-addon' ),
				'return_value' => 'true',
				'description' => esc_html__( 'If you want to start Carousel automatically, enable it.', 'charity-elementor-addon' ),
				'default' => true,
			]
		);
		$this->add_control(
			'carousel_animate_out',
			[
				'label' => esc_html__( 'Animate Out', 'charity-elementor-addon' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Yes', 'charity-elementor-addon' ),
				'label_off' => esc_html__( 'No', 'charity-elementor-addon' ),
				'return_value' => 'true',
				'description' => esc_html__( 'CSS3 animation out.', 'charity-elementor-addon' ),
			]
		);
		$this->add_control(
			'carousel_mousedrag',
			[
				'label' => esc_html__( 'Disable Mouse Drag?', 'charity-elementor-addon' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Yes', 'charity-elementor-addon' ),
				'label_off' => esc_html__( 'No', 'charity-elementor-addon' ),
				'return_value' => 'true',
				'description' => esc_html__( 'If you want to disable Mouse Drag, check it.', 'charity-elementor-addon' ),
			]
		);
		$this->add_control(
			'carousel_autowidth',
			[
				'label' => esc_html__( 'Auto Width', 'charity-elementor-addon' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Yes', 'charity-elementor-addon' ),
				'label_off' => esc_html__( 'No', 'charity-elementor-addon' ),
				'return_value' => 'true',
				'description' => esc_html__( 'Adjust Auto Width automatically for each carousel items.', 'charity-elementor-addon' ),
			]
		);
		$this->add_control(
			'carousel_autoheight',
			[
				'label' => esc_html__( 'Auto Height', 'charity-elementor-addon' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Yes', 'charity-elementor-addon' ),
				'label_off' => esc_html__( 'No', 'charity-elementor-addon' ),
				'return_value' => 'true',
				'description' => esc_html__( 'Adjust Auto Height automatically for each carousel items.', 'charity-elementor-addon' ),
			]
		);
		$this->end_controls_section();// end: Section

		// Icon
		$this->start_controls_section(
			'section_icon_style',
			[
				'label' => esc_html__( 'Icon', 'charity-elementor-addon' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'need_popup' => 'true',
				],
			]
		);
		$this->add_control(
			'icon_border_radius',
			[
				'label' => __( 'Border Radius', 'charity-elementor-addon' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .needs-image .nacep-image.nacep-popup a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_control(
			'icon_width',
			[
				'label' => esc_html__( 'Icon Width/Height', 'charity-elementor-addon' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 500,
						'step' => 1,
					],
				],
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .needs-image .nacep-image.nacep-popup a' => 'width: {{SIZE}}{{UNIT}};height: {{SIZE}}{{UNIT}};line-height: {{SIZE}}{{UNIT}};',
				],
			]
		);
		$this->add_control(
			'icon_size',
			[
				'label' => esc_html__( 'Icon Size', 'charity-elementor-addon' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 500,
						'step' => 1,
					],
				],
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .needs-image .nacep-image.nacep-popup a' => 'font-size: {{SIZE}}{{UNIT}};',
				],
			]
		);
		$this->start_controls_tabs( 'icon_style' );
			$this->start_controls_tab(
				'ico_normal',
				[
					'label' => esc_html__( 'Normal', 'charity-elementor-addon' ),
				]
			);
			$this->add_control(
				'icon_color',
				[
					'label' => esc_html__( 'Icon Color', 'charity-elementor-addon' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .needs-image .nacep-image.nacep-popup a' => 'color: {{VALUE}};',
					],
				]
			);
			$this->add_control(
				'icon_bgcolor',
				[
					'label' => esc_html__( 'Background Color', 'charity-elementor-addon' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .needs-image .nacep-image.nacep-popup a' => 'background-color: {{VALUE}};',
					],
				]
			);
			$this->end_controls_tab();  // end:Normal tab
			$this->start_controls_tab(
				'ico_hover',
				[
					'label' => esc_html__( 'Hover', 'charity-elementor-addon' ),
				]
			);
			$this->add_control(
				'icon_hover_color',
				[
					'label' => esc_html__( 'Icon Color', 'charity-elementor-addon' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .needs-image .nacep-image.nacep-popup a:hover' => 'color: {{VALUE}};',
					],
				]
			);
			$this->add_control(
				'icon_hover_bgcolor',
				[
					'label' => esc_html__( 'Background Color', 'charity-elementor-addon' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .needs-image .nacep-image.nacep-popup a:hover' => 'background-color: {{VALUE}};',
					],
				]
			);
			$this->end_controls_tab();  // end:Hover tab
		$this->end_controls_tabs(); // end tabs
		$this->end_controls_section();// end: Section

		// Title
		$this->start_controls_section(
			'section_title_style',
			[
				'label' => esc_html__( 'Title', 'charity-elementor-addon' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_responsive_control(
			'needs_title_padding',
			[
				'label' => __( 'Padding', 'charity-elementor-addon' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'selectors' => [
					'{{WRAPPER}} .nacep-needs-info h5' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'label' => esc_html__( 'Typography', 'charity-elementor-addon' ),
				'name' => 'sastool_title_typography',
				'selector' => '{{WRAPPER}} .nacep-needs-info h5',
			]
		);
		$this->add_control(
			'title_color',
			[
				'label' => esc_html__( 'Color', 'charity-elementor-addon' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .nacep-needs-info h5' => 'color: {{VALUE}};',
				],
			]
		);
		$this->end_controls_section();// end: Section

		// Number
		$this->start_controls_section(
			'section_number_style',
			[
				'label' => esc_html__( 'Number', 'charity-elementor-addon' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_control(
			'number_bg_color',
			[
				'label' => esc_html__( 'Background Color', 'charity-elementor-addon' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .nacep-needs-info h2 span' => 'background-color: {{VALUE}};',
				],
			]
		);
		$this->add_responsive_control(
			'needs_number_padding',
			[
				'label' => __( 'Padding', 'charity-elementor-addon' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'selectors' => [
					'{{WRAPPER}} .nacep-needs-info h2 span' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'label' => esc_html__( 'Typography', 'charity-elementor-addon' ),
				'name' => 'sastool_number_typography',
				'selector' => '{{WRAPPER}} .nacep-needs-info h2 span',
			]
		);
		$this->add_control(
			'number_color',
			[
				'label' => esc_html__( 'Color', 'charity-elementor-addon' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .nacep-needs-info h2 span' => 'color: {{VALUE}};',
				],
			]
		);
		$this->end_controls_section();// end: Section

		// Number Title
		$this->start_controls_section(
			'section_numb_title_style',
			[
				'label' => esc_html__( 'Number Title', 'charity-elementor-addon' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_responsive_control(
			'needs_numb_title_padding',
			[
				'label' => __( 'Padding', 'charity-elementor-addon' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'selectors' => [
					'{{WRAPPER}} .nacep-needs-info h2' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'label' => esc_html__( 'Typography', 'charity-elementor-addon' ),
				'name' => 'sastool_numb_title_typography',
				'selector' => '{{WRAPPER}} .nacep-needs-info h2',
			]
		);
		$this->add_control(
			'numb_title_color',
			[
				'label' => esc_html__( 'Color', 'charity-elementor-addon' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .nacep-needs-info h2' => 'color: {{VALUE}};',
				],
			]
		);
		$this->end_controls_section();// end: Section

		// List
		$this->start_controls_section(
			'section_list_style',
			[
				'label' => esc_html__( 'List', 'charity-elementor-addon' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_responsive_control(
			'needs_list_padding',
			[
				'label' => __( 'Padding', 'charity-elementor-addon' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'selectors' => [
					'{{WRAPPER}} .nacep-needs-info ul.needs-list li' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'label' => esc_html__( 'Typography', 'charity-elementor-addon' ),
				'name' => 'sastool_list_typography',
				'selector' => '{{WRAPPER}} .nacep-needs-info ul.needs-list li',
			]
		);
		$this->add_control(
			'list_color',
			[
				'label' => esc_html__( 'Color', 'charity-elementor-addon' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .nacep-needs-info ul.needs-list li' => 'color: {{VALUE}};',
				],
			]
		);
		$this->add_control(
			'list_icon_color',
			[
				'label' => esc_html__( 'Icon Color', 'charity-elementor-addon' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .nacep-needs-info ul.needs-list li:before' => 'color: {{VALUE}};',
				],
			]
		);
		$this->end_controls_section();// end: Section

		// Navigation
		$this->start_controls_section(
			'section_navigation_style',
			[
				'label' => esc_html__( 'Navigation', 'charity-elementor-addon' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'carousel_nav' => 'true',
				],
				'frontend_available' => true,
			]
		);
		$this->add_responsive_control(
			'arrow_size',
			[
				'label' => esc_html__( 'Size', 'charity-elementor-addon' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 42,
						'max' => 1000,
						'step' => 1,
					],
				],
				'size_units' => [ 'px' ],
				'selectors' => [
					'{{WRAPPER}} .owl-carousel .owl-nav button.owl-prev, {{WRAPPER}} .owl-carousel .owl-nav button.owl-next' => 'width: {{SIZE}}{{UNIT}};height: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .owl-carousel .owl-nav button.owl-prev:before, {{WRAPPER}} .owl-carousel .owl-nav button.owl-next:before' => 'font-size: calc({{SIZE}}{{UNIT}} - 16px);line-height: calc({{SIZE}}{{UNIT}} - 20px);',
				],
			]
		);
		$this->start_controls_tabs( 'nav_arrow_style' );
			$this->start_controls_tab(
				'nav_arrow_normal',
				[
					'label' => esc_html__( 'Normal', 'charity-elementor-addon' ),
				]
			);
			$this->add_control(
				'nav_arrow_color',
				[
					'label' => esc_html__( 'Color', 'charity-elementor-addon' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .owl-carousel .owl-nav button.owl-prev:before, {{WRAPPER}} .owl-carousel .owl-nav button.owl-next:before' => 'color: {{VALUE}};',
					],
				]
			);
			$this->add_control(
				'nav_arrow_bg_color',
				[
					'label' => esc_html__( 'Background Color', 'charity-elementor-addon' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .owl-carousel .owl-nav button.owl-prev, {{WRAPPER}} .owl-carousel .owl-nav button.owl-next' => 'background-color: {{VALUE}};',
					],
				]
			);
			$this->add_group_control(
				Group_Control_Border::get_type(),
				[
					'name' => 'nav_border',
					'label' => esc_html__( 'Border', 'charity-elementor-addon' ),
					'selector' => '{{WRAPPER}} .owl-carousel .owl-nav button.owl-prev, {{WRAPPER}} .owl-carousel .owl-nav button.owl-next',
				]
			);
			$this->end_controls_tab();  // end:Normal tab

			$this->start_controls_tab(
				'nav_arrow_hover',
				[
					'label' => esc_html__( 'Hover', 'charity-elementor-addon' ),
				]
			);
			$this->add_control(
				'nav_arrow_hov_color',
				[
					'label' => esc_html__( 'Color', 'charity-elementor-addon' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .owl-carousel .owl-nav button.owl-prev:hover:before, {{WRAPPER}} .owl-carousel .owl-nav button.owl-next:hover:before' => 'color: {{VALUE}};',
					],
				]
			);
			$this->add_control(
				'nav_arrow_bg_hover_color',
				[
					'label' => esc_html__( 'Background Color', 'charity-elementor-addon' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .owl-carousel .owl-nav button.owl-prev:hover, {{WRAPPER}} .owl-carousel .owl-nav button.owl-next:hover' => 'background-color: {{VALUE}};',
					],
				]
			);
			$this->add_group_control(
				Group_Control_Border::get_type(),
				[
					'name' => 'nav_active_border',
					'label' => esc_html__( 'Border', 'charity-elementor-addon' ),
					'selector' => '{{WRAPPER}} .owl-carousel .owl-nav button.owl-prev:hover, {{WRAPPER}} .owl-carousel .owl-nav button.owl-next:hover',
				]
			);
			$this->end_controls_tab();  // end:Hover tab

		$this->end_controls_tabs(); // end tabs
		$this->end_controls_section();// end: Section

		// Dots
		$this->start_controls_section(
			'section_dots_style',
			[
				'label' => esc_html__( 'Dots', 'charity-elementor-addon' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'carousel_dots' => 'true',
				],
				'frontend_available' => true,
			]
		);
		$this->add_responsive_control(
			'dots_size',
			[
				'label' => esc_html__( 'Size', 'charity-elementor-addon' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1000,
						'step' => 1,
					],
				],
				'size_units' => [ 'px' ],
				'selectors' => [
					'{{WRAPPER}} .owl-carousel .owl-dot' => 'width: {{SIZE}}{{UNIT}};height: {{SIZE}}{{UNIT}}',
				],
			]
		);
		$this->add_responsive_control(
			'dots_margin',
			[
				'label' => __( 'Margin', 'charity-elementor-addon' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .owl-carousel .owl-dot' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->start_controls_tabs( 'dots_style' );
			$this->start_controls_tab(
				'dots_normal',
				[
					'label' => esc_html__( 'Normal', 'charity-elementor-addon' ),
				]
			);
			$this->add_control(
				'dots_color',
				[
					'label' => esc_html__( 'Background Color', 'charity-elementor-addon' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .owl-carousel .owl-dot' => 'background: {{VALUE}};',
					],
				]
			);
			$this->add_group_control(
				Group_Control_Border::get_type(),
				[
					'name' => 'dots_border',
					'label' => esc_html__( 'Border', 'charity-elementor-addon' ),
					'selector' => '{{WRAPPER}} .owl-carousel .owl-dot',
				]
			);
			$this->end_controls_tab();  // end:Normal tab

			$this->start_controls_tab(
				'dots_active',
				[
					'label' => esc_html__( 'Active', 'charity-elementor-addon' ),
				]
			);
			$this->add_control(
				'dots_active_color',
				[
					'label' => esc_html__( 'Background Color', 'charity-elementor-addon' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .owl-carousel .owl-dot.active' => 'background: {{VALUE}};',
					],
				]
			);
			$this->add_group_control(
				Group_Control_Border::get_type(),
				[
					'name' => 'dots_active_border',
					'label' => esc_html__( 'Border', 'charity-elementor-addon' ),
					'selector' => '{{WRAPPER}} .owl-carousel .owl-dot.active',
				]
			);
			$this->end_controls_tab();  // end:Active tab

		$this->end_controls_tabs(); // end tabs
		$this->end_controls_section();// end: Section

	}

	/**
	 * Render Peoples Needs widget output on the frontend.
	 * Written in PHP and used to generate the final HTML.
	*/
	protected function render() {
		// Peoples Needs query
		$settings = $this->get_settings_for_display();
		$needsItem = $this->get_settings_for_display( 'needsItem_groups' );
		$needsList = $this->get_settings_for_display( 'needsList_groups' );
		$needs_title = !empty( $settings['needs_title'] ) ? $settings['needs_title'] : '';
		$needs_number = !empty( $settings['needs_number'] ) ? $settings['needs_number'] : '';
		$needs_content = !empty( $settings['needs_content'] ) ? $settings['needs_content'] : '';

		// Carousel
			$carousel_items = !empty( $settings['carousel_items'] ) ? $settings['carousel_items'] : '';
			$carousel_items_tablet = !empty( $settings['carousel_items_tablet'] ) ? $settings['carousel_items_tablet'] : '';
			$carousel_items_mobile = !empty( $settings['carousel_items_mobile'] ) ? $settings['carousel_items_mobile'] : '';
			$carousel_margin = !empty( $settings['carousel_margin']['size'] ) ? $settings['carousel_margin']['size'] : '';
			$carousel_autoplay_timeout = !empty( $settings['carousel_autoplay_timeout'] ) ? $settings['carousel_autoplay_timeout'] : '';
			$carousel_loop  = ( isset( $settings['carousel_loop'] ) && ( 'true' == $settings['carousel_loop'] ) ) ? $settings['carousel_loop'] : 'false';
			$carousel_dots  = ( isset( $settings['carousel_dots'] ) && ( 'true' == $settings['carousel_dots'] ) ) ? true : false;
			$carousel_nav  = ( isset( $settings['carousel_nav'] ) && ( 'true' == $settings['carousel_nav'] ) ) ? true : false;
			$carousel_autoplay  = ( isset( $settings['carousel_autoplay'] ) && ( 'true' == $settings['carousel_autoplay'] ) ) ? true : false;
			$carousel_animate_out  = ( isset( $settings['carousel_animate_out'] ) && ( 'true' == $settings['carousel_animate_out'] ) ) ? true : false;
			$carousel_mousedrag  = ( isset( $settings['carousel_mousedrag'] ) && ( 'true' == $settings['carousel_mousedrag'] ) ) ? $settings['carousel_mousedrag'] : 'false';
			$carousel_autowidth  = ( isset( $settings['carousel_autowidth'] ) && ( 'true' == $settings['carousel_autowidth'] ) ) ? true : false;
			$carousel_autoheight  = ( isset( $settings['carousel_autoheight'] ) && ( 'true' == $settings['carousel_autoheight'] ) ) ? true : false;

		// Carousel Data's
			$carousel_loop = $carousel_loop !== 'true' ? ' data-loop="true"' : ' data-loop="false"';
			$carousel_items = $carousel_items ? ' data-items="'. $carousel_items .'"' : ' data-items="1"';
			$carousel_margin = $carousel_margin ? ' data-margin="'. $carousel_margin .'"' : ' data-margin="0"';
			$carousel_dots = $carousel_dots ? ' data-dots="true"' : ' data-dots="false"';
			$carousel_nav = $carousel_nav ? ' data-nav="true"' : ' data-nav="false"';
			$carousel_autoplay_timeout = $carousel_autoplay_timeout ? ' data-autoplay-timeout="'. $carousel_autoplay_timeout .'"' : '';
			$carousel_autoplay = $carousel_autoplay ? ' data-autoplay="true"' : '';
			$carousel_animate_out = $carousel_animate_out ? ' data-animateout="true"' : '';
			$carousel_mousedrag = $carousel_mousedrag !== 'true' ? ' data-mouse-drag="true"' : ' data-mouse-drag="false"';
			$carousel_autowidth = $carousel_autowidth ? ' data-auto-width="true"' : '';
			$carousel_autoheight = $carousel_autoheight ? ' data-auto-height="true"' : '';
			$carousel_tablet = $carousel_items_tablet ? ' data-items-tablet="'. $carousel_items_tablet .'"' : ' data-items-tablet="1"';
			$carousel_mobile = $carousel_items_mobile ? ' data-items-mobile-landscape="'. $carousel_items_mobile .'"' : ' data-items-mobile-landscape="1"';
			$carousel_small_mobile = $carousel_items_mobile ? ' data-items-mobile-portrait="'. $carousel_items_mobile .'"' : ' data-items-mobile-portrait="1"';

		$needs_title = $needs_title ? '<h5>'.esc_html($needs_title).'</h5>' : '';
		$needs_number = $needs_number ? '<span>'.esc_html($needs_number).'</span> ' : '';
		$needs_content = $needs_content ? '<h2>'.$needs_number.esc_html($needs_content).'</h2>' : '';

		$output = '';
		$output .= '<div class="nacep-needs-wrap"><div class="col-na-row align-items-center">
		<div class="col-na-6">
			<div class="nacep-needs-info">
				'.$needs_title.$needs_content.'
				<ul class="needs-list">';
					foreach ( $needsList as $each_list ) {
						$needs_list = !empty( $each_list['needs_list'] ) ? $each_list['needs_list'] : '';
						$output .= '<li>'.$needs_list.'</li>';
					}
				$output .= '</ul>
			</div>
		</div>
		<div class="col-na-6">
		<div class="owl-carousel" '. $carousel_loop . $carousel_items . $carousel_margin . $carousel_dots . $carousel_nav . $carousel_autoplay_timeout . $carousel_autoplay . $carousel_animate_out . $carousel_mousedrag . $carousel_autowidth . $carousel_autoheight  . $carousel_tablet . $carousel_mobile . $carousel_small_mobile .'>';
			// Group Param Output
			foreach ( $needsItem as $each_logo ) {
				$needs_image = !empty( $each_logo['needs_image']['id'] ) ? $each_logo['needs_image']['id'] : '';
				$image_url = wp_get_attachment_url( $needs_image );

				$needs_image = $image_url ? '<div class="nacep-image"><img src="'.esc_url($image_url).'" alt="Slider"></div>' : '';
				$output .= '<div class="item"><div class="needs-image">'.$needs_image.'</div></div>';
			}
		$output .= '</div></div></div></div>';
		echo $output;

	}

}
Plugin::instance()->widgets_manager->register_widget_type( new Charity_Elementor_Addon_Unique_Peoples_Needs() );
