<?php
/**
 * Template for Post Query Controller
 *
 * @package AbsoluteAddons
 */

use Elementor\Controls_Manager;
use Elementor\Repeater;

$this->add_control(
	'portfolio_social_icon',
	[
		'label'     => esc_html__( 'Show Social Icon', 'absolute-addons' ),
		'type'      => Controls_Manager::SWITCHER,
		'options'   => [
			'yes'  => esc_html__( 'Yes', 'absolute-addons' ),
			'none' => esc_html__( 'none', 'absolute-addons' ),
		],
		'default'   => 'yes',
		'separator' => 'before',
		'condition' => [
			'absolute_portfolio' => [ 'three' ],
		],
	]
);

$repeater = new Repeater();

$repeater->add_control(
	'social_icon_text',
	[
		'label'       => esc_html__( 'Icon Text', 'absolute-addons' ),
		'type'        => Controls_Manager::TEXT,
		'label_block' => true,
		'default'     => esc_html__( 'Facebook', 'absolute-addons' ),
	]
);

$repeater->add_control(
	'social_icon',
	[
		'label'            => esc_html__( 'Icon', 'absolute-addons' ),
		'type'             => Controls_Manager::ICONS,
		'fa4compatibility' => 'absolute-addons',
		'default'          => [
			'value'   => 'fab fa-facebook-f',
			'library' => 'solid',
		],
	]
);

$repeater->add_control(
	'social_link',
	[
		'label'       => __( 'Link', 'absolute-addons' ),
		'type'        => Controls_Manager::URL,
		'dynamic'     => [
			'active' => true,
		],
		'placeholder' => __( 'https://your-link.com', 'absolute-addons' ),
	]
);

$this->add_control(
	'social',
	[
		'label'       => esc_html__( 'Social Icon', 'absolute-addons' ),
		'type'        => Controls_Manager::REPEATER,
		'fields'      => $repeater->get_controls(),
		'default'     => [
			[
				'social_icon_text' => esc_html__( 'Facebook', 'absolute-addons' ),
				'social_icon'      => [ 'value' => 'fab fa-facebook-f' ],

			],
			[
				'social_icon_text' => esc_html__( 'Twitter', 'absolute-addons' ),
				'social_icon'      => [ 'value' => 'fab fa-twitter' ],

			],
			[
				'social_icon_text' => esc_html__( 'Google Plus', 'absolute-addons' ),
				'social_icon'      => [ 'value' => 'fab fa-google-plus-g' ],

			],
			[
				'social_icon_text' => esc_html__( 'Pinterest', 'absolute-addons' ),
				'social_icon'      => [ 'value' => 'fab fa-pinterest-p' ],

			],
		],
		'title_field' => '{{{ social_icon_text }}}',
		'condition'   => [
			'portfolio_social_icon' => 'yes',
			'absolute_portfolio'    => [ 'three' ],
		],
	]
);
