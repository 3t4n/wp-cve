<?php
add_filter( 'hester_customizer_options', 'hester_customizer_services_options' );
function hester_customizer_services_options( array $options ) {
	// Service section
	$options['section']['hester_section_services'] = array(
		'title'          => esc_html__( 'Service section', 'hester-core' ),
		'panel'          => 'hester_panel_homepage',
		'class'          => 'Hester_Customizer_Control_Section_Hiding',
		'hiding_control' => 'hester_enable_services',
		'priority'       => (int) apply_filters( 'hester_section_priority', 2, 'hester_section_services' ),
	);

	// Schema toggle.
	$options['setting']['hester_enable_services'] = array(
		'transport'         => 'postMessage',
		'sanitize_callback' => 'hester_sanitize_toggle',
		'control'           => array(
			'type'        => 'hester-toggle',
			'label'       => esc_html__( 'Enable Services', 'hester-core' ),
			'description' => esc_html__( 'Enable/Disable service section', 'hester-core' ),
			'section'     => 'hester_section_services',
		),
	);

	$options['setting']['hester_services_sub_heading'] = array(
		'transport'         => 'postMessage',
		'sanitize_callback' => 'sanitize_text_field',
		'control'           => array(
			'type'     => 'hester-text',
			'label'    => esc_html__( 'Section Sub Heading', 'hester-core' ),
			'section'  => 'hester_section_services',
			'required' => array(
				array(
					'control'  => 'hester_enable_services',
					'value'    => true,
					'operator' => '==',
				),
			),
		),
	);

	$options['setting']['hester_services_heading'] = array(
		'transport'         => 'postMessage',
		'sanitize_callback' => 'sanitize_text_field',
		'control'           => array(
			'type'     => 'hester-text',
			'label'    => esc_html__( 'Section Heading', 'hester-core' ),
			'section'  => 'hester_section_services',
			'required' => array(
				array(
					'control'  => 'hester_enable_services',
					'value'    => true,
					'operator' => '==',
				),
			),
		),
		'partial'           => array(
			'selector'            => '#hester-service-heading',
			'render_callback'     => function () {
				return get_theme_mod( 'hester_services_heading' );
			},
			'container_inclusive' => false,
			'fallback_refresh'    => true,
		),
	);

	$options['setting']['hester_services_description'] = array(
		'transport'         => 'postMessage',
		'sanitize_callback' => 'hester_sanitize_textarea',
		'control'           => array(
			'type'     => 'hester-editor',
			'label'    => esc_html__( 'Section Description', 'hester-core' ),
			'section'  => 'hester_section_services',
			'required' => array(
				array(
					'control'  => 'hester_enable_services',
					'value'    => true,
					'operator' => '==',
				),
			),
		),
	);

	$options['setting']['hester_services_column'] = array(
		'transport'         => 'refresh',
		'sanitize_callback' => 'hester_sanitize_select',
		'control'           => array(
			'type'     => 'hester-select',
			'label'    => esc_html__( 'Columns', 'hester-core' ),
			'section'  => 'hester_section_services',
			'choices'  => array(
				''   => esc_html__( 'Auto columns', 'hester-core' ),
				'-6' => esc_html__( '2 columns', 'hester-core' ),
				'-4' => esc_html__( '3 columns', 'hester-core' ),
			),
			'required' => array(
				array(
					'control'  => 'hester_enable_services',
					'value'    => true,
					'operator' => '==',
				),
			),
		),
	);

	$options['setting']['hester_services_slides'] = array(
		'transport'         => 'refresh',
		'sanitize_callback' => 'hester_repeater_sanitize',
		'control'           => array(
			'type'          => 'hester-repeater',
			'label'         => esc_html__( 'Services', 'hester-core' ),
			'section'       => 'hester_section_services',
			'item_name'     => esc_html__( 'Service', 'hester-core' ),

			'live_title_id' => 'title', // apply for unput text and textarea only
			'title_format'  => esc_html__( '[live_title]', 'hester-core' ), // [live_title]
			'add_text'      => esc_html__( 'Add new Service', 'hester-core' ),
			'max_item'      => 3, // Maximum item can add,
			'limited_msg'   => wp_kses_post( __( 'Upgrade to <a target="_blank" href="https://peregrine-themes.com/hester/?utm_medium=customizer&utm_source=services&utm_campaign=upgradeToPro">Hester Pro</a> to be able to add more items and unlock other premium features!', 'hester-core' ) ),
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

				'linktext'    => array(
					'title'    => esc_html__( 'Link Text', 'hester-core' ),
					'type'     => 'text',
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
					'control'  => 'hester_enable_services',
					'value'    => true,
					'operator' => '==',
				),
			),
		),

	);

	return $options;
}
