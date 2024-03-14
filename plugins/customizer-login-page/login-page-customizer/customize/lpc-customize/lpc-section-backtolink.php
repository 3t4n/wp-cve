<?php
/** Start : Section Back to Link Settings */
$wp_customize->add_section(
	'lpc-section-backtolink',
	array(
		'title' => __( 'Go to/Back to Link', 'customizer-login-page' ),
		'panel' => 'lpc-main-panel',
	)
);
	// Back to Link heading control.
	$wp_customize->add_setting(
		'lpc-backtolink-heading',
		array(
			'default'           => '',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);
	$wp_customize->add_control(
		new Lpc_Simple_Notice_Custom_control(
			$wp_customize,
			'lpc-backtolink-heading-control',
			array(
				'label'    => __( 'Go to/Back to Link settings', 'customizer-login-page' ),
				'settings' => 'lpc-backtolink-heading',
				'section'  => 'lpc-section-backtolink',
			)
		)
	);
	// Enable/Disable Back to link.
	$wp_customize->add_setting(
		'lpc_opts[lpc-backtolink-enable]',
		array(
			'type'              => 'option',
			'default'           => 1,
			'transport'         => 'postMessage',
			'sanitize_callback' => 'lpc_switch_sanitization',
		)
	);
	$wp_customize->add_control(
		new Lpc_Toggle_Switch_Custom_control(
			$wp_customize,
			'lpc_opts[lpc-backtolink-enable]-control',
			array(
				'label'    => esc_html__( 'Display Go to/Back to Link', 'customizer-login-page' ),
				'settings' => 'lpc_opts[lpc-backtolink-enable]',
				'section'  => 'lpc-section-backtolink',
			)
		)
	);

	// Back to Link Font Size.
	$wp_customize->add_setting(
		'lpc_opts[lpc-backtolink-font-size]',
		array(
			'type'              => 'option',
			'default'           => '13',
			'transport'         => 'postMessage',
			'sanitize_callback' => 'lpc_sanitize_integer',
		)
	);
	$wp_customize->add_control(
		new lpc_Slider_Custom_Control(
			$wp_customize,
			'lpc_opts[lpc-backtolink-font-size]-control',
			array(
				'label'       => esc_html__( 'Go to/Back to Link Font Size (px) ', 'customizer-login-page' ),
				'settings'    => 'lpc_opts[lpc-backtolink-font-size]',
				'section'     => 'lpc-section-backtolink',
				'input_attrs' => array(
					'min'  => 0, // Required. Minimum value for the slider.
					'max'  => 50, // Required. Maximum value for the slider.
					'step' => 1, // Required. The size of each interval or step the slider takes between the minimum and maximum values.
				),
			)
		)
	);
	// Back to Link Text Color.
	$wp_customize->add_setting(
		'lpc_opts[lpc-backtolink-text-color]',
		array(
			'type'              => 'option',
			'default'           => '#50575e',
			'transport'         => 'postMessage',
			'sanitize_callback' => 'lpc_hex_rgba_sanitization',
		)
	);

	$wp_customize->add_control(
		new LPC_Alpha_Color_Control(
			$wp_customize,
			'lpc_opts[lpc-backtolink-text-color]-control',
			array(
				'label'       => __( 'Go to/Back to Link Color', 'customizer-login-page' ),
				'settings'    => 'lpc_opts[lpc-backtolink-text-color]',
				'section'     => 'lpc-section-backtolink',
				'input_attrs' => array(
					'resetalpha' => false,
					'palette'    => array(
						'rgba(99,78,150,1)',
						'rgba(67,78,150,1)',
						'rgba(34,78,150,.7)',
						'rgba(3,78,150,1)',
						'rgba(7,110,230,.9)',
						'rgba(234,78,150,1)',
						'rgba(99,78,150,.5)',
						'rgba(190,120,120,.5)',
					),
				),
			)
		)
	);
	// Back to Link Text Color (Hover).
	$wp_customize->add_setting(
		'lpc_opts[lpc-backtolink-text-color-hover]',
		array(
			'type'              => 'option',
			'default'           => '#135e96',
			'transport'         => 'postMessage',
			'sanitize_callback' => 'lpc_hex_rgba_sanitization',
		)
	);

	$wp_customize->add_control(
		new LPC_Alpha_Color_Control(
			$wp_customize,
			'lpc_opts[lpc-backtolink-text-color-hover]-control',
			array(
				'label'       => __( 'Go to/Back to Link Color(Hover)', 'customizer-login-page' ),
				'settings'    => 'lpc_opts[lpc-backtolink-text-color-hover]',
				'section'     => 'lpc-section-backtolink',
				'input_attrs' => array(
					'resetalpha' => false,
					'palette'    => array(
						'rgba(99,78,150,1)',
						'rgba(67,78,150,1)',
						'rgba(34,78,150,.7)',
						'rgba(3,78,150,1)',
						'rgba(7,110,230,.9)',
						'rgba(234,78,150,1)',
						'rgba(99,78,150,.5)',
						'rgba(190,120,120,.5)',
					),
				),
			)
		)
	);
	// Back to Link Text Align.
	$wp_customize->add_setting(
		'lpc_opts[lpc-backtolink-text-align]',
		array(
			'type'              => 'option',
			'default'           => 'left',
			'transport'         => 'postMessage',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);

	$wp_customize->add_control(
		new Lpc_Text_Radio_Button_Custom_Control(
			$wp_customize,
			'lpc_opts[lpc-backtolink-text-align]-control',
			array(
				'label'    => __( 'Align Text', 'customizer-login-page' ),
				'settings' => 'lpc_opts[lpc-backtolink-text-align]',
				'section'  => 'lpc-section-backtolink',
				'choices'  => array( // Optional.
					'left'   => __( 'Left' ),
					'center' => __( 'Center' ),
					'right'  => __( 'Right' ),
				),
			)
		)
	);
	// Lostpass Link Position X.
	$wp_customize->add_setting(
		'lpc_opts[lpc-backtolink-position-x]',
		array(
			'type'              => 'option',
			'default'           => '0',
			'transport'         => 'postMessage',
			'sanitize_callback' => 'lpc_sanitize_integer',
		)
	);
	$wp_customize->add_control(
		new lpc_Slider_Custom_Control(
			$wp_customize,
			'lpc_opts[lpc-backtolink-position-x]-control',
			array(
				'label'       => esc_html__( 'Position X-axis (%)', 'customizer-login-page' ),
				'settings'    => 'lpc_opts[lpc-backtolink-position-x]',
				'section'     => 'lpc-section-backtolink',
				'input_attrs' => array(
					'min'  => -100, // Required. Minimum value for the slider.
					'max'  => 100, // Required. Maximum value for the slider.
					'step' => 1, // Required. The size of each interval or step the slider takes between the minimum and maximum values.
				),
			)
		)
	);
	/** End : Section Back to Link Settings */
