<?php
/*
 * Elementor Charity Addon for Elementor Cause Filter
 * Author & Copyright: NicheAddon
*/
namespace Elementor;

if ( is_plugin_active( 'give/give.php' ) ) {

	if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

	class Charity_Elementor_Addon_Unique_Cause_Filter extends Widget_Base{

		/**
		 * Retrieve the widget name.
		*/
		public function get_name(){
			return 'nacharity_unique_cause_filter';
		}

		/**
		 * Retrieve the widget title.
		*/
		public function get_title(){
			return esc_html__( 'Cause Filter/Slider', 'charity-addon-for-elementor' );
		}

		/**
		 * Retrieve the widget icon.
		*/
		public function get_icon() {
			return 'eicon-post-slider';
		}

		/**
		 * Retrieve the cause-list of categories the widget belongs to.
		*/
		public function get_categories() {
			return ['nacharity-unique-category'];
		}

		/**
		 * Register Charitys Addon for Elementor Cause Filter widget controls.
		 * Adds different input fields to allow the user to change and customize the widget settings.
		*/
		protected function _register_controls(){

			$charity = get_posts( 'post_type="give_forms"&numberposts=-1' );
	    $CharityID = array();
	    if ( $charity ) {
	      foreach ( $charity as $form ) {
	        $CharityID[ $form->ID ] = $form->post_title;
	      }
	    } else {
	      $CharityID[ __( 'No ID\'s found', 'charity-addon-for-elementor' ) ] = 0;
	    }

			$this->start_controls_section(
				'section_cause_filtering',
				[
					'label' => esc_html__( 'Filtering Options', 'charity-addon-for-elementor' ),
				]
			);
			$this->add_control(
				'cause_style',
				[
					'label' => esc_html__( 'Cause Style', 'charity-addon-for-elementor' ),
					'type' => Controls_Manager::SELECT,
					'options' => [
						'one'          => esc_html__('Style One (Filter)', 'charity-addon-for-elementor'),
						'two'          => esc_html__('Style Two (Slider)', 'charity-addon-for-elementor'),
					],
					'default' => 'one',
				]
			);
			$this->add_control(
				'cause_col',
				[
					'label' => esc_html__( 'Cause Column', 'charity-addon-for-elementor' ),
					'type' => Controls_Manager::SELECT,
					'options' => [
						'2'          => esc_html__('Two', 'charity-addon-for-elementor'),
	          '3'          => esc_html__('Three', 'charity-addon-for-elementor'),
	          '4'          => esc_html__('Four', 'charity-addon-for-elementor'),
					],
					'default' => '3',
					'condition' => [
						'cause_style' => 'one',
					],
				]
			);
			$this->add_control(
				'need_filter',
				[
					'label' => esc_html__( 'Need Filter?', 'charity-addon-for-elementor' ),
					'type' => Controls_Manager::SWITCHER,
					'label_on' => esc_html__( 'Yes', 'charity-addon-for-elementor' ),
					'label_off' => esc_html__( 'No', 'charity-addon-for-elementor' ),
					'return_value' => 'true',
					'default' => 'true',
					'condition' => [
						'cause_style' => 'one',
					],
				]
			);
			$this->add_control(
				'cause_limit',
				[
					'label' => esc_html__( 'Cause Limit', 'charity-addon-for-elementor' ),
					'type' => Controls_Manager::NUMBER,
					'min' => -1,
					'max' => 100,
					'step' => 1,
					'default' => 3,
					'description' => esc_html__( 'Enter the number of items to show.', 'charity-addon-for-elementor' ),
				]
			);
			$this->add_control(
				'cause_order',
				[
					'label' => __( 'Order', 'charity-addon-for-elementor' ),
					'type' => Controls_Manager::SELECT,
					'options' => [
						'ASC' => esc_html__( 'Asending', 'charity-addon-for-elementor' ),
						'DESC' => esc_html__( 'Desending', 'charity-addon-for-elementor' ),
					],
					'default' => 'DESC',
				]
			);
			$this->add_control(
				'cause_orderby',
				[
					'label' => __( 'Order By', 'charity-addon-for-elementor' ),
					'type' => Controls_Manager::SELECT,
					'options' => [
						'none' => esc_html__( 'None', 'charity-addon-for-elementor' ),
						'ID' => esc_html__( 'ID', 'charity-addon-for-elementor' ),
						'author' => esc_html__( 'Author', 'charity-addon-for-elementor' ),
						'title' => esc_html__( 'Title', 'charity-addon-for-elementor' ),
						'date' => esc_html__( 'Date', 'charity-addon-for-elementor' ),
						'name' => esc_html__( 'Name', 'charity-addon-for-elementor' ),
						'modified' => esc_html__( 'Modified', 'charity-addon-for-elementor' ),
						'comment_count' => esc_html__( 'Comment Count', 'charity-addon-for-elementor' ),
					],
					'default' => 'date',
				]
			);
			$this->add_control(
				'cause_show_category',
				[
					'label' => __( 'Certain Categories?', 'charity-addon-for-elementor' ),
					'type' => Controls_Manager::SELECT2,
					'default' => [],
					'options' => NACEP_Controls_Helper_Output::get_terms_names( 'give_forms_category'),
					'multiple' => true,
				]
			);
			$this->add_control(
				'cause_show_id',
				[
					'label' => __( 'Certain ID\'s?', 'charity-addon-for-elementor' ),
					'type' => Controls_Manager::SELECT2,
					'default' => [],
					'options' => $CharityID,
					'multiple' => true,
				]
			);
			$this->add_control(
				'read_more_txt',
				[
					'label' => esc_html__( 'More Button Text', 'charity-addon-for-elementor' ),
					'type' => Controls_Manager::TEXT,
					'label_block' => true,
					'default' => esc_html__( 'Donate Now', 'charity-addon-for-elementor' ),
					'placeholder' => esc_html__( 'Type text here', 'charity-addon-for-elementor' ),
				]
			);
			$this->add_control(
			  'btn_icon',
			  [
			    'label' => esc_html__( 'Button Icon', 'charity-addon-for-elementor' ),
			    'type' => Controls_Manager::ICON,
			    'options' => NACEP_Controls_Helper_Output::get_include_icons(),
			    'frontend_available' => true,
			    'default' => 'fa fa-long-arrow-right',
			  ]
			);
			$this->add_control(
				'goal_title',
				[
					'label' => esc_html__( 'Goal Title', 'charity-addon-for-elementor' ),
					'type' => Controls_Manager::TEXT,
					'label_block' => true,
					'default' => esc_html__( 'Goal', 'charity-addon-for-elementor' ),
					'placeholder' => esc_html__( 'Type text here', 'charity-addon-for-elementor' ),
				]
			);
			$this->add_control(
				'income_title',
				[
					'label' => esc_html__( 'Achieved Title', 'charity-addon-for-elementor' ),
					'type' => Controls_Manager::TEXT,
					'label_block' => true,
					'default' => esc_html__( 'Achieved', 'charity-addon-for-elementor' ),
					'placeholder' => esc_html__( 'Type text here', 'charity-addon-for-elementor' ),
				]
			);
			$this->add_control(
				'donor_title',
				[
					'label' => esc_html__( 'Donor Title', 'charity-addon-for-elementor' ),
					'type' => Controls_Manager::TEXT,
					'label_block' => true,
					'default' => esc_html__( 'Donors', 'charity-addon-for-elementor' ),
					'placeholder' => esc_html__( 'Type text here', 'charity-addon-for-elementor' ),
					'condition' => [
						'cause_style' => 'one',
					],
				]
			);
			$this->add_control(
				'cause_pagination',
				[
					'label' => esc_html__( 'Pagination', 'charity-addon-for-elementor' ),
					'type' => Controls_Manager::SWITCHER,
					'label_on' => esc_html__( 'Show', 'charity-addon-for-elementor' ),
					'label_off' => esc_html__( 'Hide', 'charity-addon-for-elementor' ),
					'return_value' => 'true',
					'default' => 'true',
					'condition' => [
						'cause_style' => 'one',
					],
				]
			);
			$this->add_control(
				'all_text',
				[
					'label' => esc_html__( 'Filter All Text', 'charity-addon-for-elementor' ),
					'type' => Controls_Manager::TEXT,
					'label_block' => true,
					'default' => esc_html__( 'All', 'charity-addon-for-elementor' ),
					'placeholder' => esc_html__( 'Type text here', 'charity-addon-for-elementor' ),
					'condition' => [
						'need_filter' => 'true',
						'cause_style' => 'one',
					],
				]
			);
			$this->end_controls_section();// end: Section

			// Carousel Options
				$this->start_controls_section(
					'section_carousel',
					[
						'label' => esc_html__( 'Carousel Options', 'charity-elementor-addon' ),
						'condition' => [
							'cause_style' => 'two',
						],
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

			// Section
				$this->start_controls_section(
					'sectn_style_one',
					[
						'label' => esc_html__( 'Section', 'charity-addon-for-elementor' ),
						'tab' => Controls_Manager::TAB_STYLE,
					]
				);
				$this->add_control(
					'box_border_radius_one',
					[
						'label' => __( 'Border Radius', 'charity-addon-for-elementor' ),
						'type' => Controls_Manager::DIMENSIONS,
						'size_units' => [ 'px', '%', 'em' ],
						'selectors' => [
							'{{WRAPPER}} .nacep-cause-list-item, {{WRAPPER}} .cause-slider-item' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						],
					]
				);
				$this->add_responsive_control(
					'cause_section_margin_one',
					[
						'label' => __( 'Margin', 'charity-addon-for-elementor' ),
						'type' => Controls_Manager::DIMENSIONS,
						'size_units' => [ 'px' ],
						'selectors' => [
							'{{WRAPPER}} .nacep-cause-list-item, {{WRAPPER}} .cause-slider-item' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						],
					]
				);
				$this->add_responsive_control(
					'cause_section_padding_one',
					[
						'label' => __( 'Padding', 'charity-addon-for-elementor' ),
						'type' => Controls_Manager::DIMENSIONS,
						'size_units' => [ 'px' ],
						'selectors' => [
							'{{WRAPPER}} .nacep-cause-list-item, {{WRAPPER}} .cause-slider-item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						],
					]
				);
				$this->add_control(
					'secn_bg_color_one',
					[
						'label' => esc_html__( 'Background Color', 'charity-addon-for-elementor' ),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .nacep-cause-list-item, {{WRAPPER}} .cause-slider-item' => 'background-color: {{VALUE}};',
						],
					]
				);
				$this->add_group_control(
					Group_Control_Border::get_type(),
					[
						'name' => 'secn_border_one',
						'label' => esc_html__( 'Border', 'charity-addon-for-elementor' ),
						'selector' => '{{WRAPPER}} .nacep-cause-list-item, {{WRAPPER}} .cause-slider-item',
					]
				);
				$this->add_group_control(
					Group_Control_Box_Shadow::get_type(),
					[
						'name' => 'secn_box_shadow_one',
						'label' => esc_html__( 'Section Box Shadow', 'charity-addon-for-elementor' ),
						'selector' => '{{WRAPPER}} .nacep-cause-list-item, {{WRAPPER}} .cause-slider-item',
					]
				);
				$this->end_controls_section();// end: Section

			// Filter
				$this->start_controls_section(
					'section_filter_style',
					[
						'label' => esc_html__( 'Filter', 'charity-addon-for-elementor' ),
						'tab' => Controls_Manager::TAB_STYLE,
						'frontend_available' => true,
						'condition' => [
							'need_filter' => 'true',
							'cause_style' => 'one',
						],
					]
				);
				$this->add_group_control(
					Group_Control_Typography::get_type(),
					[
						'name' => 'filter_typography',
						'selector' => '{{WRAPPER}} .masonry-filters ul li a',
					]
				);
				$this->add_responsive_control(
					'filter_padding',
					[
						'label' => __( 'Filter Spacing', 'charity-addon-for-elementor' ),
						'type' => Controls_Manager::DIMENSIONS,
						'size_units' => [ 'px' ],
						'selectors' => [
							'{{WRAPPER}} .masonry-filters ul li' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						],
					]
				);
				$this->add_control(
					'dot_radius',
					[
						'label' => __( 'Dot Border Radius', 'charity-addon-for-elementor' ),
						'type' => Controls_Manager::DIMENSIONS,
						'size_units' => [ 'px', '%', 'em' ],
						'selectors' => [
							'{{WRAPPER}} .masonry-filters ul li a:after' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						],
					]
				);
				$this->add_control(
					'dot_width',
					[
						'label' => esc_html__( 'Dot Width', 'charity-addon-for-elementor' ),
						'type' => Controls_Manager::SLIDER,
						'range' => [
							'px' => [
								'min' => 0,
								'max' => 500,
								'step' => 1,
							],
							'%' => [
								'min' => 0,
								'max' => 100,
							],
						],
						'size_units' => [ 'px', '%', 'em' ],
						'selectors' => [
							'{{WRAPPER}} .masonry-filters ul li a:after' => 'width: {{SIZE}}{{UNIT}};',
						],
					]
				);
				$this->add_control(
					'dot_height',
					[
						'label' => esc_html__( 'Dot Height', 'charity-addon-for-elementor' ),
						'type' => Controls_Manager::SLIDER,
						'range' => [
							'px' => [
								'min' => 0,
								'max' => 500,
								'step' => 1,
							],
							'%' => [
								'min' => 0,
								'max' => 100,
							],
						],
						'size_units' => [ 'px', '%', 'em' ],
						'selectors' => [
							'{{WRAPPER}} .masonry-filters ul li a:after' => 'height: {{SIZE}}{{UNIT}};',
						],
					]
				);
				$this->start_controls_tabs( 'filter_style' );
					$this->start_controls_tab(
						'filter_normal',
						[
							'label' => esc_html__( 'Normal', 'charity-addon-for-elementor' ),
						]
					);
					$this->add_control(
						'filter_color',
						[
							'label' => esc_html__( 'Color', 'charity-addon-for-elementor' ),
							'type' => Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .masonry-filters ul li a' => 'color: {{VALUE}};',
							],
						]
					);
					$this->end_controls_tab();  // end:Normal tab
					$this->start_controls_tab(
						'filter_active',
						[
							'label' => esc_html__( 'Active', 'charity-addon-for-elementor' ),
						]
					);
					$this->add_control(
						'filter_active_color',
						[
							'label' => esc_html__( 'Color', 'charity-addon-for-elementor' ),
							'type' => Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .masonry-filters ul li a.active, {{WRAPPER}} .masonry-filters ul li a:hover' => 'color: {{VALUE}}',
							],
						]
					);
					$this->add_control(
						'filter_active_border_color',
						[
							'label' => esc_html__( 'Dot Color', 'charity-addon-for-elementor' ),
							'type' => Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .masonry-filters ul li a:after' => 'background-color: {{VALUE}};',
							],
						]
					);
					$this->end_controls_tab();  // end:Active tab
				$this->end_controls_tabs(); // end tabs
				$this->end_controls_section();// end: Section

			// Title
				$this->start_controls_section(
					'section_title_style',
					[
						'label' => esc_html__( 'Title', 'charity-addon-for-elementor' ),
						'tab' => Controls_Manager::TAB_STYLE,
					]
				);
				$this->add_group_control(
					Group_Control_Typography::get_type(),
					[
						'label' => esc_html__( 'Typography', 'charity-addon-for-elementor' ),
						'name' => 'sasstp_title_typography',
						'selector' => '{{WRAPPER}} h3.cause-title',
					]
				);
				$this->start_controls_tabs( 'title_style' );
					$this->start_controls_tab(
						'title_normal',
						[
							'label' => esc_html__( 'Normal', 'charity-addon-for-elementor' ),
						]
					);
					$this->add_control(
						'title_color',
						[
							'label' => esc_html__( 'Color', 'charity-addon-for-elementor' ),
							'type' => Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} h3.cause-title, {{WRAPPER}} h3.cause-title a' => 'color: {{VALUE}};',
							],
						]
					);
					$this->end_controls_tab();  // end:Normal tab
					$this->start_controls_tab(
						'title_hover',
						[
							'label' => esc_html__( 'Hover', 'charity-addon-for-elementor' ),
						]
					);
					$this->add_control(
						'title_hover_color',
						[
							'label' => esc_html__( 'Color', 'charity-addon-for-elementor' ),
							'type' => Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} h3.cause-title a:hover' => 'color: {{VALUE}};',
							],
						]
					);
					$this->end_controls_tab();  // end:Hover tab
				$this->end_controls_tabs(); // end tabs
				$this->end_controls_section();// end: Section

			// Author
				$this->start_controls_section(
					'section_author_style',
					[
						'label' => esc_html__( 'Author', 'charity-addon-for-elementor' ),
						'tab' => Controls_Manager::TAB_STYLE,
						'condition' => [
							'cause_style' => 'one',
						],
					]
				);
				$this->add_group_control(
					Group_Control_Typography::get_type(),
					[
						'label' => esc_html__( 'Typography', 'charity-addon-for-elementor' ),
						'name' => 'sasstp_author_typography',
						'selector' => '{{WRAPPER}} .cause-author a',
					]
				);
				$this->start_controls_tabs( 'author_style' );
					$this->start_controls_tab(
						'author_normal',
						[
							'label' => esc_html__( 'Normal', 'charity-addon-for-elementor' ),
						]
					);
					$this->add_control(
						'author_color',
						[
							'label' => esc_html__( 'Color', 'charity-addon-for-elementor' ),
							'type' => Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .cause-author a' => 'color: {{VALUE}};',
							],
						]
					);
					$this->end_controls_tab();  // end:Normal tab
					$this->start_controls_tab(
						'author_hover',
						[
							'label' => esc_html__( 'Hover', 'charity-addon-for-elementor' ),
						]
					);
					$this->add_control(
						'author_hover_color',
						[
							'label' => esc_html__( 'Color', 'charity-addon-for-elementor' ),
							'type' => Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .cause-author a:hover' => 'color: {{VALUE}};',
							],
						]
					);
					$this->end_controls_tab();  // end:Hover tab
				$this->end_controls_tabs(); // end tabs
				$this->end_controls_section();// end: Section

			// Categories
				$this->start_controls_section(
					'section_category_style',
					[
						'label' => esc_html__( 'Categories', 'charity-addon-for-elementor' ),
						'tab' => Controls_Manager::TAB_STYLE,
						'condition' => [
							'cause_style' => 'two',
						],
					]
				);
				$this->add_group_control(
					Group_Control_Typography::get_type(),
					[
						'label' => esc_html__( 'Typography', 'charity-addon-for-elementor' ),
						'name' => 'sasstp_category_typography',
						'selector' => '{{WRAPPER}} .cause-category a',
					]
				);
				$this->start_controls_tabs( 'category_style' );
					$this->start_controls_tab(
						'category_normal',
						[
							'label' => esc_html__( 'Normal', 'charity-addon-for-elementor' ),
						]
					);
					$this->add_control(
						'category_color',
						[
							'label' => esc_html__( 'Color', 'charity-addon-for-elementor' ),
							'type' => Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .cause-category a' => 'color: {{VALUE}};',
							],
						]
					);
					$this->add_control(
						'category_bg_color',
						[
							'label' => esc_html__( 'Background Color', 'charity-addon-for-elementor' ),
							'type' => Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .cause-category a' => 'background-color: {{VALUE}};',
							],
						]
					);
					$this->add_group_control(
						Group_Control_Box_Shadow::get_type(),
						[
							'name' => 'cat_shadow',
							'label' => esc_html__( 'Box Shadow', 'charity-addon-for-elementor' ),
							'selector' => '{{WRAPPER}} .cause-category a',
						]
					);
					$this->end_controls_tab();  // end:Normal tab
					$this->start_controls_tab(
						'category_hover',
						[
							'label' => esc_html__( 'Hover', 'charity-addon-for-elementor' ),
						]
					);
					$this->add_control(
						'category_hover_color',
						[
							'label' => esc_html__( 'Color', 'charity-addon-for-elementor' ),
							'type' => Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .cause-category a:hover' => 'color: {{VALUE}};',
							],
						]
					);
					$this->add_control(
						'category_bg_hov_color',
						[
							'label' => esc_html__( 'Background Color', 'charity-addon-for-elementor' ),
							'type' => Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .cause-category a:hover' => 'background-color: {{VALUE}};',
							],
						]
					);
					$this->end_controls_tab();  // end:Hover tab
				$this->end_controls_tabs(); // end tabs
				$this->end_controls_section();// end: Section

			// Content
				$this->start_controls_section(
					'section_cont_style',
					[
						'label' => esc_html__( 'Content', 'charity-addon-for-elementor' ),
						'tab' => Controls_Manager::TAB_STYLE,
						'condition' => [
							'cause_style' => 'two',
						],
					]
				);
				$this->add_group_control(
					Group_Control_Typography::get_type(),
					[
						'label' => esc_html__( 'Typography', 'charity-addon-for-elementor' ),
						'name' => 'sasstp_cont_typography',
						'selector' => '{{WRAPPER}} .cause-slider-item p',
					]
				);
				$this->add_control(
					'cont_color',
					[
						'label' => esc_html__( 'Color', 'charity-addon-for-elementor' ),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .cause-slider-item p' => 'color: {{VALUE}};',
						],
					]
				);
				$this->end_controls_section();// end: Section

			// Meta
				$this->start_controls_section(
					'section_meta_style',
					[
						'label' => esc_html__( 'Metas', 'charity-addon-for-elementor' ),
						'tab' => Controls_Manager::TAB_STYLE,
					]
				);
				$this->add_control(
					'meta_bg_color',
					[
						'label' => esc_html__( 'Meta Background Color', 'charity-addon-for-elementor' ),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .cause-donate-info' => 'background-color: {{VALUE}};',
						],
					]
				);
				$this->add_responsive_control(
					'meta_padding',
					[
						'label' => __( 'Padding', 'charity-addon-for-elementor' ),
						'type' => Controls_Manager::DIMENSIONS,
						'size_units' => [ 'px' ],
						'selectors' => [
							'{{WRAPPER}} .cause-donate-info' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						],
					]
				);
				$this->add_group_control(
					Group_Control_Typography::get_type(),
					[
						'label' => esc_html__( 'Title Typography', 'charity-addon-for-elementor' ),
						'name' => 'meta_title_typography',
						'selector' => '{{WRAPPER}} .cause-donate-info h3',
					]
				);
				$this->add_control(
					'meta_color',
					[
						'label' => esc_html__( 'Title Color', 'charity-addon-for-elementor' ),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .cause-donate-info h3' => 'color: {{VALUE}};',
						],
					]
				);
				$this->add_group_control(
					Group_Control_Typography::get_type(),
					[
						'label' => esc_html__( 'Content Typography', 'charity-addon-for-elementor' ),
						'name' => 'meta_cnt_typography',
						'selector' => '{{WRAPPER}} .cause-donate-info p',
					]
				);
				$this->add_control(
					'meta_cnt_color',
					[
						'label' => esc_html__( 'Content Color', 'charity-addon-for-elementor' ),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .cause-donate-info p' => 'color: {{VALUE}};',
						],
					]
				);
				$this->end_controls_section();// end: Section

			// Progress Bar
				$this->start_controls_section(
					'bar_style',
					[
						'label' => esc_html__( 'Progress Bar', 'charity-addon-for-elementor' ),
						'tab' => Controls_Manager::TAB_STYLE,
					]
				);
				$this->start_controls_tabs( 'prog_style' );
					$this->start_controls_tab(
						'prog_normal',
						[
							'label' => esc_html__( 'Normal', 'charity-addon-for-elementor' ),
						]
					);
					$this->add_control(
						'prog_bg_color',
						[
							'label' => esc_html__( 'Background Color', 'charity-addon-for-elementor' ),
							'type' => Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .nacep-cause-bar' => 'background-color: {{VALUE}};',
							],
						]
					);
					$this->add_group_control(
						Group_Control_Box_Shadow::get_type(),
						[
							'name' => 'bar_shadow',
							'label' => esc_html__( 'Box Shadow', 'charity-addon-for-elementor' ),
							'selector' => '{{WRAPPER}} .nacep-cause-bar',
						]
					);
					$this->end_controls_tab();  // end:Normal tab

					$this->start_controls_tab(
						'prog_hover',
						[
							'label' => esc_html__( 'Active', 'charity-addon-for-elementor' ),
						]
					);
					$this->add_group_control(
						Group_Control_Background::get_type(),
						[
							'name' => 'bar_gradient_background',
							'label' => __( 'Background', 'events-addon-for-elementor' ),
							'types' => [ 'classic', 'gradient' ],
							'selector' => '{{WRAPPER}} .nacep-cause-bar .progress-bar',
						]
					);
					$this->end_controls_tab();  // end:Hover tab
				$this->end_controls_tabs(); // end tabs
				$this->end_controls_section();// end: Section

			// Button
				$this->start_controls_section(
					'section_btn_style',
					[
						'label' => esc_html__( 'Button', 'charity-addon-for-elementor' ),
						'tab' => Controls_Manager::TAB_STYLE,
					]
				);
				$this->add_control(
					'btn_padding',
					[
						'label' => __( 'Padding', 'charity-addon-for-elementor' ),
						'type' => Controls_Manager::DIMENSIONS,
						'size_units' => [ 'px', '%', 'em' ],
						'selectors' => [
							'{{WRAPPER}} .nacep-btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						],
					]
				);
				$this->add_control(
					'btn_margin',
					[
						'label' => __( 'Margin', 'charity-addon-for-elementor' ),
						'type' => Controls_Manager::DIMENSIONS,
						'size_units' => [ 'px', '%', 'em' ],
						'selectors' => [
							'{{WRAPPER}} .nacep-btn' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						],
					]
				);
				$this->add_control(
					'btn_border_radius',
					[
						'label' => __( 'Border Radius', 'charity-addon-for-elementor' ),
						'type' => Controls_Manager::DIMENSIONS,
						'size_units' => [ 'px', '%', 'em' ],
						'selectors' => [
							'{{WRAPPER}} .nacep-btn:before, {{WRAPPER}} .nacep-btn:after' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						],
					]
				);
				$this->add_responsive_control(
					'btn_width',
					[
						'label' => esc_html__( 'Button Width', 'charity-addon-for-elementor' ),
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
							'{{WRAPPER}} .nacep-btn:before, {{WRAPPER}} .nacep-btn:after' => 'width:{{SIZE}}{{UNIT}};height:{{SIZE}}{{UNIT}};',
						],
					]
				);
				$this->add_responsive_control(
					'btn_line_height',
					[
						'label' => esc_html__( 'Button Line Height', 'charity-addon-for-elementor' ),
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
							'{{WRAPPER}} .nacep-btn' => 'line-height:{{SIZE}}{{UNIT}};',
						],
					]
				);
				$this->add_group_control(
					Group_Control_Typography::get_type(),
					[
						'label' => esc_html__( 'Typography', 'charity-addon-for-elementor' ),
						'name' => 'btn_typography',
						'selector' => '{{WRAPPER}} .nacep-btn',
					]
				);
				$this->add_responsive_control(
					'btn_icon_size',
					[
						'label' => esc_html__( 'Icon Size', 'charity-addon-for-elementor' ),
						'type' => Controls_Manager::SLIDER,
						'range' => [
							'px' => [
								'min' => 0,
								'max' => 1500,
								'step' => 1,
							],
						],
						'size_units' => [ 'px' ],
						'selectors' => [
							'{{WRAPPER}} .nacep-btn i' => 'font-size:{{SIZE}}{{UNIT}};',
						],
					]
				);
				$this->start_controls_tabs( 'btn_style' );
					$this->start_controls_tab(
						'btn_normal',
						[
							'label' => esc_html__( 'Normal', 'charity-addon-for-elementor' ),
						]
					);
					$this->add_control(
						'btn_color',
						[
							'label' => esc_html__( 'Text Color', 'charity-addon-for-elementor' ),
							'type' => Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .nacep-btn' => 'color: {{VALUE}};',
							],
						]
					);
					$this->add_control(
						'btn_icon_color',
						[
							'label' => esc_html__( 'Icon Color', 'charity-addon-for-elementor' ),
							'type' => Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .nacep-btn i' => 'color: {{VALUE}};',
							],
						]
					);
					$this->add_control(
						'btn_bg_color',
						[
							'label' => esc_html__( 'Background Color', 'charity-addon-for-elementor' ),
							'type' => Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .nacep-btn:before' => 'background-color: {{VALUE}};',
							],
						]
					);
					$this->add_group_control(
						Group_Control_Border::get_type(),
						[
							'name' => 'btn_border',
							'label' => esc_html__( 'Border', 'charity-addon-for-elementor' ),
							'selector' => '{{WRAPPER}} .nacep-btn:before',
						]
					);
					$this->end_controls_tab();  // end:Normal tab
					$this->start_controls_tab(
						'btn_hover',
						[
							'label' => esc_html__( 'Hover', 'charity-addon-for-elementor' ),
						]
					);
					$this->add_control(
						'btn_hover_color',
						[
							'label' => esc_html__( 'Text Color', 'charity-addon-for-elementor' ),
							'type' => Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .nacep-btn:hover' => 'color: {{VALUE}};',
							],
						]
					);
					$this->add_control(
						'btn_icon_hover_color',
						[
							'label' => esc_html__( 'Icon Color', 'charity-addon-for-elementor' ),
							'type' => Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .nacep-btn:hover i' => 'color: {{VALUE}};',
							],
						]
					);
					$this->add_control(
						'btn_bg_hover_color',
						[
							'label' => esc_html__( 'Background Color', 'charity-addon-for-elementor' ),
							'type' => Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .nacep-btn:hover:before' => 'background-color: {{VALUE}};',
							],
						]
					);
					$this->add_group_control(
						Group_Control_Border::get_type(),
						[
							'name' => 'btn_hover_border',
							'label' => esc_html__( 'Border', 'charity-addon-for-elementor' ),
							'selector' => '{{WRAPPER}} .nacep-btn:hover:before',
						]
					);
					$this->end_controls_tab();  // end:Hover tab
					$this->start_controls_tab(
						'btn_active',
						[
							'label' => esc_html__( 'Active', 'charity-addon-for-elementor' ),
						]
					);
					$this->add_control(
						'btn_active_color',
						[
							'label' => esc_html__( 'Text Color', 'charity-addon-for-elementor' ),
							'type' => Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .nacep-btn:active, {{WRAPPER}} .nacep-btn:focus' => 'color: {{VALUE}};',
							],
						]
					);
					$this->add_control(
						'btn_icon_active_color',
						[
							'label' => esc_html__( 'Icon Color', 'charity-addon-for-elementor' ),
							'type' => Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .nacep-btn:active i, {{WRAPPER}} .nacep-btn:focus i' => 'color: {{VALUE}};',
							],
						]
					);
					$this->add_control(
						'btn_bg_active_color',
						[
							'label' => esc_html__( 'Background Color', 'charity-addon-for-elementor' ),
							'type' => Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .nacep-btn:active:after, {{WRAPPER}} .nacep-btn:focus:after' => 'background-color: {{VALUE}};',
							],
						]
					);
					$this->add_group_control(
						Group_Control_Border::get_type(),
						[
							'name' => 'btn_active_border',
							'label' => esc_html__( 'Border', 'charity-addon-for-elementor' ),
							'selector' => '{{WRAPPER}} .nacep-btn:active:after, {{WRAPPER}} .nacep-btn:focus:after',
						]
					);
					$this->end_controls_tab();  // end:Hover tab
				$this->end_controls_tabs(); // end tabs
				$this->end_controls_section();// end: Section

			// Pagination
				$this->start_controls_section(
					'section_pagi_style',
					[
						'label' => esc_html__( 'Pagination', 'charity-addon-for-elementor' ),
						'tab' => Controls_Manager::TAB_STYLE,
						'condition' => [
							'cause_pagination' => 'true',
							'cause_style' => 'one',
						],
					]
				);
				$this->add_responsive_control(
					'pagi_padding',
					[
						'label' => __( 'Padding', 'charity-addon-for-elementor' ),
						'type' => Controls_Manager::DIMENSIONS,
						'size_units' => [ 'px' ],
						'selectors' => [
							'{{WRAPPER}} .nacep-pagination' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						],
					]
				);
				$this->add_responsive_control(
					'pagi_width',
					[
						'label' => esc_html__( 'Pagination Width', 'charity-addon-for-elementor' ),
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
							'{{WRAPPER}} .nacep-pagination ul li span, {{WRAPPER}} .nacep-pagination ul li a ' => 'width:{{SIZE}}{{UNIT}};height:{{SIZE}}{{UNIT}};',
						],
					]
				);
				$this->add_group_control(
					Group_Control_Typography::get_type(),
					[
						'name' => 'pagi_typography',
						'selector' => '{{WRAPPER}} .nacep-pagination ul li a, {{WRAPPER}} .nacep-pagination ul li span',
					]
				);
				$this->start_controls_tabs( 'pagi_style' );
					$this->start_controls_tab(
						'pagi_normal',
						[
							'label' => esc_html__( 'Normal', 'charity-addon-for-elementor' ),
						]
					);
					$this->add_control(
						'pagi_color',
						[
							'label' => esc_html__( 'Color', 'charity-addon-for-elementor' ),
							'type' => Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .nacep-pagination ul li a' => 'color: {{VALUE}};',
							],
						]
					);
					$this->add_control(
						'pagi_bg_color',
						[
							'label' => esc_html__( 'Background Color', 'charity-addon-for-elementor' ),
							'type' => Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .nacep-pagination ul li a' => 'background-color: {{VALUE}};',
							],
						]
					);
					$this->add_group_control(
						Group_Control_Border::get_type(),
						[
							'name' => 'pagi_border',
							'label' => esc_html__( 'Border', 'charity-addon-for-elementor' ),
							'selector' => '{{WRAPPER}} .nacep-pagination ul li a',
						]
					);
					$this->end_controls_tab();  // end:Normal tab

					$this->start_controls_tab(
						'pagi_hover',
						[
							'label' => esc_html__( 'Hover', 'charity-addon-for-elementor' ),
						]
					);
					$this->add_control(
						'pagi_hover_color',
						[
							'label' => esc_html__( 'Color', 'charity-addon-for-elementor' ),
							'type' => Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .nacep-pagination ul li a:hover' => 'color: {{VALUE}};',
							],
						]
					);
					$this->add_control(
						'pagi_bg_hover_color',
						[
							'label' => esc_html__( 'Background Color', 'charity-addon-for-elementor' ),
							'type' => Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .nacep-pagination ul li a:hover' => 'background-color: {{VALUE}};',
							],
						]
					);
					$this->add_group_control(
						Group_Control_Border::get_type(),
						[
							'name' => 'pagi_hover_border',
							'label' => esc_html__( 'Border', 'charity-addon-for-elementor' ),
							'selector' => '{{WRAPPER}} .nacep-pagination ul li a:hover',
						]
					);
					$this->end_controls_tab();  // end:Hover tab
					$this->start_controls_tab(
						'pagi_active',
						[
							'label' => esc_html__( 'Active', 'charity-addon-for-elementor' ),
						]
					);
					$this->add_control(
						'pagi_active_color',
						[
							'label' => esc_html__( 'Color', 'charity-addon-for-elementor' ),
							'type' => Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .nacep-pagination ul li span.current' => 'color: {{VALUE}};',
							],
						]
					);
					$this->add_control(
						'pagi_bg_active_color',
						[
							'label' => esc_html__( 'Background Color', 'charity-addon-for-elementor' ),
							'type' => Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .nacep-pagination ul li span.current' => 'background-color: {{VALUE}};',
							],
						]
					);
					$this->add_group_control(
						Group_Control_Border::get_type(),
						[
							'name' => 'pagi_active_border',
							'label' => esc_html__( 'Border', 'charity-addon-for-elementor' ),
							'selector' => '{{WRAPPER}} .nacep-pagination ul li span.current',
						]
					);
					$this->end_controls_tab();  // end:Active tab
				$this->end_controls_tabs(); // end tabs
				$this->end_controls_section();// end: Section

			// Navigation
				$this->start_controls_section(
					'section_navigation_style',
					[
						'label' => esc_html__( 'Navigation', 'charity-elementor-addon' ),
						'tab' => Controls_Manager::TAB_STYLE,
						'condition' => [
							'carousel_nav' => 'true',
							'cause_style' => 'two',
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
							'cause_style' => 'two',
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
		 * Render Cause Filter widget output on the frontend.
		 * Written in PHP and used to generate the final HTML.
		*/
		protected function render() {
			$settings = $this->get_settings_for_display();
			$cause_style 				= !empty( $settings['cause_style'] ) ? $settings['cause_style'] : '';
			$cause_col 				= !empty( $settings['cause_col'] ) ? $settings['cause_col'] : '';
			$cause_limit 				= !empty( $settings['cause_limit'] ) ? $settings['cause_limit'] : '';
			$cause_order 				= !empty( $settings['cause_order'] ) ? $settings['cause_order'] : '';
			$cause_orderby 				= !empty( $settings['cause_orderby'] ) ? $settings['cause_orderby'] : '';
			$cause_show_category 				= !empty( $settings['cause_show_category'] ) ? $settings['cause_show_category'] : [];
			$cause_show_id 				= !empty( $settings['cause_show_id'] ) ? $settings['cause_show_id'] : '';
			$cause_pagination 				= !empty( $settings['cause_pagination'] ) ? $settings['cause_pagination'] : '';
			$read_more_txt 				= !empty( $settings['read_more_txt'] ) ? $settings['read_more_txt'] : '';
			$btn_icon         = !empty( $settings['btn_icon'] ) ? $settings['btn_icon'] : '';
			$icon = $btn_icon ? ' <i class="'.$btn_icon.'" aria-hidden="true"></i>' : '';
			$goal_title 				= !empty( $settings['goal_title'] ) ? $settings['goal_title'] : '';
			$income_title 				= !empty( $settings['income_title'] ) ? $settings['income_title'] : '';
			$donor_title 				= !empty( $settings['donor_title'] ) ? $settings['donor_title'] : '';
			$all_text 				= !empty( $settings['all_text'] ) ? $settings['all_text'] : '';
			$need_filter = !empty( $settings['need_filter'] ) ? $settings['need_filter'] : '';

			if ($cause_col === '3') {
				$col_class = ' data-items="3"';
			}elseif ($cause_col === '4') {
				$col_class = ' data-items="4"';
			} else {
				$col_class = '';
			}

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

	    if ($cause_show_id) {
				$cause_show_id = json_encode( $cause_show_id );
				$cause_show_id = str_replace(array( '[', ']' ), '', $cause_show_id);
				$cause_show_id = str_replace(array( '"', '"' ), '', $cause_show_id);
	      $cause_show_id = explode(',',$cause_show_id);
	    } else {
	      $cause_show_id = '';
	    }

	    $cas_args = array(
			  'paged' => $my_page,
			  'post_type' => 'give_forms',
			  'posts_per_page' => (int)$cause_limit,
			  'category_name' => implode(',', $cause_show_category),
			  'orderby' => $cause_orderby,
			  'order' => $cause_order,
	      'post__in' => $cause_show_id,
			);

    $nacep_cas = new \WP_Query( $cas_args );
    if ($cause_style === 'two') { ?>
     	<div class="nacep-give-cause-slider">
	      <div class="owl-carousel" <?php echo $carousel_loop . $carousel_items . $carousel_margin . $carousel_dots . $carousel_nav . $carousel_autoplay_timeout . $carousel_autoplay . $carousel_animate_out . $carousel_mousedrag . $carousel_autowidth . $carousel_autoheight  . $carousel_tablet . $carousel_mobile . $carousel_small_mobile; ?>>
			    <?php if ($nacep_cas->have_posts()) : while ($nacep_cas->have_posts()) : $nacep_cas->the_post();

			    $form        = new \Give_Donate_Form( get_the_ID() );
					$goal        = $form->goal;
					$income      = $form->get_earnings();

					$progress = round( ( $income / $goal ) * 100, 2 );
					if ( $income >= $goal ) {
					  $progress = 100;
					}

					$income = give_human_format_large_amount( give_format_amount( $income ) );
					$goal = give_human_format_large_amount( give_format_amount( $goal ) );

					$large_image =  wp_get_attachment_image_src( get_post_thumbnail_id(get_the_ID()), 'fullsize', false, '' );
				  $large_image = $large_image[0];

				  if ($large_image) {
						$img_cls = '';
						$col_cls = 'col-na-5';
					} else {
						$img_cls = ' no-img';
						$col_cls = 'col-na-12';
					}
					?>
					<div class="cause-slider-item-wrap">
						<div class="cause-slider-item<?php echo esc_attr($img_cls); ?>">
							<div class="col-na-row align-items-center">
								<?php if ($large_image) { ?>
								<div class="col-na-7">
								  <div class="nacep-image">
								    <a href="<?php echo esc_url( get_permalink() ); ?>"><img src="<?php echo esc_url($large_image); ?>" alt="<?php echo esc_attr(get_the_title()); ?>"></a>
								  </div>
								</div>
								<?php } ?>
								<div class="<?php echo esc_attr($col_cls); ?>">
									<div class="cause-slider-info">
										<div class="cause-category">
						          <?php
						            $category_list = wp_get_post_terms(get_the_ID(), 'give_forms_category');
						            $i=1;
						            foreach ($category_list as $term) {
						              $term_link = get_term_link( $term );
						              echo '<a href="'. esc_url($term_link) .'" class="category-name">'. esc_attr($term->name) .'</a> ';
						              if($i++==2) break;
						            }
						          ?>
						        </div>
								  	<h3 class="cause-title"><a href="<?php echo esc_url( get_permalink() ); ?>"><?php echo esc_html(get_the_title()); ?></a></h3>
							  		<?php nacharity_excerpt(); ?>
									</div>
						  		<div class="cause-donate-info">
						  			<div class="col-na-row">
						  				<div class="col-na-6">
						  					<p class="goal-title"><?php echo esc_html($goal_title); ?></p>
						  					<h3 class="goal-symbol"><?php echo esc_html(give_currency_symbol().$goal); ?></h3>
						  				</div>
						  				<div class="col-na-6 align-right">
						  					<p class="income-title"><?php echo esc_html($income_title); ?></p>
						  					<h3 class="income-symbol"><?php echo esc_html(give_currency_symbol().$income); ?></h3>
						  				</div>
						  				<div class="col-na-12">
												<div class="nacep-cause-bar"><span class="progress-bar" role="progressbar" aria-valuenow="<?php echo esc_attr($progress); ?>" aria-valuemin="0" aria-valuemax="100"></span></div>
							  			</div>
						  			</div>
					  			</div>
					  			<div class="nacep-btn-wrap">
					  				<a href="<?php echo esc_url( get_permalink() ); ?>" class="nacep-btn nacep-btn-sml"><?php echo esc_html($read_more_txt); echo $icon; ?></a>
					  			</div>
								</div>
							</div>
						</div>
					</div>
			  	<?php endwhile; ?>
				</div>
			  <?php wp_reset_postdata();
				endif; ?>
			</div>
    <?php } else { ?>
	  	<div class="nacep-give-cause-filter">
				<div class="masonry-wrap">
					<?php 
          $give_forms_category = get_terms('give_forms_category');
					if ($need_filter && $give_forms_category) { ?>
					<div class="masonry-filters">
		        <ul>
		          <li><a href="javascript:void(0);" data-filter="*" class="active"><?php echo esc_html($all_text); ?></a></li>
		          <?php
		            if ($cause_show_category) {
		              $terms = $cause_show_category;
		              $count = count($terms);
		              if ($count > 0) {
		                foreach ($terms as $term) {
		                  echo '<li class="cat-'. preg_replace('/\s+/', "", strtolower($term)) .'"><a href="javascript:void(0);" data-filter=".cat-'. preg_replace('/\s+/', "", strtolower($term)) .'" title="' . str_replace('-', " ", strtolower($term)) . '">' . str_replace('-', " ", ucwords($term)) . '</a></li>';
		                 }
		              }
		            } else {
		              $terms = get_terms('give_forms_category');
							    $count = count($terms);
							    $i=0;
							    $term_list = '';
							    if ($count > 0) {
							      foreach ($terms as $term) {
							        $i++;
							        $term_list .= '<li><a href="javascript:void(0);" class="filter cat-'. esc_attr($term->slug) .'" data-filter=".cat-'. esc_attr($term->slug) .'" title="' . esc_attr($term->name) . '">' . esc_html($term->name) . '</a></li>';
							        if ($count != $i) {
							          $term_list .= '';
							        } else {
							          $term_list .= '';
							        }
							      }
							      echo $term_list;
							    }
		            }
		          ?>
		        </ul>
		      </div>
					<?php }	?>
		      <div class="nacep-masonry"<?php echo $col_class; ?>>
				    <?php if ($nacep_cas->have_posts()) : while ($nacep_cas->have_posts()) : $nacep_cas->the_post();

				    global $post;
						$nacep_terms = wp_get_post_terms($post->ID,'give_forms_category');
						foreach ($nacep_terms as $term) {
						  $nacep_cat_class = 'cat-' . $term->slug;
						}
						$nacep_count = count($nacep_terms);
						$i=0;
						$nacep_cat_class = '';
						if ($nacep_count > 0) {
						  foreach ($nacep_terms as $term) {
						    $i++;
						    $nacep_cat_class .= 'cat-'. $term->slug .' ';
						    if ($nacep_count != $i) {
						      $nacep_cat_class .= '';
						    } else {
						      $nacep_cat_class .= '';
						    }
						  }
						}

				    $form        = new \Give_Donate_Form( get_the_ID() );
						$goal        = $form->goal;
						$income      = $form->get_earnings();

						if ($income && $goal) {
							$progress = round( ( $income / $goal ) * 100, 2 );
						} else {
							$progress = '';
						}
						if ( $income >= $goal ) {
						  $progress = 100;
						}

						$income = give_human_format_large_amount( give_format_amount( $income ) );
						$goal = give_human_format_large_amount( give_format_amount( $goal ) );

						if(function_exists('give_get_payments')) {
							$args = array(
								'give_forms' => array( get_the_ID() ),
							);
							$donations = give_get_payments( $args );
							$donor_count = count($donations);
						} else {
							$donor_count = 0;
						}

						$large_image =  wp_get_attachment_image_src( get_post_thumbnail_id(get_the_ID()), 'fullsize', false, '' );
					  $large_image = $large_image[0];

					  if ($large_image) {
							$img_cls = '';
						} else {
							$img_cls = ' no-img';
						}
						?>
						<div class="masonry-item <?php echo esc_attr($nacep_cat_class); ?>" data-category="<?php echo esc_attr($nacep_cat_class); ?>">
							<div class="nacep-cause-list-item<?php echo esc_attr($img_cls); ?>">
								<?php if ($large_image) { ?>
								  <div class="nacep-image">
								    <a href="<?php echo esc_url( get_permalink() ); ?>"><img src="<?php echo esc_url($large_image); ?>" alt="<?php echo esc_attr(get_the_title()); ?>"></a>
								  </div>
								<?php } ?>
								<div class="cause-info">
							  	<h3 class="cause-title"><a href="<?php echo esc_url( get_permalink() ); ?>"><?php echo esc_html(get_the_title()); ?></a></h3>
							  	<div class="cause-author">
								  	<a href="<?php echo esc_url(get_author_posts_url( get_the_author_meta( 'ID' ) )); ?>">
							        <?php echo get_avatar( get_the_author_meta( 'ID' ), 30 ); ?>
							        <span><?php echo esc_html(get_the_author()); ?></span>
							      </a>
							    </div>
							  </div>
				  			<div class="cause-donate-info">
				  				<div class="cause-amount">
				  					<h3 class="income-symbol"><?php echo esc_html(give_currency_symbol().$income); ?></h3>
				  					<p class="goal-title"><?php echo esc_html($goal_title); ?> <?php echo esc_html(give_currency_symbol().$goal); ?></p>
				  				</div>
				  				<div class="cause-doners">
				  					<h3 class="donor-count"><?php echo esc_html($donor_count); ?></h3>
				  					<p class="donor-title"><?php echo esc_html($donor_title); ?></p>
				  				</div>
									<div class="nacep-cause-bar"><span class="progress-bar" role="progressbar" aria-valuenow="<?php echo esc_attr($progress); ?>" aria-valuemin="0" aria-valuemax="100"></span></div>
				  			</div>
							</div>
						</div>
				  	<?php endwhile; ?>
					</div>
				  <?php wp_reset_postdata();
					if ($cause_pagination && $nacep_cas->max_num_pages != '1') { nacharity_paging_nav($nacep_cas->max_num_pages,"",$paged); } ?>
				  <?php endif; ?>
				</div>
			</div>
		<?php }
	  // Return outbut buffer
		echo ob_get_clean();

		}

	}
	Plugin::instance()->widgets_manager->register_widget_type( new Charity_Elementor_Addon_Unique_Cause_Filter() );
}
