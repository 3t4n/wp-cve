<?php
$wp_customize->add_section(
	'lpc-section-exportimport',
	array(
		'title'       => __( 'Export/Import', 'customizer-login-page' ),
		'description' => __( 'Export or Import the Customizer Login Page settings.', 'customizer-login-page' ),
		'panel'       => 'lpc-main-panel',
	)
);
	// Export/Import heading control.
	$wp_customize->add_setting(
		'lpc-exportimport-heading',
		array(
			'default'           => '',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);
	$wp_customize->add_control(
		new Lpc_Simple_Notice_Custom_control(
			$wp_customize,
			'lpc-exportimport-heading-control',
			array(
				'label'    => __( 'Export/Import Customizer Login Page settings', 'customizer-login-page' ),
				'settings' => 'lpc-exportimport-heading',
				'section'  => 'lpc-section-exportimport',
			)
		)
	);
	$wp_customize->add_setting(
		'lpc-export-import', // Just a placeholder setting. This won't hold real data.
		array(
			'default'           => '',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);

	$wp_customize->add_control(
		new LPC_Export_Import_Control(
			$wp_customize,
			'lpc-export-import-control',
			array(
				'label'    => __( 'Export/Import Settings', 'customizer-login-page' ),
				'section'  => 'lpc-section-exportimport',
				'settings' => 'lpc-export-import',
			)
		)
	);
