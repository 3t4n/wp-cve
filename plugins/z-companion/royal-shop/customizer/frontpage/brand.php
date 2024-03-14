<?php
if ( ! defined( 'ABSPATH' ) ) exit; 
$wp_customize->add_setting( 'royal_shop_disable_brand_sec', array(
                'default'               => false,
                'sanitize_callback'     => 'z_companion_sanitize_checkbox',
            ) );
$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'royal_shop_disable_brand_sec', array(
                'label'                 => esc_html__('Section On/Off', 'z-companion'),
                'type'                  => 'checkbox',
                'section'               => 'royal_shop_brand',
                'settings'              => 'royal_shop_disable_brand_sec',
            ) ) );
 //Brand Content Via Repeater
      if ( class_exists( 'Z_COMPANION_Royal_Shop_Repeater' ) ){
            $wp_customize->add_setting(
             'royal_shop_brand_content', array(
             'sanitize_callback' => 'z_companion_Repeater_sanitize',  
             'default'           => '',
                )
            );
            $wp_customize->add_control(
                new Z_COMPANION_Royal_Shop_Repeater(
                    $wp_customize, 'royal_shop_brand_content', array(
                        'label'                                => esc_html__( 'Brand Content', 'z-companion' ),
                        'section'                              => 'royal_shop_brand',
                        'add_field_label'                      => esc_html__( 'Add new Brand', 'z-companion' ),
                        'item_name'                            => esc_html__( 'Brand', 'z-companion' ),
                        
                        'customizer_repeater_title_control'    => false,   
                        'customizer_repeater_subtitle_control'    => false, 

                        'customizer_repeater_text_control'    => false,  

                        'customizer_repeater_image_control'    => true, 
                        'customizer_repeater_logo_image_control'    => false, 
                        'customizer_repeater_link_control'     => true,
                        'customizer_repeater_repeater_control' => false,  
                                         
                        
                    ),'royal_shop_Brand_Repeater'
                )
            );
        }

// Add an option to disable the logo.
  $wp_customize->add_setting( 'royal_shop_brand_slider_optn', array(
    'default'           => false,
    'sanitize_callback' => 'z_companion_sanitize_checkbox',
  ) );
  $wp_customize->add_control( new royal_shop_Toggle_Control( $wp_customize, 'royal_shop_brand_slider_optn', array(
    'label'       => esc_html__( 'Slide Auto Play', 'z-companion' ),
    'section'     => 'royal_shop_brand',
    'type'        => 'toggle',
    'settings'    => 'royal_shop_brand_slider_optn',
  ) ) );

  $wp_customize->add_setting('royal_shop_brand_slider_speed', array(
        'default' =>'3000',
        'capability'        => 'edit_theme_options',
        'sanitize_callback' => 'z_companion_sanitize_number',
    ));
  $wp_customize->add_control( 'royal_shop_brand_slider_speed', array(
            'label'       => __('Speed', 'z-companion'),
            'description' =>__('Interval (in milliseconds) to go for next slide since the previous stopped if the slider is auto playing, default value is 3000','z-companion'),
            'section'     => 'royal_shop_brand',
            'type'        => 'number',
  ));

  $wp_customize->add_setting('royal_shop_brand_slider_doc', array(
    'sanitize_callback' => 'z_companion_sanitize_text',
    ));
  $wp_customize->add_control(new royal_shop_Misc_Control( $wp_customize, 'royal_shop_brand_slider_doc',
            array(
        'section'     => 'royal_shop_brand',
        'type'        => 'doc-link',
        'url'         => 'https://wpzita.com/docs/brands-section/',
        'description' => esc_html__( 'Feel difficulty, Explore our', 'z-companion' ),
        'priority'   =>100,
    )));