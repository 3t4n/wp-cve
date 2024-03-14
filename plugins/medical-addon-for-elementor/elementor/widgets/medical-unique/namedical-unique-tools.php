<?php
/*
 * Elementor Medical Addon for Elementor Tools Widget
 * Author & Copyright: NicheAddon
*/

namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Medical_Elementor_Addon_Unique_Tools extends Widget_Base{

	/**
	 * Retrieve the widget name.
	*/
	public function get_name(){
		return 'namedical_unique_tools';
	}

	/**
	 * Retrieve the widget title.
	*/
	public function get_title(){
		return esc_html__( 'Tools', 'medical-addon-for-elementor' );
	}

	/**
	 * Retrieve the widget icon.
	*/
	public function get_icon() {
		return 'eicon-tools';
	}

	/**
	 * Retrieve the list of categories the widget belongs to.
	*/
	public function get_categories() {
		return ['namedical-unique-category'];
	}

	/**
	 * Register Medical Addon for Elementor Tools widget controls.
	 * Adds different input fields to allow the user to change and customize the widget settings.
	*/
	protected function register_controls(){

		$this->start_controls_section(
			'section_tools',
			[
				'label' => __( 'Tools Options', 'medical-addon-for-elementor' ),
			]
		);
		$this->add_control(
			'tools_style',
			[
				'label' => esc_html__( 'Tools Style', 'medical-addon-for-elementor' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'one' => esc_html__( 'Style One', 'medical-addon-for-elementor' ),
					'two' => esc_html__( 'Style Two', 'medical-addon-for-elementor' ),
				],
				'default' => 'one',
				'description' => esc_html__( 'Select your tools style.', 'medical-addon-for-elementor' ),
			]
		);
		$this->add_control(
			'tools_col',
			[
				'label' => esc_html__( 'Tools Column', 'medical-addon-for-elementor' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'2' => esc_html__( '2 Column', 'medical-addon-for-elementor' ),
					'3' => esc_html__( '3 Column', 'medical-addon-for-elementor' ),
					'4' => esc_html__( '4 Column', 'medical-addon-for-elementor' ),
					'6' => esc_html__( '6 Column', 'medical-addon-for-elementor' ),
				],
				'default' => '6',
				'description' => esc_html__( 'Select your tools column.', 'medical-addon-for-elementor' ),
			]
		);
		$repeater = new Repeater();
		$repeater->add_control(
			'upload_type',
			[
				'label' => __( 'Icon Type', 'medical-addon-for-elementor' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'image' => esc_html__( 'Image', 'medical-addon-for-elementor' ),
					'icon' => esc_html__( 'Icon', 'medical-addon-for-elementor' ),
				],
				'default' => 'icon',
			]
		);
		$repeater->add_control(
			'tools_image',
			[
				'label' => esc_html__( 'Upload Icon', 'medical-addon-for-elementor' ),
				'type' => Controls_Manager::MEDIA,
				'condition' => [
					'upload_type' => 'image',
				],
				'frontend_available' => true,
				'default' => [
					'url' => Utils::get_placeholder_image_src(),
				],
				'description' => esc_html__( 'Set your icon image.', 'medical-addon-for-elementor'),
			]
		);
		$repeater->add_control(
			'tools_icon',
			[
				'label' => esc_html__( 'Select Icon', 'medical-addon-for-elementor' ),
				'type' => Controls_Manager::ICON,
				'options' => NAMEP_Controls_Helper_Output::get_include_icons(),
				'frontend_available' => true,
				'default' => 'fa fa-cog',
				'condition' => [
					'upload_type' => 'icon',
				],
			]
		);
		$repeater->add_control(
			'tools_link',
			[
				'label' => esc_html__( 'Tools Link', 'medical-elementor-addon' ),
				'type' => Controls_Manager::URL,
				'placeholder' => 'https://your-link.com',
				'default' => [
					'url' => '',
				],
				'label_block' => true,
			]
		);
		$repeater->add_control(
			'tools_title',
			[
				'label' => esc_html__( 'Title', 'medical-addon-for-elementor' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'Pediatrics', 'medical-addon-for-elementor' ),
				'placeholder' => esc_html__( 'Type title text here', 'medical-addon-for-elementor' ),
				'label_block' => true,
			]
		);
		$this->add_control(
			'tools_groups',
			[
				'label' => esc_html__( 'Tools Items', 'medical-addon-for-elementor' ),
				'type' => Controls_Manager::REPEATER,
				'default' => [
					[
						'tools_title' => esc_html__( 'Pediatrics', 'medical-addon-for-elementor' ),
					],

				],
				'fields' => $repeater->get_controls(),
				'title_field' => '{{{ tools_title }}}',
			]
		);
		$this->end_controls_section();// end: Section

		// Section
			$this->start_controls_section(
				'sectn_style',
				[
					'label' => esc_html__( 'Section', 'medical-addon-for-elementor' ),
					'tab' => Controls_Manager::TAB_STYLE,
				]
			);
			$this->add_responsive_control(
				'tools_section_padding',
				[
					'label' => __( 'Section Padding', 'medical-addon-for-elementor' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px' ],
					'selectors' => [
						'{{WRAPPER}} .namep-tool-item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);
			$this->start_controls_tabs( 'scn_style' );
				$this->start_controls_tab(
					'scn_normal',
					[
						'label' => esc_html__( 'Normal', 'medical-addon-for-elementor' ),
					]
				);
				$this->add_control(
					'secn_bg_color',
					[
						'label' => esc_html__( 'Background Color', 'medical-addon-for-elementor' ),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .namep-tool-item' => 'background-color: {{VALUE}};',
						],
					]
				);
				$this->add_group_control(
					Group_Control_Border::get_type(),
					[
						'name' => 'secn_border',
						'label' => esc_html__( 'Border', 'medical-addon-for-elementor' ),
						'selector' => '{{WRAPPER}} .namep-tool-item',
					]
				);
				$this->add_group_control(
					Group_Control_Box_Shadow::get_type(),
					[
						'name' => 'section_box_shadow',
						'label' => esc_html__( 'Box Shadow', 'medical-addon-for-elementor' ),
						'selector' => '{{WRAPPER}} .namep-tool-item',
					]
				);
				$this->end_controls_tab();  // end:Normal tab
				$this->start_controls_tab(
					'list_hover',
					[
						'label' => esc_html__( 'Hover', 'medical-addon-for-elementor' ),
					]
				);
				$this->add_control(
					'secn_hov_bg_color',
					[
						'label' => esc_html__( 'Background Color', 'medical-addon-for-elementor' ),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .namep-tool-item.namep-hover' => 'background-color: {{VALUE}};',
						],
					]
				);
				$this->add_group_control(
					Group_Control_Border::get_type(),
					[
						'name' => 'secn_hov_border',
						'label' => esc_html__( 'Border', 'medical-addon-for-elementor' ),
						'selector' => '{{WRAPPER}} .namep-tool-item.namep-hover',
					]
				);
				$this->add_group_control(
					Group_Control_Box_Shadow::get_type(),
					[
						'name' => 'section_hov_box_shadow',
						'label' => esc_html__( 'Box Shadow', 'medical-addon-for-elementor' ),
						'selector' => '{{WRAPPER}} .namep-tool-item.namep-hover',
					]
				);
				$this->end_controls_tab();  // end:Hover tab
			$this->end_controls_tabs(); // end tabs
			$this->end_controls_section();// end: Section

		// Title
			$this->start_controls_section(
				'dept_title_style',
				[
					'label' => esc_html__( 'Section Title', 'medical-addon-for-elementor' ),
					'tab' => Controls_Manager::TAB_STYLE,
				]
			);
			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'label' => esc_html__( 'Typography', 'medical-addon-for-elementor' ),
					'name' => 'dept_title_typography',
					'selector' => '{{WRAPPER}} .namep-tool-title',
				]
			);
			$this->add_control(
				'dept_title_color',
				[
					'label' => esc_html__( 'Color', 'medical-addon-for-elementor' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .namep-tool-title' => 'color: {{VALUE}};',
					],
				]
			);
			$this->end_controls_section();// end: Section

		// Icon
			$this->start_controls_section(
				'section_icon_style',
				[
					'label' => esc_html__( 'Icon', 'medical-addon-for-elementor' ),
					'tab' => Controls_Manager::TAB_STYLE,
				]
			);
			$this->add_control(
				'icon_color',
				[
					'label' => esc_html__( 'Icon Color', 'medical-addon-for-elementor' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .namep-icon' => 'color: {{VALUE}};',
					],
				]
			);
			$this->add_control(
				'icon_size',
				[
					'label' => esc_html__( 'Icon Size', 'medical-addon-for-elementor' ),
					'type' => Controls_Manager::SLIDER,
					'range' => [
						'px' => [
							'min' => 0,
							'max' => 500,
							'step' => 1,
						],
					],
					'size_units' => [ 'px' ],
					'selectors' => [
						'{{WRAPPER}} .namep-icon i' => 'font-size: {{SIZE}}{{UNIT}};line-height: {{SIZE}}{{UNIT}};',
					],
				]
			);
			$this->end_controls_section();// end: Section

	}

	/**
	 * Render App Works widget output on the frontend.
	 * Written in PHP and used to generate the final HTML.
	*/
	protected function render() {
		$settings = $this->get_settings_for_display();
		$tools_style = !empty( $settings['tools_style'] ) ? $settings['tools_style'] : '';
		$tools_col = !empty( $settings['tools_col'] ) ? $settings['tools_col'] : '';
		$tools = $this->get_settings_for_display( 'tools_groups' );

		if ($tools_col === '2') {
			$col_cls = 'nich-col-xl-6 nich-col-lg-6 nich-col-md-6';			
		} elseif ($tools_col === '3') {
			$col_cls = 'nich-col-xl-4 nich-col-lg-4 nich-col-md-4';
		} elseif ($tools_col === '4') {
			$col_cls = 'nich-col-xl-3 nich-col-lg-4 nich-col-md-6';
		} else {
			$col_cls = 'nich-col-xl-2 nich-col-lg-4 nich-col-md-4';
		}

		if ($tools_style === 'two') {
			$style_cls = 'care';
		} else {
			$style_cls = 'tool';
		}
		
		$output = '<div class="namep-tools-wrap"><div class="nich-row nich-justify-content-center">';
		// Group Param Output
		foreach ( $tools as $each_logo ) {
			$upload_type = !empty( $each_logo['upload_type'] ) ? $each_logo['upload_type'] : '';
			$tools_link = !empty( $each_logo['tools_link']['url'] ) ? esc_url($each_logo['tools_link']['url']) : '';
			$tools_link_external = !empty( $tools_link['is_external'] ) ? 'target="_blank"' : '';
			$tools_link_nofollow = !empty( $tools_link['nofollow'] ) ? 'rel="nofollow"' : '';
			$tools_link_attr = !empty( $tools_link['url'] ) ?  $tools_link_external.' '.$tools_link_nofollow : '';
			$tools_title = !empty( $each_logo['tools_title'] ) ? $each_logo['tools_title'] : '';

			$tools_image = !empty( $each_logo['tools_image']['id'] ) ? $each_logo['tools_image']['id'] : '';
			$tools_icon = !empty( $each_logo['tools_icon'] ) ? $each_logo['tools_icon'] : '';

			$image_url = wp_get_attachment_url( $tools_image );
			$tools_image = $image_url ? '<span class="namep-image"><img src="'.esc_url($image_url).'" alt="'.esc_attr($tools_title).'"></span>' : '';
			$tools_icon = $tools_icon ? '<span class="namep-icon"><i class="'.esc_attr($tools_icon).'"></i></span>' : '';

			if ($upload_type === 'icon'){
			  $icon_main = $tools_icon;
			} else {
			  $icon_main = $tools_image;
			}
	  	$title = !empty( $tools_title ) ? '<span class="namep-'.$style_cls.'-title">'.$tools_title.'</span>' : '';
	  	$output .= '<div class="'.$col_cls.'">';
	  	if ($tools_link) {
			  $output .= '<a href="'.esc_url($tools_link).'" '.$tools_link_attr.' class="namep-'.$style_cls.'-item">'.$icon_main.$title.'</a>';
	  	} else {
	  		$output .= '<div class="namep-'.$style_cls.'-item">'.$icon_main.$title.'</div>';
	  	}
	  	$output .= '</div>';
		}
		$output .= '</div></div>';
		echo $output;

	}

}
Plugin::instance()->widgets_manager->register_widget_type( new Medical_Elementor_Addon_Unique_Tools() );
