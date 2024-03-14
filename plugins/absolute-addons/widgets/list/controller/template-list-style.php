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
	'list_style',
	[
		'label' => __( 'List Style', 'absolute-addons' ),
		'tab'   => Controls_Manager::TAB_STYLE,
	]
);

$this->add_group_control(
	Group_Control_Background::get_type(),
	[
		'name'     => 'list_background',
		'label'    => __( 'Background', 'absolute-addons' ),
		'types'    => [ 'classic', 'gradient' ],
		'selector' => '{{WRAPPER}} .absp-list .absp-list-widget',
	]
);

$this->add_group_control(
	Group_Control_Box_Shadow::get_type(),
	[
		'name'     => 'list_box_shadow',
		'label'    => __( 'Box Shadow', 'absolute-addons' ),
		'selector' => '{{WRAPPER}} .absp-list .absp-list-widget',
	]
);

$this->add_group_control(
	Group_Control_Border::get_type(),
	[
		'name'     => 'list_border',
		'label'    => __( 'Border', 'absolute-addons' ),
		'selector' => '{{WRAPPER}} .absp-list .absp-list-widget',
	]
);

$this->add_responsive_control(
	'list_border_radius',
	[
		'label'      => __( 'Border Radius', 'absolute-addons' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'em', '%' ],
		'selectors'  => [
			'{{WRAPPER}} .absp-list .absp-list-widget' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',

		],
	]
);

$this->add_responsive_control(
	'list_padding',
	[
		'label'      => __( 'Padding', 'absolute-addons' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'rem', 'em', '%' ],
		'selectors'  => [
			'{{WRAPPER}} .absp-list .absp-list-widget' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
	]
);

$this->add_responsive_control(
	'list_margin',
	[
		'label'      => __( 'Margin', 'absolute-addons' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'rem', 'em', '%' ],
		'selectors'  => [
			'{{WRAPPER}} .absp-list .absp-list-widget' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
	]
);

$this->end_controls_section();

$this->start_controls_section(
	'list_wrapper',
	[
		'label' => __( 'List Item', 'absolute-addons' ),
		'tab'   => Controls_Manager::TAB_STYLE,
	]
);

$this->add_group_control(
	Group_Control_Background::get_type(),
	[
		'name'     => 'list_wrapper_background',
		'label'    => __( 'Background', 'absolute-addons' ),
		'types'    => [ 'classic', 'gradient' ],
		'selector' => '{{WRAPPER}} .absp-list .absp-list-widget .absp-list-widget-item',
	]
);

$this->add_group_control(
	Group_Control_Box_Shadow::get_type(),
	[
		'name'     => 'list_wrapper_box_shadow',
		'label'    => __( 'Box Shadow', 'absolute-addons' ),
		'selector' => '{{WRAPPER}} .absp-list .absp-list-widget .absp-list-widget-item',
	]
);

$this->add_group_control(
	Group_Control_Border::get_type(),
	[
		'name'     => 'list_wrapper_border',
		'label'    => __( 'Border', 'absolute-addons' ),
		'selector' => '{{WRAPPER}} .absp-list .absp-list-widget .absp-list-widget-item',
	]
);

$this->add_responsive_control(
	'list_wrapper_border_top',
	[
		'label'      => __( 'Border Bottom Only', 'absolute-addons' ),
		'type'       => Controls_Manager::SLIDER,
		'size_units' => [ 'px', '%' ],
		'range'      => [
			'px' => [
				'min'  => 0,
				'max'  => 10,
				'step' => 1,
			],
			'%'  => [
				'min' => 0,
				'max' => 100,
			],
		],
		'selectors'  => [
			'{{WRAPPER}} .absp-list-widget .absp-list-widget-item + .absp-list-widget-item' => 'border-top: {{SIZE}}{{UNIT}};',
		],
		'condition'  => [
			'absolute_list' => 'one',
		],
	]
);

$this->add_responsive_control(
	'list_wrapper_border_radius',
	[
		'label'      => __( 'Border Radius', 'absolute-addons' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'em', '%' ],
		'selectors'  => [
			'{{WRAPPER}} .absp-list .absp-list-widget .absp-list-widget-item' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',

		],
	]
);

$this->add_responsive_control(
	'list_wrapper_padding',
	[
		'label'      => __( 'Padding', 'absolute-addons' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'rem', 'em', '%' ],
		'selectors'  => [
			'{{WRAPPER}} .absp-list .absp-list-widget .absp-list-widget-item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
	]
);

$this->add_responsive_control(
	'list_wrapper_margin',
	[
		'label'      => __( 'Margin', 'absolute-addons' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'rem', 'em', '%' ],
		'selectors'  => [
			'{{WRAPPER}} .absp-list .absp-list-widget .absp-list-widget-item' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
	]
);

$this->end_controls_section();

$this->start_controls_section(
	'list_icon',
	[
		'label'     => __( 'List Icon', 'absolute-addons' ),
		'tab'       => Controls_Manager::TAB_STYLE,
		'condition' => [
			'absolute_list!' => [ 'four', 'eight', 'nine', 'thirteen' ],
		],
	]
);

$this->add_responsive_control(
	'list_icon_font_size',
	[
		'label'      => __( 'Font Size', 'absolute-addons' ),
		'type'       => Controls_Manager::SLIDER,
		'size_units' => [ 'px', '%' ],
		'range'      => [
			'px' => [
				'min'  => 0,
				'max'  => 100,
				'step' => 1,
			],
			'%'  => [
				'min' => 0,
				'max' => 100,
			],
		],
		'selectors'  => [
			'{{WRAPPER}} .absp-list-widget ul li i' => 'font-size: {{SIZE}}{{UNIT}};',
		],
	]
);

$this->add_responsive_control(
	'list_icon_width',
	[
		'label'      => __( 'Width', 'absolute-addons' ),
		'type'       => Controls_Manager::SLIDER,
		'size_units' => [ 'px', '%' ],
		'range'      => [
			'px' => [
				'min'  => 0,
				'max'  => 300,
				'step' => 1,
			],
			'%'  => [
				'min' => 0,
				'max' => 100,
			],
		],
		'selectors'  => [
			'{{WRAPPER}} .absp-list-widget ul li .list_wrapper_bg' => 'width: {{SIZE}}{{UNIT}};',
		],
		'condition'  => [
			'absolute_list' => 'three',
		],
	]
);

$this->add_responsive_control(
	'list_icon_height',
	[
		'label'      => __( 'Height', 'absolute-addons' ),
		'type'       => Controls_Manager::SLIDER,
		'size_units' => [ 'px', '%' ],
		'range'      => [
			'px' => [
				'min'  => 0,
				'max'  => 300,
				'step' => 1,
			],
			'%'  => [
				'min' => 0,
				'max' => 100,
			],
		],
		'selectors'  => [
			'{{WRAPPER}} .absp-list-widget ul li .list_wrapper_bg' => 'height: {{SIZE}}{{UNIT}}; line-height: {{SIZE}}{{UNIT}};',
		],
		'condition'  => [
			'absolute_list' => 'three',
		],
	]
);

$this->add_control(
	'list_icon_color',
	[
		'label'     => __( 'Icon Color', 'absolute-addons' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => [
			'{{WRAPPER}} .absp-list-widget ul li i' => 'color: {{VALUE}}',
		],
		'condition' => [
			'absolute_list!' => 'six',
		],
	]
);

$this->add_group_control(
	Group_Control_Box_Shadow::get_type(),
	[
		'name'     => 'list_icon_box_shadow',
		'label'    => __( 'Box Shadow', 'absolute-addons' ),
		'selector' => '{{WRAPPER}} .absp-wrapper .absp-list-content-four .list_wrapper_bg',
	]
);

$this->end_controls_section();

$this->start_controls_section(
	'list_title',
	[
		'label'     => __( 'List Title', 'absolute-addons' ),
		'tab'       => Controls_Manager::TAB_STYLE,
		'condition' => [
			'absolute_list!' => [ 'six', 'nine' ],
		],
	]
);

$this->add_group_control(
	Group_Control_Typography::get_type(),
	[
		'label'    => __( 'Typography', 'absolute-addons' ),
		'name'     => 'list_title_typography',
		'selector' => '{{WRAPPER}} .absp-list-widget ul li .list-title, {{WRAPPER}} .absp-list-widget ul li .absp-list-title',
	]
);

$this->add_control(
	'list_title_color',
	[
		'label'     => __( 'Title Color', 'absolute-addons' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => [
			'{{WRAPPER}} .absp-list-widget ul li .list-title' => 'color: {{VALUE}}',
			'{{WRAPPER}} .absp-list-widget ul li .absp-list-title' => 'color: {{VALUE}}',
		],
	]
);

$this->end_controls_section();

$this->start_controls_section(
	'list_sub_title',
	[
		'label'     => __( 'List Sub Title', 'absolute-addons' ),
		'tab'       => Controls_Manager::TAB_STYLE,
		'condition' => [
			'absolute_list' => [ 'ten', 'thirteen' ],
		],
	]
);

$this->add_group_control(
	Group_Control_Typography::get_type(),
	[
		'label'    => __( 'Typography', 'absolute-addons' ),
		'name'     => 'list_sub_title_typography',
		'selector' => '{{WRAPPER}} .absp-wrapper .absp-list .absp-list-widget .absp-sub-title',
	]
);

$this->add_control(
	'list_sub_title_color',
	[
		'label'     => __( 'Sub Title Color', 'absolute-addons' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => [
			'{{WRAPPER}} .absp-wrapper .absp-list .absp-list-widget .absp-sub-title' => 'color: {{VALUE}}',
		],
	]
);

$this->end_controls_section();

$this->start_controls_section(
	'list_content',
	[
		'label'     => __( 'List Content', 'absolute-addons' ),
		'tab'       => Controls_Manager::TAB_STYLE,
		'condition' => [
			'absolute_list!' => [ 'one', 'two', 'three', 'ten' ],
		],
	]
);

$this->add_group_control(
	Group_Control_Typography::get_type(),
	[
		'label'    => __( 'Content Typography', 'absolute-addons' ),
		'name'     => 'list_content_typography',
		'selector' => '{{WRAPPER}} .absp-list-widget .content p, {{WRAPPER}} .absp-list-widget .absp-list-content, {{WRAPPER}} .absp-list-widget .list-content',
	]
);

$this->add_control(
	'list_content_color',
	[
		'label'     => __( 'Content Color', 'absolute-addons' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => [
			'{{WRAPPER}} .absp-list-widget .content p'    => 'color: {{VALUE}}',
			'{{WRAPPER}} .absp-list-widget .absp-list-content' => 'color: {{VALUE}}',
			'{{WRAPPER}} .absp-list-widget .list-content' => 'color: {{VALUE}}',
		],
	]
);

$this->add_responsive_control(
	'list_content_padding',
	[
		'label'      => __( 'Padding', 'absolute-addons' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'rem', 'em', '%' ],
		'selectors'  => [
			'{{WRAPPER}} .absp-list-widget .absp-list-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
	]
);

$this->end_controls_section();

$this->start_controls_section(
	'list_image',
	[
		'label'     => __( 'List Image', 'absolute-addons' ),
		'tab'       => Controls_Manager::TAB_STYLE,
		'condition' => [
			'absolute_list' => [ 'four', 'fifteen' ],
		],
	]
);

$this->add_responsive_control(
	'list_image_width',
	[
		'label'      => __( 'Image Width', 'absolute-addons' ),
		'type'       => Controls_Manager::SLIDER,
		'size_units' => [ 'px', '%' ],
		'range'      => [
			'px' => [
				'min'  => 0,
				'max'  => 300,
				'step' => 1,
			],
			'%'  => [
				'min' => 0,
				'max' => 100,
			],
		],
		'selectors'  => [
			'{{WRAPPER}} .absp-list .absp-list-widget :is(.image, img)' => 'width: {{SIZE}}{{UNIT}};',
		],
	]
);

$this->add_responsive_control(
	'list_image_height',
	[
		'label'      => __( 'Image Height', 'absolute-addons' ),
		'type'       => Controls_Manager::SLIDER,
		'size_units' => [ 'px', '%' ],
		'range'      => [
			'px' => [
				'min'  => 0,
				'max'  => 300,
				'step' => 1,
			],
			'%'  => [
				'min' => 0,
				'max' => 100,
			],
		],
		'selectors'  => [
			'{{WRAPPER}} .absp-list .absp-list-widget :is(.image, img)' => 'height: {{SIZE}}{{UNIT}}; line-height: {{SIZE}}{{UNIT}};',
		],
	]
);

$this->add_responsive_control(
	'list_image_border_radius',
	[
		'label'      => __( 'Image Border Radius', 'absolute-addons' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'em', '%' ],
		'selectors'  => [
			'{{WRAPPER}} .absp-list .absp-list-widget :is(.image, img)' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
	]
);

$this->end_controls_section();

// Start lis number style.
$this->start_controls_section(
	'list_number',
	[
		'label'     => __( 'List Number', 'absolute-addons' ),
		'tab'       => Controls_Manager::TAB_STYLE,
		'condition' => [
			'absolute_list' => [ 'one', 'two', 'three', 'eight', 'nine' ],
		],
	]
);

	$this->start_controls_tabs( 'list_number_tabs' );

	// List number normal style
	$this->start_controls_tab(
		'list_number_normal',
		[
			'label' => __( 'Normal', 'absolute-addons' ),
		]
	);
		// List control style.
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'label'    => __( 'Number Typography', 'absolute-addons' ),
				'name'     => 'list_number_typography',
				'selector' => '{{WRAPPER}} .absp-list .absp-list-widget .absp-list-wrapper-bg, {{WRAPPER}} .absp-list .absp-list-widget .absp-list-number',
			]
		);

		$this->add_responsive_control(
			'list_number_width',
			[
				'label'      => __( 'Number Width', 'absolute-addons' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range'      => [
					'px' => [
						'min'  => 0,
						'max'  => 300,
						'step' => 1,
					],
					'%'  => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} .absp-list .absp-list-widget .absp-list-wrapper-bg' => 'width: {{SIZE}}{{UNIT}};',
				],
				'condition'  => [
					'absolute_list' => 'three',
				],
			]
		);

		$this->add_responsive_control(
			'list_number_height',
			[
				'label'      => __( 'Number Height', 'absolute-addons' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range'      => [
					'px' => [
						'min'  => 0,
						'max'  => 300,
						'step' => 1,
					],
					'%'  => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} .absp-list .absp-list-widget .absp-list-wrapper-bg' => 'height: {{SIZE}}{{UNIT}}; line-height: {{SIZE}}{{UNIT}};',
				],
				'condition'  => [
					'absolute_list' => 'three',
				],
			]
		);

		$this->add_responsive_control(
			'list_number_border_radius',
			[
				'label'      => __( 'Number Border Radius', 'absolute-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .absp-list .absp-list-widget .absp-list-wrapper-bg' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition'  => [
					'absolute_list' => 'three',
				],
			]
		);

		$this->add_control(
			'absp_list_number_color',
			[
				'label'     => __( 'Number Color', 'absolute-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .absp-list-widget ul li .absp-list-number ' => 'color: {{VALUE}}',
				],
				'condition' => [
					'absolute_list!' => [ 'three', 'six' ],
				],
			]
		);
		$this->add_control(
			'absp_list_number_background_color',
			[
				'label'     => __( 'Background Color', 'absolute-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .absp-list-widget ul li .absp-list-number ' => 'background-color: {{VALUE}};',
				],
				'condition' => [
					'absolute_list!' => [ 'three', 'six' ],
				],
			]
		);

	$this->end_controls_tab();// End normal tab.

	$this->start_controls_tab(
		'list_number_hover',
		[
			'label' => __( 'Hover', 'absolute-addons' ),
		]
	);

		// List control style
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'label'    => __( 'Number Hover Typography', 'absolute-addons' ),
				'name'     => 'list_number_typography_hover',
				'selector' => '{{WRAPPER}} .absp-list .absp-list-widget .absp-list-wrapper-bg:hover, {{WRAPPER}} .absp-list .absp-list-widget .absp-list-number:hover',
			]
		);

		$this->add_responsive_control(
			'list_number_width_hover',
			[
				'label'      => __( 'Number Width', 'absolute-addons' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range'      => [
					'px' => [
						'min'  => 0,
						'max'  => 300,
						'step' => 1,
					],
					'%'  => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} .absp-list .absp-list-widget .absp-list-wrapper-bg:hover' => 'width: {{SIZE}}{{UNIT}};',
				],
				'condition'  => [
					'absolute_list' => 'three',
				],
			]
		);

		$this->add_responsive_control(
			'list_number_height_hover',
			[
				'label'      => __( 'Number Height', 'absolute-addons' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range'      => [
					'px' => [
						'min'  => 0,
						'max'  => 300,
						'step' => 1,
					],
					'%'  => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} .absp-list .absp-list-widget .absp-list-wrapper-bg:hover' => 'height: {{SIZE}}{{UNIT}}; line-height: {{SIZE}}{{UNIT}};',
				],
				'condition'  => [
					'absolute_list' => 'three',
				],
			]
		);

		$this->add_responsive_control(
			'list_number_border_radius_hover',
			[
				'label'      => __( 'Number Border Radius', 'absolute-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .absp-list .absp-list-widget .absp-list-wrapper-bg:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition'  => [
					'absolute_list' => 'three',
				],
			]
		);

		$this->add_control(
			'absp_list_number_color_hover',
			[
				'label'     => __( 'Number Color', 'absolute-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .absp-list .absp-list-widget .absp-list-widget-item:hover .absp-list-number' => 'color: {{VALUE}}',
				],
				'condition' => [
					'absolute_list!' => [ 'three', 'six' ],
				],
			]
		);

		$this->add_control(
			'absp_list_number_background_color_hover',
			[
				'label'     => __( 'Background Color', 'absolute-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .absp-list .absp-list-widget .absp-list-widget-item:hover .absp-list-number' => 'background-color: {{VALUE}}',
				],
				'condition' => [
					'absolute_list!' => [ 'three', 'six' ],
				],
			]
		);


	$this->end_controls_tab();// End hover tab.
	$this->end_controls_tabs();

$this->end_controls_section();// End list number section
