<?php 
$wp_customize->add_setting( 'royal_shop_disable_highlight_sec', array(
                'default'               => false,
                'sanitize_callback'     => 'royal_shop_sanitize_checkbox',
            ) );
$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'royal_shop_disable_highlight_sec', array(
                'label'                 => esc_html__('Section On/Off', 'royal-shop'),
                'type'                  => 'checkbox',
                'section'               => 'royal_shop_highlight',
                'settings'              => 'royal_shop_disable_highlight_sec',
            ) ) );


//Highlight Content Via Repeater
      if ( class_exists( 'Z_COMPANION_Royal_Shop_Repeater' ) ) {
            $wp_customize->add_setting(
        'royal_shop_highlight_content', array(
        'sanitize_callback' => 'royal_shop_Repeater_sanitize',  
        'default'           => Z_COMPANION_Royal_Shop_Defaults_Models::instance()->get_feature_default(),
                )
            );

            $wp_customize->add_control(
                new Z_COMPANION_Royal_Shop_Repeater(
                    $wp_customize, 'royal_shop_highlight_content', array(
                        'label'                                => esc_html__( 'Services Content', 'royal-shop' ),
                        'section'                              => 'royal_shop_highlight',
                        'priority'                             => 15,
                        'add_field_label'                      => esc_html__( 'Add new Service', 'royal-shop' ),
                        'item_name'                            => esc_html__( 'Service', 'royal-shop' ),
                        
                        'customizer_repeater_title_control'    => true, 
                        'customizer_repeater_color_control'		=>	false, 
                        'customizer_repeater_color2_control' 	=> false,
                        'customizer_repeater_icon_control'	   => true,
                        'customizer_repeater_subtitle_control' => true, 

                        'customizer_repeater_text_control'    => false,  

                        'customizer_repeater_image_control'    => false,  
                        'customizer_repeater_link_control'     => false,
                        'customizer_repeater_repeater_control' => false,  
                                         
                        
                    ),'royal_shop_Ship_Repeater'
                )
            );
        }


  $wp_customize->add_setting('royal_shop_highlight_doc', array(
    'sanitize_callback' => 'royal_shop_sanitize_text',
    ));
  $wp_customize->add_control(new royal_shop_Misc_Control( $wp_customize, 'royal_shop_highlight_doc',
            array(
        'section'     => 'royal_shop_highlight',
        'type'        => 'doc-link',
        'url'         => 'https://WpZita.com/docs/royal-shop/#highlight-section',
        'description' => esc_html__( 'Feel difficulty, Explore our', 'royal-shop' ),
        'priority'   =>100,
    )));