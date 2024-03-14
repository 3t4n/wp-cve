<?php
/** Start : Section Messages Modify */
$wp_customize->add_section(
	'lpc-section-messages',
	array(
		'title' => __( 'Messages Modify', 'customizer-login-page' ),
		'panel' => 'lpc-main-panel',
	)
);
	// Message Modify heading control.
	$wp_customize->add_setting(
		'lpc-messages-heading',
		array(
			'default'           => '',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);
	$wp_customize->add_control(
		new Lpc_Simple_Notice_Custom_control(
			$wp_customize,
			'lpc-messages-heading-control',
			array(
				'label'    => __( 'Messages Settings {* Required Refresh *}', 'customizer-login-page' ),
				'settings' => 'lpc-messages-heading',
				'section'  => 'lpc-section-messages',
			)
		)
	);
	// Enable Login Message.
	$wp_customize->add_setting(
		'lpc_opts[lpc-enable-login-message]',
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
			'lpc_opts[lpc-enable-login-message]-control',
			array(
				'label'       => esc_html__( 'Enable Welcome Message', 'customizer-login-page' ),
				'description' => esc_html__( 'Default: Disabled', 'customizer-login-page' ),
				'settings'    => 'lpc_opts[lpc-enable-login-message]',
				'section'     => 'lpc-section-messages',
			)
		)
	);
	// Login Message.
	$wp_customize->add_setting(
		'lpc_opts[lpc-messages-login]',
		array(
			'type'              => 'option',
			'default'           => 'Welcome back! Please log in to your account.',
			'transport'         => 'postMessage',
			'sanitize_callback' => 'sanitize_textarea_field',
		)
	);
	$wp_customize->add_control(
		new Lpc_Custom_Textarea_Control(
			$wp_customize,
			'lpc_opts[lpc-messages-login]-control',
			array(
				'label'       => esc_html__( 'Login Welcome Message', 'customizer-login-page' ),
				'description' => esc_html__( 'This will replace default message on login page. Leave it blank if you dont want to change default msg.', 'customizer-login-page' ),
				'settings'    => 'lpc_opts[lpc-messages-login]',
				'section'     => 'lpc-section-messages',
				'input_attrs' => array(
					'placeholder' => esc_html__( 'Welcome back! Please log in to your account.', 'customizer-login-page' ),
				),
			)
		)
	);

	// Logout Message.
	$wp_customize->add_setting(
		'lpc_opts[lpc-messages-logout]',
		array(
			'type'              => 'option',
			'default'           => 'You are now logged out.',
			'transport'         => 'postMessage',
			'sanitize_callback' => 'sanitize_textarea_field',
		)
	);
	$wp_customize->add_control(
		new Lpc_Custom_Textarea_Control(
			$wp_customize,
			'lpc_opts[lpc-messages-logout]-control',
			array(
				'label'       => esc_html__( 'Modify Logout Message', 'customizer-login-page' ),
				'description' => esc_html__( 'This will replace default message on logout page. Leave it blank if you dont want to change default msg.', 'customizer-login-page' ),
				'settings'    => 'lpc_opts[lpc-messages-logout]',
				'section'     => 'lpc-section-messages',
				'input_attrs' => array(
					'placeholder' => esc_html__( 'You are now logged out.', 'customizer-login-page' ),
				),
			)
		)
	);
	// Lost Password Message.
	$wp_customize->add_setting(
		'lpc_opts[lpc-messages-lostpassword]',
		array(
			'type'              => 'option',
			'default'           => 'Please enter your username or email address. You will receive an email message with instructions on how to reset your password.',
			'transport'         => 'postMessage',
			'sanitize_callback' => 'sanitize_textarea_field',
		)
	);
	$wp_customize->add_control(
		new Lpc_Custom_Textarea_Control(
			$wp_customize,
			'lpc_opts[lpc-messages-lostpassword]-control',
			array(
				'label'       => esc_html__( 'Modify Lost Password Message', 'customizer-login-page' ),
				'description' => esc_html__( 'This will replace default message on lost password page. Leave it blank if you dont want to change default msg.', 'customizer-login-page' ),
				'settings'    => 'lpc_opts[lpc-messages-lostpassword]',
				'section'     => 'lpc-section-messages',
				'input_attrs' => array(
					'placeholder' => esc_html__( 'Please enter your username or email address. You will receive an email message with instructions on how to reset your password.', 'customizer-login-page' ),
				),
			)
		)
	);
	// Register Message.
	$wp_customize->add_setting(
		'lpc_opts[lpc-messages-register]',
		array(
			'type'              => 'option',
			'default'           => 'Register For This Site.',
			'transport'         => 'postMessage',
			'sanitize_callback' => 'sanitize_textarea_field',
		)
	);
	$wp_customize->add_control(
		new Lpc_Custom_Textarea_Control(
			$wp_customize,
			'lpc_opts[lpc-messages-register]-control',
			array(
				'label'       => esc_html__( 'Modify Register Message', 'customizer-login-page' ),
				'description' => esc_html__( 'This will replace default message on register page. Leave it blank if you dont want to change default msg.', 'customizer-login-page' ),
				'settings'    => 'lpc_opts[lpc-messages-register]',
				'section'     => 'lpc-section-messages',
				'input_attrs' => array(
					'placeholder' => esc_html__( 'Register For This Site.', 'customizer-login-page' ),
				),
			)
		)
	);
	/** End : Section Messages Modify */
