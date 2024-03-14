<?php
/*
 * Elementor Charity Addon for Elementor Charitable Donors
 * Author & Copyright: NicheAddon
*/
namespace Elementor;

if ( is_plugin_active( 'charitable/charitable.php' ) ) {

	if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

	class Charity_Elementor_Addon_Charitable_Donors extends Widget_Base{

		/**
		 * Retrieve the widget name.
		*/
		public function get_name(){
			return 'nacharity_charitable_donors';
		}

		/**
		 * Retrieve the widget title.
		*/
		public function get_title(){
			return esc_html__( 'Donors', 'charity-addon-for-elementor' );
		}

		/**
		 * Retrieve the widget icon.
		*/
		public function get_icon() {
			return 'eicon-gallery-grid';
		}

		/**
		 * Retrieve the donors of categories the widget belongs to.
		*/
		public function get_categories() {
			return ['nacharity-charitable-category'];
		}

		/**
		 * Register Charitys Addon for Elementor Charitable Donors widget controls.
		 * Adds different input fields to allow the user to change and customize the widget settings.
		*/
		protected function _register_controls(){

			$charity = get_posts( 'post_type="campaign"&numberposts=-1' );
	    $CharityID = array('all' => esc_html__( 'All', 'charity-addon-for-elementor' ));
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
					'label' => esc_html__( 'Donors Options', 'charity-addon-for-elementor' ),
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
					'description' => esc_html__( 'Sets the number of donors per page.', 'charity-addon-for-elementor' ),
				]
			);
			$this->add_control(
				'orderby',
				[
					'label' => __( 'Order By', 'charity-addon-for-elementor' ),
					'type' => Controls_Manager::SELECT,
					'options' => [
						'date' => esc_html__( 'Date', 'charity-addon-for-elementor' ),
						'donations' => esc_html__( 'Donations', 'charity-addon-for-elementor' ),
						'amount' => esc_html__( 'Amount', 'charity-addon-for-elementor' ),
					],
					'default' => 'date',
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
					'default' => 'DESC',
					'description' => esc_html__( 'Sets the order in which donors appear.', 'charity-addon-for-elementor' ),
				]
			);
			$this->add_control(
				'campaign',
				[
					'label' => __( 'Campaign', 'charity-addon-for-elementor' ),
					'type' => Controls_Manager::SELECT,
					'options' => $CharityID,
					'default' => 'all',
				]
			);
			$this->add_control(
				'distinct_donors',
				[
					'label' => esc_html__( 'Distinct Donors?', 'charity-addon-for-elementor' ),
					'type' => Controls_Manager::SWITCHER,
					'label_on' => esc_html__( 'Hide', 'charity-addon-for-elementor' ),
					'label_off' => esc_html__( 'Show', 'charity-addon-for-elementor' ),
					'return_value' => 'true',
				]
			);
			$this->add_control(
				'orientation',
				[
					'label' => __( 'Orientation', 'charity-addon-for-elementor' ),
					'type' => Controls_Manager::SELECT,
					'options' => [
						'vertical' => esc_html__( 'Vertical', 'charity-addon-for-elementor' ),
						'horizontal' => esc_html__( 'Horizontal', 'charity-addon-for-elementor' ),
					],
					'default' => 'horizontal',
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
				]
			);
			$this->add_control(
				'show_location',
				[
					'label' => esc_html__( 'Show Location?', 'charity-addon-for-elementor' ),
					'type' => Controls_Manager::SWITCHER,
					'label_on' => esc_html__( 'Hide', 'charity-addon-for-elementor' ),
					'label_off' => esc_html__( 'Show', 'charity-addon-for-elementor' ),
					'return_value' => 'true',
				]
			);
			$this->add_control(
				'show_amount',
				[
					'label' => esc_html__( 'Show Amount?', 'charity-addon-for-elementor' ),
					'type' => Controls_Manager::SWITCHER,
					'label_on' => esc_html__( 'Hide', 'charity-addon-for-elementor' ),
					'label_off' => esc_html__( 'Show', 'charity-addon-for-elementor' ),
					'return_value' => 'true',
					'default' => 'true',
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
				]
			);
			$this->add_control(
				'hide_if_no_donors',
				[
					'label' => esc_html__( 'Hide If No Donors?', 'charity-addon-for-elementor' ),
					'type' => Controls_Manager::SWITCHER,
					'label_on' => esc_html__( 'Hide', 'charity-addon-for-elementor' ),
					'label_off' => esc_html__( 'Show', 'charity-addon-for-elementor' ),
					'return_value' => 'true',
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
							'{{WRAPPER}} .donor-name' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
						],
					]
				);
				$this->add_group_control(
					Group_Control_Typography::get_type(),
					[
						'label' => esc_html__( 'Typography', 'charity-addon-for-elementor' ),
						'name' => 'title_typography',
						'selector' => '{{WRAPPER}} .donor-name',
					]
				);
				$this->add_control(
					'title_color',
					[
						'label' => esc_html__( 'Color', 'charity-addon-for-elementor' ),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .donor-name' => 'color: {{VALUE}} !important;',
						],
					]
				);
				$this->end_controls_section();// end: Section

			// Location
				$this->start_controls_section(
					'section_location_style',
					[
						'label' => esc_html__( 'Location', 'charity-addon-for-elementor' ),
						'tab' => Controls_Manager::TAB_STYLE,
					]
				);
				$this->add_control(
					'location_padding',
					[
						'label' => __( 'Padding', 'charity-addon-for-elementor' ),
						'type' => Controls_Manager::DIMENSIONS,
						'size_units' => [ 'px', '%', 'em' ],
						'selectors' => [
							'{{WRAPPER}} .donor-location' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
						],
					]
				);
				$this->add_group_control(
					Group_Control_Typography::get_type(),
					[
						'label' => esc_html__( 'Typography', 'charity-addon-for-elementor' ),
						'name' => 'location_typography',
						'selector' => '{{WRAPPER}} .donor-location',
					]
				);
				$this->add_control(
					'location_color',
					[
						'label' => esc_html__( 'Color', 'charity-addon-for-elementor' ),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .donor-location' => 'color: {{VALUE}} !important;',
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
							'{{WRAPPER}} .donor-donation-amount' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
						],
					]
				);
				$this->add_group_control(
					Group_Control_Typography::get_type(),
					[
						'label' => esc_html__( 'Typography', 'charity-addon-for-elementor' ),
						'name' => 'amount_typography',
						'selector' => '{{WRAPPER}} .donor-donation-amount',
					]
				);
				$this->add_control(
					'amount_color',
					[
						'label' => esc_html__( 'Color', 'charity-addon-for-elementor' ),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .donor-donation-amount' => 'color: {{VALUE}} !important;',
						],
					]
				);
				$this->end_controls_section();// end: Section

		}

		/**
		 * Render Charitable Donors widget output on the frontend.
		 * Written in PHP and used to generate the final HTML.
		*/
		protected function render() {
			$settings = $this->get_settings_for_display();
			$number 						= !empty( $settings['number'] ) ? $settings['number'] : '';
			$orderby 						= !empty( $settings['orderby'] ) ? $settings['orderby'] : '';
			$order 							= !empty( $settings['order'] ) ? $settings['order'] : '';
			$campaign 					= !empty( $settings['campaign'] ) ? $settings['campaign'] : '';
			$distinct_donors 		= !empty( $settings['distinct_donors'] ) ? $settings['distinct_donors'] : '';
			$orientation 				= !empty( $settings['orientation'] ) ? $settings['orientation'] : '';
			$show_name 					= !empty( $settings['show_name'] ) ? $settings['show_name'] : '';
			$show_location 			= !empty( $settings['show_location'] ) ? $settings['show_location'] : '';
			$show_amount 				= !empty( $settings['show_amount'] ) ? $settings['show_amount'] : '';
			$show_avatar 				= !empty( $settings['show_avatar'] ) ? $settings['show_avatar'] : '';
			$hide_if_no_donors 	= !empty( $settings['hide_if_no_donors'] ) ? $settings['hide_if_no_donors'] : '';

			$distinct_donors 		= $distinct_donors ? '1' : '0';
			$show_name 					= $show_name ? '1' : '0';
			$show_location 			= $show_location ? '1' : '0';
			$show_amount 				= $show_amount ? '1' : '0';
			$show_avatar 				= $show_avatar ? '1' : '0';
			$hide_if_no_donors 	= $hide_if_no_donors ? '1' : '0';

			$number 						= $number ? ' number='.$number : '';
			$orderby 						= $orderby ? ' orderby='.$orderby : '';
			$order 							= $order ? ' order='.$order : '';
			$campaign 					= $campaign ? ' campaign='.$campaign : '';
			$distinct_donors 		= $distinct_donors ? ' distinct_donors='.$distinct_donors : ' distinct_donors=0';
			$orientation 				= $orientation ? ' orientation='.$orientation : '';
			$show_name 					= $show_name ? ' show_name='.$show_name : ' show_name=0';
			$show_location 			= $show_location ? ' show_location='.$show_location : ' show_location=0';
			$show_amount 				= $show_amount ? ' show_amount='.$show_amount : ' show_amount=0';
			$show_avatar 				= $show_avatar ? ' show_avatar='.$show_avatar : ' show_avatar=0';
			$hide_if_no_donors 	= $hide_if_no_donors ? ' hide_if_no_donors='.$hide_if_no_donors : ' hide_if_no_donors=0';

	  	$output = '<div class="nacep-charitable-donors">'.do_shortcode( '[charitable_donors'. $number . $orderby . $order . $campaign . $distinct_donors . $orientation . $show_name . $show_location . $show_amount . $show_avatar . $hide_if_no_donors .']' ).'</div>';

		  echo $output;

		}

	}
	Plugin::instance()->widgets_manager->register_widget_type( new Charity_Elementor_Addon_Charitable_Donors() );
}
