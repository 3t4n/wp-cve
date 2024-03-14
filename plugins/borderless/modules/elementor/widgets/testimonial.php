<?php

namespace Borderless\Widgets;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Image_Size;
use \Elementor\Group_Control_Border;
use \Elementor\Group_Control_Typography;
use \Elementor\Group_Control_Background;
use \Elementor\Group_Control_Css_Filter;
use \Elementor\Group_Control_Box_Shadow;
use \Elementor\Icons_Manager;
use Elementor\Repeater;
use Elementor\Utils;

class Testimonial extends Widget_Base {
	
	public function get_name() {
		return 'borderless-elementor-testimonial';
	}
	
	public function get_title() {
		return esc_html__( 'Testimonial', 'borderless');
	}
	
	public function get_icon() {
		return 'borderless-icon-testimonial';
	}
	
	public function get_categories() {
		return [ 'borderless' ];
	}

	public function get_keywords()
	{
		return [
			'testimonial',
			'testimony',
			'review',
			'endorsement',
			'recommendation',
			'reference',
			'appreciation',
			'feedback',
			'star rating',
			'social proof',
			'borderless',
			'borderless testimonial',
			'borderless testimonials',
		];
    }

	public function get_custom_help_url()
	{
        return 'https://wpborderless.com/';
    }

	public function get_style_depends() {
		return 
			[ 
				'font-awesome-5',
				'borderless-elementor-style'
			];
	}
	
	protected function _register_controls() {


		/*-----------------------------------------------------------------------------------*/
		/*  *.  Avatar
		/*-----------------------------------------------------------------------------------*/

		$this->start_controls_section(
			'borderless_section_testimonial_picture',
			[
				'label' => esc_html__( 'Picture', 'borderless' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'borderless_testimonial_picture',
			[
				'label' => __( 'Upload Picture', 'borderless' ),
				'type' => Controls_Manager::MEDIA,
				'default' => [
					'url' => Utils::get_placeholder_image_src(),
				],
			]
		);

		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			[
				'name' => 'borderless_testimonial_picture',
				'default' => 'large',
				'separator' => 'none',
			]
		);

		$this->add_responsive_control(
			'borderless_testimonial_picture_style',
			[
				'label' => __( 'Object Fit', 'borderless' ),
				'type' => Controls_Manager::SELECT,
				'condition' => [
					'height[size]!' => '',
				],
				'options' => [
					'' => __( 'Default', 'borderless' ),
					'fill' => __( 'Fill', 'borderless' ),
					'cover' => __( 'Cover', 'borderless' ),
					'contain' => __( 'Contain', 'borderless' ),
				],
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .borderless-elementor-testimonial-picture figure img' => 'object-fit: {{VALUE}};',
				],
			]
		);

		$this->end_controls_section();


		/*-----------------------------------------------------------------------------------*/
		/*  *.  Content
		/*-----------------------------------------------------------------------------------*/

		$this->start_controls_section(
			'borderless_section_testimonial_content',
			[
				'label' => esc_html__( 'Content', 'borderless')
			]
		);

		$this->add_control(
			'borderless_testimonial_name',
			[
				'label' => esc_html__( 'Name', 'borderless'),
				'type' => Controls_Manager::TEXT,
                'dynamic' => [
                    'active' => true,
                ],
				'default' => esc_html__( 'John Doe', 'borderless'),
			]
		);

		$this->add_control(
			'borderless_testimonial_job',
			[
				'label' => esc_html__( 'Job Position', 'borderless'),
				'type' => Controls_Manager::TEXT,
                'dynamic' => [
                    'active' => true,
                ],
				'default' => esc_html__( 'Full Stack Web Developer', 'borderless'),
			]
		);

		$this->add_control(
			'borderless_testimonial_description',
			[
				'label' => esc_html__( 'Description', 'borderless'),
				'type' => Controls_Manager::TEXTAREA,
                'dynamic' => [
                    'active' => true,
                ],
				'default' => esc_html__( 'Add team member description here. Remove the text if not necessary.', 'borderless'),
			]
		);

		$this->add_control(
			'borderless_testimonial_enable_rating',
			[
				'label' => esc_html__( 'Display Rating?', 'borderless'),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
			]
		);


		$this->add_control(
		  'borderless_testimonial_rating_number',
		  [
		     'label'       => __( 'Rating Number', 'borderless'),
		     'type' => Controls_Manager::SELECT,
		     'default' => 'rating-five',
		     'options' => [
		     	'rating-one'  => __( '1', 'borderless'),
		     	'rating-two' => __( '2', 'borderless'),
		     	'rating-three' => __( '3', 'borderless'),
		     	'rating-four' => __( '4', 'borderless'),
		     	'rating-five'   => __( '5', 'borderless'),
		     ],
			'condition' => [
				'borderless_testimonial_enable_rating' => 'yes',
			],
		  ]
		);

		$this->end_controls_section();

	/*-----------------------------------------------------------------------------------*/
	/*  *.  Testimonial - Style
	/*-----------------------------------------------------------------------------------*/

	$this->start_controls_section(
		'borderless_section_testimonial_general_styles',
		[
			'label' => esc_html__( 'General', 'borderless'),
			'tab' => Controls_Manager::TAB_STYLE
		]
	);

	$this->add_control(
		'borderless_section_testimonial_general_background',
		[
			'label' => esc_html__( 'Background Color', 'borderless'),
			'type' => Controls_Manager::COLOR,
			'default' => '',
			'selectors' => [
				'{{WRAPPER}} .borderless-elementor-testimonial' => 'background-color: {{VALUE}};',
			],
		]
	);

	

	$start = is_rtl() ? 'end' : 'start';
	$end = is_rtl() ? 'start' : 'end';

	$this->add_responsive_control(
		'borderless_section_testimonial_general_align',
		[
			'label' => __( 'Alignment', 'borderless' ),
			'type' => Controls_Manager::CHOOSE,
			'options' => [
				'left'    => [
					'title' => __( 'Left', 'borderless' ),
					'icon' => 'eicon-text-align-left',
				],
				'center' => [
					'title' => __( 'Center', 'borderless' ),
					'icon' => 'eicon-text-align-center',
				],
				'right' => [
					'title' => __( 'Right', 'borderless' ),
					'icon' => 'eicon-text-align-right',
				],
			],
			'prefix_class' => 'e-grid-align-',
			'default' => 'center',
			'selectors' => [
				'{{WRAPPER}} .borderless-elementor-testimonial' => 'text-align: {{VALUE}}',
			],
		]
	);

	$this->add_responsive_control(
		'borderless_section_testimonial_general_margin',
		[
			'label' => esc_html__( 'Margin', 'borderless'),
			'type' => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', 'em', '%', 'rem' ],
			'selectors' => [
				'{{WRAPPER}} .borderless-elementor-testimonial' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			],
		]
	);

	$this->add_responsive_control(
		'borderless_section_testimonial_general_padding',
		[
			'label' => esc_html__( 'Padding', 'borderless'),
			'type' => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', 'em', '%', 'rem' ],
			'selectors' => [
				'{{WRAPPER}} .borderless-elementor-testimonial' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			],
		]
	);

	$this->add_group_control(
		Group_Control_Border::get_type(),
		[
			'name' => 'borderless_section_testimonial_general_border',
			'label' => esc_html__( 'Border', 'borderless'),
			'selector' => '{{WRAPPER}} .borderless-elementor-testimonial',
		]
	);

	$this->add_control(
		'borderless_section_testimonial_general_border_radius',
		[
			'label' => esc_html__( 'Border Radius', 'borderless'),
			'type' => Controls_Manager::DIMENSIONS,
			'selectors' => [
				'{{WRAPPER}} .borderless-elementor-testimonial' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
			],
		]
	);

	$this->end_controls_section();


	/*-----------------------------------------------------------------------------------*/
	/*  *.  Avatar - Style
	/*-----------------------------------------------------------------------------------*/

	$this->start_controls_section(
		'borderless_section_testimonial_image_styles',
		[
			'label' => esc_html__( 'Picture', 'borderless'),
			'tab' => Controls_Manager::TAB_STYLE
		]
	);

	$this->add_responsive_control(
		'borderless_testimonial_image_width',
		[
			'label' => esc_html__( 'Width', 'borderless'),
			'type' => Controls_Manager::SLIDER,
				'default' => [
					'unit' => '%',
				],
				'tablet_default' => [
					'unit' => '%',
				],
				'mobile_default' => [
					'unit' => '%',
				],
				'size_units' => [ '%', 'px', 'vw' ],
				'range' => [
					'%' => [
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
			'selectors' => [
				'{{WRAPPER}} .borderless-elementor-testimonial-picture figure img' => 'width:{{SIZE}}{{UNIT}};',
			],
		]
	);

	do_action('borderless/testimonial_circle_controls', $this);

	$this->add_responsive_control(
		'borderless_testimonial_image_max_width',
		[
			'label' => __( 'Max Width', 'borderless' ),
			'type' => Controls_Manager::SLIDER,
			'default' => [
				'unit' => '%',
			],
			'tablet_default' => [
				'unit' => '%',
			],
			'mobile_default' => [
				'unit' => '%',
			],
			'size_units' => [ '%', 'px', 'vw' ],
			'range' => [
				'%' => [
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
			'selectors' => [
				'{{WRAPPER}} .borderless-elementor-testimonial-picture figure img' => 'max-width:{{SIZE}}{{UNIT}};',
			],
		]
	);

	$this->add_responsive_control(
		'borderless_testimonial_image_height',
		[
			'label' => __( 'Height', 'borderless' ),
			'type' => Controls_Manager::SLIDER,
			'default' => [
				'unit' => 'px',
			],
			'tablet_default' => [
				'unit' => 'px',
			],
			'mobile_default' => [
				'unit' => 'px',
			],
			'size_units' => [ 'px', 'vh' ],
			'range' => [
				'px' => [
					'min' => 1,
					'max' => 500,
				],
				'vh' => [
					'min' => 1,
					'max' => 100,
				],
			],
			'selectors' => [
				'{{WRAPPER}} .borderless-elementor-testimonial-picture figure img' => 'height: {{SIZE}}{{UNIT}};',
			],
		]
	);

	$this->add_responsive_control(
		'borderless_testimonial_image_margin',
		[
			'label' => esc_html__( 'Margin', 'borderless'),
			'type' => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', 'em', '%', 'rem' ],
			'selectors' => [
				'{{WRAPPER}} .borderless-elementor-testimonial-picture figure img' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			],
		]
	);

	$this->add_responsive_control(
		'borderless_testimonial_image_padding',
		[
			'label' => esc_html__( 'Padding', 'borderless'),
			'type' => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', 'em', '%', 'rem' ],
			'selectors' => [
				'{{WRAPPER}} .borderless-elementor-testimonial-picture figure img' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			],
		]
	);

	$this->add_responsive_control(
		'borderless_testimonial_image_object_fit',
		[
			'label' => __( 'Object Fit', 'borderless' ),
			'type' => Controls_Manager::SELECT,
			'condition' => [
				'height[size]!' => '',
			],
			'options' => [
				'' => __( 'Default', 'borderless' ),
				'fill' => __( 'Fill', 'borderless' ),
				'cover' => __( 'Cover', 'borderless' ),
				'contain' => __( 'Contain', 'borderless' ),
			],
			'default' => '',
			'selectors' => [
				'{{WRAPPER}} .borderless-elementor-testimonial-picture figure img' => 'object-fit: {{VALUE}};',
			],
		]
	);

	$this->add_control(
		'borderless_testimonial_image_separator_panel_style',
		[
			'type' => Controls_Manager::DIVIDER,
			'style' => 'thick',
		]
	);

	$this->start_controls_tabs( 'borderless_testimonial_image_effects' );

	$this->start_controls_tab( 'normal',
		[
			'label' => __( 'Normal', 'borderless' ),
		]
	);

	$this->add_control(
		'borderless_testimonial_image_opacity',
		[
			'label' => __( 'Opacity', 'borderless' ),
			'type' => Controls_Manager::SLIDER,
			'range' => [
				'px' => [
					'max' => 1,
					'min' => 0.10,
					'step' => 0.01,
				],
			],
			'selectors' => [
				'{{WRAPPER}} .borderless-elementor-testimonial-picture figure img' => 'opacity: {{SIZE}};',
			],
		]
	);

	$this->add_group_control(
		Group_Control_Css_Filter::get_type(),
		[
			'name' => 'borderless_testimonial_image_css_filters',
			'selector' => '{{WRAPPER}} .borderless-elementor-testimonial-picture figure img',
		]
	);

	$this->end_controls_tab();

	$this->start_controls_tab( 'hover',
		[
			'label' => __( 'Hover', 'borderless' ),
		]
	);

	$this->add_control(
		'borderless_testimonial_image_opacity_hover',
		[
			'label' => __( 'Opacity', 'borderless' ),
			'type' => Controls_Manager::SLIDER,
			'range' => [
				'px' => [
					'max' => 1,
					'min' => 0.10,
					'step' => 0.01,
				],
			],
			'selectors' => [
				'{{WRAPPER}} .borderless-elementor-testimonial-social-profiles figure:hover img' => 'opacity: {{SIZE}};',
			],
		]
	);

	$this->add_group_control(
		Group_Control_Css_Filter::get_type(),
		[
			'name' => 'borderless_testimonial_image_css_filters_hover',
			'selector' => '{{WRAPPER}} .borderless-elementor-testimonial-social-profiles figure:hover img',
		]
	);

	$this->add_control(
		'borderless_testimonial_image_background_hover_transition',
		[
			'label' => __( 'Transition Duration', 'borderless' ),
			'type' => Controls_Manager::SLIDER,
			'range' => [
				'px' => [
					'max' => 3,
					'step' => 0.1,
				],
			],
			'selectors' => [
				'{{WRAPPER}} .borderless-elementor-testimonial-picture figure img' => 'transition-duration: {{SIZE}}s',
			],
		]
	);

	$this->add_control(
		'borderless_testimonial_image_hover_animation',
		[
			'label' => __( 'Hover Animation', 'borderless' ),
			'type' => Controls_Manager::HOVER_ANIMATION,
		]
	);

	$this->end_controls_tab();

	$this->end_controls_tabs();

	$this->add_group_control(
		Group_Control_Border::get_type(),
		[
			'name' => 'borderless_testimonial_image_border',
			'selector' => '{{WRAPPER}} .borderless-elementor-testimonial-picture figure img',
			'separator' => 'before',
		]
	);

	$this->add_responsive_control(
		'borderless_testimonial_image_border_radius',
		[
			'label' => __( 'Border Radius', 'borderless' ),
			'type' => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', '%' ],
			'selectors' => [
				'{{WRAPPER}} .borderless-elementor-testimonial-picture figure img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			],
		]
	);

	$this->add_group_control(
		Group_Control_Box_Shadow::get_type(),
		[
			'name' => 'borderless_testimonial_image_box_shadow',
			'exclude' => [
				'box_shadow_position',
			],
			'selector' => '{{WRAPPER}} .borderless-elementor-testimonial-picture figure img',
		]
	);

	$this->end_controls_section();


	/*-----------------------------------------------------------------------------------*/
	/*  *.  Content - Style
	/*-----------------------------------------------------------------------------------*/

	$this->start_controls_section(
		'borderless_section_testimonial_content_style',
		[
			'label' => esc_html__( 'Content', 'borderless'),
			'tab' => Controls_Manager::TAB_STYLE
		]
	);

	$this->add_control(
		'borderless_section_testimonial_content_heading_style',
		[
			'label' => __( 'Content Card', 'borderless'),
			'type' => Controls_Manager::HEADING,
			'separator'	=> 'before'
		]
	);

	$this->add_control(
		'borderless_section_testimonial_content__height',
		[
			'label' => esc_html__( 'Height', 'borderless'),
			'type' => Controls_Manager::SLIDER,
			'size_units'	=> [ 'px', 'em' ],
			'range' => [
				'px' => [
					'min' => 0,
					'max' => 500,
				],
				'em'	=> [
					'min'	=> 0,
					'max'	=> 200
				]
			],
			'default'	=> [
				'unit'	=> 'px',
				'size'	=> 'auto'
			],
			'selectors' => [
				'{{WRAPPER}} .borderless-elementor-testimonial-content' => 'min-height: {{SIZE}}{{UNIT}};',
			],
		]
	);

	$this->add_control(
		'borderless_testimonial_content_color_name',
		[
			'label' => esc_html__( 'Color Name', 'borderless'),
			'type' => Controls_Manager::COLOR,
			'default' => '#000',
			'selectors' => [
				'{{WRAPPER}} .borderless-elementor-testimonial-name' => 'color: {{VALUE}};',
			],
		]
	);

	$this->add_group_control(
		Group_Control_Typography::get_type(),
		[
			'label' => esc_html__( 'Typography Name', 'borderless'),
		 	'name' => 'borderless_testimonial_typography_name',
			'selector' => '{{WRAPPER}} .borderless-elementor-testimonial-name',
		]
	);

	$this->add_control(
		'borderless_testimonial_content_color_job_position',
		[
			'label' => esc_html__( 'Color Job Position', 'borderless'),
			'type' => Controls_Manager::COLOR,
			'default' => '#000',
			'selectors' => [
				'{{WRAPPER}} .borderless-elementor-testimonial-job' => 'color: {{VALUE}};',
			],
		]
	);

	$this->add_group_control(
		Group_Control_Typography::get_type(),
		[
			'label' => esc_html__( 'Typography Job Position', 'borderless'),
		 	'name' => 'borderless_testimonial_typography_job',
			'selector' => '{{WRAPPER}} .borderless-elementor-testimonial-job',
		]
	);

	$this->add_control(
		'borderless_testimonial_content_color_description',
		[
			'label' => esc_html__( 'Color Description', 'borderless'),
			'type' => Controls_Manager::COLOR,
			'default' => '#000',
			'selectors' => [
				'{{WRAPPER}} .borderless-elementor-testimonial-description' => 'color: {{VALUE}};',
			],
		]
	);

	$this->add_group_control(
		Group_Control_Typography::get_type(),
		[
			'label' => esc_html__( 'Typography Description', 'borderless'),
		 	'name' => 'borderless_testimonial_typography_description',
			'selector' => '{{WRAPPER}} .borderless-elementor-testimonial-description',
		]
	);

	$this->add_control(
		'borderless_section_testimonial_content_background',
		[
			'label' => esc_html__( 'Content Background Color', 'borderless'),
			'type' => Controls_Manager::COLOR,
			'default' => '',
			'selectors' => [
				'{{WRAPPER}} .borderless-elementor-testimonial-content' => 'background-color: {{VALUE}};',
			],
		]
	);

	

	$start = is_rtl() ? 'end' : 'start';
	$end = is_rtl() ? 'start' : 'end';

	$this->add_responsive_control(
		'borderless_section_testimonial_content_align',
		[
			'label' => __( 'Alignment', 'borderless' ),
			'type' => Controls_Manager::CHOOSE,
			'options' => [
				'left'    => [
					'title' => __( 'Left', 'borderless' ),
					'icon' => 'eicon-text-align-left',
				],
				'center' => [
					'title' => __( 'Center', 'borderless' ),
					'icon' => 'eicon-text-align-center',
				],
				'right' => [
					'title' => __( 'Right', 'borderless' ),
					'icon' => 'eicon-text-align-right',
				],
			],
			'prefix_class' => 'e-grid-align-',
			'default' => 'center',
			'selectors' => [
				'{{WRAPPER}} .borderless-elementor-testimonial-content' => 'text-align: {{VALUE}}',
			],
		]
	);

	$this->add_responsive_control(
		'borderless_section_testimonial_content_margin',
		[
			'label' => esc_html__( 'Margin', 'borderless'),
			'type' => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', 'em', '%', 'rem' ],
			'selectors' => [
				'{{WRAPPER}} .borderless-elementor-testimonial-content' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			],
		]
	);

	$this->add_responsive_control(
		'borderless_section_testimonial_content_padding',
		[
			'label' => esc_html__( 'Padding', 'borderless'),
			'type' => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', 'em', '%', 'rem' ],
			'selectors' => [
				'{{WRAPPER}} .borderless-elementor-testimonial-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			],
		]
	);

	$this->add_group_control(
		Group_Control_Border::get_type(),
		[
			'name' => 'borderless_section_testimonial_content_border',
			'label' => esc_html__( 'Border', 'borderless'),
			'selector' => '{{WRAPPER}} .borderless-elementor-testimonial-content',
		]
	);

	$this->add_control(
		'borderless_section_testimonial_content_radius',
		[
			'label' => esc_html__( 'Border Radius', 'borderless'),
			'type' => Controls_Manager::DIMENSIONS,
			'selectors' => [
				'{{WRAPPER}} .borderless-elementor-testimonial-content' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
			],
		]
	);

	$this->end_controls_section();

	/*-----------------------------------------------------------------------------------*/
	/*  *.  Quotation - Style
	/*-----------------------------------------------------------------------------------*/

	$this->start_controls_section(
		'borderless_section_testimonial_quotation_style',
		[
			'label' => esc_html__( 'Quotation', 'borderless'),
			'tab' => Controls_Manager::TAB_STYLE
		]
	);

	$this->add_group_control(
		Group_Control_Typography::get_type(),
		[
		 'name' => 'borderless_testimonial_quotation_typography',
			'selector' => '{{WRAPPER}} .borderless-elementor-testimonial-quote',
		]
	);

	$this->add_control(
		'borderless_section_testimonial_quotation_color',
		[
			'label' => esc_html__( 'Color', 'borderless'),
			'type' => Controls_Manager::COLOR,
			'default' => 'rgba(0,0,0,0.15)',
			'selectors' => [
				'{{WRAPPER}} .borderless-elementor-testimonial-quote' => 'color: {{VALUE}};',
			],
		]
	);

	$this->add_responsive_control(
		'borderless_section_testimonial_content_position',
		[
			'label' => esc_html__( 'Position', 'borderless'),
			'type' => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', 'em', '%', 'rem' ],
			'selectors' => [
				'{{WRAPPER}} .borderless-elementor-testimonial-quote' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			],
		]
	);

	$this->end_controls_section();

	}

	protected function borderless_elementor_testimonial_rating() {
		$settings = $this->get_settings_for_display('borderless_testimonial_enable_rating');

		if ( $settings == 'yes' ) :
			ob_start();
		?>
		<ul class="borderless-elementor-testimonial-star-rating">
			<li><i class="fas fa-star" aria-hidden="true"></i></li>
			<li><i class="fas fa-star" aria-hidden="true"></i></li>
			<li><i class="fas fa-star" aria-hidden="true"></i></li>
			<li><i class="fas fa-star" aria-hidden="true"></i></li>
			<li><i class="fas fa-star" aria-hidden="true"></i></li>
		</ul>
		<?php
			echo ob_get_clean();
		endif;
	}

	protected function borderless_elementor_testimonial_quote() {
		echo '<span class="borderless-elementor-testimonial-quote"></span>';
	}
	
	protected function render() {

		$settings = $this->get_settings_for_display();
		$rating = $this->get_settings_for_display('borderless_testimonial_enable_rating');

		$this->add_render_attribute(
			'borderless_testimonial_wrap',
			[
				'id'	=> 'borderless-testimonial-'.esc_attr($this->get_id()),
				'class'	=> [
					'borderless-elementor-testimonial',
				]
			]
		);

		if ( $rating == 'yes' )
		$this->add_render_attribute('borderless_testimonial_wrap', 'class', $this->get_settings('borderless_testimonial_rating_number'));

		?>

		<div <?php echo $this->get_render_attribute_string('borderless_testimonial_wrap'); ?>>

		<?php if ( !empty( $settings['borderless_testimonial_picture']['url'] ) ) {
			echo'
			<div class="borderless-elementor-testimonial-picture">  
				<figure>
					<img src="'.$settings['borderless_testimonial_picture']['url'].'">
				</figure>
           	</div>
			'; 
		}

		echo'<div class="borderless-elementor-testimonial-content">';

		if ( ! empty( $settings['borderless_testimonial_description'] ) ) {
			echo'<p class="borderless-elementor-testimonial-description">'.$settings['borderless_testimonial_description'].'</p>';
		}

		$this->borderless_elementor_testimonial_rating( $settings );

		if ( ! empty( $settings['borderless_testimonial_name'] ) ) {
			echo'<h5 class="borderless-elementor-testimonial-name">'.$settings['borderless_testimonial_name'].'</h5>';
		}
		if ( ! empty( $settings['borderless_testimonial_job'] ) ) {
			echo'<p class="borderless-elementor-testimonial-job">'.$settings['borderless_testimonial_job'].'</p>';
		}		
		echo'</div>';
		$this->borderless_elementor_testimonial_quote();
		echo'</div>';

	}
	
	protected function _content_template() {

    }
	
	
}