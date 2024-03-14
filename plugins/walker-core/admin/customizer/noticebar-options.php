<?php

add_action( 'customize_register', 'walker_core_options_register' );
	function walker_core_options_register( $wp_customize ) {
		if ( wc_fs()->can_use_premium_code() ) {
		
		$wp_customize->add_section('walker_core_notificationbar_setup', 
		 	array(
		        'title' => esc_html__('Notification Bar', 'walker-core'),
		        'panel' =>'gridchamp_theme_option',
		        'priority' => 1,
	    	)
		 );
		$wp_customize->add_setting( 'notification_bar_status', 
	    	array(
		      'default'  =>  false,
		      'sanitize_callback' => 'walker_core_sanitize_checkbox'
		  	)
	    );
		$wp_customize->add_control( 'notification_bar_status', 
			array(
			  'label'   => esc_html__( 'Enable Notification bar', 'walker-core' ),
			  'description' => '',
			  'section' => 'walker_core_notificationbar_setup',
			  'settings' => 'notification_bar_status',
			  'type'    => 'checkbox',
			  'priority' => 1
			)
		);
		$wp_customize->selective_refresh->add_partial( 'notification_bar_status', array(
            'selector' => 'span.noticebar-text',
        ) );
		$wp_customize->add_setting( 'notification_bg_color', 
			array(
		        'default'        => '#06b3ca',
		        'sanitize_callback' => 'walker_core_sanitize_hex_color',
	    	) 
		);

		$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 
			'notification_bg_color', 
			array(
		        'label'   => esc_html__( 'Background Color', 'walker-core' ),
		        'section' => 'walker_core_notificationbar_setup',
		        'settings'   => 'notification_bg_color',
		        'priority' => 2,
		        'active_callback' => function(){
				    return get_theme_mod( 'notification_bar_status', true );
				},
		    ) ) 
		);
		$wp_customize->add_setting( 'notification_text_color', 
			array(
		        'default'        => '#ffffff',
		        'sanitize_callback' => 'walker_core_sanitize_hex_color',
	    	) 
		);

		$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 
			'notification_text_color', 
			array(
		        'label'   => esc_html__( 'Text Color', 'walker-core' ),
		        'section' => 'walker_core_notificationbar_setup',
		        'settings'   => 'notification_text_color',
		        'priority' => 2,
		        'active_callback' => function(){
				    return get_theme_mod( 'notification_bar_status', true );
				},
		    ) ) 
		);
		$wp_customize->add_setting( 'notification_text', 
		 	array(
				'capability' => 'edit_theme_options',
				'default' => '',
				'sanitize_callback' => 'sanitize_textarea_field',
			) 
		);

		$wp_customize->add_control( 'notification_text', 
			array(
				'type' => 'textarea',
				'section' => 'walker_core_notificationbar_setup',
				'label' => esc_html__( 'Notification Text','walker-core' ),
				'description' => '',
				'active_callback' => function(){
				    return get_theme_mod( 'notification_bar_status', true );
				},
			)
		);
		$wp_customize->add_setting( 'notification_btn_text', 
		 	array(
				'capability' => 'edit_theme_options',
				'default' => '',
				'sanitize_callback' => 'walker_core_sanitize_text',
			) 
		);

		$wp_customize->add_control( 'notification_btn_text', 
			array(
				'type' => 'text',
				'section' => 'walker_core_notificationbar_setup',
				'label' => esc_html__( 'Button','walker-core' ),
				'active_callback' => function(){
				    return get_theme_mod( 'notification_bar_status', true );
				},
			)
		);
		$wp_customize->add_setting( 'notification_btn_url', 
			array(
				'default' => '',
		        'sanitize_callback'     =>  'esc_url_raw',
		    ) 
		);

	    $wp_customize->add_control( 'notification_btn_url', 
	    	array(
		        'label' => esc_html__( 'Button Link', 'walker-core' ),
		        'section' => 'walker_core_notificationbar_setup',
		        'settings' => 'notification_btn_url',
		        'type'=> 'url',
		        'active_callback' => function(){
				    return get_theme_mod( 'notification_bar_status', true );
				},
	    	) 
	    );
	    $wp_customize->add_setting( 'notification_btn_target', 
	    	array(
		      'default'  =>  false,
		      'sanitize_callback' => 'walker_core_sanitize_checkbox'
		  	)
	    );
		$wp_customize->add_control( 'notification_btn_target', 
			array(
			  'label'   => esc_html__( 'Open In New Tab', 'walker-core' ),
			  'description' => '',
			  'section' => 'walker_core_notificationbar_setup',
			  'settings' => 'notification_btn_target',
			  'type'    => 'checkbox',
			  'active_callback' => function(){
				    return get_theme_mod( 'notification_bar_status', true );
				},
			)
		);
		
		$wp_customize->add_setting( 'notification_button_text_color', 
			array(
		        'default'        => '#fff',
		        'sanitize_callback' => 'walker_core_sanitize_hex_color',
	    	) 
		);

		$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 
			'notification_button_text_color', 
			array(
		        'label'   => esc_html__( 'Link Text Color', 'walker-core' ),
		        'section' => 'walker_core_notificationbar_setup',
		        'settings'   => 'notification_button_text_color',
		        'active_callback' => function(){
				    return get_theme_mod( 'notification_bar_status', true );
				},
		    ) ) 
		);
		
		$wp_customize->add_setting( 'notification_button_text_hover_color', 
			array(
		        'default'        => '#ff9800',
		        'sanitize_callback' => 'walker_core_sanitize_hex_color',
	    	) 
		);

		$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 
			'notification_button_text_hover_color', 
			array(
		        'label'   => esc_html__( 'Link Text Hover Color', 'walker_core' ),
		        'section' => 'walker_core_notificationbar_setup',
		        'settings'   => 'notification_button_text_hover_color',
		        'active_callback' => function(){
				    return get_theme_mod( 'notification_bar_status', true );
				},
		    ) ) 
		);
	}
}?>