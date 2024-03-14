<?php
/*
 * Elementor Primary Addon for Elementor Table Widget
 * Author & Copyright: NicheAddon
*/

namespace Elementor;

if (!isset(get_option( 'pafe_bw_settings' )['napafe_table'])) { // enable & disable

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Primary_Addon_Table extends Widget_Base{

	/**
	 * Retrieve the widget name.
	*/
	public function get_name(){
		return 'prim_basic_table';
	}

	/**
	 * Retrieve the widget title.
	*/
	public function get_title(){
		return esc_html__( 'Table', 'primary-addon-for-elementor' );
	}

	/**
	 * Retrieve the widget icon.
	*/
	public function get_icon() {
		return 'eicon-table';
	}

	/**
	 * Retrieve the list of categories the widget belongs to.
	*/
	public function get_categories() {
		return ['prim-basic-category'];
	}

	/**
	 * Register Primary Addon for Elementor Table widget controls.
	 * Adds different input fields to allow the user to change and customize the widget settings.
	*/
	protected function _register_controls(){

		$this->start_controls_section(
			'section_table',
			[
				'label' => esc_html__( 'Table Head', 'primary-addon-for-elementor' ),
			]
		);
		$this->add_responsive_control(
			'content_alignment',
			[
				'label' => esc_html__( 'Content Alignment', 'primary-addon-for-elementor' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => esc_html__( 'Left', 'primary-addon-for-elementor' ),
						'icon' => 'fa fa-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'primary-addon-for-elementor' ),
						'icon' => 'fa fa-align-center',
					],
					'right' => [
						'title' => esc_html__( 'Right', 'primary-addon-for-elementor' ),
						'icon' => 'fa fa-align-right',
					],
				],
				'default' => 'center',
				'selectors' => [
					'{{WRAPPER}} .napae-table td, {{WRAPPER}} .napae-table thead th' => 'text-align: {{VALUE}};',
				],
			]
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'table_title',
			[
				'label' => esc_html__( 'Title', 'primary-addon-for-elementor' ),
				'type' => Controls_Manager::TEXT,
				'label_block' => true,
			]
		);
		$this->add_control(
			'tableItems_title',
			[
				'label' => esc_html__( 'Table Head', 'primary-addon-for-elementor' ),
				'type' => Controls_Manager::REPEATER,
				'default' => [
					[
						'table_title' => esc_html__( 'Members', 'primary-addon-for-elementor' ),
					],

				],
				'fields' => $repeater->get_controls(),
				'title_field' => '{{{ table_title }}}',
			]
		);
		$this->end_controls_section();// end: Section

		$this->start_controls_section(
			'section_table_bdy',
			[
				'label' => esc_html__( 'Table Body', 'primary-addon-for-elementor' ),
			]
		);

		$repeaterOne = new Repeater();

		$repeaterOne->start_controls_tabs( 'table_rows' );
			$repeaterOne->start_controls_tab(
				'table_row1',
				[
					'label' => esc_html__( 'Row', 'primary-addon-for-elementor' ),
				]
			);
			$repeaterOne->add_control(
				'text_style1',
				[
					'label' => esc_html__( 'Text Style', 'primary-addon-for-elementor' ),
					'type' => Controls_Manager::SELECT,
					'options' => [
						'text'          => esc_html__('Text', 'primary-addon-for-elementor'),
	          'icon'          => esc_html__('Icon', 'primary-addon-for-elementor'),
	          'button'          => esc_html__('Button', 'primary-addon-for-elementor'),
					],
					'default' => 'text',
				]
			);
			$repeaterOne->add_control(
				'icon1',
				[
					'label' => esc_html__( 'Select Icon', 'primary-addon-for-elementor' ),
					'type' => Controls_Manager::ICON,
					'options' => NAPAE_Controls_Helper_Output::get_include_icons(),
					'frontend_available' => true,
					'default' => 'fa fa-check',
					'condition' => [
						'text_style1' => 'icon',
					],
				]
			);
			$repeaterOne->add_control(
				'row_text1',
				[
					'label' => esc_html__( 'Text', 'primary-addon-for-elementor' ),
					'type' => Controls_Manager::WYSIWYG,
					'label_block' => true,
					'condition' => [
						'text_style1!' => 'icon',
					],
				]
			);
			$repeaterOne->add_control(
				'text_link1',
				[
					'label' => esc_html__( 'Link', 'primary-addon-for-elementor' ),
					'type' => Controls_Manager::URL,
					'placeholder' => 'https://your-link.com',
					'default' => [
						'url' => '',
					],
					'label_block' => true,
					'condition' => [
						'text_style1' => 'button',
					],
				]
			);
			$repeaterOne->end_controls_tab();  // end:Normal tab
			$repeaterOne->start_controls_tab(
				'table_row2',
				[
					'label' => esc_html__( 'Row', 'primary-addon-for-elementor' ),
				]
			);
			$repeaterOne->add_control(
				'text_style2',
				[
					'label' => esc_html__( 'Text Style', 'primary-addon-for-elementor' ),
					'type' => Controls_Manager::SELECT,
					'options' => [
						'text'          => esc_html__('Text', 'primary-addon-for-elementor'),
	          'icon'          => esc_html__('Icon', 'primary-addon-for-elementor'),
	          'button'          => esc_html__('Button', 'primary-addon-for-elementor'),
					],
					'default' => 'text',
				]
			);
			$repeaterOne->add_control(
				'icon2',
				[
					'label' => esc_html__( 'Select Icon', 'primary-addon-for-elementor' ),
					'type' => Controls_Manager::ICON,
					'options' => NAPAE_Controls_Helper_Output::get_include_icons(),
					'frontend_available' => true,
					'default' => 'fa fa-check',
					'condition' => [
						'text_style2' => 'icon',
					],
				]
			);
			$repeaterOne->add_control(
				'row_text2',
				[
					'label' => esc_html__( 'Text', 'primary-addon-for-elementor' ),
					'type' => Controls_Manager::WYSIWYG,
					'label_block' => true,
					'condition' => [
						'text_style2!' => 'icon',
					],
				]
			);
			$repeaterOne->add_control(
				'text_link2',
				[
					'label' => esc_html__( 'Link', 'primary-addon-for-elementor' ),
					'type' => Controls_Manager::URL,
					'placeholder' => 'https://your-link.com',
					'default' => [
						'url' => '',
					],
					'label_block' => true,
					'condition' => [
						'text_style2' => 'button',
					],
				]
			);
			$repeaterOne->end_controls_tab();  // end:Normal tab
			$repeaterOne->start_controls_tab(
				'table_row3',
				[
					'label' => esc_html__( 'Row', 'primary-addon-for-elementor' ),
				]
			);
			$repeaterOne->add_control(
				'text_style3',
				[
					'label' => esc_html__( 'Text Style', 'primary-addon-for-elementor' ),
					'type' => Controls_Manager::SELECT,
					'options' => [
						'text'          => esc_html__('Text', 'primary-addon-for-elementor'),
	          'icon'          => esc_html__('Icon', 'primary-addon-for-elementor'),
	          'button'          => esc_html__('Button', 'primary-addon-for-elementor'),
					],
					'default' => 'text',
				]
			);
			$repeaterOne->add_control(
				'icon3',
				[
					'label' => esc_html__( 'Select Icon', 'primary-addon-for-elementor' ),
					'type' => Controls_Manager::ICON,
					'options' => NAPAE_Controls_Helper_Output::get_include_icons(),
					'frontend_available' => true,
					'default' => 'fa fa-check',
					'condition' => [
						'text_style3' => 'icon',
					],
				]
			);
			$repeaterOne->add_control(
				'row_text3',
				[
					'label' => esc_html__( 'Text', 'primary-addon-for-elementor' ),
					'type' => Controls_Manager::WYSIWYG,
					'label_block' => true,
					'condition' => [
						'text_style3!' => 'icon',
					],
				]
			);
			$repeaterOne->add_control(
				'text_link3',
				[
					'label' => esc_html__( 'Link', 'primary-addon-for-elementor' ),
					'type' => Controls_Manager::URL,
					'placeholder' => 'https://your-link.com',
					'default' => [
						'url' => '',
					],
					'label_block' => true,
					'condition' => [
						'text_style3' => 'button',
					],
				]
			);
			$repeaterOne->end_controls_tab();  // end:Normal tab
			$repeaterOne->start_controls_tab(
				'table_row4',
				[
					'label' => esc_html__( 'Row', 'primary-addon-for-elementor' ),
				]
			);
			$repeaterOne->add_control(
				'text_style4',
				[
					'label' => esc_html__( 'Text Style', 'primary-addon-for-elementor' ),
					'type' => Controls_Manager::SELECT,
					'options' => [
						'text'          => esc_html__('Text', 'primary-addon-for-elementor'),
	          'icon'          => esc_html__('Icon', 'primary-addon-for-elementor'),
	          'button'          => esc_html__('Button', 'primary-addon-for-elementor'),
					],
					'default' => 'text',
				]
			);
			$repeaterOne->add_control(
				'icon4',
				[
					'label' => esc_html__( 'Select Icon', 'primary-addon-for-elementor' ),
					'type' => Controls_Manager::ICON,
					'options' => NAPAE_Controls_Helper_Output::get_include_icons(),
					'frontend_available' => true,
					'default' => 'fa fa-check',
					'condition' => [
						'text_style4' => 'icon',
					],
				]
			);
			$repeaterOne->add_control(
				'row_text4',
				[
					'label' => esc_html__( 'Text', 'primary-addon-for-elementor' ),
					'type' => Controls_Manager::WYSIWYG,
					'label_block' => true,
					'condition' => [
						'text_style4!' => 'icon',
					],
				]
			);
			$repeaterOne->add_control(
				'text_link4',
				[
					'label' => esc_html__( 'Link', 'primary-addon-for-elementor' ),
					'type' => Controls_Manager::URL,
					'placeholder' => 'https://your-link.com',
					'default' => [
						'url' => '',
					],
					'label_block' => true,
					'condition' => [
						'text_style4' => 'button',
					],
				]
			);
			$repeaterOne->end_controls_tab();  // end:Normal tab
			$repeaterOne->start_controls_tab(
				'table_row5',
				[
					'label' => esc_html__( 'Row', 'primary-addon-for-elementor' ),
				]
			);
			$repeaterOne->add_control(
				'text_style5',
				[
					'label' => esc_html__( 'Text Style', 'primary-addon-for-elementor' ),
					'type' => Controls_Manager::SELECT,
					'options' => [
						'text'          => esc_html__('Text', 'primary-addon-for-elementor'),
	          'icon'          => esc_html__('Icon', 'primary-addon-for-elementor'),
	          'button'          => esc_html__('Button', 'primary-addon-for-elementor'),
					],
					'default' => 'text',
				]
			);
			$repeaterOne->add_control(
				'icon5',
				[
					'label' => esc_html__( 'Select Icon', 'primary-addon-for-elementor' ),
					'type' => Controls_Manager::ICON,
					'options' => NAPAE_Controls_Helper_Output::get_include_icons(),
					'frontend_available' => true,
					'default' => 'fa fa-check',
					'condition' => [
						'text_style5' => 'icon',
					],
				]
			);
			$repeaterOne->add_control(
				'row_text5',
				[
					'label' => esc_html__( 'Text', 'primary-addon-for-elementor' ),
					'type' => Controls_Manager::WYSIWYG,
					'label_block' => true,
					'condition' => [
						'text_style5!' => 'icon',
					],
				]
			);
			$repeaterOne->add_control(
				'text_link5',
				[
					'label' => esc_html__( 'Link', 'primary-addon-for-elementor' ),
					'type' => Controls_Manager::URL,
					'placeholder' => 'https://your-link.com',
					'default' => [
						'url' => '',
					],
					'label_block' => true,
					'condition' => [
						'text_style5' => 'button',
					],
				]
			);
			$repeaterOne->end_controls_tab();  // end:Normal tab
		$repeaterOne->end_controls_tabs(); // end tabs
		$this->add_control(
			'tableItems_row',
			[
				'label' => esc_html__( 'Table Row', 'primary-addon-for-elementor' ),
				'type' => Controls_Manager::REPEATER,
				'default' => [
					[
						'row_text1' => esc_html__( 'Item #1', 'primary-addon-for-elementor' ),
					],

				],
				'fields' => $repeater->get_controls(),
				'title_field' => '{{{ row_text1 }}}',
			]
		);
		$this->end_controls_section();// end: Section

		// Table
		$this->start_controls_section(
			'table_style',
			[
				'label' => esc_html__( 'Table', 'primary-addon-for-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_control(
			'table_bg_color',
			[
				'label' => esc_html__( 'Background Color', 'primary-addon-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} table.napae-table' => 'background-color: {{VALUE}};',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'table_border',
				'label' => esc_html__( 'Border', 'primary-addon-for-elementor' ),
				'selector' => '{{WRAPPER}} .napae-table td',
			]
		);
		$this->add_control(
			'odd_options',
			[
				'label' => __( 'Odd Row', 'primary-addon-for-elementor' ),
				'type' => Controls_Manager::HEADING,
				'frontend_available' => true,
				'separator' => 'before',
			]
		);
		$this->add_control(
			'odd_bg_color',
			[
				'label' => esc_html__( 'Background Color', 'primary-addon-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} table.napae-table tbody>tr:nth-child(odd)>td' => 'background-color: {{VALUE}};',
				],
			]
		);
		$this->add_control(
			'odd_color',
			[
				'label' => esc_html__( 'Color', 'primary-addon-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} table.napae-table tbody>tr:nth-child(odd)>td' => 'color: {{VALUE}};',
				],
			]
		);
		$this->add_control(
			'even_options',
			[
				'label' => __( 'Even Row', 'primary-addon-for-elementor' ),
				'type' => Controls_Manager::HEADING,
				'frontend_available' => true,
				'separator' => 'before',
			]
		);
		$this->add_control(
			'even_bg_color',
			[
				'label' => esc_html__( 'Background Color', 'primary-addon-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} table.napae-table tbody>tr:nth-child(even)>td' => 'background-color: {{VALUE}};',
				],
			]
		);
		$this->add_control(
			'even_color',
			[
				'label' => esc_html__( 'Color', 'primary-addon-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} table.napae-table tbody>tr:nth-child(even)>td' => 'color: {{VALUE}};',
				],
			]
		);
		$this->end_controls_section();// end: Section

		// Head
		$this->start_controls_section(
			'sectn_style',
			[
				'label' => esc_html__( 'Table Head', 'primary-addon-for-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_control(
			'secn_bg_color',
			[
				'label' => esc_html__( 'Background Color', 'primary-addon-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .napae-table thead tr' => 'background-color: {{VALUE}};',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'secn_border',
				'label' => esc_html__( 'Border', 'primary-addon-for-elementor' ),
				'selector' => '{{WRAPPER}} .napae-table thead tr, {{WRAPPER}} table.napae-table thead:first-child tr:first-child th',
			]
		);
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'secn_box_shadow',
				'label' => esc_html__( 'Section Box Shadow', 'primary-addon-for-elementor' ),
				'selector' => '{{WRAPPER}} .napae-table thead tr',
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'label' => esc_html__( 'Typography', 'primary-addon-for-elementor' ),
				'name' => 'sastable_head_typography',
				'selector' => '{{WRAPPER}} .napae-table thead th',
			]
		);
		$this->add_control(
			'sastable_head_color',
			[
				'label' => esc_html__( 'Color', 'primary-addon-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .napae-table thead th' => 'color: {{VALUE}};',
				],
			]
		);
		$this->end_controls_section();// end: Section

		// Text
		$this->start_controls_section(
			'section_text_style',
			[
				'label' => esc_html__( 'Text', 'primary-addon-for-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);
			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'label' => esc_html__( 'Typography', 'primary-addon-for-elementor' ),
					'name' => 'text_typography',
					'selector' => '{{WRAPPER}} .napae-table td',
				]
			);
			$this->add_control(
				'text_color',
				[
					'label' => esc_html__( 'Color', 'primary-addon-for-elementor' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .napae-table td' => 'color: {{VALUE}};',
					],
				]
			);
			$this->add_control(
				'link_options',
				[
					'label' => __( 'Link', 'primary-addon-for-elementor' ),
					'type' => Controls_Manager::HEADING,
					'frontend_available' => true,
					'separator' => 'before',
				]
			);
			$this->start_controls_tabs( 'link_style' );
			$this->start_controls_tab(
				'link_normal',
				[
					'label' => esc_html__( 'Normal', 'primary-addon-for-elementor' ),
				]
			);
			$this->add_control(
				'link_color',
				[
					'label' => esc_html__( 'Color', 'primary-addon-for-elementor' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .napae-table td a' => 'color: {{VALUE}};',
					],
				]
			);
			$this->end_controls_tab();  // end:Normal tab
			$this->start_controls_tab(
				'link_hover',
				[
					'label' => esc_html__( 'Hover', 'primary-addon-for-elementor' ),
				]
			);
			$this->add_control(
				'link_hover_color',
				[
					'label' => esc_html__( 'Color', 'primary-addon-for-elementor' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .napae-table td a:hover' => 'color: {{VALUE}};',
					],
				]
			);
			$this->end_controls_tab();  // end:Hover tab
		$this->end_controls_tabs(); // end tabs
		$this->end_controls_section();// end: Section

		// Icon
		$this->start_controls_section(
			'section_icon_style',
			[
				'label' => esc_html__( 'Icon', 'primary-addon-for-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_responsive_control(
			'ico_size',
			[
				'label' => esc_html__( 'Size', 'primary-addon-for-elementor' ),
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
					'{{WRAPPER}} .napae-table td i' => 'font-size: {{SIZE}}{{UNIT}};',
				],
			]
		);
		$this->add_control(
			'icon_color',
			[
				'label' => esc_html__( 'Color', 'primary-addon-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .napae-table td i' => 'color: {{VALUE}};',
				],
			]
		);
		$this->end_controls_section();// end: Section

		// Button
		$this->start_controls_section(
			'section_btn_style',
			[
				'label' => esc_html__( 'Button', 'primary-addon-for-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_control(
			'btn_border_radius',
			[
				'label' => __( 'Border Radius', 'primary-addon-for-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .napae-btn, {{WRAPPER}} .napae-btn:after' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_control(
			'btn_padding',
			[
				'label' => __( 'Padding', 'primary-addon-for-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .napae-btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_responsive_control(
			'btn_width',
			[
				'label' => esc_html__( 'Button Width', 'primary-addon-for-elementor' ),
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
					'{{WRAPPER}} .napae-btn' => 'min-width:{{SIZE}}{{UNIT}};',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'label' => esc_html__( 'Typography', 'primary-addon-for-elementor' ),
				'name' => 'btn_typography',
				'selector' => '{{WRAPPER}} .napae-btn',
			]
		);
		$this->start_controls_tabs( 'btn_style' );
			$this->start_controls_tab(
				'btn_normal',
				[
					'label' => esc_html__( 'Normal', 'primary-addon-for-elementor' ),
				]
			);
			$this->add_control(
				'btn_color',
				[
					'label' => esc_html__( 'Color', 'primary-addon-for-elementor' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .napae-btn' => 'color: {{VALUE}};',
					],
				]
			);
			$this->add_control(
				'btn_bg_color',
				[
					'label' => esc_html__( 'Background Color', 'primary-addon-for-elementor' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .napae-btn' => 'background-color: {{VALUE}};',
					],
				]
			);
			$this->add_group_control(
				Group_Control_Border::get_type(),
				[
					'name' => 'btn_border',
					'label' => esc_html__( 'Border', 'primary-addon-for-elementor' ),
					'selector' => '{{WRAPPER}} .napae-btn',
				]
			);
			$this->add_group_control(
				Group_Control_Box_Shadow::get_type(),
				[
					'name' => 'btn_shadow',
					'label' => esc_html__( 'Button Shadow', 'primary-addon-for-elementor' ),
					'selector' => '{{WRAPPER}} .napae-btn:after',
				]
			);
			$this->end_controls_tab();  // end:Normal tab
			$this->start_controls_tab(
				'btn_hover',
				[
					'label' => esc_html__( 'Hover', 'primary-addon-for-elementor' ),
				]
			);
			$this->add_control(
				'btn_hover_color',
				[
					'label' => esc_html__( 'Color', 'primary-addon-for-elementor' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .napae-btn:hover' => 'color: {{VALUE}};',
					],
				]
			);
			$this->add_control(
				'btn_bg_hover_color',
				[
					'label' => esc_html__( 'Background Color', 'primary-addon-for-elementor' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .napae-btn:hover' => 'background-color: {{VALUE}};',
					],
				]
			);
			$this->add_group_control(
				Group_Control_Border::get_type(),
				[
					'name' => 'btn_hover_border',
					'label' => esc_html__( 'Border', 'primary-addon-for-elementor' ),
					'selector' => '{{WRAPPER}} .napae-btn:hover',
				]
			);
			$this->add_group_control(
				Group_Control_Box_Shadow::get_type(),
				[
					'name' => 'btn_hover_shadow',
					'label' => esc_html__( 'Button Shadow', 'primary-addon-for-elementor' ),
					'selector' => '{{WRAPPER}} .napae-btn:hover:after',
				]
			);
			$this->end_controls_tab();  // end:Hover tab
		$this->end_controls_tabs(); // end tabs
		$this->end_controls_section();// end: Section

	}

	/**
	 * Render Table widget output on the frontend.
	 * Written in PHP and used to generate the final HTML.
	*/
	protected function render() {
		$settings = $this->get_settings_for_display();
		$tableItems_title = !empty( $settings['tableItems_title'] ) ? $settings['tableItems_title'] : [];
		$tableItems_row = !empty( $settings['tableItems_row'] ) ? $settings['tableItems_row'] : [];

	  $output = '<div class="napae-responsive-table">
						    <table class="napae-table">
						      <thead>
						        <tr>';
						        // Group Param Output
										if ( is_array( $tableItems_title ) && !empty( $tableItems_title ) ){
										  foreach ( $tableItems_title as $each_title ) {
												$table_title = $each_title['table_title'] ? $each_title['table_title'] : '';
											  $output .= '<th>'.$table_title.'</th>';
										  }
										}
       	$output .= '</tr>
						      </thead>
						      <tbody>';
						      	// Group Param Output
										if ( is_array( $tableItems_row ) && !empty( $tableItems_row ) ){
										  foreach ( $tableItems_row as $each_row ) {
											$text_style1 = $each_row['text_style1'] ? $each_row['text_style1'] : '';
											$text_style2 = $each_row['text_style2'] ? $each_row['text_style2'] : '';
											$text_style3 = $each_row['text_style3'] ? $each_row['text_style3'] : '';
											$text_style4 = $each_row['text_style4'] ? $each_row['text_style4'] : '';
											$text_style5 = $each_row['text_style5'] ? $each_row['text_style5'] : '';

											$icon1 = $each_row['icon1'] ? $each_row['icon1'] : '';
											$icon1 = $icon1 ? '<i class="'.esc_attr($icon1) .'" aria-hidden="true"></i>' : '';
											$icon2 = $each_row['icon2'] ? $each_row['icon2'] : '';
											$icon2 = $icon2 ? '<i class="'.esc_attr($icon2) .'" aria-hidden="true"></i>' : '';
											$icon3 = $each_row['icon3'] ? $each_row['icon3'] : '';
											$icon3 = $icon3 ? '<i class="'.esc_attr($icon3) .'" aria-hidden="true"></i>' : '';
											$icon4 = $each_row['icon4'] ? $each_row['icon4'] : '';
											$icon4 = $icon4 ? '<i class="'.esc_attr($icon4) .'" aria-hidden="true"></i>' : '';
											$icon5 = $each_row['icon5'] ? $each_row['icon5'] : '';
											$icon5 = $icon5 ? '<i class="'.esc_attr($icon5) .'" aria-hidden="true"></i>' : '';

											$row_text1 = $each_row['row_text1'] ? $each_row['row_text1'] : '';
											$row_text2 = $each_row['row_text2'] ? $each_row['row_text2'] : '';
											$row_text3 = $each_row['row_text3'] ? $each_row['row_text3'] : '';
											$row_text4 = $each_row['row_text4'] ? $each_row['row_text4'] : '';
											$row_text5 = $each_row['row_text5'] ? $each_row['row_text5'] : '';

											$text_link1 = !empty( $each_row['text_link1']['url'] ) ? $each_row['text_link1']['url'] : '';
											$text_link1_external = !empty( $each_row['text_link1']['is_external'] ) ? 'target="_blank"' : '';
											$text_link1_nofollow = !empty( $each_row['text_link1']['nofollow'] ) ? 'rel="nofollow"' : '';
											$text_link1_attr = !empty( $text_link1 ) ?  $text_link1_external.' '.$text_link1_nofollow : '';

  										$button1 = !empty( $text_link1 ) ? '<a href="'.esc_url($text_link1).'" '.$text_link1_attr.' class="napae-btn">'.esc_html($row_text1).'</a>' : '';

											$text_link2 = !empty( $each_row['text_link2']['url'] ) ? $each_row['text_link2']['url'] : '';
											$text_link2_external = !empty( $each_row['text_link2']['is_external'] ) ? 'target="_blank"' : '';
											$text_link2_nofollow = !empty( $each_row['text_link2']['nofollow'] ) ? 'rel="nofollow"' : '';
											$text_link2_attr = !empty( $text_link2 ) ?  $text_link2_external.' '.$text_link2_nofollow : '';

  										$button2 = !empty( $text_link2 ) ? '<a href="'.esc_url($text_link2).'" '.$text_link2_attr.' class="napae-btn">'.esc_html($row_text2).'</a>' : '';

											$text_link3 = !empty( $each_row['text_link3']['url'] ) ? $each_row['text_link3']['url'] : '';
											$text_link3_external = !empty( $each_row['text_link3']['is_external'] ) ? 'target="_blank"' : '';
											$text_link3_nofollow = !empty( $each_row['text_link3']['nofollow'] ) ? 'rel="nofollow"' : '';
											$text_link3_attr = !empty( $text_link3 ) ?  $text_link3_external.' '.$text_link3_nofollow : '';

  										$button3 = !empty( $text_link3 ) ? '<a href="'.esc_url($text_link3).'" '.$text_link3_attr.' class="napae-btn">'.esc_html($row_text3).'</a>' : '';

											$text_link4 = !empty( $each_row['text_link4']['url'] ) ? $each_row['text_link4']['url'] : '';
											$text_link4_external = !empty( $each_row['text_link4']['is_external'] ) ? 'target="_blank"' : '';
											$text_link4_nofollow = !empty( $each_row['text_link4']['nofollow'] ) ? 'rel="nofollow"' : '';
											$text_link4_attr = !empty( $text_link4 ) ?  $text_link4_external.' '.$text_link4_nofollow : '';

  										$button4 = !empty( $text_link4 ) ? '<a href="'.esc_url($text_link4).'" '.$text_link4_attr.' class="napae-btn">'.esc_html($row_text4).'</a>' : '';

											$text_link5 = !empty( $each_row['text_link5']['url'] ) ? $each_row['text_link5']['url'] : '';
											$text_link5_external = !empty( $each_row['text_link5']['is_external'] ) ? 'target="_blank"' : '';
											$text_link5_nofollow = !empty( $each_row['text_link5']['nofollow'] ) ? 'rel="nofollow"' : '';
											$text_link5_attr = !empty( $text_link5 ) ?  $text_link5_external.' '.$text_link5_nofollow : '';

  										$button5 = !empty( $text_link5 ) ? '<a href="'.esc_url($text_link5).'" '.$text_link5_attr.' class="napae-btn">'.esc_html($row_text5).'</a>' : '';

  										if ($text_style1 === 'icon') {
												$out1 = $icon1 ? '<td>'.$icon1.'</td>' : '';
  										} elseif ($text_style1 === 'button') {
												$out1 = $button1 ? '<td>'.$button1.'</td>' : '';
  										} else {
												$out1 = $row_text1 ? '<td>'.$row_text1.'</td>' : '';
  										}
  										if ($text_style2 === 'icon') {
												$out2 = $icon2 ? '<td>'.$icon2.'</td>' : '';
  										} elseif ($text_style2 === 'button') {
												$out2 = $button2 ? '<td>'.$button2.'</td>' : '';
  										} else {
												$out2 = $row_text2 ? '<td>'.$row_text2.'</td>' : '';
  										}
  										if ($text_style3 === 'icon') {
												$out3 = $icon3 ? '<td>'.$icon3.'</td>' : '';
  										} elseif ($text_style3 === 'button') {
												$out3 = $button3 ? '<td>'.$button3.'</td>' : '';
  										} else {
												$out3 = $row_text3 ? '<td>'.$row_text3.'</td>' : '';
  										}
  										if ($text_style4 === 'icon') {
												$out4 = $icon4 ? '<td>'.$icon4.'</td>' : '';
  										} elseif ($text_style4 === 'button') {
												$out4 = $button4 ? '<td>'.$button4.'</td>' : '';
  										} else {
												$out4 = $row_text4 ? '<td>'.$row_text4.'</td>' : '';
  										}
  										if ($text_style5 === 'icon') {
												$out5 = $icon5 ? '<td>'.$icon5.'</td>' : '';
  										} elseif ($text_style5 === 'button') {
												$out5 = $button5 ? '<td>'.$button5.'</td>' : '';
  										} else {
												$out5 = $row_text5 ? '<td>'.$row_text5.'</td>' : '';
  										}

											  $output .= '<tr>'.$out1.$out2.$out3.$out4.$out5.'</tr>';
										  }
										}
    	$output .= '</tbody>
						    </table>
						  </div>';

		echo $output;

	}

}
Plugin::instance()->widgets_manager->register_widget_type( new Primary_Addon_Table() );

} // enable & disable
