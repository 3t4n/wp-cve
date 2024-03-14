<?php
/*
 * Elementor Medical Addon for Elementor Departments Widget
 * Author & Copyright: NicheAddon
*/

namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Medical_Elementor_Addon_Unique_Departments extends Widget_Base{

	/**
	 * Retrieve the widget name.
	*/
	public function get_name(){
		return 'namedical_unique_departments';
	}

	/**
	 * Retrieve the widget title.
	*/
	public function get_title(){
		return esc_html__( 'Departments', 'medical-addon-for-elementor' );
	}

	/**
	 * Retrieve the widget icon.
	*/
	public function get_icon() {
		return 'eicon-time-line';
	}

	/**
	 * Retrieve the list of categories the widget belongs to.
	*/
	public function get_categories() {
		return ['namedical-unique-category'];
	}

	/**
	 * Register Medical Addon for Elementor Departments widget controls.
	 * Adds different input fields to allow the user to change and customize the widget settings.
	*/
	protected function register_controls(){

		$this->start_controls_section(
			'section_departments',
			[
				'label' => __( 'Departments Options', 'medical-addon-for-elementor' ),
			]
		);		
		$this->add_control(
			'section_title',
			[
				'label' => esc_html__( 'Section Title', 'medical-addon-for-elementor' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'Hospitalization', 'medical-addon-for-elementor' ),
				'placeholder' => esc_html__( 'Type title text here', 'medical-addon-for-elementor' ),
				'label_block' => true,
			]
		);
		$this->add_control(
			'section_content',
			[
				'label' => esc_html__( 'Content', 'medical-addon-for-elementor' ),
				'default' => esc_html__( 'Health care is the maintenance or improvement of health via the diagnosis, treatment, and prevention of disease, illness, injury etc. and prevention of disease, illness, injury.' ),
				'placeholder' => esc_html__( 'Type your content here.', 'medical-addon-for-elementor' ),
				'type' => Controls_Manager::TEXTAREA,
				'label_block' => true,
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
				'default' => 'image',
			]
		);
		$repeater->add_control(
			'departments_image',
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
			'departments_icon',
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
			'departments_link',
			[
				'label' => esc_html__( 'Departments Link', 'medical-elementor-addon' ),
				'type' => Controls_Manager::URL,
				'placeholder' => 'https://your-link.com',
				'default' => [
					'url' => '',
				],
				'label_block' => true,
			]
		);
		$repeater->add_control(
			'departments_title',
			[
				'label' => esc_html__( 'Title', 'medical-addon-for-elementor' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'Pediatrics', 'medical-addon-for-elementor' ),
				'placeholder' => esc_html__( 'Type title text here', 'medical-addon-for-elementor' ),
				'label_block' => true,
			]
		);
		$this->add_control(
			'departments_groups',
			[
				'label' => esc_html__( 'Departments Items', 'medical-addon-for-elementor' ),
				'type' => Controls_Manager::REPEATER,
				'default' => [
					[
						'departments_title' => esc_html__( 'Pediatrics', 'medical-addon-for-elementor' ),
					],

				],
				'fields' => $repeater->get_controls(),
				'title_field' => '{{{ departments_title }}}',
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
				'departments_section_padding',
				[
					'label' => __( 'Section Padding', 'medical-addon-for-elementor' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px' ],
					'selectors' => [
						'{{WRAPPER}} .namep-department-item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);
			$this->start_controls_tabs( 'secn_style' );
				$this->start_controls_tab(
					'secn_normal',
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
							'{{WRAPPER}} .namep-department-item' => 'background-color: {{VALUE}};',
						],
					]
				);
				$this->add_group_control(
					Group_Control_Border::get_type(),
					[
						'name' => 'secn_border',
						'label' => esc_html__( 'Border', 'medical-addon-for-elementor' ),
						'selector' => '{{WRAPPER}} .namep-department-item',
					]
				);
				$this->add_group_control(
					Group_Control_Box_Shadow::get_type(),
					[
						'name' => 'secn_box_shadow',
						'label' => esc_html__( 'Section Box Shadow', 'medical-addon-for-elementor' ),
						'selector' => '{{WRAPPER}} .namep-service-item',
					]
				);
				$this->end_controls_tab();  // end:Normal tab

				$this->start_controls_tab(
					'secn_hover',
					[
						'label' => esc_html__( 'Hover', 'medical-addon-for-elementor' ),
					]
				);
				$this->add_control(
					'secn_bg_hov_color',
					[
						'label' => esc_html__( 'Background Color', 'medical-addon-for-elementor' ),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .namep-department-item.namep-hover' => 'background-color: {{VALUE}};',
						],
					]
				);
				$this->add_group_control(
					Group_Control_Border::get_type(),
					[
						'name' => 'secn_hov_border',
						'label' => esc_html__( 'Border', 'medical-addon-for-elementor' ),
						'selector' => '{{WRAPPER}} .namep-department-item.namep-hover',
					]
				);
				$this->add_group_control(
					Group_Control_Box_Shadow::get_type(),
					[
						'name' => 'secn_hov_box_shadow',
						'label' => esc_html__( 'Section Box Shadow', 'medical-addon-for-elementor' ),
						'selector' => '{{WRAPPER}} .namep-service-item.namep-hover',
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
					'selector' => '{{WRAPPER}} .namep-section-title h2',
				]
			);
			$this->add_control(
				'dept_title_color',
				[
					'label' => esc_html__( 'Color', 'medical-addon-for-elementor' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .namep-section-title h2' => 'color: {{VALUE}};',
					],
				]
			);
			$this->end_controls_section();// end: Section

		// Content
			$this->start_controls_section(
				'dept_content_style',
				[
					'label' => esc_html__( 'Section Content', 'medical-addon-for-elementor' ),
					'tab' => Controls_Manager::TAB_STYLE,
				]
			);
			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'label' => esc_html__( 'Typography', 'medical-addon-for-elementor' ),
					'name' => 'dept_content_typography',
					'selector' => '{{WRAPPER}} .namep-section-title p',
				]
			);
			$this->add_control(
				'dept_content_color',
				[
					'label' => esc_html__( 'Color', 'medical-addon-for-elementor' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .namep-section-title p' => 'color: {{VALUE}};',
					],
				]
			);
			$this->end_controls_section();// end: Section

		// Image
			$this->start_controls_section(
				'section_image_style',
				[
					'label' => esc_html__( 'Image', 'medical-addon-for-elementor' ),
					'tab' => Controls_Manager::TAB_STYLE,
					'condition' => [
						'upload_type' => array('image'),
					],
				]
			);
			$this->add_control(
				'image_padding',
				[
					'label' => __( 'Image Padding', 'medical-addon-for-elementor' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%', 'em' ],
					'selectors' => [
						'{{WRAPPER}} .namep-department-item .namep-image' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
					'condition' => [
						'upload_type' => array('icon'),
					],
				]
			);
			$this->add_control(
				'icon_color',
				[
					'label' => esc_html__( 'Icon Color', 'medical-addon-for-elementor' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .namep-department-item .namep-icon i' => 'color: {{VALUE}};',
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
						'{{WRAPPER}} .namep-department-item .namep-icon i' => 'font-size: {{SIZE}}{{UNIT}};line-height: {{SIZE}}{{UNIT}};',
					],
				]
			);
			$this->end_controls_section();// end: Section

		// Title
			$this->start_controls_section(
				'section_title_style',
				[
					'label' => esc_html__( 'Department Title', 'medical-addon-for-elementor' ),
					'tab' => Controls_Manager::TAB_STYLE,
				]
			);
			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'label' => esc_html__( 'Typography', 'medical-addon-for-elementor' ),
					'name' => 'sasstp_title_typography',
					'selector' => '{{WRAPPER}} .namep-department-title',
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
							'{{WRAPPER}} .namep-department-title' => 'color: {{VALUE}};',
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
					'title_hov_color',
					[
						'label' => esc_html__( 'Color', 'medical-addon-for-elementor' ),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} a:hover .namep-department-title' => 'color: {{VALUE}};',
						],
					]
				);
				$this->end_controls_tab();  // end:Hover tab
			$this->end_controls_tabs(); // end tabs
			$this->end_controls_section();// end: Section

		// Content
			$this->start_controls_section(
				'section_content_style',
				[
					'label' => esc_html__( 'Department Content', 'medical-addon-for-elementor' ),
					'tab' => Controls_Manager::TAB_STYLE,
				]
			);
			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'label' => esc_html__( 'Typography', 'medical-addon-for-elementor' ),
					'name' => 'content_typography',
					'selector' => '{{WRAPPER}} .namep-department-item p',
				]
			);
			$this->add_control(
				'content_color',
				[
					'label' => esc_html__( 'Color', 'medical-addon-for-elementor' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .namep-department-item p' => 'color: {{VALUE}};',
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
		$section_title = !empty( $settings['section_title'] ) ? $settings['section_title'] : '';
		$section_content = !empty( $settings['section_content'] ) ? $settings['section_content'] : '';
		$departments = $this->get_settings_for_display( 'departments_groups' );
		
		$title = $section_title ? '<h2 class="section-title">'.esc_html($section_title).'</h2>' : '';
		$content = $section_content ? '<p>'.esc_html($section_content).'</p>' : '';

		$output = '<div class="namep-departments-wrap"><div class="nich-row">
								<div class="nich-col-lg-6 nich-col-md-8">
			            <div class="namep-section-title">'.$title.$content.'</div>
			          </div>';
		// Group Param Output
		foreach ( $departments as $each_logo ) {
			$upload_type = !empty( $each_logo['upload_type'] ) ? $each_logo['upload_type'] : '';
			$departments_link = !empty( $each_logo['departments_link']['url'] ) ? esc_url($each_logo['departments_link']['url']) : '';
			$departments_link_external = !empty( $departments_link['is_external'] ) ? 'target="_blank"' : '';
			$departments_link_nofollow = !empty( $departments_link['nofollow'] ) ? 'rel="nofollow"' : '';
			$departments_link_attr = !empty( $departments_link['url'] ) ?  $departments_link_external.' '.$departments_link_nofollow : '';
			$departments_title = !empty( $each_logo['departments_title'] ) ? $each_logo['departments_title'] : '';

			$departments_image = !empty( $each_logo['departments_image']['id'] ) ? $each_logo['departments_image']['id'] : '';
			$departments_icon = !empty( $each_logo['departments_icon'] ) ? $each_logo['departments_icon'] : '';

			$image_url = wp_get_attachment_url( $departments_image );
			$departments_image = $image_url ? '<span class="namep-image"><img src="'.esc_url($image_url).'" alt="'.esc_attr($departments_title).'"></span>' : '';
			$departments_icon = $departments_icon ? '<span class="namep-icon"><i class="'.esc_attr($departments_icon).'"></i></span>' : '';

			if ($upload_type === 'icon'){
			  $icon_main = $departments_icon;
			} else {
			  $icon_main = $departments_image;
			}
	  	$title = !empty( $departments_title ) ? '<span class="namep-department-title">'.$departments_title.'</span>' : '';

	  	if ($departments_link) {
			  $output .= '<div class="nich-col-lg-3 nich-col-md-4 nich-col-sm-6">
			  							<a href="'.esc_url($departments_link).'" '.$departments_link_attr.' class="namep-department-item">'.$icon_main.$title.'</a>
				            </div>';
	  	} else {
	  		$output .= '<div class="nich-col-lg-3 nich-col-md-4 nich-col-sm-6">
			  							<div class="namep-department-item">'.$icon_main.$title.'</div>
				            </div>';
	  	}
		}
		$output .= '</div></div>';
		echo $output;

	}

}
Plugin::instance()->widgets_manager->register_widget_type( new Medical_Elementor_Addon_Unique_Departments() );
