<?php
/** Start Section : Login Outer Form  */
$wp_customize->add_section(
	'lpc-section-form',
	array(
		'title' => __( 'Outer Form Settings', 'customizer-login-page' ),
		'panel' => 'lpc-main-panel',
	)
);
	// Form Outer heading control.
	$wp_customize->add_setting(
		'lpc-form-outer-heading',
		array(
			'default'           => '',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);
	$wp_customize->add_control(
		new Lpc_Simple_Notice_Custom_control(
			$wp_customize,
			'lpc-form-outer-heading-control',
			array(
				'label'    => __( 'Outer From Settings', 'customizer-login-page' ),
				'settings' => 'lpc-form-outer-heading',
				'section'  => 'lpc-section-form',
			)
		)
	);
	// Form Outer heading control.
	$wp_customize->add_setting(
		'lpc-form-outer-note',
		array(
			'default'           => '',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);
	$wp_customize->add_control(
		new Lpc_Simple_Notice_Custom_control(
			$wp_customize,
			'lpc-form-outer-note-control',
			array(
				'label'    => __( 'Note: For better height alignment with an error msg, Please press login button once with an invalid username.', 'customizer-login-page' ),
				'settings' => 'lpc-form-outer-note',
				'section'  => 'lpc-section-form',
			)
		)
	);
	// Form Width.
	$wp_customize->add_setting(
		'lpc_opts[lpc-form-width]',
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
			'lpc_opts[lpc-form-width]-control',
			array(
				'label'       => esc_html__( 'Width (px)', 'customizer-login-page' ),
				'settings'    => 'lpc_opts[lpc-form-width]',
				'section'     => 'lpc-section-form',
				'input_attrs' => array(
					'min'  => 240, // Required. Minimum value for the slider.
					'max'  => 2000, // Required. Maximum value for the slider.
					'step' => 1, // Required. The size of each interval or step the slider takes between the minimum and maximum values.
				),
			)
		)
	);
	// Form height.
	$wp_customize->add_setting(
		'lpc_opts[lpc-form-height]',
		array(
			'type'              => 'option',
			'default'           => '600',
			'transport'         => 'postMessage',
			'sanitize_callback' => 'lpc_sanitize_integer',
		)
	);
	$wp_customize->add_control(
		new lpc_Slider_Custom_Control(
			$wp_customize,
			'lpc_opts[lpc-form-height]-control',
			array(
				'label'       => esc_html__( 'Height (px)', 'customizer-login-page' ),
				'settings'    => 'lpc_opts[lpc-form-height]',
				'section'     => 'lpc-section-form',
				'input_attrs' => array(
					'min'  => 500, // Required. Minimum value for the slider.
					'max'  => 1000, // Required. Maximum value for the slider.
					'step' => 1, // Required. The size of each interval or step the slider takes between the minimum and maximum values.
				),
			)
		)
	);
	// Padding Top+Bottom (px).
	$wp_customize->add_setting(
		'lpc_opts[lpc-form-padding-tb]',
		array(
			'type'              => 'option',
			'default'           => '24',
			'transport'         => 'postMessage',
			'sanitize_callback' => 'lpc_sanitize_integer',
		)
	);
	$wp_customize->add_control(
		new lpc_Slider_Custom_Control(
			$wp_customize,
			'lpc_opts[lpc-form-padding-tb]-control',
			array(
				'label'       => esc_html__( 'Padding Top+Bottom (px)', 'customizer-login-page' ),
				'settings'    => 'lpc_opts[lpc-form-padding-tb]',
				'section'     => 'lpc-section-form',
				'input_attrs' => array(
					'min'  => 0, // Required. Minimum value for the slider.
					'max'  => 300, // Required. Maximum value for the slider.
					'step' => 1, // Required. The size of each interval or step the slider takes between the minimum and maximum values.
				),
			)
		)
	);
	// Padding Left+Right (px).
	$wp_customize->add_setting(
		'lpc_opts[lpc-form-padding-lr]',
		array(
			'type'              => 'option',
			'default'           => '34',
			'transport'         => 'postMessage',
			'sanitize_callback' => 'lpc_sanitize_integer',
		)
	);
	$wp_customize->add_control(
		new lpc_Slider_Custom_Control(
			$wp_customize,
			'lpc_opts[lpc-form-padding-lr]-control',
			array(
				'label'       => esc_html__( 'Padding Left+Right (px)', 'customizer-login-page' ),
				'settings'    => 'lpc_opts[lpc-form-padding-lr]',
				'section'     => 'lpc-section-form',
				'input_attrs' => array(
					'min'  => 0, // Required. Minimum value for the slider.
					'max'  => 300, // Required. Maximum value for the slider.
					'step' => 1, // Required. The size of each interval or step the slider takes between the minimum and maximum values.
				),
			)
		)
	);
	// Position Top X axis (%).
	$wp_customize->add_setting(
		'lpc_opts[lpc-form-position-top]',
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
			'lpc_opts[lpc-form-position-top]-control',
			array(
				'label'       => esc_html__( 'Position Y Axis (%)', 'customizer-login-page' ),
				'settings'    => 'lpc_opts[lpc-form-position-top]',
				'section'     => 'lpc-section-form',
				'input_attrs' => array(
					'min'  => -100, // Required. Minimum value for the slider.
					'max'  => 100, // Required. Maximum value for the slider.
					'step' => 1, // Required. The size of each interval or step the slider takes between the minimum and maximum values.
				),
			)
		)
	);
	// Position Left Y Axiz (%).
	$wp_customize->add_setting(
		'lpc_opts[lpc-form-position-left]',
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
			'lpc_opts[lpc-form-position-left]-control',
			array(
				'label'       => esc_html__( 'Position X Axis (%)', 'customizer-login-page' ),
				'settings'    => 'lpc_opts[lpc-form-position-left]',
				'section'     => 'lpc-section-form',
				'input_attrs' => array(
					'min'  => -100, // Required. Minimum value for the slider.
					'max'  => 100, // Required. Maximum value for the slider.
					'step' => 0.5, // Required. The size of each interval or step the slider takes between the minimum and maximum values.
				),
			)
		)
	);
	// Border Style.
	$wp_customize->add_setting(
		'lpc_opts[lpc-form-border-style]',
		array(
			'type'              => 'option',
			'default'           => 'none',
			'transport'         => 'postMessage',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);

	$wp_customize->add_control(
		'lpc_opts[lpc-form-border-style]-control',
		array(
			'label'    => __( 'Border Style', 'customizer-login-page' ),
			'settings' => 'lpc_opts[lpc-form-border-style]',
			'section'  => 'lpc-section-form',
			'type'     => 'select',
			'choices'  => array( // Optional.
				'solid'  => __( 'Solid' ),
				'dotted' => __( 'Dotted' ),
				'dashed' => __( 'Dashed' ),
				'groove' => __( 'Groove' ),
				'ridge'  => __( 'Ridge' ),
				'inset'  => __( 'Inset' ),
				'outset' => __( 'Outset' ),
				'none'   => __( 'None' ),
			),
		)
	);
	// Form Border Width.
	$wp_customize->add_setting(
		'lpc_opts[lpc-form-border-width]',
		array(
			'type'              => 'option',
			'default'           => '2',
			'transport'         => 'postMessage',
			'sanitize_callback' => 'lpc_sanitize_integer',
		)
	);
	$wp_customize->add_control(
		new lpc_Slider_Custom_Control(
			$wp_customize,
			'lpc_opts[lpc-form-border-width]-control',
			array(
				'label'       => esc_html__( 'Border Width (px)', 'customizer-login-page' ),
				'settings'    => 'lpc_opts[lpc-form-border-width]',
				'section'     => 'lpc-section-form',
				'input_attrs' => array(
					'min'  => 0, // Required. Minimum value for the slider.
					'max'  => 15, // Required. Maximum value for the slider.
					'step' => 1, // Required. The size of each interval or step the slider takes between the minimum and maximum values.
				),
			)
		)
	);
	// Border Color.
	$wp_customize->add_setting(
		'lpc_opts[lpc-form-border-color]',
		array(
			'type'              => 'option',
			'default'           => '#000000',
			'transport'         => 'postMessage',
			'sanitize_callback' => 'sanitize_hex_color',
		)
	);

	$wp_customize->add_control(
		'lpc_opts[lpc-form-border-color]-control',
		array(
			'label'    => __( 'Border Color', 'customizer-login-page' ),
			'settings' => 'lpc_opts[lpc-form-border-color]',
			'section'  => 'lpc-section-form',
			'type'     => 'color',
		)
	);
	// Border Radius (px).
	$wp_customize->add_setting(
		'lpc_opts[lpc-form-border-radius]',
		array(
			'type'              => 'option',
			'default'           => '15',
			'transport'         => 'postMessage',
			'sanitize_callback' => 'lpc_sanitize_integer',
		)
	);
	$wp_customize->add_control(
		new lpc_Slider_Custom_Control(
			$wp_customize,
			'lpc_opts[lpc-form-border-radius]-control',
			array(
				'label'       => esc_html__( 'Border Radius (px)', 'customizer-login-page' ),
				'settings'    => 'lpc_opts[lpc-form-border-radius]',
				'section'     => 'lpc-section-form',
				'input_attrs' => array(
					'min'  => 0, // Required. Minimum value for the slider.
					'max'  => 600, // Required. Maximum value for the slider.
					'step' => 1, // Required. The size of each interval or step the slider takes between the minimum and maximum values.
				),
			)
		)
	);
	// Form Box Shadow.
	$wp_customize->add_setting(
		'lpc_opts[lpc-form-box-shadow]',
		array(
			'type'              => 'option',
			'default'           => '',
			'transport'         => 'postMessage',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);

	$wp_customize->add_control(
		'lpc_opts[lpc-form-box-shadow]-control',
		array(
			'label'       => __( 'Box Shadow', 'customizer-login-page' ),
			'description' => __( 'Make sure your box-shadow property is in proper CSS format like: <b>0px 0px 20px 0px rgba(0, 0, 0, 0.2)</b> or Leave it Blank for no shadow. Also you can easily Generate Here:', 'customizer-login-page' ) . ' <a href="https://cssgenerator.org/box-shadow-css-generator.html" target="_blank">' . __( 'Shadow Generator', 'customizer-login-page' ) . '</a>',
			'settings'    => 'lpc_opts[lpc-form-box-shadow]',
			'section'     => 'lpc-section-form',
			'type'        => 'text',
			'input_attrs' => array( // Optional.
				'placeholder' => '0px 0px 20px 0px rgba(0, 0, 0, 0.2)',
			),
		)
	);
	// Form Background heading control.
	$wp_customize->add_setting(
		'lpc-form-bg-heading',
		array(
			'default'           => '',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);
	$wp_customize->add_control(
		new Lpc_Simple_Notice_Custom_control(
			$wp_customize,
			'lpc-form-bg-heading-control',
			array(
				'label'    => __( 'Outer From Background Settings', 'customizer-login-page' ),
				'settings' => 'lpc-form-bg-heading',
				'section'  => 'lpc-section-form',
			)
		)
	);
	// Background Color Alpha Color Picker setting.
	$wp_customize->add_setting(
		'lpc_opts[lpc-form-bg-color]',
		array(
			'type'              => 'option',
			'default'           => 'rgb(240,240,241)',
			'transport'         => 'postMessage',
			'sanitize_callback' => 'lpc_hex_rgba_sanitization',
		)
	);

	// Alpha Color Picker control.
	$wp_customize->add_control(
		new LPC_Alpha_Color_Control(
			$wp_customize,
			'lpc_opts[lpc-form-bg-color]-control',
			array(
				'label'       => __( 'Form Background Color', 'customizer-login-page' ),
				'section'     => 'lpc-section-form',
				'settings'    => 'lpc_opts[lpc-form-bg-color]',
				'description' => __( 'For background Transparency reduce opacity. Set zero for full transparent', 'customizer-login-page' ),
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
	// Outer Form Image.
	$wp_customize->add_setting(
		'lpc_opts[lpc-form-bg-image]',
		array(
			'type'              => 'option',
			// 'default'           => LOGINPC_PLUGIN_URL . '/assets/images/logo-lpc-tp.png',
			'transport'         => 'postMessage',
			'sanitize_callback' => 'esc_url_raw',
		)
	);

	$wp_customize->add_control(
		new WP_Customize_Image_Control(
			$wp_customize,
			'lpc_opts[lpc-form-bg-image]-control',
			array(
				'label'         => __( 'Form Background Image', 'customizer-login-page' ),
				'description'   => esc_html__( 'Small png image is best suitable with less loading time.', 'customizer-login-page' ),
				'settings'      => 'lpc_opts[lpc-form-bg-image]',
				'section'       => 'lpc-section-form',
				'button_labels' => array( // Optional.
					'select'       => __( 'Select Image' ),
					'change'       => __( 'Change Image' ),
					'remove'       => __( 'Remove' ),
					'default'      => __( 'Default' ),
					'placeholder'  => __( 'No image selected' ),
					'frame_title'  => __( 'Select Image' ),
					'frame_button' => __( 'Choose Image' ),
				),
			)
		)
	);

	// Image Background repeat control.
	$wp_customize->add_setting(
		'lpc_opts[lpc-form-bg-image-repeat]',
		array(
			'type'              => 'option',
			'default'           => 'no-repeat',
			'transport'         => 'postMessage',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);

	$wp_customize->add_control(
		'lpc_opts[lpc-form-bg-image-repeat]-control',
		array(
			'label'    => __( 'Form Image Repeat', 'customizer-login-page' ),
			// 'description' => esc_html__( 'Sample description' ),
			'settings' => 'lpc_opts[lpc-form-bg-image-repeat]',
			'section'  => 'lpc-section-form',
			'type'     => 'select',
			'choices'  => array( // Optional.
				'repeat'    => __( 'Repeat' ),
				'repeat-x'  => __( 'Repeat-x axis' ),
				'repeat-y'  => __( 'Repeat-y axis' ),
				'no-repeat' => __( 'No-repeat' ),
				'initial'   => __( 'Initial' ),
				'inherit'   => __( 'Inherit' ),
			),
		)
	);
	// Image size control.
	$wp_customize->add_setting(
		'lpc_opts[lpc-form-bg-image-size]',
		array(
			'type'              => 'option',
			'default'           => 'cover',
			'transport'         => 'postMessage',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);

	$wp_customize->add_control(
		'lpc_opts[lpc-form-bg-image-size]-control',
		array(
			'label'    => __( 'Form Image Size', 'customizer-login-page' ),
			'settings' => 'lpc_opts[lpc-form-bg-image-size]',
			'section'  => 'lpc-section-form',
			'type'     => 'select',
			'choices'  => array( // Optional.
				'auto'    => __( 'Auto' ),
				'cover'   => __( 'Cover' ),
				'contain' => __( 'Contain' ),
				'initial' => __( 'Initial' ),
				'inherit' => __( 'Inherit' ),
			),
		)
	);
	// Image Position control.
	$wp_customize->add_setting(
		'lpc_opts[lpc-form-bg-image-position]',
		array(
			'type'              => 'option',
			'default'           => 'center',
			'transport'         => 'postMessage',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);

	$wp_customize->add_control(
		'lpc_opts[lpc-form-bg-image-position]-control',
		array(
			'label'    => __( 'Form Image Position', 'customizer-login-page' ),
			'settings' => 'lpc_opts[lpc-form-bg-image-position]',
			'section'  => 'lpc-section-form',
			'type'     => 'select',
			'choices'  => array( // Optional.
				'center'        => __( 'Center' ),
				'center top'    => __( 'Center Top' ),
				'center bottom' => __( 'Center Bottom' ),
				'left top'      => __( 'Left Top' ),
				'left center'   => __( 'Left Center' ),
				'left bottom'   => __( 'Left Bottom' ),
				'right top'     => __( 'Right Top' ),
				'right center'  => __( 'Right Center' ),
				'right bottom'  => __( 'Right Bottom' ),
			),
		)
	);

	/** End : Login Outer Form */
