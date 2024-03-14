<?php
/*
 * Elementor Charity Addon for Elementor Give Donor Wall
 * Author & Copyright: NicheAddon
*/
namespace Elementor;

if ( is_plugin_active( 'give/give.php' ) ) {

	if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

	class Charity_Elementor_Addon_GIVE_Donor_Wall extends Widget_Base{

		/**
		 * Retrieve the widget name.
		*/
		public function get_name(){
			return 'nacharity_give_wall';
		}

		/**
		 * Retrieve the widget title.
		*/
		public function get_title(){
			return esc_html__( 'Donor Wall', 'charity-addon-for-elementor' );
		}

		/**
		 * Retrieve the widget icon.
		*/
		public function get_icon() {
			return 'eicon-gallery-grid';
		}

		/**
		 * Retrieve the wall of categories the widget belongs to.
		*/
		public function get_categories() {
			return ['nacharity-give-category'];
		}

		/**
		 * Register Charitys Addon for Elementor Give Donor Wall widget controls.
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
					'label' => esc_html__( 'Donor Wall Options', 'charity-addon-for-elementor' ),
				]
			);
			$this->add_control(
				'donor_style',
				[
					'label' => esc_html__( 'Donor Style', 'charity-addon-for-elementor' ),
					'type' => Controls_Manager::SELECT,
					'options' => [
						'one'          => esc_html__('Style One', 'charity-addon-for-elementor'),
						'two'          => esc_html__('Style Two', 'charity-addon-for-elementor'),
					],
					'default' => 'one',
				]
			);
			$this->add_control(
				'donors_per_page',
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
				'form_id',
				[
					'label' => __( 'Form', 'charity-addon-for-elementor' ),
					'type' => Controls_Manager::SELECT2,
					'default' => [],
					'options' => $CharityID,
					'multiple' => true,
					'description' => esc_html__( 'Filters donors by form. By default, all donations except for anonymous donations are displayed.', 'charity-addon-for-elementor' ),
				]
			);
			$this->add_control(
				'orderby',
				[
					'label' => __( 'Order By', 'charity-addon-for-elementor' ),
					'type' => Controls_Manager::SELECT,
					'options' => [
						'donation_amount' => esc_html__( 'Donation Amount', 'charity-addon-for-elementor' ),
						'post_date' => esc_html__( 'Date Created', 'charity-addon-for-elementor' ),
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
				'anonymous',
				[
					'label' => esc_html__( 'Show Anonymous?', 'charity-addon-for-elementor' ),
					'type' => Controls_Manager::SWITCHER,
					'label_on' => esc_html__( 'Hide', 'charity-addon-for-elementor' ),
					'label_off' => esc_html__( 'Show', 'charity-addon-for-elementor' ),
					'return_value' => 'true',
					'description' => esc_html__( 'Determines whether anonymous donations are included.', 'charity-addon-for-elementor' ),
				]
			);
			$this->add_control(
				'show_avatar',
				[
					'label' => esc_html__( 'Show Avatar?', 'charity-addon-for-elementor' ),
					'type' => Controls_Manager::SWITCHER,
					'label_on' => esc_html__( 'Hide', 'charity-addon-for-elementor' ),
					'label_off' => esc_html__( 'Show', 'charity-addon-for-elementor' ),
					'return_value' => 'true',
					'default' => 'true',
					'description' => esc_html__( 'Determines whether the avatar is visible.', 'charity-addon-for-elementor' ),
				]
			);
			$this->add_control(
				'show_name',
				[
					'label' => esc_html__( 'Show Name?', 'charity-addon-for-elementor' ),
					'type' => Controls_Manager::SWITCHER,
					'label_on' => esc_html__( 'Hide', 'charity-addon-for-elementor' ),
					'label_off' => esc_html__( 'Show', 'charity-addon-for-elementor' ),
					'return_value' => 'true',
					'default' => 'true',
					'description' => esc_html__( 'Determines whether the name is visible.', 'charity-addon-for-elementor' ),
				]
			);
			$this->add_control(
				'show_total',
				[
					'label' => esc_html__( 'Show Total?', 'charity-addon-for-elementor' ),
					'type' => Controls_Manager::SWITCHER,
					'label_on' => esc_html__( 'Hide', 'charity-addon-for-elementor' ),
					'label_off' => esc_html__( 'Show', 'charity-addon-for-elementor' ),
					'return_value' => 'true',
					'default' => 'true',
					'description' => esc_html__( 'Determines whether the donation total is visible.', 'charity-addon-for-elementor' ),
				]
			);
			$this->add_control(
				'show_time',
				[
					'label' => esc_html__( 'Show Time?', 'charity-addon-for-elementor' ),
					'type' => Controls_Manager::SWITCHER,
					'label_on' => esc_html__( 'Hide', 'charity-addon-for-elementor' ),
					'label_off' => esc_html__( 'Show', 'charity-addon-for-elementor' ),
					'return_value' => 'true',
					'default' => 'true',
					'description' => esc_html__( 'Determines whether the date of the donation is visible.', 'charity-addon-for-elementor' ),
				]
			);
			$this->add_control(
				'only_comments',
				[
					'label' => esc_html__( 'Only Comments?', 'charity-addon-for-elementor' ),
					'type' => Controls_Manager::SWITCHER,
					'label_on' => esc_html__( 'Hide', 'charity-addon-for-elementor' ),
					'label_off' => esc_html__( 'Show', 'charity-addon-for-elementor' ),
					'return_value' => 'true',
					'description' => esc_html__( 'Determines whether to display all donors or only donors with comments.', 'charity-addon-for-elementor' ),
				]
			);
			$this->add_control(
				'show_comments',
				[
					'label' => esc_html__( 'Show Comments?', 'charity-addon-for-elementor' ),
					'type' => Controls_Manager::SWITCHER,
					'label_on' => esc_html__( 'Hide', 'charity-addon-for-elementor' ),
					'label_off' => esc_html__( 'Show', 'charity-addon-for-elementor' ),
					'return_value' => 'true',
					'default' => 'true',
					'description' => esc_html__( 'Determines whether the comment is visible.', 'charity-addon-for-elementor' ),
				]
			);
			$this->add_control(
				'comment_length',
				[
					'label' => esc_html__( 'Comment Length', 'charity-addon-for-elementor' ),
					'type' => Controls_Manager::NUMBER,
					'min' => 1,
					'max' => 1000,
					'step' => 1,
					'default' => 140,
					'description' => esc_html__( 'Sets the number of characters to display before the comment is truncated.', 'charity-addon-for-elementor' ),
					'condition' => [
						'show_comments' => 'true',
					],
				]
			);
			$this->add_control(
				'readmore_text',
				[
					'label' => esc_html__( 'Read More Text', 'charity-addon-for-elementor' ),
					'type' => Controls_Manager::TEXT,
					'label_block' => true,
					'description' => esc_html__( 'Defines the text that appears if a comment is truncated.', 'charity-addon-for-elementor' ),
				]
			);
			$this->add_control(
				'loadmore_text',
				[
					'label' => esc_html__( 'Load More Text', 'charity-addon-for-elementor' ),
					'type' => Controls_Manager::TEXT,
					'label_block' => true,
					'description' => esc_html__( 'Defines the button text used for pagination.', 'charity-addon-for-elementor' ),
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
						'{{WRAPPER}} .give-wrap .give-card' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);
			$this->add_responsive_control(
				'section_padding',
				[
					'label' => __( 'Section Padding', 'charity-addon-for-elementor' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px' ],
					'selectors' => [
						'{{WRAPPER}} .give-wrap .give-card' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
							'{{WRAPPER}} .give-wrap .give-card' => 'background-color: {{VALUE}};',
						],
					]
				);
				$this->add_group_control(
					Group_Control_Border::get_type(),
					[
						'name' => 'secn_border',
						'label' => esc_html__( 'Border', 'charity-addon-for-elementor' ),
						'selector' => '{{WRAPPER}} .give-wrap .give-card',
					]
				);
				$this->add_group_control(
					Group_Control_Box_Shadow::get_type(),
					[
						'name' => 'secn_box_shadow',
						'label' => esc_html__( 'Section Box Shadow', 'charity-addon-for-elementor' ),
						'selector' => '{{WRAPPER}} .give-wrap .give-card',
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
							 {{WRAPPER}} .give-wrap .give-card:hover span ' => 'color: {{VALUE}};',
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

			// Image
			$this->start_controls_section(
				'section_image_style',
				[
					'label' => esc_html__( 'Image', 'charity-addon-for-elementor' ),
					'tab' => Controls_Manager::TAB_STYLE,
				]
			);
			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'label' => esc_html__( 'Typography', 'charity-addon-for-elementor' ),
					'name' => 'image_typography',
					'selector' => '{{WRAPPER}} .give-donor__image',
				]
			);
			$this->add_control(
				'image_color',
				[
					'label' => esc_html__( 'Color', 'charity-addon-for-elementor' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .give-donor__image' => 'color: {{VALUE}};',
					],
				]
			);
			$this->add_control(
				'image_bg_color',
				[
					'label' => esc_html__( 'Background Color', 'charity-addon-for-elementor' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .give-donor__image' => 'background-color: {{VALUE}};',
					],
				]
			);
			$this->add_group_control(
				Group_Control_Border::get_type(),
				[
					'name' => 'image_border',
					'label' => esc_html__( 'Border', 'charity-addon-for-elementor' ),
					'selector' => '{{WRAPPER}} .give-donor__image',
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
			$this->add_control(
				'title_padding',
				[
					'label' => __( 'Padding', 'charity-addon-for-elementor' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%', 'em' ],
					'selectors' => [
						'{{WRAPPER}} .give-donor__name' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
					],
				]
			);
			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'label' => esc_html__( 'Typography', 'charity-addon-for-elementor' ),
					'name' => 'title_typography',
					'selector' => '{{WRAPPER}} .give-donor__name',
				]
			);
			$this->add_control(
				'title_color',
				[
					'label' => esc_html__( 'Color', 'charity-addon-for-elementor' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .give-donor__name' => 'color: {{VALUE}};',
					],
				]
			);
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
						'{{WRAPPER}} span.give-donor__total' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
					],
				]
			);
			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'label' => esc_html__( 'Typography', 'charity-addon-for-elementor' ),
					'name' => 'amount_typography',
					'selector' => '{{WRAPPER}} span.give-donor__total',
				]
			);
			$this->add_control(
				'amount_color',
				[
					'label' => esc_html__( 'Color', 'charity-addon-for-elementor' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} span.give-donor__total' => 'color: {{VALUE}} !important;',
					],
				]
			);
			$this->end_controls_section();// end: Section

			// Date
			$this->start_controls_section(
				'section_date_style',
				[
					'label' => esc_html__( 'Date', 'charity-addon-for-elementor' ),
					'tab' => Controls_Manager::TAB_STYLE,
				]
			);
			$this->add_control(
				'date_padding',
				[
					'label' => __( 'Padding', 'charity-addon-for-elementor' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%', 'em' ],
					'selectors' => [
						'{{WRAPPER}} span.give-donor__timestamp' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
					],
				]
			);
			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'label' => esc_html__( 'Typography', 'charity-addon-for-elementor' ),
					'name' => 'date_typography',
					'selector' => '{{WRAPPER}} span.give-donor__timestamp',
				]
			);
			$this->add_control(
				'date_color',
				[
					'label' => esc_html__( 'Color', 'charity-addon-for-elementor' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} span.give-donor__timestamp' => 'color: {{VALUE}} !important;',
					],
				]
			);
			$this->end_controls_section();// end: Section

			// Comment
			$this->start_controls_section(
				'section_comment_style',
				[
					'label' => esc_html__( 'Comment', 'charity-addon-for-elementor' ),
					'tab' => Controls_Manager::TAB_STYLE,
				]
			);
			$this->add_control(
				'comment_padding',
				[
					'label' => __( 'Padding', 'charity-addon-for-elementor' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%', 'em' ],
					'selectors' => [
						'{{WRAPPER}} p.give-donor__excerpt' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
					],
				]
			);
			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'label' => esc_html__( 'Typography', 'charity-addon-for-elementor' ),
					'name' => 'comment_typography',
					'selector' => '{{WRAPPER}} p.give-donor__excerpt',
				]
			);
			$this->add_control(
				'comment_color',
				[
					'label' => esc_html__( 'Color', 'charity-addon-for-elementor' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} p.give-donor__excerpt' => 'color: {{VALUE}} !important;',
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
				]
			);
			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'label' => esc_html__( 'Typography', 'charity-addon-for-elementor' ),
					'name' => 'link_typography',
					'selector' => '{{WRAPPER}} p.give-donor__excerpt a',
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
							'{{WRAPPER}} p.give-donor__excerpt a' => 'color: {{VALUE}} !important;',
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
							'{{WRAPPER}} p.give-donor__excerpt a:hover' => 'color: {{VALUE}} !important;',
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
				]
			);
			$this->add_control(
				'btn_border_radius',
				[
					'label' => __( 'Border Radius', 'charity-addon-for-elementor' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%', 'em' ],
					'selectors' => [
						'{{WRAPPER}} .give-donor__load_more' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
						'{{WRAPPER}} .give-donor__load_more' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
						'{{WRAPPER}} .give-donor__load_more' => 'min-width:{{SIZE}}{{UNIT}};',
					],
				]
			);
			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'label' => esc_html__( 'Typography', 'charity-addon-for-elementor' ),
					'name' => 'btn_typography',
					'selector' => '{{WRAPPER}} .give-donor__load_more',
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
							'{{WRAPPER}} .give-donor__load_more' => 'color: {{VALUE}};',
						],
					]
				);
				$this->add_control(
					'btn_bg_color',
					[
						'label' => esc_html__( 'Background Color', 'charity-addon-for-elementor' ),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .give-donor__load_more' => 'background-color: {{VALUE}};',
						],
					]
				);
				$this->add_group_control(
					Group_Control_Border::get_type(),
					[
						'name' => 'btn_border',
						'label' => esc_html__( 'Border', 'charity-addon-for-elementor' ),
						'selector' => '{{WRAPPER}} .give-donor__load_more',
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
							'{{WRAPPER}} .give-donor__load_more:hover' => 'color: {{VALUE}};',
						],
					]
				);
				$this->add_control(
					'btn_bg_hover_color',
					[
						'label' => esc_html__( 'Background Color', 'charity-addon-for-elementor' ),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .give-donor__load_more:hover' => 'background-color: {{VALUE}};',
						],
					]
				);
				$this->add_group_control(
					Group_Control_Border::get_type(),
					[
						'name' => 'btn_hover_border',
						'label' => esc_html__( 'Border', 'charity-addon-for-elementor' ),
						'selector' => '{{WRAPPER}} .give-donor__load_more:hover',
					]
				);
				$this->end_controls_tab();  // end:Hover tab
			$this->end_controls_tabs(); // end tabs
			$this->end_controls_section();// end: Section

		}

		/**
		 * Render Give Donor Wall widget output on the frontend.
		 * Written in PHP and used to generate the final HTML.
		*/
		protected function render() {
			$settings = $this->get_settings_for_display();
			$donor_style 				= !empty( $settings['donor_style'] ) ? $settings['donor_style'] : '';
			$donors_per_page 	= !empty( $settings['donors_per_page'] ) ? $settings['donors_per_page'] : '';
			$form_id 				= !empty( $settings['form_id'] ) ? $settings['form_id'] : '';
			$orderby 	= !empty( $settings['orderby'] ) ? $settings['orderby'] : '';
			$order 		= !empty( $settings['order'] ) ? $settings['order'] : '';
			$columns 	= !empty( $settings['columns'] ) ? $settings['columns'] : '';
			$anonymous 	= !empty( $settings['anonymous'] ) ? $settings['anonymous'] : '';
			$show_avatar 	= !empty( $settings['show_avatar'] ) ? $settings['show_avatar'] : '';
			$show_name 		= !empty( $settings['show_name'] ) ? $settings['show_name'] : '';
			$show_total = !empty( $settings['show_total'] ) ? $settings['show_total'] : '';
			$show_time  = !empty( $settings['show_time'] ) ? $settings['show_time'] : '';
			$only_comments  = !empty( $settings['only_comments'] ) ? $settings['only_comments'] : '';
			$show_comments  = !empty( $settings['show_comments'] ) ? $settings['show_comments'] : '';
			$comment_length = !empty( $settings['comment_length'] ) ? $settings['comment_length'] : '';
			$readmore_text = !empty( $settings['readmore_text'] ) ? $settings['readmore_text'] : '';
			$loadmore_text = !empty( $settings['loadmore_text'] ) ? $settings['loadmore_text'] : '';

			$anonymous = $anonymous ? 'true' : 'false';
			$show_avatar = $show_avatar ? 'true' : 'false';
			$show_name = $show_name ? 'true' : 'false';
			$show_total = $show_total ? 'true' : 'false';
			$show_time = $show_time ? 'true' : 'false';
			$only_comments = $only_comments ? 'true' : 'false';
			$show_comments = $show_comments ? 'true' : 'false';

			$donors_per_page = $donors_per_page ? ' donors_per_page="'.$donors_per_page.'"' : '';
			$form_id 					 = $form_id ? ' form_id="'.implode(',', $form_id).'"' : '';
			$orderby 				 = $orderby ? ' orderby="'.$orderby.'"' : '';
			$order 					 = $order ? ' order="'.$order.'"' : '';
			$columns 				 = $columns ? ' columns="'.$columns.'"' : '';
			$anonymous 	 = $anonymous ? ' anonymous="'.$anonymous.'"' : '';
			$show_avatar 	 = $show_avatar ? ' show_avatar="'.$show_avatar.'"' : '';
			$show_name 		 = $show_name ? ' show_name="'.$show_name.'"' : '';
			$show_total  = $show_total ? ' show_total="'.$show_total.'"' : '';
			$show_time 	 = $show_time ? ' show_time="'.$show_time.'"' : '';
			$only_comments 	 = $only_comments ? ' only_comments="'.$only_comments.'"' : '';
			$show_comments 	 = $show_comments ? ' show_comments="'.$show_comments.'"' : '';
			$comment_length  = $comment_length ? ' comment_length="'.$comment_length.'"' : '';
			$readmore_text  = $readmore_text ? ' readmore_text="'.$readmore_text.'"' : '';
			$loadmore_text  = $loadmore_text ? ' loadmore_text="'.$loadmore_text.'"' : '';

			if($donor_style == 'two') {
				$style_cls = ' style-two';
			} else {
				$style_cls = '';
			}

	  	$output = '<div class="nacep-give-wall '.$style_cls.'">'.do_shortcode( '[give_donor_wall '. $donors_per_page . $form_id . $orderby . $order . $columns . $anonymous . $show_avatar . $show_name . $show_total . $show_time . $only_comments . $show_comments . $comment_length . $readmore_text . $loadmore_text .']' ).'</div>';

		  echo $output;

		}

	}
	Plugin::instance()->widgets_manager->register_widget_type( new Charity_Elementor_Addon_GIVE_Donor_Wall() );
}
