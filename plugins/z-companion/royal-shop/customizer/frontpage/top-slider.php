<?php
if ( ! defined( 'ABSPATH' ) ) exit; 
$wp_customize->add_setting( 'royal_shop_disable_top_slider_sec', array(
                'default'               => false,
                'sanitize_callback'     => 'z_companion_sanitize_checkbox',
            ) );
$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'royal_shop_disable_top_slider_sec', array(
                'label'                 => esc_html__('Section On/Off', 'z-companion'),
                'type'                  => 'checkbox',
                'section'               => 'royal_shop_top_slider_section',
                'settings'              => 'royal_shop_disable_top_slider_sec',
            ) ) );

if(class_exists('Royal_Shop_WP_Customize_Control_Radio_Image')){
        $wp_customize->add_setting(
            'royal_shop_top_slide_layout', array(
                'default'           => 'slide-layout-5',
                'sanitize_callback' => 'z_companion_sanitize_radio',
            )
        );
$wp_customize->add_control(
            new Royal_Shop_WP_Customize_Control_Radio_Image(
                $wp_customize, 'royal_shop_top_slide_layout', array(
                    'label'    => esc_html__( 'Slider Layout', 'z-companion' ),
                    'section'  => 'royal_shop_top_slider_section',
                    'choices'  => array(
                        'slide-layout-5' => array(
                            'url' => ROYAL_SHOP_SLIDER_LAYOUT_5,
                        ),
                         'slide-layout-6' => array(
                            'url' => ROYAL_SHOP_SLIDER_LAYOUT_6,
                        ),
                        'slide-layout-2'   => array(
                            'url' =>ROYAL_SHOP_SLIDER_LAYOUT_2,
                        ),
                        'slide-layout-3' => array(
                            'url' => ROYAL_SHOP_SLIDER_LAYOUT_3,
                        ),
                        'slide-layout-4'   => array(
                            'url' =>ROYAL_SHOP_SLIDER_LAYOUT_4,
                        ),                        
                                 
                    ),
                )
            )
        );
} 
//Slider Content Via Repeater
      if ( class_exists( 'Z_COMPANION_Royal_Shop_Repeater' ) ){
            $wp_customize->add_setting(
             'royal_shop_top_slide_content', array(
             'sanitize_callback' => 'z_companion_Repeater_sanitize',  
             'default'           => '',
                )
            );
            $wp_customize->add_control(
                new Z_COMPANION_Royal_Shop_Repeater(
                    $wp_customize, 'royal_shop_top_slide_content', array(
                        'label'                                => esc_html__( 'Slide Content', 'z-companion' ),
                        'section'                              => 'royal_shop_top_slider_section',
                        'add_field_label'                      => esc_html__( 'Add new Slide', 'z-companion' ),
                        'item_name'                            => esc_html__( 'Slide', 'z-companion' ),
                        
                        'customizer_repeater_title_control'    => true,   
                        'customizer_repeater_subtitle_control'    => true, 
                        'customizer_repeater_text_control'    => true,  
                        'customizer_repeater_image_control'    => true, 
                        'customizer_repeater_logo_image_control'    => true,  
                        'customizer_repeater_link_control'     => true,
                        'customizer_repeater_repeater_control' => false,  
                                         
                        
                    ),'royal_shop_top_slide_content'
                )
            );
        }
//Slider 5th Content Via Repeater
      if ( class_exists( 'Z_COMPANION_Royal_Shop_Repeater' ) ){
            $wp_customize->add_setting(
             'royal_shop_top_slide_lay5_content', array(
             'sanitize_callback' => 'z_companion_Repeater_sanitize',  
             'default'           => '',
                )
            );
            $wp_customize->add_control(
                new Z_COMPANION_Royal_Shop_Repeater(
                    $wp_customize, 'royal_shop_top_slide_lay5_content', array(
                        'label'                                => esc_html__( 'Slide Content', 'z-companion' ),
                        'section'                              => 'royal_shop_top_slider_section',
                        'add_field_label'                      => esc_html__( 'Add new Slide', 'z-companion' ),
                        'item_name'                            => esc_html__( 'Slide', 'z-companion' ),
                        
                        'customizer_repeater_title_control'    => true,   
                        'customizer_repeater_subtitle_control'    => true, 
                        'customizer_repeater_text_control'    => true,  
                        'customizer_repeater_image_control'    => true, 
                        'customizer_repeater_logo_image_control'    => false,  
                        'customizer_repeater_link_control'     => true,
                        'customizer_repeater_repeater_control' => false,  
                                         
                        
                    ),'royal_shop_top_slide_lay5_content'
                )
            );
        }
        // Add an option to disable the logo.
  $wp_customize->add_setting( 'royal_shop_top_slider_optn', array(
    'default'           => false,
    'sanitize_callback' => 'z_companion_sanitize_checkbox',
  ) );
  $wp_customize->add_control( new royal_shop_Toggle_Control( $wp_customize, 'royal_shop_top_slider_optn', array(
    'label'       => esc_html__( 'Slide Auto Play', 'z-companion' ),
    'section'     => 'royal_shop_top_slider_section',
    'type'        => 'toggle',
    'settings'    => 'royal_shop_top_slider_optn',
  ) ) );

  $wp_customize->add_setting('royal_shop_top_slider_speed', array(
        'default' =>'3000',
        'capability'        => 'edit_theme_options',
        'sanitize_callback' => 'z_companion_sanitize_number',
));
$wp_customize->add_control( 'royal_shop_top_slider_speed', array(
        'label'    => __('Speed', 'z-companion'),
        'description' =>__('Interval (in milliseconds) to go for next slide since the previous stopped if the slider is auto playing, default value is 3000','z-companion'),
        'section'  => 'royal_shop_top_slider_section',
         'type'        => 'number',
));
// slider-layout-2
$wp_customize->add_setting('royal_shop_lay2_adimg', array(
        'default'       => '',
        'capability'    => 'edit_theme_options',
        'sanitize_callback' => 'z_companion_sanitize_upload',
    ));
$wp_customize->add_control( new WP_Customize_Image_Control($wp_customize, 'royal_shop_lay2_adimg', array(
        'label'          => __('Right Col Image', 'z-companion'),
        'section'        => 'royal_shop_top_slider_section',
        'settings'       => 'royal_shop_lay2_adimg',
 )));
$wp_customize->add_setting('royal_shop_lay2_url', array(
        'default' =>'',
        'capability'        => 'edit_theme_options',
        'sanitize_callback' => 'z_companion_sanitize_text',
));
$wp_customize->add_control( 'royal_shop_lay2_url', array(
        'label'    => __('url', 'z-companion'),
        'section'  => 'royal_shop_top_slider_section',
         'type'    => 'text',
));

// slider-layout-3
$wp_customize->add_setting('royal_shop_lay3_adimg', array(
        'default'       => '',
        'capability'    => 'edit_theme_options',
        'sanitize_callback' => 'z_companion_sanitize_upload',
    ));
$wp_customize->add_control( new WP_Customize_Image_Control($wp_customize, 'royal_shop_lay3_adimg', array(
        'label'          => __('Right Col Image 1', 'z-companion'),
        'section'        => 'royal_shop_top_slider_section',
        'settings'       => 'royal_shop_lay3_adimg',
 )));
$wp_customize->add_setting('royal_shop_lay3_url', array(
        'default' =>'',
        'capability'        => 'edit_theme_options',
        'sanitize_callback' => 'z_companion_sanitize_text',
));
$wp_customize->add_control( 'royal_shop_lay3_url', array(
        'label'    => __('url', 'z-companion'),
        'section'  => 'royal_shop_top_slider_section',
         'type'    => 'text',
));
$wp_customize->add_setting('royal_shop_lay3_adimg2', array(
        'default'       => '',
        'capability'    => 'edit_theme_options',
        'sanitize_callback' => 'z_companion_sanitize_upload',
    ));
$wp_customize->add_control( new WP_Customize_Image_Control($wp_customize, 'royal_shop_lay3_adimg2', array(
        'label'          => __('Right Col Image 2', 'z-companion'),
        'section'        => 'royal_shop_top_slider_section',
        'settings'       => 'royal_shop_lay3_adimg2',
 )));
$wp_customize->add_setting('royal_shop_lay3_2url', array(
        'default' =>'',
        'capability'        => 'edit_theme_options',
        'sanitize_callback' => 'z_companion_sanitize_text',
));
$wp_customize->add_control( 'royal_shop_lay3_2url', array(
        'label'    => __('url', 'z-companion'),
        'section'  => 'royal_shop_top_slider_section',
         'type'    => 'text',
));

$wp_customize->add_setting('royal_shop_lay3_adimg3', array(
        'default'       => '',
        'capability'    => 'edit_theme_options',
        'sanitize_callback' => 'z_companion_sanitize_upload',
    ));
$wp_customize->add_control( new WP_Customize_Image_Control($wp_customize, 'royal_shop_lay3_adimg3', array(
        'label'          => __('Right Col Image 3', 'z-companion'),
        'section'        => 'royal_shop_top_slider_section',
        'settings'       => 'royal_shop_lay3_adimg3',
 )));
$wp_customize->add_setting('royal_shop_lay3_3url', array(
        'default' =>'',
        'capability'        => 'edit_theme_options',
        'sanitize_callback' => 'z_companion_sanitize_text',
));
$wp_customize->add_control( 'royal_shop_lay3_3url', array(
        'label'    => __('url', 'z-companion'),
        'section'  => 'royal_shop_top_slider_section',
         'type'    => 'text',
));

// slider-layout-4
$wp_customize->add_setting('royal_shop_lay4_adimg1', array(
        'default'       => '',
        'capability'    => 'edit_theme_options',
        'sanitize_callback' => 'z_companion_sanitize_upload',
    ));
$wp_customize->add_control( new WP_Customize_Image_Control($wp_customize, 'royal_shop_lay4_adimg1', array(
        'label'          => __('Right Col Image 1', 'z-companion'),
        'section'        => 'royal_shop_top_slider_section',
        'settings'       => 'royal_shop_lay4_adimg1',
 )));
$wp_customize->add_setting('royal_shop_lay4_url1', array(
        'default' =>'',
        'capability'        => 'edit_theme_options',
        'sanitize_callback' => 'z_companion_sanitize_text',
));
$wp_customize->add_control( 'royal_shop_lay4_url1', array(
        'label'    => __('url', 'z-companion'),
        'section'  => 'royal_shop_top_slider_section',
         'type'    => 'text',
));

$wp_customize->add_setting('royal_shop_lay4_adimg2', array(
        'default'       => '',
        'capability'    => 'edit_theme_options',
        'sanitize_callback' => 'z_companion_sanitize_upload',
    ));
$wp_customize->add_control( new WP_Customize_Image_Control($wp_customize, 'royal_shop_lay4_adimg2', array(
        'label'          => __('Right Col Image 2', 'z-companion'),
        'section'        => 'royal_shop_top_slider_section',
        'settings'       => 'royal_shop_lay4_adimg2',
 )));
$wp_customize->add_setting('royal_shop_lay4_url2', array(
        'default' =>'',
        'capability'        => 'edit_theme_options',
        'sanitize_callback' => 'z_companion_sanitize_text',
));
$wp_customize->add_control( 'royal_shop_lay4_url2', array(
        'label'    => __('url', 'z-companion'),
        'section'  => 'royal_shop_top_slider_section',
         'type'    => 'text',
));

// slider-layout-6
$wp_customize->add_setting('royal_shop_lay6_adimg', array(
        'default'       => '',
        'capability'    => 'edit_theme_options',
        'sanitize_callback' => 'royal_shop_sanitize_upload',
    ));
$wp_customize->add_control( new WP_Customize_Image_Control($wp_customize, 'royal_shop_lay6_adimg', array(
        'label'          => __('Right Col Image 1', 'royal-shop'),
        'section'        => 'royal_shop_top_slider_section',
        'settings'       => 'royal_shop_lay6_adimg',
 )));
$wp_customize->add_setting('royal_shop_lay6_url', array(
        'default' =>'',
        'capability'        => 'edit_theme_options',
        'sanitize_callback' => 'royal_shop_sanitize_text',
));
$wp_customize->add_control( 'royal_shop_lay6_url', array(
        'label'    => __('url', 'royal-shop'),
        'section'  => 'royal_shop_top_slider_section',
         'type'    => 'text',
));
$wp_customize->add_setting('royal_shop_lay6_adimg2', array(
        'default'       => '',
        'capability'    => 'edit_theme_options',
        'sanitize_callback' => 'royal_shop_sanitize_upload',
    ));
$wp_customize->add_control( new WP_Customize_Image_Control($wp_customize, 'royal_shop_lay6_adimg2', array(
        'label'          => __('Right Col Image 2', 'royal-shop'),
        'section'        => 'royal_shop_top_slider_section',
        'settings'       => 'royal_shop_lay6_adimg2',
 )));
$wp_customize->add_setting('royal_shop_lay6_2url', array(
        'default' =>'',
        'capability'        => 'edit_theme_options',
        'sanitize_callback' => 'royal_shop_sanitize_text',
));
$wp_customize->add_control( 'royal_shop_lay6_2url', array(
        'label'    => __('url', 'royal-shop'),
        'section'  => 'royal_shop_top_slider_section',
         'type'    => 'text',
));

$wp_customize->add_setting('royal_shop_lay6_adimg3', array(
        'default'       => '',
        'capability'    => 'edit_theme_options',
        'sanitize_callback' => 'royal_shop_sanitize_upload',
    ));
$wp_customize->add_control( new WP_Customize_Image_Control($wp_customize, 'royal_shop_lay6_adimg3', array(
        'label'          => __('Right Col Image 3', 'royal-shop'),
        'section'        => 'royal_shop_top_slider_section',
        'settings'       => 'royal_shop_lay6_adimg3',
 )));
$wp_customize->add_setting('royal_shop_lay6_3url', array(
        'default' =>'',
        'capability'        => 'edit_theme_options',
        'sanitize_callback' => 'royal_shop_sanitize_text',
));
$wp_customize->add_control( 'royal_shop_lay6_3url', array(
        'label'    => __('url', 'royal-shop'),
        'section'  => 'royal_shop_top_slider_section',
         'type'    => 'text',
));

$wp_customize->add_setting('royal_shop_top_slider_doc', array(
    'sanitize_callback' => 'z_companion_sanitize_text',
    ));
$wp_customize->add_control(new royal_shop_Misc_Control( $wp_customize, 'royal_shop_top_slider_doc',
            array(
        'section'    => 'royal_shop_top_slider_section',
        'type'      => 'doc-link',
        'url'       => 'https://wpzita.com/docs/main-slider/',
        'description' => esc_html__( 'Feel difficulty, Explore our', 'z-companion' ),
        'priority'   =>100,
    )));
if(class_exists('Heading')){
$wp_customize->add_setting('royal_shop_slider_collapse', array(
        'default'           => '',
        'sanitize_callback' => 'z_companion_sanitize_text',  
                )
            );
$wp_customize->add_control(
            new Heading(
                $wp_customize, 'royal_shop_slider_collapse', array(
                    'label'            => esc_html__( 'Slider', 'z-companion' ),
                    'section'          => 'royal_shop_top_slider_section',
                    'priority'         => 1,
                    'class'            => 'collapse-layout-accordion',
                    'accordion'        => true,
                    'expanded'         => false,
                    'controls_to_wrap' => 21,
                )
            )
        );
}