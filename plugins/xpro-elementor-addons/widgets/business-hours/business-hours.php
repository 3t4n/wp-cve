<?php

namespace XproElementorAddons\Widget;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;
use Elementor\Repeater;
use Elementor\Widget_Base;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Xpro Elementor Addons
 *
 * Elementor widget.
 *
 * @since 1.0.0
 */
class Business_Hours extends Widget_Base {


	/**
	 * Get widget name.
	 *
	 * Retrieve image widget name.
	 *
	 * @return string Widget name.
	 * @since 1.0.0
	 * @access public
	 *
	 */
	public function get_name() {
		return 'xpro-business-hours';
	}

	/**
	 * Get widget title.
	 *
	 * Retrieve image widget title.
	 *
	 * @return string Widget title.
	 * @since 1.0.0
	 * @access public
	 *
	 */
	public function get_title() {
		return __( 'Business Hours', 'xpro-elementor-addons' );
	}

	/**
	 * Get widget icon.
	 *
	 * Retrieve image widget icon.
	 *
	 * @return string Widget icon.
	 * @since 1.0.0
	 * @access public
	 *
	 */
	public function get_icon() {
		return 'xi-business-hours xpro-widget-label';
	}

	/**
	 * Get widget categories.
	 *
	 * Retrieve the list of categories the image widget belongs to.
	 *
	 * Used to determine where to display the widget in the editor.
	 *
	 * @return array Widget categories.
	 * @since 1.0.0
	 * @access public
	 *
	 */
	public function get_categories() {
		return array( 'xpro-widgets' );
	}

	/**
	 * Get widget keywords.
	 *
	 * Retrieve the list of keywords the widget belongs to.
	 *
	 * @return array Widget keywords.
	 * @since 1.0.0
	 * @access public
	 *
	 */
	public function get_keywords() {
		return array( 'xpro', 'business', 'hours' );
	}

	/**
	 * Retrieve the list of style the widget depended on.
	 *
	 * Used to set style dependencies required to run the widget.
	 *
	 * @return array Widget style dependencies.
	 * @since 1.0.0
	 *
	 * @access public
	 *
	 */
	protected function register_controls() {

		$this->start_controls_section(
			'section_general',
			array(
				'label' => __( 'General', 'xpro-elementor-addons' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'show_title',
			array(
				'label'        => __( 'Heading', 'xpro-elementor-addons' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Show', 'xpro-elementor-addons' ),
				'label_off'    => __( 'Hide', 'xpro-elementor-addons' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->add_control(
			'title',
			array(
				'label'       => __( 'Title', 'xpro-elementor-addons' ),
				'type'        => Controls_Manager::TEXT,
				'label_block' => true,
				'default'     => __( 'Business Hours', 'xpro-elementor-addons' ),
				'dynamic'     => array(
					'active' => true,
				),
				'condition'   => array(
					'show_title' => 'yes',
				),
			)
		);

		$this->add_control(
			'sub_title',
			array(
				'label'       => __( 'Sub Title', 'xpro-elementor-addons' ),
				'type'        => Controls_Manager::TEXT,
				'label_block' => true,
				'default'     => __( 'Come Visit us Today!', 'xpro-elementor-addons' ),
				'dynamic'     => array(
					'active' => true,
				),
				'condition'   => array(
					'show_title' => 'yes',
				),
			)
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'business_day',
			array(
				'label'       => __( 'Day', 'xpro-elementor-addons' ),
				'type'        => Controls_Manager::TEXT,
				'label_block' => true,
				'default'     => __( 'Business Hours', 'xpro-elementor-addons' ),
				'dynamic'     => array(
					'active' => true,
				),
			)
		);

		$repeater->add_control(
			'business_time',
			array(
				'label'       => __( 'Time', 'xpro-elementor-addons' ),
				'type'        => Controls_Manager::TEXT,
				'label_block' => true,
				'default'     => __( '10:00AM - 07:00PM', 'xpro-elementor-addons' ),
				'dynamic'     => array(
					'active' => true,
				),
			)
		);

		$repeater->add_control(
			'inline_style',
			array(
				'label'        => __( 'Inline Style', 'xpro-elementor-addons' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Show', 'xpro-elementor-addons' ),
				'label_off'    => __( 'Hide', 'xpro-elementor-addons' ),
				'return_value' => 'yes',
			)
		);

		$repeater->start_controls_tabs(
			'row_style_item',
			array(
				'condition' => array(
					'inline_style' => 'yes',
				),
			)
		);

		$repeater->start_controls_tab(
			'item_normal_style',
			array(
				'label' => __( 'Normal', 'xpro-elementor-addons' ),
			)
		);

		$repeater->add_control(
			'inline_item_day_color',
			array(
				'label'     => __( 'Day Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ul > {{CURRENT_ITEM}}.xpro-business-hour-item .xpro-business-hour-day' => 'color: {{VALUE}}',
				),
				'condition' => array(
					'inline_style' => 'yes',
				),
			)
		);

		$repeater->add_control(
			'inline_item_time_color',
			array(
				'label'     => __( 'Time Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ul > {{CURRENT_ITEM}}.xpro-business-hour-item .xpro-business-hour-time' => 'color: {{VALUE}}',
				),
				'condition' => array(
					'inline_style' => 'yes',
				),
			)
		);

		$repeater->add_control(
			'inline_item_bg_color',
			array(
				'label'     => __( 'Background Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ul > {{CURRENT_ITEM}}.xpro-business-hour-item' => 'background-color: {{VALUE}}',
				),
				'condition' => array(
					'inline_style' => 'yes',
				),
			)
		);

		$repeater->add_control(
			'inline_separator_color',
			array(
				'label'     => __( 'Separator Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ul > {{CURRENT_ITEM}}.xpro-business-hour-item .xpro-business-hour-separator-horizontal > span' => 'border-bottom-color: {{VALUE}}',
					'{{WRAPPER}} ul > {{CURRENT_ITEM}}.xpro-business-hour-item .xpro-business-hour-separator-vertical'          => 'border-right-color: {{VALUE}}',
				),
				'condition' => array(
					'inline_style' => 'yes',
				),
			)
		);

		$repeater->end_controls_tab();

		$repeater->start_controls_tab(
			'item_hover_style',
			array(
				'label' => __( 'Hover', 'xpro-elementor-addons' ),
			)
		);

		$repeater->add_control(
			'inline_item_day_hcolor',
			array(
				'label'     => __( 'Day Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ul > {{CURRENT_ITEM}}.xpro-business-hour-item:hover .xpro-business-hour-day' => 'color: {{VALUE}}',
				),
				'condition' => array(
					'inline_style' => 'yes',
				),
			)
		);

		$repeater->add_control(
			'inline_item_time_hcolor',
			array(
				'label'     => __( 'Time Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ul > {{CURRENT_ITEM}}.xpro-business-hour-item:hover .xpro-business-hour-time' => 'color: {{VALUE}}',
				),
				'condition' => array(
					'inline_style' => 'yes',
				),
			)
		);

		$repeater->add_control(
			'inline_item_bg',
			array(
				'label'     => __( 'Background Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ul > {{CURRENT_ITEM}}.xpro-business-hour-item:hover' => 'background-color: {{VALUE}}',
				),
				'condition' => array(
					'inline_style' => 'yes',
				),
			)
		);

		$repeater->end_controls_tab();

		$repeater->end_controls_tabs();

		$this->add_control(
			'business_item',
			array(
				'type'        => Controls_Manager::REPEATER,
				'title_field' => '{{{ business_day }}}',
				'fields'      => $repeater->get_controls(),
				'separator'   => 'before',
				'default'     => array(
					array(
						'business_day'  => __( 'Monday', 'xpro-elementor-addons' ),
						'business_time' => __( '10:00AM - 07:00PM', 'xpro-elementor-addons' ),
					),
					array(
						'business_day'  => __( 'Tuesday', 'xpro-elementor-addons' ),
						'business_time' => __( '10:00AM - 07:00PM', 'xpro-elementor-addons' ),
					),
					array(
						'business_day'  => __( 'Wednesday', 'xpro-elementor-addons' ),
						'business_time' => __( '10:00AM - 07:00PM', 'xpro-elementor-addons' ),
					),
					array(
						'business_day'  => __( 'Thursday', 'xpro-elementor-addons' ),
						'business_time' => __( '10:00AM - 07:00PM', 'xpro-elementor-addons' ),
					),
					array(
						'business_day'  => __( 'Friday', 'xpro-elementor-addons' ),
						'business_time' => __( '10:00AM - 07:00PM', 'xpro-elementor-addons' ),
					),
					array(
						'business_day'  => __( 'Saturday', 'xpro-elementor-addons' ),
						'business_time' => __( 'Closed', 'xpro-elementor-addons' ),
					),
					array(
						'business_day'  => __( 'Sunday', 'xpro-elementor-addons' ),
						'business_time' => __( 'Closed', 'xpro-elementor-addons' ),
					),
				),
			)
		);

		$this->end_controls_section();

		//Styling Tab
		$this->start_controls_section(
			'section_style_title',
			array(
				'label'     => __( 'Heading', 'xpro-elementor-addons' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'show_title' => 'yes',
				),
			)
		);

		$this->add_responsive_control(
			'heading_align',
			array(
				'label'     => __( 'Alignment', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => array(
					'left'   => array(
						'title' => __( 'Left', 'xpro-elementor-addons' ),
						'icon'  => 'eicon-h-align-left',
					),
					'center' => array(
						'title' => __( 'Center', 'xpro-elementor-addons' ),
						'icon'  => 'eicon-h-align-center',
					),
					'right'  => array(
						'title' => __( 'Right', 'xpro-elementor-addons' ),
						'icon'  => 'eicon-h-align-right',
					),
				),

				'selectors' => array(
					'{{WRAPPER}} .xpro-business-hour-header' => 'text-align: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'heading_background',
				'label'    => __( 'Background', 'xpro-elementor-addons' ),
				'types'    => array( 'classic', 'gradient' ),
				'exclude'  => array( 'image' ),
				'selector' => '{{WRAPPER}} .xpro-business-hour-header',
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'heading_border',
				'label'    => __( 'Border', 'xpro-elementor-addons' ),
				'selector' => '{{WRAPPER}} .xpro-business-hour-header',
			)
		);

		$this->add_control(
			'heading_border_radius',
			array(
				'label'      => __( 'Border Radius', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .xpro-business-hour-header' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'heading_padding',
			array(
				'label'      => __( 'Padding', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .xpro-business-hour-header' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'title_options',
			array(
				'label'     => __( 'Title', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => array(
					'title!' => '',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'      => 'title_typography',
				'label'     => __( 'Typography', 'xpro-elementor-addons' ),
				'selector'  => '{{WRAPPER}} .xpro-business-hour-title',
				'condition' => array(
					'title!' => '',
				),
			)
		);

		$this->add_control(
			'title_color',
			array(
				'label'     => __( 'Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .xpro-business-hour-title' => 'color: {{VALUE}}',
				),
				'condition' => array(
					'title!' => '',
				),
			)
		);

		$this->add_control(
			'title_margin',
			array(
				'label'      => __( 'Margin', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .xpro-business-hour-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'condition'  => array(
					'title!' => '',
				),
			)
		);

		$this->add_control(
			'sub_title_options',
			array(
				'label'     => __( 'Sub Title', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => array(
					'sub_title!' => '',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'      => 'sub_title_typography',
				'label'     => __( 'Typography', 'xpro-elementor-addons' ),
				'selector'  => '{{WRAPPER}} .xpro-business-hour-sub-title',
				'condition' => array(
					'sub_title!' => '',
				),
			)
		);

		$this->add_control(
			'sub_title_color',
			array(
				'label'     => __( 'Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .xpro-business-hour-sub-title' => 'color: {{VALUE}}',
				),
				'condition' => array(
					'sub_title!' => '',
				),
			)
		);

		$this->add_control(
			'sub_title_margin',
			array(
				'label'      => __( 'Margin', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .xpro-business-hour-sub-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'condition'  => array(
					'sub_title!' => '',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_item',
			array(
				'label' => __( 'List Item', 'xpro-elementor-addons' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_responsive_control(
			'item_align',
			array(
				'label'        => __( 'Alignment', 'xpro-elementor-addons' ),
				'type'         => Controls_Manager::CHOOSE,
				'options'      => array(
					'left'  => array(
						'title' => __( 'Left', 'xpro-elementor-addons' ),
						'icon'  => 'eicon-h-align-left',
					),
					'right' => array(
						'title' => __( 'Right', 'xpro-elementor-addons' ),
						'icon'  => 'eicon-h-align-right',
					),
				),
				'default'      => 'row',
				'prefix_class' => 'elementor%s-align-',
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'item_typography',
				'label'    => __( 'Typography', 'xpro-elementor-addons' ),
				'selector' => '{{WRAPPER}} .xpro-business-hour-day,{{WRAPPER}} .xpro-business-hour-time',
			)
		);

		$this->add_responsive_control(
			'item_content_width',
			array(
				'label'      => __( 'Content Width', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'selectors'  => array(
					'{{WRAPPER}} .xpro-business-hour-day,{{WRAPPER}} .xpro-business-hour-time' => 'width: {{SIZE}}{{UNIT}};',
				),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 500,
					),
				),
				'condition'  => array(
					'separator_layout' => 'horizontal',
				),
			)
		);

		$this->start_controls_tabs(
			'row_style_item'
		);

		$this->start_controls_tab(
			'item_normal_style',
			array(
				'label' => __( 'Normal', 'xpro-elementor-addons' ),
			)
		);

		$this->add_control(
			'item_day_color_odd',
			array(
				'label'     => __( 'Day Color(Odd)', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .xpro-business-hour-item:nth-child(odd) .xpro-business-hour-day' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'item_day_color_even',
			array(
				'label'     => __( 'Day Color(Even)', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .xpro-business-hour-item:nth-child(even) .xpro-business-hour-day' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'item_time_color_odd',
			array(
				'label'     => __( 'Time Color(Odd)', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .xpro-business-hour-item:nth-child(odd) .xpro-business-hour-time' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'item_time_color_even',
			array(
				'label'     => __( 'Time Color(Even)', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .xpro-business-hour-item:nth-child(even) .xpro-business-hour-time' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'item_bg_color_odd',
			array(
				'label'     => __( 'Background Color(Odd)', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .xpro-business-hour-item:nth-child(odd)' => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'item_bg_color_even',
			array(
				'label'     => __( 'Background Color(Even)', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .xpro-business-hour-item:nth-child(even)' => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'item_hover_style',
			array(
				'label' => __( 'Hover', 'xpro-elementor-addons' ),
			)
		);

		$this->add_control(
			'item_day_hcolor_odd',
			array(
				'label'     => __( 'Day Color(Odd)', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .xpro-business-hour-item:nth-child(odd):hover .xpro-business-hour-day' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'item_day_hcolor_even',
			array(
				'label'     => __( 'Day Color(Even)', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .xpro-business-hour-item:nth-child(even):hover .xpro-business-hour-day' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'item_time_hcolor_odd',
			array(
				'label'     => __( 'Time Color(Odd)', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .xpro-business-hour-item:nth-child(odd):hover .xpro-business-hour-time' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'item_time_hcolor_even',
			array(
				'label'     => __( 'Time Color(Even)', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .xpro-business-hour-item:nth-child(even):hover .xpro-business-hour-time' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'item_bg_hcolor_odd',
			array(
				'label'     => __( 'Background Color(Odd)', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .xpro-business-hour-item:nth-child(odd):hover' => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'item_bg_hcolor_even',
			array(
				'label'     => __( 'Background Color(Even)', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .xpro-business-hour-item:nth-child(even):hover' => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'item_border_style',
			array(
				'label'     => __( 'Border', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'dashed',
				'separator' => 'before',
				'options'   => array(
					'none'   => __( 'None', 'xpro-elementor-addons' ),
					'solid'  => __( 'Solid', 'xpro-elementor-addons' ),
					'dashed' => __( 'Dashed', 'xpro-elementor-addons' ),
					'dotted' => __( 'Dotted', 'xpro-elementor-addons' ),
					'double' => __( 'Double', 'xpro-elementor-addons' ),
				),
				'selectors' => array(
					'{{WRAPPER}} .xpro-business-hour-item' => 'border-bottom-style: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'item_border_width',
			array(
				'label'      => __( 'Size', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'default'    => array(
					'size' => 1,
				),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 10,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .xpro-business-hour-item' => 'border-bottom-width: {{SIZE}}{{UNIT}};',
				),
				'condition'  => array(
					'item_border_style!' => 'none',
				),
			)
		);

		$this->add_control(
			'item_border_color',
			array(
				'label'     => __( 'Border Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .xpro-business-hour-item' => 'border-bottom-color: {{VALUE}}',
				),
				'condition' => array(
					'item_border_style!' => 'none',
				),
			)
		);

		$this->add_control(
			'item_border_radius',
			array(
				'label'      => __( 'Border Radius', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .xpro-business-hour-item' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'item_padding',
			array(
				'label'      => __( 'Padding', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .xpro-business-hour-item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_separator',
			array(
				'label' => __( 'Separator', 'xpro-elementor-addons' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'separator_layout',
			array(
				'label'   => __( 'Separator Layout', 'xpro-elementor-addons' ),
				'type'    => Controls_Manager::CHOOSE,
				'default' => 'none',
				'options' => array(
					'none'       => array(
						'title' => __( 'None', 'xpro-elementor-addons' ),
						'icon'  => 'eicon-ban',
					),
					'horizontal' => array(
						'title' => __( 'Horizontal', 'xpro-elementor-addons' ),
						'icon'  => 'eicon-ellipsis-h',
					),
					'vertical'   => array(
						'title' => __( 'Vertical', 'xpro-elementor-addons' ),
						'icon'  => 'eicon-editor-list-ul',
					),
				),
			)
		);

		$this->add_control(
			'separator_width',
			array(
				'label'      => __( 'Size', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min'  => 0,
						'max'  => 100,
						'step' => 1,
					),
				),
				'default'    => array(
					'size' => 60,
				),
				'selectors'  => array(
					'{{WRAPPER}} .xpro-business-hour-separator-vertical,{{WRAPPER}} .xpro-business-hour-item' => 'height: {{SIZE}}{{UNIT}};',
				),
				'condition'  => array(
					'separator_layout' => 'vertical',
				),
			)
		);

		$this->add_control(
			'separator_color_odd',
			array(
				'label'     => __( 'Color(Odd)', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .xpro-business-hour-item:nth-child(odd) .xpro-business-hour-separator-horizontal > span' => 'border-bottom-color: {{VALUE}}',
					'{{WRAPPER}} .xpro-business-hour-item:nth-child(odd) .xpro-business-hour-separator-vertical'          => 'border-right-color: {{VALUE}}',
				),
				'condition' => array(
					'separator_layout!' => 'none',
				),
			)
		);

		$this->add_control(
			'separator_color_even',
			array(
				'label'     => __( 'Color(Even)', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .xpro-business-hour-item:nth-child(even) .xpro-business-hour-separator-horizontal > span' => 'border-bottom-color: {{VALUE}}',
					'{{WRAPPER}} .xpro-business-hour-item:nth-child(even) .xpro-business-hour-separator-vertical'          => 'border-right-color: {{VALUE}}',
				),
				'condition' => array(
					'separator_layout!' => 'none',
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Render image widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function render() {

		$settings = $this->get_settings_for_display();

		require XPRO_ELEMENTOR_ADDONS_WIDGET . 'business-hours/layout/frontend.php';
	}
}
