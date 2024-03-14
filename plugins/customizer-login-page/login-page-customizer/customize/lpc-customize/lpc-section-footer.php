<?php
/** Section : Footer. */
$wp_customize->add_section(
	'lpc-section-footer',
	array(
		'title' => __( 'Footer & Copyrights', 'customizer-login-page' ),
		'panel' => 'lpc-main-panel',
	)
);
// Footer heading control.
$wp_customize->add_setting(
	'lpc-footer-heading',
	array(
		'default'           => '',
		'sanitize_callback' => 'sanitize_text_field',
	)
);
$wp_customize->add_control(
	new Lpc_Simple_Notice_Custom_control(
		$wp_customize,
		'lpc-footer-heading-control',
		array(
			'label'    => __( 'Login Page Footer Settings', 'customizer-login-page' ),
			'settings' => 'lpc-footer-heading',
			'section'  => 'lpc-section-footer',
		)
	)
);
// Enable/Disable Footer .
$wp_customize->add_setting(
	'lpc_opts[lpc-footer-enable]',
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
		'lpc_opts[lpc-footer-enable]-control',
		array(
			'label'    => esc_html__( 'Display Footer', 'customizer-login-page' ),
			'settings' => 'lpc_opts[lpc-footer-enable]',
			'section'  => 'lpc-section-footer',
		)
	)
);

// Footer Bg Color.
$wp_customize->add_setting(
	'lpc_opts[lpc-footer-background]',
	array(
		'type'              => 'option',
		'default'           => '#fff',
		'transport'         => 'postMessage',
		'sanitize_callback' => 'lpc_hex_rgba_sanitization',
	)
);
// Alpha Color Picker control.
$wp_customize->add_control(
	new LPC_Alpha_Color_Control(
		$wp_customize,
		'lpc_opts[lpc-footer-background]-control',
		array(
			'label'       => __( 'Footer Background Color', 'customizer-login-page' ),
			'description' => __( 'Set opacity 0 to make it transparent/Invisible.', 'customizer-login-page' ),
			'settings'    => 'lpc_opts[lpc-footer-background]',
			'section'     => 'lpc-section-footer',
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
// Copyright heading control.
$wp_customize->add_setting(
	'lpc-footer-copyright-heading',
	array(
		'default'           => '',
		'sanitize_callback' => 'sanitize_text_field',
	)
);
$wp_customize->add_control(
	new Lpc_Simple_Notice_Custom_control(
		$wp_customize,
		'lpc-footer-copyright-heading-control',
		array(
			'label'    => __( 'Copyright Settings', 'customizer-login-page' ),
			'settings' => 'lpc-footer-copyright-heading',
			'section'  => 'lpc-section-footer',
		)
	)
);
// Enable/Disable Footer .
$wp_customize->add_setting(
	'lpc_opts[lpc-copyright-enable]',
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
		'lpc_opts[lpc-copyright-enable]-control',
		array(
			'label'    => esc_html__( 'Display Copyright', 'customizer-login-page' ),
			'settings' => 'lpc_opts[lpc-copyright-enable]',
			'section'  => 'lpc-section-footer',
		)
	)
);
// Copyright Text.
$wp_customize->add_setting(
	'lpc_opts[lpc-footer-copyright-text]',
	array(
		'type'              => 'option',
		'default'           => '© 2023 WordPress, All Rights Reserved.',
		'transport'         => 'postMessage',
		'sanitize_callback' => 'sanitize_text_field',
	)
);
$wp_customize->add_control(
	new Lpc_Custom_Textarea_Control(
		$wp_customize,
		'lpc_opts[lpc-footer-copyright-text]-control',
		array(
			'label'       => __( 'Copyright Text', 'customizer-login-page' ),
			'settings'    => 'lpc_opts[lpc-footer-copyright-text]',
			'section'     => 'lpc-section-footer',
			'type'        => 'text',
			'input_attrs' => array(
				'rows' => '2',
			),
		)
	)
);
// Copyright Text Color.
$wp_customize->add_setting(
	'lpc_opts[lpc-footer-copyright-color]',
	array(
		'type'              => 'option',
		'default'           => '#000',
		'transport'         => 'postMessage',
		'sanitize_callback' => 'lpc_hex_rgba_sanitization',
	)
);
// Alpha Color Picker control.
$wp_customize->add_control(
	new LPC_Alpha_Color_Control(
		$wp_customize,
		'lpc_opts[lpc-footer-copyright-color]-control',
		array(
			'label'       => __( 'Copyright Text Color', 'customizer-login-page' ),
			'settings'    => 'lpc_opts[lpc-footer-copyright-color]',
			'section'     => 'lpc-section-footer',
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

// Copyright Font Size.
$wp_customize->add_setting(
	'lpc_opts[lpc-footer-copyright-font-size]',
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
		'lpc_opts[lpc-footer-copyright-font-size]-control',
		array(
			'label'       => esc_html__( 'Copyright Font Size (px) ', 'customizer-login-page' ),
			'settings'    => 'lpc_opts[lpc-footer-copyright-font-size]',
			'section'     => 'lpc-section-footer',
			'input_attrs' => array(
				'min'  => 0, // Required. Minimum value for the slider.
				'max'  => 50, // Required. Maximum value for the slider.
				'step' => 1, // Required. The size of each interval or step the slider takes between the minimum and maximum values.
			),
		)
	)
);
// Copyright Font Size.
$wp_customize->add_setting(
	'lpc_opts[lpc-footer-copyright-font-weight]',
	array(
		'type'              => 'option',
		'default'           => '400',
		'transport'         => 'postMessage',
		'sanitize_callback' => 'lpc_sanitize_integer',
	)
);
$wp_customize->add_control(
	new lpc_Slider_Custom_Control(
		$wp_customize,
		'lpc_opts[lpc-footer-copyright-font-weight]-control',
		array(
			'label'       => esc_html__( 'Copyright Font Weight', 'customizer-login-page' ),
			'settings'    => 'lpc_opts[lpc-footer-copyright-font-weight]',
			'section'     => 'lpc-section-footer',
			'input_attrs' => array(
				'min'  => 100, // Required. Minimum value for the slider.
				'max'  => 900, // Required. Maximum value for the slider.
				'step' => 100, // Required. The size of each interval or step the slider takes between the minimum and maximum values.
			),
		)
	)
);
// Poweredby heading control.
$wp_customize->add_setting(
	'lpc-footer-poweredby-heading',
	array(
		'default'           => '',
		'sanitize_callback' => 'sanitize_text_field',
	)
);
$wp_customize->add_control(
	new Lpc_Simple_Notice_Custom_control(
		$wp_customize,
		'lpc-footer-poweredby-heading-control',
		array(
			'label'       => __( 'Poweredby Settings', 'customizer-login-page' ),
			'description' => __( 'Support US by displaying "Poweredby: Customizer Login Page".', 'customizer-login-page' ),
			'settings'    => 'lpc-footer-poweredby-heading',
			'section'     => 'lpc-section-footer',
		)
	)
);
// Enable/Disable Poweredby .
$wp_customize->add_setting(
	'lpc_opts[lpc-poweredby-enable]',
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
		'lpc_opts[lpc-poweredby-enable]-control',
		array(
			'label'    => esc_html__( 'Display Poweredby', 'customizer-login-page' ),
			'settings' => 'lpc_opts[lpc-poweredby-enable]',
			'section'  => 'lpc-section-footer',
		)
	)
);
// Powered by Left/Right Choose.
$wp_customize->add_setting(
	'lpc_opts[lpc-poweredby-position]',
	array(
		'type'              => 'option',
		'default'           => 'right',
		'transport'         => 'postMessage',
		'sanitize_callback' => 'sanitize_text_field',
	)
);
$wp_customize->add_control(
	new Lpc_Text_Radio_Button_Custom_Control(
		$wp_customize,
		'lpc_opts[lpc-poweredby-position]-control',
		array(
			'label'    => __( 'Poweredby Position', 'customizer-login-page' ),
			'settings' => 'lpc_opts[lpc-poweredby-position]',
			'section'  => 'lpc-section-footer',
			'choices'  => array(
				'left'  => __( 'Left' ),
				'right' => __( 'Right' ),
			),
		)
	)
);
// poweredby Text Color.
$wp_customize->add_setting(
	'lpc_opts[lpc-footer-poweredby-color]',
	array(
		'type'              => 'option',
		'default'           => '#000',
		'transport'         => 'postMessage',
		'sanitize_callback' => 'lpc_hex_rgba_sanitization',
	)
);
// Alpha Color Picker control.
$wp_customize->add_control(
	new LPC_Alpha_Color_Control(
		$wp_customize,
		'lpc_opts[lpc-footer-poweredby-color]-control',
		array(
			'label'       => __( 'Poweredby Text Color', 'customizer-login-page' ),
			'settings'    => 'lpc_opts[lpc-footer-poweredby-color]',
			'section'     => 'lpc-section-footer',
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