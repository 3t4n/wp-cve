<?php
	//Latest News Section
	$wp_customize->add_section('wpblack_latest_news_section', array(
	    'title' => esc_html__('Latest News Settings', 'spicebox'),
	    'panel' => 'section_settings',
	    'priority' => 5,
	));


	// Enable news section
	$wp_customize->add_setting('latest_news_section_enable', array(
	    'default' => true,
	    'sanitize_callback' => 'spiceb_wpblack_sanitize_checkbox'
	    ));

	$wp_customize->add_control(new WPBlack_Toggle_Control($wp_customize, 'latest_news_section_enable',
	                array(
	            'label' => esc_html__('Enable/Disable Latest News Section', 'spicebox'),
	            'type' => 'toggle',
	            'section' => 'wpblack_latest_news_section',
	                )
	));

	
	//News section subtitle
	$wp_customize->add_setting('home_news_section_discription', array(
	    'default' => esc_html__('Cras Vitae Placerat', 'spicebox'),
	    'sanitize_callback' => 'spiceb_wpblack_home_page_sanitize_text',
	    'transport' => $selective_refresh,
	));
	$wp_customize->add_control('home_news_section_discription', array(
	    'label' => esc_html__('Sub Title', 'spicebox'),
	    'section' => 'wpblack_latest_news_section',
	    'type' => 'text',
	    'active_callback' => 'spiceb_wpblack_news_callback'
	));

	// News section title
	$wp_customize->add_setting('home_news_section_title', array(
	    'capability' => 'edit_theme_options',
	    'default' => esc_html__('Vitae Lacinia', 'spicebox'),
	    'sanitize_callback' => 'spiceb_wpblack_home_page_sanitize_text',
	    'transport' => $selective_refresh,
	));
	$wp_customize->add_control('home_news_section_title', array(
	    'label' => esc_html__('Title', 'spicebox'),
	    'section' => 'wpblack_latest_news_section',
	    'type' => 'text',
	    'active_callback' => 'spiceb_wpblack_news_callback'
	));

	// Read More Button
	$wp_customize->add_setting('home_news_button_title', array(
	    'capability' => 'edit_theme_options',
	    'default' => esc_html__('Cras Vitae', 'spicebox'),
	    'sanitize_callback' => 'spiceb_wpblack_home_page_sanitize_text',
	));
	$wp_customize->add_control('home_news_button_title', array(
	    'label' => esc_html__('Read More Text', 'spicebox'),
	    'section' => 'wpblack_latest_news_section',
	    'type' => 'text',
	    'active_callback' => 'spiceb_wpblack_news_callback'
	));

	// enable/disable meta section 
	$wp_customize->add_setting('home_meta_section_settings',
	        array('capability' => 'edit_theme_options',
	            'default' => true,
	            'sanitize_callback' => 'spiceb_wpblack_sanitize_checkbox',
	    
	));
	$wp_customize->add_control(
	        'home_meta_section_settings',
	        array(
	            'type' => 'checkbox',
	            'label' => esc_html__('Enable/Disable post meta in blog section', 'spicebox'),
	            'section' => 'wpblack_latest_news_section',
	            'active_callback' => 'spiceb_wpblack_news_callback'
	        )
	);

	//Navigation Type
	$wp_customize->add_setting('news_nav_style', array('default' => 'bullets'));
	$wp_customize->add_control('news_nav_style', array(
	    'label' => __('Navigation Style', 'spicebox'),
	    'section' => 'wpblack_latest_news_section',
	    'type' => 'radio',
	    'choices' => array(
	        'bullets' => __('Bullets', 'spicebox'),
	        'navigation' => __('Navigation', 'spicebox'),
	        'both' => __('Both', 'spicebox'),
	    ),
	    'active_callback' => 'spiceb_wpblack_news_callback'
	));

	// animation speed
	$wp_customize->add_setting('newz_animation_speed', array('default' => 3000));
	$wp_customize->add_control('newz_animation_speed',
	        array(
	            'label' => __('Animation speed', 'spicebox'),
	            'section' => 'wpblack_latest_news_section',
	            'type' => 'select',
	            'choices' => array(
	                2000 => '2.0',
	                3000 => '3.0',
	                4000 => '4.0',
	                5000 => '5.0',
	                6000 => '6.0',
	            ),
	            'active_callback' => 'spiceb_wpblack_news_callback'
	));

// smooth speed
$wp_customize->add_setting('news_smooth_speed', array('default' => 1000));
$wp_customize->add_control('news_smooth_speed',
        array(
            'label' => __('Smooth speed', 'spicebox'),
            'section' => 'wpblack_latest_news_section',
            'type' => 'select',
            'active_callback' => 'spiceb_wpblack_news_callback',
            'choices' => array('500' => '0.5',
                '1000' => '1.0',
                '1500' => '1.5',
                '2000' => '2.0',
                '2500' => '2.5',
                '3000' => '3.0')
));

	/**
	 * Add selective refresh for Front page news section controls.
	 */
	$wp_customize->selective_refresh->add_partial('home_news_section_title', array(
	    'selector' => '.home-blog .section-header h2',
	    'settings' => 'home_news_section_title',
	    'render_callback' => 'spiceb_home_news_section_title_render_callback',
	));

	$wp_customize->selective_refresh->add_partial('home_news_section_discription', array(
	    'selector' => '.home-blog .section-header p',
	    'settings' => 'home_news_section_discription',
	    'render_callback' => 'spiceb_home_news_section_discription_render_callback',
	));

	$wp_customize->selective_refresh->add_partial('home_blog_more_btn', array(
	    'selector' => '.home-blog .business-view-more-post',
	    'settings' => 'home_blog_more_btn',
	    'render_callback' => 'spiceb_home_blog_more_btn_render_callback',
	));

	$wp_customize->selective_refresh->add_partial('home_news_button_title', array(
	    'selector' => '.home-blog a.more-link',
	    'settings' => 'home_news_button_title',
	    'render_callback' => 'spiceb_home_news_button_title_render_callback',
	));

function spiceb_home_news_section_title_render_callback() {
    return get_theme_mod('home_news_section_title');
}

function spiceb_home_news_section_discription_render_callback() {
    return get_theme_mod('home_news_section_discription');
}

function spiceb_home_blog_more_btn_render_callback() {
    return get_theme_mod('home_blog_more_btn');
}

function spiceb_home_news_button_title_render_callback() {
    return get_theme_mod('home_news_button_title');
}