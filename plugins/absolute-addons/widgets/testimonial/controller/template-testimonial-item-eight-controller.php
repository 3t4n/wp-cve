<?php

if ( ! defined( 'ABSPATH' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	die();
}

use Elementor\Controls_Manager;
use Elementor\Repeater;

$this->start_controls_section(
	'section_content_eight',
	[
		'label'     => esc_html__( 'Testimonial Content Section', 'absolute-addons' ),
		'condition' => [
			'absolute_testimonial' => [ 'eight' ],
		],
	]
);

$this->add_control(
	'item_position',
	[
		'label'   => esc_html__( 'Position', 'absolute-addons' ),
		'type'    => Controls_Manager::SELECT,
		'options' => [
			'normal' => esc_html__( 'Flat Style', 'absolute-addons' ),
			'random' => esc_html__( 'Random Style', 'absolute-addons' ),
		],
		'default' => 'random',
		'toggle'  => false,
	]
);

//Slider Repeater
$repeater = new Repeater();

$repeater->add_control(
	'image_position',
	[
		'label'   => esc_html__( 'Image Position', 'absolute-addons' ),
		'type'    => Controls_Manager::CHOOSE,
		'options' => [
			'left'  => [
				'title' => esc_html__( 'Left', 'absolute-addons' ),
				'icon'  => 'eicon-h-align-left',
			],
			'right' => [
				'title' => esc_html__( 'Right', 'absolute-addons' ),
				'icon'  => 'eicon-h-align-right',
			],
		],
		'default' => 'left',
		'toggle'  => false,
	]
);

$repeater->add_control(
	'testimonial_title',
	[
		'label'       => esc_html__( 'Testimonial Title', 'absolute-addons' ),
		'type'        => Controls_Manager::TEXT,
		'default'     => esc_html__( 'K. Stone', 'absolute-addons' ),
		'label_block' => true,
	]
);

$repeater->add_control(
	'testimonial_designation',
	[
		'label'       => esc_html__( 'Testimonial Designation', 'absolute-addons' ),
		'type'        => Controls_Manager::TEXT,
		'default'     => esc_html__( 'Creative Director', 'absolute-addons' ),
		'label_block' => true,
	]
);

$repeater->add_control(
	'testimonial_content',
	[
		'label'   => esc_html__( 'Testimonial Content', 'absolute-addons' ),
		'type'    => Controls_Manager::WYSIWYG,
		'default' => esc_html__( 'Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat.', 'absolute-addons' ),
	]
);

$repeater->add_control(
	'testimonial_image',
	[
		'label'   => esc_html__( 'Image', 'absolute-addons' ),
		'type'    => Controls_Manager::MEDIA,
		'default' => [
			'url' => absp_get_default_placeholder(),
		],
	]
);

$repeater->add_control(
	'testimonial_quote_select',
	[
		'label'   => esc_html__( 'Select quote', 'absolute-addons' ),
		'type'    => Controls_Manager::SELECT,
		'options' => [
			'yes'  => esc_html__( 'Yes', 'absolute-addons' ),
			'none' => esc_html__( 'No', 'absolute-addons' ),
		],
		'default' => 'yes',
	]
);

$repeater->add_control(
	'testimonial_quote',
	[
		'label'            => esc_html__( 'Select quote Icon', 'absolute-addons' ),
		'type'             => Controls_Manager::ICONS,
		'fa4compatibility' => 'absolute-addons',
		'default'          => [
			'value'   => 'fa fa-quote-right',
			'library' => 'solid',
		],
		'condition'        => [
			'testimonial_quote_select' => 'yes',
		],
	]
);

$this->add_control(
	'testimonial_style_eight',
	[
		'label'       => esc_html__( 'Testimonial Item', 'absolute-addons' ),
		'type'        => Controls_Manager::REPEATER,
		'fields'      => $repeater->get_controls(),
		'default'     => [
			[
				'testimonial_title'       => esc_html__( 'Jasica Alberta', 'absolute-addons' ),
				'testimonial_designation' => esc_html__( 'CTO, VerMedia Inc.', 'absolute-addons' ),
				'testimonial_content'     => esc_html__( 'Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat. Ut wisi enim ad minim veniam, quis nostrud exerci tation ullamcorper.', 'absolute-addons' ),
			],
			[
				'testimonial_title'       => esc_html__( 'Herculeaks', 'absolute-addons' ),
				'testimonial_designation' => esc_html__( 'Creative Director, Xolomo Ltd.', 'absolute-addons' ),
				'testimonial_content'     => esc_html__( 'Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat. Ut wisi enim ad minim veniam, quis nostrud exerci tation ullamcorper.', 'absolute-addons' ),
			],
			[
				'testimonial_title'       => esc_html__( 'Nutalia Jn.', 'absolute-addons' ),
				'testimonial_designation' => esc_html__( 'Director, CompInc.', 'absolute-addons' ),
				'testimonial_content'     => esc_html__( 'Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat. Ut wisi enim ad minim veniam, quis nostrud exerci tation ullamcorper.', 'absolute-addons' ),
			],
			[
				'testimonial_title'       => esc_html__( 'Xiantchu Mao', 'absolute-addons' ),
				'testimonial_designation' => esc_html__( 'Co-Founder, MyCopany Limited', 'absolute-addons' ),
				'testimonial_content'     => esc_html__( 'Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat. Ut wisi enim ad minim veniam, quis nostrud exerci tation ullamcorper.', 'absolute-addons' ),
			],
			[
				'testimonial_title'       => esc_html__( 'Harsh Meckan', 'absolute-addons' ),
				'testimonial_designation' => esc_html__( 'Co-Founder, SalaVetra Inc.', 'absolute-addons' ),
				'testimonial_content'     => esc_html__( 'Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat. Ut wisi enim ad minim veniam, quis nostrud exerci tation ullamcorper.', 'absolute-addons' ),
			],
		],
		'title_field' => '{{{ testimonial_title }}}',

		'condition'   => [
			'absolute_testimonial' => [ 'eight' ],
		],
	]
);

$this->end_controls_section();
