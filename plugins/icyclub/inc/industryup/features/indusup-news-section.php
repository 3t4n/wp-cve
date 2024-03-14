<?php //News
if ( ! function_exists( 'icycp_industryup_news_customize_register' ) ) :
function icycp_industryup_news_customize_register($wp_customize){
$selective_refresh = isset( $wp_customize->selective_refresh ) ? 'postMessage' : 'refresh';

$wp_customize->add_section( 'news_section' , array(
		'title' => __('News settings', 'icyclub'),
		'panel' => 'homepage_sections',
		'priority' => 40
   	) );
	
	$wp_customize->add_setting( 'news_section_show',
		   array(
			  'default' => 1,
			  'transport' => 'refresh',
			  'sanitize_callback' => 'icycp_industryup_switch_sanitization'
		   )
		);
		 
		$wp_customize->add_control( new Icycp_Industryup_Toggle_Switch_Custom_control( $wp_customize, 'news_section_show',
		   array(
			  'label' => esc_html__( 'News Enable/Disable','icyclub'),
			  'section' => 'news_section'
		   )
	) );

    $wp_customize->add_setting(
        'news_section_title', array(
        'capability' => 'edit_theme_options',
        'default' => __('Latest News','icyclub'),
        'transport' => $selective_refresh
    ) );
    $wp_customize->add_control( 'news_section_title', array(
        'label' => __('Title', 'consultup'),
        'section' => 'news_section',
        'type' => 'text',
    ) );

    $wp_customize->add_setting(
        'news_section_subtitle', array(
        'capability' => 'edit_theme_options',
        'default' => __('Our Blog','industryup'),
        'transport' => $selective_refresh
    ) );
    $wp_customize->add_control( 'news_section_subtitle', array(
        'label' => __('Subtitle', 'industryup'),
        'section' => 'news_section',
        'type' => 'text',
    ) );
	
	
	$wp_customize->add_setting(
		'news_section_description', array(
        'capability' => 'edit_theme_options',
		'default' => 'Excepteur sint occaecat cupidatat non proide',
		'transport' => $selective_refresh
    ) );
    $wp_customize->add_control( 'news_section_description', array(
        'label' => __('News section description', 'icyclub'),
        'section' => 'news_section',
        'type' => 'textarea',
    ) );


    // date in header display type
    $wp_customize->add_setting( 'consultup_post_content_type', array(
        'default'           => 'content',
        'capability'        => 'edit_theme_options',
    ) );

    $wp_customize->add_control( 'consultup_post_content_type', array(
        'type'     => 'radio',
        'label'    => esc_html__( 'Blog Post Content Type', 'consultup' ),
        'choices'  => array(
            'content'          => esc_html__( 'Content', 'consultup' ),
            'excerpt' => esc_html__( 'Excerpt', 'consultup' ),
        ),
        'section'  => 'news_section',
        'settings' => 'consultup_post_content_type',
    ) );


    $wp_customize->add_setting(
		'consultup_excerpt_length', array(
        'capability' => 'edit_theme_options',
		'default' => __('180','consultup'),
    ) );
    $wp_customize->add_control( 'consultup_excerpt_length', array(
        'label' => __('Excerpt length', 'icyclub'),
        'section' => 'news_section',
        'type' => 'number',
    ) );



    $wp_customize->add_setting(
		'news_section_post_count', array(
        'capability' => 'edit_theme_options',
		'default' => __('3','icyclub'),
    ) );
    $wp_customize->add_control( 'news_section_post_count', array(
        'label' => __('Number of Items', 'icyclub'),
        'section' => 'news_section',
        'type' => 'select',
        'choices' => array('3'=>__('3', 'icyclub'),'6' => __('6','icyclub'), '9' => __('9','icyclub'),'12'=> __('12','icyclub')),
    ) );
}

add_action( 'customize_register', 'icycp_industryup_news_customize_register' );
endif;


/**
 * Add selective refresh for Front page section section controls.
 */
function icypb_industryup_home_news_register_section_partials( $wp_customize ){

	//News Title
	$wp_customize->selective_refresh->add_partial( 'news_section_title', array(
		'selector'            => '.blog .bs-heading h3',
		'settings'            => 'news_section_title',
		'render_callback'  => 'icypb_consultco_news_section_title_render_callback',
	
	) );

    //News Description
    $wp_customize->selective_refresh->add_partial( 'news_section_subtitle', array(
        'selector'            => '.blog .bs-heading h2',
        'settings'            => 'news_section_subtitle',
        'render_callback'  => 'icypb_consultco_news_section_subtitle_render_callback',
    
    ) );
	
	//News Description
	$wp_customize->selective_refresh->add_partial( 'news_section_description', array(
		'selector'            => '.blog .bs-heading p',
		'settings'            => 'news_section_description',
		'render_callback'  => 'icypb_consultco_news_section_description_render_callback',
	
	) );

    
}

add_action( 'customize_register', 'icypb_industryup_home_news_register_section_partials' );


// feature_title
function icypb_consultco_news_section_title_render_callback() {
    return get_theme_mod( 'news_section_title' );
}

// feature_subtitle
function icypb_consultco_news_section_subtitle_render_callback() {
    return get_theme_mod( 'news_section_subtitle' );
}

// feature description
function icypb_consultco_news_section_description_render_callback() {
    return get_theme_mod( 'news_section_description' );
}