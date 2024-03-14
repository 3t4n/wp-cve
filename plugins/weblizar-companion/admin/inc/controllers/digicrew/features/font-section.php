<?php 
defined( 'ABSPATH' ) or die();
class wl_fontfamily_customizer {
    
public static function wl_digicrew_fontfamily_customizer( $wp_customize ) {
/**
------------------------------------------------------------
SECTION: Fonts
------------------------------------------------------------
**/
$wp_customize->add_section('section_fonts', array(
    'label'      => __( 'Typography',  WL_COMPANION_DOMAIN),
    'title'     => esc_html__('Typography', 'mytheme'),
    'priority'  => 46,
    'panel'     => 'digicrew_theme_option',
));

    $wp_customize->add_setting(
        'main_google_font_list',
        array(
            'type'              => 'theme_mod',
            'default'           => '',
            'transport'         => 'refresh',
            'sanitize_callback' => 'digicrew_sanitize_text',
            'capability'        => 'edit_theme_options',
        )
    );
   

 require(WL_COMPANION_PLUGIN_DIR_PATH . 'admin/inc/controllers/digicrew/functions/font-functions.php');
    $wp_customize->add_control( 
        new digicrew_Font_Control( 
            $wp_customize, 
            'main_google_font_list', 
            array(
                'label'      => __( 'Title Font',  WL_COMPANION_DOMAIN),
                'section'    => 'section_fonts',
                'settings'   => 'main_google_font_list',
                'type'       => 'text',
            ) 
        ) 
    );

    $wp_customize->add_setting('body_font', array(
               'title'       => esc_html__(' Body Links Font ', WL_COMPANION_DOMAIN),
               'type'        => 'theme_mod',
               'default'     =>  esc_html__('Aleo', WL_COMPANION_DOMAIN),
               'sanitize_callback'  => 'digicrew_sanitize_text',
               'capability'         => 'edit_theme_options',
    ));
     $wp_customize->add_control(new digicrew_Font_Control( $wp_customize , 'body_font', array(
               'label'      => __('Body Links Font',  WL_COMPANION_DOMAIN ),
               'section'    => 'section_fonts',
               'settings'   => 'body_font',
               'type'       => 'text',
     )));

    $wp_customize->add_setting('para_font', array(
               'title'       => esc_html__('Description ', WL_COMPANION_DOMAIN),
               'type'        => 'theme_mod',
               'default'     =>  esc_html__('Aleo', WL_COMPANION_DOMAIN),
               'sanitize_callback'  => 'digicrew_sanitize_text',
               'capability'         => 'edit_theme_options',
    ));
     $wp_customize->add_control(new digicrew_Font_Control( $wp_customize , 'para_font', array(
               'label'      => __('Description Font',  WL_COMPANION_DOMAIN ),
               'section'    => 'section_fonts',
               'settings'   => 'para_font',
               'type'       => 'text',
     )));

     //footer_a_font

    $wp_customize->add_setting('footer_a_font', array(
               'title'       => esc_html__('Footer Links Font', WL_COMPANION_DOMAIN),
               'type'        => 'theme_mod',
               'default'     =>  esc_html__('Aleo', WL_COMPANION_DOMAIN),
               'sanitize_callback'  => 'digicrew_sanitize_text',
               'capability'         => 'edit_theme_options',
    ));
     $wp_customize->add_control(new digicrew_Font_Control( $wp_customize , 'footer_a_font', array(
               'label'      => __('Footer links Font',  WL_COMPANION_DOMAIN ),
               'section'    => 'section_fonts',
               'settings'   => 'footer_a_font',
               'type'       => 'text',
     )));

    $wp_customize->add_setting('font-w-title', array(
               'title'       => esc_html__('Title Font Weight', WL_COMPANION_DOMAIN),
               'type'        => 'theme_mod',
               'default'     =>  esc_html__('700', WL_COMPANION_DOMAIN),
               'sanitize_callback'  => 'digicrew_sanitize_text',
               'capability'         => 'edit_theme_options',
    ));
     $wp_customize->add_control( 'font-w-title', array(
               'label'      => __('Title Font Weight',  WL_COMPANION_DOMAIN ),
               'section'    => 'section_fonts',
               'settings'   => 'font-w-title',
               'type'       => 'number',
     ));
         $wp_customize->add_setting('font-w-link', array(
               'title'       => esc_html__('Links Font Weight', WL_COMPANION_DOMAIN),
               'type'        => 'theme_mod',
               'default'     =>  esc_html__('500', WL_COMPANION_DOMAIN),
               'sanitize_callback'  => 'digicrew_sanitize_text',
               'capability'         => 'edit_theme_options',
    ));
     $wp_customize->add_control( 'font-w-link', array(
               'label'      => __('Links Font Weight',  WL_COMPANION_DOMAIN ),
               'section'    => 'section_fonts',
               'settings'   => 'font-w-link',
               'type'       => 'number',
     ));
              $wp_customize->add_setting('font-w-des', array(
               'title'       => esc_html__('Description Font Weight', WL_COMPANION_DOMAIN),
               'type'        => 'theme_mod',
               'default'     =>  esc_html__('400', WL_COMPANION_DOMAIN),
               'sanitize_callback'  => 'digicrew_sanitize_text',
               'capability'         => 'edit_theme_options',
    ));
     $wp_customize->add_control( 'font-w-des', array(
               'label'      => __('Description Font Weight',  WL_COMPANION_DOMAIN ),
               'section'    => 'section_fonts',
               'settings'   => 'font-w-des',
               'type'       => 'number',
     ));
     


}}
