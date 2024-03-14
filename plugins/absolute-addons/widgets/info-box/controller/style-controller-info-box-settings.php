<?php
defined( 'ABSPATH' ) || exit;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;


$this->start_controls_section(
	'section_settings',
	[
		'label' => esc_html__( 'Settings', 'absolute-addons' ),
		'tab'   => Controls_Manager::TAB_STYLE,
	]
);

$this->start_controls_tabs( 'settings_section_tabs' );
// Normal State Tab
$this->start_controls_tab(
	'settings_section_normal',
	[
		'label' => esc_html__( 'Normal', 'absolute-addons' ),
	]
);

$this->add_group_control(
	Group_Control_Background::get_type(),
	[
		'name'           => 'body_section_background',
		'label'          => esc_html__( 'Body Section Background', 'absolute-addons' ),
		'label_block'    => true,
		'types'          => [ 'classic', 'gradient' ],
		'fields_options' => [
			'background' => [
				'label' => 'Body Section Background',
			],
		],
		'selector'       => '{{WRAPPER}} .absp-wrapper .absp-info-box-item',
	]
);

$this->add_group_control(
	Group_Control_Border::get_type(),
	[
		'name'           => 'body_border',
		'label'          => esc_html__( 'Body Border', 'absolute-addons' ),
		'fields_options' => [
			'border' => [
				'label' => 'Body Section Border',
			],
		],
		'selector'       => '{{WRAPPER}} .absp-wrapper .absp-info-box-item',
	]
);

$this->add_responsive_control(
	'body_section_border_radius',
	[
		'label'      => esc_html__( 'Body Section Border Radius', 'absolute-addons' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'em', '%' ],
		'selectors'  => [
			'{{WRAPPER}} .absp-wrapper .absp-info-box-item' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
	]
);

$this->add_group_control(
	Group_Control_Box_Shadow::get_type(),
	[
		'name'     => 'body_section_box_shadow',
		'label'    => esc_html__( 'Body Section Box Shadow', 'absolute-addons' ),
		'selector' => '{{WRAPPER}} .absp-wrapper .absp-info-box-item',
	]
);

$this->add_responsive_control(
	'body_section_padding',
	[
		'label'      => esc_html__( 'Padding', 'absolute-addons' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'em', '%' ],
		'selectors'  => [
			'{{WRAPPER}} .absp-wrapper .absp-info-box-item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
	]
);

$this->add_responsive_control(
	'body_section_margin',
	[
		'label'      => esc_html__( 'Margin', 'absolute-addons' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'em', '%' ],
		'selectors'  => [
			'{{WRAPPER}} .absp-wrapper .absp-info-box-item' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
	]
);

$this->end_controls_tab();

// Hover State Tab
$this->start_controls_tab(
	'settings_section_hover',
	[
		'label' => esc_html__( 'Hover', 'absolute-addons' ),
	]
);

$this->add_control(
	'hover_animation_info_box',
	[
		'label'        => esc_html__( 'Body Section Hover Animation', 'absolute-addons' ),
		'type'         => Controls_Manager::HOVER_ANIMATION,
		'prefix_class' => 'elementor-animation-',
	]
);

$this->add_group_control(
	Group_Control_Background::get_type(),
	[
		'name'           => 'body_section_background_hover',
		'label'          => esc_html__( 'Body Section Hover Background', 'absolute-addons' ),
		'label_block'    => true,
		'types'          => [ 'classic', 'gradient' ],
		'fields_options' => [
			'background' => [
				'label' => 'Body Section Hover Background',
			],
		],
		'selector'       => '{{WRAPPER}} .absp-wrapper .absp-info-box-item:hover',
	]
);

$this->add_group_control(
	Group_Control_Border::get_type(),
	[
		'name'           => 'body_section_border_hover',
		'label'          => esc_html__( 'Body Section Hover Border', 'absolute-addons' ),
		'fields_options' => [
			'border' => [
				'label' => 'Body Section Hover Border',
			],
		],
		'selector'       => '{{WRAPPER}} .absp-wrapper .absp-info-box-item:hover',
	]
);

$this->add_responsive_control(
	'body_section_border_radius:hover',
	[
		'label'      => esc_html__( 'Body Section Hover Border Radius', 'absolute-addons' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'em', '%' ],
		'selectors'  => [
			'{{WRAPPER}} .absp-wrapper .absp-info-box-item:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
	]
);

$this->add_group_control(
	Group_Control_Box_Shadow::get_type(),
	[
		'name'     => 'body_section_box_shadow_hover',
		'label'    => esc_html__( 'Body Section Hover Box Shadow', 'absolute-addons' ),
		'selector' => '{{WRAPPER}} .absp-wrapper .absp-info-box-item:hover',
	]
);

$this->add_control(
	'title_color_hover',
	[
		'label'     => esc_html__( 'Title Hover Color', 'absolute-addons' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => [
			'{{WRAPPER}} .absp-wrapper .absp-info-box-item.element-one:hover .info-box-content .info-box-title,
			{{WRAPPER}} .absp-wrapper .absp-info-box-item.element-seven:hover .info-box-content .info-box-title,
			{{WRAPPER}} .absp-wrapper .absp-info-box-item:hover .info-box-content .info-box-title' => 'color: {{VALUE}};',
		],
	]
);

$this->add_control(
	'content_color_hover',
	[
		'label'     => esc_html__( 'Content Hover Color', 'absolute-addons' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => [
			'{{WRAPPER}} .absp-wrapper .absp-info-box-item.element-one:hover .info-box-content,
			{{WRAPPER}} .absp-wrapper .absp-info-box-item.element-three:hover .info-box-content,
			{{WRAPPER}} .absp-wrapper .absp-info-box-item.element-seven:hover .info-box-content,
			{{WRAPPER}} .absp-wrapper .absp-info-box-item.element-twelve:hover .info-box-content,
			{{WRAPPER}} .absp-wrapper .absp-info-box-item:hover .info-box-content' => 'color: {{VALUE}};',
		],
	]
);

$this->add_group_control(
	Group_Control_Background::get_type(),
	[
		'name'           => 'icon_section_background_hover',
		'label'          => esc_html__( 'Icon Section Hover Background', 'absolute-addons' ),
		'label_block'    => true,
		'types'          => [ 'classic', 'gradient' ],
		'fields_options' => [
			'background' => [
				'label' => 'Icon Section Hover Background',
			],
		],
		'selector'       => '{{WRAPPER}} .absp-wrapper .absp-info-box-item:hover .info-box .info-box-icon',
		'conditions'     => [
			'relation' => 'and',
			'terms'    => [
				[
					'name'     => 'absolute_info_box',
					'operator' => '!==',
					'value'    => 'nineteen',
				],
				[
					'name'     => 'absolute_info_box',
					'operator' => '!==',
					'value'    => 'fourteen',
				],
				[
					'name'     => 'absolute_info_box',
					'operator' => '!==',
					'value'    => 'seventeen',
				],
				[
					'name'     => 'absolute_info_box',
					'operator' => '!==',
					'value'    => 'twenty',
				],
				[
					'name'     => 'absolute_info_box',
					'operator' => '!==',
					'value'    => 'twenty-one',
				],

			],
		],
	]
);

$this->add_group_control(
	Group_Control_Border::get_type(),
	[
		'name'           => 'icon_border_hover',
		'label'          => esc_html__( 'Icon Section Hover Border', 'absolute-addons' ),
		'fields_options' => [
			'border' => [
				'label' => 'Icon Section Hover Border',
			],
		],
		'selector'       => '{{WRAPPER}} .absp-wrapper .absp-info-box-item:hover .info-box .info-box-icon',
		'conditions'     => [
			'relation' => 'and',
			'terms'    => [
				[
					'name'     => 'absolute_info_box',
					'operator' => '!==',
					'value'    => 'nineteen',
				],
				[
					'name'     => 'absolute_info_box',
					'operator' => '!==',
					'value'    => 'fourteen',
				],
				[
					'name'     => 'absolute_info_box',
					'operator' => '!==',
					'value'    => 'seventeen',
				],
				[
					'name'     => 'absolute_info_box',
					'operator' => '!==',
					'value'    => 'twenty',
				],
				[
					'name'     => 'absolute_info_box',
					'operator' => '!==',
					'value'    => 'twenty-one',
				],

			],
		],
	]
);

$this->add_responsive_control(
	'icon_section_border_radius_hover',
	[
		'label'      => esc_html__( 'Icon Section Hover Border Radius', 'absolute-addons' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'em', '%' ],
		'selectors'  => [
			'{{WRAPPER}} .absp-wrapper .absp-info-box-item:hover .info-box .info-box-icon' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
		'conditions' => [
			'relation' => 'and',
			'terms'    => [
				[
					'name'     => 'absolute_info_box',
					'operator' => '!==',
					'value'    => 'nineteen',
				],
				[
					'name'     => 'absolute_info_box',
					'operator' => '!==',
					'value'    => 'fourteen',
				],
				[
					'name'     => 'absolute_info_box',
					'operator' => '!==',
					'value'    => 'seventeen',
				],
				[
					'name'     => 'absolute_info_box',
					'operator' => '!==',
					'value'    => 'twenty',
				],
				[
					'name'     => 'absolute_info_box',
					'operator' => '!==',
					'value'    => 'twenty-one',
				],

			],
		],
	]
);

$this->add_group_control(
	Group_Control_Box_Shadow::get_type(),
	[
		'name'       => 'icon_section_box_shadow_hover',
		'label'      => esc_html__( 'Icon Section Hover Box Shadow', 'absolute-addons' ),
		'selector'   => '{{WRAPPER}} .absp-wrapper .absp-info-box-item:hover .info-box .info-box-icon',
		'conditions' => [
			'relation' => 'and',
			'terms'    => [
				[
					'name'     => 'absolute_info_box',
					'operator' => '!==',
					'value'    => 'nineteen',
				],
				[
					'name'     => 'absolute_info_box',
					'operator' => '!==',
					'value'    => 'fourteen',
				],
				[
					'name'     => 'absolute_info_box',
					'operator' => '!==',
					'value'    => 'seventeen',
				],
				[
					'name'     => 'absolute_info_box',
					'operator' => '!==',
					'value'    => 'twenty',
				],
				[
					'name'     => 'absolute_info_box',
					'operator' => '!==',
					'value'    => 'twenty-one',
				],

			],
		],
	]
);

$this->add_control(
	'icon_color_hover',
	[
		'label'     => esc_html__( 'Icon Hover Color', 'absolute-addons' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => [
			'{{WRAPPER}} .absp-wrapper .absp-info-box-item.element-seven:hover .info-box-icon i,
			{{WRAPPER}} .absp-wrapper .absp-info-box-item:hover .info-box .info-box-icon i,
			{{WRAPPER}} .absp-wrapper .absp-info-box-item:hover .info-box-inner .info-box-icon i' => 'color: {{VALUE}};',
		],
	]
);

$this->add_control(
	'info_box_separator_color_hover',
	array(
		'label'       => esc_html__( ' Separator Color', 'absolute-addons' ),
		'type'        => Controls_Manager::COLOR,
		'selectors'   => array(
			'{{WRAPPER}} .absp-wrapper .absp-info-box-item:hover .info-box-separator' => 'background-color: {{VALUE}}',
		),
		'description' => esc_html__( 'Select Separator Hover color.', 'absolute-addons' ),
		'conditions'  => [
			'relation' => 'or',
			'terms'    => [
				[
					'name'     => 'absolute_info_box',
					'operator' => '==',
					'value'    => 'two',
				],
				[
					'name'     => 'absolute_info_box',
					'operator' => '==',
					'value'    => 'ten',
				],
				[
					'name'     => 'absolute_info_box',
					'operator' => '==',
					'value'    => 'eleven',
				],
				[
					'name'     => 'absolute_info_box',
					'operator' => '==',
					'value'    => 'twelve',
				],
				[
					'name'     => 'absolute_info_box',
					'operator' => '==',
					'value'    => 'fourteen',
				],
				[
					'name'     => 'absolute_info_box',
					'operator' => '==',
					'value'    => 'fifteen',
				],
				[
					'name'     => 'absolute_info_box',
					'operator' => '==',
					'value'    => 'thirteen',
				],
				[
					'name'     => 'absolute_info_box',
					'operator' => '==',
					'value'    => 'twenty-four',
				],
			],
		],
	)
);

$this->add_control(
	'shape_section_background_hover',
	array(
		'label'      => esc_html__('Shape Hover Background', 'absolute-addons'),
		'type'       => Controls_Manager::COLOR,
		'selectors'  => array(
			'{{WRAPPER}} .absp-wrapper .absp-info-box-item:hover .info-box-bg-shape-svg' => 'fill: {{VALUE}}',
		),
		'conditions' => [
			'relation' => 'or',
			'terms'    => [
				[
					'name'     => 'absolute_info_box',
					'operator' => '==',
					'value'    => 'five',
				],
				[
					'name'     => 'absolute_info_box',
					'operator' => '==',
					'value'    => 'fourteen',
				],
			],
		],
	)
);

$this->add_control(
	'icon_shape_section_background_hover',
	array(
		'label'     => esc_html__('Icon Shape Hover Top Circle Background', 'absolute-addons'),
		'type'      => Controls_Manager::COLOR,
		'selectors' => array(
			'{{WRAPPER}} .absp-wrapper .absp-info-box-item:hover .info-box-icon .shape-circle1' => 'background-color: {{VALUE}}',
		),
		'condition' => [
			'absolute_info_box' => 'five',
		],
	)
);

$this->add_control(
	'icon_shape2_section_background_hover',
	array(
		'label'     => esc_html__('Icon Shape Hover Bottom Circle Background', 'absolute-addons'),
		'type'      => Controls_Manager::COLOR,
		'selectors' => array(
			'{{WRAPPER}} .absp-wrapper .absp-info-box-item:hover .info-box-icon .shape-circle2' => 'background-color: {{VALUE}}',
		),
		'condition' => [
			'absolute_info_box' => 'five',
		],
	)
);

$this->add_control(
	'hover_button_color_hover',
	[
		'label'      => esc_html__( 'Button Hover Text Color', 'absolute-addons' ),
		'type'       => Controls_Manager::COLOR,
		'selectors'  => [
			'
			{{WRAPPER}} .absp-wrapper .absp-info-box-item:hover .info-box .info-box-content .info-box-btn.button-icon:after,
			{{WRAPPER}} .absp-wrapper .absp-info-box-item:hover .info-box .info-box-content .info-box-btn' => 'color: {{VALUE}};',
		],
		'conditions' => [
			'relation' => 'and',
			'terms'    => [
				[
					'name'     => 'absolute_info_box',
					'operator' => '!==',
					'value'    => 'ten',
				],
				[
					'name'     => 'absolute_info_box',
					'operator' => '!==',
					'value'    => 'thirteen',
				],
				[
					'name'     => 'absolute_info_box',
					'operator' => '!==',
					'value'    => 'twenty-one',
				],
				[
					'name'     => 'absolute_info_box',
					'operator' => '!==',
					'value'    => 'twenty-four',
				],
			],
		],
	]
);

$this->add_group_control(
		Group_Control_Background::get_type(),
		[
			'name'           => 'hover_button_background_hover',
			'label'          => esc_html__( 'Button Hover Background', 'absolute-addons' ),
			'fields_options' => [
				'background' => [
					'label' => 'Button Hover Background',
				],
			],
			'selector'       => '{{WRAPPER}} .absp-wrapper .absp-info-box-item:hover .info-box .info-box-content .info-box-btn',
			'conditions'     => [
				'relation' => 'and',
				'terms'    => [
					[
						'name'     => 'absolute_info_box',
						'operator' => '!==',
						'value'    => 'ten',
					],
					[
						'name'     => 'absolute_info_box',
						'operator' => '!==',
						'value'    => 'thirteen',
					],
					[
						'name'     => 'absolute_info_box',
						'operator' => '!==',
						'value'    => 'twenty-one',
					],
					[
						'name'     => 'absolute_info_box',
						'operator' => '!==',
						'value'    => 'twenty-four',
					],
				],
			],
		]
);

$this->add_control(
	'hover_button_border_color_hover',
	[
		'label'      => esc_html__( 'Button Hover Border Color', 'absolute-addons' ),
		'type'       => Controls_Manager::COLOR,
		'selectors'  => [
			'{{WRAPPER}} .absp-wrapper .absp-info-box-item:hover .info-box .info-box-content .info-box-btn' => 'border-color: {{VALUE}};',
		],
		'conditions' => [
			'relation' => 'and',
			'terms'    => [
				[
					'name'     => 'absolute_info_box',
					'operator' => '!==',
					'value'    => 'ten',
				],
				[
					'name'     => 'absolute_info_box',
					'operator' => '!==',
					'value'    => 'thirteen',
				],
				[
					'name'     => 'absolute_info_box',
					'operator' => '!==',
					'value'    => 'twenty-one',
				],
				[
					'name'     => 'absolute_info_box',
					'operator' => '!==',
					'value'    => 'twenty-four',
				],
			],
		],
	]
);

$this->add_responsive_control(
	'hover_button_border_radius_hover',
	[
		'label'      => esc_html__( 'Button Hover Border Radius', 'absolute-addons' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'em', '%' ],
		'selectors'  => [
			'
			{{WRAPPER}} .absp-wrapper .absp-info-box-item:hover .info-box .info-box-content .info-box-btn' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
		'conditions' => [
			'relation' => 'and',
			'terms'    => [
				[
					'name'     => 'absolute_info_box',
					'operator' => '!==',
					'value'    => 'ten',
				],
				[
					'name'     => 'absolute_info_box',
					'operator' => '!==',
					'value'    => 'thirteen',
				],
				[
					'name'     => 'absolute_info_box',
					'operator' => '!==',
					'value'    => 'twenty-one',
				],
				[
					'name'     => 'absolute_info_box',
					'operator' => '!==',
					'value'    => 'twenty-four',
				],
			],
		],
	]
);

$this->add_group_control(
	Group_Control_Box_Shadow::get_type(),
	[
		'name'       => 'hover_button_box_shadow_hover',
		'label'      => esc_html__( 'Button Hover Box Shadow', 'absolute-addons' ),
		'selector'   => '
		{{WRAPPER}} .absp-wrapper .absp-info-box-item:hover .info-box .info-box-content .info-box-btn',
		'conditions' => [
			'relation' => 'and',
			'terms'    => [
				[
					'name'     => 'absolute_info_box',
					'operator' => '!==',
					'value'    => 'ten',
				],
				[
					'name'     => 'absolute_info_box',
					'operator' => '!==',
					'value'    => 'thirteen',
				],
				[
					'name'     => 'absolute_info_box',
					'operator' => '!==',
					'value'    => 'twenty-one',
				],
				[
					'name'     => 'absolute_info_box',
					'operator' => '!==',
					'value'    => 'twenty-four',
				],
			],
		],
	]
);

$this->end_controls_tab();

$this->end_controls_tabs();

$this->end_controls_section();




