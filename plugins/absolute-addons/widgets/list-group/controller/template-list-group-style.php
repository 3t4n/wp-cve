<?php

use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Background;

if ( ! defined( 'ABSPATH' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	die();
}

$this->start_controls_section(
	'list_group_style',
	[
		'label' => esc_html__( 'List Group Style', 'absolute-addons' ),
		'tab'   => Controls_Manager::TAB_STYLE,
	]
);

$this->add_responsive_control(
	'width',
	[
		'label'      => esc_html__( 'Width', 'absolute-addons' ),
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
			'{{WRAPPER}} .absp-list-group .list-group' => 'max-width: {{SIZE}}{{UNIT}};',
		],
	]
);

$this->add_group_control(
	Group_Control_Background::get_type(),
	[
		'name'     => 'list_group_background',
		'label'    => esc_html__( 'Background', 'absolute-addons' ),
		'types'    => [ 'classic', 'gradient' ],
		'selector' => '{{WRAPPER}} .absp-list-group .list-group',
	]
);

$this->add_group_control(
	Group_Control_Box_Shadow::get_type(),
	[
		'name'     => 'box_shadow',
		'label'    => esc_html__( 'Box Shadow', 'absolute-addons' ),
		'selector' => '{{WRAPPER}} .absp-list-group .list-group',
	]
);

$this->add_group_control(
	Group_Control_Border::get_type(),
	[
		'name'     => 'list_group_border',
		'label'    => esc_html__( 'Border', 'absolute-addons' ),
		'selector' => '{{WRAPPER}} .absp-list-group .list-group',
	]
);

$this->add_responsive_control(
	'list_group_border_radius',
	[
		'label'      => esc_html__( 'Border Radius', 'absolute-addons' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'em', '%' ],
		'selectors'  => [
			'{{WRAPPER}} .absp-list-group .list-group' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',

		],
	]
);

$this->add_responsive_control(
	'list_group_padding',
	[
		'label'      => esc_html__( 'Padding', 'absolute-addons' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'rem', 'em', '%' ],
		'selectors'  => [
			'{{WRAPPER}} .absp-list-group .list-group' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
	]
);

$this->add_responsive_control(
	'list_group_margin',
	[
		'label'      => esc_html__( 'Margin', 'absolute-addons' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'rem', 'em', '%' ],
		'selectors'  => [
			'{{WRAPPER}} .absp-list-group .list-group' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
	]
);

$this->end_controls_section();

$this->start_controls_section(
	'list_group_item',
	[
		'label' => esc_html__( 'List Group Item', 'absolute-addons' ),
		'tab'   => Controls_Manager::TAB_STYLE,
	]
);

$this->start_controls_tabs( 'list_group_item_tabs' );

$this->start_controls_tab(
	'list_group_item_normal',
	[
		'label' => esc_html__( 'Normal', 'absolute-addons' ),
	]
);

$this->add_responsive_control(
	'list_group_item_width',
	[
		'label'      => esc_html__( 'Item Width', 'absolute-addons' ),
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
			'{{WRAPPER}} .absp-list-group .list-group-item' => 'max-width: {{SIZE}}{{UNIT}};',
		],
	]
);

$this->add_group_control(
	Group_Control_Background::get_type(),
	[
		'name'     => 'list_group_item_background',
		'label'    => esc_html__( 'Background', 'absolute-addons' ),
		'types'    => [ 'classic', 'gradient' ],
		'selector' => '{{WRAPPER}} .absp-list-group .list-group-item',
	]
);

$this->add_group_control(
	Group_Control_Box_Shadow::get_type(),
	[
		'name'     => 'item_box_shadow',
		'label'    => esc_html__( 'Box Shadow', 'absolute-addons' ),
		'selector' => '{{WRAPPER}} .absp-list-group .list-group-item',
	]
);

$this->add_group_control(
	Group_Control_Border::get_type(),
	[
		'name'     => 'list_group_item_border',
		'label'    => esc_html__( 'Border', 'absolute-addons' ),
		'selector' => '{{WRAPPER}} .absp-list-group .list-group-item',
	]
);

$this->add_responsive_control(
	'list_group_item_border_radius',
	[
		'label'      => esc_html__( 'Item Border Radius', 'absolute-addons' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'em', '%' ],
		'selectors'  => [
			'{{WRAPPER}} .absp-list-group .list-group-item' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',

		],
	]
);

$this->add_responsive_control(
	'list_group_item_padding',
	[
		'label'      => esc_html__( 'Item Padding', 'absolute-addons' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'rem', 'em', '%' ],
		'selectors'  => [
			'{{WRAPPER}} .absp-list-group .list-group-item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
	]
);

$this->add_responsive_control(
	'list_group_item_margin',
	[
		'label'      => esc_html__( 'Item Margin', 'absolute-addons' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'rem', 'em', '%' ],
		'selectors'  => [
			'{{WRAPPER}} .absp-list-group .list-group-item' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
	]
);

$this->end_controls_tab();

$this->start_controls_tab(
	'item_hover',
	[
		'label' => esc_html__( 'Hover', 'absolute-addons' ),
	]
);

$this->add_group_control(
	Group_Control_Background::get_type(),
	[
		'name'     => 'list_group_item_background_hover',
		'label'    => esc_html__( 'Background', 'absolute-addons' ),
		'types'    => [ 'classic', 'gradient' ],
		'selector' => '{{WRAPPER}} .absp-list-group .list-group-item:hover',
	]
);

$this->add_group_control(
	Group_Control_Box_Shadow::get_type(),
	[
		'name'     => 'item_box_shadow_hover',
		'label'    => esc_html__( 'Box Shadow', 'absolute-addons' ),
		'selector' => '{{WRAPPER}} .absp-list-group .list-group-item:hover',
	]
);

$this->add_group_control(
	Group_Control_Border::get_type(),
	[
		'name'     => 'list_group_item_border_hover',
		'label'    => esc_html__( 'Border', 'absolute-addons' ),
		'selector' => '{{WRAPPER}} .absp-list-group .list-group-item:hover',
	]
);

$this->add_responsive_control(
	'list_group_item_border_radius_hover',
	[
		'label'      => esc_html__( 'Item Border Radius', 'absolute-addons' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'em', '%' ],
		'selectors'  => [
			'{{WRAPPER}} .absp-list-group .list-group-item:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
	]
);

$this->end_controls_tab();

$this->end_controls_tabs();

$this->end_controls_section();

$this->start_controls_section(
	'list_group_title',
	[
		'label' => esc_html__( 'Title', 'absolute-addons' ),
		'tab'   => Controls_Manager::TAB_STYLE,
	]
);

$this->start_controls_tabs( 'list_group_title_tabs' );

$this->start_controls_tab(
	'list_group_title_normal',
	[
		'label' => esc_html__( 'Normal', 'absolute-addons' ),
	]
);

$this->add_group_control(
	Group_Control_Typography::get_type(),
	[
		'label'    => esc_html__( 'Typography', 'absolute-addons' ),
		'name'     => 'list_group_title_typography',
		'selector' => '{{WRAPPER}} .absp-list-group .list-group-item h4 a',
	]
);

$this->add_group_control(
	Group_Control_Background::get_type(),
	[
		'name'     => 'list_group_title_background',
		'label'    => esc_html__( 'Background', 'absolute-addons' ),
		'types'    => [ 'classic', 'gradient' ],
		'selector' => '{{WRAPPER}} .absp-list-group .list-group-item h4 a',
	]
);

$this->add_control(
	'list_group_title_color',
	[
		'label'     => esc_html__( 'Title Color', 'absolute-addons' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => [
			'{{WRAPPER}} .absp-list-group .list-group-item h4 a' => 'color: {{VALUE}}',
		],
	]
);

$this->add_responsive_control(
	'list_group_title_padding',
	[
		'label'      => esc_html__( 'Title Padding', 'absolute-addons' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'rem', 'em', '%' ],
		'selectors'  => [
			'{{WRAPPER}} .absp-list-group .list-group-item h4 a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
	]
);

$this->end_controls_tab();

$this->start_controls_tab(
	'list_group_title_hover',
	[
		'label' => esc_html__( 'Hover', 'absolute-addons' ),
	]
);

$this->add_group_control(
	Group_Control_Background::get_type(),
	[
		'name'     => 'list_group_title_background_hover',
		'label'    => esc_html__( 'Background', 'absolute-addons' ),
		'types'    => [ 'classic', 'gradient' ],
		'selector' => '{{WRAPPER}} .absp-list-group:hover .list-group-item h4 a',
	]
);

$this->add_control(
	'list_group_title_color_hover',
	[
		'label'     => esc_html__( 'Title Color', 'absolute-addons' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => [
			'{{WRAPPER}} .absp-list-group .list-group-item:hover h4 a' => 'color: {{VALUE}}',
		],
	]
);

$this->end_controls_tab();

$this->end_controls_tabs();

$this->end_controls_section();

$this->start_controls_section(
	'list_group_list_icon',
	[
		'label'     => esc_html__( 'List Icon', 'absolute-addons' ),
		'tab'       => Controls_Manager::TAB_STYLE,
		'condition' => [
			'absolute_list_group!' => [ 'one', 'three', 'four', 'six', 'eight', 'eleven', 'thirteen' ],
		],
	]
);

$this->start_controls_tabs( 'list_group_list_icon_tabs' );

$this->start_controls_tab(
	'list_group_list_icon_normal',
	[
		'label' => esc_html__( 'Normal', 'absolute-addons' ),
	]
);

$this->add_responsive_control(
	'list_icon_width',
	[
		'label'      => esc_html__( 'List Icon Width', 'absolute-addons' ),
		'type'       => Controls_Manager::SLIDER,
		'size_units' => [ 'px', '%' ],
		'range'      => [
			'px' => [
				'min'  => 0,
				'max'  => 200,
				'step' => 1,
			],
			'%'  => [
				'min' => 0,
				'max' => 100,
			],
		],
		'selectors'  => [
			'{{WRAPPER}} .absp-list-group .list-group-item .list-icon' => 'width: {{SIZE}}{{UNIT}}; text-align: center;',
		],
	]
);

$this->add_responsive_control(
	'list_icon_height',
	[
		'label'      => esc_html__( 'List Icon Height', 'absolute-addons' ),
		'type'       => Controls_Manager::SLIDER,
		'size_units' => [ 'px', '%' ],
		'range'      => [
			'px' => [
				'min'  => 0,
				'max'  => 200,
				'step' => 1,
			],
			'%'  => [
				'min' => 0,
				'max' => 100,
			],
		],
		'selectors'  => [
			'{{WRAPPER}} .absp-list-group .list-group-item .list-icon' => 'height: {{SIZE}}{{UNIT}}; line-height: {{SIZE}}{{UNIT}};',
		],
	]
);

$this->add_group_control(
	Group_Control_Background::get_type(),
	[
		'name'     => 'list_group_list_icon_background',
		'label'    => esc_html__( 'Background', 'absolute-addons' ),
		'types'    => [ 'classic', 'gradient' ],
		'selector' => '{{WRAPPER}} .absp-list-group .list-group-item .list-icon',
	]
);

$this->add_control(
	'list_group_list_icon_color',
	[
		'label'     => esc_html__( 'List Icon Color', 'absolute-addons' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => [
			'{{WRAPPER}} .absp-list-group .list-group-item .list-icon' => 'color: {{VALUE}}',
		],
	]
);

$this->add_responsive_control(
	'list_group_list_icon_border_radius',
	[
		'label'      => esc_html__( 'Item Border Radius', 'absolute-addons' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'em', '%' ],
		'selectors'  => [
			'{{WRAPPER}} .absp-list-group .list-group-item .list-icon' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
	]
);

$this->add_responsive_control(
	'list_group_list_icon_margin',
	[
		'label'      => esc_html__( 'List Icon Margin', 'absolute-addons' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'rem', 'em', '%' ],
		'selectors'  => [
			'{{WRAPPER}} .absp-list-group .list-group-item .list-icon' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
	]
);

$this->end_controls_tab();

$this->start_controls_tab(
	'list_group_list_icon_hover',
	[
		'label' => esc_html__( 'Hover', 'absolute-addons' ),
	]
);

$this->add_group_control(
	Group_Control_Background::get_type(),
	[
		'name'     => 'list_group_list_icon_background_hover',
		'label'    => esc_html__( 'Background', 'absolute-addons' ),
		'types'    => [ 'classic', 'gradient' ],
		'selector' => '{{WRAPPER}} .absp-list-group:hover .list-group-item .list-icon',
	]
);

$this->add_control(
	'list_group_list_icon_color_hover',
	[
		'label'     => esc_html__( 'Title Color', 'absolute-addons' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => [
			'{{WRAPPER}} .absp-list-group .list-group-item:hover .list-icon' => 'color: {{VALUE}}',
		],
	]
);

$this->end_controls_tab();

$this->end_controls_tabs();

$this->end_controls_section();

$this->start_controls_section(
	'list_group_list_number',
	[
		'label'     => esc_html__( 'List Number', 'absolute-addons' ),
		'tab'       => Controls_Manager::TAB_STYLE,
		'condition' => [
			'absolute_list_group' => [ 'three', 'four', 'six', 'eight', 'eleven', 'thirteen' ],
		],
	]
);

$this->start_controls_tabs( 'list_group_list_number_tabs' );

$this->start_controls_tab(
	'list_group_list_number_normal',
	[
		'label' => esc_html__( 'Normal', 'absolute-addons' ),
	]
);

$this->add_responsive_control(
	'list_number_width',
	[
		'label'      => esc_html__( 'List Icon Width', 'absolute-addons' ),
		'type'       => Controls_Manager::SLIDER,
		'size_units' => [ 'px', '%' ],
		'range'      => [
			'px' => [
				'min'  => 0,
				'max'  => 200,
				'step' => 1,
			],
			'%'  => [
				'min' => 0,
				'max' => 100,
			],
		],
		'selectors'  => [
			'{{WRAPPER}} .absp-list-group .list-group-item .list-number' => 'max-width: {{SIZE}}{{UNIT}}; text-align: center;',
		],
	]
);

$this->add_responsive_control(
	'list_number_height',
	[
		'label'      => esc_html__( 'List Icon Height', 'absolute-addons' ),
		'type'       => Controls_Manager::SLIDER,
		'size_units' => [ 'px', '%' ],
		'range'      => [
			'px' => [
				'min'  => 0,
				'max'  => 200,
				'step' => 1,
			],
			'%'  => [
				'min' => 0,
				'max' => 100,
			],
		],
		'selectors'  => [
			'{{WRAPPER}} .absp-list-group .list-group-item .list-number' => 'max-height: {{SIZE}}{{UNIT}}; line-height: {{SIZE}}{{UNIT}};',
		],
	]
);

$this->add_group_control(
	Group_Control_Typography::get_type(),
	[
		'label'    => esc_html__( 'Typography', 'absolute-addons' ),
		'name'     => 'list_group_list_number_typography',
		'selector' => '{{WRAPPER}} .absp-list-group .list-group-item .list-number',
	]
);

$this->add_group_control(
	Group_Control_Background::get_type(),
	[
		'name'     => 'list_group_list_number_background',
		'label'    => esc_html__( 'Background', 'absolute-addons' ),
		'types'    => [ 'classic', 'gradient' ],
		'selector' => '{{WRAPPER}} .absp-list-group .list-group-item .list-number',
	]
);

$this->add_control(
	'list_group_list_number_color',
	[
		'label'     => esc_html__( 'List Icon Color', 'absolute-addons' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => [
			'{{WRAPPER}} .absp-list-group .list-group-item .list-number' => 'color: {{VALUE}}',
		],
	]
);

$this->add_responsive_control(
	'list_group_list_number_border_radius',
	[
		'label'      => esc_html__( 'Item Border Radius', 'absolute-addons' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'em', '%' ],
		'selectors'  => [
			'{{WRAPPER}} .absp-list-group .list-group-item .list-number' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
	]
);

$this->add_responsive_control(
	'list_group_list_number_margin',
	[
		'label'      => esc_html__( 'List Icon Margin', 'absolute-addons' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'rem', 'em', '%' ],
		'selectors'  => [
			'{{WRAPPER}} .absp-list-group .list-group-item .list-number' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
	]
);

$this->end_controls_tab();

$this->start_controls_tab(
	'list_group_list_number_hover',
	[
		'label' => esc_html__( 'Hover', 'absolute-addons' ),
	]
);

$this->add_group_control(
	Group_Control_Background::get_type(),
	[
		'name'     => 'list_group_list_number_background_hover',
		'label'    => esc_html__( 'Background', 'absolute-addons' ),
		'types'    => [ 'classic', 'gradient' ],
		'selector' => '{{WRAPPER}} .absp-list-group:hover .list-group-item .list-number',
	]
);

$this->add_control(
	'list_group_list_number_color_hover',
	[
		'label'     => esc_html__( 'Title Color', 'absolute-addons' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => [
			'{{WRAPPER}} .absp-list-group .list-group-item:hover .list-number' => 'color: {{VALUE}}',
		],
	]
);

$this->end_controls_tab();

$this->end_controls_tabs();

$this->end_controls_section();

$this->start_controls_section(
	'list_group_icon',
	[
		'label' => esc_html__( 'Group Icon', 'absolute-addons' ),
		'tab'   => Controls_Manager::TAB_STYLE,
	]
);

$this->start_controls_tabs( 'list_group_icon_tabs' );

$this->start_controls_tab(
	'list_group_icon_normal',
	[
		'label' => esc_html__( 'Normal', 'absolute-addons' ),
	]
);

$this->add_responsive_control(
	'icon_width',
	[
		'label'      => esc_html__( 'Icon Width', 'absolute-addons' ),
		'type'       => Controls_Manager::SLIDER,
		'size_units' => [ 'px', '%' ],
		'range'      => [
			'px' => [
				'min'  => 0,
				'max'  => 200,
				'step' => 1,
			],
			'%'  => [
				'min' => 0,
				'max' => 100,
			],
		],
		'selectors'  => [
			'{{WRAPPER}} .absp-list-group .list-group-item .list-group-icon' => 'max-width: {{SIZE}}{{UNIT}}; text-align: center;',
		],
	]
);

$this->add_responsive_control(
	'icon_height',
	[
		'label'      => esc_html__( 'Icon Height', 'absolute-addons' ),
		'type'       => Controls_Manager::SLIDER,
		'size_units' => [ 'px', '%' ],
		'range'      => [
			'px' => [
				'min'  => 0,
				'max'  => 200,
				'step' => 1,
			],
			'%'  => [
				'min' => 0,
				'max' => 100,
			],
		],
		'selectors'  => [
			'{{WRAPPER}} .absp-list-group .list-group-item .list-group-icon' => 'max-height: {{SIZE}}{{UNIT}}; line-height: {{SIZE}}{{UNIT}};',
		],
	]
);

$this->add_group_control(
	Group_Control_Background::get_type(),
	[
		'name'     => 'list_group_icon_background',
		'label'    => esc_html__( 'Background', 'absolute-addons' ),
		'types'    => [ 'classic', 'gradient' ],
		'selector' => '{{WRAPPER}} .absp-list-group .list-group-item .list-group-icon',
	]
);

$this->add_control(
	'list_group_icon_color',
	[
		'label'     => esc_html__( 'List Icon Color', 'absolute-addons' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => [
			'{{WRAPPER}} .absp-list-group .list-group-item .list-group-icon' => 'color: {{VALUE}}',
		],
	]
);

$this->add_responsive_control(
	'list_group_icon_border_radius',
	[
		'label'      => esc_html__( 'Icon Border Radius', 'absolute-addons' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'em', '%' ],
		'selectors'  => [
			'{{WRAPPER}} .absp-list-group .list-group-item .list-group-icon' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
	]
);

$this->add_responsive_control(
	'list_group_icon_margin',
	[
		'label'      => esc_html__( 'Icon Margin', 'absolute-addons' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'rem', 'em', '%' ],
		'selectors'  => [
			'{{WRAPPER}} .absp-list-group .list-group-item .list-group-icon' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
	]
);

$this->end_controls_tab();

$this->start_controls_tab(
	'list_group_icon_hover',
	[
		'label' => esc_html__( 'Hover', 'absolute-addons' ),
	]
);

$this->add_group_control(
	Group_Control_Background::get_type(),
	[
		'name'     => 'list_group_icon_background_hover',
		'label'    => esc_html__( 'Background', 'absolute-addons' ),
		'types'    => [ 'classic', 'gradient' ],
		'selector' => '{{WRAPPER}} .absp-list-group .list-group-item:hover .list-group-icon',
	]
);

$this->add_control(
	'list_group_icon_color_hover',
	[
		'label'     => esc_html__( 'Icon Color', 'absolute-addons' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => [
			'{{WRAPPER}} .absp-list-group .list-group-item:hover .list-group-icon' => 'color: {{VALUE}}',
		],
	]
);

$this->end_controls_tab();

$this->end_controls_tabs();

$this->end_controls_section();
