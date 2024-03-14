<?php

namespace Borderless\Widgets;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

use \Elementor\Controls_Manager;
use \Elementor\Group_Control_Background;
use \Elementor\Group_Control_Box_Shadow;
use \Elementor\Group_Control_Typography;
use \Elementor\Core\Schemes\Typography;
use \Elementor\Widget_Base;

class Circular_Progress_Bar extends Widget_Base {
	
	public function get_name() {
		return 'borderless-elementor-circular-progress-bar';
	}
	
	public function get_title() {
		return esc_html__( 'Circular Progress Bar', 'borderless');
	}
	
	public function get_icon() {
		return 'borderless-icon-circular-progress-bar';
	}
	
	public function get_categories() {
		return [ 'borderless' ];
	}

	public function get_keywords()
	{
        return [
			'circular progress bar',
			'progress bar',
			'bar',
			'borderless',
			'borderless circular progress bar',
			'borderless progress bar',
			'borderless bar'
		];
    }

	public function get_custom_help_url()
	{
        return 'https://wpborderless.com/';
    }

	public function get_style_depends() {
		return 
			[ 
				'borderless-elementor-style'
			];
	}
	
	public function get_script_depends() {
		return 
			[ 
				'borderless-elementor-appear-script',
				'borderless-elementor-progressbar-script'
			];
	}
	
	protected function _register_controls() {


		/*-----------------------------------------------------------------------------------*/
		/*  *.  Layout
		/*-----------------------------------------------------------------------------------*/

		$this->start_controls_section(
			'borderless_section_circular_progress_bar_layout',
			[
				'label' => esc_html__( 'Layout', 'borderless' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'borderless_circular_progress_bar_title',
			[
				'label' => esc_html__( 'Title', 'borderless'),
				'type' => Controls_Manager::TEXT,
                'dynamic' => [
                    'active' => true,
                ],
			]
		);

		$this->add_control(
			'borderless_circular_progress_bar_counter_value',
			[
				'label' => esc_html__( 'Counter Value', 'borderless'),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['%'],
					'range' => [
						'%' => [
						'min' => 1,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => '%',
					'size' => 50,
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
            'borderless_circular_progress_bar_animation_duration',
            [
                'label' => __('Animation Duration', 'borderless'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 1000,
                        'max' => 10000,
                        'step' => 100,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 1500,
                ],
                'separator' => 'before',
            ]
        );

		$this->end_controls_section();


	/*-----------------------------------------------------------------------------------*/
	/*  *.  Layout - Style
	/*-----------------------------------------------------------------------------------*/

	$this->start_controls_section(
		'borderless_section_circular_progress_bar_styles_general',
		[
			'label' => esc_html__( 'General', 'borderless'),
			'tab' => Controls_Manager::TAB_STYLE
		]
	);

	$this->add_control(
		'borderless_circular_progress_bar_alignment',
		[
			'label' => __('Alignment', 'borderless'),
			'type' => \Elementor\Controls_Manager::CHOOSE,
			'options' => [
				'borderless-circular-progress-bar-alignment-left' => [
					'title' => __('Left', 'borderless'),
					'icon' => 'fa fa-align-left',
				],
				'borderless-circular-progress-bar-alignment-center' => [
					'title' => __('Center', 'borderless'),
					'icon' => 'fa fa-align-center',
				],
				'borderless-circular-progress-bar-alignment-right' => [
					'title' => __('Right', 'borderless'),
					'icon' => 'fa fa-align-right',
				],
			],
			'default' => 'borderless-circular-progress-bar-alignment-center',
		]
	);

	$this->add_control(
		'borderless_circular_progress_bar_size',
		[
			'label' => __('Size', 'borderless'),
			'type' => Controls_Manager::SLIDER,
			'size_units' => ['px'],
			'range' => [
				'px' => [
					'min' => 50,
					'max' => 500,
					'step' => 1,
				],
			],
			'default' => [
				'unit' => 'px',
				'size' => 200,
			],
			'selectors' => [
				'{{WRAPPER}} .borderless-elementor-circular-progress-bar' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
			],
			'separator' => 'before',
		]
	);

	$this->add_control(
		'borderless_circular_progress_bar_stroke_width',
		[
			'label' => __('Stroke Width', 'borderless'),
			'type' => Controls_Manager::SLIDER,
			'size_units' => ['px'],
			'range' => [
				'px' => [
					'min' => 0,
					'max' => 100,
					'step' => 1,
				],
			],
			'default' => [
				'unit' => 'px',
				'size' => 12,
			],
			'separator' => 'before',
		]
	);

	$this->add_control(
		'borderless_circular_progress_bar_stroke_color_style',
		[
			'label' => __( 'Stroke Color Style', 'borderless' ),
			'type' => \Elementor\Controls_Manager::SELECT,
			'default' => 'borderless-elementor-circular-progress-bar-stroke-solid-color',
			'options' => [
				'borderless-elementor-circular-progress-bar-stroke-solid-color'  => __( 'Solid', 'borderless' ),
				'borderless-elementor-circular-progress-bar-stroke-gradient-color' => __( 'Gradient', 'borderless' ),
			],
		]
	);

	$this->add_control(
		'borderless_circular_progress_bar_stroke_color',
		[
			'label' => __('Stroke Color', 'borderless'),
			'type' => Controls_Manager::COLOR,
			'default' => '#000',
			'condition' => [
				'borderless_circular_progress_bar_stroke_color_style' => 'borderless-elementor-circular-progress-bar-stroke-solid-color',
			],
		]
	);

	$this->add_control(
		'borderless_circular_progress_bar_stroke_color_from',
		[
			'label' => __('Stroke Color From', 'borderless'),
			'type' => Controls_Manager::COLOR,
			'default' => '#000',
			'condition' => [
				'borderless_circular_progress_bar_stroke_color_style' => 'borderless-elementor-circular-progress-bar-stroke-gradient-color',
			],
		]
	);

	$this->add_control(
		'borderless_circular_progress_bar_stroke_color_to',
		[
			'label' => __('Stroke Color To', 'borderless'),
			'type' => Controls_Manager::COLOR,
			'default' => '#000',
			'condition' => [
				'borderless_circular_progress_bar_stroke_color_style' => 'borderless-elementor-circular-progress-bar-stroke-gradient-color',
			],
		]
	);

	$this->add_control(
		'borderless_circular_progress_bar_trail_width',
		[
			'label' => __('Trail Width', 'borderless'),
			'type' => Controls_Manager::SLIDER,
			'size_units' => ['px'],
			'range' => [
				'px' => [
					'min' => 0,
					'max' => 100,
					'step' => 1,
				],
			],
			'default' => [
				'unit' => 'px',
				'size' => 12,
			],
			'separator' => 'before',
		]
	);

	$this->add_control(
		'borderless_circular_progress_bar_trail_color',
		[
			'label' => __('Trail Color', 'borderless'),
			'type' => Controls_Manager::COLOR,
			'default' => '#eee',
		]
	);

	$this->add_control(
		'borderless_circular_progress_bar_shape',
		[
			'label' => __( 'Shape', 'borderless' ),
			'type' => \Elementor\Controls_Manager::SELECT,
			'default' => 'borderless-elementor-circular-progress-bar--round',
			'options' => [
				'borderless-elementor-circular-progress-bar--square'  => __( 'Square', 'borderless' ),
				'borderless-elementor-circular-progress-bar--round' => __( 'Round', 'borderless' ),
			],
			'separator' => 'before',
		]
	);	

	$this->add_control(
		'borderless_circular_progress_bar_background_color',
		[
			'label' => __('Background Color', 'borderless'),
			'type' => Controls_Manager::COLOR,
			'default' => '#fff',
			'selectors' => [
				'{{WRAPPER}} .borderless-elementor-circular-progress-bar' => 'background-color: {{VALUE}}',
			],
		]
	);

	$this->add_group_control(
		Group_Control_Box_Shadow::get_type(),
		[
			'name' => 'borderless_circular_progress_bar_box_shadow',
			'label' => __('Box Shadow', 'borderless'),
			'selector' => '{{WRAPPER}} .borderless-elementor-circular-progress-bar',
			'separator' => 'before',
		]
	);

	$this->end_controls_section();

	/*-----------------------------------------------------------------------------------*/
	/*  *.  Typography - Style
	/*-----------------------------------------------------------------------------------*/

	$this->start_controls_section(
		'borderless_section_circular_progress_bar_typography',
		[
			'label' => __('Typography', 'borderless'),
			'tab' => Controls_Manager::TAB_STYLE,
		]
	);

	$this->add_group_control(
		Group_Control_Typography::get_type(),
		[
			'name' => 'borderless_circular_progress_bar_title_typography',
			'label' => __('Title', 'borderless'),
			'scheme' => Typography::TYPOGRAPHY_1,
			'selector' => '{{WRAPPER}} .borderless-elementor-circular-progress-bar .progressbar-text .borderless-elementor-circular-progress-bar-title',
		]
	);

	$this->add_control(
		'borderless_circular_progress_bar_title_color',
		[
			'label' => __('Title Color', 'borderless'),
			'type' => Controls_Manager::COLOR,
			'default' => '',
			'selectors' => [
				'{{WRAPPER}} .borderless-elementor-circular-progress-bar .progressbar-text .borderless-elementor-circular-progress-bar-title' => 'color: {{VALUE}}',
			],
			'separator' => 'after',
		]
	);

	$this->add_group_control(
		Group_Control_Typography::get_type(),
		[
			'name' => 'borderless_circular_progress_bar_counter_typography',
			'label' => __('Counter', 'borderless'),
			'scheme' => Typography::TYPOGRAPHY_1,
			'selector' => '{{WRAPPER}} .borderless-elementor-circular-progress-bar .progressbar-text .borderless-elementor-circular-progress-bar-counter-value',
		]
	);

	$this->add_control(
		'borderless_circular_progress_bar_counter_color',
		[
			'label' => __('Counter Color', 'borderless'),
			'type' => Controls_Manager::COLOR,
			'default' => '',
			'separator' => 'after',
		]
	);

	$this->add_group_control(
		Group_Control_Typography::get_type(),
		[
			'name' => 'borderless_circular_progress_bar_postfix_typography',
			'label' => __('Postfix', 'borderless'),
			'scheme' => Typography::TYPOGRAPHY_1,
			'selector' => '{{WRAPPER}} .borderless-elementor-circular-progress-bar-counter-postfix',
		]
	);

	$this->add_control(
		'borderless_circular_progress_bar_postfix_color',
		[
			'label' => __('Postfix Color', 'borderless'),
			'type' => Controls_Manager::COLOR,
			'default' => '',
			'selectors' => [
				'{{WRAPPER}} .borderless-elementor-circular-progress-bar-counter-postfix' => 'color: {{VALUE}}',
			],
		]
	);

	$this->end_controls_section();

	}

	/*-----------------------------------------------------------------------------------*/
	/*  *.  Render
	/*-----------------------------------------------------------------------------------*/
	
	protected function render() {
		$settings = $this->get_settings_for_display();

		if ($settings['borderless_circular_progress_bar_stroke_color_style'] == 'borderless-elementor-circular-progress-bar-stroke-solid-color' ) {
			$borderless_circular_progress_bar_stroke_color_style = 'stroke_color_mode="solid" stroke_color="'.$settings['borderless_circular_progress_bar_stroke_color'].'"';

		} else {
			$borderless_circular_progress_bar_stroke_color_style = 'stroke_color_mode="gradient" stroke_color="'.$settings['borderless_circular_progress_bar_stroke_color'].'" stroke_color_from="'.$settings['borderless_circular_progress_bar_stroke_color_from'].'" stroke_color_to="'.$settings['borderless_circular_progress_bar_stroke_color_to'].'"';
		}

		echo'<div class="borderless-elementor-circular-progress-bar-widget '.$settings['borderless_circular_progress_bar_alignment'].'"><div class="borderless-elementor-circular-progress-bar '.$settings['borderless_circular_progress_bar_shape'].'" title="'.$settings['borderless_circular_progress_bar_title'].'" counter_value="'.$settings['borderless_circular_progress_bar_counter_value']['size'].'" '.$borderless_circular_progress_bar_stroke_color_style.' trail_color="'.$settings['borderless_circular_progress_bar_trail_color'].'" counter_color="'.$settings['borderless_circular_progress_bar_counter_color'].'" stroke_width="'.$settings['borderless_circular_progress_bar_stroke_width']['size'].'" trail_width="'.$settings['borderless_circular_progress_bar_trail_width']['size'].'" animation_duration="'.$settings['borderless_circular_progress_bar_animation_duration']['size'].'">
		</div></div>';

	}
	
	protected function _content_template() {

    }
	
	
}