<?php
/** Section : Logo Start. */
$wp_customize->add_section(
	'lpc-section-logo',
	array(
		'title' => __( 'Logo Settings', 'customizer-login-page' ),
		'panel' => 'lpc-main-panel',
	)
);

// heading control.
$wp_customize->add_setting(
	'lpc-logo-heading',
	array(
		'default'           => '',
		'sanitize_callback' => 'sanitize_text_field',
	)
);
$wp_customize->add_control(
	new Lpc_Simple_Notice_Custom_control(
		$wp_customize,
		'lpc-logo-heading-control',
		array(
			'label'    => __( 'Logo Settings', 'customizer-login-page' ),
			'settings' => 'lpc-logo-heading',
			'section'  => 'lpc-section-logo',
		)
	)
);
// Enable/Disable Logo.
$wp_customize->add_setting(
	'lpc_opts[lpc-logo-enable]',
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
		'lpc_opts[lpc-logo-enable]-control',
		array(
			'label'    => esc_html__( 'Display logo', 'customizer-login-page' ),
			'settings' => 'lpc_opts[lpc-logo-enable]',
			'section'  => 'lpc-section-logo',
		)
	)
);
// logo Image core control.
$wp_customize->add_setting(
	'lpc_opts[lpc-logo-image]',
	array(
		'type'              => 'option',
		'default'           => LOGINPC_ADMIN_URL . 'images/wordpress-logo.svg',
		'transport'         => 'postMessage',
		'sanitize_callback' => 'esc_url_raw',
	)
);

$wp_customize->add_control(
	new WP_Customize_Image_Control(
		$wp_customize,
		'lpc_opts[lpc-logo-image]-control',
		array(
			'label'         => __( 'Logo Image', 'customizer-login-page' ),
			'description'   => esc_html__( 'Small png image is best suitable with less loading time. To remove default wordpress image disable logo', 'customizer-login-page' ),
			'settings'      => 'lpc_opts[lpc-logo-image]',
			'section'       => 'lpc-section-logo',
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

// Logo Height Slider Range.
$wp_customize->add_setting(
	'lpc_opts[lpc-logo-height]',
	array(
		'type'              => 'option',
		'default'           => '65',
		'transport'         => 'postMessage',
		'sanitize_callback' => 'lpc_sanitize_integer',
	)
);
$wp_customize->add_control(
	new lpc_Slider_Custom_Control(
		$wp_customize,
		'lpc_opts[lpc-logo-height]-control',
		array(
			'label'       => esc_html__( 'Logo Height (px)', 'customizer-login-page' ),
			'description' => esc_html__( 'Logo height will not go beyond uploaded image height and is related to Logo width for maintaining the aspect ratio of logo.', 'customizer-login-page' ),
			'settings'    => 'lpc_opts[lpc-logo-height]',
			'section'     => 'lpc-section-logo',
			'input_attrs' => array(
				'min'  => 10, // Required. Minimum value for the slider.
				'max'  => 500, // Required. Maximum value for the slider.
				'step' => 1, // Required. The size of each interval or step the slider takes between the minimum and maximum values.
			),
		)
	)
);
// Logo Width Slider Range.
$wp_customize->add_setting(
	'lpc_opts[lpc-logo-width]',
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
		'lpc_opts[lpc-logo-width]-control',
		array(
			'label'       => esc_html__( 'Logo Width (px)', 'customizer-login-page' ),
			'description' => esc_html__( 'Logo width is also related to Logo Height for maintaining the aspect ratio of logo.', 'customizer-login-page' ),
			'settings'    => 'lpc_opts[lpc-logo-width]',
			'section'     => 'lpc-section-logo',
			'input_attrs' => array(
				'min'  => 10, // Required. Minimum value for the slider.
				'max'  => 500, // Required. Maximum value for the slider.
				'step' => 1, // Required. The size of each interval or step the slider takes between the minimum and maximum values.
			),
		)
	)
);
// Logo Padding Slider Range.
$wp_customize->add_setting(
	'lpc_opts[lpc-logo-padding]',
	array(
		'type'              => 'option',
		'default'           => '30',
		'transport'         => 'postMessage',
		'sanitize_callback' => 'lpc_sanitize_integer',
	)
);
$wp_customize->add_control(
	new lpc_Slider_Custom_Control(
		$wp_customize,
		'lpc_opts[lpc-logo-padding]-control',
		array(
			'label'       => esc_html__( 'Logo Padding (px)', 'customizer-login-page' ),
			'settings'    => 'lpc_opts[lpc-logo-padding]',
			'section'     => 'lpc-section-logo',
			'input_attrs' => array(
				'min'  => 0, // Required. Minimum value for the slider.
				'max'  => 500, // Required. Maximum value for the slider.
				'step' => 1, // Required. The size of each interval or step the slider takes between the minimum and maximum values.
			),
		)
	)
);
// Logo Top Margin Slider Range.
$wp_customize->add_setting(
	'lpc_opts[lpc-logo-margin-top]',
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
		'lpc_opts[lpc-logo-margin-top]-control',
		array(
			'label'       => esc_html__( 'Logo Margin Top (px)', 'customizer-login-page' ),
			'settings'    => 'lpc_opts[lpc-logo-margin-top]',
			'section'     => 'lpc-section-logo',
			'input_attrs' => array(
				'min'  => 0, // Required. Minimum value for the slider.
				'max'  => 200, // Required. Maximum value for the slider.
				'step' => 1, // Required. The size of each interval or step the slider takes between the minimum and maximum values.
			),
		)
	)
);
// Logo Bottom Margin Slider Range.
$wp_customize->add_setting(
	'lpc_opts[lpc-logo-margin-bottom]',
	array(
		'type'              => 'option',
		'default'           => '25',
		'transport'         => 'postMessage',
		'sanitize_callback' => 'lpc_sanitize_integer',
	)
);
$wp_customize->add_control(
	new lpc_Slider_Custom_Control(
		$wp_customize,
		'lpc_opts[lpc-logo-margin-bottom]-control',
		array(
			'label'       => esc_html__( 'Logo Margin Bottom (px)', 'customizer-login-page' ),
			'settings'    => 'lpc_opts[lpc-logo-margin-bottom]',
			'section'     => 'lpc-section-logo',
			'input_attrs' => array(
				'min'  => 0, // Required. Minimum value for the slider.
				'max'  => 200, // Required. Maximum value for the slider.
				'step' => 1, // Required. The size of each interval or step the slider takes between the minimum and maximum values.
			),
		)
	)
);

// Logo Target URL.
$wp_customize->add_setting(
	'lpc_opts[lpc-logo-link]',
	array(
		'type'              => 'option',
		'default'           => '',
		'transport'         => 'postMessage',
		'sanitize_callback' => 'esc_url_raw',
	)
);

$wp_customize->add_control(
	'lpc_opts[lpc-logo-link]-control',
	array(
		'label'       => __( 'Logo Link/URL', 'customizer-login-page' ),
		'description' => esc_html__( 'Link or Url to be opened on click of logo', 'customizer-login-page' ),
		'settings'    => 'lpc_opts[lpc-logo-link]',
		'section'     => 'lpc-section-logo',
		'type'        => 'url',
		'input_attrs' => array( // Optional.
			'placeholder' => 'https://wordpress.org',
		),
	)
);

// Logo Position.
$wp_customize->add_setting(
	'lpc_opts[lpc-logo-position-x]',
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
		'lpc_opts[lpc-logo-position-x]-control',
		array(
			'label'       => esc_html__( 'Logo Position X Axis', 'customizer-login-page' ),
			'settings'    => 'lpc_opts[lpc-logo-position-x]',
			'section'     => 'lpc-section-logo',
			'input_attrs' => array(
				'min'  => -100, // Required. Minimum value for the slider.
				'max'  => 100, // Required. Maximum value for the slider.
				'step' => 1, // Required. The size of each interval or step the slider takes between the minimum and maximum values.
			),
		)
	)
);
/** Logo section End. */
