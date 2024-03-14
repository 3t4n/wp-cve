<?php
/*
 * Elementor Education Addon Events Widget
 * Author & Copyright: NicheAddon
*/

namespace Elementor;

if (!isset(get_option( 'naedu_bw_settings' )['naedu_event'])) { // enable & disable

if ( is_plugin_active( 'the-events-calendar/the-events-calendar.php' ) ) {

	if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

	class Education_Elementor_Addon_Events extends Widget_Base{

		/**
		 * Retrieve the widget name.
		*/
		public function get_name(){
			return 'naedu_basic_event';
		}

		/**
		 * Retrieve the widget title.
		*/
		public function get_title(){
			return esc_html__( 'Event Listing', 'education-addon' );
		}

		/**
		 * Retrieve the widget icon.
		*/
		public function get_icon() {
			return 'eicon-archive-posts';
		}

		/**
		 * Retrieve the list of categories the widget belongs to.
		*/
		public function get_categories() {
			return ['naedu-basic-category'];
		}

		/**
		 * Register Events Addon for Elementor TEC Event widget controls.
		 * Adds different input fields to allow the user to change and customize the widget settings.
		*/
		protected function register_controls(){

			$args = array(
	    'post_type' => 'tribe_events',
	    'posts_per_page' => -1,
	    );
	    $pages = get_posts($args);
	    $event_post = array();
	    if ( $pages ) {
	      foreach ( $pages as $page ) {
	        $event_post[ $page->ID ] = $page->post_title;
	      }
	    } else {
	      $event_post[ esc_html__( 'No Events found', 'education-addon' ) ] = 0;
	    }

			$this->start_controls_section(
				'section_event_settings',
				[
					'label' => esc_html__( 'Event Options', 'education-addon' ),
				]
			);
			$this->add_control(
				'event_style',
				[
					'label' => __( 'Event Style', 'education-addon' ),
					'type' => Controls_Manager::SELECT,
					'options' => [
						'one' => esc_html__( 'Style One', 'education-addon' ),
						'two' => esc_html__( 'Style Two', 'education-addon' ),
					],
					'default' => 'one',
					'description' => esc_html__( 'Select your event style.', 'education-addon' ),
				]
			);
			$this->add_control(
				'event_col',
				[
					'label' => esc_html__( 'Courses Column', 'education-addon' ),
					'type' => Controls_Manager::SELECT,
					'options' => [
						'1'          => esc_html__('One', 'education-addon'),
						'2'          => esc_html__('Two', 'education-addon'),
	          '3'          => esc_html__('Three', 'education-addon'),
	          '4'          => esc_html__('Four', 'education-addon'),
					],
					'default' => '2',
				]
			);
			$this->add_control(
				'event_list_heading',
				[
					'label' => __( 'Listing', 'education-addon' ),
					'type' => Controls_Manager::HEADING,
					'separator' => 'before',
				]
			);
			$this->add_control(
				'event_limit',
				[
					'label' => esc_html__( 'Limit', 'education-addon' ),
					'type' => Controls_Manager::NUMBER,
					'min' => -1,
					'default' => -1,
					'step' => 1,
				]
			);
			$this->add_control(
				'event_order',
				[
					'label' => esc_html__( 'Order', 'education-addon' ),
					'type' => Controls_Manager::SELECT,
					'default' => 'DESC',
					'options' => [
						'DESC' => esc_html__('DESC', 'education-addon'),
						'ASC' => esc_html__('ASC', 'education-addon'),
					],
				]
			);
			$this->add_control(
				'event_orderby',
				[
					'label' => esc_html__( 'Order By', 'education-addon' ),
					'type' => Controls_Manager::SELECT2,
					'default' => '',
					'options' => [
						'none' => esc_html__('None', 'education-addon'),
						'ID' => esc_html__('ID', 'education-addon'),
						'author' => esc_html__('Author', 'education-addon'),
						'title' => esc_html__('Name', 'education-addon'),
						'date' => esc_html__('Date', 'education-addon'),
						'rand' => esc_html__('Rand', 'education-addon'),
						'menu_order' => esc_html__('Menu Order', 'education-addon'),
					],
				]
			);
			$this->add_control(
				'event_id',
				[
					'label' => __( 'Event ID', 'education-addon' ),
					'type' => Controls_Manager::SELECT2,
					'default' => [],
					'options' => $event_post,
					'multiple' => true,
				]
			);
			$this->add_control(
				'short_content',
				[
					'label' => esc_html__( 'Excerpt Length', 'education-addon' ),
					'type' => Controls_Manager::NUMBER,
					'min' => 1,
					'step' => 1,
					'default' => 15,
					'description' => esc_html__( 'How many words you want in short content paragraph.', 'education-addon' ),
				]
			);
			$this->add_control(
				'event_pagination',
				[
					'label' => esc_html__( 'Pagination?', 'education-addon' ),
					'type' => Controls_Manager::SWITCHER,
					'label_on' => esc_html__( 'Yes', 'education-addon' ),
					'label_off' => esc_html__( 'No', 'education-addon' ),
					'return_value' => 'true',
				]
			);
			$this->add_control(
				'btn_txt',
				[
					'label' => esc_html__( 'Button Text', 'education-addon' ),
					'type' => Controls_Manager::TEXT,
					'label_block' => true,
					'default' => esc_html__( 'Get Tickets', 'education-addon' ),
					'placeholder' => esc_html__( 'Type text here', 'education-addon' ),
				]
			);
			$this->end_controls_section();// end: Section

			// Section
				$this->start_controls_section(
					'sectn_style',
					[
						'label' => esc_html__( 'Section', 'education-addon' ),
						'tab' => Controls_Manager::TAB_STYLE,
					]
				);
				$this->add_responsive_control(
					'info_padding',
					[
						'label' => __( 'Section Spacing', 'education-addon' ),
						'type' => Controls_Manager::DIMENSIONS,
						'size_units' => [ 'px' ],
						'selectors' => [
							'{{WRAPPER}} .naedu-events figure' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						],
					]
				);
				$this->start_controls_tabs( 'scn_style' );
					$this->start_controls_tab(
						'scn_normal',
						[
							'label' => esc_html__( 'Normal', 'education-addon' ),
						]
					);
					$this->add_control(
						'secn_bg_color',
						[
							'label' => esc_html__( 'Background Color', 'education-addon' ),
							'type' => Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .naedu-events figure' => 'background-color: {{VALUE}};',
							],
						]
					);
					$this->add_group_control(
						Group_Control_Border::get_type(),
						[
							'name' => 'secn_border',
							'label' => esc_html__( 'Border', 'education-addon' ),
							'selector' => '{{WRAPPER}} .naedu-events figure',
						]
					);
					$this->add_group_control(
						Group_Control_Box_Shadow::get_type(),
						[
							'name' => 'secn_box_shadow',
							'label' => esc_html__( 'Section Box Shadow', 'education-addon' ),
							'selector' => '{{WRAPPER}} .naedu-events figure',
						]
					);
					$this->end_controls_tab();  // end:Normal tab
					$this->start_controls_tab(
						'scn_hover',
						[
							'label' => esc_html__( 'Hover', 'education-addon' ),
						]
					);
					$this->add_control(
						'secn_hover_bg_color',
						[
							'label' => esc_html__( 'Background Color', 'education-addon' ),
							'type' => Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .naedu-events figure:hover' => 'background-color: {{VALUE}};',
							],
						]
					);
					$this->add_group_control(
						Group_Control_Border::get_type(),
						[
							'name' => 'secn_hover_border',
							'label' => esc_html__( 'Border', 'education-addon' ),
							'selector' => '{{WRAPPER}} .naedu-events figure:hover',
						]
					);
					$this->add_group_control(
						Group_Control_Box_Shadow::get_type(),
						[
							'name' => 'secn_hover_box_shadow',
							'label' => esc_html__( 'Section Box Shadow', 'education-addon' ),
							'selector' => '{{WRAPPER}} .naedu-events figure:hover',
						]
					);
					$this->end_controls_tab();  // end:Normal tab
				$this->end_controls_tabs(); // end tabs
				$this->end_controls_section();// end: Section

			// Title
				$this->start_controls_section(
					'section_name_style',
					[
						'label' => esc_html__( 'Event Title', 'education-addon' ),
						'tab' => Controls_Manager::TAB_STYLE,
					]
				);
				$this->add_responsive_control(
					'title_padding',
					[
						'label' => __( 'Padding', 'education-addon' ),
						'type' => Controls_Manager::DIMENSIONS,
						'size_units' => [ 'px' ],
						'selectors' => [
							'{{WRAPPER}} .naedu-events figure h3' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						],
					]
				);
				$this->add_group_control(
					Group_Control_Typography::get_type(),
					[
						'name' => 'name_typography',
						'selector' => '{{WRAPPER}} .naedu-events figure h3',
					]
				);
				$this->start_controls_tabs( 'name_style' );
					$this->start_controls_tab(
						'title_normal',
						[
							'label' => esc_html__( 'Normal', 'education-addon' ),
						]
					);
					$this->add_control(
						'name_color',
						[
							'label' => esc_html__( 'Color', 'education-addon' ),
							'type' => Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .naedu-events figure h3, {{WRAPPER}} .naedu-events figure h3 a' => 'color: {{VALUE}};',
							],
						]
					);
					$this->end_controls_tab();  // end:Normal tab

					$this->start_controls_tab(
						'title_hover',
						[
							'label' => esc_html__( 'Hover', 'education-addon' ),
						]
					);
					$this->add_control(
						'name_hover_color',
						[
							'label' => esc_html__( 'Color', 'education-addon' ),
							'type' => Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .naedu-events figure h3 a:hover' => 'color: {{VALUE}};',
							],
						]
					);
					$this->end_controls_tab();  // end:Hover tab
				$this->end_controls_tabs(); // end tabs
				$this->end_controls_section();// end: Section

			// Meta
				$this->start_controls_section(
					'section_meta_style',
					[
						'label' => esc_html__( 'Meta', 'education-addon' ),
						'tab' => Controls_Manager::TAB_STYLE,
					]
				);
				$this->add_responsive_control(
					'meta_padding',
					[
						'label' => __( 'Padding', 'education-addon' ),
						'type' => Controls_Manager::DIMENSIONS,
						'size_units' => [ 'px' ],
						'selectors' => [
							'{{WRAPPER}} .naedu-events .naedu-meta li' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						],
					]
				);
				$this->add_responsive_control(
					'meta_margin',
					[
						'label' => __( 'Margin', 'education-addon' ),
						'type' => Controls_Manager::DIMENSIONS,
						'size_units' => [ 'px' ],
						'selectors' => [
							'{{WRAPPER}} .naedu-events .naedu-meta li' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						],
					]
				);
				$this->add_group_control(
					Group_Control_Typography::get_type(),
					[
						'name' => 'meta_typography',
						'selector' => '{{WRAPPER}} .naedu-events .naedu-meta li',
					]
				);
				$this->add_control(
					'meta_color',
					[
						'label' => esc_html__( 'Color', 'education-addon' ),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .naedu-events .naedu-meta li' => 'color: {{VALUE}};',
						],
					]
				);
				$this->end_controls_section();// end: Section

			// Price
				$this->start_controls_section(
					'section_price_style',
					[
						'label' => esc_html__( 'Price', 'education-addon' ),
						'tab' => Controls_Manager::TAB_STYLE,
					]
				);
				$this->add_responsive_control(
					'price_padding',
					[
						'label' => __( 'Padding', 'education-addon' ),
						'type' => Controls_Manager::DIMENSIONS,
						'size_units' => [ 'px' ],
						'selectors' => [
							'{{WRAPPER}} .event-auther span.event-cost' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						],
					]
				);
				$this->add_group_control(
					Group_Control_Typography::get_type(),
					[
						'name' => 'price_typography',
						'selector' => '{{WRAPPER}} .event-auther span.event-cost',
					]
				);
				$this->add_control(
					'price_color',
					[
						'label' => esc_html__( 'Color', 'education-addon' ),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .event-auther span.event-cost' => 'color: {{VALUE}};',
						],
					]
				);
				$this->end_controls_section();// end: Section

			// Date
				$this->start_controls_section(
					'date_style',
					[
						'label' => esc_html__( 'Date', 'education-addon' ),
						'tab' => Controls_Manager::TAB_STYLE,
					]
				);
				$this->add_control(
					'date_box_border_radius',
					[
						'label' => __( 'Border Radius', 'education-addon' ),
						'type' => Controls_Manager::DIMENSIONS,
						'size_units' => [ 'px', '%', 'em' ],
						'selectors' => [
							'{{WRAPPER}} .events-style-two .event-date' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						],
					]
				);
				$this->add_responsive_control(
					'news_date_margin',
					[
						'label' => __( 'Margin', 'education-addon' ),
						'type' => Controls_Manager::DIMENSIONS,
						'size_units' => [ 'px' ],
						'selectors' => [
							'{{WRAPPER}} .events-style-two .event-date' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						],
					]
				);
				$this->add_group_control(
					Group_Control_Background::get_type(),
					[
						'name' => 'date_bg_color',
						'label' => __( 'Background Color', 'education-addon' ),
						'types' => [ 'classic', 'gradient' ],
						'selector' => '{{WRAPPER}} .events-style-two .event-date',
					]
				);
				$this->add_group_control(
					Group_Control_Border::get_type(),
					[
						'name' => 'date_border',
						'label' => esc_html__( 'Border', 'education-addon' ),
						'selector' => '{{WRAPPER}} .events-style-two .event-date',
					]
				);
				$this->add_group_control(
					Group_Control_Box_Shadow::get_type(),
					[
						'name' => 'date_box_shadow',
						'label' => esc_html__( 'Section Box Shadow', 'education-addon' ),
						'selector' => '{{WRAPPER}} .events-style-two .event-date',
					]
				);
				$this->add_group_control(
					Group_Control_Typography::get_type(),
					[
						'label' => esc_html__( 'Typography', 'education-addon' ),
						'name' => 'date_title_typography',
						'selector' => '{{WRAPPER}} .events-style-two .event-date',
					]
				);
				$this->add_group_control(
					Group_Control_Typography::get_type(),
					[
						'label' => esc_html__( 'Month Typography', 'education-addon' ),
						'name' => 'month_title_typography',
						'selector' => '{{WRAPPER}} .event-date small',
					]
				);
				$this->end_controls_section();// end: Section

			// Content
				$this->start_controls_section(
					'section_content_style',
					[
						'label' => esc_html__( 'Content', 'education-addon' ),
						'tab' => Controls_Manager::TAB_STYLE,
					]
				);
				$this->add_responsive_control(
					'content_padding',
					[
						'label' => __( 'Padding', 'education-addon' ),
						'type' => Controls_Manager::DIMENSIONS,
						'size_units' => [ 'px' ],
						'selectors' => [
							'{{WRAPPER}} .naedu-events p' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						],
					]
				);
				$this->add_group_control(
					Group_Control_Typography::get_type(),
					[
						'name' => 'content_typography',
						'selector' => '{{WRAPPER}} .naedu-events p',
					]
				);
				$this->add_control(
					'content_color',
					[
						'label' => esc_html__( 'Color', 'education-addon' ),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .naedu-events p' => 'color: {{VALUE}};',
						],
					]
				);
				$this->end_controls_section();// end: Section

			// Reminder
				$this->start_controls_section(
					'section_rem_style',
					[
						'label' => esc_html__( 'Reminder Link', 'education-addon' ),
						'tab' => Controls_Manager::TAB_STYLE,
					]
				);
				$this->add_responsive_control(
					'rem_padding',
					[
						'label' => __( 'Padding', 'education-addon' ),
						'type' => Controls_Manager::DIMENSIONS,
						'size_units' => [ 'px' ],
						'selectors' => [
							'{{WRAPPER}} .event-auther ul li a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						],
					]
				);
				$this->add_group_control(
					Group_Control_Typography::get_type(),
					[
						'name' => 'rem_typography',
						'selector' => '{{WRAPPER}} .event-auther ul li a',
					]
				);
				$this->start_controls_tabs( 'reminder_style' );
					$this->start_controls_tab(
						'rem_normal',
						[
							'label' => esc_html__( 'Normal', 'education-addon' ),
						]
					);
					$this->add_control(
						'rem_color',
						[
							'label' => esc_html__( 'Color', 'education-addon' ),
							'type' => Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .event-auther ul li a' => 'color: {{VALUE}};',
							],
						]
					);
					$this->add_control(
						'rem_bdr_color',
						[
							'label' => esc_html__( 'Border Color', 'education-addon' ),
							'type' => Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .event-auther ul, {{WRAPPER}} .event-auther ul li:not(:first-child)' => 'color: {{VALUE}};',
							],
						]
					);
					$this->end_controls_tab();  // end:Normal tab
					$this->start_controls_tab(
						'rem_hover',
						[
							'label' => esc_html__( 'Hover', 'education-addon' ),
						]
					);
					$this->add_control(
						'rem_hover_color',
						[
							'label' => esc_html__( 'Color', 'education-addon' ),
							'type' => Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .event-auther ul li a:hover' => 'color: {{VALUE}};',
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
						'label' => esc_html__( 'Button', 'education-addon' ),
						'tab' => Controls_Manager::TAB_STYLE,
					]
				);
				$this->add_control(
					'btn_border_radius',
					[
						'label' => __( 'Border Radius', 'education-addon' ),
						'type' => Controls_Manager::DIMENSIONS,
						'size_units' => [ 'px', '%', 'em' ],
						'selectors' => [
							'{{WRAPPER}} .naedu-events .naedu-btn' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						],
					]
				);
				$this->add_control(
					'btn_padding',
					[
						'label' => __( 'Button Padding', 'education-addon' ),
						'type' => Controls_Manager::DIMENSIONS,
						'size_units' => [ 'px', '%', 'em' ],
						'selectors' => [
							'{{WRAPPER}} .naedu-events .naedu-btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						],
					]
				);
				$this->add_group_control(
					Group_Control_Typography::get_type(),
					[
						'label' => esc_html__( 'Typography', 'education-addon' ),
						'name' => 'btn_typography',
						'selector' => '{{WRAPPER}} .naedu-events .naedu-btn',
					]
				);
				$this->start_controls_tabs( 'btn_style' );
					$this->start_controls_tab(
						'btn_normal',
						[
							'label' => esc_html__( 'Normal', 'education-addon' ),
						]
					);
					$this->add_control(
						'btn_color',
						[
							'label' => esc_html__( 'Color', 'education-addon' ),
							'type' => Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .naedu-events .naedu-btn' => 'color: {{VALUE}};',
							],
						]
					);
					$this->add_control(
						'btn_bg_color',
						[
							'label' => esc_html__( 'Background Color', 'education-addon' ),
							'type' => Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .naedu-events .naedu-btn' => 'background-color: {{VALUE}};',
							],
						]
					);
					$this->add_group_control(
						Group_Control_Border::get_type(),
						[
							'name' => 'btn_border',
							'label' => esc_html__( 'Border', 'education-addon' ),
							'selector' => '{{WRAPPER}} .naedu-events .naedu-btn',
						]
					);
					$this->add_group_control(
						Group_Control_Box_Shadow::get_type(),
						[
							'name' => 'btn_shadow',
							'label' => esc_html__( 'Button Shadow', 'education-addon' ),
							'selector' => '{{WRAPPER}} .naedu-events .naedu-btn',
						]
					);
					$this->end_controls_tab();  // end:Normal tab
					$this->start_controls_tab(
						'btn_hover',
						[
							'label' => esc_html__( 'Hover', 'education-addon' ),
						]
					);
					$this->add_control(
						'btn_hover_color',
						[
							'label' => esc_html__( 'Color', 'education-addon' ),
							'type' => Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .naedu-events .naedu-btn:hover' => 'color: {{VALUE}};',
							],
						]
					);
					$this->add_control(
						'btn_bg_hover_color',
						[
							'label' => esc_html__( 'Background Color', 'education-addon' ),
							'type' => Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .naedu-events .naedu-btn:hover' => 'background-color: {{VALUE}};',
							],
						]
					);
					$this->add_group_control(
						Group_Control_Border::get_type(),
						[
							'name' => 'btn_hover_border',
							'label' => esc_html__( 'Border', 'education-addon' ),
							'selector' => '{{WRAPPER}} .naedu-events .naedu-btn:hover',
						]
					);
					$this->add_group_control(
						Group_Control_Box_Shadow::get_type(),
						[
							'name' => 'btn_hover_shadow',
							'label' => esc_html__( 'Button Shadow', 'education-addon' ),
							'selector' => '{{WRAPPER}} .naedu-events .naedu-btn:hover',
						]
					);
					$this->end_controls_tab();  // end:Hover tab
				$this->end_controls_tabs(); // end tabs
				$this->end_controls_section();// end: Section

			// Pagination
				$this->start_controls_section(
					'section_pagi_style',
					[
						'label' => esc_html__( 'Pagination', 'education-addon' ),
						'tab' => Controls_Manager::TAB_STYLE,
						'condition' => [
							'organizer_pagination' => 'true',
						],
					]
				);
				$this->add_responsive_control(
					'pagi_padding',
					[
						'label' => __( 'Padding', 'education-addon' ),
						'type' => Controls_Manager::DIMENSIONS,
						'size_units' => [ 'px' ],
						'selectors' => [
							'{{WRAPPER}} .naeep-pagination' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						],
					]
				);
				$this->add_responsive_control(
					'pagi_width',
					[
						'label' => esc_html__( 'Pagination Width', 'education-addon' ),
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
							'{{WRAPPER}} .naeep-pagination ul li span, {{WRAPPER}} .naeep-pagination ul li a ' => 'width:{{SIZE}}{{UNIT}};height:{{SIZE}}{{UNIT}};',
						],
					]
				);
				$this->add_group_control(
					Group_Control_Typography::get_type(),
					[
						'name' => 'pagi_typography',
						'selector' => '{{WRAPPER}} .naeep-pagination ul li a, {{WRAPPER}} .naeep-pagination ul li span',
					]
				);
				$this->start_controls_tabs( 'pagi_style' );
					$this->start_controls_tab(
						'pagi_normal',
						[
							'label' => esc_html__( 'Normal', 'education-addon' ),
						]
					);
					$this->add_control(
						'pagi_color',
						[
							'label' => esc_html__( 'Color', 'education-addon' ),
							'type' => Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .naeep-pagination ul li a' => 'color: {{VALUE}};',
							],
						]
					);
					$this->add_control(
						'pagi_bg_color',
						[
							'label' => esc_html__( 'Background Color', 'education-addon' ),
							'type' => Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .naeep-pagination ul li a' => 'background-color: {{VALUE}};',
							],
						]
					);
					$this->add_group_control(
						Group_Control_Border::get_type(),
						[
							'name' => 'pagi_border',
							'label' => esc_html__( 'Border', 'education-addon' ),
							'selector' => '{{WRAPPER}} .naeep-pagination ul li a',
						]
					);
					$this->end_controls_tab();  // end:Normal tab

					$this->start_controls_tab(
						'pagi_hover',
						[
							'label' => esc_html__( 'Hover', 'education-addon' ),
						]
					);
					$this->add_control(
						'pagi_hover_color',
						[
							'label' => esc_html__( 'Color', 'education-addon' ),
							'type' => Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .naeep-pagination ul li a:hover' => 'color: {{VALUE}};',
							],
						]
					);
					$this->add_control(
						'pagi_bg_hover_color',
						[
							'label' => esc_html__( 'Background Color', 'education-addon' ),
							'type' => Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .naeep-pagination ul li a:hover' => 'background-color: {{VALUE}};',
							],
						]
					);
					$this->add_group_control(
						Group_Control_Border::get_type(),
						[
							'name' => 'pagi_hover_border',
							'label' => esc_html__( 'Border', 'education-addon' ),
							'selector' => '{{WRAPPER}} .naeep-pagination ul li a:hover',
						]
					);
					$this->end_controls_tab();  // end:Hover tab
					$this->start_controls_tab(
						'pagi_active',
						[
							'label' => esc_html__( 'Active', 'education-addon' ),
						]
					);
					$this->add_control(
						'pagi_active_color',
						[
							'label' => esc_html__( 'Color', 'education-addon' ),
							'type' => Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .naeep-pagination ul li span.current' => 'color: {{VALUE}};',
							],
						]
					);
					$this->add_control(
						'pagi_bg_active_color',
						[
							'label' => esc_html__( 'Background Color', 'education-addon' ),
							'type' => Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .naeep-pagination ul li span.current' => 'background-color: {{VALUE}};',
							],
						]
					);
					$this->add_group_control(
						Group_Control_Border::get_type(),
						[
							'name' => 'pagi_active_border',
							'label' => esc_html__( 'Border', 'education-addon' ),
							'selector' => '{{WRAPPER}} .naeep-pagination ul li span.current',
						]
					);
					$this->end_controls_tab();  // end:Active tab
				$this->end_controls_tabs(); // end tabs

				$this->end_controls_section();// end: Section

		}

		/**
		 * Render Event widget output on the frontend.
		 * Written in PHP and used to generate the final HTML.
		*/
		protected function render() {
			$settings = $this->get_settings_for_display();

			// Event query
			$event_style = !empty( $settings['event_style'] ) ? $settings['event_style'] : '';
			$event_limit = !empty( $settings['event_limit'] ) ? $settings['event_limit'] : '';
			$event_order = !empty( $settings['event_order'] ) ? $settings['event_order'] : '';
			$event_orderby = !empty( $settings['event_orderby'] ) ? $settings['event_orderby'] : '';
			$event_id = !empty( $settings['event_id'] ) ? $settings['event_id'] : '';
			$event_col = !empty( $settings['event_col'] ) ? $settings['event_col'] : '';
			$short_content = !empty( $settings['short_content'] ) ? $settings['short_content'] : '15';
			$button_type = !empty( $settings['button_type'] ) ? $settings['button_type'] : '';
			$btn_txt = !empty( $settings['btn_txt'] ) ? $settings['btn_txt'] : '';
			$event_pagination  = ( isset( $settings['event_pagination'] ) && ( 'true' == $settings['event_pagination'] ) ) ? true : false;

	  	$event_col = $event_col ? $event_col : '2';
	  	$btn_txt = $btn_txt ? $btn_txt : esc_html__( 'Get Tickets', 'education-addon' );

	  	if ($event_style === 'two') {
				$style_class = ' events-style-two';
			} else {
				$style_class = '';
			}
	  	if ($event_col === '2') {
				$col_class = 'nich-col-lg-6 nich-col-md-6';
			} elseif ($event_col === '1') {
				$col_class = 'nich-col-md-12';
			} elseif ($event_col === '4') {
				$col_class = 'nich-col-lg-3 nich-col-md-6';
			} else {
				$col_class = 'nich-col-lg-4 nich-col-md-6';
			}

			// Turn output buffer on
			ob_start();

			// Pagination
			global $paged;
			if ( get_query_var( 'paged' ) )
			  $my_page = get_query_var( 'paged' );
			else {
			  if ( get_query_var( 'page' ) )
				$my_page = get_query_var( 'page' );
			  else
				$my_page = 1;
			  set_query_var( 'paged', $my_page );
			  $paged = $my_page;
			}
			$event_limit = $event_limit ? $event_limit : '-1';
			if ($event_id) {
				$event_id = json_encode( $event_id );
				$event_id = str_replace(array( '[', ']' ), '', $event_id);
				$event_id = str_replace(array( '"', '"' ), '', $event_id);
	      $event_id = explode(',',$event_id);
	    } else {
	      $event_id = '';
	    }

			$args = array(
			  'paged' => $my_page,
			  'post_type' => 'tribe_events',
			  'posts_per_page' => (int) $event_limit,
			  'orderby' => $event_orderby,
			  'order' => $event_order,
		  	'post__in' => $event_id,
			);
			$naevents_event = new \WP_Query( $args );
			if ($naevents_event->have_posts()) : ?>
				<div class="naedu-events<?php echo esc_attr($style_class); ?>">
					<?php if ($event_style === 'two') { ?><div class="nich-row"><?php } ?>
					<?php while ($naevents_event->have_posts()) : $naevents_event->the_post();
					$venu_details = tribe_get_venue_details(get_the_ID());
					$organizer_ids = tribe_get_organizer_ids(get_the_ID());
					$currency = tribe_get_event_meta( get_the_ID(), '_EventCurrencySymbol', true );
					// Featured Image
					$large_image =  wp_get_attachment_image_src( get_post_thumbnail_id(get_the_ID()), 'fullsize', false, '' );
					$large_image = $large_image[0];		
					if ($event_style === 'two') { ?>
			  		<div class="<?php echo esc_attr($col_class); ?>">
		          <figure>
		            <?php if ($large_image) { ?>
	                <a href="<?php echo esc_url( get_permalink() ); ?>"><img src="<?php echo esc_url($large_image); ?>" alt="Image"></a>
	            	<?php } ?>
		            <figcaption>
		              <div class="event-info">
		                <h3><a href="<?php echo esc_url( get_permalink() ); ?>"><?php echo get_the_title(); ?></a></h3>
		                <ul class="naedu-meta">
					          	<?php if (!empty($venu_details['linked_name']) || !empty($venu_details['address'])) { ?>
		                  <li><i class="fas fa-map-marker-alt"></i><span>
						          	<?php if (!empty($venu_details['linked_name'])) {
			                    echo esc_html($venu_details['linked_name']);
			                  } else {
			                    echo $venu_details['address'];
			                  } ?>
						          </span></li>
			                <?php } ?>
		                  <li><i class="fas fa-clock"></i><span><?php echo tribe_get_start_date( null, false, 'H:i' ); ?> - <?php echo tribe_get_end_date( null, false, 'H:i' ); ?></span></li>
		                </ul>
		                <div class="event-date"><?php echo tribe_get_start_date( null, false, 'd' ); ?> <small><?php echo tribe_get_start_date( null, false, 'M' ); ?></small></div>
					        	<?php naedu_excerpt($short_content); ?>
		              </div>
		              <div class="event-auther">
		                <ul>
		                  <li><a href="<?php echo esc_url( tribe_get_single_ical_link(get_the_ID()) ); ?>" target="_blank"><span><i class="fas fa-bell"></i> <?php echo esc_html__( 'GET REMINDER (iCal)', 'education-addon' ); ?></span></a></li>
		                  <li><a href="<?php echo esc_url( tribe_get_gcal_link(get_the_ID()) ); ?>" target="_blank"><span><i class="fas fa-bell"></i> <?php echo esc_html__( 'GET REMINDER (gCal)', 'education-addon' ); ?></span></a></li>
		                </ul>
		                <a href="<?php echo esc_url( get_permalink() ); ?>" class="naedu-btn btn-block"><?php echo esc_html($btn_txt); ?> <?php echo $currency; echo tribe_get_cost(); ?></a>
		              </div>
		            </figcaption>
		          </figure>
		        </div>
					<?php } else { ?>
						<figure>
							<?php if ($large_image) { ?>
                <a href="<?php echo esc_url( get_permalink() ); ?>"><img src="<?php echo esc_url($large_image); ?>" alt="Image"></a>
            	<?php } ?>
					    <figcaption>
					      <div class="event-info">
					        <h3><a href="<?php echo esc_url( get_permalink() ); ?>"><?php echo get_the_title(); ?></a></h3>
					        <ul class="naedu-meta">
					          <?php if (!empty($venu_details['linked_name']) || !empty($venu_details['address'])) { ?>
	                  <li><i class="fas fa-map-marker-alt"></i><span>
					          	<?php if (!empty($venu_details['linked_name'])) {
		                    echo esc_html($venu_details['linked_name']);
		                  } else {
		                    echo $venu_details['address'];
		                  } ?>
					          </span></li>
		                <?php } ?>
					          <li><i class="fas fa-calendar-alt"></i><span><?php echo tribe_get_start_date( null, false, 'M d, Y' ); ?> - <?php echo tribe_get_end_date( null, false, 'M d, Y' ); ?></span></li>
					          <li><i class="fas fa-clock"></i><span><?php echo tribe_get_start_date( null, false, 'H:i' ); ?> - <?php echo tribe_get_end_date( null, false, 'H:i' ); ?></span></li>
					        </ul>
					        <?php naedu_excerpt($short_content); ?>
					      </div>
					      <div class="event-auther">
					        <span class="event-cost"><?php echo $currency; echo tribe_get_cost(); ?></span>
					        <a href="<?php echo esc_url( get_permalink() ); ?>" class="naedu-btn"><?php echo esc_html($btn_txt); ?></a>
					      </div>
					    </figcaption>
					  </figure>
					<?php }
					endwhile;
					if ($naevents_event->max_num_pages > 1) {
						if ($event_pagination) {
			    		if ( function_exists( 'naedu_paging_nav' ) ) {
		          	echo naedu_paging_nav($naevents_event->max_num_pages,"",$paged);
		        	}
		        }
		      } wp_reset_postdata(); ?>
					<?php if ($event_style === 'two') { ?></div><?php } ?>
				</div>
			<?php endif;

			// Return outbut buffer
			echo ob_get_clean();

		}

	}
	Plugin::instance()->widgets_manager->register_widget_type( new Education_Elementor_Addon_Events() );
	
}

} // enable & disable