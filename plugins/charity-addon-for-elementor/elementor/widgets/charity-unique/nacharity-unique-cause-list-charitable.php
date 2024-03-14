<?php
/*
 * Elementor Charity Addon for Elementor Cause List
 * Author & Copyright: NicheAddon
*/
namespace Elementor;

if ( is_plugin_active( 'charitable/charitable.php' ) ) {

	if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

	class Charity_Elementor_Addon_Unique_Cause_List_Charitable extends Widget_Base{

		/**
		 * Retrieve the widget name.
		*/
		public function get_name(){
			return 'nacharity_unique_cause_list_charitable';
		}

		/**
		 * Retrieve the widget title.
		*/
		public function get_title(){
			return esc_html__( 'Cause List', 'charity-addon-for-elementor' );
		}

		/**
		 * Retrieve the widget icon.
		*/
		public function get_icon() {
			return 'eicon-archive-posts';
		}

		/**
		 * Retrieve the cause-list of categories the widget belongs to.
		*/
		public function get_categories() {
			return ['nacharity-unique-category'];
		}

		/**
		 * Register Charitys Addon for Elementor Cause List widget controls.
		 * Adds different input fields to allow the user to change and customize the widget settings.
		*/
		protected function _register_controls(){

			$charity = get_posts( 'post_type="campaign"&numberposts=-1' );
	    $CharityID = array();
	    if ( $charity ) {
	      foreach ( $charity as $form ) {
	        $CharityID[ $form->ID ] = $form->post_title;
	      }
	    } else {
	      $CharityID[ __( 'No ID\'s found', 'charity-addon-for-elementor' ) ] = 0;
	    }

			$this->start_controls_section(
				'section_cause_listing',
				[
					'label' => esc_html__( 'Listing Options', 'charity-addon-for-elementor' ),
				]
			);
			$this->add_control(
				'cause_style',
				[
					'label' => esc_html__( 'Cause Style', 'charity-addon-for-elementor' ),
					'type' => Controls_Manager::SELECT,
					'options' => [
						'one'          => esc_html__('Style One', 'charity-addon-for-elementor'),
						'two'          => esc_html__('Style Two', 'charity-addon-for-elementor'),
						'three'        => esc_html__('Style Three', 'charity-addon-for-elementor'),
						'four'         => esc_html__('Style Four', 'charity-addon-for-elementor'),
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
						'1'          => esc_html__('One', 'charity-addon-for-elementor'),
						'2'          => esc_html__('Two', 'charity-addon-for-elementor'),
	          '3'          => esc_html__('Three', 'charity-addon-for-elementor'),
	          '4'          => esc_html__('Four', 'charity-addon-for-elementor'),
					],
					'default' => '3',
				]
			);
			$this->add_control(
				'cause_limit',
				[
					'label' => esc_html__( 'Cause Limit', 'charity-addon-for-elementor' ),
					'type' => Controls_Manager::NUMBER,
					'min' => 1,
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
					'options' => NACEP_Controls_Helper_Output::get_terms_names( 'campaign_category'),
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
				'need_content',
				[
					'label' => esc_html__( 'Need Content', 'charity-addon-for-elementor' ),
					'type' => Controls_Manager::SWITCHER,
					'label_on' => esc_html__( 'Show', 'charity-addon-for-elementor' ),
					'label_off' => esc_html__( 'Hide', 'charity-addon-for-elementor' ),
					'return_value' => 'true',
					'default' => 'false',
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
				]
			);
			$this->add_control(
				'goal_title',
				[
					'label' => esc_html__( 'Goal Title', 'charity-addon-for-elementor' ),
					'type' => Controls_Manager::TEXT,
					'label_block' => true,
					'default' => esc_html__( 'of', 'charity-addon-for-elementor' ),
					'placeholder' => esc_html__( 'Type text here', 'charity-addon-for-elementor' ),
				]
			);
			$this->add_control(
				'donor_title',
				[
					'label' => esc_html__( 'Donors Title', 'charity-addon-for-elementor' ),
					'type' => Controls_Manager::TEXT,
					'label_block' => true,
					'default' => esc_html__( 'Donors', 'charity-addon-for-elementor' ),
					'placeholder' => esc_html__( 'Type text here', 'charity-addon-for-elementor' ),
				]
			);
			$this->add_control(
				'remaing_title',
				[
					'label' => esc_html__( 'Remaing Title', 'charity-addon-for-elementor' ),
					'type' => Controls_Manager::TEXT,
					'label_block' => true,
					'default' => esc_html__( 'Remaing', 'charity-addon-for-elementor' ),
					'placeholder' => esc_html__( 'Type text here', 'charity-addon-for-elementor' ),
					'condition' => [
						'cause_style' => array('two','four'),
					],
				]
			);
			$this->add_control(
				'btn_text',
				[
					'label' => esc_html__( 'Button Text', 'charity-addon-for-elementor' ),
					'type' => Controls_Manager::TEXT,
					'label_block' => true,
					'default' => esc_html__( 'Donate', 'charity-addon-for-elementor' ),
					'placeholder' => esc_html__( 'Type text here', 'charity-addon-for-elementor' ),
					'condition' => [
						'cause_style' => array('three','four'),
					],
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
			    'condition' => [
						'cause_style' => array('three','four'),
					],
			  ]
			);
			$this->end_controls_section();// end: Section

			// Circle Progress
				$this->start_controls_section(
					'section_bar',
					[
						'label' => esc_html__( 'Circle Progress Bar', 'charity-addon-for-elementor' ),
						'condition' => [
							'cause_style' => array('two'),
						],
					]
				);
				$this->add_control(
					'bar_color',
					[
						'label' => esc_html__( 'Progress Bar Color', 'charity-addon-for-elementor' ),
						'type' => Controls_Manager::COLOR,
					]
				);
				$this->add_control(
					'bar_fill_color',
					[
						'label' => esc_html__( 'Progress Bar Fill Color', 'charity-addon-for-elementor' ),
						'type' => Controls_Manager::COLOR,
					]
				);
				$this->add_control(
					'bar_bg_color',
					[
						'label' => esc_html__( 'Progress Bar Background Color', 'charity-addon-for-elementor' ),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .circle-progressbar canvas' => 'background-color: {{VALUE}};',
						],
					]
				);
				$this->add_control(
					'reverse',
					[
						'label' => esc_html__( 'Reverse Animation?', 'charity-addon-for-elementor' ),
						'type' => Controls_Manager::SWITCHER,
						'label_on' => esc_html__( 'Yes', 'charity-addon-for-elementor' ),
						'label_off' => esc_html__( 'No', 'charity-addon-for-elementor' ),
						'return_value' => 'true',
					]
				);
				$this->add_responsive_control(
					'size',
					[
						'label' => esc_html__( 'Canvas Size', 'charity-elementor-addon' ),
						'type' => Controls_Manager::NUMBER,
						'min' => 1,
						'max' => 1000,
						'step' => 1,
						'default' => 150,
					]
				);
				$this->add_responsive_control(
					'thickness',
					[
						'label' => esc_html__( 'Thickness', 'charity-elementor-addon' ),
						'type' => Controls_Manager::NUMBER,
						'min' => 1,
						'max' => 100,
						'step' => 1,
						'default' => 10,
					]
				);
				$this->add_responsive_control(
					'start_angle',
					[
						'label' => esc_html__( 'Start Angle', 'charity-elementor-addon' ),
						'type' => Controls_Manager::NUMBER,
						'min' => 0,
						'max' => 300,
						'step' => 1,
						'default' => 300,
					]
				);
				$this->end_controls_section();// end: Section

			// Section
				$this->start_controls_section(
					'sectn_style',
					[
						'label' => esc_html__( 'Section', 'charity-addon-for-elementor' ),
						'tab' => Controls_Manager::TAB_STYLE,
					]
				);
				$this->add_control(
					'box_border_radius',
					[
						'label' => __( 'Border Radius', 'charity-addon-for-elementor' ),
						'type' => Controls_Manager::DIMENSIONS,
						'size_units' => [ 'px', '%', 'em' ],
						'selectors' => [
							'{{WRAPPER}} .nacep-cause-list-item' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						],
					]
				);
				$this->add_responsive_control(
					'cause_section_margin',
					[
						'label' => __( 'Margin', 'charity-addon-for-elementor' ),
						'type' => Controls_Manager::DIMENSIONS,
						'size_units' => [ 'px' ],
						'selectors' => [
							'{{WRAPPER}} .nacep-cause-list-item' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						],
					]
				);
				$this->add_responsive_control(
					'cause_section_padding',
					[
						'label' => __( 'Padding', 'charity-addon-for-elementor' ),
						'type' => Controls_Manager::DIMENSIONS,
						'size_units' => [ 'px' ],
						'selectors' => [
							'{{WRAPPER}} .nacep-cause-list-item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						],
					]
				);
				$this->add_control(
					'secn_bg_color',
					[
						'label' => esc_html__( 'Background Color', 'charity-addon-for-elementor' ),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .nacep-cause-list-item' => 'background-color: {{VALUE}};',
						],
					]
				);
				$this->add_group_control(
					Group_Control_Border::get_type(),
					[
						'name' => 'secn_border',
						'label' => esc_html__( 'Border', 'charity-addon-for-elementor' ),
						'selector' => '{{WRAPPER}} .nacep-cause-list-item',
					]
				);
				$this->add_group_control(
					Group_Control_Box_Shadow::get_type(),
					[
						'name' => 'secn_box_shadow',
						'label' => esc_html__( 'Section Box Shadow', 'charity-addon-for-elementor' ),
						'selector' => '{{WRAPPER}} .nacep-cause-list-item',
					]
				);
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

			// Content
				$this->start_controls_section(
					'section_content_style',
					[
						'label' => esc_html__( 'Content', 'charity-addon-for-elementor' ),
						'tab' => Controls_Manager::TAB_STYLE,
					]
				);
					$this->add_group_control(
						Group_Control_Typography::get_type(),
						[
							'label' => esc_html__( 'Typography', 'charity-addon-for-elementor' ),
							'name' => 'content_typography',
							'selector' => '{{WRAPPER}} .cause-info p',
						]
					);
					$this->add_control(
						'content_color',
						[
							'label' => esc_html__( 'Color', 'charity-addon-for-elementor' ),
							'type' => Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .cause-info p' => 'color: {{VALUE}};',
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
						'condition' => [
							'cause_style!' => array('two'),
						],
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
							'organizer_pagination' => 'true',
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

		}

		/**
		 * Render Cause List widget output on the frontend.
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
			$need_content 				= !empty( $settings['need_content'] ) ? $settings['need_content'] : '';
			$cause_pagination 				= !empty( $settings['cause_pagination'] ) ? $settings['cause_pagination'] : '';
			$goal_title 				= !empty( $settings['goal_title'] ) ? $settings['goal_title'] : '';
			$donor_title 				= !empty( $settings['donor_title'] ) ? $settings['donor_title'] : '';
			$remaing_title 				= !empty( $settings['remaing_title'] ) ? $settings['remaing_title'] : '';
			$btn_text 				= !empty( $settings['btn_text'] ) ? $settings['btn_text'] : '';
			$btn_icon 				= !empty( $settings['btn_icon'] ) ? $settings['btn_icon'] : '';
			$icon = $btn_icon ? ' <i class="'.$btn_icon.'" aria-hidden="true"></i>' : '';

			$bar_color 				= !empty( $settings['bar_color'] ) ? $settings['bar_color'] : '';
			$bar_fill_color 	= !empty( $settings['bar_fill_color'] ) ? $settings['bar_fill_color'] : '';
			$reverse 				  = !empty( $settings['reverse'] ) ? $settings['reverse'] : '';
			$size 						= !empty( $settings['size'] ) ? $settings['size'] : '';
			$thickness 						= !empty( $settings['thickness'] ) ? $settings['thickness'] : '';
			$start_angle 						= !empty( $settings['start_angle'] ) ? $settings['start_angle'] : '';

			$bar_color = $bar_color ? ' data-color="'.$bar_color.'"' : '';
			$bar_fill_color = $bar_fill_color ? ' data-fill="'.$bar_fill_color.'"' : '';
			$reverse = $reverse ? ' data-reverse="true"' : ' data-reverse="false"';
			$size = $size ? ' data-size="'.$size.'"' : '';
			$thickness = $thickness ? ' data-thickness="'.$thickness.'"' : '';
			$start_angle = $start_angle ? ' data-start="'.$start_angle.'"' : '';

			if ($cause_style === 'two') {
				$style_cls = ' style-two';
			} elseif ($cause_style === 'three') {
				$style_cls = ' style-three';
			} elseif ($cause_style === 'four') {
				$style_cls = ' style-four style-two';
			} else {
				$style_cls = '';
			}

			if ($cause_col === '2') {
				$col_class = 'col-na-6';
			} elseif ($cause_col === '1') {
				$col_class = 'col-na-12';
			} elseif ($cause_col === '4') {
				$col_class = 'col-na-3';
			} else {
				$col_class = 'col-na-4';
			}

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
			  'post_type' => 'campaign',
			  'posts_per_page' => (int)$cause_limit,
			  'category_name' => implode(',', $cause_show_category),
			  'orderby' => $cause_orderby,
			  'order' => $cause_order,
	      'post__in' => $cause_show_id,
			);

		ob_start();
    $nacep_cas = new \WP_Query( $cas_args ); ?>
  	<div class="nacep-give-cause-list<?php echo esc_attr($style_cls); ?>">
			<div class="col-na-row">
	    <?php if ($nacep_cas->have_posts()) : while ($nacep_cas->have_posts()) : $nacep_cas->the_post();

	    $campaign        = new \Charitable_Campaign( $id );
			$currency_helper = charitable_get_currency_helper();
			$income          = $campaign->get_donated_amount();
			$goal            = $campaign->get_meta( '_campaign_goal' );
			$donor_count 		 = $campaign->get_donor_count();

			$remaing = $goal - $income;
			if ($income && $goal) {
				$progress = round( ( $income / $goal ) * 100 );
			} else {
				$progress = '';
			}

			if ( $income >= $goal ) {
			  $progress = 100;
			  $remaing = 0;
			}

			if ( $income >= $goal ) {
			  $progressCir = 1;
			} else {
			  $progressCir = '0.'.$progress;
			}

			$large_image =  wp_get_attachment_image_src( get_post_thumbnail_id(get_the_ID()), 'fullsize', false, '' );
		  $large_image = $large_image[0];

		  if ($large_image) {
				$img_cls = '';
			} else {
				$img_cls = ' no-img';
			}
			?>
			<div class="<?php echo esc_attr($col_class); ?>">
				<?php if ($cause_style === 'two') { ?>
					<div class="nacep-cause-list-item<?php echo esc_attr($img_cls); ?>">
						<div class="cause-info">
					  	<h3 class="cause-title"><a href="<?php echo esc_url( get_permalink() ); ?>"><?php echo esc_html(get_the_title()); ?></a></h3>
						  <div class="circle-progressbar-wrap"<?php echo $bar_color . $bar_fill_color . $reverse . $size . $thickness . $start_angle; ?>>
								<div class="circle-progressbar" data-value="<?php echo esc_attr($progressCir); ?>">
	                <h3 class="circle-progressbar-counter"><span class="circle-counter"><?php echo esc_html($progress); ?></span>%</h3>
	              </div>
	            </div>
					  </div>
		  			<div class="cause-donate-info">
			  			<div class="col-na-row">
			  				<div class="col-na-4">
			  					<h3 class="goal-title"><?php echo esc_html($goal_title); ?></h3>
			  					<p class="goal-amount"><?php echo esc_html($currency_helper->get_monetary_amount( $goal )); ?></p>
			  				</div>
			  				<div class="col-na-4">
			  					<h3 class="remaing-title"><?php echo esc_html($remaing_title); ?></h3>
			  					<p class="remaing-amount"><?php echo esc_html($currency_helper->get_monetary_amount( $remaing )); ?></p>
			  				</div>
			  				<div class="col-na-4">
			  					<h3 class="donor-title"><?php echo esc_html($donor_title); ?></h3>
			  					<p class="donor-count"><?php echo esc_html($donor_count); ?></p>
			  				</div>
			  			</div>
		  			</div>
					</div>
				<?php } elseif ($cause_style === 'three') { ?>
					<div class="nacep-cause-list-item<?php echo esc_attr($img_cls); ?>">
						<?php if ($large_image) { ?>
						  <div class="nacep-image">
						    <a href="<?php echo esc_url( get_permalink() ); ?>"><img src="<?php echo esc_url($large_image); ?>" alt="<?php echo esc_attr(get_the_title()); ?>"></a>
						  </div>
						<?php } ?>
		  			<div class="cause-donate-info">
		  				<div class="cause-amount">
		  					<h3 class="income-amount"><?php echo esc_html($currency_helper->get_monetary_amount( $income )); ?></h3>
		  					<p class="goal-amount goal-title-with-amount"><?php echo esc_html($goal_title); ?> <?php echo esc_html($currency_helper->get_monetary_amount( $goal )); ?></p>
		  				</div>
		  				<div class="cause-doners">
		  					<h3 class="donor-count"><?php echo esc_html($donor_count); ?></h3>
		  					<p class="donor-title"><?php echo esc_html($donor_title); ?></p>
		  				</div>
							<div class="nacep-cause-bar"><span class="progress-bar" role="progressbar" aria-valuenow="<?php echo esc_attr($progress); ?>" aria-valuemin="0" aria-valuemax="100"></span></div>
		  			</div>
						<div class="cause-info">
					  	<h3 class="cause-title"><a href="<?php echo esc_url( get_permalink() ); ?>"><?php echo esc_html(get_the_title()); ?></a></h3>
					  	<?php nacharity_excerpt(); ?>
					  	<div class="nacep-btn-wrap">
			  				<a href="<?php echo esc_url( get_permalink() ); ?>" class="nacep-btn"><?php echo esc_html($btn_text); echo $icon; ?></a>
			  			</div>
					  </div>
					</div>
				<?php } elseif ($cause_style === 'four') { ?>
					<div class="nacep-cause-list-item<?php echo esc_attr($img_cls); ?>">
						<?php if ($large_image) { ?>
						  <div class="nacep-image">
						    <a href="<?php echo esc_url( get_permalink() ); ?>"><img src="<?php echo esc_url($large_image); ?>" alt="<?php echo esc_attr(get_the_title()); ?>"></a>
						  </div>
						<?php } ?>
		  			<div class="cause-donate-info">
			  			<div class="col-na-row">
			  				<div class="col-na-4">
			  					<h3 class="goal_title"><?php echo esc_html($goal_title); ?></h3>
			  					<p class="goal-amount"><?php echo esc_html($currency_helper->get_monetary_amount( $goal )); ?></p>
			  				</div>
			  				<div class="col-na-4">
			  					<h3 class="remaing-title"><?php echo esc_html($remaing_title); ?></h3>
			  					<p class="remaing-amount"><?php echo esc_html($currency_helper->get_monetary_amount( $remaing )); ?></p>
			  				</div>
			  				<div class="col-na-4">
			  					<h3 class="donor-title"><?php echo esc_html($donor_title); ?></h3>
			  					<p class="donor-count"><?php echo esc_html($donor_count); ?></p>
			  				</div>
			  			</div>
		  			</div>
						<div class="nacep-cause-bar"><span class="progress-bar" role="progressbar" aria-valuenow="<?php echo esc_attr($progress); ?>" aria-valuemin="0" aria-valuemax="100"></span></div>
						<div class="cause-info">
							<div class="cause-category">
			          <?php
			            $category_list = wp_get_post_terms(get_the_ID(), 'campaign_category');
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
					  	<div class="nacep-btn-wrap">
			  				<a href="<?php echo esc_url( get_permalink() ); ?>" class="nacep-btn"><?php echo esc_html($btn_text); echo $icon; ?></a>
			  			</div>
					  </div>
					</div>
				<?php } else { ?>
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
		  					<h3 class="income-amount"><?php echo esc_html($currency_helper->get_monetary_amount( $income )); ?></h3>
		  					<p class="goal-title goal-title-with-amount"><?php echo esc_html($goal_title); ?> <?php echo esc_html($currency_helper->get_monetary_amount( $goal )); ?></p>
		  				</div>
		  				<div class="cause-doners">
		  					<h3 class="donor-count"><?php echo esc_html($donor_count); ?></h3>
		  					<p class="donor-title"><?php echo esc_html($donor_title); ?></p>
		  				</div>
							<div class="nacep-cause-bar"><span class="progress-bar" role="progressbar" aria-valuenow="<?php echo esc_attr($progress); ?>" aria-valuemin="0" aria-valuemax="100"></span></div>
		  			</div>
					</div>
				<?php } ?>
			</div>
	  	<?php endwhile; ?>
			</div>
		  <?php wp_reset_postdata();
			if ($cause_pagination && $nacep_cas->max_num_pages != '1') { nacharity_paging_nav($nacep_cas->max_num_pages,"",$paged); } ?>
		</div>
	  <?php endif;
	  // Return outbut buffer
		echo ob_get_clean();

		}

	}
	Plugin::instance()->widgets_manager->register_widget_type( new Charity_Elementor_Addon_Unique_Cause_List_Charitable() );
}
