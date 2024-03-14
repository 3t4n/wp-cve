<?php
/** Start : Section Lost PassWord Settings */
$wp_customize->add_section(
	'lpc-section-lostpass',
	array(
		'title' => __( 'Forget Link & Forget Form', 'customizer-login-page' ),
		'panel' => 'lpc-main-panel',
	)
);
	// Lost Pass Link heading control.
	$wp_customize->add_setting(
		'lpc-form-lostpass-link-heading',
		array(
			'default'           => '',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);
	$wp_customize->add_control(
		new Lpc_Simple_Notice_Custom_control(
			$wp_customize,
			'lpc-form-lostpass-link-heading-control',
			array(
				'label'    => __( 'Link Settings for Lost Password', 'customizer-login-page' ),
				'settings' => 'lpc-form-lostpass-link-heading',
				'section'  => 'lpc-section-lostpass',
			)
		)
	);
	// Enable/Disable Lost Password.
	$wp_customize->add_setting(
		'lpc_opts[lpc-form-lostpass-enable]',
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
			'lpc_opts[lpc-form-lostpass-enable]-control',
			array(
				'label'    => esc_html__( 'Display Lost Password', 'customizer-login-page' ),
				'settings' => 'lpc_opts[lpc-form-lostpass-enable]',
				'section'  => 'lpc-section-lostpass',
			)
		)
	);

	// Lost Pass Link text.
	$wp_customize->add_setting(
		'lpc_opts[lpc-form-lostpass-text]',
		array(
			'type'              => 'option',
			'default'           => 'Lost your password?',
			'transport'         => 'postMessage',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);
	$wp_customize->add_control(
		'lpc_opts[lpc-form-lostpass-text]-control',
		array(
			'label'       => __( 'Lost your Password Text:', 'customizer-login-page' ),
			'description' => __( 'Default: Lost your password? ', 'customizer-login-page' ),
			'section'     => 'lpc-section-lostpass',
			'settings'    => 'lpc_opts[lpc-form-lostpass-text]',
			'type'        => 'text',
		)
	);
	// Lost Password Font Size.
	$wp_customize->add_setting(
		'lpc_opts[lpc-form-lostpass-font-size]',
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
			'lpc_opts[lpc-form-lostpass-font-size]-control',
			array(
				'label'       => esc_html__( 'Link Font Size (px) ', 'customizer-login-page' ),
				'settings'    => 'lpc_opts[lpc-form-lostpass-font-size]',
				'section'     => 'lpc-section-lostpass',
				'input_attrs' => array(
					'min'  => 0, // Required. Minimum value for the slider.
					'max'  => 50, // Required. Maximum value for the slider.
					'step' => 1, // Required. The size of each interval or step the slider takes between the minimum and maximum values.
				),
			)
		)
	);
	// lostpass Text Color.
	$wp_customize->add_setting(
		'lpc_opts[lpc-form-lostpass-text-color]',
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
			'lpc_opts[lpc-form-lostpass-text-color]-control',
			array(
				'label'       => __( 'lost password Text Color', 'customizer-login-page' ),
				'settings'    => 'lpc_opts[lpc-form-lostpass-text-color]',
				'section'     => 'lpc-section-lostpass',
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
	// lostpass Text Color (Hover).
	$wp_customize->add_setting(
		'lpc_opts[lpc-form-lostpass-text-color-hover]',
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
			'lpc_opts[lpc-form-lostpass-text-color-hover]-control',
			array(
				'label'       => __( 'Lost password Text Color (Hover)', 'customizer-login-page' ),
				'settings'    => 'lpc_opts[lpc-form-lostpass-text-color-hover]',
				'section'     => 'lpc-section-lostpass',
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
	// Lost Password Text Align.
	$wp_customize->add_setting(
		'lpc_opts[lpc-form-lostpass-text-align]',
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
			'lpc_opts[lpc-form-lostpass-text-align]-control',
			array(
				'label'    => __( 'Align Text', 'customizer-login-page' ),
				'settings' => 'lpc_opts[lpc-form-lostpass-text-align]',
				'section'  => 'lpc-section-lostpass',
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
		'lpc_opts[lpc-form-lostpass-position-x]',
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
			'lpc_opts[lpc-form-lostpass-position-x]-control',
			array(
				'label'       => esc_html__( 'Position X-axis (%)', 'customizer-login-page' ),
				'description' => esc_html__( 'Position is set in presets as per Register Link is enabled' ),
				'settings'    => 'lpc_opts[lpc-form-lostpass-position-x]',
				'section'     => 'lpc-section-lostpass',
				'input_attrs' => array(
					'min'  => -100, // Required. Minimum value for the slider.
					'max'  => 100, // Required. Maximum value for the slider.
					'step' => 1, // Required. The size of each interval or step the slider takes between the minimum and maximum values.
				),
			)
		)
	);
	// Lost Pass form heading control.
	$wp_customize->add_setting(
		'lpc-form-lostpass-form-heading',
		array(
			'default'           => '',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);
	$wp_customize->add_control(
		new Lpc_Simple_Notice_Custom_control(
			$wp_customize,
			'lpc-form-lostpass-form-heading-control',
			array(
				'label'       => __( 'Lost Password Form settings ', 'customizer-login-page' ),
				'description' => __( 'Visit Lost Password form to see changes.', 'logipc' ),
				'settings'    => 'lpc-form-lostpass-form-heading',
				'section'     => 'lpc-section-lostpass',
			)
		)
	);
	// Lost Pass Box Label text.
	$wp_customize->add_setting(
		'lpc_opts[lpc-form-lostpass-box-label]',
		array(
			'type'              => 'option',
			'default'           => 'Username or Email Address',
			'transport'         => 'postMessage',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);
	$wp_customize->add_control(
		'lpc_opts[lpc-form-lostpass-box-label]-control',
		array(
			'label'       => __( 'Text Box Label', 'customizer-login-page' ),
			'description' => __( 'Default: Username or Email Address', 'customizer-login-page' ),
			'section'     => 'lpc-section-lostpass',
			'settings'    => 'lpc_opts[lpc-form-lostpass-box-label]',
			'type'        => 'text',
		)
	);
	// Lost Pass Label Font Size.
	$wp_customize->add_setting(
		'lpc_opts[lpc-form-lostpass-box-label-size]',
		array(
			'type'              => 'option',
			'default'           => '14',
			'transport'         => 'postMessage',
			'sanitize_callback' => 'lpc_sanitize_integer',
		)
	);
	$wp_customize->add_control(
		new lpc_Slider_Custom_Control(
			$wp_customize,
			'lpc_opts[lpc-form-lostpass-box-label-size]-control',
			array(
				'label'       => esc_html__( 'Label font size (px)', 'customizer-login-page' ),
				'settings'    => 'lpc_opts[lpc-form-lostpass-box-label-size]',
				'section'     => 'lpc-section-lostpass',
				'input_attrs' => array(
					'min'  => 0, // Required. Minimum value for the slider.
					'max'  => 100, // Required. Maximum value for the slider.
					'step' => 1, // Required. The size of each interval or step the slider takes between the minimum and maximum values.
				),
			)
		)
	);
	// Lost Password Text Align.
	$wp_customize->add_setting(
		'lpc_opts[lpc-form-lostpass-label-align]',
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
			'lpc_opts[lpc-form-lostpass-label-align]-control',
			array(
				'label'    => __( 'Align Label', 'customizer-login-page' ),
				'settings' => 'lpc_opts[lpc-form-lostpass-label-align]',
				'section'  => 'lpc-section-lostpass',
				'choices'  => array( // Optional.
					'left'   => __( 'Left' ),
					'center' => __( 'Center' ),
					'right'  => __( 'Right' ),
				),
			)
		)
	);
	/** End : Section Lost PassWord Settings */
