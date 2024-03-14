<?php
/*
 * Elementor Charity Addon for Elementor Give Form Grid
 * Author & Copyright: NicheAddon
*/
namespace Elementor;

if ( is_plugin_active( 'give/give.php' ) ) {

	if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

	class Charity_Elementor_Addon_GIVE_Form_Grid extends Widget_Base{

		/**
		 * Retrieve the widget name.
		*/
		public function get_name(){
			return 'nacharity_give_grid';
		}

		/**
		 * Retrieve the widget title.
		*/
		public function get_title(){
			return esc_html__( 'Form Grid', 'charity-addon-for-elementor' );
		}

		/**
		 * Retrieve the widget icon.
		*/
		public function get_icon() {
			return 'eicon-posts-grid';
		}

		/**
		 * Retrieve the grid of categories the widget belongs to.
		*/
		public function get_categories() {
			return ['nacharity-give-category'];
		}

		/**
		 * Register Charitys Addon for Elementor Give Form Grid widget controls.
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
				'section_donor',
				[
					'label' => esc_html__( 'Form Grid Options', 'charity-addon-for-elementor' ),
				]
			);
			$this->add_control(
				'forms_per_page',
				[
					'label' => esc_html__( 'Limit', 'charity-addon-for-elementor' ),
					'type' => Controls_Manager::NUMBER,
					'min' => 0,
					'max' => 100,
					'step' => 1,
					'default' => 10,
					'description' => esc_html__( 'Sets the number of donors per page.', 'charity-addon-for-elementor' ),
				]
			);
			$this->add_control(
				'ids',
				[
					'label' => __( 'Form IDs', 'charity-addon-for-elementor' ),
					'type' => Controls_Manager::SELECT2,
					'default' => [],
					'options' => $CharityID,
					'multiple' => true,
					'description' => esc_html__( 'Select a list of form IDs. If empty, all published forms are displayed.', 'charity-addon-for-elementor' ),
				]
			);
			$this->add_control(
				'exclude',
				[
					'label' => __( 'Excluded Form IDs', 'charity-addon-for-elementor' ),
					'type' => Controls_Manager::SELECT2,
					'default' => [],
					'options' => $CharityID,
					'multiple' => true,
					'description' => esc_html__( 'Select a list of form IDs to exclude those from the grid.', 'charity-addon-for-elementor' ),
				]
			);
			$this->add_control(
				'orderby',
				[
					'label' => __( 'Order By', 'charity-addon-for-elementor' ),
					'type' => Controls_Manager::SELECT,
					'options' => [
						'name'             => esc_html__( 'Form Name', 'give' ),
						'amount_donated'   => esc_html__( 'Amount Donated', 'give' ),
						'number_donations' => esc_html__( 'Number of Donations', 'give' ),
						'post__in'         => esc_html__( 'Provided Form IDs', 'give' ),
						'closest_to_goal'  => esc_html__( 'Closest To Goal', 'give' ),
						'title'  					 => esc_html__( 'Title', 'give' ),
						'menu_order'  		 => esc_html__( 'Menu Order', 'give' ),
						'none'  					 => esc_html__( 'None', 'give' ),
						'ID'  						 => esc_html__( 'ID', 'give' ),
						'author'  				 => esc_html__( 'Author', 'give' ),
						'name'  					 => esc_html__( 'Name', 'give' ),
						'modified'  			 => esc_html__( 'Modified', 'give' ),
						'rand'  					 => esc_html__( 'Rand', 'give' ),
					],
					'default' => 'donation_amount',
					'description' => esc_html__( 'Different parameters to set the order in which donors appear.', 'charity-addon-for-elementor' ),
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
					'default' => 'ASC',
					'description' => esc_html__( 'Sets the order in which donors appear.', 'charity-addon-for-elementor' ),
				]
			);
			$this->add_control(
				'columns',
				[
					'label' => __( 'Columns', 'charity-addon-for-elementor' ),
					'type' => Controls_Manager::SELECT,
					'options' => [
						'best-fit' => esc_html__( 'Best Fit', 'charity-addon-for-elementor' ),
						'1' => esc_html__( '1', 'charity-addon-for-elementor' ),
						'2' => esc_html__( '2', 'charity-addon-for-elementor' ),
						'3' => esc_html__( '3', 'charity-addon-for-elementor' ),
						'4' => esc_html__( '4', 'charity-addon-for-elementor' ),
					],
					'default' => 'best-fit',
					'description' => esc_html__( 'Sets the number of donors per row.', 'charity-addon-for-elementor' ),
				]
			);
			$this->add_control(
				'show_goal',
				[
					'label' => esc_html__( 'Show Goal?', 'charity-addon-for-elementor' ),
					'type' => Controls_Manager::SWITCHER,
					'label_on' => esc_html__( 'Hide', 'charity-addon-for-elementor' ),
					'label_off' => esc_html__( 'Show', 'charity-addon-for-elementor' ),
					'return_value' => 'true',
					'default' => 'true',
					'description' => esc_html__( 'Do you want to display the goal\'s progress bar?', 'charity-addon-for-elementor' ),
				]
			);
			$this->add_control(
				'show_excerpt',
				[
					'label' => esc_html__( 'Show Excerpt?', 'charity-addon-for-elementor' ),
					'type' => Controls_Manager::SWITCHER,
					'label_on' => esc_html__( 'Hide', 'charity-addon-for-elementor' ),
					'label_off' => esc_html__( 'Show', 'charity-addon-for-elementor' ),
					'return_value' => 'true',
					'default' => 'true',
					'description' => esc_html__( 'Do you want to display the excerpt?', 'charity-addon-for-elementor' ),
				]
			);
			$this->add_control(
				'show_featured_image',
				[
					'label' => esc_html__( 'Show Featured Image?', 'charity-addon-for-elementor' ),
					'type' => Controls_Manager::SWITCHER,
					'label_on' => esc_html__( 'Hide', 'charity-addon-for-elementor' ),
					'label_off' => esc_html__( 'Show', 'charity-addon-for-elementor' ),
					'return_value' => 'true',
					'default' => 'true',
					'description' => esc_html__( 'Do you want to display the featured image?', 'charity-addon-for-elementor' ),
				]
			);
			$this->add_control(
				'display_style',
				[
					'label' => __( 'Display Style', 'charity-addon-for-elementor' ),
					'type' => Controls_Manager::SELECT,
					'options' => [
						'redirect' => esc_html__( 'Redirect', 'charity-addon-for-elementor' ),
						'modal_reveal' => esc_html__( 'Modal', 'charity-addon-for-elementor' ),
					],
					'default' => 'redirect',
					'description' => esc_html__( 'Show form as modal window or redirect to a new page?', 'charity-addon-for-elementor' ),
				]
			);
			$this->add_control(
				'additional_attr',
				[
					'label' => __( 'Additional Attributes', 'charity-addon-for-elementor' ),
					'type' => Controls_Manager::HEADING,
					'frontend_available' => true,
					'separator' => 'before',
				]
			);
			$this->add_control(
				'categories',
				[
					'label' => __( 'Certain Categories?', 'charity-addon-for-elementor' ),
					'type' => Controls_Manager::SELECT2,
					'default' => [],
					'options' => NACEP_Controls_Helper_Output::get_terms_names( 'give_forms_category'),
					'multiple' => true,
				]
			);
			$this->add_control(
				'tags',
				[
					'label' => __( 'Certain Tags?', 'charity-addon-for-elementor' ),
					'type' => Controls_Manager::SELECT2,
					'default' => [],
					'options' => NACEP_Controls_Helper_Output::get_terms_names( 'give_forms_tag'),
					'multiple' => true,
				]
			);
			$this->add_control(
				'paged',
				[
					'label' => esc_html__( 'Show Pagination?', 'charity-addon-for-elementor' ),
					'type' => Controls_Manager::SWITCHER,
					'label_on' => esc_html__( 'Hide', 'charity-addon-for-elementor' ),
					'label_off' => esc_html__( 'Show', 'charity-addon-for-elementor' ),
					'return_value' => 'true',
					'default' => 'true',
					'description' => esc_html__( 'Do you want to display the pagination?', 'charity-addon-for-elementor' ),
				]
			);
			$this->add_control(
				'show_title',
				[
					'label' => esc_html__( 'Show Title?', 'charity-addon-for-elementor' ),
					'type' => Controls_Manager::SWITCHER,
					'label_on' => esc_html__( 'Hide', 'charity-addon-for-elementor' ),
					'label_off' => esc_html__( 'Show', 'charity-addon-for-elementor' ),
					'return_value' => 'true',
					'default' => 'true',
					'description' => esc_html__( 'Do you want to display the title?', 'charity-addon-for-elementor' ),
				]
			);
			$this->add_control(
				'image_size',
				[
					'label' => __( 'Image Size', 'charity-addon-for-elementor' ),
					'type' => Controls_Manager::SELECT,
					'options' => [
						'thumbnail'    => esc_html__( 'Thumbnail (150 x 150)', 'give' ),
						'medium'   		 => esc_html__( 'Medium (300 x 300)', 'give' ),
						'medium_large' => esc_html__( 'Medium Large (768 x 768)', 'give' ),
						'large'        => esc_html__( 'Large resolution (1024 x 1024)', 'give' ),
						'full'         => esc_html__( 'Original image', 'give' ),
					],
					'default' => 'full',
					'description' => esc_html__( 'Select image size.', 'charity-addon-for-elementor' ),
				]
			);
			$this->add_control(
				'image_height',
				[
					'label' => esc_html__( 'Image Height', 'charity-addon-for-elementor' ),
					'type' => Controls_Manager::NUMBER,
					'min' => 0,
					'max' => 1000,
					'step' => 1,
					'description' => esc_html__( 'Sets the image height.', 'charity-addon-for-elementor' ),
				]
			);
			$this->add_control(
				'excerpt_length',
				[
					'label' => esc_html__( 'Excerpt Length', 'charity-addon-for-elementor' ),
					'type' => Controls_Manager::NUMBER,
					'min' => 1,
					'max' => 1000,
					'step' => 1,
					'default' => 16,
					'description' => esc_html__( 'Sets the number of characters to display.', 'charity-addon-for-elementor' ),
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
							'{{WRAPPER}} .give-wrap .give-card__body' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						],
					]
				);
				$this->start_controls_tabs( 'secn_style' );
					$this->start_controls_tab(
						'secn_normal',
						[
							'label' => esc_html__( 'Normal', 'charity-addon-for-elementor' ),
						]
					);
					$this->add_control(
						'secn_bg_color',
						[
							'label' => esc_html__( 'Background Color', 'charity-addon-for-elementor' ),
							'type' => Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .give-wrap .give-card__body' => 'background-color: {{VALUE}};',
							],
						]
					);
					$this->add_group_control(
						Group_Control_Border::get_type(),
						[
							'name' => 'secn_border',
							'label' => esc_html__( 'Border', 'charity-addon-for-elementor' ),
							'selector' => '{{WRAPPER}} .give-wrap .give-card__body',
						]
					);
					$this->add_group_control(
						Group_Control_Box_Shadow::get_type(),
						[
							'name' => 'secn_box_shadow',
							'label' => esc_html__( 'Section Box Shadow', 'charity-addon-for-elementor' ),
							'selector' => '{{WRAPPER}} .give-wrap .give-card__body',
						]
					);
					$this->end_controls_tab();  // end:Normal tab

					$this->start_controls_tab(
						'secn_hover',
						[
							'label' => esc_html__( 'Hover', 'charity-addon-for-elementor' ),
						]
					);
					$this->add_control(
						'secn_nrml_color',
						[
							'label' => esc_html__( 'Color', 'charity-addon-for-elementor' ),
							'type' => Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .give-wrap .give-card:hover h3,
								 {{WRAPPER}} .give-wrap .give-card:hover p ' => 'color: {{VALUE}};',
							],
						]
					);
					$this->add_control(
						'secn_bg_hover_color',
						[
							'label' => esc_html__( 'Background Color', 'charity-addon-for-elementor' ),
							'type' => Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .give-wrap .give-card:hover' => 'background-color: {{VALUE}};',
							],
						]
					);
					$this->add_group_control(
						Group_Control_Border::get_type(),
						[
							'name' => 'secn_hov_border',
							'label' => esc_html__( 'Border', 'charity-addon-for-elementor' ),
							'selector' => '{{WRAPPER}} .give-wrap .give-card:hover',
						]
					);
					$this->add_group_control(
						Group_Control_Box_Shadow::get_type(),
						[
							'name' => 'secn_hov_box_shadow',
							'label' => esc_html__( 'Section Box Shadow', 'charity-addon-for-elementor' ),
							'selector' => '{{WRAPPER}} .give-wrap .give-card:hover',
						]
					);
					$this->end_controls_tab();  // end:Hover tab
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
				$this->add_control(
					'title_padding',
					[
						'label' => __( 'Padding', 'charity-addon-for-elementor' ),
						'type' => Controls_Manager::DIMENSIONS,
						'size_units' => [ 'px', '%', 'em' ],
						'selectors' => [
							'{{WRAPPER}} .give-wrap .give-card__title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
						],
					]
				);
				$this->add_group_control(
					Group_Control_Typography::get_type(),
					[
						'label' => esc_html__( 'Typography', 'charity-addon-for-elementor' ),
						'name' => 'title_typography',
						'selector' => '{{WRAPPER}} .give-wrap .give-card__title',
					]
				);
				$this->add_control(
					'title_color',
					[
						'label' => esc_html__( 'Color', 'charity-addon-for-elementor' ),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .give-wrap .give-card__title' => 'color: {{VALUE}};',
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
				$this->add_control(
					'content_padding',
					[
						'label' => __( 'Padding', 'charity-addon-for-elementor' ),
						'type' => Controls_Manager::DIMENSIONS,
						'size_units' => [ 'px', '%', 'em' ],
						'selectors' => [
							'{{WRAPPER}} .give-wrap .give-card__text' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
						],
					]
				);
				$this->add_group_control(
					Group_Control_Typography::get_type(),
					[
						'label' => esc_html__( 'Typography', 'charity-addon-for-elementor' ),
						'name' => 'content_typography',
						'selector' => '{{WRAPPER}} .give-wrap .give-card__text',
					]
				);
				$this->add_control(
					'content_color',
					[
						'label' => esc_html__( 'Color', 'charity-addon-for-elementor' ),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .give-wrap .give-card__text' => 'color: {{VALUE}} !important;',
						],
					]
				);
				$this->end_controls_section();// end: Section

			// Income
				$this->start_controls_section(
					'section_income_style',
					[
						'label' => esc_html__( 'Income Amount', 'charity-addon-for-elementor' ),
						'tab' => Controls_Manager::TAB_STYLE,
					]
				);
				$this->add_control(
					'income_padding',
					[
						'label' => __( 'Padding', 'charity-addon-for-elementor' ),
						'type' => Controls_Manager::DIMENSIONS,
						'size_units' => [ 'px', '%', 'em' ],
						'selectors' => [
							'{{WRAPPER}} span.income' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
						],
					]
				);
				$this->add_group_control(
					Group_Control_Typography::get_type(),
					[
						'label' => esc_html__( 'Typography', 'charity-addon-for-elementor' ),
						'name' => 'income_typography',
						'selector' => '{{WRAPPER}} span.income',
					]
				);
				$this->add_control(
					'income_color',
					[
						'label' => esc_html__( 'Color', 'charity-addon-for-elementor' ),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} span.income' => 'color: {{VALUE}};',
						],
					]
				);
				$this->end_controls_section();// end: Section

			// Progress Bar Title
				$this->start_controls_section(
					'section_bar_title_style',
					[
						'label' => esc_html__( 'Progress Bar Title', 'charity-addon-for-elementor' ),
						'tab' => Controls_Manager::TAB_STYLE,
					]
				);
				$this->add_control(
					'bar_title_padding',
					[
						'label' => __( 'Padding', 'charity-addon-for-elementor' ),
						'type' => Controls_Manager::DIMENSIONS,
						'size_units' => [ 'px', '%', 'em' ],
						'selectors' => [
							'{{WRAPPER}} .raised' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
						],
					]
				);
				$this->add_group_control(
					Group_Control_Typography::get_type(),
					[
						'label' => esc_html__( 'Typography', 'charity-addon-for-elementor' ),
						'name' => 'bar_title_typography',
						'selector' => '{{WRAPPER}} .raised',
					]
				);
				$this->add_control(
					'bar_title_color',
					[
						'label' => esc_html__( 'Color', 'charity-addon-for-elementor' ),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .raised' => 'color: {{VALUE}};',
						],
					]
				);
				$this->end_controls_section();// end: Section

			// Progress Bar
				$this->start_controls_section(
					'progress_style',
					[
						'label' => esc_html__( 'Progress Bar', 'charity-addon-for-elementor' ),
						'tab' => Controls_Manager::TAB_STYLE,
					]
				);
				$this->start_controls_tabs( 'prog_style' );
					$this->start_controls_tab(
						'progress_normal',
						[
							'label' => esc_html__( 'Normal', 'charity-addon-for-elementor' ),
						]
					);
					$this->add_control(
						'progress_bg_color',
						[
							'label' => esc_html__( 'Background Color', 'charity-addon-for-elementor' ),
							'type' => Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .give-progress-bar' => 'background-color: {{VALUE}};',
							],
						]
					);
					$this->end_controls_tab();  // end:Normal tab

					$this->start_controls_tab(
						'progress_hover',
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
							'selector' => '{{WRAPPER}} .give-progress-bar>span',
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
							'paged' => 'true',
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
							'{{WRAPPER}} .give-page-numbers' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
							'{{WRAPPER}} .give-page-numbers span, {{WRAPPER}} .give-page-numbers a ' => 'width:{{SIZE}}{{UNIT}};height:{{SIZE}}{{UNIT}};',
						],
					]
				);
				$this->add_group_control(
					Group_Control_Typography::get_type(),
					[
						'name' => 'pagi_typography',
						'selector' => '{{WRAPPER}} .give-page-numbers a, {{WRAPPER}} .give-page-numbers span',
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
								'{{WRAPPER}} .give-page-numbers a' => 'color: {{VALUE}};',
							],
						]
					);
					$this->add_control(
						'pagi_bg_color',
						[
							'label' => esc_html__( 'Background Color', 'charity-addon-for-elementor' ),
							'type' => Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .give-page-numbers a' => 'background-color: {{VALUE}};',
							],
						]
					);
					$this->add_group_control(
						Group_Control_Border::get_type(),
						[
							'name' => 'pagi_border',
							'label' => esc_html__( 'Border', 'charity-addon-for-elementor' ),
							'selector' => '{{WRAPPER}} .give-page-numbers a',
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
								'{{WRAPPER}} .give-page-numbers a:hover' => 'color: {{VALUE}};',
							],
						]
					);
					$this->add_control(
						'pagi_bg_hover_color',
						[
							'label' => esc_html__( 'Background Color', 'charity-addon-for-elementor' ),
							'type' => Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .give-page-numbers a:hover' => 'background-color: {{VALUE}};',
							],
						]
					);
					$this->add_group_control(
						Group_Control_Border::get_type(),
						[
							'name' => 'pagi_hover_border',
							'label' => esc_html__( 'Border', 'charity-addon-for-elementor' ),
							'selector' => '{{WRAPPER}} .give-page-numbers a:hover',
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
								'{{WRAPPER}} .give-page-numbers span.current' => 'color: {{VALUE}};',
							],
						]
					);
					$this->add_control(
						'pagi_bg_active_color',
						[
							'label' => esc_html__( 'Background Color', 'charity-addon-for-elementor' ),
							'type' => Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .give-page-numbers span.current' => 'background-color: {{VALUE}};',
							],
						]
					);
					$this->add_group_control(
						Group_Control_Border::get_type(),
						[
							'name' => 'pagi_active_border',
							'label' => esc_html__( 'Border', 'charity-addon-for-elementor' ),
							'selector' => '{{WRAPPER}} .give-page-numbers span.current',
						]
					);
					$this->end_controls_tab();  // end:Active tab
				$this->end_controls_tabs(); // end tabs
				$this->end_controls_section();// end: Section

		}

		/**
		 * Render Give Form Grid widget output on the frontend.
		 * Written in PHP and used to generate the final HTML.
		*/
		protected function render() {
			$settings = $this->get_settings_for_display();

			$forms_per_page 	= !empty( $settings['forms_per_page'] ) ? $settings['forms_per_page'] : '';
			$ids 				= !empty( $settings['ids'] ) ? $settings['ids'] : '';
			$exclude 				= !empty( $settings['exclude'] ) ? $settings['exclude'] : '';
			$orderby 	= !empty( $settings['orderby'] ) ? $settings['orderby'] : '';
			$order 		= !empty( $settings['order'] ) ? $settings['order'] : '';
			$columns 	= !empty( $settings['columns'] ) ? $settings['columns'] : '';
			$show_goal 	= !empty( $settings['show_goal'] ) ? $settings['show_goal'] : '';
			$show_excerpt 	= !empty( $settings['show_excerpt'] ) ? $settings['show_excerpt'] : '';
			$show_featured_image 		= !empty( $settings['show_featured_image'] ) ? $settings['show_featured_image'] : '';
			$display_style = !empty( $settings['display_style'] ) ? $settings['display_style'] : '';
			$categories 				= !empty( $settings['categories'] ) ? $settings['categories'] : '';
			$tags 				= !empty( $settings['tags'] ) ? $settings['tags'] : '';
			$paged 				= !empty( $settings['paged'] ) ? $settings['paged'] : '';
			$show_title 				= !empty( $settings['show_title'] ) ? $settings['show_title'] : '';
			$image_size 				= !empty( $settings['image_size'] ) ? $settings['image_size'] : '';
			$image_height 				= !empty( $settings['image_height'] ) ? $settings['image_height'] : '';
			$excerpt_length 				= !empty( $settings['excerpt_length'] ) ? $settings['excerpt_length'] : '';

			$show_goal = $show_goal ? 'true' : 'false';
			$show_excerpt = $show_excerpt ? 'true' : 'false';
			$show_featured_image = $show_featured_image ? 'true' : 'false';
			$paged = $paged ? 'true' : 'false';
			$show_title = $show_title ? 'true' : 'false';

			$forms_per_page  = $forms_per_page ? ' forms_per_page="'.$forms_per_page.'"' : '';
			$ids 					   = $ids ? ' ids="'.implode(',', $ids).'"' : '';
			$exclude 				 = $exclude ? ' exclude="'.implode(',', $exclude).'"' : '';
			$orderby 				 = $orderby ? ' orderby="'.$orderby.'"' : '';
			$order 					 = $order ? ' order="'.$order.'"' : '';
			$columns 				 = $columns ? ' columns="'.$columns.'"' : '';
			$show_goal 	 		 = $show_goal ? ' show_goal="'.$show_goal.'"' : '';
			$show_excerpt 	 = $show_excerpt ? ' show_excerpt="'.$show_excerpt.'"' : '';
			$show_featured_image 		 = $show_featured_image ? ' show_featured_image="'.$show_featured_image.'"' : '';
			$display_style   = $display_style ? ' display_style="'.$display_style.'"' : '';
			$categories 		 = $categories ? ' cats="'.implode(',', $categories).'"' : '';
			$tags 					 = $tags ? ' tags="'.implode(',', $tags).'"' : '';
			$paged 	 		 = $paged ? ' paged="'.$paged.'"' : '';
			$show_title 	 		 = $show_title ? ' show_title="'.$show_title.'"' : '';
			$image_size 	 		 = $image_size ? ' image_size="'.$image_size.'"' : '';
			$image_height 	 		 = $image_height ? ' image_height="'.$image_height.'px"' : '';
			$excerpt_length 	 		 = $excerpt_length ? ' excerpt_length="'.$excerpt_length.'"' : '';

	  	$output = '<div class="nacep-give-grid">'.do_shortcode( '[give_form_grid '. $forms_per_page . $ids . $exclude . $categories . $tags . $orderby . $order . $columns . $show_goal . $show_excerpt . $show_featured_image . $display_style . $paged . $show_title . $image_size . $image_height . $excerpt_length .']' ).'</div>';

		  echo $output;

		}

	}
	Plugin::instance()->widgets_manager->register_widget_type( new Charity_Elementor_Addon_GIVE_Form_Grid() );
}
