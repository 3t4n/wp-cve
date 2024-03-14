<?php

use Elementor\Controls_Manager;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Css_Filter;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Background;

if ( ! defined( 'ABSPATH' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	die();
}

$this->start_controls_section(
	'general_style_advance_tab',
	[
		'label' => esc_html__( 'General Advance Tab Style', 'absolute-addons' ),
		'tab'   => Controls_Manager::TAB_STYLE,
	]
);

$this->add_group_control(
	Group_Control_Background::get_type(),
	[
		'name'     => 'general_style_advance_tab_body_background',
		'label'    => esc_html__( 'Background', 'absolute-addons' ),
		'types'    => [ 'classic', 'gradient' ],
		'selector' => '{{WRAPPER}} .absp-advance-tab .absp-tab-container, {{WRAPPER}} .absp-advance-tab .absp-tab-container .absp-nav-tab li::before',
	]
);

$this->add_group_control(
	Group_Control_Box_Shadow::get_type(),
	[
		'name'     => 'box_shadow',
		'label'    => esc_html__( 'Box Shadow', 'absolute-addons' ),
		'selector' => '{{WRAPPER}} .absp-advance-tab .absp-tab-container',
		'default'  =>
			[
				'horizontal' => 2,
				'vertical'   => 3,
				'blur'       => 2,
				'spread'     => 2,
				'color'      => 'rgba(0,0,0,0.05)',
			],
	]
);

$this->add_group_control(
	Group_Control_Border::get_type(),
	[
		'name'     => 'general_border',
		'label'    => esc_html__( 'Border', 'absolute-addons' ),
		'selector' => '{{WRAPPER}} .absp-advance-tab .absp-tab-container',
	]
);

$this->add_responsive_control(
	'body_section_border_radius',
	[
		'label'      => esc_html__( 'Advance Tab Border Radius', 'absolute-addons' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'em', '%' ],
		'selectors'  => [
			'{{WRAPPER}} .absp-advance-tab .absp-tab-container' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
	]
);

$this->add_group_control(
	Group_Control_Background::get_type(),
	[
		'name'           => 'advance_tab_before_border_color',
		'fields_options' => [
			'background' => [
				'label' => esc_html__( 'Before Border Color', 'absolute-addons' ),
			],
		],
		'types'          => [ 'classic', 'gradient' ],
		'selector'       => '{{WRAPPER}} .absp-advance-tab .absp-tab-container .absp-nav-tab::before, {{WRAPPER}} .absp-advance-tab .absp-tab-container .absp-tab-content::before',
		'condition'      => [
			'advance_tab' => [ 'three' ],
		],
	]
);

$this->add_responsive_control(
	'body_section_padding',
	[
		'label'      => esc_html__( 'Section Padding', 'absolute-addons' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'rem', 'em', '%' ],
		'selectors'  => [
			'{{WRAPPER}} .absp-advance-tab .absp-tab-container' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
	]
);

$this->add_responsive_control(
	'body_section_margin',
	[
		'label'      => esc_html__( 'Section Margin', 'absolute-addons' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'rem', 'em', '%' ],
		'selectors'  => [
			'{{WRAPPER}} .absp-advance-tab .absp-tab-container' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
	]
);

$this->end_controls_section();

$this->start_controls_section(
	'advance_tab_nav_title',
	[
		'label' => esc_html__( 'Tab Title', 'absolute-addons' ),
		'tab'   => Controls_Manager::TAB_STYLE,
	]
);

$this->start_controls_tabs( 'tab_normal_style' );

$this->start_controls_tab(
	'advance_tab_normal',
	[
		'label' => esc_html__( 'Normal', 'absolute-addons' ),
	]
);

$this->add_group_control(
	Group_Control_Typography::get_type(),
	[
		'label'    => esc_html__( 'Typography', 'absolute-addons' ),
		'name'     => 'advance_tab_title_typography',
		'selector' => '{{WRAPPER}} .absp-advance-tab .absp-tab-container .absp-nav-tab li a',
	]
);

$this->add_group_control(
	Group_Control_Background::get_type(),
	[
		'name'           => 'advance_tab_title_background',
		'fields_options' => [
			'background' => [
				'label' => esc_html__( 'Background', 'absolute-addons' ),
			],
		],
		'types'          => [ 'classic', 'gradient' ],
		'selector'       => '{{WRAPPER}} .absp-advance-tab .absp-tab-container .absp-nav-tab li a, {{WRAPPER}} .advance-tab-item-five .absp-nav-tab li.is-open::before',
		'condition' => [
			'advance_tab!' => [ 'nine' ],
		],
	]
);

$this->add_control(
	'advance_tab_title_color',
	[
		'label'     => esc_html__( 'Title Color', 'absolute-addons' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => [
			'{{WRAPPER}} .absp-advance-tab .absp-tab-container .absp-nav-tab li a' => 'color: {{VALUE}}',
		],
		'condition' => [
			'advance_tab!' => [ 'nine' ],
		],
	]
);

$this->add_control(
	'advance_tab_before_color',
	[
		'label'     => __( 'Before Border Color', 'absolute-addons' ),
		'type'      => Controls_Manager::COLOR,
		'default'   => '',
		'selectors' => [
			'{{WRAPPER}} .absp-advance-tab .absp-tab-container .absp-nav-tab::before' => 'background: {{VALUE}};',
		],
		'condition' => [
			'advance_tab' => [ 'ten' ],
		],
	]
);

$this->add_control(
	'advance_tab_after_color',
	[
		'label'     => __( 'After Color', 'absolute-addons' ),
		'type'      => Controls_Manager::COLOR,
		'default'   => '',
		'selectors' => [
			'{{WRAPPER}} .absp-advance-tab .absp-tab-container .absp-nav-tab li::after' => 'background: {{VALUE}};',
		],
		'condition' => [
			'advance_tab' => [ 'ten' ],
		],
	]
);

$this->add_control(
	'advance_tab_title_icon_color',
	[
		'label'     => esc_html__( 'Icon Color', 'absolute-addons' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => [
			'{{WRAPPER}} .absp-advance-tab .absp-tab-container .absp-nav-tab li i' => 'color: {{VALUE}}',
		],
		'condition' => [
			'advance_tab' => [ 'five', 'six', 'seven', 'eight', 'nine' ],
		],
	]
);

$this->add_group_control(
	Group_Control_Box_Shadow::get_type(),
	[
		'name'     => 'advance_tab_title_box_shadow',
		'label'    => esc_html__( 'Box Shadow', 'absolute-addons' ),
		'selector' => '{{WRAPPER}} .absp-advance-tab .absp-tab-container .absp-nav-tab li a',
		'condition' => [
			'advance_tab!' => [ 'nine' ],
		],
	]
);

$this->add_group_control(
	Group_Control_Border::get_type(),
	[
		'name'     => 'advance_tab_title_border',
		'label'    => esc_html__( 'Border', 'absolute-addons' ),
		'selector' => '{{WRAPPER}} .absp-advance-tab .absp-tab-container .absp-nav-tab li a',
		'condition' => [
			'advance_tab!' => [ 'nine' ],
		],
	]
);

$this->add_responsive_control(
	'advance_tab_title_border_radius',
	[
		'label'      => esc_html__( 'Advance Tab Border Radius', 'absolute-addons' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'em', '%' ],
		'selectors'  => [
			'{{WRAPPER}} .absp-advance-tab .absp-tab-container .absp-nav-tab li a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
		'condition' => [
			'advance_tab!' => [ 'nine' ],
		],
	]
);

$this->add_responsive_control(
	'advance_tab_title_padding',
	[
		'label'      => esc_html__( 'Title Padding', 'absolute-addons' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'rem', 'em', '%' ],
		'selectors'  => [
			'{{WRAPPER}} .absp-advance-tab .absp-tab-container .absp-nav-tab li a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
	]
);

$this->add_responsive_control(
	'advance_tab_title_margin',
	[
		'label'      => esc_html__( 'Title Margin', 'absolute-addons' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'rem', 'em', '%' ],
		'selectors'  => [
			'{{WRAPPER}} .absp-advance-tab .absp-tab-container .absp-nav-tab li a' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
	]
);

$this->end_controls_tab();

$this->start_controls_tab(
	'advance_tab_hover',
	[
		'label' => esc_html__( 'Hover', 'absolute-addons' ),
		'condition' => [
			'advance_tab!' => [ 'nine' ],
		],
	]
);

$this->add_group_control(
	Group_Control_Background::get_type(),
	[
		'name'           => 'advance_tab_title_background_hover',
		'fields_options' => [
			'background' => [
				'label' => esc_html__( 'Background', 'absolute-addons' ),
			],
		],
		'types'          => [ 'classic', 'gradient' ],
		'selector'       => '{{WRAPPER}} .absp-advance-tab .absp-tab-container .absp-nav-tab li a:hover',
		'condition' => [
			'advance_tab!' => [ 'nine' ],
		],
	]
);

$this->add_control(
	'advance_tab_title_color_hover',
	[
		'label'     => esc_html__( 'Title Color', 'absolute-addons' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => [
			'{{WRAPPER}} .absp-advance-tab .absp-tab-container .absp-nav-tab li a:hover' => 'color: {{VALUE}}',
		],
		'condition' => [
			'advance_tab!' => [ 'nine' ],
		],
	]
);

$this->add_control(
	'advance_tab_before_hover_color',
	[
		'label'     => __( 'Before Border Color', 'absolute-addons' ),
		'type'      => Controls_Manager::COLOR,
		'default'   => '',
		'selectors' => [
			'{{WRAPPER}} .absp-advance-tab .absp-tab-container .absp-nav-tab li:hover::before' => 'background: {{VALUE}};',
		],
		'condition' => [
			'advance_tab' => [ 'ten' ],
		],
	]
);

$this->add_group_control(
	Group_Control_Box_Shadow::get_type(),
	[
		'name'     => 'advance_tab_title_box_shadow_hover',
		'label'    => esc_html__( 'Box Shadow', 'absolute-addons' ),
		'selector' => '{{WRAPPER}} .absp-advance-tab .absp-tab-container .absp-nav-tab li a:hover',
		'condition' => [
			'advance_tab!' => [ 'nine' ],
		],
	]
);

$this->add_group_control(
	Group_Control_Border::get_type(),
	[
		'name'     => 'advance_tab_title_border_hover_hover',
		'label'    => esc_html__( 'Border', 'absolute-addons' ),
		'selector' => '{{WRAPPER}} .absp-advance-tab .absp-tab-container .absp-nav-tab li a:hover',
		'condition' => [
			'advance_tab!' => [ 'nine' ],
		],
	]
);

$this->add_responsive_control(
	'advance_tab_title_border_radius_hover',
	[
		'label'      => esc_html__( 'Advance Tab Border Radius', 'absolute-addons' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'em', '%' ],
		'selectors'  => [
			'{{WRAPPER}} .absp-advance-tab .absp-tab-container .absp-nav-tab li a:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
		'condition' => [
			'advance_tab!' => [ 'nine' ],
		],
	]
);

$this->end_controls_tab();

$this->start_controls_tab(
	'advance_tab_active',
	[
		'label' => esc_html__( 'Active', 'absolute-addons' ),
	]
);

$this->add_group_control(
	Group_Control_Background::get_type(),
	[
		'name'           => 'advance_tab_title_background_active',
		'fields_options' => [
			'background' => [
				'label' => esc_html__( 'Background', 'absolute-addons' ),
			],
		],
		'types'          => [ 'classic', 'gradient' ],
		'selector'       => '{{WRAPPER}} .absp-advance-tab .absp-tab-container .absp-nav-tab li.is-open a, {{WRAPPER}} .absp-advance-tab .absp-tab-container .absp-nav-tab li.is-open a::before, {{WRAPPER}} .advance-tab-item-five .absp-nav-tab li.is-open::before',
		'condition' => [
			'advance_tab!' => [ 'nine' ],
		],
	]
);

$this->add_control(
	'advance_tab_title_color_active',
	[
		'label'     => esc_html__( 'Title Color', 'absolute-addons' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => [
			'{{WRAPPER}} .absp-advance-tab .absp-tab-container .absp-nav-tab li.is-open a' => 'color: {{VALUE}}',
		],
		'condition' => [
			'advance_tab!' => [ 'nine' ],
		],
	]
);

$this->add_control(
	'advance_tab_before_active_color',
	[
		'label'     => __( 'Active Border Color', 'absolute-addons' ),
		'type'      => Controls_Manager::COLOR,
		'default'   => '',
		'selectors' => [
			'{{WRAPPER}} .absp-advance-tab .absp-tab-container .absp-nav-tab li.is-open::before' => 'border-color: {{VALUE}};',
		],
		'condition' => [
			'advance_tab' => [ 'ten' ],
		],
	]
);

$this->add_control(
	'advance_tab_title_icon_color_active',
	[
		'label'     => esc_html__( 'Icon Color', 'absolute-addons' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => [
			'{{WRAPPER}} .absp-advance-tab .absp-tab-container .absp-nav-tab li.is-open i' => 'color: {{VALUE}}',
		],
		'condition' => [
			'advance_tab' => [ 'five', 'six', 'seven', 'eight', 'nine' ],
		],
	]
);

$this->add_group_control(
	Group_Control_Background::get_type(),
	[
		'name'           => 'advance_tab_title_before',
		'fields_options' => [
			'background' => [
				'label' => esc_html__( 'Before border color', 'absolute-addons' ),
			],
		],
		'types'          => [ 'classic', 'gradient' ],
		'selector'       => '{{WRAPPER}} .absp-advance-tab .absp-tab-container .absp-nav-tab li.is-open a::before',
		'condition'      => [
			'advance_tab' => [ 'seven' ],
		],
	]
);

$this->add_group_control(
	Group_Control_Box_Shadow::get_type(),
	[
		'name'     => 'advance_tab_title_box_shadow_active',
		'label'    => esc_html__( 'Box Shadow', 'absolute-addons' ),
		'selector' => '{{WRAPPER}} .absp-advance-tab .absp-tab-container .absp-nav-tab li.is-open a',
		'condition' => [
			'advance_tab!' => [ 'nine' ],
		],
	]
);

$this->add_group_control(
	Group_Control_Border::get_type(),
	[
		'name'     => 'advance_tab_title_border_active',
		'label'    => esc_html__( 'Border', 'absolute-addons' ),
		'selector' => '{{WRAPPER}} .absp-advance-tab .absp-tab-container .absp-nav-tab li.is-open a',
		'condition' => [
			'advance_tab!' => [ 'nine' ],
		],
	]
);

$this->add_responsive_control(
	'advance_tab_title_border_radius_active',
	[
		'label'      => esc_html__( 'Advance Tab Border Radius', 'absolute-addons' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'em', '%' ],
		'selectors'  => [
			'{{WRAPPER}} .absp-advance-tab .absp-tab-container .absp-nav-tab li.is-open a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
		'condition' => [
			'advance_tab!' => [ 'nine' ],
		],
	]
);

$this->add_responsive_control(
	'advance_tab_title_padding_active',
	[
		'label'      => esc_html__( 'Title Padding', 'absolute-addons' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'rem', 'em', '%' ],
		'selectors'  => [
			'{{WRAPPER}} .absp-advance-tab .absp-tab-container .absp-nav-tab li.is-open a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
	]
);

$this->end_controls_tab();

$this->end_controls_tabs();

$this->end_controls_section();

$this->start_controls_section(
	'advance_tab_image',
	[
		'label'     => esc_html__( 'Image', 'absolute-addons' ),
		'tab'       => Controls_Manager::TAB_STYLE,
		'condition' => [
			'advance_tab!' => [ 'three', 'seven', 'ten' ],
		],
	]
);

$this->add_responsive_control(
	'advance_tab_image_width',
	[
		'label'          => __( 'Width', 'absolute-addons' ),
		'type'           => Controls_Manager::SLIDER,
		'default'        => [
			'unit' => '%',
		],
		'tablet_default' => [
			'unit' => '%',
		],
		'mobile_default' => [
			'unit' => '%',
		],
		'size_units'     => [ '%', 'px', 'vw' ],
		'range'          => [
			'%'  => [
				'min' => 1,
				'max' => 100,
			],
			'px' => [
				'min' => 1,
				'max' => 1000,
			],
			'vw' => [
				'min' => 1,
				'max' => 100,
			],
		],
		'selectors'      => [
			'{{WRAPPER}} .absp-advance-tab .absp-tab-container .absp-tab-content .absp-nav-body .tab-image-gallery .tab-gallery-item img' => 'width: {{SIZE}}{{UNIT}};',
			'{{WRAPPER}} .absp-advance-tab .absp-tab-container .absp-tab-content .absp-nav-body .tab-image img'                           => 'width: {{SIZE}}{{UNIT}};',
		],
	]
);

$this->add_responsive_control(
	'advance_tab_image_space',
	[
		'label'          => __( 'Max Width', 'absolute-addons' ),
		'type'           => Controls_Manager::SLIDER,
		'default'        => [
			'unit' => '%',
		],
		'tablet_default' => [
			'unit' => '%',
		],
		'mobile_default' => [
			'unit' => '%',
		],
		'size_units'     => [ '%', 'px', 'vw' ],
		'range'          => [
			'%'  => [
				'min' => 1,
				'max' => 100,
			],
			'px' => [
				'min' => 1,
				'max' => 1000,
			],
			'vw' => [
				'min' => 1,
				'max' => 100,
			],
		],
		'selectors'      => [
			'{{WRAPPER}} .absp-advance-tab .absp-tab-container .absp-tab-content .absp-nav-body .tab-image-gallery .tab-gallery-item img' => 'max-width: {{SIZE}}{{UNIT}};',
			'{{WRAPPER}} .absp-advance-tab .absp-tab-container .absp-tab-content .absp-nav-body .tab-image img'                           => 'max-width: {{SIZE}}{{UNIT}};',
		],
	]
);

$this->add_responsive_control(
	'advance_tab_image_height',
	[
		'label'          => __( 'Height', 'absolute-addons' ),
		'type'           => Controls_Manager::SLIDER,
		'default'        => [
			'unit' => 'px',
		],
		'tablet_default' => [
			'unit' => 'px',
		],
		'mobile_default' => [
			'unit' => 'px',
		],
		'size_units'     => [ 'px', 'vh' ],
		'range'          => [
			'px' => [
				'min' => 1,
				'max' => 500,
			],
			'vh' => [
				'min' => 1,
				'max' => 100,
			],
		],
		'selectors'      => [
			'{{WRAPPER}} .absp-advance-tab .absp-tab-container .absp-tab-content .absp-nav-body .tab-image-gallery .tab-gallery-item img' => 'height: {{SIZE}}{{UNIT}};',
			'{{WRAPPER}} .absp-advance-tab .absp-tab-container .absp-tab-content .absp-nav-body .tab-image img'                           => 'height: {{SIZE}}{{UNIT}};',
		],
	]
);

$this->add_responsive_control(
	'advance_tab_image_object-fit',
	[
		'label'     => __( 'Object Fit', 'absolute-addons' ),
		'type'      => Controls_Manager::SELECT,
		'condition' => [
			'advance_tab_image_height[size]!' => '',
		],
		'options'   => [
			''        => __( 'Default', 'absolute-addons' ),
			'fill'    => __( 'Fill', 'absolute-addons' ),
			'cover'   => __( 'Cover', 'absolute-addons' ),
			'contain' => __( 'Contain', 'absolute-addons' ),
		],
		'default'   => '',
		'selectors' => [
			'{{WRAPPER}} .absp-advance-tab .absp-tab-container .absp-tab-content .absp-nav-body .tab-image-gallery .tab-gallery-item img' => 'object-fit: {{VALUE}};',
			'{{WRAPPER}} .absp-advance-tab .absp-tab-container .absp-tab-content .absp-nav-body .tab-image img'                           => 'object-fit: {{VALUE}};',
		],
	]
);

$this->add_group_control(
	Group_Control_Css_Filter::get_type(),
	[
		'name'     => 'css_filters',
		'selector' => '{{WRAPPER}} .absp-advance-tab .absp-tab-container .absp-tab-content .absp-nav-body .tab-image-gallery .tab-gallery-item img, {{WRAPPER}} .absp-advance-tab .absp-tab-container .absp-tab-content .absp-nav-body .tab-image img',
	]
);

$this->add_group_control(
	Group_Control_Border::get_type(),
	[
		'name'      => 'advance_tab_image_border',
		'selector'  => '{{WRAPPER}} .absp-advance-tab .absp-tab-container .absp-tab-content .absp-nav-body .tab-image-gallery .tab-gallery-item img, {{WRAPPER}} .absp-advance-tab .absp-tab-container .absp-tab-content .absp-nav-body .tab-image img',
		'separator' => 'before',
	]
);

$this->add_responsive_control(
	'advance_tab_image_border_radius',
	[
		'label'      => __( 'Border Radius', 'absolute-addons' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', '%' ],
		'selectors'  => [
			'{{WRAPPER}} .absp-advance-tab .absp-tab-container .absp-tab-content .absp-nav-body .tab-image-gallery .tab-gallery-item img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			'{{WRAPPER}} .absp-advance-tab .absp-tab-container .absp-tab-content .absp-nav-body .tab-image img'                           => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
	]
);

$this->add_group_control(
	Group_Control_Box_Shadow::get_type(),
	[
		'name'     => 'advance_tab_image_box_shadow',
		'exclude'  => [ //phpcs:ignore WordPressVIPMinimum.Performance.WPQueryParams.PostNotIn_exclude
			'box_shadow_position',
		],
		'selector' => '{{WRAPPER}} .absp-advance-tab .absp-tab-container .absp-tab-content .absp-nav-body .tab-image-gallery .tab-gallery-item img, {{WRAPPER}} .absp-advance-tab .absp-tab-container .absp-tab-content .absp-nav-body .tab-image img',
	]
);

$this->end_controls_section();

$this->start_controls_section(
	'advance_tab_image_caption',
	[
		'label'     => __( 'Caption', 'absolute-addons' ),
		'tab'       => Controls_Manager::TAB_STYLE,
		'condition' => [
			'advance_tab' => [ 'one', 'five' ],
		],
	]
);

$this->add_responsive_control(
	'advance_tab_image_caption_align',
	[
		'label'     => __( 'Alignment', 'absolute-addons' ),
		'type'      => Controls_Manager::CHOOSE,
		'options'   => [
			'left'    => [
				'title' => __( 'Left', 'absolute-addons' ),
				'icon'  => 'eicon-text-align-left',
			],
			'center'  => [
				'title' => __( 'Center', 'absolute-addons' ),
				'icon'  => 'eicon-text-align-center',
			],
			'right'   => [
				'title' => __( 'Right', 'absolute-addons' ),
				'icon'  => 'eicon-text-align-right',
			],
			'justify' => [
				'title' => __( 'Justified', 'absolute-addons' ),
				'icon'  => 'eicon-text-align-justify',
			],
		],
		'default'   => '',
		'selectors' => [
			'{{WRAPPER}} .absp-advance-tab .absp-tab-container .absp-tab-content .absp-nav-body .tab-image-gallery .tab-gallery-item .tab-image-caption' => 'text-align: {{VALUE}};',
		],
	]
);

$this->add_responsive_control(
	'advance_tab_image_text_color',
	[
		'label'     => __( 'Text Color', 'absolute-addons' ),
		'type'      => Controls_Manager::COLOR,
		'default'   => '',
		'selectors' => [
			'{{WRAPPER}} .absp-advance-tab .absp-tab-container .absp-tab-content .absp-nav-body .tab-image-gallery .tab-gallery-item .tab-image-caption' => 'color: {{VALUE}};',
		],
	]
);

$this->add_control(
	'advance_tab_image_caption_background_color',
	[
		'label'     => __( 'Background Color', 'absolute-addons' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => [
			'{{WRAPPER}} .absp-advance-tab .absp-tab-container .absp-tab-content .absp-nav-body .tab-image-gallery .tab-gallery-item .tab-image-caption' => 'background-color: {{VALUE}};',
		],
	]
);

$this->add_group_control(
	Group_Control_Typography::get_type(),
	[
		'name'     => 'advance_tab_image_caption_typography',
		'selector' => '{{WRAPPER}} .absp-advance-tab .absp-tab-container .absp-tab-content .absp-nav-body .tab-image-gallery .tab-gallery-item .tab-image-caption',
	]
);

$this->add_group_control(
	Group_Control_Text_Shadow::get_type(),
	[
		'name'     => 'advance_tab_image_caption_text_shadow',
		'selector' => '{{WRAPPER}} .absp-advance-tab .absp-tab-container .absp-tab-content .absp-nav-body .tab-image-gallery .tab-gallery-item .tab-image-caption',
	]
);

$this->add_group_control(
	Group_Control_Border::get_type(),
	[
		'name'      => 'advance_tab_image_caption_border',
		'selector'  => '{{WRAPPER}} .absp-advance-tab .absp-tab-container .absp-tab-content .absp-nav-body .tab-image-gallery .tab-gallery-item .tab-image-caption',
		'separator' => 'after',
	]
);

$this->add_responsive_control(
	'advance_tab_image_caption_space',
	[
		'label'     => __( 'Spacing', 'absolute-addons' ),
		'type'      => Controls_Manager::SLIDER,
		'range'     => [
			'px' => [
				'min' => 0,
				'max' => 100,
			],
		],
		'selectors' => [
			'{{WRAPPER}} .absp-advance-tab .absp-tab-container .absp-tab-content .absp-nav-body .tab-image-gallery .tab-gallery-item .tab-image-caption' => 'margin-top: {{SIZE}}{{UNIT}};',
		],
	]
);

$this->end_controls_section();

$this->start_controls_section(
	'advance_tab_content_box',
	[
		'label' => __( 'Content Box', 'absolute-addons' ),
		'tab'   => Controls_Manager::TAB_STYLE,
	]
);

$this->add_group_control(
	Group_Control_Background::get_type(),
	[
		'name'           => 'advance_tab_content_box_bg',
		'fields_options' => [
			'background' => [
				'label' => esc_html__( 'Background Color', 'absolute-addons' ),
			],
		],
		'types'          => [ 'classic', 'gradient' ],
		'selector'       => '{{WRAPPER}} .absp-advance-tab .absp-tab-container .absp-tab-content, {{WRAPPER}} .advance-tab-item-five .absp-tab-content .absp-nav-body',
	]
);

$this->add_group_control(
	Group_Control_Border::get_type(),
	[
		'name'      => 'advance_tab_content_box_border',
		'selector'  => '{{WRAPPER}} .absp-advance-tab .absp-tab-container .absp-tab-content',
		'separator' => 'before',
	]
);

$this->add_responsive_control(
	'advance_tab_content_box_border_radius',
	[
		'label'      => __( 'Border Radius', 'absolute-addons' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', '%' ],
		'selectors'  => [
			'{{WRAPPER}} .absp-advance-tab .absp-tab-container .absp-tab-content' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
	]
);

$this->add_group_control(
	Group_Control_Box_Shadow::get_type(),
	[
		'name'     => 'advance_tab_content_box_box_shadow',
		'exclude'  => [ //phpcs:ignore WordPressVIPMinimum.Performance.WPQueryParams.PostNotIn_exclude
			'box_shadow_position',
		],
		'selector' => '{{WRAPPER}} .absp-advance-tab .absp-tab-container .absp-tab-content',
	]
);

$this->add_responsive_control(
	'advance_tab_content_box_padding',
	[
		'label'      => esc_html__( 'Content Box Padding', 'absolute-addons' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'rem', 'em', '%' ],
		'selectors'  => [
			'{{WRAPPER}} .absp-advance-tab .absp-tab-container .absp-tab-content .absp-nav-body' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
		'condition'  => [
			'advance_tab' => [ 'four', 'five' ],
		],
	]
);

$this->add_responsive_control(
	'advance_tab_content_box_paddings',
	[
		'label'      => esc_html__( 'Content Box Padding', 'absolute-addons' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'rem', 'em', '%' ],
		'selectors'  => [
			'{{WRAPPER}} .absp-advance-tab .absp-tab-container .absp-tab-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
		'condition'  => [
			'advance_tab!' => [ 'four', 'five' ],
		],
	]
);

$this->add_responsive_control(
	'advance_tab_content_box_margin',
	[
		'label'      => esc_html__( 'Content Box Margin', 'absolute-addons' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'rem', 'em', '%' ],
		'selectors'  => [
			'{{WRAPPER}} .absp-advance-tab .absp-tab-container .absp-tab-content' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
	]
);

$this->end_controls_section();

$this->start_controls_section(
	'advance_tab_content_title',
	[
		'label'     => __( 'Content Title', 'absolute-addons' ),
		'tab'       => Controls_Manager::TAB_STYLE,
		'condition' => [
		//          'caption_source!' => 'none',
		],
	]
);

$this->add_group_control(
	Group_Control_Typography::get_type(),
	[
		'name'     => 'advance_tab_content_title_typography',
		'selector' => '{{WRAPPER}} .absp-advance-tab .absp-tab-container .absp-tab-content .absp-nav-body .content-title',
	]
);

$this->add_group_control(
	Group_Control_Background::get_type(),
	[
		'name'           => 'advance_tab_content_title_background',
		'fields_options' => [
			'background' => [
				'label' => esc_html__( 'Background', 'absolute-addons' ),
			],
		],
		'types'          => [ 'classic', 'gradient' ],
		'selector'       => '{{WRAPPER}} .absp-advance-tab .absp-tab-container .absp-tab-content .absp-nav-body .content-title',
	]
);

$this->add_control(
	'advance_tab_content_title_color',
	[
		'label'     => __( 'Text Color', 'absolute-addons' ),
		'type'      => Controls_Manager::COLOR,
		'default'   => '',
		'selectors' => [
			'{{WRAPPER}} .absp-advance-tab .absp-tab-container .absp-tab-content .absp-nav-body .content-title' => 'color: {{VALUE}};',
		],
	]
);

$this->add_control(
	'advance_tab_content_title_icon_color',
	[
		'label'     => __( 'Icon Color', 'absolute-addons' ),
		'type'      => Controls_Manager::COLOR,
		'default'   => '',
		'selectors' => [
			'{{WRAPPER}} .absp-advance-tab .absp-tab-container .absp-tab-content .absp-nav-body .content-left .content-left-wrapper i' => 'color: {{VALUE}};',
		],
		'condition' => [
			'advance_tab' => [ 'seven' ],
		],
	]
);

$this->add_responsive_control(
	'advance_tab_content_title_padding',
	[
		'label'      => esc_html__( 'Title Padding', 'absolute-addons' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'rem', 'em', '%' ],
		'selectors'  => [
			'{{WRAPPER}} .absp-advance-tab .absp-tab-container .absp-tab-content .absp-nav-body .content-title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
	]
);

$this->end_controls_section();

$this->start_controls_section(
	'advance_tab_content_sub_title',
	[
		'label'     => __( 'Content Sub Title', 'absolute-addons' ),
		'tab'       => Controls_Manager::TAB_STYLE,
		'condition' => [
			'advance_tab' => [ 'two', 'three', 'five', 'seven', 'ten' ],
		],
	]
);

$this->add_group_control(
	Group_Control_Typography::get_type(),
	[
		'name'     => 'advance_tab_content_sub_title_typography',
		'selector' => '{{WRAPPER}} .absp-advance-tab .absp-tab-container .absp-tab-content .absp-nav-body .content-sub-title',
	]
);

$this->add_group_control(
	Group_Control_Background::get_type(),
	[
		'name'           => 'advance_tab_content_sub_title_background',
		'fields_options' => [
			'background' => [
				'label' => esc_html__( 'Background', 'absolute-addons' ),
			],
		],
		'types'          => [ 'classic', 'gradient' ],
		'selector'       => '{{WRAPPER}} .absp-advance-tab .absp-tab-container .absp-tab-content .absp-nav-body .content-sub-title',
	]
);

$this->add_control(
	'advance_tab_content_sub_title_color',
	[
		'label'     => __( 'Text Color', 'absolute-addons' ),
		'type'      => Controls_Manager::COLOR,
		'default'   => '',
		'selectors' => [
			'{{WRAPPER}} .absp-advance-tab .absp-tab-container .absp-tab-content .absp-nav-body .content-sub-title' => 'color: {{VALUE}};',
		],
	]
);

$this->add_responsive_control(
	'advance_tab_content_sub_title_padding',
	[
		'label'      => esc_html__( 'Title Padding', 'absolute-addons' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'rem', 'em', '%' ],
		'selectors'  => [
			'{{WRAPPER}} .absp-advance-tab .absp-tab-container .absp-tab-content .absp-nav-body .content-sub-title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
	]
);

$this->end_controls_section();

$this->start_controls_section(
	'advance_tab_content',
	[
		'label' => __( 'Content', 'absolute-addons' ),
		'tab'   => Controls_Manager::TAB_STYLE,
	]
);

$this->add_group_control(
	Group_Control_Typography::get_type(),
	[
		'name'     => 'advance_tab_content_typography',
		'selector' => '{{WRAPPER}} .absp-advance-tab .absp-tab-container .absp-tab-content .absp-nav-body .tab-body-content',
	]
);

$this->add_group_control(
	Group_Control_Background::get_type(),
	[
		'name'           => 'advance_tab_content_background',
		'fields_options' => [
			'background' => [
				'label' => esc_html__( 'Background', 'absolute-addons' ),
			],
		],
		'types'          => [ 'classic', 'gradient' ],
		'selector'       => '{{WRAPPER}} .absp-advance-tab .absp-tab-container .absp-tab-content .absp-nav-body .tab-body-content',
	]
);

$this->add_control(
	'advance_tab_content_color',
	[
		'label'     => __( 'Text Color', 'absolute-addons' ),
		'type'      => Controls_Manager::COLOR,
		'default'   => '',
		'selectors' => [
			'{{WRAPPER}} .absp-advance-tab .absp-tab-container .absp-tab-content .absp-nav-body .tab-body-content' => 'color: {{VALUE}};',
		],
	]
);

$this->add_responsive_control(
	'advance_tab_content_right_padding',
	[
		'label'      => esc_html__( 'Content Padding', 'absolute-addons' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'rem', 'em', '%' ],
		'selectors'  => [
			'{{WRAPPER}} .absp-advance-tab .absp-tab-container .absp-tab-content .absp-nav-body .content-right' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
		'condition'  => [
			'advance_tab' => [ 'four' ],
		],
	]
);

$this->add_responsive_control(
	'advance_tab_content_padding',
	[
		'label'      => esc_html__( 'Content Padding', 'absolute-addons' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'rem', 'em', '%' ],
		'selectors'  => [
			'{{WRAPPER}} .absp-advance-tab .absp-tab-container .absp-tab-content .absp-nav-body .tab-body-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
	]
);

$this->end_controls_section();

$this->start_controls_section(
	'advance_tab_button',
	[
		'label'     => __( 'Button', 'absolute-addons' ),
		'tab'       => Controls_Manager::TAB_STYLE,
		'condition' => [
			'advance_tab' => [ 'one', 'four', 'seven', 'eight', 'nine' ],
		],
	]
);

$this->start_controls_tabs( 'tab_button_normal_style' );

$this->start_controls_tab(
	'advance_tab_button_normal',
	[
		'label' => esc_html__( 'Normal', 'absolute-addons' ),
	]
);

$this->add_group_control(
	Group_Control_Typography::get_type(),
	[
		'label'    => esc_html__( 'Typography', 'absolute-addons' ),
		'name'     => 'advance_tab_button_typography',
		'selector' => '{{WRAPPER}} .absp-advance-tab .absp-tab-container .absp-tab-content .absp-nav-body .tab-content-button',
	]
);

$this->add_group_control(
	Group_Control_Background::get_type(),
	[
		'name'           => 'advance_tab_button_background',
		'fields_options' => [
			'background' => [
				'label' => esc_html__( 'Background', 'absolute-addons' ),
			],
		],
		'types'          => [ 'classic', 'gradient' ],
		'selector'       => '{{WRAPPER}} .absp-advance-tab .absp-tab-container .absp-tab-content .absp-nav-body .tab-content-button',
	]
);

$this->add_control(
	'advance_tab_button_color',
	[
		'label'     => esc_html__( 'Title Color', 'absolute-addons' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => [
			'{{WRAPPER}} .absp-advance-tab .absp-tab-container .absp-tab-content .absp-nav-body .tab-content-button' => 'color: {{VALUE}}',
		],
	]
);

$this->add_group_control(
	Group_Control_Border::get_type(),
	[
		'name'     => 'advance_tab_button_border',
		'label'    => esc_html__( 'Border', 'absolute-addons' ),
		'selector' => '{{WRAPPER}} .absp-advance-tab .absp-tab-container .absp-tab-content .absp-nav-body .tab-content-button',
	]
);

$this->add_responsive_control(
	'advance_tab_button_border_radius',
	[
		'label'      => esc_html__( 'Button Border Radius', 'absolute-addons' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'em', '%' ],
		'selectors'  => [
			'{{WRAPPER}} .absp-advance-tab .absp-tab-container .absp-tab-content .absp-nav-body .tab-content-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
	]
);

$this->add_group_control(
	Group_Control_Box_Shadow::get_type(),
	[
		'name'     => 'advance_tab_button_shadow',
		'label'    => esc_html__( 'Box Shadow', 'absolute-addons' ),
		'selector' => '{{WRAPPER}} .absp-advance-tab .absp-tab-container .absp-tab-content .absp-nav-body .tab-content-button',
	]
);

$this->add_responsive_control(
	'advance_tab_button_padding',
	[
		'label'      => esc_html__( 'Button Padding', 'absolute-addons' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'rem', 'em', '%' ],
		'selectors'  => [
			'{{WRAPPER}} .absp-advance-tab .absp-tab-container .absp-tab-content .absp-nav-body .tab-content-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
	]
);

$this->add_responsive_control(
	'advance_tab_button_margin',
	[
		'label'      => esc_html__( 'Button Margin', 'absolute-addons' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'rem', 'em', '%' ],
		'selectors'  => [
			'{{WRAPPER}} .absp-advance-tab .absp-tab-container .absp-tab-content .absp-nav-body .tab-content-button' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
	]
);

$this->end_controls_tab();

$this->start_controls_tab(
	'advance_tab_button_hover',
	[
		'label' => esc_html__( 'Hover', 'absolute-addons' ),
	]
);

$this->add_group_control(
	Group_Control_Background::get_type(),
	[
		'name'           => 'advance_tab_button_background_hover',
		'fields_options' => [
			'background' => [
				'label' => esc_html__( 'Background', 'absolute-addons' ),
			],
		],
		'types'          => [ 'classic', 'gradient' ],
		'selector'       => '{{WRAPPER}} .absp-advance-tab .absp-tab-container .absp-tab-content .absp-nav-body .tab-content-button:hover',
	]
);

$this->add_control(
	'advance_tab_button_color_hover',
	[
		'label'     => esc_html__( 'Title Color', 'absolute-addons' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => [
			'{{WRAPPER}} .absp-advance-tab .absp-tab-container .absp-tab-content .absp-nav-body .tab-content-button:hover' => 'color: {{VALUE}}',
		],
	]
);

$this->add_group_control(
	Group_Control_Box_Shadow::get_type(),
	[
		'name'     => 'advance_tab_button_box_shadow_hover',
		'label'    => esc_html__( 'Box Shadow', 'absolute-addons' ),
		'selector' => '{{WRAPPER}} .absp-advance-tab .absp-tab-container .absp-tab-content .absp-nav-body .tab-content-button:hover',
	]
);

$this->add_group_control(
	Group_Control_Border::get_type(),
	[
		'name'     => 'advance_tab_button_border_hover_hover',
		'label'    => esc_html__( 'Border', 'absolute-addons' ),
		'selector' => '{{WRAPPER}} .absp-advance-tab .absp-tab-container .absp-tab-content .absp-nav-body .tab-content-button:hover',
	]
);

$this->add_responsive_control(
	'advance_tab_button_border_radius_hover',
	[
		'label'      => esc_html__( 'Button Border Radius', 'absolute-addons' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'em', '%' ],
		'selectors'  => [
			'{{WRAPPER}} .absp-advance-tab .absp-tab-container .absp-tab-content .absp-nav-body .tab-content-button:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
	]
);

$this->end_controls_tab();

$this->end_controls_tabs();

$this->end_controls_section();
