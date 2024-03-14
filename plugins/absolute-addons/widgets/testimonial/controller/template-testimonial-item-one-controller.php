<?php

if ( ! defined( 'ABSPATH' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	die();
}

use Elementor\Controls_Manager;
use Elementor\Utils;

$this->start_controls_section(
	'testimonial_section_content',
	[
		'label'     => esc_html__( 'Testimonial Content', 'absolute-addons' ),
		'condition' => [
			'absolute_testimonial' => [ 'one', 'two', 'three', 'four', 'five', 'six', 'seven' ],
		],
	]
);

$this->add_control(
	'item_style_one_title',
	[
		'label'   => esc_html__( 'Title', 'absolute-addons' ),
		'type'    => Controls_Manager::TEXT,
		'default' => esc_html__( 'Herculeaks', 'absolute-addons' ),
	]
);

$this->add_control(
	'item_style_one_desig',
	[
		'label'   => esc_html__( 'Designation', 'absolute-addons' ),
		'type'    => Controls_Manager::TEXT,
		'default' => esc_html__( 'Creative Director, CompanyName', 'absolute-addons' ),
	]
);

$this->add_control(
	'item_style_one_position',
	[
		'label'        => __( 'Image Position', 'absolute-addons' ),
		'type'         => Controls_Manager::CHOOSE,
		'default'      => 'top',
		'options'      => [
			'left'  => [
				'title' => __( 'Left', 'absolute-addons' ),
				'icon'  => 'eicon-h-align-left',
			],
			'top'   => [
				'title' => __( 'Top', 'absolute-addons' ),
				'icon'  => 'eicon-v-align-top',
			],
			'right' => [
				'title' => __( 'Right', 'absolute-addons' ),
				'icon'  => 'eicon-h-align-right',
			],
		],
		'prefix_class' => 'elementor-position-',
		'toggle'       => false,
		'condition'    => [
			'absolute_testimonial' => 'one',
		],
	]
);

$this->add_control(
	'item_style_one_content',
	[
		'label'   => esc_html__( 'Item Content', 'absolute-addons' ),
		'type'    => Controls_Manager::WYSIWYG,
		'default' => esc_html__( 'Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat. Ut wisi enim ad minim veniam, quis nostrud exerci tation ullamcorper suscipit lobortis nisl ut aliquip ex ea commodo.', 'absolute-addons' ),
	]
);

$this->add_control(
	'item_style_one_img',
	[
		'label'   => esc_html__( 'Item Image', 'absolute-addons' ),
		'type'    => Controls_Manager::MEDIA,
		'default' => [
			'url' => Utils::get_placeholder_image_src(),
		],
	]
);

$this->add_control(
	'item_style_one_after_before_icon_select',
	[
		'label'      => esc_html__( 'Show Quote Icon', 'absolute-addons' ),
		'type'       => Controls_Manager::SELECT,
		'options'    => [
			'yes'  => esc_html__( 'Yes', 'absolute-addons' ),
			'none' => esc_html__( 'No', 'absolute-addons' ),
		],
		'default'    => 'yes',
		'conditions' => [
			'relation' => 'or',
			'terms'    => [
				[
					'name'     => 'absolute_testimonial',
					'operator' => '==',
					'value'    => 'one',
				],
				[
					'name'     => 'absolute_testimonial',
					'operator' => '==',
					'value'    => 'two',
				],
				[
					'name'     => 'absolute_testimonial',
					'operator' => '==',
					'value'    => 'three',
				],
			],
		],
	]
);

$this->add_control(
	'before_icon',
	[
		'label'            => esc_html__( 'Before Icon', 'absolute-addons' ),
		'type'             => Controls_Manager::ICONS,
		'fa4compatibility' => 'absolute-addons',
		'default'          => [
			'value'   => 'fas fa-quote-left',
			'library' => 'solid',
		],
		'conditions'       => [
			'relation' => 'or',
			'terms'    => [
				[
					'name'     => 'item_style_one_after_before_icon_select',
					'operator' => '==',
					'value'    => 'yes',
				],
				[
					'name'     => 'absolute_testimonial',
					'operator' => '==',
					'value'    => 'one',
				],
				[
					'name'     => 'absolute_testimonial',
					'operator' => '==',
					'value'    => 'two',
				],
				[
					'name'     => 'absolute_testimonial',
					'operator' => '==',
					'value'    => 'three',
				],
			],
		],
	]
);

$this->add_control(
	'item_style_one_quote_position',
	[
		'label'     => esc_html__( 'Quote Position', 'absolute-addons' ),
		'type'      => Controls_Manager::SELECT,
		'default'   => 'top_left',
		'options'   => [
			'top_left'     => esc_html__( 'Top Left', 'absolute-addons' ),
			'top_right'    => esc_html__( 'Top Right', 'absolute-addons' ),
			'bottom_left'  => esc_html__( 'Bottom Left', 'absolute-addons' ),
			'bottom_right' => esc_html__( 'Bottom Right', 'absolute-addons' ),
		],
		'condition' => [
			'absolute_testimonial'                    => 'one',
			'item_style_one_after_before_icon_select' => 'yes',
		],
	]
);

$this->add_control(
	'testimonial_review',
	[
		'label'     => esc_html__( 'Review Select', 'absolute-addons' ),
		'type'      => Controls_Manager::SELECT,
		'options'   => [
			'0' => esc_html__( 'None', 'absolute-addons' ),
			'1' => esc_html__( '1', 'absolute-addons' ),
			'2' => esc_html__( '2', 'absolute-addons' ),
			'3' => esc_html__( '3', 'absolute-addons' ),
			'4' => esc_html__( '4', 'absolute-addons' ),
			'5' => esc_html__( '5', 'absolute-addons' ),
		],
		'default'   => '5',
		'condition' => [
			'absolute_testimonial' => 'seven',
		],
	]
);

$this->add_control(
	'testimonial_review_icon',
	[
		'label'            => esc_html__( 'Select Review Icon', 'absolute-addons' ),
		'type'             => Controls_Manager::ICONS,
		'fa4compatibility' => 'absolute-addons',
		'default'          => [
			'value'   => 'fas fa-star',
			'library' => 'solid',
		],
		'condition'        => [
			'testimonial_review!'  => '0',
			'absolute_testimonial' => 'seven',
		],
	]
);

$this->end_controls_section();
