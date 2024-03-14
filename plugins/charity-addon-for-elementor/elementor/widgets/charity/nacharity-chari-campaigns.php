<?php
/*
 * Elementor Charity Addon for Elementor Charitable Campaigns
 * Author & Copyright: NicheAddon
*/
namespace Elementor;

if ( is_plugin_active( 'charitable/charitable.php' ) ) {

	if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

	class Charity_Elementor_Addon_Charitable_Campaigns extends Widget_Base{

		/**
		 * Retrieve the widget name.
		*/
		public function get_name(){
			return 'nacharity_charitable_campaigns';
		}

		/**
		 * Retrieve the widget title.
		*/
		public function get_title(){
			return esc_html__( 'Campaigns', 'charity-addon-for-elementor' );
		}

		/**
		 * Retrieve the widget icon.
		*/
		public function get_icon() {
			return 'eicon-posts-grid';
		}

		/**
		 * Retrieve the campaigns of categories the widget belongs to.
		*/
		public function get_categories() {
			return ['nacharity-charitable-category'];
		}

		/**
		 * Register Charitys Addon for Elementor Charitable Campaigns widget controls.
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
				'section_donor',
				[
					'label' => esc_html__( 'Campaigns Options', 'charity-addon-for-elementor' ),
				]
			);			
			$this->add_control(
				'id',
				[
					'label' => __( 'IDs', 'charity-addon-for-elementor' ),
					'type' => Controls_Manager::SELECT2,
					'default' => [],
					'options' => $CharityID,
					'multiple' => true,
					'description' => esc_html__( 'Select a list of IDs. If empty, all published campaigns are displayed.', 'charity-addon-for-elementor' ),
				]
			);
			$this->add_control(
				'orderby',
				[
					'label' => __( 'Order By', 'charity-addon-for-elementor' ),
					'type' => Controls_Manager::SELECT,
					'options' => [
						'post_date'        => esc_html__( 'Post Date', 'give' ),
						'popular'   			 => esc_html__( 'Popular', 'give' ),
						'ending' 				   => esc_html__( 'Ending', 'give' ),
						'title'  					 => esc_html__( 'Title', 'give' ),
						'menu_order'  		 => esc_html__( 'Menu Order', 'give' ),
						'none'  					 => esc_html__( 'None', 'give' ),
						'ID'  						 => esc_html__( 'ID', 'give' ),
						'author'  				 => esc_html__( 'Author', 'give' ),
						'name'  					 => esc_html__( 'Name', 'give' ),
						'modified'  			 => esc_html__( 'Modified', 'give' ),
						'rand'  					 => esc_html__( 'Rand', 'give' ),
					],
					'default' => 'post_date',
					'description' => esc_html__( 'Different parameters to set the order in which campaigns appear.', 'charity-addon-for-elementor' ),
				]
			);
			$this->add_control(
				'order',
				[
					'label' => __( 'Order', 'charity-addon-for-elementor' ),
					'type' => Controls_Manager::SELECT,
					'options' => [
						'ASC' => esc_html__( 'Asending', 'charity-addon-for-elementor' ),
						'DESC' => esc_html__( 'Desending', 'charity-addon-for-elementor' ),
					],
					'default' => 'DESC',
					'description' => esc_html__( 'Sets the order in which campaigns appear.', 'charity-addon-for-elementor' ),
				]
			);
			$this->add_control(
				'number',
				[
					'label' => esc_html__( 'Limit', 'charity-addon-for-elementor' ),
					'type' => Controls_Manager::NUMBER,
					'min' => 0,
					'max' => 100,
					'step' => 1,
					'default' => 10,
					'description' => esc_html__( 'Sets the number of campaigns per page.', 'charity-addon-for-elementor' ),
				]
			);
			$this->add_control(
				'category',
				[
					'label' => __( 'Certain Categories?', 'charity-addon-for-elementor' ),
					'type' => Controls_Manager::SELECT2,
					'default' => [],
					'options' => NACEP_Controls_Helper_Output::get_terms_names( 'campaign_category'),
					'multiple' => true,
				]
			);
			$this->add_control(
				'tag',
				[
					'label' => __( 'Certain Tags?', 'charity-addon-for-elementor' ),
					'type' => Controls_Manager::SELECT2,
					'default' => [],
					'options' => NACEP_Controls_Helper_Output::get_terms_names( 'campaign_tag'),
					'multiple' => true,
				]
			);
			$this->add_control(
				'exclude',
				[
					'label' => __( 'Excluded IDs', 'charity-addon-for-elementor' ),
					'type' => Controls_Manager::SELECT2,
					'default' => [],
					'options' => $CharityID,
					'multiple' => true,
					'description' => esc_html__( 'Select a list of IDs to exclude those from the grid.', 'charity-addon-for-elementor' ),
				]
			);
			$this->add_control(
				'include_inactive',
				[
					'label' => esc_html__( 'Include Inactive?', 'charity-addon-for-elementor' ),
					'type' => Controls_Manager::SWITCHER,
					'label_on' => esc_html__( 'Hide', 'charity-addon-for-elementor' ),
					'label_off' => esc_html__( 'Show', 'charity-addon-for-elementor' ),
					'return_value' => 'true',
				]
			);
			$this->add_control(
				'columns',
				[
					'label' => __( 'Columns', 'charity-addon-for-elementor' ),
					'type' => Controls_Manager::SELECT,
					'options' => [
						'1' => esc_html__( '1', 'charity-addon-for-elementor' ),
						'2' => esc_html__( '2', 'charity-addon-for-elementor' ),
						'3' => esc_html__( '3', 'charity-addon-for-elementor' ),
						'4' => esc_html__( '4', 'charity-addon-for-elementor' ),
					],
					'default' => '2',
					'description' => esc_html__( 'Sets the number of campaign per row.', 'charity-addon-for-elementor' ),
				]
			);
			$this->add_control(
				'button',
				[
					'label' => __( 'Button Type', 'charity-addon-for-elementor' ),
					'type' => Controls_Manager::SELECT,
					'options' => [
						'0' => esc_html__( 'None', 'charity-addon-for-elementor' ),
						'donate' => esc_html__( 'Donate', 'charity-addon-for-elementor' ),
						'details' => esc_html__( 'Details', 'charity-addon-for-elementor' ),
					],
					'default' => 'donate',
					'description' => esc_html__( 'Select button style.', 'charity-addon-for-elementor' ),
				]
			);
			$this->add_control(
				'responsive',
				[
					'label' => esc_html__( 'Responsive Breakpoint', 'charity-addon-for-elementor' ),
					'type' => Controls_Manager::TEXT,
					'placeholder' => esc_html__( '768px', 'charity-addon-for-elementor' ),
					'label_block' => true,
				]
			);
			$this->add_control(
				'masonry',
				[
					'label' => esc_html__( 'Masonry?', 'charity-addon-for-elementor' ),
					'type' => Controls_Manager::SWITCHER,
					'label_on' => esc_html__( 'Hide', 'charity-addon-for-elementor' ),
					'label_off' => esc_html__( 'Show', 'charity-addon-for-elementor' ),
					'return_value' => 'true',
				]
			);
			$this->add_control(
				'map',
				[
					'label' => esc_html__( 'Show Map?', 'charity-addon-for-elementor' ),
					'type' => Controls_Manager::SWITCHER,
					'label_on' => esc_html__( 'Hide', 'charity-addon-for-elementor' ),
					'label_off' => esc_html__( 'Show', 'charity-addon-for-elementor' ),
					'return_value' => 'true',
				]
			);
			$this->add_control(
				'width',
				[
					'label' => esc_html__( 'Map Width', 'charity-addon-for-elementor' ),
					'type' => Controls_Manager::TEXT,
					'placeholder' => esc_html__( '(400px, 100%, etc)', 'charity-addon-for-elementor' ),
					'label_block' => true,
					'condition' => [
						'map' => 'true',
					],
				]
			);
			$this->add_control(
				'height',
				[
					'label' => esc_html__( 'Map Height', 'charity-addon-for-elementor' ),
					'type' => Controls_Manager::TEXT,
					'placeholder' => esc_html__( '400px', 'charity-addon-for-elementor' ),
					'label_block' => true,
					'condition' => [
						'map' => 'true',
					],
				]
			);
			$this->add_control(
				'zoom',
				[
					'label' => esc_html__( 'Map Zoom Level', 'charity-addon-for-elementor' ),
					'type' => Controls_Manager::TEXT,
					'placeholder' => esc_html__( '5', 'charity-addon-for-elementor' ),
					'label_block' => true,
					'condition' => [
						'map' => 'true',
					],
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
				$this->add_responsive_control(
					'section_padding',
					[
						'label' => __( 'Section Padding', 'charity-addon-for-elementor' ),
						'type' => Controls_Manager::DIMENSIONS,
						'size_units' => [ 'px' ],
						'selectors' => [
							'{{WRAPPER}} .nacep-charitable-campaigns ol.campaign-loop.campaign-grid li.campaign' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						],
					]
				);
				$this->add_control(
					'secn_bg_color',
					[
						'label' => esc_html__( 'Background Color', 'charity-addon-for-elementor' ),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .nacep-charitable-campaigns ol.campaign-loop.campaign-grid li.campaign' => 'background-color: {{VALUE}};',
						],
					]
				);
				$this->add_group_control(
					Group_Control_Border::get_type(),
					[
						'name' => 'secn_border',
						'label' => esc_html__( 'Border', 'charity-addon-for-elementor' ),
						'selector' => '{{WRAPPER}} .nacep-charitable-campaigns ol.campaign-loop.campaign-grid li.campaign',
					]
				);
				$this->add_group_control(
					Group_Control_Box_Shadow::get_type(),
					[
						'name' => 'secn_box_shadow',
						'label' => esc_html__( 'Section Box Shadow', 'charity-addon-for-elementor' ),
						'selector' => '{{WRAPPER}} .nacep-charitable-campaigns ol.campaign-loop.campaign-grid li.campaign',
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
						'selector' => '{{WRAPPER}} .nacep-charitable-campaigns ol.campaign-loop.campaign-grid li.campaign a',
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
								'{{WRAPPER}} .nacep-charitable-campaigns ol.campaign-loop.campaign-grid li.campaign a' => 'color: {{VALUE}};',
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
								'{{WRAPPER}} .nacep-charitable-campaigns ol.campaign-loop.campaign-grid li.campaign a:hover' => 'color: {{VALUE}};',
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
						'label' => esc_html__( 'Content', 'charity-addon-for-elementor' ),
						'tab' => Controls_Manager::TAB_STYLE,
					]
				);
				$this->add_control(
					'content_padding',
					[
						'label' => __( 'Padding', 'charity-addon-for-elementor' ),
						'type' => Controls_Manager::DIMENSIONS,
						'size_units' => [ 'px', '%', 'em' ],
						'selectors' => [
							'{{WRAPPER}} .campaign-description' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
						],
					]
				);
				$this->add_group_control(
					Group_Control_Typography::get_type(),
					[
						'label' => esc_html__( 'Typography', 'charity-addon-for-elementor' ),
						'name' => 'content_typography',
						'selector' => '{{WRAPPER}} .campaign-description',
					]
				);
				$this->add_control(
					'content_color',
					[
						'label' => esc_html__( 'Color', 'charity-addon-for-elementor' ),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .campaign-description' => 'color: {{VALUE}} !important;',
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
				$this->start_controls_tabs( 'barr_style' );
					$this->start_controls_tab(
						'bar_normal',
						[
							'label' => esc_html__( 'Normal', 'charity-addon-for-elementor' ),
						]
					);
					$this->add_control(
						'bar_bg_color',
						[
							'label' => esc_html__( 'Background Color', 'charity-addon-for-elementor' ),
							'type' => Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .campaign-progress-bar' => 'background-color: {{VALUE}};',
							],
						]
					);
					$this->end_controls_tab();  // end:Normal tab

					$this->start_controls_tab(
						'bar_hover',
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
							'selector' => '{{WRAPPER}} .campaign-progress-bar span.bar',
						]
					);
					$this->end_controls_tab();  // end:Hover tab
				$this->end_controls_tabs(); // end tabs
				$this->end_controls_section();// end: Section

			// Amount
				$this->start_controls_section(
					'section_amount_style',
					[
						'label' => esc_html__( 'Amount', 'charity-addon-for-elementor' ),
						'tab' => Controls_Manager::TAB_STYLE,
					]
				);
				$this->add_control(
					'amount_padding',
					[
						'label' => __( 'Padding', 'charity-addon-for-elementor' ),
						'type' => Controls_Manager::DIMENSIONS,
						'size_units' => [ 'px', '%', 'em' ],
						'selectors' => [
							'{{WRAPPER}} .campaign-donation-stats' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
						],
					]
				);
				$this->add_group_control(
					Group_Control_Typography::get_type(),
					[
						'label' => esc_html__( 'Typography', 'charity-addon-for-elementor' ),
						'name' => 'amount_typography',
						'selector' => '{{WRAPPER}} .campaign-donation-stats',
					]
				);
				$this->add_control(
					'amount_color',
					[
						'label' => esc_html__( 'Color', 'charity-addon-for-elementor' ),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .campaign-donation-stats' => 'color: {{VALUE}} !important;',
						],
					]
				);
				$this->end_controls_section();// end: Section

			// Link
				$this->start_controls_section(
					'section_link_style',
					[
						'label' => esc_html__( 'Link', 'charity-addon-for-elementor' ),
						'tab' => Controls_Manager::TAB_STYLE,
						'condition' => [
							'button' => 'details',
						],
					]
				);
				$this->add_group_control(
					Group_Control_Typography::get_type(),
					[
						'label' => esc_html__( 'Typography', 'charity-addon-for-elementor' ),
						'name' => 'link_typography',
						'selector' => '{{WRAPPER}} a.button',
					]
				);
				$this->start_controls_tabs( 'link_style' );
					$this->start_controls_tab(
						'link_normal',
						[
							'label' => esc_html__( 'Normal', 'charity-addon-for-elementor' ),
						]
					);
					$this->add_control(
						'link_color',
						[
							'label' => esc_html__( 'Color', 'charity-addon-for-elementor' ),
							'type' => Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} a.button' => 'color: {{VALUE}} !important;',
							],
						]
					);
					$this->end_controls_tab();  // end:Normal tab
					$this->start_controls_tab(
						'link_hover',
						[
							'label' => esc_html__( 'Hover', 'charity-addon-for-elementor' ),
						]
					);
					$this->add_control(
						'link_hover_color',
						[
							'label' => esc_html__( 'Color', 'charity-addon-for-elementor' ),
							'type' => Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} a.button:hover' => 'color: {{VALUE}} !important;',
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
						'label' => esc_html__( 'Button', 'charity-addon-for-elementor' ),
						'tab' => Controls_Manager::TAB_STYLE,
						'condition' => [
							'button' => 'donate',
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
							'{{WRAPPER}} a.donate-button.button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						],
					]
				);
				$this->add_control(
					'btn_padding',
					[
						'label' => __( 'Padding', 'charity-addon-for-elementor' ),
						'type' => Controls_Manager::DIMENSIONS,
						'size_units' => [ 'px', '%', 'em' ],
						'selectors' => [
							'{{WRAPPER}} a.donate-button.button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
							'{{WRAPPER}} a.donate-button.button' => 'min-width:{{SIZE}}{{UNIT}};',
						],
					]
				);
				$this->add_group_control(
					Group_Control_Typography::get_type(),
					[
						'label' => esc_html__( 'Typography', 'charity-addon-for-elementor' ),
						'name' => 'btn_typography',
						'selector' => '{{WRAPPER}} a.donate-button.button',
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
							'label' => esc_html__( 'Color', 'charity-addon-for-elementor' ),
							'type' => Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} a.donate-button.button' => 'color: {{VALUE}};',
							],
						]
					);
					$this->add_control(
						'btn_bg_color',
						[
							'label' => esc_html__( 'Background Color', 'charity-addon-for-elementor' ),
							'type' => Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} a.donate-button.button' => 'background-color: {{VALUE}};',
							],
						]
					);
					$this->add_group_control(
						Group_Control_Border::get_type(),
						[
							'name' => 'btn_border',
							'label' => esc_html__( 'Border', 'charity-addon-for-elementor' ),
							'selector' => '{{WRAPPER}} a.donate-button.button',
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
							'label' => esc_html__( 'Color', 'charity-addon-for-elementor' ),
							'type' => Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} a.donate-button.button:hover' => 'color: {{VALUE}};',
							],
						]
					);
					$this->add_control(
						'btn_bg_hover_color',
						[
							'label' => esc_html__( 'Background Color', 'charity-addon-for-elementor' ),
							'type' => Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} a.donate-button.button:hover' => 'background-color: {{VALUE}};',
							],
						]
					);
					$this->add_group_control(
						Group_Control_Border::get_type(),
						[
							'name' => 'btn_hover_border',
							'label' => esc_html__( 'Border', 'charity-addon-for-elementor' ),
							'selector' => '{{WRAPPER}} a.donate-button.button:hover',
						]
					);
					$this->end_controls_tab();  // end:Hover tab
				$this->end_controls_tabs(); // end tabs
				$this->end_controls_section();// end: Section

		}

		/**
		 * Render Charitable Campaigns widget output on the frontend.
		 * Written in PHP and used to generate the final HTML.
		*/
		protected function render() {
			$settings = $this->get_settings_for_display();
			$id 							= !empty( $settings['id'] ) ? $settings['id'] : '';
			$orderby 					= !empty( $settings['orderby'] ) ? $settings['orderby'] : '';
			$order 						= !empty( $settings['order'] ) ? $settings['order'] : '';
			$number 					= !empty( $settings['number'] ) ? $settings['number'] : '';
			$category 				= !empty( $settings['category'] ) ? $settings['category'] : '';
			$tag 							= !empty( $settings['tag'] ) ? $settings['tag'] : '';
			$exclude 					= !empty( $settings['exclude'] ) ? $settings['exclude'] : '';
			$include_inactive = !empty( $settings['include_inactive'] ) ? $settings['include_inactive'] : '';
			$columns 					= !empty( $settings['columns'] ) ? $settings['columns'] : '';
			$button 					= !empty( $settings['button'] ) ? $settings['button'] : '';
			$responsive 			= !empty( $settings['responsive'] ) ? $settings['responsive'] : '';
			$masonry 					= !empty( $settings['masonry'] ) ? $settings['masonry'] : '';
			$map 							= !empty( $settings['map'] ) ? $settings['map'] : '';
			$width 						= !empty( $settings['width'] ) ? $settings['width'] : '';
			$height 					= !empty( $settings['height'] ) ? $settings['height'] : '';
			$zoom 						= !empty( $settings['zoom'] ) ? $settings['zoom'] : '';
			
			$include_inactive = $include_inactive ? '1' : '0';
			$masonry 					= $masonry ? '1' : '0';
			$map 							= $map ? '1' : '0';

			$id = $id ? ' id='.implode(',', $id) : '';
			$orderby = $orderby ? ' orderby='.$orderby : '';
			$order = $order ? ' order='.$order : '';
			$number = $number ? ' number='.$number : '';
			$category = $category ? ' category='.implode(',', $category) : '';
			$tag = $tag ? ' tag='.implode(',', $tag) : '';
			$exclude = $exclude ? ' exclude='.implode(',', $exclude) : '';
			$include_inactive = $include_inactive ? ' include_inactive='.$include_inactive : '';
			$columns = $columns ? ' columns='.$columns : '';
			$button = $button ? ' button='.$button : '';
			$responsive = $responsive ? ' responsive='.$responsive : '';
			$masonry = $masonry ? ' masonry='.$masonry : '';
			$map = $map ? ' map='.$map : '';
			$width = $width ? ' width='.$width : '';
			$height = $height ? ' height='.$height : '';
			$zoom = $zoom ? ' zoom='.$zoom : '';

	  	$output = '<div class="nacep-charitable-campaigns">'.do_shortcode( '[campaigns'. $id . $orderby . $order . $number . $category . $tag . $exclude . $include_inactive . $columns . $button . $responsive . $masonry . $map . $width . $height . $zoom .']' ).'</div>';

		  echo $output;

		}

	}
	Plugin::instance()->widgets_manager->register_widget_type( new Charity_Elementor_Addon_Charitable_Campaigns() );
}
