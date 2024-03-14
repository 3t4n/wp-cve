<?php
/** Start : Section Message Box Styling */
$wp_customize->add_section(
	'lpc-section-msg-style',
	array(
		'title' => __( 'Msg Box Styling', 'customizer-login-page' ),
		'panel' => 'lpc-main-panel',
	)
);
	// Msg Box styling heading control.
	$wp_customize->add_setting(
		'lpc-msg-style-heading',
		array(
			'default'           => '',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);
	$wp_customize->add_control(
		new Lpc_Simple_Notice_Custom_control(
			$wp_customize,
			'lpc-msg-style-heading-control',
			array(
				'label'    => __( 'Message Box Styles {*Applies to all Messages}', 'customizer-login-page' ),
				'settings' => 'lpc-msg-style-heading',
				'section'  => 'lpc-section-msg-style',
			)
		)
	);
	// Msg Box Styling Width.
	$wp_customize->add_setting(
		'lpc_opts[lpc-msg-style-width]',
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
			'lpc_opts[lpc-msg-style-width]-control',
			array(
				'label'       => esc_html__( 'Message Box Width (px)', 'customizer-login-page' ),
				'settings'    => 'lpc_opts[lpc-msg-style-width]',
				'section'     => 'lpc-section-msg-style',
				'input_attrs' => array(
					'min'  => 0,
					'max'  => 800,
					'step' => 1,
				),
			)
		)
	);
	// Msg Box Styling Text Color.
	$wp_customize->add_setting(
		'lpc_opts[lpc-msg-style-text-color]',
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
			'lpc_opts[lpc-msg-style-text-color]-control',
			array(
				'label'       => __( 'Message Text Color', 'customizer-login-page' ),
				'settings'    => 'lpc_opts[lpc-msg-style-text-color]',
				'section'     => 'lpc-section-msg-style',
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
	// Msg Box Styling Text Align.
	$wp_customize->add_setting(
		'lpc_opts[lpc-msg-style-text-align]',
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
			'lpc_opts[lpc-msg-style-text-align]-control',
			array(
				'label'    => __( 'Message Align Text', 'customizer-login-page' ),
				'settings' => 'lpc_opts[lpc-msg-style-text-align]',
				'section'  => 'lpc-section-msg-style',
				'choices'  => array( // Optional.
					'left'   => __( 'Left' ),
					'center' => __( 'Center' ),
					'right'  => __( 'Right' ),
				),
			)
		)
	);
	// Msg Box Styling Font Size.
	$wp_customize->add_setting(
		'lpc_opts[lpc-msg-style-font-size]',
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
			'lpc_opts[lpc-msg-style-font-size]-control',
			array(
				'label'       => esc_html__( 'Message Font Size (px) ', 'customizer-login-page' ),
				'settings'    => 'lpc_opts[lpc-msg-style-font-size]',
				'section'     => 'lpc-section-msg-style',
				'input_attrs' => array(
					'min'  => 0, // Required. Minimum value for the slider.
					'max'  => 50, // Required. Maximum value for the slider.
					'step' => 1, // Required. The size of each interval or step the slider takes between the minimum and maximum values.
				),
			)
		)
	);
	// Msg Box Styling background Color Picker setting.
	$wp_customize->add_setting(
		'lpc_opts[lpc-msg-style-background]',
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
			'lpc_opts[lpc-msg-style-background]-control',
			array(
				'label'       => __( 'Message Background Color', 'customizer-login-page' ),
				'settings'    => 'lpc_opts[lpc-msg-style-background]',
				'section'     => 'lpc-section-msg-style',
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
	// Msg Box Styling Border Width.
	$wp_customize->add_setting(
		'lpc_opts[lpc-msg-style-border-width]',
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
			'lpc_opts[lpc-msg-style-border-width]-control',
			array(
				'label'       => esc_html__( 'Message Border Width (px)', 'customizer-login-page' ),
				'settings'    => 'lpc_opts[lpc-msg-style-border-width]',
				'section'     => 'lpc-section-msg-style',
				'input_attrs' => array(
					'min'  => 0, // Minimum value for the slider.
					'max'  => 20, // Maximum value for the slider.
					'step' => 1, // The size of each interval or step the slider takes between the minimum and maximum values.
				),
			)
		)
	);
	// Msg Box Styling Border Color Picker setting.
	$wp_customize->add_setting(
		'lpc_opts[lpc-msg-style-border-color]',
		array(
			'type'              => 'option',
			'default'           => '#72aee6',
			'transport'         => 'postMessage',
			'sanitize_callback' => 'lpc_hex_rgba_sanitization',
		)
	);
	// Alpha Color Picker control.
	$wp_customize->add_control(
		new LPC_Alpha_Color_Control(
			$wp_customize,
			'lpc_opts[lpc-msg-style-border-color]-control',
			array(
				'label'       => __( 'Message Border Color', 'customizer-login-page' ),
				'settings'    => 'lpc_opts[lpc-msg-style-border-color]',
				'section'     => 'lpc-section-msg-style',
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
	// Msg Box Styling Padding Top-Bottom.
	$wp_customize->add_setting(
		'lpc_opts[lpc-msg-style-padding-tb]',
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
			'lpc_opts[lpc-msg-style-padding-tb]-control',
			array(
				'label'       => esc_html__( 'Message Padding Top+Bottom (px)', 'customizer-login-page' ),
				'settings'    => 'lpc_opts[lpc-msg-style-padding-tb]',
				'section'     => 'lpc-section-msg-style',
				'input_attrs' => array(
					'min'  => 0,
					'max'  => 100,
					'step' => 1,
				),
			)
		)
	);
	// Msg Box Styling Padding Left-Right.
	$wp_customize->add_setting(
		'lpc_opts[lpc-msg-style-padding-lr]',
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
			'lpc_opts[lpc-msg-style-padding-lr]-control',
			array(
				'label'       => esc_html__( 'Message Padding Left+Right (px)', 'customizer-login-page' ),
				'settings'    => 'lpc_opts[lpc-msg-style-padding-lr]',
				'section'     => 'lpc-section-msg-style',
				'input_attrs' => array(
					'min'  => 0,
					'max'  => 100,
					'step' => 1,
				),
			)
		)
	);
	// Msg Box Styling Border Radius.
	$wp_customize->add_setting(
		'lpc_opts[lpc-msg-style-border-radius]',
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
			'lpc_opts[lpc-msg-style-border-radius]-control',
			array(
				'label'       => esc_html__( 'Message Border Radius (px)', 'customizer-login-page' ),
				'settings'    => 'lpc_opts[lpc-msg-style-border-radius]',
				'section'     => 'lpc-section-msg-style',
				'input_attrs' => array(
					'min'  => 0,
					'max'  => 300,
					'step' => 1,
				),
			)
		)
	);
	// Msg Box Styling Margin Top.
	$wp_customize->add_setting(
		'lpc_opts[lpc-msg-style-margin-top]',
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
			'lpc_opts[lpc-msg-style-margin-top]-control',
			array(
				'label'       => esc_html__( 'Message Margin Top (px)', 'customizer-login-page' ),
				'settings'    => 'lpc_opts[lpc-msg-style-margin-top]',
				'section'     => 'lpc-section-msg-style',
				'input_attrs' => array(
					'min'  => 0,
					'max'  => 100,
					'step' => 1,
				),
			)
		)
	);
	// Msg Box Styling Margin Bottom.
	$wp_customize->add_setting(
		'lpc_opts[lpc-msg-style-margin-bottom]',
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
			'lpc_opts[lpc-msg-style-margin-bottom]-control',
			array(
				'label'       => esc_html__( 'Message Margin Bottom (px)', 'customizer-login-page' ),
				'settings'    => 'lpc_opts[lpc-msg-style-margin-bottom]',
				'section'     => 'lpc-section-msg-style',
				'input_attrs' => array(
					'min'  => 0,
					'max'  => 100,
					'step' => 1,
				),
			)
		)
	);
	// Msg Box Styling Box Shadow.
	$wp_customize->add_setting(
		'lpc_opts[lpc-msg-style-box-shadow]',
		array(
			'type'              => 'option',
			'default'           => '',
			'transport'         => 'postMessage',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);

	$wp_customize->add_control(
		'lpc_opts[lpc-msg-style-box-shadow]-control',
		array(
			'label'       => __( 'Message Box Shadow', 'customizer-login-page' ),
			'description' => __( 'Make sure your box-shadow property is in proper CSS format like: <b>0 1px 1px 0 rgba(0,0,0,.1)</b> or Leave it Blank for no shadow. Also you can easily Generate Here:', 'customizer-login-page' ) . ' <a href="https://cssgenerator.org/box-shadow-css-generator.html" target="_blank">' . __( 'Shadow Generator', 'customizer-login-page' ) . '</a>',
			'settings'    => 'lpc_opts[lpc-msg-style-box-shadow]',
			'section'     => 'lpc-section-msg-style',
			'type'        => 'text',
			'input_attrs' => array( // Optional.
				'placeholder' => '0 1px 1px 0 rgba(0,0,0,.1)',
			),
		)
	);

	// Masg Box Position X.
	$wp_customize->add_setting(
		'lpc_opts[lpc-msg-style-box-position-x]',
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
			'lpc_opts[lpc-msg-style-box-position-x]-control',
			array(
				'label'       => esc_html__( 'Msg Box Position X-axis (%)', 'customizer-login-page' ),
				'settings'    => 'lpc_opts[lpc-msg-style-box-position-x]',
				'section'     => 'lpc-section-msg-style',
				'input_attrs' => array(
					'min'  => -100, // Required. Minimum value for the slider.
					'max'  => 100, // Required. Maximum value for the slider.
					'step' => 1, // Required. The size of each interval or step the slider takes between the minimum and maximum values.
				),
			)
		)
	);
	/** End : Section Message Box Styling */
