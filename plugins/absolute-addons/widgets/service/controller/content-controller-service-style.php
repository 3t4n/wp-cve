<?php
/**
 * Content Controller - Service
 *
 * @package AbsoluteAddons
 */
defined( 'ABSPATH' ) || exit;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Css_Filter;
use Elementor\Group_Control_Typography;

$this->start_controls_section(
	'service_item_section',
	[
		'label' => __( 'Service Item', 'absolute-addons' ),
		'tab'   => Controls_Manager::TAB_STYLE,
	]
);

$this->add_group_control(
	Group_Control_Background::get_type(),
	[
		'name'     => 'service_item_normal_background',
		'label'    => __( 'Background', 'absolute-addons' ),
		'types'    => [ 'classic', 'gradient' ],
		'selector' => '{{WRAPPER}} .absp-service .absp-service-item',
	]
);

$this->add_group_control(
	Group_Control_Box_Shadow::get_type(),
	[
		'name'     => 'box_shadow',
		'label'    => __( 'Box Shadow', 'absolute-addons' ),
		'selector' => '{{WRAPPER}} .absp-service .absp-service-item',
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

$this->add_responsive_control(
	'service_item_radius',
	[
		'label'      => __( 'Item Border Radius', 'absolute-addons' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'em', '%' ],
		'selectors'  => [
			'{{WRAPPER}} .absp-service .absp-service-item' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
	]
);

$this->add_control(
	'service_item_column_gap',
	[
		'label'      => __( 'Column Gap', 'absolute-addons' ),
		'type'       => Controls_Manager::SLIDER,
		'size_units' => [ 'px', 'rem', 'em', '%' ],
		'range'      => [
			'px' => [
				'min'  => 0,
				'max'  => 200,
				'step' => 5,
			],
			'%'  => [
				'min' => 0,
				'max' => 100,
			],
		],
		'selectors'  => [
			'{{WRAPPER}} .absp-service .absp-service-grid-col' => 'column-gap: {{SIZE}}{{UNIT}}',
		],
	]
);

$this->add_control(
	'service_item_row_gap',
	[
		'label'      => __( 'Row Gap', 'absolute-addons' ),
		'type'       => Controls_Manager::SLIDER,
		'size_units' => [ 'px', 'rem', 'em', '%' ],
		'range'      => [
			'px' => [
				'min'  => 0,
				'max'  => 200,
				'step' => 5,
			],
			'%'  => [
				'min' => 0,
				'max' => 100,
			],
		],
		'selectors'  => [
			'{{WRAPPER}} .absp-service .absp-service-grid-col' => 'row-gap: {{SIZE}}{{UNIT}}',
		],
	]
);

$this->add_responsive_control(
	'service_item_normal_padding',
	[
		'label'      => __( 'Item Padding', 'absolute-addons' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'rem', 'em', '%' ],
		'selectors'  => [
			'{{WRAPPER}} .absp-service .absp-service-item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
	]
);

$this->end_controls_section();

$this->start_controls_section(
	'service_image_section',
	[
		'label'     => __( 'Feature Image', 'absolute-addons' ),
		'tab'       => Controls_Manager::TAB_STYLE,
		'condition' => [
			'absolute_service' => [ 'twenty-five', 'twenty-nine' ],
		],
	]
);

$this->add_group_control(
	Group_Control_Box_Shadow::get_type(),
	[
		'name'     => 'image_box_shadow',
		'label'    => __( 'Box Shadow', 'absolute-addons' ),
		'selector' => '{{WRAPPER}} .absp-service .absp-service-item .absp-service-image',
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

$this->add_responsive_control(
	'service_image_radius',
	[
		'label'      => __( 'Image Border Radius', 'absolute-addons' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'em', '%' ],
		'selectors'  => [
			'{{WRAPPER}} .absp-service .absp-service-item .absp-service-image' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
	]
);

$this->add_group_control(
	Group_Control_Css_Filter::get_type(),
	[
		'name'     => 'service_image_css_filters',
		'selector' => '{{WRAPPER}} .absp-service .absp-service-item .absp-service-image img',
	]
);

$this->add_responsive_control(
	'service_image_padding',
	[
		'label'      => __( 'Image Padding', 'absolute-addons' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'rem', 'em', '%' ],
		'selectors'  => [
			'{{WRAPPER}} .absp-service .absp-service-item .absp-service-image' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
	]
);

$this->end_controls_section();

$this->start_controls_section(
	'service_head_section',
	[
		'label'     => __( 'Box Wrapper', 'absolute-addons' ),
		'tab'       => Controls_Manager::TAB_STYLE,
		'condition' => [
			'absolute_service' => 'three',
		],
	]
);

$this->start_controls_tabs( 'service_head_tabs' );

$this->start_controls_tab(
	'service_head_normal',
	[
		'label' => __( 'Normal', 'absolute-addons' ),
	]
);

$this->add_group_control(
	Group_Control_Background::get_type(),
	[
		'name'     => 'service_head_normal_background',
		'label'    => __( 'Background', 'absolute-addons' ),
		'types'    => [ 'classic', 'gradient' ],
		'selector' => '{{WRAPPER}} .absp-service .absp-service-left',
	]
);

$this->add_group_control(
	Group_Control_Border::get_type(),
	[
		'name'     => 'service_head_border_hover',
		'label'    => __( 'Border', 'absolute-addons' ),
		'selector' => '{{WRAPPER}} .absp-service .absp-service-left',
	]
);

$this->add_responsive_control(
	'service_head_radius',
	[
		'label'      => __( 'Box Border Radius', 'absolute-addons' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'em', '%' ],
		'selectors'  => [
			'{{WRAPPER}} .absp-service .absp-service-item .absp-service-left' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
	]
);

$this->end_controls_tab();

$this->start_controls_tab(
	'service_head_hover',
	[
		'label' => __( 'Hover', 'absolute-addons' ),
	]
);

$this->add_group_control(
	Group_Control_Background::get_type(),
	[
		'name'     => 'service_head_hover_background',
		'label'    => __( 'Background', 'absolute-addons' ),
		'types'    => [ 'classic', 'gradient' ],
		'selector' => '{{WRAPPER}} .absp-service .absp-service-item:hover .absp-service-left',
	]
);

$this->add_group_control(
	Group_Control_Border::get_type(),
	[
		'name'     => 'service_head_border',
		'label'    => __( 'Border', 'absolute-addons' ),
		'selector' => '{{WRAPPER}} .absp-service .absp-service-item:hover .absp-service-left',
	]
);

$this->end_controls_tab();

$this->end_controls_tabs();

$this->end_controls_section();

$this->start_controls_section(
	'service_number_section',
	[
		'label'     => __( 'Service Number', 'absolute-addons' ),
		'tab'       => Controls_Manager::TAB_STYLE,
		'condition' => [
			'absolute_service' => [ 'one', 'two', 'three', 'four', 'five', 'six' ],
		],
	]
);

$this->start_controls_tabs( 'service_number_tabs' );

$this->start_controls_tab(
	'service_number_normal',
	[
		'label' => __( 'Normal', 'absolute-addons' ),
	]
);

$this->add_group_control(
	Group_Control_Typography::get_type(),
	[
		'label'    => __( 'Typography', 'absolute-addons' ),
		'name'     => 'service_number_normal_typography',
		'selector' => '{{WRAPPER}} .absp-service .absp-service-item .absp-service-number',
	]
);

$this->add_control(
	'service_number_color',
	[
		'label'     => __( 'Number Color', 'absolute-addons' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => [
			'{{WRAPPER}} .absp-service .absp-service-item .absp-service-number' => 'color: {{VALUE}}',
		],
		'condition' => [
			'absolute_service' => 'five',
		],
	]
);

$this->add_group_control(
	Group_Control_Background::get_type(),
	[
		'name'     => 'service_number_bg',
		'label'    => __( 'Background', 'absolute-addons' ),
		'types'    => [ 'classic', 'gradient' ],
		'selector' => '{{WRAPPER}} .absp-service .absp-service-item .absp-service-number',
	]
);

$this->add_responsive_control(
	'service_number_normal_width',
	[
		'label'      => __( 'Number Width', 'absolute-addons' ),
		'type'       => Controls_Manager::SLIDER,
		'size_units' => [ 'px', '%' ],
		'range'      => [
			'px' => [
				'min'  => 0,
				'max'  => 1200,
				'step' => 1,
			],
			'%'  => [
				'min' => 0,
				'max' => 100,
			],
		],
		'selectors'  => [
			'{{WRAPPER}} .absp-service .absp-service-item .absp-service-number' => 'width: {{SIZE}}{{UNIT}};',
		],
	]
);

$this->add_responsive_control(
	'service_number_normal_height',
	[
		'label'      => __( 'Number Height', 'absolute-addons' ),
		'type'       => Controls_Manager::SLIDER,
		'size_units' => [ 'px', '%' ],
		'range'      => [
			'px' => [
				'min'  => 0,
				'max'  => 1200,
				'step' => 1,
			],
			'%'  => [
				'min' => 0,
				'max' => 100,
			],
		],
		'selectors'  => [
			'{{WRAPPER}} .absp-service .absp-service-item .absp-service-number' => 'height: {{SIZE}}{{UNIT}}; line-height: {{SIZE}}{{UNIT}};',
		],
	]
);

$this->add_responsive_control(
	'service_number_radius',
	[
		'label'      => __( 'Number Border Radius', 'absolute-addons' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'em', '%' ],
		'selectors'  => [
			'{{WRAPPER}} .absp-service .absp-service-item .absp-service-number' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
	]
);

$this->end_controls_tab();

$this->start_controls_tab(
	'service_number_hover',
	[
		'label' => __( 'Hover', 'absolute-addons' ),
	]
);

$this->add_group_control(
	Group_Control_Background::get_type(),
	[
		'name'     => 'service_number_hover_background',
		'label'    => __( 'Background', 'absolute-addons' ),
		'types'    => [ 'classic', 'gradient' ],
		'selector' => '{{WRAPPER}} .absp-service .absp-service-item:hover .absp-service-number',
	]
);

$this->add_control(
	'service_number_hover_color',
	[
		'label'     => __( 'Number Color', 'absolute-addons' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => [
			'{{WRAPPER}} .absp-service .absp-service-item:hover .absp-service-number' => 'color: {{VALUE}}',
		],
	]
);

$this->end_controls_tab();

$this->end_controls_tabs();

$this->end_controls_section();

$this->start_controls_section(
	'service_title_section',
	[
		'label' => __( 'Service Title', 'absolute-addons' ),
		'tab'   => Controls_Manager::TAB_STYLE,
	]
);

$this->start_controls_tabs( 'service_title_tabs' );

$this->start_controls_tab(
	'service_title_normal',
	[
		'label' => __( 'Normal', 'absolute-addons' ),
	]
);

$this->add_group_control(
	Group_Control_Typography::get_type(),
	[
		'label'    => __( 'Typography', 'absolute-addons' ),
		'name'     => 'service_title_normal_typography',
		'selector' => '{{WRAPPER}} .absp-service .absp-service-item .absp-service-title',
	]
);

$this->add_control(
	'service_title_color',
	[
		'label'     => __( 'Title Color', 'absolute-addons' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => [
			'{{WRAPPER}} .absp-service .absp-service-item .absp-service-title' => 'color: {{VALUE}}',
		],
		'condition' => [
			'absolute_service' => 'five',
		],
	]
);

$this->add_responsive_control(
	'service_title_normal_padding',
	[
		'label'      => __( 'Title Padding', 'absolute-addons' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'rem', 'em', '%' ],
		'selectors'  => [
			'{{WRAPPER}} .absp-service .absp-service-item .absp-service-title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
	]
);

$this->end_controls_tab();

$this->start_controls_tab(
	'service_title_hover',
	[
		'label' => __( 'Hover', 'absolute-addons' ),
	]
);

$this->add_control(
	'service_title_hover_color',
	[
		'label'     => __( 'Title Color', 'absolute-addons' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => [
			'{{WRAPPER}} .absp-service .absp-service-item:hover .absp-service-title'          => 'color: {{VALUE}}',
			'{{WRAPPER}} .absp-service .absp-service-twenty-nine .absp-service-item:hover hr' => 'background-color: {{VALUE}}',
		],
	]
);

$this->end_controls_tab();

$this->end_controls_tabs();

$this->end_controls_section();

$this->start_controls_section(
	'service_subtitle_section',
	[
		'label'     => __( 'Service Sub title', 'absolute-addons' ),
		'tab'       => Controls_Manager::TAB_STYLE,
		'condition' => [
			'absolute_service' => 'twenty-five',
		],
	]
);

$this->start_controls_tabs( 'service_subtitle_tabs' );

$this->start_controls_tab(
	'service_subtitle_normal',
	[
		'label' => __( 'Normal', 'absolute-addons' ),
	]
);

$this->add_group_control(
	Group_Control_Typography::get_type(),
	[
		'label'    => __( 'Typography', 'absolute-addons' ),
		'name'     => 'service_subtitle_normal_typography',
		'selector' => '{{WRAPPER}} .absp-service .absp-service-item .absp-service-subtitle',
	]
);

$this->add_group_control(
	Group_Control_Background::get_type(),
	[
		'name'     => 'service_subtitle_normal_background',
		'label'    => __( 'Background', 'absolute-addons' ),
		'types'    => [ 'classic', 'gradient' ],
		'selector' => '{{WRAPPER}} .absp-service .absp-service-item .absp-service-subtitle',
	]
);

$this->add_responsive_control(
	'service_subtitle_radius',
	[
		'label'      => __( 'Border Radius', 'absolute-addons' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'em', '%' ],
		'selectors'  => [
			'{{WRAPPER}} .absp-service .absp-service-item .absp-service-subtitle' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
	]
);

$this->add_responsive_control(
	'service_subtitle_normal_padding',
	[
		'label'      => __( 'Sub Title Padding', 'absolute-addons' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'rem', 'em', '%' ],
		'selectors'  => [
			'{{WRAPPER}} .absp-service .absp-service-item .absp-service-subtitle' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
	]
);

$this->end_controls_tab();

$this->start_controls_tab(
	'service_subtitle_hover',
	[
		'label' => __( 'Hover', 'absolute-addons' ),
	]
);

$this->add_group_control(
	Group_Control_Background::get_type(),
	[
		'name'     => 'service_subtitle_hover_background',
		'label'    => __( 'Background', 'absolute-addons' ),
		'types'    => [ 'classic', 'gradient' ],
		'selector' => '{{WRAPPER}} .absp-service .absp-service-item:hover .absp-service-subtitle',
	]
);

$this->add_control(
	'service_subtitle_hover_color',
	[
		'label'     => __( 'Sub Title Color', 'absolute-addons' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => [
			'{{WRAPPER}} .absp-service .absp-service-item:hover .absp-service-subtitle' => 'color: {{VALUE}}',
		],
	]
);

$this->end_controls_tab();

$this->end_controls_tabs();

$this->end_controls_section();

$this->start_controls_section(
	'service_icon_section',
	[
		'label'     => __( 'Service Icon', 'absolute-addons' ),
		'tab'       => Controls_Manager::TAB_STYLE,
		'condition' => [
			'absolute_service' => [ 'one', 'nine', 'ten', 'nineteen' ],
		],
	]
);

$this->add_control(
	'service_alignment',
	[
		'label'     => __( 'Alignment', 'absolute-addons' ),
		'type'      => Controls_Manager::CHOOSE,
		'options'   => [
			'flex-start' => [
				'title' => __( 'Top', 'absolute-addons' ),
				'icon'  => 'eicon-h-align-left fa-rotate-90',
			],
			'center'     => [
				'title' => __( 'Center', 'absolute-addons' ),
				'icon'  => 'eicon-h-align-center fa-rotate-90',
			],
			'flex-end'   => [
				'title' => __( 'Bottom', 'absolute-addons' ),
				'icon'  => 'eicon-h-align-right fa-rotate-90',
			],
		],
		'default'   => 'center',
		'toggle'    => true,
		'condition' => [
			'absolute_service' => 'nine',
		],
	]
);

$this->start_controls_tabs( 'service_icon_tabs' );

$this->start_controls_tab(
	'service_icon_normal',
	[
		'label' => __( 'Normal', 'absolute-addons' ),
	]
);

$this->add_group_control(
	Group_Control_Typography::get_type(),
	[
		'label'       => __( 'Typography', 'absolute-addons' ),
		'name'        => 'service_icon_normal_typography',
		'description' => 'Control icon size',
		'selector'    => '{{WRAPPER}} .absp-service .absp-service-item .absp-service-icon',
	]
);

$this->add_group_control(
	Group_Control_Background::get_type(),
	[
		'name'     => 'service_icon_background',
		'label'    => __( 'Background', 'absolute-addons' ),
		'types'    => [ 'classic', 'gradient' ],
		'selector' => '{{WRAPPER}} .absp-service .absp-service-item .absp-service-icon',
	]
);

$this->add_control(
	'service_icon_normal_color',
	[
		'label'     => __( 'Icon Color', 'absolute-addons' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => [
			'{{WRAPPER}} .absp-service .absp-service-item .absp-service-icon' => 'color: {{VALUE}}',
		],
		'condition' => [
			'absolute_service!' => 'nineteen',
		],
	]
);

$this->add_group_control(
	Group_Control_Border::get_type(),
	[
		'name'      => 'icon_border',
		'label'     => __( 'Border', 'absolute-addons' ),
		'selector'  => '{{WRAPPER}} .absp-service .absp-service-item .absp-service-icon',
		'condition' => [
			'absolute_service' => 'nineteen',
		],
	]
);

$this->add_group_control(
	Group_Control_Box_Shadow::get_type(),
	[
		'name'      => 'icon_box_shadow',
		'label'     => __( 'Box Shadow', 'absolute-addons' ),
		'selector'  => '{{WRAPPER}} .absp-service .absp-service-item .absp-service-icon',
		'default'   => [
			'horizontal' => 2,
			'vertical'   => 3,
			'blur'       => 2,
			'spread'     => 2,
			'color'      => 'rgba(0,0,0,0.05)',
		],
		'condition' => [
			'absolute_service' => 'nineteen',
		],
	]
);

$this->add_responsive_control(
	'service_icon_radius',
	[
		'label'      => __( 'Image Border Radius', 'absolute-addons' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'em', '%' ],
		'selectors'  => [
			'{{WRAPPER}} .absp-service .absp-service-item .absp-service-icon' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
		'condition'  => [
			'absolute_service' => 'nineteen',
		],
	]
);

$this->add_responsive_control(
	'service_icon_normal_padding',
	[
		'label'      => __( 'Icon Padding', 'absolute-addons' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'rem', 'em', '%' ],
		'selectors'  => [
			'{{WRAPPER}} .absp-service .absp-service-item .absp-service-icon' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
	]
);

$this->add_responsive_control(
	'service_icon_normal_margin',
	[
		'label'      => __( 'Icon Margin', 'absolute-addons' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'rem', 'em', '%' ],
		'selectors'  => [
			'{{WRAPPER}} .absp-service .absp-service-item .absp-service-icon' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
	]
);

$this->add_responsive_control(
	'service_icon_normal_border_radius',
	[
		'label'      => __( 'Icon Border Radius', 'absolute-addons' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', '%' ],
		'selectors'  => [
			'{{WRAPPER}} .absp-service .absp-service-item .absp-service-icon' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
	]
);

$this->end_controls_tab();

$this->start_controls_tab(
	'service_icon_hover',
	[
		'label' => __( 'Hover', 'absolute-addons' ),
	]
);

$this->add_group_control(
	Group_Control_Background::get_type(),
	[
		'name'      => 'service_icon_background_hover',
		'label'     => __( 'Background', 'absolute-addons' ),
		'types'     => [ 'classic', 'gradient' ],
		'selector'  => '{{WRAPPER}} .absp-service .absp-service-item:hover .absp-service-icon',
		'condition' => [
			'absolute_service' => 'nineteen',
		],
	]
);

$this->add_control(
	'service_icon_hover_color',
	[
		'label'     => __( 'Icon Color', 'absolute-addons' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => [
			'{{WRAPPER}} .absp-service .absp-service-item:hover .absp-service-icon' => 'color: {{VALUE}}',
		],
	]
);

$this->end_controls_tab();

$this->end_controls_tabs();

$this->end_controls_section();

$this->start_controls_section(
	'service_content_section',
	[
		'label' => __( 'Service Content', 'absolute-addons' ),
		'tab'   => Controls_Manager::TAB_STYLE,
	]
);

$this->start_controls_tabs( 'service_content_tabs' );

$this->start_controls_tab(
	'service_content_normal',
	[
		'label' => __( 'Normal', 'absolute-addons' ),
	]
);

$this->add_group_control(
	Group_Control_Typography::get_type(),
	[
		'label'    => __( 'Typography', 'absolute-addons' ),
		'name'     => 'service_content_normal_typography',
		'selector' => '{{WRAPPER}} .absp-service .absp-service-item .absp-service-content',
	]
);

$this->add_control(
	'service_content_normal_color',
	[
		'label'     => __( 'Content Color', 'absolute-addons' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => [
			'{{WRAPPER}} .absp-service .absp-service-item .absp-service-content' => 'color: {{VALUE}}',
		],
	]
);

$this->add_group_control(
	Group_Control_Border::get_type(),
	[
		'name'     => 'service_content_border',
		'label'    => __( 'Border', 'absolute-addons' ),
		'selector' => '{{WRAPPER}} .absp-service .absp-service-item .absp-service-content',
	]
);

$this->add_responsive_control(
	'service_content_normal_padding',
	[
		'label'      => __( 'Content Padding', 'absolute-addons' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'rem', 'em', '%' ],
		'selectors'  => [
			'{{WRAPPER}} .absp-service .absp-service-item .absp-service-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
	]
);

$this->end_controls_tab();

$this->start_controls_tab(
	'service_content_hover',
	[
		'label' => __( 'Hover', 'absolute-addons' ),
	]
);

$this->add_control(
	'service_content_hover_color',
	[
		'label'     => __( 'Content Color', 'absolute-addons' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => [
			'{{WRAPPER}} .absp-service .absp-service-item:hover .absp-service-content' => 'color: {{VALUE}}',
		],
	]
);

$this->end_controls_tab();

$this->end_controls_tabs();

$this->end_controls_section();

/*
 * Button
 */
$this->start_controls_section(
	'service_btn',
	[
		'label'     => __( 'Button', 'absolute-addons' ),
		'tab'       => Controls_Manager::TAB_STYLE,
		'condition' => [
			'absp_service_btn_switch' => 'yes',
			'absolute_service'        => [ 'five', 'six', 'nine', 'ten' ],
		],
	]
);

$this->start_controls_tabs( 'service_btn_tabs' );

$this->start_controls_tab(
	'service_btn_tabs_normal',
	[
		'label' => __( 'Normal', 'absolute-addons' ),
	]
);

$this->add_group_control(
	Group_Control_Typography::get_type(), [
		'name'     => 'absp_service_btn_typography',
		'selector' => '{{WRAPPER}} .absp-service .absp-btn',
	]
);

$this->add_group_control(
	Group_Control_Background::get_type(),
	[
		'name'           => 'service_btn_background',
		'fields_options' => [
			'background' => [
				'label' => __( 'Background Color', 'absolute-addons' ),
			],
		],
		'types'          => [ 'classic', 'gradient' ],
		'selector'       => '{{WRAPPER}} .absp-service .absp-btn',
		'default'        => '',
	]
);

$this->add_control(
	'absp_service_btn_color',
	[
		'label'     => __( 'Color', 'absolute-addons' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => [
			'{{WRAPPER}} .absp-service .absp-btn' => 'color: {{VALUE}};',
		],
	]
);

$this->add_group_control(
	Group_Control_Border::get_type(),
	[
		'label'    => __( 'Border', 'absolute-addons' ),
		'name'     => 'service_btn_border',
		'selector' => '{{WRAPPER}} .absp-service .absp-btn',
	]
);

$this->add_responsive_control(
	'service_btn_border_radius',
	[
		'label'      => __( 'Border Radius', 'absolute-addons' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', '%' ],
		'selectors'  => [
			'{{WRAPPER}} .absp-service .absp-btn' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
	]
);

$this->add_group_control(
	Group_Control_box_Shadow::get_type(), [
		'name'     => 'absp_service_btn_shadow',
		'selector' => '{{WRAPPER}} .absp-service .absp-btn',
	]
);

$this->add_responsive_control(
	'service_btn_padding',
	[
		'label'      => __( 'Padding', 'absolute-addons' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'rem' ],
		'selectors'  => [
			'{{WRAPPER}} .absp-service .absp-btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
	]
);

$this->add_responsive_control(
	'service_btn_margin',
	[
		'label'      => __( 'Margin', 'absolute-addons' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'rem' ],
		'selectors'  => [
			'{{WRAPPER}} .absp-service .absp-btn' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
	]
);

$this->end_controls_tab();

$this->start_controls_tab(
	'absp_service_btn_hover',
	[
		'label' => __( 'Hover', 'absolute-addons' ),
	]
);

$this->add_group_control(
	Group_Control_Background::get_type(),
	[
		'name'           => 'service_btn_background_hover',
		'fields_options' => [
			'background' => [
				'label' => __( 'Background Color', 'absolute-addons' ),
			],
		],
		'types'          => [ 'classic', 'gradient' ],
		'selector'       => '{{WRAPPER}} .absp-service .absp-btn:hover',
		'default'        => '',
	]
);

$this->add_control(
	'absp_service_btn_hover_color',
	[
		'label'     => __( 'Color', 'absolute-addons' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => [
			'{{WRAPPER}} .absp-service .absp-btn:hover' => 'color: {{VALUE}};',
		],
	]
);

$this->add_group_control(
	Group_Control_Border::get_type(),
	[
		'label'    => __( 'Border', 'absolute-addons' ),
		'name'     => 'service_btn_border_hover',
		'selector' => '{{WRAPPER}} .absp-service .absp-btn:hover',
	]
);

$this->end_controls_tab();

$this->end_controls_tabs();

$this->end_controls_section();
