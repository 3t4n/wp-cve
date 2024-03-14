<?php 
if ( ! defined( 'ABSPATH' ) ) exit; 
$wp_customize->add_setting( 'royal_shop_disable_banner_sec', array(
                'default'               => false,
                'sanitize_callback'     => 'z_companion_sanitize_checkbox',
            ) );
$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'royal_shop_disable_banner_sec', array(
                'label'                 => esc_html__('Section On/Off', 'z-companion'),
                'type'                  => 'checkbox',
                'section'               => 'royal_shop_banner',
                'settings'              => 'royal_shop_disable_banner_sec',
            ) ) );
// choose col layout
if(class_exists('Royal_Shop_WP_Customize_Control_Radio_Image')){
        $wp_customize->add_setting(
            'royal_shop_banner_layout', array(
                'default'           => 'bnr-three',
                'sanitize_callback' => 'z_companion_sanitize_radio',
            )
        );
$wp_customize->add_control(
            new Royal_Shop_WP_Customize_Control_Radio_Image(
                $wp_customize, 'royal_shop_banner_layout', array(
                    'label'    => esc_html__( 'Layout', 'z-companion' ),
                    'section'  => 'royal_shop_banner',
                    'choices'  => array(
                        'bnr-one'   => array(
                            'url'  => ROYAL_SHOP_BANNER_IMG_LAYOUT_1,
                        ),
                        'bnr-three' => array(
                            'url'   => ROYAL_SHOP_BANNER_IMG_LAYOUT_3,
                        ),
                        'bnr-two'   => array(
                            'url'   => ROYAL_SHOP_BANNER_IMG_LAYOUT_2,
                        ),
                        'bnr-four' => array(
                            'url'  => ROYAL_SHOP_BANNER_IMG_LAYOUT_4,
                        ),
                        'bnr-five' => array(
                            'url'  => ROYAL_SHOP_BANNER_IMG_LAYOUT_5,
                        ),
                        'bnr-six' => array(
                            'url'  => ROYAL_SHOP_BANNER_IMG_LAYOUT_5,
                        ),
                        
                    ),
                )
            )
        );
    } 
// first image
$wp_customize->add_setting('royal_shop_bnr_1_img', array(
        'default'       => '',
        'capability'    => 'edit_theme_options',
        'sanitize_callback' => 'z_companion_sanitize_upload',
    ));
$wp_customize->add_control( new WP_Customize_Image_Control($wp_customize, 'royal_shop_bnr_1_img', array(
        'label'          => __('Image 1', 'z-companion'),
        'section'        => 'royal_shop_banner',
        'settings'       => 'royal_shop_bnr_1_img',
 )));
// first url
$wp_customize->add_setting('royal_shop_bnr_1_url', array(
        'default' =>'',
        'capability'        => 'edit_theme_options',
        'sanitize_callback' => 'z_companion_sanitize_text',
));
$wp_customize->add_control( 'royal_shop_bnr_1_url', array(
        'label'    => __('url', 'z-companion'),
        'section'  => 'royal_shop_banner',
         'type'    => 'text',
));
// second image
$wp_customize->add_setting('royal_shop_bnr_2_img', array(
        'default'       => '',
        'capability'    => 'edit_theme_options',
        'sanitize_callback' => 'z_companion_sanitize_upload',
    ));
$wp_customize->add_control( new WP_Customize_Image_Control($wp_customize, 'royal_shop_bnr_2_img', array(
        'label'          => __('Image 2', 'z-companion'),
        'section'        => 'royal_shop_banner',
        'settings'       => 'royal_shop_bnr_2_img',
 )));

// second url
$wp_customize->add_setting('royal_shop_bnr_2_url', array(
        'default' =>'',
        'capability'        => 'edit_theme_options',
        'sanitize_callback' => 'z_companion_sanitize_text',
));
$wp_customize->add_control( 'royal_shop_bnr_2_url', array(
        'label'    => __('url', 'z-companion'),
        'section'  => 'royal_shop_banner',
         'type'    => 'text',
));

// third image
$wp_customize->add_setting('royal_shop_bnr_3_img', array(
        'default'       => '',
        'capability'    => 'edit_theme_options',
        'sanitize_callback' => 'z_companion_sanitize_upload',
    ));
$wp_customize->add_control( new WP_Customize_Image_Control($wp_customize, 'royal_shop_bnr_3_img', array(
        'label'          => __('Image 3', 'z-companion'),
        'section'        => 'royal_shop_banner',
        'settings'       => 'royal_shop_bnr_3_img',
 )));

// third url
$wp_customize->add_setting('royal_shop_bnr_3_url', array(
        'default' =>'',
        'capability'        => 'edit_theme_options',
        'sanitize_callback' => 'z_companion_sanitize_text',
));
$wp_customize->add_control( 'royal_shop_bnr_3_url', array(
        'label'    => __('url', 'z-companion'),
        'section'  => 'royal_shop_banner',
         'type'    => 'text',
));
// fourth image
$wp_customize->add_setting('royal_shop_bnr_4_img', array(
        'default'       => '',
        'capability'    => 'edit_theme_options',
        'sanitize_callback' => 'z_companion_sanitize_upload',
    ));
$wp_customize->add_control( new WP_Customize_Image_Control($wp_customize, 'royal_shop_bnr_4_img', array(
        'label'          => __('Image 4', 'z-companion'),
        'section'        => 'royal_shop_banner',
        'settings'       => 'royal_shop_bnr_4_img',
 )));
$wp_customize->add_setting('royal_shop_bnr_4_url', array(
        'default' =>'',
        'capability'        => 'edit_theme_options',
        'sanitize_callback' => 'z_companion_sanitize_text',
));
$wp_customize->add_control( 'royal_shop_bnr_4_url', array(
        'label'    => __('url', 'z-companion'),
        'section'  => 'royal_shop_banner',
         'type'    => 'text',
));

// fifth image
$wp_customize->add_setting('royal_shop_bnr_5_img', array(
        'default'       => '',
        'capability'    => 'edit_theme_options',
        'sanitize_callback' => 'z_companion_sanitize_upload',
    ));
$wp_customize->add_control( new WP_Customize_Image_Control($wp_customize, 'royal_shop_bnr_5_img', array(
        'label'          => __('Image 5', 'z-companion'),
        'section'        => 'royal_shop_banner',
        'settings'       => 'royal_shop_bnr_5_img',
 )));
$wp_customize->add_setting('royal_shop_bnr_5_url', array(
        'default' =>'',
        'capability'        => 'edit_theme_options',
        'sanitize_callback' => 'z_companion_sanitize_text',
));
$wp_customize->add_control( 'royal_shop_bnr_5_url', array(
        'label'    => __('url', 'z-companion'),
        'section'  => 'royal_shop_banner',
         'type'    => 'text',
));
$wp_customize->add_setting('royal_shop_bnr_doc', array(
    'sanitize_callback' => 'z_companion_sanitize_text',
    ));
$wp_customize->add_control(new royal_shop_Misc_Control( $wp_customize, 'royal_shop_bnr_doc',
            array(
        'section'     => 'royal_shop_banner',
        'type'        => 'doc-link',
        'url'         => 'https://wpzita.com/docs/ad-banner/',
        'description' => esc_html__( 'Feel difficulty, Explore our', 'z-companion' ),
        'priority'   =>100,
    )));