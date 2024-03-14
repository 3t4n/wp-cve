<?php

namespace XproElementorAddons\Widget;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Group_Control_Typography;
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
class Step_Flow extends Widget_Base {

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
		return 'xpro-step-flow';
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
		return __( 'Step Flow', 'xpro-elementor-addons' );
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
		return 'xi-step-flow xpro-widget-label';
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
		return array( 'step', 'steps', 'flow', 'flows' );
	}

	/**
	 * Register widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function register_controls() {

		$this->start_controls_section(
			'section_news_ticker',
			array(
				'label' => __( 'General', 'xpro-elementor-addons' ),
			)
		);

		$this->add_control(
			'step_flow_icon',
			array(
				'show_label'  => false,
				'type'        => Controls_Manager::ICONS,
				'label_block' => true,
				'default'     => array(
					'value'   => 'fas fa-fingerprint',
					'library' => 'fa-solid',
				),
			)
		);

		$this->add_control(
			'step_flow_title',
			array(
				'label'       => __( 'Title', 'xpro-elementor-addons' ),
				'type'        => Controls_Manager::TEXT,
				'label_block' => true,
				'default'     => __( 'Step Heading', 'xpro-elementor-addons' ),
				'placeholder' => __( 'Type Step Flow Title', 'xpro-elementor-addons' ),
				'dynamic'     => array(
					'active' => true,
				),
			)
		);

		$this->add_control(
			'step_flow_description',
			array(
				'label'       => esc_html__( 'Description', 'xpro-elementor-addons' ),
				'type'        => Controls_Manager::WYSIWYG,
				'rows'        => 5,
				'placeholder' => esc_html__( 'Type your description here', 'xpro-elementor-addons' ),
				'default'     => __( 'Lorem Ipsum is simply dummy text of the printing and industry.', 'xpro-elementor-addons' ),
				'label_block' => true,
			)
		);

		$this->add_control(
			'step_flow_badge_text',
			array(
				'label'       => __( 'Badge Text', 'xpro-elementor-addons' ),
				'type'        => Controls_Manager::TEXT,
				'placeholder' => __( 'Type Icon Badge Text', 'xpro-elementor-addons' ),
				'default'     => __( '01', 'xpro-elementor-addons' ),
				'dynamic'     => array(
					'active' => true,
				),
			)
		);

		$this->add_control(
			'step_flow_separator',
			array(
				'label'        => __( 'Separator', 'xpro-elementor-addons' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => __( 'yes', 'xpro-elementor-addons' ),
				'return_value' => 'yes',
			)
		);

		$this->add_responsive_control(
			'general_align',
			array(
				'label'        => __( 'Alignment', 'xpro-elementor-addons' ),
				'type'         => Controls_Manager::CHOOSE,
				'separator'    => 'before',
				'options'      => array(
					'left'   => array(
						'title' => __( 'Left', 'xpro-elementor-addons' ),
						'icon'  => 'eicon-text-align-left',
					),
					'center' => array(
						'title' => __( 'Center', 'xpro-elementor-addons' ),
						'icon'  => 'eicon-text-align-center',
					),
					'right'  => array(
						'title' => __( 'Right', 'xpro-elementor-addons' ),
						'icon'  => 'eicon-text-align-right',
					),
				),
				'prefix_class' => 'xpro-content-align%s',
				'default'      => __( 'center', 'xpro-elementor-addons' ),
				'selectors'    => array(
					'{{WRAPPER}} .xpro-step-flow-wrapper' => 'text-align: {{VALUE}};',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'step_flow_icon_style',
			array(
				'label' => __( 'Media', 'xpro-elementor-addons' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_responsive_control(
			'icon_size',
			array(
				'label'      => __( 'Media Size', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'default'    => array(
					'unit' => 'px',
					'size' => 30,

				),
				'selectors'  => array(
					'{{WRAPPER}}'                          => '--xpro-step-flow-icon-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .xpro-step-flow-icon > i' => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .xpro-step-flow-icon > svg' => 'width: {{SIZE}}{{UNIT}}; height:auto;',
				),
			)
		);

		$this->add_responsive_control(
			'icon_box_icon',
			array(
				'label'      => __( 'Background Size', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 200,
					),
				),
				'default'    => array(
					'unit' => 'px',
					'size' => 50,

				),
				'selectors'  => array(
					'{{WRAPPER}}'                      => '--xpro-step-flow-icon-padding: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .xpro-step-flow-icon' => 'padding: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'icon_margin_bottom',
			array(
				'label'      => __( 'Bottom Spacing', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%' ),
				'range'      => array(
					'px' => array(
						'min'  => 0,
						'max'  => 100,
						'step' => 5,
					),
				),
				'default'    => array(
					'unit' => 'px',
					'size' => 20,
				),
				'selectors'  => array(
					'{{WRAPPER}} .xpro-step-flow-icon ' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'icon_color',
			array(
				'label'     => __( 'Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .xpro-step-flow-icon > i' => 'color: {{VALUE}}',
					'{{WRAPPER}} .xpro-step-flow-icon > svg' => 'fill: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'icon_content_bg',
				'label'    => __( 'Background', 'xpro-elementor-addons' ),
				'types'    => array( 'classic', 'gradient' ),
				'exclude'  => array( 'image' ),
				'selector' => '{{WRAPPER}} .xpro-step-flow-icon',
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'icon_border',
				'label'    => __( 'Border', 'xpro-elementor-addons' ),
				'selector' => '{{WRAPPER}} .xpro-step-flow-icon',
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'icon_box_shadow',
				'label'    => __( 'Box Shadow', 'xpro-elementor-addons' ),
				'selector' => '{{WRAPPER}} .xpro-step-flow-icon',
			)
		);

		$this->add_responsive_control(
			'icon_border_radius',
			array(
				'label'      => __( 'Border Radius', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .xpro-step-flow-icon' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_step_flow_title_style',
			array(
				'label'     => __( 'Title', 'xpro-elementor-addons' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'step_flow_title!' => '',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'title_typography',
				'label'    => __( 'Typography', 'xpro-elementor-addons' ),
				'selector' => '{{WRAPPER}} .xpro-step-flow-title',
			)
		);

		$this->add_control(
			'title_color',
			array(
				'label'     => __( 'Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .xpro-step-flow-title' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			array(
				'name'     => 'title_text_shadow',
				'label'    => __( 'Text Shadow', 'xpro-elementor-addons' ),
				'selector' => '{{WRAPPER}} .xpro-step-flow-title',
			)
		);

		$this->add_responsive_control(
			'title_margin',
			array(
				'label'      => __( 'Margin', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .xpro-step-flow-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_step_flow_description_style',
			array(
				'label'     => __( 'Description', 'xpro-elementor-addons' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'step_flow_description!' => '',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'description_typography',
				'label'    => __( 'Typography', 'xpro-elementor-addons' ),
				'selector' => '{{WRAPPER}} .xpro-step-flow-description, {{WRAPPER}} .xpro-step-flow-description > *',
			)
		);

		$this->add_control(
			'description_color',
			array(
				'label'     => __( 'Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .xpro-step-flow-description, {{WRAPPER}} .xpro-step-flow-description > *' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_responsive_control(
			'description_margin',
			array(
				'label'      => __( 'Margin', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .xpro-step-flow-description' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_step_flow_separator_style',
			array(
				'label'     => __( 'Separator', 'xpro-elementor-addons' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'step_flow_separator' => 'yes',
				),
			)
		);

		$this->add_control(
			'separator_layout_style',
			array(
				'label'   => __( 'Layout', 'xpro-elementor-addons' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'line',
				'options' => array(
					'line'       => __( 'Line', 'xpro-elementor-addons' ),
					'line-arrow' => __( 'Line Arrow', 'xpro-elementor-addons' ),
					'arrow'      => __( 'Arrow', 'xpro-elementor-addons' ),
					'circle'     => __( 'Circle', 'xpro-elementor-addons' ),
				),
			)
		);

		$this->add_control(
			'separator_border_type',
			array(
				'label'     => __( 'Border Type', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'solid',
				'options'   => array(
					'solid'  => __( 'Solid', 'xpro-elementor-addons' ),
					'dotted' => __( 'Dotted', 'xpro-elementor-addons' ),
					'dashed' => __( 'Dashed', 'xpro-elementor-addons' ),
				),
				'selectors' => array(
					'{{WRAPPER}} .xpro-step-flow-line,
					{{WRAPPER}} .xpro-step-flow-line-arrow,
					{{WRAPPER}} .xpro-step-flow-circle,
					{{WRAPPER}} .xpro-step-flow-arrow' => 'border-top-style: {{VALUE}}',
					'{{WRAPPER}} .xpro-step-flow-line-arrow::after,
					{{WRAPPER}} .xpro-step-flow-arrow::after' => 'border-top-style: {{VALUE}};border-right-style: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'separator_transform_toggle',
			array(
				'label'        => __( 'Transform', 'xpro-elementor-addons' ),
				'type'         => Controls_Manager::POPOVER_TOGGLE,
				'label_off'    => __( 'None', 'xpro-elementor-addons' ),
				'label_on'     => __( 'Custom', 'xpro-elementor-addons' ),
				'return_value' => 'yes',
			)
		);

		$this->start_popover();

		$this->add_responsive_control(
			'separator_offset_y',
			array(
				'label'      => __( 'Offset Top', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 1000,
					),
					'%'  => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'condition'  => array(
					'separator_transform_toggle' => 'yes',
				),
				'selectors'  => array(
					'{{WRAPPER}} .xpro-step-flow-line-arrow,
					{{WRAPPER}} .xpro-step-flow-arrow,
					{{WRAPPER}} .xpro-step-flow-circle,
					{{WRAPPER}} .xpro-step-flow-line' => 'top:{{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'separator_offset_x',
			array(
				'label'      => __( 'Offset Left', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 1000,
					),
					'%'  => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'default'    => array(
					'unit' => 'px',
					'size' => 20,
				),
				'condition'  => array(
					'separator_transform_toggle' => 'yes',
				),
				'selectors'  => array(
					'{{WRAPPER}} .xpro-step-flow-line-arrow,
					{{WRAPPER}} .xpro-step-flow-arrow,
					{{WRAPPER}} .xpro-step-flow-circle,
					{{WRAPPER}} .xpro-step-flow-line' => 'left: calc( 100% + {{SIZE}}{{UNIT}} );',
					'{{WRAPPER}}'                     => '--xpro-step-flow-direction-offset-x: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'separator_rotate',
			array(
				'label'          => __( 'Rotate', 'xpro-elementor-addons' ),
				'type'           => Controls_Manager::SLIDER,
				'size_units'     => array( 'deg' ),
				'default'        => array(
					'unit' => 'deg',
				),
				'tablet_default' => array(
					'unit' => 'deg',
				),
				'mobile_default' => array(
					'unit' => 'deg',
				),
				'range'          => array(
					'deg' => array(
						'min' => 0,
						'max' => 360,
					),
				),
				'condition'      => array(
					'separator_transform_toggle' => 'yes',
				),
				'selectors'      => array(
					'{{WRAPPER}}' => '--xpro-step-flow-direction-angle: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->end_popover();

		$this->add_responsive_control(
			'separator_width',
			array(
				'label'      => __( 'Width', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%' ),
				'range'      => array(
					'px' => array(
						'min'  => 0,
						'max'  => 500,
						'step' => 1,
					),
					'%'  => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'default'    => array(
					'unit' => 'px',
					'size' => 100,

				),
				'selectors'  => array(
					'{{WRAPPER}} .xpro-step-flow-line, 
					 {{WRAPPER}} .xpro-step-flow-circle,
					 {{WRAPPER}} .xpro-step-flow-line-arrow' => 'width: {{SIZE}}{{UNIT}};',
				),
				'condition'  => array(
					'separator_layout_style' => array( 'line', 'line-arrow', 'circle' ),
				),
			)
		);

		$this->add_responsive_control(
			'separator_size',
			array(
				'label'      => __( 'Thickness', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%' ),
				'range'      => array(
					'px' => array(
						'min'  => 0,
						'max'  => 10,
						'step' => 1,
					),
				),
				'default'    => array(
					'unit' => 'px',
					'size' => 2,
				),
				'selectors'  => array(
					'{{WRAPPER}} .xpro-step-flow-line'   => 'border-top-width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .xpro-step-flow-line-arrow' => 'border-top-width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .xpro-step-flow-line-arrow:after' => 'border-width: {{SIZE}}{{UNIT}}; width:calc(15px * {{SIZE}} / 2); height:calc(15px * {{SIZE}} / 2); top:calc(-17px * {{SIZE}} / 4); right:{{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .xpro-step-flow-arrow:after' => 'border-width: {{SIZE}}{{UNIT}}; width:calc(15px * {{SIZE}} / 2); height:calc(15px * {{SIZE}} / 2); top: calc(-15px * {{SIZE}} / 4);',
					'{{WRAPPER}} .xpro-step-flow-circle' => 'border-top-width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .xpro-step-flow-circle:after' => 'border-width: {{SIZE}}{{UNIT}}; width:calc(15px * {{SIZE}} / 2); height:calc(15px * {{SIZE}} / 2); top:calc(-2px * {{SIZE}} / 4);',
				),
			)
		);

		$this->add_responsive_control(
			'separator_margin-left',
			array(
				'label'      => __( 'Distance', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min'  => 0,
						'max'  => 100,
						'step' => 1,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .xpro-step-flow-line,
					 {{WRAPPER}} .xpro-step-flow-line-arrow,
					 {{WRAPPER}} .xpro-step-flow-arrow' => 'margin-left: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'separator_color',
			array(
				'label'     => __( 'Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .xpro-step-flow-line,
					 {{WRAPPER}} .xpro-step-flow-line-arrow,
					 {{WRAPPER}} .xpro-step-flow-line-arrow::after,
					 {{WRAPPER}} .xpro-step-flow-circle,
					 {{WRAPPER}} .xpro-step-flow-arrow::after' => 'border-color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'separator_circle_color',
			array(
				'label'     => __( 'Circle Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .xpro-step-flow-circle::after' => 'background-color: {{VALUE}}',
				),
				'condition' => array(
					'separator_layout_style' => 'circle',
				),
			)
		);

		$this->add_control(
			'separator_hide_on',
			array(
				'label'   => __( 'Hide On', 'xpro-elementor-addons' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'none',
				'options' => array(
					'none'   => __( 'None', 'xpro-elementor-addons' ),
					'tablet' => __( 'Tablet & Mobile', 'xpro-elementor-addons' ),
					'mobile' => __( 'Mobile Only', 'xpro-elementor-addons' ),
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_step_flow_badge_style',
			array(
				'label'     => __( 'Badge', 'xpro-elementor-addons' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'step_flow_badge_text!' => '',
				),
			)
		);

		$this->add_control(
			'badge_position',
			array(
				'label'   => __( 'Position', 'xpro-elementor-addons' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'top-left',
				'options' => array(
					'top-left'      => __( 'Top Left', 'xpro-elementor-addons' ),
					'top-center'    => __( 'Top Center', 'xpro-elementor-addons' ),
					'top-right'     => __( 'Top Right', 'xpro-elementor-addons' ),
					'middle-left'   => __( 'Middle Left', 'xpro-elementor-addons' ),
					'middle-center' => __( 'Middle Center', 'xpro-elementor-addons' ),
					'middle-right'  => __( 'Middle Right', 'xpro-elementor-addons' ),
					'bottom-left'   => __( 'Bottom Left', 'xpro-elementor-addons' ),
					'bottom-center' => __( 'Bottom Center', 'xpro-elementor-addons' ),
					'bottom-right'  => __( 'Bottom Right', 'xpro-elementor-addons' ),
				),
			)
		);

		$this->add_control(
			'badge_offset_toggle',
			array(
				'label'        => __( 'Offset', 'xpro-elementor-addons' ),
				'type'         => Controls_Manager::POPOVER_TOGGLE,
				'label_off'    => __( 'None', 'xpro-elementor-addons' ),
				'label_on'     => __( 'Custom', 'xpro-elementor-addons' ),
				'return_value' => 'yes',
			)
		);

		$this->start_popover();

		$this->add_responsive_control(
			'badge_offset_x',
			array(
				'label'      => __( 'Offset Left', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%' ),
				'default'    => array(
					'unit' => 'px',
				),
				'range'      => array(
					'px' => array(
						'min' => - 1000,
						'max' => 1000,
					),
					'%'  => array(
						'min' => - 100,
						'max' => 100,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .xpro-step-flow-badge.xpro-badge' => '--xpro-badge-translate-x: {{SIZE}}{{UNIT}};',
				),
				'condition'  => array(
					'badge_offset_toggle' => 'yes',
				),
			)
		);

		$this->add_responsive_control(
			'badge_offset_y',
			array(
				'label'      => __( 'Offset Top', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%' ),
				'default'    => array(
					'unit' => 'px',
				),
				'range'      => array(
					'px' => array(
						'min' => - 1000,
						'max' => 1000,
					),
					'%'  => array(
						'min' => - 100,
						'max' => 100,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .xpro-step-flow-badge.xpro-badge' => '--xpro-badge-translate-y: {{SIZE}}{{UNIT}};',
				),
				'condition'  => array(
					'badge_offset_toggle' => 'yes',
				),
			)
		);
		$this->end_popover();

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'badge_typography',
				'label'    => __( 'Typography', 'xpro-elementor-addons' ),
				'selector' => '{{WRAPPER}} .xpro-step-flow-badge',
			)
		);

		$this->add_control(
			'badge_color',
			array(
				'label'     => __( 'Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .xpro-step-flow-badge' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'badge_background',
				'label'    => __( 'Background', 'xpro-elementor-addons' ),
				'types'    => array( 'classic', 'gradient' ),
				'selector' => '{{WRAPPER}} .xpro-step-flow-badge',
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'badge_border',
				'label'    => __( 'Border', 'xpro-elementor-addons' ),
				'selector' => '{{WRAPPER}} .xpro-step-flow-badge',
			)
		);

		$this->add_responsive_control(
			'badge_border_radius',
			array(
				'label'      => __( 'Border Radius', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .xpro-step-flow-badge' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'badge_padding',
			array(
				'label'      => __( 'Padding', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .xpro-step-flow-badge' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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

		require XPRO_ELEMENTOR_ADDONS_WIDGET . 'step-flow/layout/frontend.php';
	}
}
