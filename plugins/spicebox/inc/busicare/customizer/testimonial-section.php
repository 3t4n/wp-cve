<?php
	/* Testimonial Section */
	$wp_customize->add_section('testimonial_section', array(
	    'title' => esc_html__('Testimonials Settings', 'spicebox'),
	    'panel' => 'section_settings',
	    'priority' =>4,
	));

	// Enable testimonial section
	$wp_customize->add_setting('testimonial_section_enable', array(
	    'default' => true,
	    'sanitize_callback' => 'spiceb_busicare_sanitize_checkbox'
	    ));

	$wp_customize->add_control(new busicare_Toggle_Control($wp_customize, 'testimonial_section_enable',
	                array(
	            'label' => esc_html__('Enable/Disable Testimonial Section', 'spicebox'),
	            'type' => 'toggle',
	            'section' => 'testimonial_section',
	                )
	));
	// testimonial section title
	$wp_customize->add_setting('home_testimonial_section_title', array(
	    'capability' => 'edit_theme_options',
	    'default' => esc_html__('Proin Egestas', 'spicebox'),
	    'sanitize_callback' => 'spiceb_busicare_home_page_sanitize_text',
	));
	$wp_customize->add_control('home_testimonial_section_title', array(
	    'label' => esc_html__('Title', 'spicebox'),
	    'section' => 'testimonial_section',
	    'type' => 'text',
	    'active_callback' => 'spiceb_busicare_testimonial_callback'
	));

	if (class_exists('Spicebox_Repeater')) {
    $wp_customize->add_setting('busicare_testimonial_content', array(
    ));

    $wp_customize->add_control(new Spicebox_Repeater($wp_customize, 'busicare_testimonial_content', array(
                'label' => esc_html__('Testimonial content', 'spicebox'),
                'section' => 'testimonial_section',
                'add_field_label' => esc_html__('Add New Testimonial', 'spicebox'),
                'item_name' => esc_html__('Testimonial', 'spicebox'),
                'customizer_repeater_title_control' => true,
                'customizer_repeater_text_control' => true,
                'customizer_repeater_user_name_control' => true,
                'customizer_repeater_designation_control' => true,
                'customizer_repeater_link_control' => true,
                'customizer_repeater_checkbox_control' => true,
                'customizer_repeater_image_control' => true,
                'active_callback' => 'spiceb_busicare_testimonial_callback'
            )));
}

//Navigation Type
$wp_customize->add_setting('testimonial_nav_style', array('default' => 'bullets'));
$wp_customize->add_control('testimonial_nav_style', array(
    'label' => esc_html__('Navigation Style', 'spicebox'),
    'section' => 'testimonial_section',
    'type' => 'radio',
    'priority' => 17,
    'choices' => array(
        'bullets' => __('Bullets', 'spicebox'),
        'navigation' => __('Navigation', 'spicebox'),
        'both' => __('Both', 'spicebox'),
    ),
    'active_callback' => 'spiceb_busicare_testimonial_callback'
));

	//Testimonial Background Image
	$wp_customize->add_setting('testimonial_callout_background', array(
		'default' => SPICEB_PLUGIN_URL . '/inc/busicare/images/testimonial/testimonial-bg.jpg',
	    'sanitize_callback' => 'esc_url_raw',
	));

	$wp_customize->add_control(new WP_Customize_Image_Control($wp_customize, 'testimonial_callout_background', array(
	            'label' => esc_html__('Background Image', 'spicebox'),
	            'section' => 'testimonial_section',
	            'settings' => 'testimonial_callout_background',
	            'active_callback' => 'spiceb_busicare_testimonial_callback'
	        )));

	// Image overlay
	$wp_customize->add_setting('testimonial_image_overlay', array(
	    'default' => true,
	    'sanitize_callback' => 'spiceb_busicare_sanitize_checkbox',
	));

	$wp_customize->add_control('testimonial_image_overlay', array(
	    'label' => esc_html__('Enable/Disable testimonial image overlay', 'spicebox'),
	    'section' => 'testimonial_section',
	    'type' => 'checkbox',
	    'active_callback' => 'spiceb_busicare_testimonial_callback'
	));

	//Testimonial Background Overlay Color
	$wp_customize->add_setting('testimonial_overlay_section_color', array(
	    'sanitize_callback' => 'spiceb_busicare_home_page_sanitize_text',
	    'default' => 'rgba(0, 11, 24, 0.7)',
	));

	$wp_customize->add_control(new SpiceBox_Customize_Alpha_Color_Control($wp_customize, 'testimonial_overlay_section_color', array(
	            'label' => esc_html__('Testimonial image overlay color', 'spicebox'),
	            'palette' => true,
	            'section' => 'testimonial_section',
	            'active_callback' => 'spiceb_busicare_testimonial_callback'
	        )
	));

	

$wp_customize->selective_refresh->add_partial('home_testimonial_section_title', array(
    'selector' => '.testimonial h2',
    'settings' => 'home_testimonial_section_title',
    'render_callback' => 'home_testimonial_section_title_render_callback',
));
$wp_customize->selective_refresh->add_partial('home_testimonial_thumb', array(
    'selector' => '.testimonial .testmonial-block img',
    'settings' => 'home_testimonial_thumb',
    'render_callback' => 'home_testimonial_section_title_render_callback',
));
$wp_customize->selective_refresh->add_partial('home_testimonial_title', array(
    'selector' => '.testimonial .testmonial-block .entry-content .title span',
    'settings' => 'home_testimonial_title',
    'render_callback' => 'home_testimonial_section_title_render_callback',
));
$wp_customize->selective_refresh->add_partial('home_testimonial_desc', array(
    'selector' => '.testimonial .testmonial-block .entry-content p',
    'settings' => 'home_testimonial_desc',
    'render_callback' => 'home_testimonial_section_title_render_callback',
));
$wp_customize->selective_refresh->add_partial('home_testimonial_name', array(
    'selector' => '.testimonial .testmonial-block figcaption .name',
    'settings' => 'home_testimonial_name',
    'render_callback' => 'home_testimonial_section_title_render_callback',
));
$wp_customize->selective_refresh->add_partial('home_testimonial_designation', array(
    'selector' => '.testimonial .testmonial-block figcaption .designation ',
    'settings' => 'home_testimonial_designation',
    'render_callback' => 'home_testimonial_section_title_render_callback',
));