<?php

	/* Slider Section */
	$wp_customize->add_section('slider_section', array(
	    'title' => esc_html__('Slider Settings', 'spicebox'),
	    'panel' => 'section_settings',
	    'priority' => 1,
	));

	// Enable slider
	$wp_customize->add_setting('home_page_slider_enabled', array(
	    'default' => true,
	    'sanitize_callback' => 'spiceb_wpkites_sanitize_checkbox',
	));

	$wp_customize->add_control(new WPKites_Toggle_Control($wp_customize, 'home_page_slider_enabled',
	                array(
	            'label' => esc_html__('Enable/Disable Slider Section', 'spicebox'),
	            'type' => 'toggle',
	            'section' => 'slider_section',
	            'priority' => 1,
	                )
	));

	// Slider Variation
    $wp_customize->add_setting( 'slide_variation', array( 'default' => 'slide') );
    $wp_customize->add_control( 'slide_variation',
    array(
        'label'    => esc_html__( 'Slider Background Type', 'spicebox' ),
        'section'  => 'slider_section',
        'type'     => 'select',
        'active_callback' => 'spiceb_wpkites_slider_callback',
        'choices'=>array(
            'slide'=>esc_html__('Image', 'spicebox'),
            'video'=>esc_html__('Video', 'spicebox')
            )
    ));

    // Slider Video Section
    $wp_customize->add_setting( 'slide_video_upload',
       array(
          'default' => '',
          'transport' => 'refresh',
          'sanitize_callback' => 'absint'
       )
    );
    $wp_customize->add_control( new WP_Customize_Media_Control( $wp_customize, 'slide_video_upload',
       array(
          'label' => esc_html__( 'Slider video','spicebox' ),
          'description' => esc_html__( 'Upload your video in .mp4 format and minimize its file size for best results. For this theme the recommended size is 1150 × 2000 pixels.','spicebox' ),
          'section' => 'slider_section',
          'mime_type' => 'video',  // Required. Can be image, audio, video, application, text
          'active_callback' => 'spiceb_wpkites_slider_callback',
          'button_labels' => array( // Optional
             'select' => esc_html__( 'Select File' ),
             'change' => esc_html__( 'Change File' ),
             'default' => esc_html__( 'Default' ),
             'remove' => esc_html__( 'Remove' ),
             'placeholder' => esc_html__( 'No file selected' ),
             'frame_title' => esc_html__( 'Select File' ),
             'frame_button' => esc_html__( 'Choose File' ),

          )
       )
    ) );

    //Slider video url
    $wp_customize->add_setting( 'slide_video_url',array(
    'capability'     => 'edit_theme_options',
    'default' => '',
    'sanitize_callback' => 'esc_url_raw',
    ));
    $wp_customize->add_control( 'slide_video_url',array(
    'label'   => esc_html__('Or, enter a YouTube URL:','spicebox'),
    'section' => 'slider_section',
    'type' => 'text',
    'active_callback' => 'spiceb_wpkites_slider_callback'

    ));

    
	
	//Slider Image
	$wp_customize->add_setting('home_slider_image', array(
		'default' => WPKITES_TEMPLATE_DIR_URI . '/assets/images/slider/slider.jpg',
	    'sanitize_callback' => 'esc_url_raw',
	));
	
	$wp_customize->add_control(
	    new WP_Customize_Image_Control(
	            $wp_customize,
	            'home_slider_image',
	            array(
	        'type' => 'upload',
	        'label' => esc_html__('Image', 'spicebox'),
	        'settings' => 'home_slider_image',
	        'section' => 'slider_section',
	        'active_callback' => 'spiceb_wpkites_slider_callback'
	            )
	    )
	);

	// Image overlay
	$wp_customize->add_setting('slider_image_overlay', array(
	    'default' => true,
	    'sanitize_callback' => 'spiceb_wpkites_sanitize_checkbox',
	        )
	);

	$wp_customize->add_control('slider_image_overlay', array(
	    'label' => esc_html__('Enable/Disable slider image overlay', 'spicebox'),
	    'section' => 'slider_section',
	    'type' => 'checkbox',
	    'active_callback' => 'spiceb_wpkites_slider_callback'
	        )
	);

	//Slider Background Overlay Color
	$wp_customize->add_setting('slider_overlay_section_color', array(
	    'sanitize_callback' => 'sanitize_text_field',
	    'default' => 'rgba(0,0,0,0.6)',
	        )
	);

	$wp_customize->add_control(new SpiceBox_Customize_Alpha_Color_Control($wp_customize, 'slider_overlay_section_color', array(
	            'label' => esc_html__('Slider image overlay color', 'spicebox'),
	            'palette' => true,
	            'section' => 'slider_section',
	            'active_callback' => 'spiceb_wpkites_slider_callback'
	                )
	));

   //Content Alignment
    $wp_customize->add_setting( 'slider_content_alignment',
    	array(
	    'default' => 'left',
	    'transport' => 'refresh',
		)
	);
    $wp_customize->add_control( new Spicebox_Text_Radio_Button_Custom_Control( $wp_customize, 'slider_content_alignment',
        array(
        'label' => esc_html__( 'Slider Content Alignment', 'spicebox' ),
        'section' => 'slider_section',
        'active_callback' => 'spiceb_wpkites_slider_callback',
        'choices' => array(
            'left' => esc_html__( 'Left' ), // Required. Setting for this particular radio button choice and the text to display
            'center' => esc_html__( 'Center' ), // Required. Setting for this particular radio button choice and the text to display
            'right' => esc_html__( 'Right' ) // Required. Setting for this particular radio button choice and the text to display
		        )
		    )
		) );

	// Slider title
	$wp_customize->add_setting('home_slider_title', array(
	    'default' => __('Nulla nec dolor sit amet<br> lacus molestie', 'spicebox'),
	    'capability' => 'edit_theme_options',
	    'sanitize_callback' => 'spiceb_wpkites_home_page_sanitize_text',
	));
	$wp_customize->add_control('home_slider_title', array(
	    'label' => esc_html__('Title', 'spicebox'),
	    'section' => 'slider_section',
	    'type' => 'text',
	    'active_callback' => 'spiceb_wpkites_slider_callback'
	));

	//Slider discription
	$wp_customize->add_setting('home_slider_discription', array(
	    'default' => __('Sea summo mazim ex, ea errem eleifend definitionem vim.<br> Ut nec hinc dolor possim mei ludus efficiendi ei sea summo mazim ex.', 'spicebox'),
	    'sanitize_callback' => 'spiceb_wpkites_home_page_sanitize_text',
	));
	$wp_customize->add_control('home_slider_discription', array(
	    'label' => esc_html__('Description', 'spicebox'),
	    'section' => 'slider_section',
	    'type' => 'textarea',
	    'active_callback' => 'spiceb_wpkites_slider_callback'
	));


	// Slider button text
	$wp_customize->add_setting('home_slider_btn_txt', array(
	    'default' => esc_html__('Nec Sem', 'spicebox'),
	    'sanitize_callback' => 'spiceb_wpkites_home_page_sanitize_text',
	));
	$wp_customize->add_control('home_slider_btn_txt', array(
	    'label' => esc_html__('Button Text', 'spicebox'),
	    'section' => 'slider_section',
	    'type' => 'text',
	    'active_callback' => 'spiceb_wpkites_slider_callback'
	));

	// Slider button link
	$wp_customize->add_setting('home_slider_btn_link', array(
	    'default' => esc_html__('#', 'spicebox'),
	    'sanitize_callback' => 'esc_url_raw',
	));
	$wp_customize->add_control('home_slider_btn_link', array(
	    'label' => esc_html__('Button Link', 'spicebox'),
	    'section' => 'slider_section',
	    'type' => 'text',
	    'active_callback' => 'spiceb_wpkites_slider_callback'
	));

	// Slider button target
	$wp_customize->add_setting(
	        'home_slider_btn_target',
	        array(
	            'default' => false,
	            'sanitize_callback' => 'spiceb_wpkites_sanitize_checkbox',
	));
	$wp_customize->add_control('home_slider_btn_target', array(
	    'label' => esc_html__('Open link in new tab', 'spicebox'),
	    'section' => 'slider_section',
	    'type' => 'checkbox',
	    'active_callback' => 'spiceb_wpkites_slider_callback'
	));

	// Slider button2 text
	$wp_customize->add_setting('home_slider_btn_txt2', array(
	    'default' => esc_html__('Cras Vitae', 'spicebox'),
	    'sanitize_callback' => 'spiceb_wpkites_home_page_sanitize_text',
	));
	$wp_customize->add_control('home_slider_btn_txt2', array(
	    'label' => esc_html__('Button 2 Text', 'spicebox'),
	    'section' => 'slider_section',
	    'type' => 'text',
	    'active_callback' => 'spiceb_wpkites_slider_callback'
	));

	// Slider button link
	$wp_customize->add_setting('home_slider_btn_link2', array(
	    'default' => esc_html__('#', 'spicebox'),
	    'sanitize_callback' => 'esc_url_raw',
	));
	$wp_customize->add_control('home_slider_btn_link2', array(
	    'label' => esc_html__('Button 2 Link', 'spicebox'),
	    'section' => 'slider_section',
	    'type' => 'text',
	    'active_callback' => 'spiceb_wpkites_slider_callback'
	));

	// Slider button target
	$wp_customize->add_setting(
	        'home_slider_btn_target2',
	        array(
	            'default' => false,
	            'sanitize_callback' => 'spiceb_wpkites_sanitize_checkbox',
	));
	$wp_customize->add_control('home_slider_btn_target2', array(
	    'label' => esc_html__('Open link in new tab', 'spicebox'),
	    'section' => 'slider_section',
	    'type' => 'checkbox',
	    'active_callback' => 'spiceb_wpkites_slider_callback'
	));


$wp_customize->selective_refresh->add_partial('home_slider_subtitle', array(
    'selector' => '.bcslider-section .slider-caption .heading ',
    'settings' => 'home_slider_subtitle',
    'render_callback' => 'home_slider_section_title_render_callback',
));
$wp_customize->selective_refresh->add_partial('home_slider_title', array(
    'selector' => '.bcslider-section .slider-caption .title ',
    'settings' => 'home_slider_title',
    'render_callback' => 'home_slider_section_title_render_callback',
));
$wp_customize->selective_refresh->add_partial('home_slider_discription', array(
    'selector' => '.bcslider-section .slider-caption .description ',
    'settings' => 'home_slider_discription',
    'render_callback' => 'home_slider_section_title_render_callback',
));
$wp_customize->selective_refresh->add_partial('home_slider_btn_txt', array(
    'selector' => '.bcslider-section .slider-caption .btn-combo .btn-default ',
    'settings' => 'home_slider_btn_txt',
    'render_callback' => 'home_slider_section_title_render_callback',
));
$wp_customize->selective_refresh->add_partial('home_slider_btn_txt2', array(
    'selector' => '.bcslider-section .slider-caption .btn-combo .btn-light ',
    'settings' => 'home_slider_btn_txt2',
    'render_callback' => 'home_slider_section_title_render_callback',
));