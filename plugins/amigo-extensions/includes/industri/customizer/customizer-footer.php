<?php  
// customizer page templates settings
add_action( 'customize_register', 'amigo_industri_customizer_footer');
function amigo_industri_customizer_footer( $wp_customize ) {

    $default = amigo_industri_default_settings();

    $selective_refresh = isset( $wp_customize->selective_refresh ) ? 'postMessage' : 'refresh';
   

    /***************************************************************
        //footer top
    *****************************************************************/ 

    // footer top section
    $wp_customize->add_section('footer_top', array(
        'title'    => esc_html__( 'Footer Above', 'amigo-extensions' ),
        'panel' => 'theme_footer',
        'priority' => 0, 
    )); 

    // selective refresh
    $wp_customize->selective_refresh->add_partial( 'footer_top_items', array(
        'selector'            => '.footer-contact .container',              
        'render_callback'  => function() { return get_theme_mod( 'footer_top_items' ); },
    ) );

    // footer top content
    $wp_customize->add_setting( 'footer_top_items', array(
        'sanitize_callback' => 'amigo_repeater_sanitize',
        'default' => amigo_industri_default_footer_above(),
        'priority' => 2,
    ));

    $wp_customize->add_control( new Amigo_Customizer_Repeater( $wp_customize, 'footer_top_items', array(
        'label'   => esc_html__('Footer Top Items','amigo-extensions'),
        'item_name' => esc_html__( 'Item', 'amigo-extensions' ),
        'section' => 'footer_top',     
        'customizer_repeater_image_control' => false,
        'customizer_repeater_title_control' => true,
        'customizer_repeater_subtitle_control' => false,
        'customizer_repeater_text_control' => false,
        'customizer_repeater_link_control' => false,
        'customizer_repeater_text2_control'=> false,     
        'customizer_repeater_link2_control' => false,
        'customizer_repeater_button2_control' => false,
        'customizer_repeater_slide_align' => false,
        'customizer_repeater_icon_control' => true,     
        'customizer_repeater_checkbox_control' => false,
                                

    ) ) );

     

}