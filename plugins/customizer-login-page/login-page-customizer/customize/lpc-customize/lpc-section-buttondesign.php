<?php
	/** Start : Section Button Design/Settings */
	$wp_customize->add_section(
		'lpc-section-button',
		array(
			'title' => __( 'Form Button Design/Settings', 'customizer-login-page' ),
			'panel' => 'lpc-main-panel',
		)
	);
		// Button heading control.
		$wp_customize->add_setting(
			'lpc-section-button-heading',
			array(
				'default'           => '',
				'sanitize_callback' => 'sanitize_text_field',
			)
		);
		$wp_customize->add_control(
			new Lpc_Simple_Notice_Custom_control(
				$wp_customize,
				'lpc-section-button-heading-control',
				array(
					'label'    => __( 'Button Settings', 'customizer-login-page' ),
					'settings' => 'lpc-section-button-heading',
					'section'  => 'lpc-section-button',
				)
			)
		);
		// Login Button text.
		$wp_customize->add_setting(
			'lpc_opts[lpc-form-button-text]',
			array(
				'type'              => 'option',
				'default'           => 'Log In',
				'transport'         => 'postMessage',
				'sanitize_callback' => 'sanitize_text_field',
			)
		);
		$wp_customize->add_control(
			'lpc_opts[lpc-form-button-text]-control',
			array(
				'label'    => __( 'Login Button Text [ Default : Log In ]', 'customizer-login-page' ),
				// 'description'    => __( 'Publish and please log out and navigate to the login page to observe the updated Title changes.', 'customizer-login-page' ),
				'section'  => 'lpc-section-button',
				'settings' => 'lpc_opts[lpc-form-button-text]',
				'type'     => 'text',
			)
		);
		// Button Align.
		$wp_customize->add_setting(
			'lpc_opts[lpc-form-button-align]',
			array(
				'type'              => 'option',
				'default'           => 'center',
				'transport'         => 'postMessage',
				'sanitize_callback' => 'sanitize_text_field',
			)
		);
		$wp_customize->add_control(
			new Lpc_Text_Radio_Button_Custom_Control(
				$wp_customize,
				'lpc_opts[lpc-form-button-align]-control',
				array(
					'label'    => __( 'Button Alignment', 'customizer-login-page' ),
					'settings' => 'lpc_opts[lpc-form-button-align]',
					'section'  => 'lpc-section-button',
					'choices'  => array(
						'left'   => __( 'Left' ), // Required.
						'center' => __( 'Center' ), // Required.
						'right'  => __( 'Right' ), // Required.
					),
				)
			)
		);
		// Button Width (%).
		$wp_customize->add_setting(
			'lpc_opts[lpc-form-button-width]',
			array(
				'type'              => 'option',
				'default'           => '50',
				'transport'         => 'postMessage',
				'sanitize_callback' => 'lpc_sanitize_integer',
			)
		);
		$wp_customize->add_control(
			new lpc_Slider_Custom_Control(
				$wp_customize,
				'lpc_opts[lpc-form-button-width]-control',
				array(
					'label'       => esc_html__( 'Button Width (%)', 'customizer-login-page' ),
					'settings'    => 'lpc_opts[lpc-form-button-width]',
					'section'     => 'lpc-section-button',
					'input_attrs' => array(
						'min'  => 0, // Required. Minimum value for the slider.
						'max'  => 100, // Required. Maximum value for the slider.
						'step' => 1, // Required. The size of each interval or step the slider takes between the minimum and maximum values.
					),
				)
			)
		);
		// Button Height (px).
		$wp_customize->add_setting(
			'lpc_opts[lpc-form-button-height]',
			array(
				'type'              => 'option',
				'default'           => '32',
				'transport'         => 'postMessage',
				'sanitize_callback' => 'lpc_sanitize_integer',
			)
		);
		$wp_customize->add_control(
			new lpc_Slider_Custom_Control(
				$wp_customize,
				'lpc_opts[lpc-form-button-height]-control',
				array(
					'label'       => esc_html__( 'Button Height (px)', 'customizer-login-page' ),
					'settings'    => 'lpc_opts[lpc-form-button-height]',
					'section'     => 'lpc-section-button',
					'input_attrs' => array(
						'min'  => 0, // Required. Minimum value for the slider.
						'max'  => 100, // Required. Maximum value for the slider.
						'step' => 1, // Required. The size of each interval or step the slider takes between the minimum and maximum values.
					),
				)
			)
		);
		// Button Font Size .
		$wp_customize->add_setting(
			'lpc_opts[lpc-form-button-font-size]',
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
				'lpc_opts[lpc-form-button-font-size]-control',
				array(
					'label'       => esc_html__( 'Font Size (px)', 'customizer-login-page' ),
					'settings'    => 'lpc_opts[lpc-form-button-font-size]',
					'section'     => 'lpc-section-button',
					'input_attrs' => array(
						'min'  => 0, // Required. Minimum value for the slider.
						'max'  => 100, // Required. Maximum value for the slider.
						'step' => 1, // Required. The size of each interval or step the slider takes between the minimum and maximum values.
					),
				)
			)
		);
		// Button Margin Heading.
		$wp_customize->add_setting(
			'lpc-section-button-mheading',
			array(
				'default'           => '',
				'sanitize_callback' => 'sanitize_text_field',
			)
		);
		$wp_customize->add_control(
			new Lpc_Simple_Notice_Custom_control(
				$wp_customize,
				'lpc-section-button-mheading-control',
				array(
					'label'    => __( 'Button Margin', 'customizer-login-page' ),
					'settings' => 'lpc-section-button-mheading',
					'section'  => 'lpc-section-button',
				)
			)
		);
		// Button Margin Top .
		$wp_customize->add_setting(
			'lpc_opts[lpc-form-button-margin-top]',
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
				'lpc_opts[lpc-form-button-margin-top]-control',
				array(
					'label'       => esc_html__( 'Button Margin-top (px)', 'customizer-login-page' ),
					'settings'    => 'lpc_opts[lpc-form-button-margin-top]',
					'section'     => 'lpc-section-button',
					'input_attrs' => array(
						'min'  => 0, // Required. Minimum value for the slider.
						'max'  => 100, // Required. Maximum value for the slider.
						'step' => 1, // Required. The size of each interval or step the slider takes between the minimum and maximum values.
					),
				)
			)
		);
		// Button Margin right .
		$wp_customize->add_setting(
			'lpc_opts[lpc-form-button-margin-right]',
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
				'lpc_opts[lpc-form-button-margin-right]-control',
				array(
					'label'       => esc_html__( 'Button Margin-Right (px)', 'customizer-login-page' ),
					'settings'    => 'lpc_opts[lpc-form-button-margin-right]',
					'section'     => 'lpc-section-button',
					'input_attrs' => array(
						'min'  => 0, // Required. Minimum value for the slider.
						'max'  => 100, // Required. Maximum value for the slider.
						'step' => 1, // Required. The size of each interval or step the slider takes between the minimum and maximum values.
					),
				)
			)
		);
		// Button Margin bottom .
		$wp_customize->add_setting(
			'lpc_opts[lpc-form-button-margin-bottom]',
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
				'lpc_opts[lpc-form-button-margin-bottom]-control',
				array(
					'label'       => esc_html__( 'Button Margin-bottom (px)', 'customizer-login-page' ),
					'settings'    => 'lpc_opts[lpc-form-button-margin-bottom]',
					'section'     => 'lpc-section-button',
					'input_attrs' => array(
						'min'  => 0, // Required. Minimum value for the slider.
						'max'  => 100, // Required. Maximum value for the slider.
						'step' => 1, // Required. The size of each interval or step the slider takes between the minimum and maximum values.
					),
				)
			)
		);
		// Button Margin left .
		$wp_customize->add_setting(
			'lpc_opts[lpc-form-button-margin-left]',
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
				'lpc_opts[lpc-form-button-margin-left]-control',
				array(
					'label'       => esc_html__( 'Button Margin-left (px)', 'customizer-login-page' ),
					'settings'    => 'lpc_opts[lpc-form-button-margin-left]',
					'section'     => 'lpc-section-button',
					'input_attrs' => array(
						'min'  => 0, // Required. Minimum value for the slider.
						'max'  => 100, // Required. Maximum value for the slider.
						'step' => 1, // Required. The size of each interval or step the slider takes between the minimum and maximum values.
					),
				)
			)
		);
		// Button Margin Heading.
		$wp_customize->add_setting(
			'lpc-section-button-pheading',
			array(
				'default'           => '',
				'sanitize_callback' => 'sanitize_text_field',
			)
		);
		$wp_customize->add_control(
			new Lpc_Simple_Notice_Custom_control(
				$wp_customize,
				'lpc-section-button-pheading-control',
				array(
					'label'    => __( 'Button Padding', 'customizer-login-page' ),
					'settings' => 'lpc-section-button-pheading',
					'section'  => 'lpc-section-button',
				)
			)
		);
		// Button Padding Top+Bottom .
		$wp_customize->add_setting(
			'lpc_opts[lpc-form-button-padding-tb]',
			array(
				'type'              => 'option',
				'default'           => '1',
				'transport'         => 'postMessage',
				'sanitize_callback' => 'lpc_sanitize_integer',
			)
		);
		$wp_customize->add_control(
			new lpc_Slider_Custom_Control(
				$wp_customize,
				'lpc_opts[lpc-form-button-padding-tb]-control',
				array(
					'label'       => esc_html__( 'Button Padding Top+Bottom (px)', 'customizer-login-page' ),
					'settings'    => 'lpc_opts[lpc-form-button-padding-tb]',
					'section'     => 'lpc-section-button',
					'input_attrs' => array(
						'min'  => 0, // Required. Minimum value for the slider.
						'max'  => 100, // Required. Maximum value for the slider.
						'step' => 1, // Required. The size of each interval or step the slider takes between the minimum and maximum values.
					),
				)
			)
		);
		// Button Padding Top+Bottom .
		$wp_customize->add_setting(
			'lpc_opts[lpc-form-button-padding-lr]',
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
				'lpc_opts[lpc-form-button-padding-lr]-control',
				array(
					'label'       => esc_html__( 'Button Padding left+Right (px)', 'customizer-login-page' ),
					'settings'    => 'lpc_opts[lpc-form-button-padding-lr]',
					'section'     => 'lpc-section-button',
					'input_attrs' => array(
						'min'  => 0, // Required. Minimum value for the slider.
						'max'  => 100, // Required. Maximum value for the slider.
						'step' => 1, // Required. The size of each interval or step the slider takes between the minimum and maximum values.
					),
				)
			)
		);
		// Button Styling Heading.
		$wp_customize->add_setting(
			'lpc-section-button-styling-heading',
			array(
				'default'           => '',
				'sanitize_callback' => 'sanitize_text_field',
			)
		);
		$wp_customize->add_control(
			new Lpc_Simple_Notice_Custom_control(
				$wp_customize,
				'lpc-section-button-styling-heading-control',
				array(
					'label'    => __( 'Button Styling', 'customizer-login-page' ),
					'settings' => 'lpc-section-button-styling-heading',
					'section'  => 'lpc-section-button',
				)
			)
		);
		// Button Color.
		$wp_customize->add_setting(
			'lpc_opts[lpc-form-button-color]',
			array(
				'type'              => 'option',
				'default'           => '#2271b1',
				'transport'         => 'postMessage',
				'sanitize_callback' => 'lpc_hex_rgba_sanitization',
			)
		);

		// Alpha Color Picker control.
		$wp_customize->add_control(
			new LPC_Alpha_Color_Control(
				$wp_customize,
				'lpc_opts[lpc-form-button-color]-control',
				array(
					'label'       => __( 'Button Color', 'customizer-login-page' ),
					'settings'    => 'lpc_opts[lpc-form-button-color]',
					'section'     => 'lpc-section-button',
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
		// Button Color (Hover).
		$wp_customize->add_setting(
			'lpc_opts[lpc-form-button-color-hover]',
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
				'lpc_opts[lpc-form-button-color-hover]-control',
				array(
					'label'       => __( 'Button Color (Hover)', 'customizer-login-page' ),
					'settings'    => 'lpc_opts[lpc-form-button-color-hover]',
					'section'     => 'lpc-section-button',
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
		// Button Text Color.
		$wp_customize->add_setting(
			'lpc_opts[lpc-form-button-text-color]',
			array(
				'type'              => 'option',
				'default'           => '#fff',
				'transport'         => 'postMessage',
				'sanitize_callback' => 'lpc_hex_rgba_sanitization',
			)
		);

		$wp_customize->add_control(
			new LPC_Alpha_Color_Control(
				$wp_customize,
				'lpc_opts[lpc-form-button-text-color]-control',
				array(
					'label'       => __( 'Button Text Color', 'customizer-login-page' ),
					'settings'    => 'lpc_opts[lpc-form-button-text-color]',
					'section'     => 'lpc-section-button',
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
		// Button Text Color (Hover).
		$wp_customize->add_setting(
			'lpc_opts[lpc-form-button-text-color-hover]',
			array(
				'type'              => 'option',
				'default'           => '#fff',
				'transport'         => 'postMessage',
				'sanitize_callback' => 'lpc_hex_rgba_sanitization',
			)
		);

		$wp_customize->add_control(
			new LPC_Alpha_Color_Control(
				$wp_customize,
				'lpc_opts[lpc-form-button-text-color-hover]-control',
				array(
					'label'       => __( 'Button Text Color (Hover)', 'customizer-login-page' ),
					'settings'    => 'lpc_opts[lpc-form-button-text-color-hover]',
					'section'     => 'lpc-section-button',
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
		// Button Border Style.
		$wp_customize->add_setting(
			'lpc_opts[lpc-form-button-border-style]',
			array(
				'type'              => 'option',
				'default'           => 'solid',
				'transport'         => 'postMessage',
				'sanitize_callback' => 'sanitize_text_field',
			)
		);

		$wp_customize->add_control(
			'lpc_opts[lpc-form-button-border-style]-control',
			array(
				'label'    => __( 'Button Border Style', 'customizer-login-page' ),
				'settings' => 'lpc_opts[lpc-form-button-border-style]',
				'section'  => 'lpc-section-button',
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
		// Button Border Width.
		$wp_customize->add_setting(
			'lpc_opts[lpc-form-button-border-width]',
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
				'lpc_opts[lpc-form-button-border-width]-control',
				array(
					'label'       => esc_html__( 'Button Border Width (px)', 'customizer-login-page' ),
					'settings'    => 'lpc_opts[lpc-form-button-border-width]',
					'section'     => 'lpc-section-button',
					'input_attrs' => array(
						'min'  => 0, // Required. Minimum value for the slider.
						'max'  => 15, // Required. Maximum value for the slider.
						'step' => 1, // Required. The size of each interval or step the slider takes between the minimum and maximum values.
					),
				)
			)
		);
		// Button Border Color.
		$wp_customize->add_setting(
			'lpc_opts[lpc-form-button-border-color]',
			array(
				'type'              => 'option',
				'default'           => '#2271b1',
				'transport'         => 'postMessage',
				'sanitize_callback' => 'lpc_hex_rgba_sanitization',
			)
		);

		$wp_customize->add_control(
			new LPC_Alpha_Color_Control(
				$wp_customize,
				'lpc_opts[lpc-form-button-border-color]-control',
				array(
					'label'       => __( 'Button Border Color', 'customizer-login-page' ),
					'settings'    => 'lpc_opts[lpc-form-button-border-color]',
					'section'     => 'lpc-section-button',
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
		// Button Border Hover Color.
		$wp_customize->add_setting(
			'lpc_opts[lpc-form-button-border-hover-color]',
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
				'lpc_opts[lpc-form-button-border-hover-color]-control',
				array(
					'label'       => __( 'Button Border Color (Hover)', 'customizer-login-page' ),
					'settings'    => 'lpc_opts[lpc-form-button-border-hover-color]',
					'section'     => 'lpc-section-button',
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
		// Button Border Radius (px).
		$wp_customize->add_setting(
			'lpc_opts[lpc-form-button-border-radius]',
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
				'lpc_opts[lpc-form-button-border-radius]-control',
				array(
					'label'       => esc_html__( 'Button Border Radius (px)', 'customizer-login-page' ),
					'settings'    => 'lpc_opts[lpc-form-button-border-radius]',
					'section'     => 'lpc-section-button',
					'input_attrs' => array(
						'min'  => 0, // Required. Minimum value for the slider.
						'max'  => 300, // Required. Maximum value for the slider.
						'step' => 1, // Required. The size of each interval or step the slider takes between the minimum and maximum values.
					),
				)
			)
		);
		// Form Button Box Shadow.
		$wp_customize->add_setting(
			'lpc_opts[lpc-form-button-box-shadow]',
			array(
				'type'              => 'option',
				'default'           => '',
				'transport'         => 'postMessage',
				'sanitize_callback' => 'sanitize_text_field',
			)
		);

		$wp_customize->add_control(
			'lpc_opts[lpc-form-button-box-shadow]-control',
			array(
				'label'       => __( 'Button Box Shadow', 'customizer-login-page' ),
				'description' => __( 'Make sure your box-shadow property is in proper CSS format like: <b>0px 0px 20px 0px rgba(0, 0, 0, 0.2)</b> or Leave it Blank for no shadow. Also you can easily Generate Here:', 'customizer-login-page' ) . ' <a href="https://cssgenerator.org/box-shadow-css-generator.html" target="_blank">' . __( 'Shadow Generator', 'customizer-login-page' ) . '</a>',
				'settings'    => 'lpc_opts[lpc-form-button-box-shadow]',
				'section'     => 'lpc-section-button',
				'type'        => 'text',
				'input_attrs' => array( // Optional.
					'placeholder' => '0 1px 3px rgba(0,0,0,.04)',
				),
			)
		);
		/** End : Section Button Design/Settings */
