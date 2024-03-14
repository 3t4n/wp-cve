<?php
/** Start : Login Inner Form */
$wp_customize->add_section(
	'lpc-section-inner-form',
	array(
		'title' => __( 'Inner Form Settings', 'customizer-login-page' ),
		'panel' => 'lpc-main-panel',
	)
);
	// Inner Form heading control.
	$wp_customize->add_setting(
		'lpc-inner-form-heading',
		array(
			'default'           => '',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);
	$wp_customize->add_control(
		new Lpc_Simple_Notice_Custom_control(
			$wp_customize,
			'lpc-inner-form-heading-control',
			array(
				'label'    => __( 'Inner Form Settings', 'customizer-login-page' ),
				'settings' => 'lpc-inner-form-heading',
				'section'  => 'lpc-section-inner-form',
			)
		)
	);
	// Inner Form width.
	$wp_customize->add_setting(
		'lpc_opts[lpc-inner-form-width]',
		array(
			'type'              => 'option',
			'default'           => '270',
			'transport'         => 'postMessage',
			'sanitize_callback' => 'lpc_sanitize_integer',
		)
	);
	$wp_customize->add_control(
		new lpc_Slider_Custom_Control(
			$wp_customize,
			'lpc_opts[lpc-inner-form-width]-control',
			array(
				'label'       => esc_html__( 'Width (px)', 'customizer-login-page' ),
				'settings'    => 'lpc_opts[lpc-inner-form-width]',
				'section'     => 'lpc-section-inner-form',
				'input_attrs' => array(
					'min'  => 180, // Required. Minimum value for the slider.
					'max'  => 2000, // Required. Maximum value for the slider.
					'step' => 1, // Required. The size of each interval or step the slider takes between the minimum and maximum values.
				),
			)
		)
	);
	// Inner Form Height.
	$wp_customize->add_setting(
		'lpc_opts[lpc-inner-form-height]',
		array(
			'type'              => 'option',
			'default'           => '230',
			'transport'         => 'postMessage',
			'sanitize_callback' => 'lpc_sanitize_integer',
		)
	);
	$wp_customize->add_control(
		new lpc_Slider_Custom_Control(
			$wp_customize,
			'lpc_opts[lpc-inner-form-height]-control',
			array(
				'label'       => esc_html__( 'Height (px)', 'customizer-login-page' ),
				'settings'    => 'lpc_opts[lpc-inner-form-height]',
				'section'     => 'lpc-section-inner-form',
				'input_attrs' => array(
					'min'  => 180, // Required. Minimum value for the slider.
					'max'  => 1000, // Required. Maximum value for the slider.
					'step' => 1, // Required. The size of each interval or step the slider takes between the minimum and maximum values.
				),
			)
		)
	);
	// Inner Form Paddings heading control.
	$wp_customize->add_setting(
		'lpc-inner-form-paddings',
		array(
			'default'           => '',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);
	$wp_customize->add_control(
		new Lpc_Simple_Notice_Custom_control(
			$wp_customize,
			'lpc-inner-form-paddings-control',
			array(
				'label'    => __( 'Inner Form Paddings', 'customizer-login-page' ),
				'settings' => 'lpc-inner-form-paddings',
				'section'  => 'lpc-section-inner-form',
			)
		)
	);
	// Inner Form Padding Top.
	$wp_customize->add_setting(
		'lpc_opts[lpc-inner-form-padding-top]',
		array(
			'type'              => 'option',
			'default'           => '26',
			'transport'         => 'postMessage',
			'sanitize_callback' => 'lpc_sanitize_integer',
		)
	);
	$wp_customize->add_control(
		new lpc_Slider_Custom_Control(
			$wp_customize,
			'lpc_opts[lpc-inner-form-padding-top]-control',
			array(
				'label'       => esc_html__( 'Padding Top (px)', 'customizer-login-page' ),
				'settings'    => 'lpc_opts[lpc-inner-form-padding-top]',
				'section'     => 'lpc-section-inner-form',
				'input_attrs' => array(
					'min'  => 0, // Required. Minimum value for the slider.
					'max'  => 300, // Required. Maximum value for the slider.
					'step' => 1, // Required. The size of each interval or step the slider takes between the minimum and maximum values.
				),
			)
		)
	);
	// Inner Form Padding Right.
	$wp_customize->add_setting(
		'lpc_opts[lpc-inner-form-padding-right]',
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
			'lpc_opts[lpc-inner-form-padding-right]-control',
			array(
				'label'       => esc_html__( 'Padding Right (px)', 'customizer-login-page' ),
				'settings'    => 'lpc_opts[lpc-inner-form-padding-right]',
				'section'     => 'lpc-section-inner-form',
				'input_attrs' => array(
					'min'  => 0, // Required. Minimum value for the slider.
					'max'  => 300, // Required. Maximum value for the slider.
					'step' => 1, // Required. The size of each interval or step the slider takes between the minimum and maximum values.
				),
			)
		)
	);

	// Inner Form Padding Bottom.
	$wp_customize->add_setting(
		'lpc_opts[lpc-inner-form-padding-bottom]',
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
			'lpc_opts[lpc-inner-form-padding-bottom]-control',
			array(
				'label'       => esc_html__( 'Padding Bottom (px)', 'customizer-login-page' ),
				'settings'    => 'lpc_opts[lpc-inner-form-padding-bottom]',
				'section'     => 'lpc-section-inner-form',
				'input_attrs' => array(
					'min'  => 0, // Required. Minimum value for the slider.
					'max'  => 300, // Required. Maximum value for the slider.
					'step' => 1, // Required. The size of each interval or step the slider takes between the minimum and maximum values.
				),
			)
		)
	);

	// Inner Form Padding Left.
	$wp_customize->add_setting(
		'lpc_opts[lpc-inner-form-padding-left]',
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
			'lpc_opts[lpc-inner-form-padding-left]-control',
			array(
				'label'       => esc_html__( 'Padding Left (px)', 'customizer-login-page' ),
				'settings'    => 'lpc_opts[lpc-inner-form-padding-left]',
				'section'     => 'lpc-section-inner-form',
				'input_attrs' => array(
					'min'  => 0, // Required. Minimum value for the slider.
					'max'  => 300, // Required. Maximum value for the slider.
					'step' => 1, // Required. The size of each interval or step the slider takes between the minimum and maximum values.
				),
			)
		)
	);
	// Inner Form Margin heading control.
	$wp_customize->add_setting(
		'lpc-inner-form-margins',
		array(
			'default'           => '',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);
	$wp_customize->add_control(
		new Lpc_Simple_Notice_Custom_control(
			$wp_customize,
			'lpc-inner-form-margins-control',
			array(
				'label'    => __( 'Inner Form margins', 'customizer-login-page' ),
				'settings' => 'lpc-inner-form-margins',
				'section'  => 'lpc-section-inner-form',
			)
		)
	);
	// Inner Form margin Top.
	$wp_customize->add_setting(
		'lpc_opts[lpc-inner-form-margin-top]',
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
			'lpc_opts[lpc-inner-form-margin-top]-control',
			array(
				'label'       => esc_html__( 'margin Top (px)', 'customizer-login-page' ),
				'settings'    => 'lpc_opts[lpc-inner-form-margin-top]',
				'section'     => 'lpc-section-inner-form',
				'input_attrs' => array(
					'min'  => 0, // Required. Minimum value for the slider.
					'max'  => 300, // Required. Maximum value for the slider.
					'step' => 1, // Required. The size of each interval or step the slider takes between the minimum and maximum values.
				),
			)
		)
	);
	// Inner Form margin Right.
	$wp_customize->add_setting(
		'lpc_opts[lpc-inner-form-margin-right]',
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
			'lpc_opts[lpc-inner-form-margin-right]-control',
			array(
				'label'       => esc_html__( 'margin Right (px)', 'customizer-login-page' ),
				'settings'    => 'lpc_opts[lpc-inner-form-margin-right]',
				'section'     => 'lpc-section-inner-form',
				'input_attrs' => array(
					'min'  => 0, // Required. Minimum value for the slider.
					'max'  => 300, // Required. Maximum value for the slider.
					'step' => 1, // Required. The size of each interval or step the slider takes between the minimum and maximum values.
				),
			)
		)
	);

	// Inner Form margin Bottom.
	$wp_customize->add_setting(
		'lpc_opts[lpc-inner-form-margin-bottom]',
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
			'lpc_opts[lpc-inner-form-margin-bottom]-control',
			array(
				'label'       => esc_html__( 'margin Bottom (px)', 'customizer-login-page' ),
				'settings'    => 'lpc_opts[lpc-inner-form-margin-bottom]',
				'section'     => 'lpc-section-inner-form',
				'input_attrs' => array(
					'min'  => 0, // Required. Minimum value for the slider.
					'max'  => 300, // Required. Maximum value for the slider.
					'step' => 1, // Required. The size of each interval or step the slider takes between the minimum and maximum values.
				),
			)
		)
	);

	// Inner Form margin Left.
	$wp_customize->add_setting(
		'lpc_opts[lpc-inner-form-margin-left]',
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
			'lpc_opts[lpc-inner-form-margin-left]-control',
			array(
				'label'       => esc_html__( 'margin Left (px)', 'customizer-login-page' ),
				'settings'    => 'lpc_opts[lpc-inner-form-margin-left]',
				'section'     => 'lpc-section-inner-form',
				'input_attrs' => array(
					'min'  => 0, // Required. Minimum value for the slider.
					'max'  => 300, // Required. Maximum value for the slider.
					'step' => 1, // Required. The size of each interval or step the slider takes between the minimum and maximum values.
				),
			)
		)
	);
	// Inner Form Border Style.
	$wp_customize->add_setting(
		'lpc_opts[lpc-inner-form-border-style]',
		array(
			'type'              => 'option',
			'default'           => 'solid',
			'transport'         => 'postMessage',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);

	$wp_customize->add_control(
		'lpc_opts[lpc-inner-form-border-style]-control',
		array(
			'label'    => __( 'Border Style', 'customizer-login-page' ),
			'settings' => 'lpc_opts[lpc-inner-form-border-style]',
			'section'  => 'lpc-section-inner-form',
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
	// Inner Form Border Width.
	$wp_customize->add_setting(
		'lpc_opts[lpc-inner-form-border-width]',
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
			'lpc_opts[lpc-inner-form-border-width]-control',
			array(
				'label'       => esc_html__( 'Border Width (px)', 'customizer-login-page' ),
				'settings'    => 'lpc_opts[lpc-inner-form-border-width]',
				'section'     => 'lpc-section-inner-form',
				'input_attrs' => array(
					'min'  => 0, // Required. Minimum value for the slider.
					'max'  => 15, // Required. Maximum value for the slider.
					'step' => 1, // Required. The size of each interval or step the slider takes between the minimum and maximum values.
				),
			)
		)
	);
	// Inner Form Border Color.
	$wp_customize->add_setting(
		'lpc_opts[lpc-inner-form-border-color]',
		array(
			'type'              => 'option',
			'default'           => '#c3c4c7',
			'transport'         => 'postMessage',
			'sanitize_callback' => 'sanitize_hex_color',
		)
	);

	$wp_customize->add_control(
		'lpc_opts[lpc-inner-form-border-color]-control',
		array(
			'label'    => __( 'Border Color', 'customizer-login-page' ),
			'settings' => 'lpc_opts[lpc-inner-form-border-color]',
			'section'  => 'lpc-section-inner-form',
			'type'     => 'color',
		)
	);
	// Inner Border Radius (%).
	$wp_customize->add_setting(
		'lpc_opts[lpc-inner-form-border-radius]',
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
			'lpc_opts[lpc-inner-form-border-radius]-control',
			array(
				'label'       => esc_html__( 'Border Radius (px)', 'customizer-login-page' ),
				'settings'    => 'lpc_opts[lpc-inner-form-border-radius]',
				'section'     => 'lpc-section-inner-form',
				'input_attrs' => array(
					'min'  => 0, // Required. Minimum value for the slider.
					'max'  => 300, // Required. Maximum value for the slider.
					'step' => 1, // Required. The size of each interval or step the slider takes between the minimum and maximum values.
				),
			)
		)
	);
	// Inner Form Box Shadow.
	$wp_customize->add_setting(
		'lpc_opts[lpc-inner-form-box-shadow]',
		array(
			'type'              => 'option',
			'default'           => '',
			'transport'         => 'postMessage',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);

	$wp_customize->add_control(
		'lpc_opts[lpc-inner-form-box-shadow]-control',
		array(
			'label'       => __( 'Box Shadow', 'customizer-login-page' ),
			'description' => __( 'Make sure your box-shadow property is in proper CSS format like: <b>0px 0px 20px 0px rgba(0, 0, 0, 0.2)</b> or Leave it Blank for no shadow. Also you can easily Generate Here:', 'customizer-login-page' ) . ' <a href="https://cssgenerator.org/box-shadow-css-generator.html" target="_blank">' . __( 'Shadow Generator', 'customizer-login-page' ) . '</a>',
			'settings'    => 'lpc_opts[lpc-inner-form-box-shadow]',
			'section'     => 'lpc-section-inner-form',
			'type'        => 'text',
			'input_attrs' => array( // Optional.
				'placeholder' => '0px 0px 20px 0px rgba(0, 0, 0, 0.2)',
			),
		)
	);
	// Inner Form Background heading control.
	$wp_customize->add_setting(
		'lpc-inner-form-bg-heading',
		array(
			'default'           => '',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);
	$wp_customize->add_control(
		new Lpc_Simple_Notice_Custom_control(
			$wp_customize,
			'lpc-inner-form-bg-heading-control',
			array(
				'label'    => __( 'Inner From Background Settings', 'customizer-login-page' ),
				'settings' => 'lpc-inner-form-bg-heading',
				'section'  => 'lpc-section-inner-form',
			)
		)
	);
	// Inner Background Color Alpha Color Picker setting.
	$wp_customize->add_setting(
		'lpc_opts[lpc-inner-form-bg-color]',
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
			'lpc_opts[lpc-inner-form-bg-color]-control',
			array(
				'label'       => __( 'Inner Form Background Color', 'customizer-login-page' ),
				'section'     => 'lpc-section-inner-form',
				'settings'    => 'lpc_opts[lpc-inner-form-bg-color]',
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
	// Inner Form Background Image.
	$wp_customize->add_setting(
		'lpc_opts[lpc-inner-form-bg-image]',
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
			'lpc_opts[lpc-inner-form-bg-image]-control',
			array(
				'label'         => __( 'Inner Form Background Image', 'customizer-login-page' ),
				'description'   => esc_html__( 'Small png image is best suitable with less loading time.', 'customizer-login-page' ),
				'settings'      => 'lpc_opts[lpc-inner-form-bg-image]',
				'section'       => 'lpc-section-inner-form',
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
	// Inner Form Position heading control.
	$wp_customize->add_setting(
		'lpc-inner-form-position-heading',
		array(
			'type'              => 'option',
			'default'           => '',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);
	$wp_customize->add_control(
		new Lpc_Simple_Notice_Custom_control(
			$wp_customize,
			'lpc-inner-form-position-heading-control',
			array(
				'label'    => __( 'Inner Form Position', 'customizer-login-page' ),
				'settings' => 'lpc-inner-form-position-heading',
				'section'  => 'lpc-section-inner-form',
			)
		)
	);

	// Position Top X axis (%).
	$wp_customize->add_setting(
		'lpc_opts[lpc-inner-form-position-top]',
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
			'lpc_opts[lpc-inner-form-position-top]-control',
			array(
				'label'       => esc_html__( 'Position Y Axis (%)', 'customizer-login-page' ),
				'settings'    => 'lpc_opts[lpc-inner-form-position-top]',
				'section'     => 'lpc-section-inner-form',
				'input_attrs' => array(
					'min'  => -100, // Required. Minimum value for the slider.
					'max'  => 100, // Required. Maximum value for the slider.
					'step' => 0.5, // Required. The size of each interval or step the slider takes between the minimum and maximum values.
				),
			)
		)
	);
	// Inner Form Position Left Y Axiz (%).
	$wp_customize->add_setting(
		'lpc_opts[lpc-inner-form-position-left]',
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
			'lpc_opts[lpc-inner-form-position-left]-control',
			array(
				'label'       => esc_html__( 'Position X Axis (%)', 'customizer-login-page' ),
				'settings'    => 'lpc_opts[lpc-inner-form-position-left]',
				'section'     => 'lpc-section-inner-form',
				'input_attrs' => array(
					'min'  => -100, // Required. Minimum value for the slider.
					'max'  => 100, // Required. Maximum value for the slider.
					'step' => 0.5, // Required. The size of each interval or step the slider takes between the minimum and maximum values.
				),
			)
		)
	);
	/** End Section : Inner Form  */
