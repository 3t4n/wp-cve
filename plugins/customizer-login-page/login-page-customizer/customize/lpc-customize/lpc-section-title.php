<?php
/** Section : Login page Title  */
		$wp_customize->add_section(
			'lpc-section-title',
			array(
				'title' => __( 'Login Page Title', 'customizer-login-page' ),
				'panel' => 'lpc-main-panel',
			)
		);

			// title heading control.
			$wp_customize->add_setting(
				'lpc-title-heading',
				array(
					'default'           => '',
					'sanitize_callback' => 'sanitize_text_field',
				)
			);
			$wp_customize->add_control(
				new Lpc_Simple_Notice_Custom_control(
					$wp_customize,
					'lpc-title-heading-control',
					array(
						'label'    => __( 'Page Title Settings', 'customizer-login-page' ),
						'settings' => 'lpc-title-heading',
						'section'  => 'lpc-section-title',
					)
				)
			);

			// login page title text.
			$wp_customize->add_setting(
				'lpc_opts[lpc-title-text]',
				array(
					'type'              => 'option',
					'default'           => '',
					'transport'         => 'postMessage',
					'sanitize_callback' => 'sanitize_text_field',
				)
			);

			$wp_customize->add_control(
				new LPC_Custom_Title_Text_Control(
					$wp_customize,
					'lpc_opts[lpc-title-text]-control',
					array(

						'label'       => __( 'Login Page Title', 'customizer-login-page' ),
						'description' => __( 'Publish and please log out and navigate to the login page to observe the updated Title changes.', 'customizer-login-page' ),
						'section'     => 'lpc-section-title',
						'settings'    => 'lpc_opts[lpc-title-text]',
						'type'        => 'text',
					)
				)
			);
			/** Section : Login page Title End  */
