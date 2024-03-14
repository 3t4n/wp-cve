<?php

add_filter( 'hester_customizer_options', 'hester_customizer_additional_options' );
function hester_customizer_additional_options( array $options ) {

	// info section
	$options['section']['hester_section_info'] = array(
		'title'          => esc_html__( 'Info section', 'hester-core' ),
		'panel'          => 'hester_panel_homepage',
		'class'          => 'Hester_Customizer_Control_Section_Hiding',
		'hiding_control' => 'hester_enable_info',
		'priority'       => (int) apply_filters( 'hester_section_priority', 1, 'hester_section_info' ),
	);

	// Schema toggle.
	$options['setting']['hester_enable_info'] = array(
		'transport'         => 'postMessage',
		'sanitize_callback' => 'hester_sanitize_toggle',
		'control'           => array(
			'type'        => 'hester-toggle',
			'label'       => esc_html__( 'Enable info', 'hester-core' ),
			'description' => esc_html__( 'Add info section , generally display below the slider.', 'hester-core' ),
			'section'     => 'hester_section_info',
		),
	);

	$options['setting']['hester_info_overlap'] = array(
		'transport'         => 'refresh',
		'sanitize_callback' => 'hester_sanitize_toggle',
		'control'           => array(
			'type'        => 'hester-toggle',
			'label'       => esc_html__( 'Overlap slider?', 'hester-core' ),
			'description' => esc_html__( 'On/Off section overlapping over slider.', 'hester-core' ),
			'section'     => 'hester_section_info',
			'required'    => array(
				array(
					'control'  => 'hester_enable_info',
					'value'    => true,
					'operator' => '==',
				),
			),
		),
	);

	$options['setting']['hester_info_sub_heading'] = array(
		'transport'         => 'postMessage',
		'sanitize_callback' => 'sanitize_text_field',
		'control'           => array(
			'type'     => 'hester-text',
			'label'    => esc_html__( 'Section Sub Heading', 'hester-core' ),
			'section'  => 'hester_section_info',
			'required' => array(
				array(
					'control'  => 'hester_enable_info',
					'value'    => true,
					'operator' => '==',
				),
				array(
					'control'  => 'hester_info_overlap',
					'value'    => false,
					'operator' => '==',
				),
			),
		),
	);

	$options['setting']['hester_info_heading'] = array(
		'transport'         => 'postMessage',
		'sanitize_callback' => 'sanitize_text_field',
		'control'           => array(
			'type'     => 'hester-text',
			'label'    => esc_html__( 'Section Heading', 'hester-core' ),
			'section'  => 'hester_section_info',
			'required' => array(
				array(
					'control'  => 'hester_enable_info',
					'value'    => true,
					'operator' => '==',
				),
				array(
					'control'  => 'hester_info_overlap',
					'value'    => false,
					'operator' => '==',
				),
			),
		),
		'partial'           => array(
			'selector'            => '#hester-info-heading',
			'render_callback'     => function () {
				return get_theme_mod( 'hester_info_heading' );
			},
			'container_inclusive' => false,
			'fallback_refresh'    => true,
		),
	);

	$options['setting']['hester_info_description'] = array(
		'transport'         => 'postMessage',
		'sanitize_callback' => 'hester_sanitize_textarea',
		'control'           => array(
			'type'     => 'hester-editor',
			'label'    => esc_html__( 'Section Description', 'hester-core' ),
			'section'  => 'hester_section_info',
			'required' => array(
				array(
					'control'  => 'hester_enable_info',
					'value'    => true,
					'operator' => '==',
				),
				array(
					'control'  => 'hester_info_overlap',
					'value'    => false,
					'operator' => '==',
				),
			),
		),
	);

	$options['setting']['hester_info_column'] = array(
		'transport'         => 'refresh',
		'sanitize_callback' => 'hester_sanitize_select',
		'control'           => array(
			'type'     => 'hester-select',
			'label'    => esc_html__( 'Columns', 'hester-core' ),
			'section'  => 'hester_section_info',
			'choices'  => array(
				''   => esc_html__( 'Auto columns', 'hester-core' ),
				'-6' => esc_html__( '2 columns', 'hester-core' ),
				'-4' => esc_html__( '3 columns', 'hester-core' ),
				'-3' => esc_html__( '4 columns', 'hester-core' ),
			),
			'required' => array(
				array(
					'control'  => 'hester_enable_info',
					'value'    => true,
					'operator' => '==',
				),
			),
		),
	);

	$options['setting']['hester_info_style'] = array(
		'transport'         => 'refresh',
		'sanitize_callback' => 'hester_sanitize_select',
		'control'           => array(
			'type'     => 'hester-select',
			'label'    => esc_html__( 'Style', 'hester-core' ),
			'section'  => 'hester_section_info',
			'choices'  => array(
				''   => esc_html__( 'Default style', 'hester-core' ),
				'0'  => esc_html__( '1 style', 'hester-core' ),
			),
			'required' => array(
				array(
					'control'  => 'hester_enable_info',
					'value'    => true,
					'operator' => '==',
				),
			),
		),
	);

	$options['setting']['hester_info_slides'] = array(
		'transport'         => 'refresh',
		'sanitize_callback' => 'hester_repeater_sanitize',
		'control'           => array(
			'type'          => 'hester-repeater',
			'label'         => esc_html__( 'Info content', 'hester-core' ),
			'section'       => 'hester_section_info',
			'item_name'     => esc_html__( 'Info', 'hester-core' ),

			'live_title_id' => 'title', // apply for unput text and textarea only
			'title_format'  => esc_html__( '[live_title]', 'hester-core' ), // [live_title]
			'add_text'      => esc_html__( 'Add new info', 'hester-core' ),
			'max_item'      => 4, // Maximum item can add,
			'limited_msg'   => wp_kses_post( __( 'Upgrade to <a target="_blank" href="https://peregrine-themes.com/hester/?utm_medium=customizer&utm_source=info&utm_campaign=upgradeToPro">Hester Pro</a> to be able to add more items and unlock other premium features!', 'hester-core' ) ),
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

				'is_active'   => array(
					'title' => esc_html__( 'Set Active', 'hester-core' ),
					'type'  => 'checkbox',
				),
			),

			'required'      => array(
				array(
					'control'  => 'hester_enable_info',
					'value'    => true,
					'operator' => '==',
				),
			),
		),

	);
	return $options;
}
