<?php
function spawp_frontpage_sections_settings( $wp_customize ){

	if ( class_exists( 'SPAWP_Customizer_Repeater' ) ) {
		$wp_customize->add_setting('spawp_option[slider_content]', array(
			'default'           => spwp_slider_default_contents(),
			'sanitize_callback' => 'spawp_customizer_repeater_sanitize',
			'type' => 'option',
		) );
	    $wp_customize->add_control( new SPAWP_Customizer_Repeater( $wp_customize,'spawp_option[slider_content]', array(
			'label'                             => esc_html__( 'Slider Items Content', 'spawp' ),
			'section'                           => 'spawp_slider_section',
			'priority' => 100,
			'add_field_label'                   => esc_html__( 'Add new slide item', 'spawp' ),
			'item_name'                         => esc_html__( 'Slide Item', 'spawp' ),
			'customizer_repeater_image_control' => true,
			'customizer_repeater_icon_control' => false,
			'customizer_repeater_title_control' => true,
			'customizer_repeater_subtitle_control' => true,
			'customizer_repeater_text_control'  => true,
			'customizer_repeater_button_text_control' => true,
			'customizer_repeater_link_control'  => true,
			'customizer_repeater_checkbox_control' => true,
			'customizer_repeater_content_align' => true,
			)
	    ) );

	    $wp_customize->add_setting('spawp_option[service_content]',	array(
	    	'default'           => spwp_service_default_contents(),
			'sanitize_callback' => 'spawp_customizer_repeater_sanitize',
			'type' => 'option',
		) );
	    $wp_customize->add_control( new SPAWP_Customizer_Repeater( $wp_customize,'spawp_option[service_content]', array(
			'label'                             => esc_html__( 'Service Items Content', 'spawp' ),
			'section'                           => 'spawp_service_section',
			'priority' => 100,
			'add_field_label'                   => esc_html__( 'Add new service item', 'spawp' ),
			'item_name'                         => esc_html__( 'Service Item', 'spawp' ),
			'customizer_repeater_icon_control' => false,
			'customizer_repeater_image_control' => true,
			'customizer_repeater_title_control' => true,
			'customizer_repeater_text_control'  => true,
			'customizer_repeater_currency_control'  => true,
			'customizer_repeater_price_control'  => true,
			'customizer_repeater_button_text_control' => true,
			'customizer_repeater_link_control'  => true,
			'customizer_repeater_checkbox_control' => true,
			)
	    ) );

	    $wp_customize->add_setting('spawp_option[feature_content]',	array(
	    	'default'           => spwp_feature_default_contents(),
			'sanitize_callback' => 'spawp_customizer_repeater_sanitize',
			'type' => 'option',
		) );
	    $wp_customize->add_control( new SPAWP_Customizer_Repeater( $wp_customize,'spawp_option[feature_content]', array(
			'label'                             => esc_html__( 'Feature Items Content', 'spawp' ),
			'section'                           => 'spawp_feature_section',
			'priority' => 100,
			'add_field_label'                   => esc_html__( 'Add new feature item', 'spawp' ),
			'item_name'                         => esc_html__( 'Feature Item', 'spawp' ),
			'customizer_repeater_icon_control' => true,
			'customizer_repeater_image_control' => false,
			'customizer_repeater_title_control' => true,
			'customizer_repeater_text_control'  => true,
			'customizer_repeater_button_text_control' => false,
			'customizer_repeater_link_control'  => false,
			'customizer_repeater_checkbox_control' => false,
			)
	    ) );

	    $wp_customize->add_setting('spawp_option[testimonial_content]', array(
	    	'default'           => spwp_testimonial_default_contents(),
			'sanitize_callback' => 'spawp_customizer_repeater_sanitize',
			'type' => 'option',
		) );
	    $wp_customize->add_control( new SPAWP_Customizer_Repeater( $wp_customize,'spawp_option[testimonial_content]', array(
			'label'                             => esc_html__( 'Testimonial Items Content', 'spawp' ),
			'section'                           => 'spawp_testimonial_section',
			'priority' => 100,
			'add_field_label'                   => esc_html__( 'Add new testimonial item', 'spawp' ),
			'item_name'                         => esc_html__( 'Testimonial Item', 'spawp' ),
			'customizer_repeater_icon_control' => false,
			'customizer_repeater_image_control' => true,
			'customizer_repeater_title_control' => true,
			'customizer_repeater_designation_control' => true,
			'customizer_repeater_text_control'  => true,
			'customizer_repeater_button_text_control' => false,
			'customizer_repeater_link_control'  => false,
			'customizer_repeater_checkbox_control' => false,
			'customizer_repeater_repeater_control' => false,
			)
	    ) );

	    $wp_customize->add_setting('spawp_option[team_content]', array(
	    	'default'           => spwp_team_default_contents(),
			'sanitize_callback' => 'spawp_customizer_repeater_sanitize',
			'type' => 'option',
		) );
	    $wp_customize->add_control( new SPAWP_Customizer_Repeater( $wp_customize,'spawp_option[team_content]',array(
			'label'                             => esc_html__( 'Team Items Content', 'spawp' ),
			'section'                           => 'spawp_team_section',
			'priority' => 100,
			'add_field_label'                   => esc_html__( 'Add new team item', 'spawp' ),
			'item_name'                         => esc_html__( 'Team Item', 'spawp' ),
			'customizer_repeater_icon_control' => false,
			'customizer_repeater_image_control' => true,
			'customizer_repeater_title_control' => true,
			'customizer_repeater_designation_control' => true,
			'customizer_repeater_text_control'  => true,
			'customizer_repeater_button_text_control' => false,
			'customizer_repeater_link_control'  => true,
			'customizer_repeater_checkbox_control' => false,
			'customizer_repeater_repeater_control' => true,
			)
	    ) );
	}

	$sections = array(
		'service',
		'feature',
		'testimonial',
		'team',
	);

	foreach ($sections as $section) {
		$wp_customize->get_setting( 'spawp_option['.$section.'_subtitle]' )->transport  = 'postMessage';
		$wp_customize->selective_refresh->add_partial(
			'spawp_option['.$section.'_subtitle]',
			array(
				'selector'        => '.home_section.'.$section.' .section_subtitle',
				'render_callback' => array( 'SPAWP_Customizer_Partials', $section.'_subtitle' ),
			)
		);

		$wp_customize->get_setting( 'spawp_option['.$section.'_title]' )->transport  = 'postMessage';
		$wp_customize->selective_refresh->add_partial(
			'spawp_option['.$section.'_title]',
			array(
				'selector'        => '.home_section.'.$section.' .section_title',
				'render_callback' => array( 'SPAWP_Customizer_Partials', $section.'_title' ),
			)
		);

		$wp_customize->get_setting( 'spawp_option['.$section.'_desc]' )->transport  = 'postMessage';
		$wp_customize->selective_refresh->add_partial(
			'spawp_option['.$section.'_desc]',
			array(
				'selector'        => '.home_section.'.$section.' .section_description',
				'render_callback' => array( 'SPAWP_Customizer_Partials', $section.'_desc' ),
			)
		);
	}

	// blog
	$wp_customize->get_setting( 'spawp_option[blog_subtitle]' )->transport  = 'postMessage';
	$wp_customize->selective_refresh->add_partial(
		'spawp_option[blog_subtitle]',
		array(
			'selector'        => '.home_section.news .section_subtitle',
		)
	);

	$wp_customize->get_setting( 'spawp_option[blog_title]' )->transport  = 'postMessage';
	$wp_customize->selective_refresh->add_partial(
		'spawp_option[blog_title]',
		array(
			'selector'        => '.home_section.news .section_title',
		)
	);

	$wp_customize->get_setting( 'spawp_option[blog_desc]' )->transport  = 'postMessage';
	$wp_customize->selective_refresh->add_partial(
		'spawp_option[blog_desc]',
		array(
			'selector'        => '.home_section.news .section_description',
		)
	);

}
add_action( 'customize_register', 'spawp_frontpage_sections_settings',30 );