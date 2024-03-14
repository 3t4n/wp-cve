<?php
/*
 * Elementor Charity Addon for Elementor Give Receipt
 * Author & Copyright: NicheAddon
*/
namespace Elementor;

if ( is_plugin_active( 'give/give.php' ) ) {

	if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

	class Charity_Elementor_Addon_GIVE_Receipt extends Widget_Base{

		/**
		 * Retrieve the widget name.
		*/
		public function get_name(){
			return 'nacharity_give_receipt';
		}

		/**
		 * Retrieve the widget title.
		*/
		public function get_title(){
			return esc_html__( 'Receipt', 'charity-addon-for-elementor' );
		}

		/**
		 * Retrieve the widget icon.
		*/
		public function get_icon() {
			return 'eicon-document-file';
		}

		/**
		 * Retrieve the receipt of categories the widget belongs to.
		*/
		public function get_categories() {
			return ['nacharity-give-category'];
		}

		/**
		 * Register Charitys Addon for Elementor Give Receipt widget controls.
		 * Adds different input fields to allow the user to change and customize the widget settings.
		*/
		protected function _register_controls(){

			$this->start_controls_section(
				'section_receipt',
				[
					'label' => esc_html__( 'Receipt Options', 'charity-addon-for-elementor' ),
				]
			);
			$this->add_control(
				'price',
				[
					'label' => esc_html__( 'Show Donation Amount?', 'charity-addon-for-elementor' ),
					'type' => Controls_Manager::SWITCHER,
					'label_on' => esc_html__( 'Hide', 'charity-addon-for-elementor' ),
					'label_off' => esc_html__( 'Show', 'charity-addon-for-elementor' ),
					'return_value' => 'true',
					'default' => 'true',
					'description' => esc_html__( 'Do you want to display the donation amount?', 'charity-addon-for-elementor' ),
				]
			);
			$this->add_control(
				'donor',
				[
					'label' => esc_html__( 'Show Donor Name?', 'charity-addon-for-elementor' ),
					'type' => Controls_Manager::SWITCHER,
					'label_on' => esc_html__( 'Hide', 'charity-addon-for-elementor' ),
					'label_off' => esc_html__( 'Show', 'charity-addon-for-elementor' ),
					'return_value' => 'true',
					'default' => 'true',
					'description' => esc_html__( 'Do you want to display the donor name?', 'charity-addon-for-elementor' ),
				]
			);
			$this->add_control(
				'date',
				[
					'label' => esc_html__( 'Show Date?', 'charity-addon-for-elementor' ),
					'type' => Controls_Manager::SWITCHER,
					'label_on' => esc_html__( 'Hide', 'charity-addon-for-elementor' ),
					'label_off' => esc_html__( 'Show', 'charity-addon-for-elementor' ),
					'return_value' => 'true',
					'default' => 'true',
					'description' => esc_html__( 'Do you want to display the date?', 'charity-addon-for-elementor' ),
				]
			);
			$this->add_control(
				'payment_method',
				[
					'label' => esc_html__( 'Show Payment Method?', 'charity-addon-for-elementor' ),
					'type' => Controls_Manager::SWITCHER,
					'label_on' => esc_html__( 'Hide', 'charity-addon-for-elementor' ),
					'label_off' => esc_html__( 'Show', 'charity-addon-for-elementor' ),
					'return_value' => 'true',
					'default' => 'true',
					'description' => esc_html__( 'Do you want to display the payment method?', 'charity-addon-for-elementor' ),
				]
			);
			$this->add_control(
				'payment_id',
				[
					'label' => esc_html__( 'Show Payment ID?', 'charity-addon-for-elementor' ),
					'type' => Controls_Manager::SWITCHER,
					'label_on' => esc_html__( 'Hide', 'charity-addon-for-elementor' ),
					'label_off' => esc_html__( 'Show', 'charity-addon-for-elementor' ),
					'return_value' => 'true',
					'default' => 'true',
					'description' => esc_html__( 'Do you want to display the payment id?', 'charity-addon-for-elementor' ),
				]
			);
			$this->add_control(
				'company_name',
				[
					'label' => esc_html__( 'Show Company Name?', 'charity-addon-for-elementor' ),
					'type' => Controls_Manager::SWITCHER,
					'label_on' => esc_html__( 'Hide', 'charity-addon-for-elementor' ),
					'label_off' => esc_html__( 'Show', 'charity-addon-for-elementor' ),
					'return_value' => 'true',
					'default' => 'true',
					'description' => esc_html__( 'Do you want to display the company name?', 'charity-addon-for-elementor' ),
				]
			);
			$this->end_controls_section();// end: Section

			// Table
				$this->start_controls_section(
					'table_style',
					[
						'label' => esc_html__( 'Table', 'charity-addon-for-elementor' ),
						'tab' => Controls_Manager::TAB_STYLE,
					]
				);
				$this->add_group_control(
					Group_Control_Border::get_type(),
					[
						'name' => 'table_border',
						'label' => esc_html__( 'Border', 'charity-addon-for-elementor' ),
						'selector' => '{{WRAPPER}} table.give-table',
					]
				);
				$this->add_control(
					'odd_options',
					[
						'label' => __( 'Odd Row', 'charity-addon-for-elementor' ),
						'type' => Controls_Manager::HEADING,
						'frontend_available' => true,
						'separator' => 'before',
					]
				);
				$this->add_control(
					'odd_bg_color',
					[
						'label' => esc_html__( 'Background Color', 'charity-addon-for-elementor' ),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} table.give-table tbody>tr:nth-child(odd)>td' => 'background-color: {{VALUE}};',
						],
					]
				);
				$this->add_control(
					'odd_color',
					[
						'label' => esc_html__( 'Color', 'charity-addon-for-elementor' ),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} table.give-table tbody>tr:nth-child(odd)>td' => 'color: {{VALUE}};',
						],
					]
				);
				$this->add_control(
					'even_options',
					[
						'label' => __( 'Even Row', 'charity-addon-for-elementor' ),
						'type' => Controls_Manager::HEADING,
						'frontend_available' => true,
						'separator' => 'before',
					]
				);
				$this->add_control(
					'even_bg_color',
					[
						'label' => esc_html__( 'Background Color', 'charity-addon-for-elementor' ),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} table.give-table tbody>tr:nth-child(even)>td' => 'background-color: {{VALUE}};',
						],
					]
				);
				$this->add_control(
					'even_color',
					[
						'label' => esc_html__( 'Color', 'charity-addon-for-elementor' ),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} table.give-table tbody>tr:nth-child(even)>td' => 'color: {{VALUE}};',
						],
					]
				);
				$this->end_controls_section();// end: Section

			// Head
				$this->start_controls_section(
					'sectn_style',
					[
						'label' => esc_html__( 'Table Head', 'charity-addon-for-elementor' ),
						'tab' => Controls_Manager::TAB_STYLE,
					]
				);
				$this->add_control(
					'secn_bg_color',
					[
						'label' => esc_html__( 'Background Color', 'charity-addon-for-elementor' ),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} table.give-table th' => 'background-color: {{VALUE}};',
						],
					]
				);
				$this->add_group_control(
					Group_Control_Border::get_type(),
					[
						'name' => 'secn_border',
						'label' => esc_html__( 'Border', 'charity-addon-for-elementor' ),
						'selector' => '{{WRAPPER}} table.give-table th',
					]
				);
				$this->add_group_control(
					Group_Control_Box_Shadow::get_type(),
					[
						'name' => 'secn_box_shadow',
						'label' => esc_html__( 'Section Box Shadow', 'charity-addon-for-elementor' ),
						'selector' => '{{WRAPPER}} table.give-table th',
					]
				);
				$this->add_group_control(
					Group_Control_Typography::get_type(),
					[
						'label' => esc_html__( 'Typography', 'charity-addon-for-elementor' ),
						'name' => 'give_head_typography',
						'selector' => '{{WRAPPER}} table.give-table th',
					]
				);
				$this->add_control(
					'give_head_color',
					[
						'label' => esc_html__( 'Color', 'charity-addon-for-elementor' ),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} table.give-table th' => 'color: {{VALUE}};',
						],
					]
				);
				$this->end_controls_section();// end: Section

			// Text
			$this->start_controls_section(
				'section_text_style',
				[
					'label' => esc_html__( 'Text', 'charity-addon-for-elementor' ),
					'tab' => Controls_Manager::TAB_STYLE,
				]
			);
				$this->add_group_control(
					Group_Control_Typography::get_type(),
					[
						'label' => esc_html__( 'Typography', 'charity-addon-for-elementor' ),
						'name' => 'text_typography',
						'selector' => '{{WRAPPER}} table.give-table tbody tr td',
					]
				);
				$this->add_control(
					'text_color',
					[
						'label' => esc_html__( 'Color', 'charity-addon-for-elementor' ),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} table.give-table tbody tr td' => 'color: {{VALUE}};',
						],
					]
				);
				$this->add_control(
					'link_options',
					[
						'label' => __( 'Link', 'charity-addon-for-elementor' ),
						'type' => Controls_Manager::HEADING,
						'frontend_available' => true,
						'separator' => 'before',
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
							'{{WRAPPER}} table.give-table tbody tr td a' => 'color: {{VALUE}};',
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
							'{{WRAPPER}} table.give-table tbody tr td a:hover' => 'color: {{VALUE}};',
						],
					]
				);
				$this->end_controls_tab();  // end:Hover tab
			$this->end_controls_tabs(); // end tabs
			$this->end_controls_section();// end: Section

		}

		/**
		 * Render Give Receipt widget output on the frontend.
		 * Written in PHP and used to generate the final HTML.
		*/
		protected function render() {
			$settings = $this->get_settings_for_display();
			$price 	= !empty( $settings['price'] ) ? $settings['price'] : '';
			$donor 	= !empty( $settings['donor'] ) ? $settings['donor'] : '';
			$date 	= !empty( $settings['date'] ) ? $settings['date'] : '';
			$payment_method 	= !empty( $settings['payment_method'] ) ? $settings['payment_method'] : '';
			$payment_id 	= !empty( $settings['payment_id'] ) ? $settings['payment_id'] : '';
			$company_name 	= !empty( $settings['company_name'] ) ? $settings['company_name'] : '';

			$price = $price ? 'true' : 'false';
			$donor = $donor ? 'true' : 'false';
			$date = $date ? 'true' : 'false';
			$payment_method = $payment_method ? 'true' : 'false';
			$payment_id = $payment_id ? 'true' : 'false';
			$company_name = $company_name ? 'true' : 'false';

			$price = $price ? ' price="'.$price.'"' : '';
			$donor = $donor ? ' donor="'.$donor.'"' : '';
			$date = $date ? ' date="'.$date.'"' : '';
			$payment_method = $payment_method ? ' payment_method="'.$payment_method.'"' : '';
			$payment_id = $payment_id ? ' payment_id="'.$payment_id.'"' : '';
			$company_name = $company_name ? ' company_name="'.$company_name.'"' : '';

	  	$output = '<div class="nacep-give-receipt">'.do_shortcode( '[give_receipt'. $price . $donor . $date . $payment_method . $payment_id . $company_name .']' ).'</div>';

		  echo $output;

		}

	}
	Plugin::instance()->widgets_manager->register_widget_type( new Charity_Elementor_Addon_GIVE_Receipt() );
}
