<?php
if ( ! defined( 'ABSPATH' ) ) exit; 
$wp_customize->add_setting( 'royal_shop_disable_category_slide_sec', array(
                'default'               => false,
                'sanitize_callback'     => 'z_companion_sanitize_checkbox',
            ) );
$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'royal_shop_disable_category_slide_sec', array(
                'label'                 => esc_html__('Section On/Off', 'z-companion'),
                'type'                  => 'checkbox',
                'section'               => 'royal_shop_cat_slide_section',
                'settings'              => 'royal_shop_disable_category_slide_sec',
            ) ) );

// section heading
$wp_customize->add_setting('royal_shop_cat_slider_heading', array(
        'default' => __('Product Categories Section','z-companion'),
        'capability'        => 'edit_theme_options',
        'sanitize_callback' => 'z_companion_sanitize_text',
        'transport'         => 'postMessage',
));
$wp_customize->add_control( 'royal_shop_cat_slider_heading', array(
        'label'    => __('Section Heading', 'z-companion'),
        'section'  => 'royal_shop_cat_slide_section',
         'type'       => 'text',
));
/*****************/
// category layout
/*****************/
if(class_exists('Royal_Shop_WP_Customize_Control_Radio_Image')){
        $wp_customize->add_setting(
            'royal_shop_cat_slide_layout', array(
                'default'           => 'cat-layout-1',
                'sanitize_callback' => 'z_companion_sanitize_radio',
            )
        );
$wp_customize->add_control(
            new Royal_Shop_WP_Customize_Control_Radio_Image(
                $wp_customize, 'royal_shop_cat_slide_layout', array(
                    'label'    => esc_html__( 'Category Layout', 'z-companion' ),
                    'section'  => 'royal_shop_cat_slide_section',
                    'choices'  => array(
                        'cat-layout-1'   => array(
                            'url' => ROYAL_SHOP_CAT_SLIDER_LAYOUT_1,
                        ),
                        'cat-layout-2'   => array(
                            'url' => ROYAL_SHOP_CAT_SLIDER_LAYOUT_2,
                        ),
                        'cat-layout-3' => array(
                            'url' => ROYAL_SHOP_CAT_SLIDER_LAYOUT_3,
                        ),
                              
                    ),
                )
            )
        );
} 

if(class_exists('Heading')){
$wp_customize->add_setting('royal_shop_slide_list_dropdown', array(
        'default'           => '',
        'sanitize_callback' => 'z_companion_sanitize_text',  
                )
            );
$wp_customize->add_control(
            new Heading(
                $wp_customize, 'royal_shop_slide_list_dropdown', array(
                    'label'            => esc_html__( 'Choose Categories', 'z-companion' ),
                    'section'          => 'royal_shop_cat_slide_section',
                    'class'            => 'royal_shop_slide_list_dropdown',
                    'accordion'        => true,
                    'expanded'         => false,
                    'controls_to_wrap' => 1,
                )
            )
        );
}
//= Choose All Category  =   
    if (class_exists( 'royal_shop_Customize_Control_Checkbox_Multiple')) {
   $wp_customize->add_setting('royal_shop_category_slide_list', array(
        'default'           => '',
        'sanitize_callback' => 'z_companion_checkbox_explode'
    ));
    $wp_customize->add_control(new royal_shop_Customize_Control_Checkbox_Multiple(
            $wp_customize,'royal_shop_category_slide_list', array(
        'settings'=> 'royal_shop_category_slide_list',
        'label'   => __( '', 'z-companion' ),
        'section' => 'royal_shop_cat_slide_section',
        'choices' => z_companion_get_category_list(array('taxonomy' =>'product_cat'),false),
        ) 
    ));

}  

$wp_customize->add_setting('royal_shop_cat_item_no', array(
            'default'           => 9,
            'capability'        => 'edit_theme_options',
            'sanitize_callback' =>'z_companion_sanitize_number',
        )
    );
    $wp_customize->add_control('royal_shop_cat_item_no', array(
            'type'        => 'number',
            'section'     => 'royal_shop_cat_slide_section',
            'label'       => __( 'No. of Column to show', 'z-companion' ),
            'input_attrs' => array(
                'min'  => 0,
                'step' => 1,
                'max'  => 10,
            ),
        )
    ); 

// Add an option to disable the logo.
  $wp_customize->add_setting( 'royal_shop_category_slider_optn', array(
    'default'           => false,
    'sanitize_callback' => 'z_companion_sanitize_checkbox',
  ) );
  $wp_customize->add_control( new royal_shop_Toggle_Control( $wp_customize, 'royal_shop_category_slider_optn', array(
    'label'       => esc_html__( 'Slide Auto Play', 'z-companion' ),
    'section'     => 'royal_shop_cat_slide_section',
    'type'        => 'toggle',
    'settings'    => 'royal_shop_category_slider_optn',
  ) ) );
$wp_customize->add_setting('royal_shop_category_slider_speed', array(
        'default' =>'3000',
        'capability'        => 'edit_theme_options',
        'sanitize_callback' => 'z_companion_sanitize_number',
));
$wp_customize->add_control( 'royal_shop_category_slider_speed', array(
        'label'    => __('Speed', 'z-companion'),
        'description' =>__('Interval (in milliseconds) to go for next slide since the previous stopped if the slider is auto playing, default value is 3000','z-companion'),
        'section'  => 'royal_shop_cat_slide_section',
         'type'        => 'number',
));
  $wp_customize->add_setting('royal_shop_category_slider_doc', array(
    'sanitize_callback' => 'z_companion_sanitize_text',
    ));
$wp_customize->add_control(new royal_shop_Misc_Control( $wp_customize, 'royal_shop_category_slider_doc',
            array(
        'section'    => 'royal_shop_cat_slide_section',
        'type'      => 'doc-link',
        'url'       => 'https://wpzita.com/docs/product-categories-section/',
        'description' => esc_html__( 'Feel difficulty, Explore our', 'z-companion' ),
        'priority'   =>100,
    )));