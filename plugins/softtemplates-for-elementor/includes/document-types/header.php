<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Softtemplate_Header_Document extends Softtemplate_Document_Base {

	public function get_name() {
		return 'softtemplate_header';
	}

	public static function get_title() {
		return __( 'Header', 'soft-template-core' );
	}

	/**
	 * @since 2.0.0
	 * @access protected
	 */
	protected function register_controls() {
		parent::register_controls();

		$this->start_controls_section(
			'softtemplate_template_preview_header',
			array(
				'label' => __( 'Header Extra', 'soft-template-core' ),
				'tab' => Elementor\Controls_Manager::TAB_SETTINGS,
			)
		);

		$this->add_control(
			'fixed_header',
			[
				'label' => __( 'Sticky Header', 'soft-template-core' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => __( 'Show', 'soft-template-core' ),
				'label_off' => __( 'Hide', 'soft-template-core' ),
				'return_value' => 'yes',
				'default' => '',
			]
		);			
		
		$this->add_control(
			'tablet_fixed_header',
			[
				'label' => __( 'Sticky Header Hide Tablet', 'soft-template-core' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => __( 'Show', 'soft-template-core' ),
				'label_off' => __( 'Hide', 'soft-template-core' ),
				'return_value' => 'yes',
				'default' => '',
				'condition'   => [
					'fixed_header' => 'yes',
				],
			]
		);			
		
		$this->add_control(
			'mobile_fixed_header',
			[
				'label' => __( 'Sticky Header Hide Mobile', 'soft-template-core' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => __( 'Show', 'soft-template-core' ),
				'label_off' => __( 'Hide', 'soft-template-core' ),
				'return_value' => 'yes',
				'default' => '',
				'condition'   => [
					'fixed_header' => 'yes',
				],
			]
		);	
		
		$this->add_control(
			'sticky_trigger',
			[
				'label' => __( 'Sticky Trigger', 'soft-template-core' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => '',
				'options' => [
					'down' => __( 'On Scroll Down', 'soft-template-core' ),
					'' => __( 'None', 'soft-template-core' ),
				],
				'condition'   => [
					'fixed_header' => 'yes',
				],
			]
		);

		$this->add_control(
			'z_index',
			[
				'label' => __( 'Z-Index', 'soft-template-core' ),
				'type' => \Elementor\Controls_Manager::NUMBER,
				'min' => 0,
				'max' => 1000,
				'step' => 0,
				'default' => 0,
				'condition'   => [
					'fixed_header' => 'yes',
				],
				'selectors' => [
					'header.stfe-sticky-header, header.sfte-fixed-header' => 'z-index: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'top_positions',
			[
				'label' => __( 'Top Position', 'soft-template-core' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 500,
					],
				],
				'default' => [
					'size' => 0,
					'unit' => 'px',
				],
				'condition'   => [
					'fixed_header' => 'yes',
				],
				'separator' => 'after',
			]
		);
		
		$this->add_control(
			'transparent_header',
			[
				'label' => __( 'Transparent Header', 'soft-template-core' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => __( 'Show', 'soft-template-core' ),
				'label_off' => __( 'Hide', 'soft-template-core' ),
				'return_value' => 'yes',
				'default' => '',
			]
		);

		$this->add_control(
			'header_background',
			[
				'label' => __( 'Background Color', 'soft-template-core' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'header.stfe-sticky-header.enable-sticky' => 'background: {{VALUE}}',
				],
			]
		);	
		
		$this->add_control(
			'header_color',
			[
				'label' => __( 'Elements Color', 'soft-template-core' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'header.stfe-sticky-header.enable-sticky, header.stfe-sticky-header.enable-sticky nav > ul > li > div > .stfe-menu-item, header.stfe-sticky-header.enable-sticky .stfe-search-modal, header.stfe-sticky-header.enable-sticky .stfe-offcanvas-trigger' => 'color: {{VALUE}} !important;',
				],
			]
		);

		$this->add_control(
			'header_hover_color',
			[
				'label' => __( 'Elements Hover Color', 'soft-template-core' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'header.stfe-sticky-header.enable-sticky nav > ul > li:hover > div > .stfe-menu-item, header.stfe-sticky-header.enable-sticky .stfe-search-modal:hover, header.stfe-sticky-header.enable-sticky .stfe-offcanvas-trigger:hover' => 'color: {{VALUE}} !important;',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'header_box_shadow',
				'label' => __( 'Box Shadow', 'soft-template-core' ),
				'selector' => 'header.stfe-sticky-header',
			]
		);

		$this->end_controls_section();
	}

}