<?php
if ( ! defined( 'ABSPATH' ) ) exit; 
/**
 * Category Section Customizer Settings
 */
function z_companion_get_category_list($arr='',$all=true){
    $cats = array();
    foreach ( get_categories($arr) as $categories => $category ){
        $cats[$category->slug] = $category->name;
     }
     return $cats;
  }

$wp_customize->add_setting( 'royal_shop_disable_cat_sec', array(
                'default'               => false,
                'sanitize_callback'     => 'z_companion_sanitize_checkbox',
            ) );
$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'royal_shop_disable_cat_sec', array(
                'label'                 => esc_html__('Section On/Off', 'z-companion'),
                'type'                  => 'checkbox',
                'section'               => 'royal_shop_category_tab_section',
                'settings'              => 'royal_shop_disable_cat_sec',
            ) ) );
// section heading
$wp_customize->add_setting('royal_shop_cat_tab_heading', array(
        'default' => __('Filter Product Slider','z-companion'),
        'capability'        => 'edit_theme_options',
        'sanitize_callback' => 'z_companion_sanitize_text',
        'transport'         => 'postMessage',
));
$wp_customize->add_control( 'royal_shop_cat_tab_heading', array(
        'label'    => __('Section Heading', 'z-companion'),
        'section'  => 'royal_shop_category_tab_section',
         'type'       => 'text',
));

if(class_exists('Heading')){
$wp_customize->add_setting('royal_shop_fpcategory_dropdown', array(
        'default'           => '',
        'sanitize_callback' => 'z_companion_sanitize_text',  
                )
            );
$wp_customize->add_control(
            new Heading(
                $wp_customize, 'royal_shop_fpcategory_dropdown', array(
                    'label'            => esc_html__( 'Choose Categories', 'z-companion' ),
                    'section'          => 'royal_shop_category_tab_section',
                    'class'            => 'royal_shop_fpcategory_dropdown',
                    'accordion'        => true,
                    'expanded'         => false,
                    'controls_to_wrap' => 1,
                )
            )
        );
}
//= Choose All Category  =   
    if (class_exists( 'royal_shop_Customize_Control_Checkbox_Multiple')) {
   $wp_customize->add_setting('z_companion_category_tab_list', array(
        'default'           => '',
        'sanitize_callback' => 'z_companion_checkbox_explode'
    ));
    $wp_customize->add_control(new royal_shop_Customize_Control_Checkbox_Multiple(
            $wp_customize,'z_companion_category_tab_list', array(
        'settings'=> 'z_companion_category_tab_list',
        'label'   => __( '', 'z-companion' ),
        'section' => 'royal_shop_category_tab_section',
        'choices' => z_companion_get_category_list(array('taxonomy' =>'product_cat'),false),
        ) 
    ));
}  

$wp_customize->add_setting('royal_shop_category_optn', array(
        'default'        => 'recent',
        'capability'     => 'edit_theme_options',
        'sanitize_callback' => 'z_companion_sanitize_select',
    ));
$wp_customize->add_control( 'royal_shop_category_optn', array(
        'settings' => 'royal_shop_category_optn',
        'label'    => __('Choose Option','z-companion'),
        'section'  => 'royal_shop_category_tab_section',
        'type'     => 'select',
        'choices'    => array(
        'recent'     => __('Recent','z-companion'),
        'featured'   => __('Featured','z-companion'),
        'random'     => __('Random','z-companion'),
            
        ),
    ));

$wp_customize->add_setting( 'royal_shop_single_row_slide_cat', array(
                'default'               => false,
                'sanitize_callback'     => 'z_companion_sanitize_checkbox',
            ) );
$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'royal_shop_single_row_slide_cat', array(
                'label'                 => esc_html__('Enable Single Row Slide', 'z-companion'),
                'type'                  => 'checkbox',
                'section'               => 'royal_shop_category_tab_section',
                'settings'              => 'royal_shop_single_row_slide_cat',
            ) ) );
// Add an option to disable the logo.
  $wp_customize->add_setting( 'royal_shop_cat_slider_optn', array(
    'default'           => false,
    'sanitize_callback' => 'z_companion_sanitize_checkbox',
  ) );
  $wp_customize->add_control( new royal_shop_Toggle_Control( $wp_customize, 'royal_shop_cat_slider_optn', array(
    'label'       => esc_html__( 'Slide Auto Play', 'z-companion' ),
    'section'     => 'royal_shop_category_tab_section',
    'type'        => 'toggle',
    'settings'    => 'royal_shop_cat_slider_optn',
  ) ) );
$wp_customize->add_setting('royal_shop_cat_slider_speed', array(
        'default' =>'3000',
        'capability'        => 'edit_theme_options',
        'sanitize_callback' => 'z_companion_sanitize_number',
));
$wp_customize->add_control( 'royal_shop_cat_slider_speed', array(
        'label'    => __('Speed', 'z-companion'),
        'description' =>__('Interval (in milliseconds) to go for next slide since the previous stopped if the slider is auto playing, default value is 3000','z-companion'),
        'section'  => 'royal_shop_category_tab_section',
         'type'        => 'number',
));
$wp_customize->add_setting('royal_shop_cat_tab_slider_doc', array(
    'sanitize_callback' => 'z_companion_sanitize_text',
    ));
$wp_customize->add_control(new royal_shop_Misc_Control( $wp_customize, 'royal_shop_cat_tab_slider_doc',
            array(
        'section'    => 'royal_shop_category_tab_section',
        'type'      => 'doc-link',
        'url'       => 'https://wpzita.com/docs/filter-product-slider/',
        'description' => esc_html__( 'Feel difficulty, Explore our', 'z-companion' ),
    )));