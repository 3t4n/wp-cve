<?php
/** Start : Input Fields */
$wp_customize->add_section(
	'lpc-section-form-inputs',
	array(
		'title' => __( 'Form Inputs Settings', 'customizer-login-page' ),
		'panel' => 'lpc-main-panel',
	)
);
	// Input heading control.
	$wp_customize->add_setting(
		'lpc-form-inputs-heading',
		array(
			'default'           => '',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);
	$wp_customize->add_control(
		new Lpc_Simple_Notice_Custom_control(
			$wp_customize,
			'lpc-form-inputs-heading-control',
			array(
				'label'    => __( 'Inputs Settings', 'customizer-login-page' ),
				'settings' => 'lpc-form-inputs-heading',
				'section'  => 'lpc-section-form-inputs',
			)
		)
	);
	// Inputs Font Selector.
	$wp_customize->add_setting(
		'lpc_opts[lpc-form-inputs-font]',
		array(
			'type'              => 'option',
			'default'           => '-apple-system,BlinkMacSystemFont,Segoe UI,Roboto,Oxygen-Sans,Ubuntu,Cantarell,Helvetica Neue,sans-serif',
			'transport'         => 'postMessage',
			'sanitize_callback' => 'sanitize_text_field',

		)
	);

	$wp_customize->add_control(
		new Font_Selector(
			$wp_customize,
			'lpc_opts[lpc-form-inputs-font]-control',
			array(
				'label'    => esc_html__( 'Inputs Font family', 'customizer-login-page' ),
				'settings' => 'lpc_opts[lpc-form-inputs-font]',
				'section'  => 'lpc-section-form-inputs',
				'type'     => 'select',
			)
		)
	);
	// Inputs Label Alpha Color Picker setting.
	$wp_customize->add_setting(
		'lpc_opts[lpc-form-inputs-labels-color]',
		array(
			'type'              => 'option',
			'default'           => '#3c434a',
			'transport'         => 'postMessage',
			'sanitize_callback' => 'lpc_hex_rgba_sanitization',
		)
	);

	// Alpha Color Picker control.
	$wp_customize->add_control(
		new LPC_Alpha_Color_Control(
			$wp_customize,
			'lpc_opts[lpc-form-inputs-labels-color]-control',
			array(
				'label'       => __( 'Labels Color', 'customizer-login-page' ),
				'section'     => 'lpc-section-form-inputs',
				'settings'    => 'lpc_opts[lpc-form-inputs-labels-color]',
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
	// Inputs Font Size.
	$wp_customize->add_setting(
		'lpc_opts[lpc-form-inputs-labels-size]',
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
			'lpc_opts[lpc-form-inputs-labels-size]-control',
			array(
				'label'       => esc_html__( 'Labels font size (px)', 'customizer-login-page' ),
				'settings'    => 'lpc_opts[lpc-form-inputs-labels-size]',
				'section'     => 'lpc-section-form-inputs',
				'input_attrs' => array(
					'min'  => 0, // Required. Minimum value for the slider.
					'max'  => 100, // Required. Maximum value for the slider.
					'step' => 1, // Required. The size of each interval or step the slider takes between the minimum and maximum values.
				),
			)
		)
	);
	// Inputs Text Align.
	$wp_customize->add_setting(
		'lpc_opts[lpc-form-inputs-labels-align]',
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
			'lpc_opts[lpc-form-inputs-labels-align]-control',
			array(
				'label'    => __( 'Align Labels', 'customizer-login-page' ),
				'settings' => 'lpc_opts[lpc-form-inputs-labels-align]',
				'section'  => 'lpc-section-form-inputs',
				'choices'  => array( // Optional.
					'left'   => __( 'Left' ),
					'center' => __( 'Center' ),
					'right'  => __( 'Right' ),
				),
			)
		)
	);
	// Input heading control.
	$wp_customize->add_setting(
		'lpc-form-inputs-remember-heading',
		array(
			'default'           => '',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);
	$wp_customize->add_control(
		new Lpc_Simple_Notice_Custom_control(
			$wp_customize,
			'lpc-form-inputs-remember-heading-control',
			array(
				'label'    => __( 'Remember Me Settings', 'customizer-login-page' ),
				'settings' => 'lpc-form-inputs-remember-heading',
				'section'  => 'lpc-section-form-inputs',
			)
		)
	);
	// Inputs Remember Align.
	$wp_customize->add_setting(
		'lpc_opts[lpc-form-inputs-remember-align]',
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
			'lpc_opts[lpc-form-inputs-remember-align]-control',
			array(
				'label'    => __( 'Align Remember Me', 'customizer-login-page' ),
				'settings' => 'lpc_opts[lpc-form-inputs-remember-align]',
				'section'  => 'lpc-section-form-inputs',
				'choices'  => array(
					'left'   => __( 'Left' ), // Required.
					'center' => __( 'Center' ), // Required.
					'right'  => __( 'Right' ), // Required.
				),
			)
		)
	);
	// Input heading control.
	$wp_customize->add_setting(
		'lpc-form-inputs-tb-heading',
		array(
			'default'           => '',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);
	$wp_customize->add_control(
		new Lpc_Simple_Notice_Custom_control(
			$wp_customize,
			'lpc-form-inputs-tb-heading-control',
			array(
				'label'    => __( 'Text Boxes Settings', 'customizer-login-page' ),
				'settings' => 'lpc-form-inputs-tb-heading',
				'section'  => 'lpc-section-form-inputs',
			)
		)
	);
	// Inputs Text Box Alpha Color Picker setting.
	$wp_customize->add_setting(
		'lpc_opts[lpc-form-inputs-tb-color]',
		array(
			'type'              => 'option',
			'default'           => '#ffffff',
			'transport'         => 'postMessage',
			'sanitize_callback' => 'lpc_hex_rgba_sanitization',
		)
	);

	// Alpha Color Picker control.
	$wp_customize->add_control(
		new LPC_Alpha_Color_Control(
			$wp_customize,
			'lpc_opts[lpc-form-inputs-tb-color]-control',
			array(
				'label'       => __( 'Text Box Background Color', 'customizer-login-page' ),
				'section'     => 'lpc-section-form-inputs',
				'settings'    => 'lpc_opts[lpc-form-inputs-tb-color]',
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
	// Inputs Text Alpha Color Picker setting.
	$wp_customize->add_setting(
		'lpc_opts[lpc-form-inputs-text-color]',
		array(
			'type'              => 'option',
			'default'           => '#2c3338',
			'transport'         => 'postMessage',
			'sanitize_callback' => 'lpc_hex_rgba_sanitization',
		)
	);

	// Alpha Color Picker control.
	$wp_customize->add_control(
		new LPC_Alpha_Color_Control(
			$wp_customize,
			'lpc_opts[lpc-form-inputs-text-color]-control',
			array(
				'label'       => __( 'Text Box Text Color', 'customizer-login-page' ),
				'section'     => 'lpc-section-form-inputs',
				'settings'    => 'lpc_opts[lpc-form-inputs-text-color]',
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
	// Inputs Text Box Width.
	$wp_customize->add_setting(
		'lpc_opts[lpc-form-inputs-text-width]',
		array(
			'type'              => 'option',
			'default'           => '100',
			'transport'         => 'postMessage',
			'sanitize_callback' => 'lpc_sanitize_integer',
		)
	);
	$wp_customize->add_control(
		new lpc_Slider_Custom_Control(
			$wp_customize,
			'lpc_opts[lpc-form-inputs-text-width]-control',
			array(
				'label'       => esc_html__( 'Input Text Box Width (%) ', 'customizer-login-page' ),
				'description' => esc_html__( 'Width percentage is respective to Outer Form width', 'customizer-login-page' ),
				'settings'    => 'lpc_opts[lpc-form-inputs-text-width]',
				'section'     => 'lpc-section-form-inputs',
				'input_attrs' => array(
					'min'  => 0, // Required. Minimum value for the slider.
					'max'  => 100, // Required. Maximum value for the slider.
					'step' => 1, // Required. The size of each interval or step the slider takes between the minimum and maximum values.
				),
			)
		)
	);
	// Inputs Box Height.
	$wp_customize->add_setting(
		'lpc_opts[lpc-form-inputs-text-height]',
		array(
			'type'              => 'option',
			'default'           => '40',
			'transport'         => 'postMessage',
			'sanitize_callback' => 'lpc_sanitize_integer',
		)
	);
	$wp_customize->add_control(
		new lpc_Slider_Custom_Control(
			$wp_customize,
			'lpc_opts[lpc-form-inputs-text-height]-control',
			array(
				'label'       => esc_html__( 'Input Text Box height (px) ', 'customizer-login-page' ),
				'settings'    => 'lpc_opts[lpc-form-inputs-text-height]',
				'section'     => 'lpc-section-form-inputs',
				'input_attrs' => array(
					'min'  => 40, // Required. Minimum value for the slider.
					'max'  => 100, // Required. Maximum value for the slider.
					'step' => 1, // Required. The size of each interval or step the slider takes between the minimum and maximum values.
				),
			)
		)
	);
	// Inputs Box Top Margin.
	$wp_customize->add_setting(
		'lpc_opts[lpc-form-inputs-text-margin-top]',
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
			'lpc_opts[lpc-form-inputs-text-margin-top]-control',
			array(
				'label'       => esc_html__( 'Text Box Margin Top (px) ', 'customizer-login-page' ),
				'settings'    => 'lpc_opts[lpc-form-inputs-text-margin-top]',
				'section'     => 'lpc-section-form-inputs',
				'input_attrs' => array(
					'min'  => 0, // Required. Minimum value for the slider.
					'max'  => 100, // Required. Maximum value for the slider.
					'step' => 1, // Required. The size of each interval or step the slider takes between the minimum and maximum values.
				),
			)
		)
	);
	// Inputs Box Bottom Margin.
	$wp_customize->add_setting(
		'lpc_opts[lpc-form-inputs-text-margin-bottom]',
		array(
			'type'              => 'option',
			'default'           => '16',
			'transport'         => 'postMessage',
			'sanitize_callback' => 'lpc_sanitize_integer',
		)
	);
	$wp_customize->add_control(
		new lpc_Slider_Custom_Control(
			$wp_customize,
			'lpc_opts[lpc-form-inputs-text-margin-bottom]-control',
			array(
				'label'       => esc_html__( 'Text Box Margin Bottom (px) ', 'customizer-login-page' ),
				'settings'    => 'lpc_opts[lpc-form-inputs-text-margin-bottom]',
				'section'     => 'lpc-section-form-inputs',
				'input_attrs' => array(
					'min'  => 0, // Required. Minimum value for the slider.
					'max'  => 100, // Required. Maximum value for the slider.
					'step' => 1, // Required. The size of each interval or step the slider takes between the minimum and maximum values.
				),
			)
		)
	);
	// Inputs Text Font Size.
	$wp_customize->add_setting(
		'lpc_opts[lpc-form-inputs-text-font-size]',
		array(
			'type'              => 'option',
			'default'           => '18',
			'transport'         => 'postMessage',
			'sanitize_callback' => 'lpc_sanitize_integer',
		)
	);
	$wp_customize->add_control(
		new lpc_Slider_Custom_Control(
			$wp_customize,
			'lpc_opts[lpc-form-inputs-text-font-size]-control',
			array(
				'label'       => esc_html__( 'Input Text Font Size (px) ', 'customizer-login-page' ),
				'settings'    => 'lpc_opts[lpc-form-inputs-text-font-size]',
				'section'     => 'lpc-section-form-inputs',
				'input_attrs' => array(
					'min'  => 0, // Required. Minimum value for the slider.
					'max'  => 100, // Required. Maximum value for the slider.
					'step' => 1, // Required. The size of each interval or step the slider takes between the minimum and maximum values.
				),
			)
		)
	);
	// Inputs Text Box Padding top+Bottom.
	$wp_customize->add_setting(
		'lpc_opts[lpc-form-inputs-text-padding-tb]',
		array(
			'type'              => 'option',
			'default'           => '3',
			'transport'         => 'postMessage',
			'sanitize_callback' => 'lpc_sanitize_integer',
		)
	);
	$wp_customize->add_control(
		new lpc_Slider_Custom_Control(
			$wp_customize,
			'lpc_opts[lpc-form-inputs-text-padding-tb]-control',
			array(
				'label'       => esc_html__( 'Text Box Padding Top+Bottom (px) ', 'customizer-login-page' ),
				'settings'    => 'lpc_opts[lpc-form-inputs-text-padding-tb]',
				'section'     => 'lpc-section-form-inputs',
				'input_attrs' => array(
					'min'  => 0, // Required. Minimum value for the slider.
					'max'  => 50, // Required. Maximum value for the slider.
					'step' => 1, // Required. The size of each interval or step the slider takes between the minimum and maximum values.
				),
			)
		)
	);
	// Inputs Text Box Padding Left+Right.
	$wp_customize->add_setting(
		'lpc_opts[lpc-form-inputs-text-padding-lr]',
		array(
			'type'              => 'option',
			'default'           => '5',
			'transport'         => 'postMessage',
			'sanitize_callback' => 'lpc_sanitize_integer',
		)
	);
	$wp_customize->add_control(
		new lpc_Slider_Custom_Control(
			$wp_customize,
			'lpc_opts[lpc-form-inputs-text-padding-lr]-control',
			array(
				'label'       => esc_html__( 'Text Box Padding Left+Right (px) ', 'customizer-login-page' ),
				'settings'    => 'lpc_opts[lpc-form-inputs-text-padding-lr]',
				'section'     => 'lpc-section-form-inputs',
				'input_attrs' => array(
					'min'  => 0, // Required. Minimum value for the slider.
					'max'  => 50, // Required. Maximum value for the slider.
					'step' => 1, // Required. The size of each interval or step the slider takes between the minimum and maximum values.
				),
			)
		)
	);
	// Input heading control.
	$wp_customize->add_setting(
		'lpc-form-inputs-eye-heading',
		array(
			'default'           => '',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);
	$wp_customize->add_control(
		new Lpc_Simple_Notice_Custom_control(
			$wp_customize,
			'lpc-form-inputs-eye-heading-control',
			array(
				'label'    => __( 'Password Eye Adjust', 'customizer-login-page' ),
				'settings' => 'lpc-form-inputs-eye-heading',
				'section'  => 'lpc-section-form-inputs',
			)
		)
	);
	// Inputs Password Eye Position Top (Px).
	$wp_customize->add_setting(
		'lpc_opts[lpc-form-inputs-eye-position-top]',
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
			'lpc_opts[lpc-form-inputs-eye-position-top]-control',
			array(
				'label'       => esc_html__( 'Password Eye Position Top (px)', 'customizer-login-page' ),
				'settings'    => 'lpc_opts[lpc-form-inputs-eye-position-top]',
				'section'     => 'lpc-section-form-inputs',
				'input_attrs' => array(
					'min'  => 0, // Required. Minimum value for the slider.
					'max'  => 100, // Required. Maximum value for the slider.
					'step' => 1, // Required. The size of each interval or step the slider takes between the minimum and maximum values.
				),
			)
		)
	);
	// Inputs Password Eye Position Right (px).
	$wp_customize->add_setting(
		'lpc_opts[lpc-form-inputs-eye-position-right]',
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
			'lpc_opts[lpc-form-inputs-eye-position-right]-control',
			array(
				'label'       => esc_html__( 'Password Eye Position Right (px)', 'customizer-login-page' ),
				'settings'    => 'lpc_opts[lpc-form-inputs-eye-position-right]',
				'section'     => 'lpc-section-form-inputs',
				'input_attrs' => array(
					'min'  => 0, // Required. Minimum value for the slider.
					'max'  => 100, // Required. Maximum value for the slider.
					'step' => 1, // Required. The size of each interval or step the slider takes between the minimum and maximum values.
				),
			)
		)
	);
	// Input Labels heading control.
	$wp_customize->add_setting(
		'lpc-form-inputs-labels-heading',
		array(
			'default'           => '',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);
	$wp_customize->add_control(
		new Lpc_Simple_Notice_Custom_control(
			$wp_customize,
			'lpc-form-inputs-labels-heading-control',
			array(
				'label'    => __( 'Change Default Labels', 'customizer-login-page' ),
				'settings' => 'lpc-form-inputs-labels-heading',
				'section'  => 'lpc-section-form-inputs',
			)
		)
	);
	// Usename Box Label text.
	$wp_customize->add_setting(
		'lpc_opts[lpc-form-Username-label]',
		array(
			'type'              => 'option',
			'default'           => 'Username or Email Address',
			'transport'         => 'postMessage',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);
	$wp_customize->add_control(
		'lpc_opts[lpc-form-Username-label]-control',
		array(
			'label'       => __( 'Username Box Label', 'customizer-login-page' ),
			'description' => __( 'Default: Username or Email Address', 'customizer-login-page' ),
			'section'     => 'lpc-section-form-inputs',
			'settings'    => 'lpc_opts[lpc-form-Username-label]',
			'type'        => 'text',
		)
	);
	// Password Box Label Text Setting.
	$wp_customize->add_setting(
		'lpc_opts[lpc-form-Password-label]',
		array(
			'type'              => 'option',
			'default'           => 'Password',
			'transport'         => 'postMessage',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);

	$wp_customize->add_control(
		'lpc_opts[lpc-form-Password-label]-control',
		array(
			'label'       => __( 'Password Box Label', 'customizer-login-page' ),
			'description' => __( 'Default: Password', 'customizer-login-page' ),
			'section'     => 'lpc-section-form-inputs',
			'settings'    => 'lpc_opts[lpc-form-Password-label]',
			'type'        => 'text',
		)
	);

	// Remember Me Text Setting.
	$wp_customize->add_setting(
		'lpc_opts[lpc-form-rememberme-text]',
		array(
			'type'              => 'option',
			'default'           => 'Remember Me',
			'transport'         => 'postMessage',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);

	$wp_customize->add_control(
		'lpc_opts[lpc-form-rememberme-text]-control',
		array(
			'label'       => __( 'Remember Me Text', 'customizer-login-page' ),
			'description' => __( 'Default: Remember Me', 'customizer-login-page' ),
			'section'     => 'lpc-section-form-inputs',
			'settings'    => 'lpc_opts[lpc-form-rememberme-text]',
			'type'        => 'text',
		)
	);
	/** End : Input Fields */
