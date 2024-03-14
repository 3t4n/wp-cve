<?php
/** Start : Section Errors Modify */
$wp_customize->add_section(
	'lpc-section-errors',
	array(
		'title' => __( 'Errors Modify', 'customizer-login-page' ),
		'panel' => 'lpc-main-panel',
	)
);
	// Errors heading control.
	$wp_customize->add_setting(
		'lpc-login-errors-heading',
		array(
			'default'           => '',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);
	$wp_customize->add_control(
		new Lpc_Simple_Notice_Custom_control(
			$wp_customize,
			'lpc-login-errors-heading-control',
			array(
				'label'    => __( 'Login From Errors {* Required Refresh *}', 'customizer-login-page' ),
				'settings' => 'lpc-login-errors-heading',
				'section'  => 'lpc-section-errors',
			)
		)
	);
	// Error for empty_username.
	$wp_customize->add_setting(
		'lpc_opts[lpc-error-login-empty-username]',
		array(
			'type'              => 'option',
			'default'           => '',
			'transport'         => 'postMessage',
			'sanitize_callback' => 'lpc_sanitize_custom_error',
		)
	);
	$wp_customize->add_control(
		new Lpc_Custom_Textarea_Control(
			$wp_customize,
			'lpc_opts[lpc-error-login-empty-username]-control',
			array(
				'label'       => __( 'Modify Empty Username Error Msg', 'customizer-login-page' ),
				'description' => esc_html__( 'This will replace default error on empty username. Leave it blank if you dont want to change default.', 'customizer-login-page' ),
				'settings'    => 'lpc_opts[lpc-error-login-empty-username]',
				'section'     => 'lpc-section-errors',
				'type'        => 'text',
				'input_attrs' => array(
					'placeholder' => esc_html__( '<strong>Error:</strong> The username field is empty.', 'customizer-login-page' ),
					'rows'        => '2',
				),
			)
		)
	);
	// Error for empty_password.
	$wp_customize->add_setting(
		'lpc_opts[lpc-error-login-empty-password]',
		array(
			'type'              => 'option',
			'default'           => '',
			'transport'         => 'postMessage',
			'sanitize_callback' => 'lpc_sanitize_custom_error',
		)
	);
	$wp_customize->add_control(
		new Lpc_Custom_Textarea_Control(
			$wp_customize,
			'lpc_opts[lpc-error-login-empty-password]-control',
			array(
				'label'       => __( 'Modify Empty Password Error Msg', 'customizer-login-page' ),
				'description' => esc_html__( 'This will replace default error on empty password. Leave it blank if you dont want to change default.', 'customizer-login-page' ),
				'settings'    => 'lpc_opts[lpc-error-login-empty-password]',
				'section'     => 'lpc-section-errors',
				'type'        => 'text',
				'input_attrs' => array(
					'placeholder' => esc_html__( '<strong>Error:</strong> The password field is empty.', 'customizer-login-page' ),
					'rows'        => '2',
				),
			)
		)
	);
	// Error for invalid_username.
	$wp_customize->add_setting(
		'lpc_opts[lpc-error-login-invalid-username]',
		array(
			'type'              => 'option',
			'default'           => '',
			'transport'         => 'postMessage',
			'sanitize_callback' => 'lpc_sanitize_custom_error',
		)
	);
	$wp_customize->add_control(
		new Lpc_Custom_Textarea_Control(
			$wp_customize,
			'lpc_opts[lpc-error-login-invalid-username]-control',
			array(
				'label'       => __( 'Modify Invalid Username Error Msg', 'customizer-login-page' ),
				'description' => esc_html__( 'This will replace default error on Invalid Username. Leave it blank if you dont want to change default.', 'customizer-login-page' ),
				'settings'    => 'lpc_opts[lpc-error-login-invalid-username]',
				'section'     => 'lpc-section-errors',
				'type'        => 'text',
				'input_attrs' => array(
					'placeholder' => esc_html__( '<strong>Error:</strong> The username is not registered on this site. If you are unsure of your username, try your email address instead.', 'customizer-login-page' ),
					'rows'        => '2',
				),
			)
		)
	);
	// Error for incorrect_password.
	$wp_customize->add_setting(
		'lpc_opts[lpc-error-login-incorrect-password]',
		array(
			'type'              => 'option',
			'default'           => '',
			'transport'         => 'postMessage',
			'sanitize_callback' => 'lpc_sanitize_custom_error',
		)
	);
	$wp_customize->add_control(
		new Lpc_Custom_Textarea_Control(
			$wp_customize,
			'lpc_opts[lpc-error-login-incorrect-password]-control',
			array(
				'label'       => __( 'Modify Incorrect Password Error Msg', 'customizer-login-page' ),
				'description' => esc_html__( 'This will replace default error on Incorrect Password. Leave it blank if you dont want to change default.', 'customizer-login-page' ),
				'settings'    => 'lpc_opts[lpc-error-login-incorrect-password]',
				'section'     => 'lpc-section-errors',
				'type'        => 'text',
				'input_attrs' => array(
					'placeholder' => esc_html__( '<strong>Error:</strong> The password you entered for the username is incorrect.', 'customizer-login-page' ),
					'rows'        => '5',
				),
			)
		)
	);
	// Error for invalid_email.
	$wp_customize->add_setting(
		'lpc_opts[lpc-error-login-invalid-email]',
		array(
			'type'              => 'option',
			'default'           => '',
			'transport'         => 'postMessage',
			'sanitize_callback' => 'lpc_sanitize_custom_error',
		)
	);
	$wp_customize->add_control(
		new Lpc_Custom_Textarea_Control(
			$wp_customize,
			'lpc_opts[lpc-error-login-invalid-email]-control',
			array(
				'label'       => __( 'Modify Invalid Email Error Msg', 'customizer-login-page' ),
				'description' => esc_html__( 'This will replace default error on Invalid Email. Leave it blank if you dont want to change default.', 'customizer-login-page' ),
				'settings'    => 'lpc_opts[lpc-error-login-invalid-email]',
				'section'     => 'lpc-section-errors',
				'type'        => 'text',
				'input_attrs' => array(
					'placeholder' => esc_html__( 'Unknown email address. Check again or try your username.', 'customizer-login-page' ),
					'rows'        => '2',
				),
			)
		)
	);
	// Register Errors heading control.
	$wp_customize->add_setting(
		'lpc-register-errors-heading',
		array(
			'default'           => '',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);
	$wp_customize->add_control(
		new Lpc_Simple_Notice_Custom_control(
			$wp_customize,
			'lpc-register-errors-heading-control',
			array(
				'label'    => __( 'Register form Errors {* Required Refresh *}', 'customizer-login-page' ),
				'settings' => 'lpc-register-errors-heading',
				'section'  => 'lpc-section-errors',
			)
		)
	);
	// Register Error for empty_username.
	$wp_customize->add_setting(
		'lpc_opts[lpc-error-register-empty-username]',
		array(
			'type'              => 'option',
			'default'           => '',
			'transport'         => 'postMessage',
			'sanitize_callback' => 'lpc_sanitize_custom_error',
		)
	);
	$wp_customize->add_control(
		new Lpc_Custom_Textarea_Control(
			$wp_customize,
			'lpc_opts[lpc-error-register-empty-username]-control',
			array(
				'label'       => __( 'Register form Empty Username', 'customizer-login-page' ),
				'description' => esc_html__( 'This will replace default error on Register form Empty User. Leave it blank if you dont want to change default.', 'customizer-login-page' ),
				'settings'    => 'lpc_opts[lpc-error-register-empty-username]',
				'section'     => 'lpc-section-errors',
				'type'        => 'text',
				'input_attrs' => array(
					'placeholder' => esc_html__( '<strong>Error:</strong> Please enter a username.', 'customizer-login-page' ),
					'rows'        => '2',
				),
			)
		)
	);
	// Register Error for empty_email.
	$wp_customize->add_setting(
		'lpc_opts[lpc-error-register-empty-email]',
		array(
			'type'              => 'option',
			'default'           => '',
			'transport'         => 'postMessage',
			'sanitize_callback' => 'lpc_sanitize_custom_error',
		)
	);
	$wp_customize->add_control(
		new Lpc_Custom_Textarea_Control(
			$wp_customize,
			'lpc_opts[lpc-error-register-empty-email]-control',
			array(
				'label'       => __( 'Register form Empty Email', 'customizer-login-page' ),
				'description' => esc_html__( 'This will replace default error on Register form Empty Email. Leave it blank if you dont want to change default.', 'customizer-login-page' ),
				'settings'    => 'lpc_opts[lpc-error-register-empty-email]',
				'section'     => 'lpc-section-errors',
				'type'        => 'text',
				'input_attrs' => array(
					'placeholder' => esc_html__( '<strong>Error:</strong> Please type your email address.', 'customizer-login-page' ),
					'rows'        => '2',
				),
			)
		)
	);
	// Register Error for invalid_username.
	$wp_customize->add_setting(
		'lpc_opts[lpc-error-register-invalid-username]',
		array(
			'type'              => 'option',
			'default'           => '',
			'transport'         => 'postMessage',
			'sanitize_callback' => 'lpc_sanitize_custom_error',
		)
	);
	$wp_customize->add_control(
		new Lpc_Custom_Textarea_Control(
			$wp_customize,
			'lpc_opts[lpc-error-register-invalid-username]-control',
			array(
				'label'       => __( 'Register form Invalid Username', 'customizer-login-page' ),
				'description' => esc_html__( 'This will replace default error on Register form Invalid Username. Leave it blank if you dont want to change default.', 'customizer-login-page' ),
				'settings'    => 'lpc_opts[lpc-error-register-invalid-username]',
				'section'     => 'lpc-section-errors',
				'input_attrs' => array(
					'placeholder' => esc_html__( '<strong>Error:</strong> This username is invalid because it uses illegal characters. Please enter a valid username.', 'customizer-login-page' ),
					'rows'        => '3',
				),
			)
		)
	);
	// Register Error for username_exists.
	$wp_customize->add_setting(
		'lpc_opts[lpc-error-register-username-exists]',
		array(
			'type'              => 'option',
			'default'           => '',
			'transport'         => 'postMessage',
			'sanitize_callback' => 'lpc_sanitize_custom_error',
		)
	);
	$wp_customize->add_control(
		new Lpc_Custom_Textarea_Control(
			$wp_customize,
			'lpc_opts[lpc-error-register-username-exists]-control',
			array(
				'label'       => __( 'Register form Username Exists', 'customizer-login-page' ),
				'description' => esc_html__( 'This will replace default error on Register form Username Exists. Leave it blank if you don\'t want to change default.', 'customizer-login-page' ),
				'settings'    => 'lpc_opts[lpc-error-register-username-exists]',
				'section'     => 'lpc-section-errors',
				'input_attrs' => array(
					'placeholder' => esc_html__( '<strong>Error:</strong> This username is already registered. Please choose another one..', 'customizer-login-page' ),
					'rows'        => '2',
				),
			)
		)
	);
	// Register Error for invalid_email.
	$wp_customize->add_setting(
		'lpc_opts[lpc-error-register-invalid-email]',
		array(
			'type'              => 'option',
			'default'           => '',
			'transport'         => 'postMessage',
			'sanitize_callback' => 'lpc_sanitize_custom_error',
		)
	);
	$wp_customize->add_control(
		new Lpc_Custom_Textarea_Control(
			$wp_customize,
			'lpc_opts[lpc-error-register-invalid-email]-control',
			array(
				'label'       => __( 'Register form Invalid Email', 'customizer-login-page' ),
				'description' => esc_html__( 'This will replace default error on Register form Invalid Email. Leave it blank if you don\'t want to change default.', 'customizer-login-page' ),
				'settings'    => 'lpc_opts[lpc-error-register-invalid-email]',
				'section'     => 'lpc-section-errors',
				'input_attrs' => array(
					'placeholder' => esc_html__( '<strong>Error:</strong> The email address is not correct.', 'customizer-login-page' ),
					'rows'        => '2',
				),
			)
		)
	);
	// Register Error for email_exists.
	$wp_customize->add_setting(
		'lpc_opts[lpc-error-register-email-exists]',
		array(
			'type'              => 'option',
			'default'           => '',
			'transport'         => 'postMessage',
			'sanitize_callback' => 'lpc_sanitize_custom_error',
		)
	);
	$wp_customize->add_control(
		new Lpc_Custom_Textarea_Control(
			$wp_customize,
			'lpc_opts[lpc-error-register-email-exists]-control',
			array(
				'label'       => __( 'Register form Email Exists', 'customizer-login-page' ),
				'description' => esc_html__( 'This will replace default error on Register form Email Exists. Leave it blank if you don\'t want to change default.', 'customizer-login-page' ),
				'settings'    => 'lpc_opts[lpc-error-register-email-exists]',
				'section'     => 'lpc-section-errors',
				'input_attrs' => array(
					'placeholder' => esc_html__( '<strong>Error:</strong> Error: This email address is already registered.Log in with this address or choose another one', 'customizer-login-page' ),
					'rows'        => '4',
				),
			)
		)
	);
	// Lost Errors heading control.
	$wp_customize->add_setting(
		'lpc-errors-lost-heading',
		array(
			'default'           => '',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);
	$wp_customize->add_control(
		new Lpc_Simple_Notice_Custom_control(
			$wp_customize,
			'lpc-errors-lost-heading-control',
			array(
				'label'    => __( 'Lost form Errors {* Required Refresh *}', 'customizer-login-page' ),
				'settings' => 'lpc-errors-lost-heading',
				'section'  => 'lpc-section-errors',
			)
		)
	);
	// Lost Password Error for empty_username.
	$wp_customize->add_setting(
		'lpc_opts[lpc-errors-lost-empty-username]',
		array(
			'type'              => 'option',
			'default'           => '',
			'transport'         => 'postMessage',
			'sanitize_callback' => 'lpc_sanitize_custom_error',
		)
	);
	$wp_customize->add_control(
		new Lpc_Custom_Textarea_Control(
			$wp_customize,
			'lpc_opts[lpc-errors-lost-empty-username]-control',
			array(
				'label'       => __( 'Lost Password Empty Field', 'customizer-login-page' ),
				'description' => esc_html__( 'This will replace default error on Lost Password form when field is Empty. Leave it blank if you dont want to change default.', 'customizer-login-page' ),
				'settings'    => 'lpc_opts[lpc-errors-lost-empty-username]',
				'section'     => 'lpc-section-errors',
				'input_attrs' => array(
					'placeholder' => esc_html__( '<strong>Error:</strong> Please enter a username or email address.', 'customizer-login-page' ),
					'rows'        => '2',
				),
			)
		)
	);

	// Lost Password Error for invalid_email.
	$wp_customize->add_setting(
		'lpc_opts[lpc-error-lost-invalid-email]',
		array(
			'type'              => 'option',
			'default'           => '',
			'transport'         => 'postMessage',
			'sanitize_callback' => 'lpc_sanitize_custom_error',
		)
	);
	$wp_customize->add_control(
		new Lpc_Custom_Textarea_Control(
			$wp_customize,
			'lpc_opts[lpc-error-lost-invalid-email]-control',
			array(
				'label'       => __( 'Lost Password Invalid Email', 'customizer-login-page' ),
				'description' => esc_html__( 'This will replace default error on Lost Password form for Invalid Email. Leave it blank if you don\'t want to change default.', 'customizer-login-page' ),
				'settings'    => 'lpc_opts[lpc-error-lost-invalid-email]',
				'section'     => 'lpc-section-errors',
				'input_attrs' => array(
					'placeholder' => esc_html__( '<strong>Error:</strong> Invalid email address.', 'customizer-login-page' ),
					'rows'        => '2',
				),
			)
		)
	);

	// // Lost Password Error for invalidcombo.
	// $wp_customize->add_setting(
	// 'lpc-error-lost-invalidcombo',
	// array(
	// 'default'           => '',
	// 'transport'         => 'postMessage',
	// 'sanitize_callback' => 'lpc_sanitize_custom_error',
	// )
	// );
	// $wp_customize->add_control(
	// new Lpc_Custom_Textarea_Control(
	// $wp_customize,
	// 'lpc-error-lost-invalidcombo-control',
	// array(
	// 'label'       => __( 'Lost Password Invalid Combo', 'customizer-login-page' ),
	// 'description' => esc_html__( 'This will replace default error on Lost Password form for Invalid Username/Email combination. Leave it blank if you don\'t want to change default.', 'customizer-login-page' ),
	// 'settings'    => 'lpc-error-lost-invalidcombo',
	// 'section'     => 'lpc-section-errors',
	// 'input_attrs' => array(
	// 'placeholder' => esc_html__( '<strong>Error:</strong> There is no account with that username or email address.', 'customizer-login-page' ),
	// 'rows'        => '2',
	// ),
	// )
	// )
	// );
	/** End : Section Errors Modify */
