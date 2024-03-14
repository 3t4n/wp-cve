<?php
if ( ! defined( 'ABSPATH' ) ) exit; 
$wp_customize->add_setting( 'royal_shop_disable_product_list_sec', array(
                'default'               => false,
                'sanitize_callback'     => 'z_companion_sanitize_checkbox',
            ) );
$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'royal_shop_disable_product_list_sec', array(
                'label'                 => esc_html__('Section On/Off', 'z-companion'),
                'type'                  => 'checkbox',
                'section'               => 'royal_shop_product_slide_list',
                'settings'              => 'royal_shop_disable_product_list_sec',
            ) ) );
// section heading
$wp_customize->add_setting('royal_shop_product_list_heading', array(
        'default' => __('ListView Slider','z-companion'),
        'capability'        => 'edit_theme_options',
        'sanitize_callback' => 'z_companion_sanitize_text',
        'transport'         => 'postMessage',
));
$wp_customize->add_control( 'royal_shop_product_list_heading', array(
        'label'    => __('Section Heading', 'z-companion'),
        'section'  => 'royal_shop_product_slide_list',
         'type'       => 'text',
));
//control setting for select options
    $wp_customize->add_setting('royal_shop_product_list_cat', array(
    'default' => 0,
    'sanitize_callback' => 'z_companion_sanitize_select',
    ) );
    $wp_customize->add_control( 'royal_shop_product_list_cat', array(
    'label'   => __('Select Category','z-companion'),
    'section' => 'royal_shop_product_slide_list',
    'type' => 'select',
    'choices' => z_companion_royal_shop_product_category_list(array('taxonomy' =>'product_cat'),true),
    ) );

$wp_customize->add_setting('royal_shop_product_list_optn', array(
        'default'        => 'recent',
        'capability'     => 'edit_theme_options',
        'sanitize_callback' => 'z_companion_sanitize_select',
    ));
$wp_customize->add_control('royal_shop_product_list_optn', array(
        'settings' => 'royal_shop_product_list_optn',
        'label'   => __('Choose Option','z-companion'),
        'section' => 'royal_shop_product_slide_list',
        'type'    => 'select',
        'choices'    => array(
        'recent'     => __('Recent','z-companion'),
        'featured'   => __('Featured','z-companion'),
        'random'     => __('Random','z-companion'),   
        ),
    ));

$wp_customize->add_setting( 'royal_shop_single_row_prdct_list', array(
                'default'               => false,
                'sanitize_callback'     => 'z_companion_sanitize_checkbox',
            ) );
$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'royal_shop_single_row_prdct_list', array(
                'label'                 => esc_html__('Enable Single Row Slide', 'z-companion'),
                'type'                  => 'checkbox',
                'section'               => 'royal_shop_product_slide_list',
                'settings'              => 'royal_shop_single_row_prdct_list',
            ) ) );


// Add an option to disable the logo.
  $wp_customize->add_setting( 'royal_shop_product_list_slide_optn', array(
    'default'           => false,
    'sanitize_callback' => 'z_companion_sanitize_checkbox',
  ) );
  $wp_customize->add_control( new royal_shop_Toggle_Control( $wp_customize, 'royal_shop_product_list_slide_optn', array(
    'label'       => esc_html__( 'Slide Auto Play', 'z-companion' ),
    'section'     => 'royal_shop_product_slide_list',
    'type'        => 'toggle',
    'settings'    => 'royal_shop_product_list_slide_optn',
  ) ) );
  $wp_customize->add_setting('royal_shop_product_list_slider_speed', array(
        'default' =>'3000',
        'capability'        => 'edit_theme_options',
        'sanitize_callback' => 'z_companion_sanitize_number',
    ));
    $wp_customize->add_control( 'royal_shop_product_list_slider_speed', array(
            'label'       => __('Speed', 'z-companion'),
            'description' =>__('Interval (in milliseconds) to go for next slide since the previous stopped if the slider is auto playing, default value is 3000','z-companion'),
            'section'     => 'royal_shop_product_slide_list',
             'type'       => 'number',
    ));
  $wp_customize->add_setting('royal_shop_product_list_slide_doc', array(
    'sanitize_callback' => 'z_companion_sanitize_text',
    ));
  $wp_customize->add_control(new royal_shop_Misc_Control( $wp_customize, 'royal_shop_product_list_slide_doc',
            array(
        'section'    => 'royal_shop_product_slide_list',
        'type'      => 'doc-link',
        'url'       => 'https://wpzita.com/docs/listview-slider/',
        'description' => esc_html__( 'Feel difficulty, Explore our', 'z-companion' ),
        'priority'   =>100,
    )));