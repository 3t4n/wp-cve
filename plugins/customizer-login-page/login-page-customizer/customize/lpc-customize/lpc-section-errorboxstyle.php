<?php
/** Start : Section Error Box Styling */
$wp_customize->add_section(
	'lpc-section-error-style',
	array(
		'title' => __( 'Error Box Styling', 'customizer-login-page' ),
		'panel' => 'lpc-main-panel',
	)
);
	// Error Box styling heading control.
	$wp_customize->add_setting(
		'lpc-error-style-heading',
		array(
			'default'           => '',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);
	$wp_customize->add_control(
		new Lpc_Simple_Notice_Custom_control(
			$wp_customize,
			'lpc-error-style-heading-control',
			array(
				'label'    => __( 'Error Box Styles {*Applies to all Errors}', 'customizer-login-page' ),
				'settings' => 'lpc-error-style-heading',
				'section'  => 'lpc-section-error-style',
			)
		)
	);
	// error Box Styling Width.
	$wp_customize->add_setting(
		'lpc_opts[lpc-error-style-width]',
		array(
			'type'              => 'option',
			'default'           => '320',
			'transport'         => 'postMessage',
			'sanitize_callback' => 'lpc_sanitize_integer',
		)
	);
	$wp_customize->add_control(
		new lpc_Slider_Custom_Control(
			$wp_customize,
			'lpc_opts[lpc-error-style-width]-control',
			array(
				'label'       => esc_html__( 'Error Box Width (px)', 'customizer-login-page' ),
				'settings'    => 'lpc_opts[lpc-error-style-width]',
				'section'     => 'lpc-section-error-style',
				'input_attrs' => array(
					'min'  => 0,
					'max'  => 800,
					'step' => 1,
				),
			)
		)
	);
	// Error Box Styling Text Color.
	$wp_customize->add_setting(
		'lpc_opts[lpc-error-style-text-color]',
		array(
			'type'              => 'option',
			'default'           => '#3c434a',
			'transport'         => 'postMessage',
			'sanitize_callback' => 'lpc_hex_rgba_sanitization',
		)
	);
	$wp_customize->add_control(
		new LPC_Alpha_Color_Control(
			$wp_customize,
			'lpc_opts[lpc-error-style-text-color]-control',
			array(
				'label'       => __( 'Error Text Color', 'customizer-login-page' ),
				'settings'    => 'lpc_opts[lpc-error-style-text-color]',
				'section'     => 'lpc-section-error-style',
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
	// Error Box Styling Text Align.
	$wp_customize->add_setting(
		'lpc_opts[lpc-error-style-text-align]',
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
			'lpc_opts[lpc-error-style-text-align]-control',
			array(
				'label'    => __( 'Error Align Text', 'customizer-login-page' ),
				'settings' => 'lpc_opts[lpc-error-style-text-align]',
				'section'  => 'lpc-section-error-style',
				'choices'  => array(
					'left'   => __( 'Left' ),
					'center' => __( 'Center' ),
					'right'  => __( 'Right' ),
				),
			)
		)
	);
	// Error Box Styling Font Size.
	$wp_customize->add_setting(
		'lpc_opts[lpc-error-style-font-size]',
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
			'lpc_opts[lpc-error-style-font-size]-control',
			array(
				'label'       => esc_html__( 'Error Font Size (px) ', 'customizer-login-page' ),
				'settings'    => 'lpc_opts[lpc-error-style-font-size]',
				'section'     => 'lpc-section-error-style',
				'input_attrs' => array(
					'min'  => 0,
					'max'  => 50,
					'step' => 1,
				),
			)
		)
	);
	// Error Box Styling background Color Picker setting.
	$wp_customize->add_setting(
		'lpc_opts[lpc-error-style-background]',
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
			'lpc_opts[lpc-error-style-background]-control',
			array(
				'label'       => __( 'Error Background Color', 'customizer-login-page' ),
				'settings'    => 'lpc_opts[lpc-error-style-background]',
				'section'     => 'lpc-section-error-style',
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
	// Error Box Styling Border Width.
	$wp_customize->add_setting(
		'lpc_opts[lpc-error-style-border-width]',
		array(
			'type'              => 'option',
			'default'           => '4',
			'transport'         => 'postMessage',
			'sanitize_callback' => 'lpc_sanitize_integer',
		)
	);
	$wp_customize->add_control(
		new lpc_Slider_Custom_Control(
			$wp_customize,
			'lpc_opts[lpc-error-style-border-width]-control',
			array(
				'label'       => esc_html__( 'Error Border Width (px)', 'customizer-login-page' ),
				'settings'    => 'lpc_opts[lpc-error-style-border-width]',
				'section'     => 'lpc-section-error-style',
				'input_attrs' => array(
					'min'  => 0,
					'max'  => 50,
					'step' => 1,
				),
			)
		)
	);
	// Error Box Styling Border Color Picker setting.
	$wp_customize->add_setting(
		'lpc_opts[lpc-error-style-border-color]',
		array(
			'type'              => 'option',
			'default'           => '#d63638', // Set a default error color.
			'transport'         => 'postMessage',
			'sanitize_callback' => 'lpc_hex_rgba_sanitization',
		)
	);
	// Alpha Color Picker control.
	$wp_customize->add_control(
		new LPC_Alpha_Color_Control(
			$wp_customize,
			'lpc_opts[lpc-error-style-border-color]-control',
			array(
				'label'       => __( 'Error Border Color', 'customizer-login-page' ),
				'settings'    => 'lpc_opts[lpc-error-style-border-color]',
				'section'     => 'lpc-section-error-style',
				'input_attrs' => array(
					'resetalpha' => false,
					'palette'    => array(
						'rgba(150,78,78,1)',
						'rgba(150,67,78,1)',
						'rgba(150,34,78,.7)',
						'rgba(150,3,78,1)',
						'rgba(230,7,110,.9)',
						'rgba(150,78,234,1)',
						'rgba(150,78,99,.5)',
						'rgba(120,120,190,.5)',
					),
				),
			)
		)
	);
	// Error Box Styling Padding Top-Bottom.
	$wp_customize->add_setting(
		'lpc_opts[lpc-error-style-padding-tb]',
		array(
			'type'              => 'option',
			'default'           => '12',
			'transport'         => 'postMessage',
			'sanitize_callback' => 'lpc_sanitize_integer',
		)
	);
	$wp_customize->add_control(
		new lpc_Slider_Custom_Control(
			$wp_customize,
			'lpc_opts[lpc-error-style-padding-tb]-control',
			array(
				'label'       => esc_html__( 'Error Padding Top+Bottom (px)', 'customizer-login-page' ),
				'settings'    => 'lpc_opts[lpc-error-style-padding-tb]',
				'section'     => 'lpc-section-error-style',
				'input_attrs' => array(
					'min'  => 0,
					'max'  => 100,
					'step' => 1,
				),
			)
		)
	);
	// Error Box Styling Padding Left-Right.
	$wp_customize->add_setting(
		'lpc_opts[lpc-error-style-padding-lr]',
		array(
			'type'              => 'option',
			'default'           => '12',
			'transport'         => 'postMessage',
			'sanitize_callback' => 'lpc_sanitize_integer',
		)
	);
	$wp_customize->add_control(
		new lpc_Slider_Custom_Control(
			$wp_customize,
			'lpc_opts[lpc-error-style-padding-lr]-control',
			array(
				'label'       => esc_html__( 'Error Padding Left+Right (px)', 'customizer-login-page' ),
				'settings'    => 'lpc_opts[lpc-error-style-padding-lr]',
				'section'     => 'lpc-section-error-style',
				'input_attrs' => array(
					'min'  => 0,
					'max'  => 100,
					'step' => 1,
				),
			)
		)
	);
	// Error Box Styling Border Radius.
	$wp_customize->add_setting(
		'lpc_opts[lpc-error-style-border-radius]',
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
			'lpc_opts[lpc-error-style-border-radius]-control',
			array(
				'label'       => esc_html__( 'Error Border Radius (px)', 'customizer-login-page' ),
				'settings'    => 'lpc_opts[lpc-error-style-border-radius]',
				'section'     => 'lpc-section-error-style',
				'input_attrs' => array(
					'min'  => 0,
					'max'  => 300,
					'step' => 1,
				),
			)
		)
	);

	// Error Box Styling Margin Top.
	$wp_customize->add_setting(
		'lpc_opts[lpc-error-style-margin-top]',
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
			'lpc_opts[lpc-error-style-margin-top]-control',
			array(
				'label'       => esc_html__( 'Error Margin Top (px)', 'customizer-login-page' ),
				'settings'    => 'lpc_opts[lpc-error-style-margin-top]',
				'section'     => 'lpc-section-error-style',
				'input_attrs' => array(
					'min'  => 0,
					'max'  => 100,
					'step' => 1,
				),
			)
		)
	);
	// Error Box Styling Margin Bottom.
	$wp_customize->add_setting(
		'lpc_opts[lpc-error-style-margin-bottom]',
		array(
			'type'              => 'option',
			'default'           => '20',
			'transport'         => 'postMessage',
			'sanitize_callback' => 'lpc_sanitize_integer',
		)
	);
	$wp_customize->add_control(
		new lpc_Slider_Custom_Control(
			$wp_customize,
			'lpc_opts[lpc-error-style-margin-bottom]-control',
			array(
				'label'       => esc_html__( 'Error Margin Bottom (px)', 'customizer-login-page' ),
				'settings'    => 'lpc_opts[lpc-error-style-margin-bottom]',
				'section'     => 'lpc-section-error-style',
				'input_attrs' => array(
					'min'  => 0,
					'max'  => 100,
					'step' => 1,
				),
			)
		)
	);
	// Error Box Styling Box Shadow.
	$wp_customize->add_setting(
		'lpc_opts[lpc-error-style-box-shadow]',
		array(
			'type'              => 'option',
			'default'           => '',
			'transport'         => 'postMessage',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);

	$wp_customize->add_control(
		'lpc_opts[lpc-error-style-box-shadow]-control',
		array(
			'label'       => __( 'Error Box Shadow', 'customizer-login-page' ),
			'description' => __( 'Make sure your box-shadow property is in proper CSS format like: <b>0 1px 1px 0 rgba(0,0,0,.1)</b> or Leave it Blank for no shadow. Also you can easily Generate Here:', 'customizer-login-page' ) . ' <a href="https://cssgenerator.org/box-shadow-css-generator.html" target="_blank">' . __( 'Shadow Generator', 'customizer-login-page' ) . '</a>',
			'settings'    => 'lpc_opts[lpc-error-style-box-shadow]',
			'section'     => 'lpc-section-error-style',
			'type'        => 'text',
			'input_attrs' => array( // Optional.
				'placeholder' => '0 1px 1px 0 rgba(0,0,0,.1)',
			),
		)
	);

	// Error Box Position X.
	$wp_customize->add_setting(
		'lpc_opts[lpc-error-style-box-position-x]',
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
			'lpc_opts[lpc-error-style-box-position-x]-control',
			array(
				'label'       => esc_html__( 'Error Box Position X-axis (%)', 'customizer-login-page' ),
				'settings'    => 'lpc_opts[lpc-error-style-box-position-x]',
				'section'     => 'lpc-section-error-style',
				'input_attrs' => array(
					'min'  => -100, // Required. Minimum value for the slider.
					'max'  => 100, // Required. Maximum value for the slider.
					'step' => 1, // Required. The size of each interval or step the slider takes between the minimum and maximum values.
				),
			)
		)
	);
	/** End : Section Error Box Styling */
