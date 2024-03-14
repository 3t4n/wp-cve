<?php

add_filter( 'hester_customizer_options', 'hester_customizer_blog_options' );
function hester_customizer_blog_options( array $options ) {
	// Blog section
	$options['section']['hester_section_blog'] = array(
		'title'          => esc_html__( 'Blog Section', 'hester-core' ),
		'panel'          => 'hester_panel_homepage',
		'class'          => 'Hester_Customizer_Control_Section_Hiding',
		'hiding_control' => 'hester_enable_blog',
		'priority'       => (int) apply_filters( 'hester_section_priority', 6, 'hester_section_blog' ),
	);

	// toggle.
	$options['setting']['hester_enable_blog'] = array(
		'transport'         => 'postMessage',
		'sanitize_callback' => 'hester_sanitize_toggle',
		'control'           => array(
			'type'    => 'hester-toggle',
			'label'   => esc_html__( 'Enable Blog section', 'hester-core' ),
			'section' => 'hester_section_blog',
		),
		'required'          => array(
			array(
				'control'  => 'hester_section_blog',
				'value'    => true,
				'operator' => '==',
			),
		),
	);

	$options['setting']['hester_blog_sub_heading'] = array(
		'transport'         => 'postMessage',
		'sanitize_callback' => 'sanitize_text_field',
		'control'           => array(
			'type'     => 'hester-text',
			'label'    => esc_html__( 'Section Sub Heading', 'hester-core' ),
			'section'  => 'hester_section_blog',
			'required' => array(
				array(
					'control'  => 'hester_enable_blog',
					'value'    => true,
					'operator' => '==',
				),
			),
		),
	);

	$options['setting']['hester_blog_heading'] = array(
		'transport'         => 'postMessage',
		'sanitize_callback' => 'sanitize_text_field',
		'control'           => array(
			'type'     => 'hester-text',
			'label'    => esc_html__( 'Section Heading', 'hester-core' ),
			'section'  => 'hester_section_blog',
			'required' => array(
				array(
					'control'  => 'hester_enable_blog',
					'value'    => true,
					'operator' => '==',
				),
			),
		),
		'partial'           => array(
			'selector'            => '#hester-blog-heading',
			'render_callback'     => function () {
				return get_theme_mod( 'hester_blog_heading' );
			},
			'container_inclusive' => false,
			'fallback_refresh'    => true,
		),
	);

	$options['setting']['hester_blog_description'] = array(
		'transport'         => 'postMessage',
		'sanitize_callback' => 'hester_sanitize_textarea',
		'control'           => array(
			'type'     => 'hester-editor',
			'label'    => esc_html__( 'Section Description', 'hester-core' ),
			'section'  => 'hester_section_blog',
			'required' => array(
				array(
					'control'  => 'hester_enable_blog',
					'value'    => true,
					'operator' => '==',
				),
			),
		),
	);

	// Post Settings heading.
	$options['setting']['hester_blog_posts_heading'] = array(
		'transport'         => 'postMessage',
		'sanitize_callback' => 'hester_sanitize_toggle',
		'control'           => array(
			'type'     => 'hester-heading',
			'section'  => 'hester_section_blog',
			'label'    => esc_html__( 'Post Settings', 'hester-core' ),
			'required' => array(
				array(
					'control'  => 'hester_enable_blog',
					'value'    => true,
					'operator' => '==',
				),
			),
		),
	);

	// Post count.
	$options['setting']['hester_blog_posts_number'] = array(
		'transport'         => 'postMessage',
		'sanitize_callback' => 'hester_sanitize_range',
		'control'           => array(
			'type'        => 'hester-range',
			'section'     => 'hester_section_blog',
			'label'       => esc_html__( 'Post Number', 'hester-core' ),
			'description' => esc_html__( 'Set the number of visible posts.', 'hester-core' ),
			'min'         => 1,
			'max'         => 3,
			'step'        => 1,
			'unit'        => '',
			'required'    => array(
				array(
					'control'  => 'hester_enable_blog',
					'value'    => true,
					'operator' => '==',
				),
				array(
					'control'  => 'hester_blog_posts_heading',
					'value'    => true,
					'operator' => '==',
				),
			),
		),
	);

	// Post category.
	$options['setting']['hester_blog_posts_category'] = array(
		'transport'         => 'refresh',
		'sanitize_callback' => 'hester_sanitize_select',
		'control'           => array(
			'type'        => 'hester-select',
			'section'     => 'hester_section_blog',
			'label'       => esc_html__( 'Category', 'hester-core' ),
			'description' => esc_html__( 'Display posts from selected category only. Leave empty to include all.', 'hester-core' ),
			'is_select2'  => true,
			'data_source' => 'category',
			'multiple'    => true,
			'required'    => array(
				array(
					'control'  => 'hester_enable_blog',
					'value'    => true,
					'operator' => '==',
				),
				array(
					'control'  => 'hester_blog_posts_heading',
					'value'    => true,
					'operator' => '==',
				),
			),
		),
	);

	$options['setting']['hester_blog_column'] = array(
		'transport'         => 'refresh',
		'sanitize_callback' => 'hester_sanitize_select',
		'control'           => array(
			'type'     => 'hester-select',
			'label'    => esc_html__( 'Columns', 'hester-core' ),
			'section'  => 'hester_section_blog',
			'choices'  => array(
				''   => esc_html__( 'Auto columns', 'hester-core' ),
				'-6' => esc_html__( '2 columns', 'hester-core' ),
				'-4' => esc_html__( '3 columns', 'hester-core' ),
			),
			'required' => array(
				array(
					'control'  => 'hester_enable_blog',
					'value'    => true,
					'operator' => '==',
				),
				array(
					'control'  => 'hester_blog_posts_heading',
					'value'    => true,
					'operator' => '==',
				),
			),
		),
	);

	return $options;
}
