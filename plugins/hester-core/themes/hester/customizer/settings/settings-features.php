<?php

add_filter( 'hester_customizer_options', 'hester_customizer_features_options' );
function hester_customizer_features_options( array $options ) {
	// Features section
	$options['section']['hester_section_features'] = array(
		'title'          => esc_html__( 'Feature section', 'hester-core' ),
		'panel'          => 'hester_panel_homepage',
		'class'          => 'Hester_Customizer_Control_Section_Hiding',
		'hiding_control' => 'hester_enable_features',
		'priority'       => (int) apply_filters( 'hester_section_priority', 4, 'hester_section_features' ),
	);

	// toggle.
	$options['setting']['hester_enable_features'] = array(
		'transport'         => 'postMessage',
		'sanitize_callback' => 'hester_sanitize_toggle',
		'control'           => array(
			'type'    => 'hester-toggle',
			'label'   => esc_html__( 'Enable Feature section', 'hester-core' ),
			'section' => 'hester_section_features',
		),
		'required'          => array(
			array(
				'control'  => 'hester_section_features',
				'value'    => true,
				'operator' => '==',
			),
		),
	);

	$options['setting']['hester_features_sub_heading'] = array(
		'transport'         => 'postMessage',
		'sanitize_callback' => 'sanitize_text_field',
		'control'           => array(
			'type'     => 'hester-text',
			'label'    => esc_html__( 'Section Sub Heading', 'hester-core' ),
			'section'  => 'hester_section_features',
			'required' => array(
				array(
					'control'  => 'hester_enable_features',
					'value'    => true,
					'operator' => '==',
				),
			),
		),
	);

	$options['setting']['hester_features_heading'] = array(
		'transport'         => 'postMessage',
		'sanitize_callback' => 'sanitize_text_field',
		'control'           => array(
			'type'     => 'hester-text',
			'label'    => esc_html__( 'Section Heading', 'hester-core' ),
			'section'  => 'hester_section_features',
			'required' => array(
				array(
					'control'  => 'hester_enable_features',
					'value'    => true,
					'operator' => '==',
				),
			),
		),
		'partial'           => array(
			'selector'            => '#hester-features-heading',
			'render_callback'     => function() {
				return get_theme_mod( 'hester_features_heading' );},
			'container_inclusive' => false,
			'fallback_refresh'    => true,
		),
	);

	$options['setting']['hester_features_description'] = array(
		'transport'         => 'postMessage',
		'sanitize_callback' => 'hester_sanitize_textarea',
		'control'           => array(
			'type'     => 'hester-editor',
			'label'    => esc_html__( 'Section Description', 'hester-core' ),
			'section'  => 'hester_section_features',
			'required' => array(
				array(
					'control'  => 'hester_enable_features',
					'value'    => true,
					'operator' => '==',
				),
			),
		),
	);

	$options['setting']['hester_features_slides'] = array(
		'transport'         => 'refresh',
		'sanitize_callback' => 'hester_repeater_sanitize',
		'control'           => array(
			'type'          => 'hester-repeater',
			'label'         => esc_html__( 'Features', 'hester-core' ),
			'section'       => 'hester_section_features',
			'item_name'     => esc_html__( 'Feature', 'hester-core' ),

			'live_title_id' => 'title', // apply for unput text and textarea only
			'title_format'  => esc_html__( '[live_title]', 'hester-core' ), // [live_title]
			'add_text'      => esc_html__( 'Add new Feature', 'hester-core' ),
			'max_item'      => 4, // Maximum item can add,
			'limited_msg'   => wp_kses_post( __( 'Upgrade to <a target="_blank" href="https://peregrine-themes.com/hester/?utm_medium=customizer&utm_source=features&utm_campaign=upgradeToPro">Hester Pro</a> to be able to add more items and unlock other premium features!', 'hester-core' ) ),
			'fields'        => array(

				'icon'        => array(
					'title' => esc_html__( 'Icon', 'hester-core' ),
					'type'  => 'icon',
				),
				'title'       => array(
					'title' => esc_html__( 'Title', 'hester-core' ),
					'type'  => 'text',
				),
				'description' => array(
					'title' => esc_html__( 'Description', 'hester-core' ),
					'type'  => 'editor',
				),
				'add_link'    => array(
					'title' => esc_html__( 'Add Link?', 'hester-core' ),
					'type'  => 'checkbox',
				),
				'link'        => array(
					'title'    => esc_html__( 'Link', 'hester-core' ),
					'type'     => 'url',
					'required' => array(
						array(
							'add_link',
							'=',
							true,
						),
					),
				),
			),

			'required'      => array(
				array(
					'control'  => 'hester_enable_features',
					'value'    => true,
					'operator' => '==',
				),
			),
		),
	);

	return $options;
}
