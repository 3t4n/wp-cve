<?php

if ( ! defined( 'ABSPATH' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	die();
}

use Elementor\Controls_Manager;

$this->start_controls_section(
	'section_content_ten',
	[
		'label'     => esc_html__( 'Content Section', 'absolute-addons' ),
		'condition' => [
			'absolute_testimonial' => [ 'ten' ],
		],
	]
);

$this->add_control(
	'item_style_ten_position',
	[
		'label'   => esc_html__( 'Image Position', 'absolute-addons' ),
		'type'    => Controls_Manager::CHOOSE,
		'options' => [
			'top'    => [
				'title' => esc_html__( 'Top', 'absolute-addons' ),
				'icon'  => 'eicon-v-align-top',
			],
			'bottom' => [
				'title' => esc_html__( 'Bottom', 'absolute-addons' ),
				'icon'  => 'eicon-v-align-bottom',
			],
			'left'   => [
				'title' => esc_html__( 'Left', 'absolute-addons' ),
				'icon'  => 'eicon-h-align-left',
			],
			'right'  => [
				'title' => esc_html__( 'Right', 'absolute-addons' ),
				'icon'  => 'eicon-h-align-right',
			],
		],
		'default' => 'top',
		'toggle'  => false,
	]
);

$this->add_control(
	'testimonial_style_ten_title',
	[
		'label'       => esc_html__( 'Testimonial Title', 'absolute-addons' ),
		'type'        => Controls_Manager::TEXT,
		'default'     => esc_html__( 'Rossey Jacky', 'absolute-addons' ),
		'label_block' => true,
	]
);

$this->add_control(
	'testimonial_style_ten_designation',
	[
		'label'       => esc_html__( 'Testimonial Designation', 'absolute-addons' ),
		'type'        => Controls_Manager::TEXT,
		'default'     => esc_html__( 'CEO, CompanyName', 'absolute-addons' ),
		'label_block' => true,
	]
);

$this->add_control(
	'testimonial_style_ten_content',
	[
		'label'   => esc_html__( 'Testimonial Content', 'absolute-addons' ),
		'type'    => Controls_Manager::WYSIWYG,
		'default' => esc_html__( 'Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat.', 'absolute-addons' ),
	]
);

$this->add_control(
	'testimonial_ten_image',
	[
		'label'   => esc_html__( 'Image', 'absolute-addons' ),
		'type'    => Controls_Manager::MEDIA,
		'default' => [
			'url' => absp_get_default_placeholder(),
		],
	]
);

$this->end_controls_section();
