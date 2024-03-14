<?php

namespace EmTmplF\Inc;

defined( 'ABSPATH' ) || exit;

return apply_filters( 'emtmpl_image_map', [
	'infor_icons' => [
		'home'     => [
			'home-black'        => esc_html__( 'Black', '9mail-wp-email-templates-designer' ),
			'home-white'        => esc_html__( 'White', '9mail-wp-email-templates-designer' ),
			'home-white-border' => esc_html__( 'White/Border', '9mail-wp-email-templates-designer' ),
			'home-black-border' => esc_html__( 'Black/Border', '9mail-wp-email-templates-designer' ),
			'home-black-white'  => esc_html__( 'Black/White', '9mail-wp-email-templates-designer' ),
			'home-white-black'  => esc_html__( 'White/Black', '9mail-wp-email-templates-designer' ),
		],
		'email'    => [
			'email-black'        => esc_html__( 'Black', '9mail-wp-email-templates-designer' ),
			'email-white'        => esc_html__( 'White', '9mail-wp-email-templates-designer' ),
			'email-white-border' => esc_html__( 'White/Border', '9mail-wp-email-templates-designer' ),
			'email-black-border' => esc_html__( 'Black/Border', '9mail-wp-email-templates-designer' ),
			'email-black-white'  => esc_html__( 'Black/White', '9mail-wp-email-templates-designer' ),
			'email-white-black'  => esc_html__( 'White/Black', '9mail-wp-email-templates-designer' ),
		],
		'phone'    => [
			'phone-black'        => esc_html__( 'Black', '9mail-wp-email-templates-designer' ),
			'phone-white'        => esc_html__( 'White', '9mail-wp-email-templates-designer' ),
			'phone-white-border' => esc_html__( 'White/Border', '9mail-wp-email-templates-designer' ),
			'phone-black-border' => esc_html__( 'Black/Border', '9mail-wp-email-templates-designer' ),
			'phone-black-white'  => esc_html__( 'Black/White', '9mail-wp-email-templates-designer' ),
			'phone-white-black'  => esc_html__( 'White/Black', '9mail-wp-email-templates-designer' ),
		],
		'location' => [
			'location-white'        => esc_html__( 'Black', '9mail-wp-email-templates-designer' ),
			'location-black'        => esc_html__( 'White', '9mail-wp-email-templates-designer' ),
			'location-white-border' => esc_html__( 'White/Border', '9mail-wp-email-templates-designer' ),
			'location-black-border' => esc_html__( 'Black/Border', '9mail-wp-email-templates-designer' ),
			'location-black-white'  => esc_html__( 'Black/White', '9mail-wp-email-templates-designer' ),
			'location-white-black'  => esc_html__( 'White/Black', '9mail-wp-email-templates-designer' ),
		],
	],

	'social_icons' => [
		'facebook' => [
			''                => esc_html__( 'Disable', '9mail-wp-email-templates-designer' ),
			'fb-black'        => esc_html__( 'Black', '9mail-wp-email-templates-designer' ),
			'fb-white'        => esc_html__( 'White', '9mail-wp-email-templates-designer' ),
			'fb-blue'         => esc_html__( 'Color', '9mail-wp-email-templates-designer' ),
			'fb-white-border' => esc_html__( 'White border', '9mail-wp-email-templates-designer' ),
			'fb-black-border' => esc_html__( 'Black border', '9mail-wp-email-templates-designer' ),
			'fb-blue-border'  => esc_html__( 'Color border', '9mail-wp-email-templates-designer' ),
			'fb-blue-white'   => esc_html__( 'Color - White', '9mail-wp-email-templates-designer' ),
			'fb-white-black'  => esc_html__( 'Black - White', '9mail-wp-email-templates-designer' ),
			'fb-white-blue'   => esc_html__( 'White - Color', '9mail-wp-email-templates-designer' ),
		],

		'twitter' => [
			''                 => esc_html__( 'Disable', '9mail-wp-email-templates-designer' ),
			'twi-black'        => esc_html__( 'Black', '9mail-wp-email-templates-designer' ),
			'twi-white'        => esc_html__( 'White', '9mail-wp-email-templates-designer' ),
			'twi-cyan'         => esc_html__( 'Color', '9mail-wp-email-templates-designer' ),
			'twi-white-border' => esc_html__( 'White border', '9mail-wp-email-templates-designer' ),
			'twi-black-border' => esc_html__( 'Black border', '9mail-wp-email-templates-designer' ),
			'twi-cyan-border'  => esc_html__( 'Color border', '9mail-wp-email-templates-designer' ),
			'twi-cyan-white'   => esc_html__( 'Color - White', '9mail-wp-email-templates-designer' ),
			'twi-white-black'  => esc_html__( 'Black - White', '9mail-wp-email-templates-designer' ),
			'twi-white-cyan'   => esc_html__( 'White - Color', '9mail-wp-email-templates-designer' ),
		],

		'instagram' => [
			''                 => esc_html__( 'Disable', '9mail-wp-email-templates-designer' ),
			'ins-black'        => esc_html__( 'Black', '9mail-wp-email-templates-designer' ),
			'ins-white'        => esc_html__( 'White', '9mail-wp-email-templates-designer' ),
			'ins-color'        => esc_html__( 'Color', '9mail-wp-email-templates-designer' ),
			'ins-white-border' => esc_html__( 'White border', '9mail-wp-email-templates-designer' ),
			'ins-black-border' => esc_html__( 'Black border', '9mail-wp-email-templates-designer' ),
			'ins-color-border' => esc_html__( 'Color border', '9mail-wp-email-templates-designer' ),
			'ins-color-white'  => esc_html__( 'Color - White', '9mail-wp-email-templates-designer' ),
			'ins-white-black'  => esc_html__( 'Black - White', '9mail-wp-email-templates-designer' ),
			'ins-white-color'  => esc_html__( 'White - Color', '9mail-wp-email-templates-designer' ),
		],

		'youtube' => [
			''                => esc_html__( 'Disable', '9mail-wp-email-templates-designer' ),
			'yt-black'        => esc_html__( 'Black', '9mail-wp-email-templates-designer' ),
			'yt-white'        => esc_html__( 'White', '9mail-wp-email-templates-designer' ),
			'yt-color'        => esc_html__( 'Color', '9mail-wp-email-templates-designer' ),
			'yt-white-border' => esc_html__( 'White border', '9mail-wp-email-templates-designer' ),
			'yt-black-border' => esc_html__( 'Black border', '9mail-wp-email-templates-designer' ),
			'yt-color-border' => esc_html__( 'Color border', '9mail-wp-email-templates-designer' ),
			'yt-color-white'  => esc_html__( 'Color - White', '9mail-wp-email-templates-designer' ),
			'yt-white-black'  => esc_html__( 'Black - White', '9mail-wp-email-templates-designer' ),
			'yt-white-color'  => esc_html__( 'White - Color', '9mail-wp-email-templates-designer' ),
		],

		'linkedin' => [
			''                => esc_html__( 'Disable', '9mail-wp-email-templates-designer' ),
			'li-black'        => esc_html__( 'Black', '9mail-wp-email-templates-designer' ),
			'li-white'        => esc_html__( 'White', '9mail-wp-email-templates-designer' ),
			'li-color'        => esc_html__( 'Color', '9mail-wp-email-templates-designer' ),
			'li-white-border' => esc_html__( 'White border', '9mail-wp-email-templates-designer' ),
			'li-black-border' => esc_html__( 'Black border', '9mail-wp-email-templates-designer' ),
			'li-color-border' => esc_html__( 'Color border', '9mail-wp-email-templates-designer' ),
			'li-color-white'  => esc_html__( 'Color - White', '9mail-wp-email-templates-designer' ),
			'li-white-black'  => esc_html__( 'Black - White', '9mail-wp-email-templates-designer' ),
			'li-white-color'  => esc_html__( 'White - Color', '9mail-wp-email-templates-designer' ),
		],

		'whatsapp' => [
			''                => esc_html__( 'Disable', '9mail-wp-email-templates-designer' ),
			'wa-black'        => esc_html__( 'Black', '9mail-wp-email-templates-designer' ),
			'wa-white'        => esc_html__( 'White', '9mail-wp-email-templates-designer' ),
			'wa-color'        => esc_html__( 'Color', '9mail-wp-email-templates-designer' ),
			'wa-white-border' => esc_html__( 'White border', '9mail-wp-email-templates-designer' ),
			'wa-black-border' => esc_html__( 'Black border', '9mail-wp-email-templates-designer' ),
			'wa-color-border' => esc_html__( 'Color border', '9mail-wp-email-templates-designer' ),
			'wa-color-white'  => esc_html__( 'Color - White', '9mail-wp-email-templates-designer' ),
			'wa-white-black'  => esc_html__( 'Black - White', '9mail-wp-email-templates-designer' ),
			'wa-white-color'  => esc_html__( 'White - Color', '9mail-wp-email-templates-designer' ),
		],
	]
] );

