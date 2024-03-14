<?php

if ( ! defined( 'ABSPATH' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	die();
}

use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;
use Elementor\Repeater;

$this->start_controls_section(
	'section_content_nine',
	[
		'label'     => esc_html__( 'Content Section', 'absolute-addons' ),
		'condition' => [
			'absolute_testimonial' => [ 'nine' ],
		],
	]
);

//Slider Repeater
$repeater = new Repeater();

$repeater->add_group_control(
	Group_Control_Background::get_type(),
	[
		'name'     => 'section_style_testimonial_body_background',
		'label'    => esc_html__( 'Background', 'absolute-addons' ),
		'types'    => [ 'classic', 'gradient' ],
		'selector' => '{{WRAPPER}} .testimonial-style-nine .testimonial-right-section {{CURRENT_ITEM}} .testimonial-item-wrapper',
	]
);

$repeater->add_control(
	'testimonial_style_nine_title',
	[
		'label'       => esc_html__( 'Testimonial Title', 'absolute-addons' ),
		'type'        => Controls_Manager::TEXT,
		'default'     => esc_html__( 'Jane Smith', 'absolute-addons' ),
		'label_block' => true,
	]
);

$repeater->add_control(
	'testimonial_style_nine_designation',
	[
		'label'       => esc_html__( 'Testimonial Designation', 'absolute-addons' ),
		'type'        => Controls_Manager::TEXT,
		'default'     => esc_html__( 'Chief Executive Officer', 'absolute-addons' ),
		'label_block' => true,
	]
);

$repeater->add_control(
	'testimonial_style_nine_content',
	[
		'label'   => esc_html__( 'Testimonial Content', 'absolute-addons' ),
		'type'    => Controls_Manager::WYSIWYG,
		'default' => esc_html__( 'Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat. Ut wisi enim', 'absolute-addons' ),
	]
);

$repeater->add_control(
	'item_style_nine_position',
	[
		'label'   => esc_html__( 'Image Position', 'absolute-addons' ),
		'type'    => Controls_Manager::CHOOSE,
		'options' => [
			'left'  => [
				'title' => esc_html__( 'Top', 'absolute-addons' ),
				'icon'  => 'eicon-h-align-left',
			],
			'right' => [
				'title' => esc_html__( 'Bottom', 'absolute-addons' ),
				'icon'  => 'eicon-h-align-right',
			],
		],
		'default' => 'right',
		'toggle'  => false,
	]
);

$repeater->add_control(
	'testimonial_nine_image',
	[
		'label'   => esc_html__( 'Image', 'absolute-addons' ),
		'type'    => Controls_Manager::MEDIA,
		'default' => [
			'url' => absp_get_default_placeholder(),
		],
	]
);

$this->add_control(
	'testimonial_style_nine',
	[
		'label'       => esc_html__( 'Testimonial Item', 'absolute-addons' ),
		'type'        => Controls_Manager::REPEATER,
		'fields'      => $repeater->get_controls(),
		'default'     => [
			[
				'testimonial_style_nine_title'       => esc_html__( 'Jane Smith', 'absolute-addons' ),
				'testimonial_style_nine_designation' => esc_html__( 'CEO CompanyName', 'absolute-addons' ),
				'testimonial_style_nine_content'     => esc_html__( 'Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat. Ut wisi enim.', 'absolute-addons' ),
				'testimonial_nine_image'             => [
					'url' => absp_get_default_placeholder(),
				],
			],
			[
				'testimonial_style_nine_title'       => esc_html__( 'Barkman D.', 'absolute-addons' ),
				'testimonial_style_nine_designation' => esc_html__( 'CEO CompanyName', 'absolute-addons' ),
				'testimonial_style_nine_content'     => esc_html__( 'Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat. Ut wisi enim.', 'absolute-addons' ),
				'testimonial_nine_image'             => [
					'url' => absp_get_default_placeholder(),
				],
			],
			[
				'testimonial_style_nine_title'       => esc_html__( 'Rossey Jacky', 'absolute-addons' ),
				'testimonial_style_nine_designation' => esc_html__( 'CEO CompanyName', 'absolute-addons' ),
				'testimonial_style_nine_content'     => esc_html__( 'Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat. Ut wisi enim.', 'absolute-addons' ),
				'testimonial_nine_image'             => [
					'url' => absp_get_default_placeholder(),
				],
			],
		],
		'title_field' => '{{{ testimonial_style_nine_title }}}',
		'conditions'  => [
			'relation' => 'or',
			'terms'    => [
				[
					'name'     => 'absolute_testimonial',
					'operator' => '==',
					'value'    => 'nine',
				],
			],
		],
	]
);

$this->end_controls_section();

$this->start_controls_section(
	'section_title',
	[
		'label'      => esc_html__( 'Section Contents', 'absolute-addons' ),
		'conditions' => [
			'relation' => 'or',
			'terms'    => [
				[
					'name'     => 'absolute_testimonial',
					'operator' => '==',
					'value'    => 'nine',
				],
			],
		],
	]
);

$this->add_control(
	'item_section_title',
	[
		'label'      => esc_html__( 'Section Title', 'absolute-addons' ),
		'type'       => Controls_Manager::TEXT,
		'default'    => esc_html__( 'Our Clients TEMSTIMONIALS', 'absolute-addons' ),
		'conditions' => [
			'relation' => 'or',
			'terms'    => [
				[
					'name'     => 'absolute_testimonial',
					'operator' => '==',
					'value'    => 'nine',
				],
			],
		],
	]
);

$this->add_control(
	'item_section_description',
	[
		'label'      => esc_html__( 'Section Description', 'absolute-addons' ),
		'type'       => Controls_Manager::WYSIWYG,
		'default'    => esc_html__( 'Adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat. Ut wisi enim ad minim veniam, quis nostrud exerci tation.', 'absolute-addons' ),
		'conditions' => [
			'relation' => 'or',
			'terms'    => [
				[
					'name'     => 'absolute_testimonial',
					'operator' => '==',
					'value'    => 'nine',
				],
			],
		],
	]
);

$this->end_controls_section();
