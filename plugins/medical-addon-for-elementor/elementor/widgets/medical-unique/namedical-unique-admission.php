<?php
/*
 * Elementor Medical Addon for Elementor Admission Widget
 * Author & Copyright: NicheAddon
*/

namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Medical_Elementor_Addon_Unique_Admission extends Widget_Base{

	/**
	 * Retrieve the widget name.
	*/
	public function get_name(){
		return 'namedical_unique_admission';
	}

	/**
	 * Retrieve the widget title.
	*/
	public function get_title(){
		return esc_html__( 'Admission', 'medical-addon-for-elementor' );
	}

	/**
	 * Retrieve the widget icon.
	*/
	public function get_icon() {
		return 'eicon-bullet-list';
	}

	/**
	 * Retrieve the list of categories the widget belongs to.
	*/
	public function get_categories() {
		return ['namedical-unique-category'];
	}

	/**
	 * Register Medical Addon for Elementor Admission widget controls.
	 * Adds different input fields to allow the user to change and customize the widget settings.
	*/
	protected function register_controls(){

		$this->start_controls_section(
			'section_admission',
			[
				'label' => __( 'Admission Options', 'medical-addon-for-elementor' ),
			]
		);
		$this->start_controls_tabs( 'admission_list' );
			$this->start_controls_tab(
				'list_one',
				[
					'label' => esc_html__( 'List One', 'medical-addon-for-elementor' ),
				]
			);
			$repeater = new Repeater();
			$repeater->add_control(
				'list_text',
				[
					'label' => esc_html__( 'List Text', 'medical-addon-for-elementor' ),
					'type' => Controls_Manager::TEXT,
					'default' => esc_html__( 'Medical Students', 'medical-addon-for-elementor' ),
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
					'default' => [
						[
							'list_text' => esc_html__( 'Medical Students', 'medical-addon-for-elementor' ),
						],
					],
					'fields' => $repeater->get_controls(),
					'title_field' => '{{{ list_text }}}',
					'prevent_empty' => false,
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
			$this->end_controls_tab();  // end:Normal tab
			$this->start_controls_tab(
				'list_two',
				[
					'label' => esc_html__( 'List Two', 'medical-addon-for-elementor' ),
				]
			);
			$repeaterTwo = new Repeater();
			$repeaterTwo->add_control(
				'list_text',
				[
					'label' => esc_html__( 'List Text', 'medical-addon-for-elementor' ),
					'type' => Controls_Manager::TEXT,
					'default' => esc_html__( 'Medical Students', 'medical-addon-for-elementor' ),
					'placeholder' => esc_html__( 'Type title text here', 'medical-addon-for-elementor' ),
					'label_block' => true,
				]
			);
			$repeaterTwo->add_control(
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
				'listItemsTwo_groups',
				[
					'label' => esc_html__( 'List', 'medical-addon-for-elementor' ),
					'type' => Controls_Manager::REPEATER,
					'default' => [
						[
							'list_text' => esc_html__( 'Medical Students', 'medical-addon-for-elementor' ),
						],
					],
					'fields' => $repeaterTwo->get_controls(),
					'title_field' => '{{{ list_text }}}',
					'prevent_empty' => false,
				]
			);
			$this->add_control(
				'btn_text_two',
				[
					'label' => esc_html__( 'Button Text', 'medical-addon-for-elementor' ),
					'type' => Controls_Manager::TEXT,
					'default' => esc_html__( 'Buy Now', 'medical-addon-for-elementor' ),
					'placeholder' => esc_html__( 'Type title text here', 'medical-addon-for-elementor' ),
					'label_block' => true,
				]
			);
			$this->add_control(
				'btn_link_two',
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
			$this->end_controls_tab();  // end:Hover tab
		$this->end_controls_tabs(); // end tabs
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
							'min' => 500,
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
						'{{WRAPPER}} .namep-admission-wrap' => 'max-width:{{SIZE}}{{UNIT}};',
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
						'{{WRAPPER}} .namep-admission-list' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);
			$this->add_control(
				'section_bg_color',
				[
					'label' => esc_html__( 'Background Color', 'medical-addon-for-elementor' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .namep-admission-list' => 'background-color: {{VALUE}};',
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
						'{{WRAPPER}} .namep-admission-inner-wrap' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);
			$this->add_control(
				'section_bdr_color',
				[
					'label' => esc_html__( 'Border Color', 'medical-addon-for-elementor' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .namep-admission-list' => 'border-color: {{VALUE}};',
					],
				]
			);
			$this->add_group_control(
				Group_Control_Box_Shadow::get_type(),
				[
					'name' => 'section_box_shadow',
					'label' => esc_html__( 'Box Shadow', 'medical-addon-for-elementor' ),
					'selector' => '{{WRAPPER}} .namep-admission-inner-wrap',
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
						'{{WRAPPER}} .namep-bullet-list li' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);
			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'label' => esc_html__( 'Typography', 'medical-addon-for-elementor' ),
					'name' => 'list_typography',
					'selector' => '{{WRAPPER}} .namep-bullet-list li',
				]
			);
			$this->add_control(
				'icon_color',
				[
					'label' => esc_html__( 'Icon Color', 'medical-addon-for-elementor' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .namep-bullet-list li:before' => 'color: {{VALUE}};',
					],
				]
			);
			$this->add_control(
				'icon_bg_color',
				[
					'label' => esc_html__( 'Icon Background Color', 'medical-addon-for-elementor' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .namep-bullet-list li:before' => 'background-color: {{VALUE}};',
					],
				]
			);
			$this->start_controls_tabs( 'list_style' );
				$this->start_controls_tab(
					'list_normal',
					[
						'label' => esc_html__( 'Normal', 'medical-addon-for-elementor' ),
					]
				);
				$this->add_control(
					'list_color',
					[
						'label' => esc_html__( 'Color', 'medical-addon-for-elementor' ),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .namep-bullet-list li, {{WRAPPER}} .namep-bullet-list li a' => 'color: {{VALUE}};',
						],
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
					'list_hover_color',
					[
						'label' => esc_html__( 'Color', 'medical-addon-for-elementor' ),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .namep-bullet-list li a:hover' => 'color: {{VALUE}};',
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
		$btn_text = !empty( $settings['btn_text'] ) ? $settings['btn_text'] : '';
		$btn_link = !empty( $settings['btn_link']['url'] ) ? $settings['btn_link']['url'] : '';
		$btn_link_external = !empty( $settings['btn_link']['is_external'] ) ? 'target="_blank"' : '';
		$btn_link_nofollow = !empty( $settings['btn_link']['nofollow'] ) ? 'rel="nofollow"' : '';
		$btn_link_attr = !empty( $btn_link ) ?  $btn_link_external.' '.$btn_link_nofollow : '';

		$btn_text_two = !empty( $settings['btn_text_two'] ) ? $settings['btn_text_two'] : '';
		$btn_link_two = !empty( $settings['btn_link_two']['url'] ) ? $settings['btn_link_two']['url'] : '';
		$btn_link_two_external = !empty( $settings['btn_link_two']['is_external'] ) ? 'target="_blank"' : '';
		$btn_link_two_nofollow = !empty( $settings['btn_link_two']['nofollow'] ) ? 'rel="nofollow"' : '';
		$btn_link_two_attr = !empty( $btn_link_two ) ?  $btn_link_two_external.' '.$btn_link_two_nofollow : '';

		$listItems_groups = !empty( $settings['listItems_groups'] ) ? $settings['listItems_groups'] : '';
		$listItemsTwo_groups = !empty( $settings['listItemsTwo_groups'] ) ? $settings['listItemsTwo_groups'] : '';

		$button = $btn_link ? '<a href="'.esc_url($btn_link).'" '.$btn_link_attr.' class="namep-btn btn-style-two">'. esc_html($btn_text) .'</a>' : '';
		$buttonTwo = $btn_link_two ? '<a href="'.esc_url($btn_link_two).'" '.$btn_link_two_attr.' class="namep-btn btn-style-two">'. esc_html($btn_text_two) .'</a>' : '';
		
		$output = '<div class="namep-admission-wrap">
								<div class="namep-admission-inner-wrap">
            			<div class="nich-row">
            				<div class="nich-col-md-6">
			                <div class="namep-admission-list">
			                  <ul class="namep-bullet-list">';
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
            $output .= '</ul>'.$button.'
			                </div>
			              </div>
			              <div class="nich-col-md-6">
			              	<div class="namep-admission-list">
			                  <ul class="namep-bullet-list">';
													if ( is_array( $listItemsTwo_groups ) && !empty( $listItemsTwo_groups ) ) {
													  foreach ( $listItemsTwo_groups as $each_list ) {
													  	$list_text = !empty( $each_list['list_text'] ) ? $each_list['list_text'] : '';
													  	$text_link = !empty( $each_list['text_link']['url'] ) ? $each_list['text_link']['url'] : '';
															$text_link_external = !empty( $each_list['text_link']['is_external'] ) ? 'target="_blank"' : '';
															$text_link_nofollow = !empty( $each_list['text_link']['nofollow'] ) ? 'rel="nofollow"' : '';
															$text_link_attr = !empty( $text_link ) ?  $text_link_external.' '.$text_link_nofollow : '';

													  	$text = $text_link ? '<li><a href="'.esc_url($text_link).'" '.$text_link_attr.'>'. esc_html($list_text) .'</a></li>' : '<li>'. esc_html($list_text) .'</li>';
					                  	$output .= $text;
						                }
						              }
            $output .= '</ul>'.$buttonTwo.'
			                </div>
			              </div>
		              </div>
              	</div>
              </div>';
		echo $output;

	}

}
Plugin::instance()->widgets_manager->register_widget_type( new Medical_Elementor_Addon_Unique_Admission() );
