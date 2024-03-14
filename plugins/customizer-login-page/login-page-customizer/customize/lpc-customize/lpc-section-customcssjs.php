<?php
$wp_customize->add_section(
	'lpc-section-customcssjs',
	array(
		'title'       => __( 'Custom CSS/JS', 'customizer-login-page' ),
		'description' => __( 'Add your custom CSS/JS if required', 'customizer-login-page' ),
		'panel'       => 'lpc-main-panel',
	)
);

// customcssjs heading control.
$wp_customize->add_setting(
	'lpc-customcssjs-heading',
	array(
		'default'           => '',
		'sanitize_callback' => 'sanitize_text_field',
	)
);
$wp_customize->add_control(
	new Lpc_Simple_Notice_Custom_control(
		$wp_customize,
		'lpc-customcssjs-heading-control',
		array(
			'label'    => __( 'Note: CSS / JS update require full Reload after Publish', 'customizer-login-page' ),
			'settings' => 'lpc-customcssjs-heading',
			'section'  => 'lpc-section-customcssjs',
		)
	)
);

// Custom CSS.
$wp_customize->add_setting(
	'lpc_opts[lpc-customcss]',
	array(
		'type'              => 'option',
		'default'           => '',
		'transport'         => 'postMessage',
		'sanitize_callback' => 'sanitize_text_field',
	)
);
$wp_customize->add_control(
	new Lpc_Custom_Textarea_Control(
		$wp_customize,
		'lpc_opts[lpc-customcss]-control',
		array(
			'label'       => __( 'Custom Css', 'customizer-login-page' ),
			// 'description' => __( "Paste your custom CSS without <style> tag. [ **Required reload after publish ].... Here's some helpful, highly specific CSS selectors for the login page:\nbody.login {}\nbody.login div#login {}\nbody.login div#login h1 {}\nbody.login div#login h1 a {}\nbody.login div#login form#loginform {}\nbody.login div#login form#loginform p {}\nbody.login div#login form#loginform p label {}\nbody.login div#login form#loginform input {}\nbody.login div#login form#loginform input#user_login {}\nbody.login div#login form#loginform input#user_pass {}\nbody.login div#login form#loginform p.forgetmenot {}\nbody.login div#login form#loginform p.forgetmenot input#rememberme {}\nbody.login div#login form#loginform p.submit {}\nbody.login div#login form#loginform p.submit input#wp-submit {}\nbody.login div#login p#nav {}\nbody.login div#login p#nav a {}\nbody.login div#login p#backtoblog {}\nbody.login div#login p#backtoblog a {}", 'customizer-login-page' ),
			'description' => __(
				'Paste your custom CSS without style tag. [ **Required reload after publish ]<br>' .
				"Here's some helpful, highly specific CSS selectors for the login page:<br>" .
				'body.login {}<br>' .
				'body.login div#login<br>' .
				'body.login div#login h1<br>' .
				'body.login div#login h1 a<br>' .
				'body.login div#login form#loginform<br>' .
				'body.login div#login form#loginform p<br>' .
				'body.login div#login form#loginform p label<br>' .
				'body.login div#login form#loginform input<br>' .
				'body.login div#login form#loginform input#user_login<br>' .
				'body.login div#login form#loginform input#user_pass<br>' .
				'body.login div#login form#loginform p.forgetmenot<br>' .
				'body.login div#login form#loginform p.submit<br>' .
				'body.login div#login form#loginform p.submit input#wp-submit<br>' .
				'body.login div#login p#nav<br>' .
				'body.login div#login p#nav a<br>' .
				'body.login div#login p#backtoblog<br>' .
				'body.login div#login p#backtoblog a',
				'customizer-login-page'
			),
			'settings'    => 'lpc_opts[lpc-customcss]',
			'section'     => 'lpc-section-customcssjs',
			'type'        => 'text',
			'input_attrs' => array(
				'rows' => '5',
			),
		)
	)
);

// Custom JS.
$wp_customize->add_setting(
	'lpc_opts[lpc-customjs]',
	array(
		'type'              => 'option',
		'default'           => '',
		'transport'         => 'postMessage',
		'sanitize_callback' => 'sanitize_text_field',
	)
);
$wp_customize->add_control(
	new Lpc_Custom_Textarea_Control(
		$wp_customize,
		'lpc_opts[lpc-customjs]-control',
		array(
			'label'       => __( 'Custom Js', 'customizer-login-page' ),
			'description' => __( 'Paste your custom JS without <script> tag. [ **Required reload after publish ]', 'customizer-login-page' ),
			'settings'    => 'lpc_opts[lpc-customjs]',
			'section'     => 'lpc-section-customcssjs',
			'type'        => 'text',
			'input_attrs' => array(
				'rows' => '5',
			),
		)
	)
);
