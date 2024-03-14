<?php
$theme = wp_get_theme();
	if( $theme->name=='BusiCare Dark')
	{
	$priority = 5;	
	}
	else
	{
	$priority = 3;	
	}
	//Latest News Section
	$wp_customize->add_section('busicare_latest_news_section', array(
	    'title' => esc_html__('Latest News Settings', 'spicebox'),
	    'panel' => 'section_settings',
	    'priority' => $priority,
	));


	// Enable news section
	$wp_customize->add_setting('latest_news_section_enable', array(
	    'default' => true,
	    'sanitize_callback' => 'spiceb_busicare_sanitize_checkbox'
	    ));

	$wp_customize->add_control(new BusiCare_Toggle_Control($wp_customize, 'latest_news_section_enable',
	                array(
	            'label' => esc_html__('Enable/Disable Latest News Section', 'spicebox'),
	            'type' => 'toggle',
	            'section' => 'busicare_latest_news_section',
	                )
	));

	
	// News section title
	$wp_customize->add_setting('home_news_section_title', array(
	    'capability' => 'edit_theme_options',
	    'default' => esc_html__('Vitae Lacinia', 'spicebox'),
	    'sanitize_callback' => 'spiceb_busicare_home_page_sanitize_text',
	    'transport' => $selective_refresh,
	));
	$wp_customize->add_control('home_news_section_title', array(
	    'label' => esc_html__('Title', 'spicebox'),
	    'section' => 'busicare_latest_news_section',
	    'type' => 'text',
	    'active_callback' => 'spiceb_busicare_news_callback'
	));

	//News section subtitle
	$wp_customize->add_setting('home_news_section_discription', array(
	    'default' => esc_html__('Cras Vitae Placerat', 'spicebox'),
	    'sanitize_callback' => 'spiceb_busicare_home_page_sanitize_text',
	    'transport' => $selective_refresh,
	));
	$wp_customize->add_control('home_news_section_discription', array(
	    'label' => esc_html__('Sub Title', 'spicebox'),
	    'section' => 'busicare_latest_news_section',
	    'type' => 'text',
	    'active_callback' => 'spiceb_busicare_news_callback'
	));

	// Read More Button
	$wp_customize->add_setting('home_news_button_title', array(
	    'capability' => 'edit_theme_options',
	    'default' => esc_html__('Cras Vitae', 'spicebox'),
	    'sanitize_callback' => 'spiceb_busicare_home_page_sanitize_text',
	    'transport' => $selective_refresh,
	));
	$wp_customize->add_control('home_news_button_title', array(
	    'label' => esc_html__('Read More Text', 'spicebox'),
	    'section' => 'busicare_latest_news_section',
	    'type' => 'text',
	    'active_callback' => 'spiceb_busicare_news_callback'
	));

	// enable / disable meta section 
	$wp_customize->add_setting('home_meta_section_settings',
	        array('capability' => 'edit_theme_options',
	            'default' => true,
	            'sanitize_callback' => 'spiceb_busicare_sanitize_checkbox',
	    
	));
	$wp_customize->add_control(
	        'home_meta_section_settings',
	        array(
	            'type' => 'checkbox',
	            'label' => esc_html__('Enable/Disable post meta in blog section', 'spicebox'),
	            'section' => 'busicare_latest_news_section',
	            'active_callback' => 'spiceb_busicare_news_callback'
	        )
	);

	//Button Text
	$wp_customize->add_setting(
	        'home_blog_more_btn',
	        array(
	            'default' => esc_html__('View More', 'spicebox'),
	            'capability' => 'edit_theme_options',
	            'sanitize_callback' => 'sanitize_text_field',
	            'transport' => $selective_refresh,
	        )
	);
	$wp_customize->add_control(
	        'home_blog_more_btn',
	        array(
	            'label' => esc_html__('View More Button Text', 'spicebox'),
	            'section' => 'busicare_latest_news_section',
	            'type' => 'text',
	            'active_callback' => 'spiceb_busicare_news_callback'
	));

	//Button Link
	$wp_customize->add_setting(
	        'home_blog_more_btn_link',
	        array(
	            'default' => '#',
	            'capability' => 'edit_theme_options',
	            'sanitize_callback' => 'esc_url_raw',
	            'transport' => $selective_refresh,
	));
	$wp_customize->add_control(
        'home_blog_more_btn_link',
        array(
            'label' => esc_html__('View More Button Link', 'spicebox'),
            'section' => 'busicare_latest_news_section',
            'type' => 'text',
            'active_callback' => 'spiceb_busicare_news_callback'
	));

	//Add option target
	$wp_customize->add_setting(
	        'home_blog_more_btn_link_target',
	        array('sanitize_callback' => 'spiceb_busicare_sanitize_checkbox',
	            'transport' => $selective_refresh,
	));

	$wp_customize->add_control(
	        'home_blog_more_btn_link_target',
	        array(
	            'type' => 'checkbox',
	            'label' => esc_html__('Open link in new tab', 'spicebox'),
	            'section' => 'busicare_latest_news_section',
	            'active_callback' => 'spiceb_busicare_news_callback'
	        )
	);

	/**
	 * Add selective refresh for Front page news section controls.
	 */
	$wp_customize->selective_refresh->add_partial('home_news_section_title', array(
	    'selector' => '.home-blog .section-header h2',
	    'settings' => 'home_news_section_title',
	    'render_callback' => 'spiceb_home_news_section_title_render_callback',
	));

	$wp_customize->selective_refresh->add_partial('home_news_section_discription', array(
	    'selector' => '.home-blog .section-header h5',
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