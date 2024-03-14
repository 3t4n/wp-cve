<?php
/*
 * Elementor Charity Addon for Elementor Give Totals
 * Author & Copyright: NicheAddon
*/
namespace Elementor;

if ( is_plugin_active( 'give/give.php' ) ) {

	if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

	class Charity_Elementor_Addon_GIVE_Totals extends Widget_Base{

		/**
		 * Retrieve the widget name.
		*/
		public function get_name(){
			return 'nacharity_give_totals';
		}

		/**
		 * Retrieve the widget title.
		*/
		public function get_title(){
			return esc_html__( 'Totals', 'charity-addon-for-elementor' );
		}

		/**
		 * Retrieve the widget icon.
		*/
		public function get_icon() {
			return 'eicon-price-list';
		}

		/**
		 * Retrieve the totals of categories the widget belongs to.
		*/
		public function get_categories() {
			return ['nacharity-give-category'];
		}

		/**
		 * Register Charitys Addon for Elementor Give Totals widget controls.
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
					'label' => esc_html__( 'Totals Options', 'charity-addon-for-elementor' ),
				]
			);
			$this->add_control(
				'ids',
				[
					'label' => __( 'Form', 'charity-addon-for-elementor' ),
					'type' => Controls_Manager::SELECT2,
					'default' => [],
					'options' => $CharityID,
					'multiple' => true,
					'description' => esc_html__( 'Enter the IDs separated by commas for the donation forms you would like to combine within the totals.', 'charity-addon-for-elementor' ),
				]
			);
			$this->add_control(
				'total_goal',
				[
					'label' => esc_html__( 'Total Goal', 'charity-addon-for-elementor' ),
					'type' => Controls_Manager::TEXT,
					'placeholder' => esc_html__( '$10,000', 'charity-addon-for-elementor' ),
					'label_block' => true,
					'description' => esc_html__( 'Enter the total goal amount that you would like to display.', 'charity-addon-for-elementor' ),
				]
			);
			$this->add_control(
				'message',
				[
					'label' => esc_html__( 'Message', 'charity-addon-for-elementor' ),
					'type' => Controls_Manager::TEXTAREA,
					'placeholder' => esc_html__( 'Message text here', 'charity-addon-for-elementor' ),
					'label_block' => true,
					'description' => esc_html__( 'Enter a message to display encouraging donors to support the goal.', 'charity-addon-for-elementor' ),
				]
			);
			$this->add_control(
				'link',
				[
					'label' => esc_html__( 'Link', 'charity-addon-for-elementor' ),
					'type' => Controls_Manager::TEXT,
					'placeholder' => esc_html__( 'https://your-link.com', 'charity-addon-for-elementor' ),
					'label_block' => true,
					'description' => esc_html__( 'Enter a link to the main campaign donation form.', 'charity-addon-for-elementor' ),
				]
			);
			$this->add_control(
				'link_text',
				[
					'label' => esc_html__( 'Link Text', 'charity-addon-for-elementor' ),
					'type' => Controls_Manager::TEXT,
					'default' => esc_html__( 'Donate!', 'charity-addon-for-elementor' ),
					'placeholder' => esc_html__( 'Donate!', 'charity-addon-for-elementor' ),
					'label_block' => true,
					'description' => esc_html__( 'Enter hyperlink text for the link to the main campaign donation form.', 'charity-addon-for-elementor' ),
				]
			);
			$this->add_control(
				'progress_bar',
				[
					'label' => esc_html__( 'Show Progress Bar?', 'charity-addon-for-elementor' ),
					'type' => Controls_Manager::SWITCHER,
					'label_on' => esc_html__( 'Hide', 'charity-addon-for-elementor' ),
					'label_off' => esc_html__( 'Show', 'charity-addon-for-elementor' ),
					'return_value' => 'true',
					'default' => 'true',
					'description' => esc_html__( 'Determines whether the avatar is visible.', 'charity-addon-for-elementor' ),
				]
			);

			$this->end_controls_section();// end: Section

			// Title
				$this->start_controls_section(
					'section_title_style',
					[
						'label' => esc_html__( 'Raised Title', 'charity-addon-for-elementor' ),
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
							'{{WRAPPER}} .raised' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
						],
					]
				);
				$this->add_group_control(
					Group_Control_Typography::get_type(),
					[
						'label' => esc_html__( 'Typography', 'charity-addon-for-elementor' ),
						'name' => 'title_typography',
						'selector' => '{{WRAPPER}} .raised',
					]
				);
				$this->add_control(
					'title_color',
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
					'sectn_style',
					[
						'label' => esc_html__( 'Progress Bar', 'charity-addon-for-elementor' ),
						'tab' => Controls_Manager::TAB_STYLE,
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
								'{{WRAPPER}} .give-progress-bar' => 'background-color: {{VALUE}};',
							],
						]
					);
					$this->end_controls_tab();  // end:Normal tab

					$this->start_controls_tab(
						'secn_hover',
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

			// Message
				$this->start_controls_section(
					'section_message_style',
					[
						'label' => esc_html__( 'Message', 'charity-addon-for-elementor' ),
						'tab' => Controls_Manager::TAB_STYLE,
					]
				);
				$this->add_group_control(
					Group_Control_Typography::get_type(),
					[
						'label' => esc_html__( 'Typography', 'charity-addon-for-elementor' ),
						'name' => 'message_typography',
						'selector' => '{{WRAPPER}} .nacep-give-totals',
					]
				);
				$this->add_control(
					'message_color',
					[
						'label' => esc_html__( 'Color', 'charity-addon-for-elementor' ),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .nacep-give-totals' => 'color: {{VALUE}} !important;',
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
				$this->add_control(
					'link_margin',
					[
						'label' => __( 'Margin', 'charity-addon-for-elementor' ),
						'type' => Controls_Manager::DIMENSIONS,
						'size_units' => [ 'px', '%', 'em' ],
						'selectors' => [
							'{{WRAPPER}} a.give-totals-text-link' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
						],
					]
				);
				$this->add_group_control(
					Group_Control_Typography::get_type(),
					[
						'label' => esc_html__( 'Typography', 'charity-addon-for-elementor' ),
						'name' => 'link_typography',
						'selector' => '{{WRAPPER}} a.give-totals-text-link',
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
								'{{WRAPPER}} a.give-totals-text-link' => 'color: {{VALUE}} !important;',
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
								'{{WRAPPER}} a.give-totals-text-link:hover' => 'color: {{VALUE}} !important;',
							],
						]
					);
					$this->end_controls_tab();  // end:Hover tab
				$this->end_controls_tabs(); // end tabs
				$this->end_controls_section();// end: Section

		}

		/**
		 * Render Give Totals widget output on the frontend.
		 * Written in PHP and used to generate the final HTML.
		*/
		protected function render() {
			$settings = $this->get_settings_for_display();
			$ids 				= !empty( $settings['ids'] ) ? $settings['ids'] : '';
			$total_goal 	= !empty( $settings['total_goal'] ) ? $settings['total_goal'] : '';
			$message 		= !empty( $settings['message'] ) ? $settings['message'] : '';
			$link 	= !empty( $settings['link'] ) ? $settings['link'] : '';
			$link_text 	= !empty( $settings['link_text'] ) ? $settings['link_text'] : '';
			$progress_bar 	= !empty( $settings['progress_bar'] ) ? $settings['progress_bar'] : '';

			$progress_bar = $progress_bar ? 'true' : 'false';

			$ids 					 = $ids ? ' ids="'.implode(',', $ids).'"' : '';
			$total_goal 				 = $total_goal ? ' total_goal="'.$total_goal.'"' : '';
			$message 					 = $message ? ' message="'.$message.'"' : '';
			$link 				 = $link ? ' link="'.$link.'"' : '';
			$link_text 	 = $link_text ? ' link_text="'.$link_text.'"' : '';
			$progress_bar 	 = $progress_bar ? ' progress_bar="'.$progress_bar.'"' : '';

	  	$output = '<div class="nacep-give-totals">'.do_shortcode( '[give_totals '. $ids . $total_goal . $message . $link . $link_text . $progress_bar .']' ).'</div>';

		  echo $output;

		}

	}
	Plugin::instance()->widgets_manager->register_widget_type( new Charity_Elementor_Addon_GIVE_Totals() );
}
