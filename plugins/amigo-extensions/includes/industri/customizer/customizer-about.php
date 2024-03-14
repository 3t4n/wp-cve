<?php 
// customizer theme about settings
add_action( 'customize_register', 'industri_customizer_about_settings');
function industri_customizer_about_settings( $wp_customize ) {

	$default = amigo_industri_default_settings();

	$selective_refresh = isset( $wp_customize->selective_refresh ) ? 'postMessage' : 'refresh';

	// about section
	$wp_customize->add_section('about_section', array(
		'title'    => esc_html__( 'About', 'industri' ),
		'panel'	=> 'theme_homepage',
		'priority' => 1,		

	));	

	// seprator	about section settings		
	$wp_customize->add_setting('separator_about_settings', array('priority'=> 1));
	$wp_customize->add_control(new Amigo_Separator( $wp_customize, 
		'separator_about_settings', array(
			'label' => __('Settings','industri'),
			'settings' => 'separator_about_settings',
			'section' => 'about_section',					
		)
	));

	// is display
	$wp_customize->add_setting(
		'display_about_section',
		array(
			'default' => esc_html($default['display_about_section']),
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'amigo_sanitize_checkbox',
			'priority'      => 1,
		)
	);	

	$wp_customize->add_control( 
		'display_about_section',
		array(
			'label'   		=> __('Show/Hide About Section','industri'),
			'section'		=> 'about_section',
			'type' 			=> 'checkbox',
			'transport'         => $selective_refresh,
		)  
	);	

	// selective refresh
	$wp_customize->selective_refresh->add_partial( 'about_title', array(
		'selector'            => '.about-section .about-content .section-title h5',				
		'render_callback'  => function() { return get_theme_mod( 'about_title' ); },
	) );	

	// about section title
	$wp_customize->add_setting(
		'about_title',
		array(
			'default' => esc_html( $default['about_title'] ),
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'amigo_sanitize_text',
			'priority'      => 1,
		)
	);	

	$wp_customize->add_control( 
		'about_title',
		array(
			'label'   		=> __('Title','industri'),
			'section'		=> 'about_section',
			'type' 			=> 'text',
			'transport'         => $selective_refresh,
		)  
	);

	// selective refresh
	$wp_customize->selective_refresh->add_partial( 'about_subtitle', array(
		'selector'            => '.about-content .section-title h3',				
		'render_callback'  => function() { return get_theme_mod( 'about_subtitle' ); },
	) );	

	// about section sub title
	$wp_customize->add_setting(
		'about_subtitle',
		array(
			'default' => esc_html( $default['about_subtitle'] ),
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'amigo_sanitize_text',
			'priority'      => 1,
		)
	);	

	$wp_customize->add_control( 
		'about_subtitle',
		array(
			'label'   		=> __('Sub Title','industri'),
			'section'		=> 'about_section',
			'type' 			=> 'text',
			'transport'         => $selective_refresh,
		)  
	);	

	// selective refresh
	$wp_customize->selective_refresh->add_partial( 'about_text', array(
		'selector'            => '.about-content .section-title p',				
		'render_callback'  => function() { return get_theme_mod( 'about_text' ); },
	) );	

	// about section text
	$wp_customize->add_setting(
		'about_text',
		array(
			'default' => esc_html( $default['about_text'] ),
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'amigo_sanitize_text',
			'priority'      => 1,
		)
	);	

	$wp_customize->add_control( 
		'about_text',
		array(
			'label'   		=> __('Text','industri'),
			'section'		=> 'about_section',
			'type' 			=> 'textarea',
			'transport'         => $selective_refresh,
		)  
	);	

	// about section button text
	$wp_customize->add_setting(
		'about_button_text',
		array(
			'default' => esc_html( $default['about_button_text'] ),
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'amigo_sanitize_text',
			'priority'      => 1,
		)
	);	

	$wp_customize->add_control( 
		'about_button_text',
		array(
			'label'   		=> __('Button Text','industri'),
			'section'		=> 'about_section',
			'type' 			=> 'text',
			'transport'         => $selective_refresh,
		)  
	);

	// about section button link
	$wp_customize->add_setting(
		'about_button_link',
		array(
			'default' => esc_html( $default['about_button_link'] ),
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'amigo_sanitize_text',
			'priority'      => 1,
		)
	);	

	$wp_customize->add_control( 
		'about_button_link',
		array(
			'label'   		=> __('Button Link','industri'),
			'section'		=> 'about_section',
			'type' 			=> 'text',
			'transport'         => $selective_refresh,
		)  
	);	

	// selective refresh
	$wp_customize->selective_refresh->add_partial( 'about_items', array(
		'selector'            => '.about-section .about-content .about-list',				
		'render_callback'  => function() { return get_theme_mod( 'about_items' ); },
	) );	

	// seprator	about content		
	$wp_customize->add_setting('separator_about_content', array('priority'=> 2));
	$wp_customize->add_control(new Amigo_Separator( $wp_customize, 
		'separator_about_content', array(
			'label' => __('Content','industri'),
			'settings' => 'separator_about_content',
			'section' => 'about_section',					
		)
	));	

	// about content
	$wp_customize->add_setting( 'about_items', array(
		'sanitize_callback' => 'amigo_repeater_sanitize',
		'default' => amigo_industri_default_about_items(),
		'priority' => 2,
	));

	$wp_customize->add_control( new Amigo_Customizer_Repeater( $wp_customize, 'about_items', array(
		'label'   => esc_html__('About Items','industri'),
		'item_name' => esc_html__( 'Item', 'industri' ),
		'section' => 'about_section',		
		'customizer_repeater_image_control' => false,
		'customizer_repeater_title_control' => true,
		'customizer_repeater_subtitle_control' => false,
		'customizer_repeater_text_control' => true,
		'customizer_repeater_text2_control'=> false,
		'customizer_repeater_link_control' => false,
		'customizer_repeater_link2_control' => false,
		'customizer_repeater_button2_control' => false,
		'customizer_repeater_slide_align' => false,
		'customizer_repeater_icon_control' => true,		
		'customizer_repeater_checkbox_control' => true,
								

	) ) );

	// seprator	about section overlay		
	$wp_customize->add_setting('separator_about_overlay', array('priority'=> 2));
	$wp_customize->add_control(new Amigo_Separator( $wp_customize, 
		'separator_about_overlay', array(
			'label' => __('Overlay','industri'),
			'settings' => 'separator_about_overlay',
			'section' => 'about_section',					
		)
	));	

	// selective refresh
	$wp_customize->selective_refresh->add_partial( 'display_about_overlay', array(
		'selector'            => '.about-section .about-img .overlay',				
		'render_callback'  => function() { return get_theme_mod( 'display_about_overlay' ); },
	) );	

	// is display overlay
	$wp_customize->add_setting(
		'display_about_overlay',
		array(
			'default' => true,
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'amigo_sanitize_checkbox',
			'priority'      => 2,
		)
	);	

	$wp_customize->add_control( 
		'display_about_overlay',
		array(
			'label'   		=> __('Show/Hide About Overlay','industri'),
			'section'		=> 'about_section',
			'type' 			=> 'checkbox',
			'transport'         => $selective_refresh,
		)  
	);	

	// about overlay title
	$wp_customize->add_setting(
		'about_overlay_title',
		array(
			'default' => esc_html( $default['about_overlay_title'] ),
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'amigo_sanitize_text',
			'priority'      => 2,
		)
	);	

	$wp_customize->add_control( 
		'about_overlay_title',
		array(
			'label'   		=> __('Title','industri'),
			'section'		=> 'about_section',
			'type' 			=> 'text',
			'transport'         => $selective_refresh,
		)  
	);

	// about overlay sub title
	$wp_customize->add_setting(
		'about_overlay_subtitle',
		array(
			'default' => esc_html( $default['about_overlay_subtitle'] ),
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'amigo_sanitize_text',
			'priority'      => 2,
		)
	);	

	$wp_customize->add_control( 
		'about_overlay_subtitle',
		array(
			'label'   		=> __('Sub Title','industri'),
			'section'		=> 'about_section',
			'type' 			=> 'text',
			'transport'         => $selective_refresh,
		)  
	);	

	// about overlay video button text
	$wp_customize->add_setting(
		'about_overlay_video_text',
		array(
			'default' => esc_html( $default['about_overlay_video_text'] ),
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'amigo_sanitize_text',
			'priority'      => 2,
		)
	);	

	$wp_customize->add_control( 
		'about_overlay_video_text',
		array(
			'label'   		=> __('Video Text','industri'),
			'section'		=> 'about_section',
			'type' 			=> 'text',
			'transport'         => $selective_refresh,
		)  
	);	

	// about overlay video link
	$wp_customize->add_setting(
		'about_overlay_video_link',
		array(
			'default' => esc_html( $default['about_overlay_video_link'] ),
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'amigo_sanitize_text',
			'priority'      => 2,
		)
	);	

	$wp_customize->add_control( 
		'about_overlay_video_link',
		array(
			'label'   		=> __('Video Link','industri'),
			'section'		=> 'about_section',
			'type' 			=> 'text',
			'transport'         => $selective_refresh,
		)  
	);	

	// seprator	about section image		
	$wp_customize->add_setting('separator_about_image', array('priority'=> 3));
	$wp_customize->add_control(new Amigo_Separator( $wp_customize, 
		'separator_about_image', array(
			'label' => __('Background','industri'),
			'settings' => 'separator_about_image',
			'section' => 'about_section',					
		)
	));	

	// selective refresh
	$wp_customize->selective_refresh->add_partial( 'about_image_first', array(
		'selector'            => '.about-section .about-img',				
		'render_callback'  => function() { return get_theme_mod( 'about_image_first' ); },
	) );	

	// image one
	$wp_customize->add_setting(
		'about_image_first',
		array(
			'default' => esc_html( $default['about_image_first'] ),
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'amigo_sanitize_url',
			'priority'      => 3,
		)
	);	

	$wp_customize->add_control( new WP_Customize_Image_Control(
		$wp_customize,
		'about_image_first',
		array(
			'label'      => __( 'Image', 'industri' ),
			'section'    => 'about_section',
			'settings'   => 'about_image_first',					
		)
	)); 

}