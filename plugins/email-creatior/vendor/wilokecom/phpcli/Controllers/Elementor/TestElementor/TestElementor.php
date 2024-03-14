<?php
// Example file

use HSSC\Helpers\App;

return [
	'control' => [
		'general_settings' => [
			'options'  => [
				'label' => esc_html__('General Settings', ESC_HTML_TEXT_DOMAIN),
				'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
			],
			'controls' => [
				'items_per_row' => App::get('ElementorCommonRegistration')::getElItemsPerRowControl(),
				'max_row'       => App::get('ElementorCommonRegistration')::getElMaxRowsControl(),
				'gap'           => App::get('ElementorCommonRegistration')::getElGapControl()
			]
		],
		'content_section'  => [
			'options'  => [
				'label' => esc_html__('Filter Setting', ESC_HTML_TEXT_DOMAIN),
				'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
			],
			'controls' => [
				'get_post_by'     => App::get('ElementorCommonRegistration')::getElGetPostByControl(),
				'categories'      => [
					'label'     => esc_html__('Categorys', ESC_HTML_TEXT_DOMAIN),
					'type'      => \Elementor\Controls_Manager::SELECT2,
					'multiple'  => true,
					'condition' => [
						'get_post_by' => 'categories'
					],
					'default'   => array_keys(App::get('ElementorCommonRegistration')::getTermsOptions())[0],
					'options'   => App::get('ElementorCommonRegistration')::getTermsOptions()
				],
				'specified_posts' => [
					'label'     => esc_html__('Specified Posts', ESC_HTML_TEXT_DOMAIN),
					'type'      => \Elementor\Controls_Manager::SELECT2,
					'multiple'  => true,
					'condition' => [
						'get_post_by' => 'specified_posts'
					],
					'default'   => [array_keys(App::get('ElementorCommonRegistration')::getPostOptions())[0]],
					'options'   => App::get('ElementorCommonRegistration')::getPostOptions()
				],
				'orderby'         => App::get('ElementorCommonRegistration')::getElOrderByControl(),
				'order'           => App::get('ElementorCommonRegistration')::getElOrderControl()
			]
		]
	]
];
