<?php
/*
 * Elementor Charity Addon for Elementor Charitable Stats
 * Author & Copyright: NicheAddon
*/
namespace Elementor;

if ( is_plugin_active( 'charitable/charitable.php' ) ) {

	if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

	class Charity_Elementor_Addon_Charitable_Stats extends Widget_Base{

		/**
		 * Retrieve the widget name.
		*/
		public function get_name(){
			return 'nacharity_charitable_stat';
		}

		/**
		 * Retrieve the widget title.
		*/
		public function get_title(){
			return esc_html__( 'Stats', 'charity-addon-for-elementor' );
		}

		/**
		 * Retrieve the widget icon.
		*/
		public function get_icon() {
			return 'eicon-info-circle-o';
		}

		/**
		 * Retrieve the stat of categories the widget belongs to.
		*/
		public function get_categories() {
			return ['nacharity-charitable-category'];
		}

		/**
		 * Register Charitys Addon for Elementor Charitable Stats widget controls.
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
					'label' => esc_html__( 'Stats Options', 'charity-addon-for-elementor' ),
				]
			);
			$this->add_control(
				'display',
				[
					'label' => __( 'Display', 'charity-addon-for-elementor' ),
					'type' => Controls_Manager::SELECT,
					'options' => [
						'total' => esc_html__( 'Total', 'charity-addon-for-elementor' ),
						'progress' => esc_html__( 'Progress', 'charity-addon-for-elementor' ),
						'donors' => esc_html__( 'Donors', 'charity-addon-for-elementor' ),
						'donations' => esc_html__( 'Donations', 'charity-addon-for-elementor' ),
					],
					'default' => 'total',
					'description' => esc_html__( 'Different parameters to set the display in which stat appear.', 'charity-addon-for-elementor' ),
				]
			);
			$this->add_control(
				'campaign',
				[
					'label' => __( 'Campaign', 'charity-addon-for-elementor' ),
					'type' => Controls_Manager::SELECT2,
					'default' => [],
					'options' => $CharityID,
					'multiple' => true,
				]
			);
			$this->add_control(
				'goal',
				[
					'label' => esc_html__( 'Goal', 'charity-addon-for-elementor' ),
					'type' => Controls_Manager::TEXT,
					'label_block' => true,
					'condition' => [
						'display' => 'progress',
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
						'condition' => [
							'display' => 'progress',
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
								'{{WRAPPER}} .campaign-progress-bar' => 'background-color: {{VALUE}};',
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
							'selector' => '{{WRAPPER}} .campaign-progress-bar span.bar',
						]
					);
					$this->end_controls_tab();  // end:Hover tab
				$this->end_controls_tabs(); // end tabs
				$this->end_controls_section();// end: Section

			// Text
				$this->start_controls_section(
					'section_title_style',
					[
						'label' => esc_html__( 'Text', 'charity-addon-for-elementor' ),
						'tab' => Controls_Manager::TAB_STYLE,
						'condition' => [
							'display!' => 'progress',
						],
					]
				);
				$this->add_control(
					'title_padding',
					[
						'label' => __( 'Padding', 'charity-addon-for-elementor' ),
						'type' => Controls_Manager::DIMENSIONS,
						'size_units' => [ 'px', '%', 'em' ],
						'selectors' => [
							'{{WRAPPER}} .nacep-charitable-stat' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
						],
					]
				);
				$this->add_group_control(
					Group_Control_Typography::get_type(),
					[
						'label' => esc_html__( 'Typography', 'charity-addon-for-elementor' ),
						'name' => 'title_typography',
						'selector' => '{{WRAPPER}} .nacep-charitable-stat',
					]
				);
				$this->add_control(
					'title_color',
					[
						'label' => esc_html__( 'Color', 'charity-addon-for-elementor' ),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .nacep-charitable-stat' => 'color: {{VALUE}} !important;',
						],
					]
				);
				$this->end_controls_section();// end: Section

		}

		/**
		 * Render Charitable Stats widget output on the frontend.
		 * Written in PHP and used to generate the final HTML.
		*/
		protected function render() {
			$settings = $this->get_settings_for_display();
			$display 							= !empty( $settings['display'] ) ? $settings['display'] : '';
			$campaign 					= !empty( $settings['campaign'] ) ? $settings['campaign'] : '';
			$goal 							= !empty( $settings['goal'] ) ? $settings['goal'] : '';

			$display 		= $display ? ' display='.$display : '';
			$campaign = $campaign ? ' campaign='.implode(',', $campaign) : '';
			$goal 		= $goal ? ' goal='.$goal : '';

	  	$output = '<div class="nacep-charitable-stat">'.do_shortcode( '[charitable_stat'. $display . $campaign . $goal .']' ).'</div>';

		  echo $output;

		}

	}
	Plugin::instance()->widgets_manager->register_widget_type( new Charity_Elementor_Addon_Charitable_Stats() );
}
