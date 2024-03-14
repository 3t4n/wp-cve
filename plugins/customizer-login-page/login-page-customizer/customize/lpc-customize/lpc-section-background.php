<?php
/** Section : Login Background  */
$wp_customize->add_section(
	'lpc-section-background',
	array(
		'title' => __( 'Background Settings', 'customizer-login-page' ),
		'panel' => 'lpc-main-panel',
	)
);

	// Background Solid heading control.
	$wp_customize->add_setting(
		'lpc-background-heading',
		array(
			'default'           => '',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);
	$wp_customize->add_control(
		new Lpc_Simple_Notice_Custom_control(
			$wp_customize,
			'lpc-background-heading-control',
			array(
				'label'    => __( 'Background Color Settings', 'customizer-login-page' ),
				'settings' => 'lpc-background-heading',
				'section'  => 'lpc-section-background',
			)
		)
	);
	// Background Color Choose.
	$wp_customize->add_setting(
		'lpc_opts[lpc-background-color-choice]',
		array(
			'type'              => 'option',
			'default'           => 'solid',
			'transport'         => 'postMessage',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);
	$wp_customize->add_control(
		new Lpc_Text_Radio_Button_Custom_Control(
			$wp_customize,
			'lpc_opts[lpc-background-color-choice]-control',
			array(
				'label'    => __( 'Choose Background Color Type', 'customizer-login-page' ),
				'settings' => 'lpc_opts[lpc-background-color-choice]',
				'section'  => 'lpc-section-background',
				'choices'  => array(
					'solid'    => __( 'Solid Color' ),
					'gradient' => __( 'Gradient Color' ),
				),
			)
		)
	);
	// Background Color Alpha Color Picker setting.
	$wp_customize->add_setting(
		'lpc_opts[lpc-background-color]',
		array(
			'type'              => 'option',
			'default'           => ' #C3C4C7',
			'transport'         => 'postMessage',
			'sanitize_callback' => 'lpc_hex_rgba_sanitization',
		)
	);

	// Alpha Color Picker control.
	$wp_customize->add_control(
		new LPC_Alpha_Color_Control(
			$wp_customize,
			'lpc_opts[lpc-background-color]-control',
			array(
				'label'       => __( 'Solid Background Color', 'customizer-login-page' ),
				'section'     => 'lpc-section-background',
				'settings'    => 'lpc_opts[lpc-background-color]',
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

	// Gradient Color Alpha Color 1 setting.
	$wp_customize->add_setting(
		'lpc_opts[lpc-background-gcolor1]',
		array(
			'type'              => 'option',
			'default'           => ' #C3C4C7',
			'transport'         => 'postMessage',
			'sanitize_callback' => 'lpc_hex_rgba_sanitization',
		)
	);

	// Alpha Color Picker control.
	$wp_customize->add_control(
		new LPC_Alpha_Color_Control(
			$wp_customize,
			'lpc_opts[lpc-background-gcolor1]-control',
			array(
				'label'       => __( 'Gradient Color 1', 'customizer-login-page' ),
				'section'     => 'lpc-section-background',
				'settings'    => 'lpc_opts[lpc-background-gcolor1]',
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
	// Color 1 Gradient Percent.
	$wp_customize->add_setting(
		'lpc_opts[lpc-background-gcol1percent]',
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
			'lpc_opts[lpc-background-gcol1percent]-control',
			array(
				'label'       => esc_html__( 'Color 1 Percentage (%)', 'customizer-login-page' ),
				'settings'    => 'lpc_opts[lpc-background-gcol1percent]',
				'section'     => 'lpc-section-background',
				'input_attrs' => array(
					'min'  => 0, // Required. Minimum value for the slider.
					'max'  => 100, // Required. Maximum value for the slider.
					'step' => 1, // Required. The size of each interval or step the slider takes between the minimum and maximum values.
				),
			)
		)
	);

	// Gradient Color Alpha Color 2 setting.
	$wp_customize->add_setting(
		'lpc_opts[lpc-background-gcolor2]',
		array(
			'type'              => 'option',
			'default'           => ' #C3C4C7',
			'transport'         => 'postMessage',
			'sanitize_callback' => 'lpc_hex_rgba_sanitization',
		)
	);

	// Alpha Color Picker control.
	$wp_customize->add_control(
		new LPC_Alpha_Color_Control(
			$wp_customize,
			'lpc_opts[lpc-background-gcolor2]-control',
			array(
				'label'       => __( 'Gradient Color 2', 'customizer-login-page' ),
				'section'     => 'lpc-section-background',
				'settings'    => 'lpc_opts[lpc-background-gcolor2]',
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

	// Color 2 Gradient Percent.
	$wp_customize->add_setting(
		'lpc_opts[lpc-background-gcol2percent]',
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
			'lpc_opts[lpc-background-gcol2percent]-control',
			array(
				'label'       => esc_html__( 'Color 2 Percentage (%)', 'customizer-login-page' ),
				'settings'    => 'lpc_opts[lpc-background-gcol2percent]',
				'section'     => 'lpc-section-background',
				'input_attrs' => array(
					'min'  => 0, // Required. Minimum value for the slider.
					'max'  => 100, // Required. Maximum value for the slider.
					'step' => 1, // Required. The size of each interval or step the slider takes between the minimum and maximum values.
				),
			)
		)
	);


	// Gradient Angle.
	$wp_customize->add_setting(
		'lpc_opts[lpc-background-gangle]',
		array(
			'type'              => 'option',
			'default'           => '90',
			'transport'         => 'postMessage',
			'sanitize_callback' => 'lpc_sanitize_integer',
		)
	);
	$wp_customize->add_control(
		new lpc_Slider_Custom_Control(
			$wp_customize,
			'lpc_opts[lpc-background-gangle]-control',
			array(
				'label'       => esc_html__( 'Gradient Angle (Degree)', 'customizer-login-page' ),
				'settings'    => 'lpc_opts[lpc-background-gangle]',
				'section'     => 'lpc-section-background',
				'input_attrs' => array(
					'min'  => 0, // Required. Minimum value for the slider.
					'max'  => 360, // Required. Maximum value for the slider.
					'step' => 0.5, // Required. The size of each interval or step the slider takes between the minimum and maximum values.
				),
			)
		)
	);

	// Background heading control.
	$wp_customize->add_setting(
		'lpc-background-img-heading',
		array(
			'default'           => '',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);
	$wp_customize->add_control(
		new Lpc_Simple_Notice_Custom_control(
			$wp_customize,
			'lpc-background-img-heading-control',
			array(
				'label'    => __( 'Background Image Settings', 'customizer-login-page' ),
				'settings' => 'lpc-background-heading',
				'section'  => 'lpc-section-background',
			)
		)
	);
	// Enable Background Image.
	$wp_customize->add_setting(
		'lpc_opts[lpc-bg-img-enable]',
		array(
			'type'              => 'option',
			'default'           => 0,
			'transport'         => 'postMessage',
			'sanitize_callback' => 'lpc_switch_sanitization',
		)
	);
	$wp_customize->add_control(
		new Lpc_Toggle_Switch_Custom_control(
			$wp_customize,
			'lpc_opts[lpc-bg-img-enable]-control',
			array(
				'label'    => esc_html__( 'Display Background Image', 'customizer-login-page' ),
				'settings' => 'lpc_opts[lpc-bg-img-enable]',
				'section'  => 'lpc-section-background',
			)
		)
	);
	// Background Image core control.
	$wp_customize->add_setting(
		'lpc_opts[lpc-background-image]',
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
			'lpc_opts[lpc-background-image]-control',
			array(
				'label'         => __( 'Background Image', 'customizer-login-page' ),
				// 'description'   => esc_html__( 'Small png image is best suitable with less loading time.', 'customizer-login-page' ),
				'settings'      => 'lpc_opts[lpc-background-image]',
				'section'       => 'lpc-section-background',
				'button_labels' => array( // Optional.
					'select'       => __( 'Select Image' ),
					'change'       => __( 'Change Image' ),
					'remove'       => __( 'Remove' ),
					'default'      => __( 'Default' ),
					'placeholder'  => __( 'No image selected' ),
					'frame_title'  => __( 'Select Image' ),
					'frame_button' => __( 'Choose Image' ),
				),
				'input_attrs'   => array(
					'onclick' => 'event.preventDefault();',
				),
			)
		)
	);

	// Image Background repeat control.
	$wp_customize->add_setting(
		'lpc_opts[lpc-bg-image-repeat]',
		array(
			'type'              => 'option',
			'default'           => 'no-repeat',
			'transport'         => 'postMessage',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);

	$wp_customize->add_control(
		'lpc_opts[lpc-bg-image-repeat]-control',
		array(
			'label'    => __( 'Image Repeat', 'customizer-login-page' ),
			// 'description' => esc_html__( 'Sample description' ),
			'settings' => 'lpc_opts[lpc-bg-image-repeat]',
			'section'  => 'lpc-section-background',
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
		'lpc_opts[lpc-bg-image-size]',
		array(
			'type'              => 'option',
			'default'           => 'cover',
			'transport'         => 'postMessage',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);

	$wp_customize->add_control(
		'lpc_opts[lpc-bg-image-size]-control',
		array(
			'label'    => __( 'Image Size', 'customizer-login-page' ),
			'settings' => 'lpc_opts[lpc-bg-image-size]',
			'section'  => 'lpc-section-background',
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
		'lpc_opts[lpc-bg-image-position]',
		array(
			'type'              => 'option',
			'default'           => 'center',
			'transport'         => 'postMessage',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);

	$wp_customize->add_control(
		'lpc_opts[lpc-bg-image-position]-control',
		array(
			'label'    => __( 'Image Position', 'customizer-login-page' ),
			'settings' => 'lpc_opts[lpc-bg-image-position]',
			'section'  => 'lpc-section-background',
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

	// Heading Background Video.
	$wp_customize->add_setting(
		'lpc-background-video-heading',
		array(
			'default'           => '',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);
	$wp_customize->add_control(
		new Lpc_Simple_Notice_Custom_control(
			$wp_customize,
			'lpc-background-video-heading-control',
			array(
				'label'    => __( 'Background Video Settings', 'customizer-login-page' ),
				'settings' => 'lpc-background-video-heading',
				'section'  => 'lpc-section-background',
			)
		)
	);
	// Enable Background Video.
	$wp_customize->add_setting(
		'lpc_opts[lpc-bg-video-enable]',
		array(
			'type'              => 'option',
			'default'           => 0,
			'transport'         => 'postMessage',
			'sanitize_callback' => 'lpc_switch_sanitization',
		)
	);
	$wp_customize->add_control(
		new Lpc_Toggle_Switch_Custom_control(
			$wp_customize,
			'lpc_opts[lpc-bg-video-enable]-control',
			array(
				'label'    => esc_html__( 'Display Background Video', 'customizer-login-page' ),
				'settings' => 'lpc_opts[lpc-bg-video-enable]',
				'section'  => 'lpc-section-background',
			)
		)
	);
	// Background Video.
	$wp_customize->add_setting(
		'lpc_opts[lpc-background-video]',
		array(
			'type'              => 'option',
			'default'           => '',
			'transport'         => 'postMessage',
			'sanitize_callback' => 'absint',
		)
	);

	$wp_customize->add_control(
		new WP_Customize_Media_Control(
			$wp_customize,
			'lpc_opts[lpc-background-video]-control',
			array(
				'label'         => __( 'Background Video', 'customizer-login-page' ),
				// 'description'   => esc_html__( 'This is the description for the Media Control' ),
				'settings'      => 'lpc_opts[lpc-background-video]',
				'section'       => 'lpc-section-background',
				'mime_type'     => 'video',  // Required. Can be image, audio, video, application, text.
				'button_labels' => array( // Optional.
					'select'       => __( 'Select File' ),
					'change'       => __( 'Change File' ),
					'default'      => __( 'Default' ),
					'remove'       => __( 'Remove' ),
					'placeholder'  => __( 'No file selected' ),
					'frame_title'  => __( 'Select File' ),
					'frame_button' => __( 'Choose File' ),
				),
			)
		)
	);
	// Background Video Loop.
	$wp_customize->add_setting(
		'lpc_opts[lpc-bg-video-loop]',
		array(
			'type'              => 'option',
			'default'           => 1,
			'transport'         => 'postMessage',
			'sanitize_callback' => 'lpc_switch_sanitization',
		)
	);

	$wp_customize->add_control(
		'lpc_opts[lpc-bg-video-loop]-control',
		array(
			'label'    => __( 'Enable Video Loop ', 'customizer-login-page' ),
			'settings' => 'lpc_opts[lpc-bg-video-loop]',
			'section'  => 'lpc-section-background',
			'type'     => 'checkbox',
		)
	);
	// Background Video Mute.
	$wp_customize->add_setting(
		'lpc_opts[lpc-bg-video-mute]',
		array(
			'type'              => 'option',
			'default'           => 0,
			'transport'         => 'postMessage',
			'sanitize_callback' => 'lpc_switch_sanitization',
		)
	);

	$wp_customize->add_control(
		'lpc_opts[lpc-bg-video-mute]-control',
		array(
			'label'    => __( 'Mute Video ', 'customizer-login-page' ),
			'settings' => 'lpc_opts[lpc-bg-video-mute]',
			'section'  => 'lpc-section-background',
			'type'     => 'checkbox',
		)
	);
	// Video size control.
	$wp_customize->add_setting(
		'lpc_opts[lpc-bg-video-size]',
		array(
			'type'              => 'option',
			'default'           => 'cover',
			'transport'         => 'postMessage',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);

	$wp_customize->add_control(
		'lpc_opts[lpc-bg-video-size]-control',
		array(
			'label'    => __( 'Video Size', 'customizer-login-page' ),
			'settings' => 'lpc_opts[lpc-bg-video-size]',
			'section'  => 'lpc-section-background',
			'type'     => 'select',
			'choices'  => array( // Optional.
				'fill'       => __( 'Fill' ),
				'cover'      => __( 'Cover' ),
				'contain'    => __( 'Contain' ),
				'scale-down' => __( 'Scale-Down' ),
				'none'       => __( 'None' ),
			),
		)
	);
	// Video position control.
	$wp_customize->add_setting(
		'lpc_opts[lpc-bg-video-position]',
		array(
			'type'              => 'option',
			'default'           => '50% 50%',
			'transport'         => 'postMessage',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);

	$wp_customize->add_control(
		'lpc_opts[lpc-bg-video-position]-control',
		array(
			'label'       => __( 'Video Position', 'customizer-login-page' ),
			'settings'    => 'lpc_opts[lpc-bg-video-position]',
			'section'     => 'lpc-section-background',
			'type'        => 'text',
			'input_attrs' => array( // Optional.
				'placeholder' => '50% 50%',
			),
		)
	);
	/** End Section : Login Background  */
