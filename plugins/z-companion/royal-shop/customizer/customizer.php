<?php 
if ( ! defined( 'ABSPATH' ) ) exit; 
/**
 * all customizer setting includeed
 *
 * @param  
 * @return mixed|string
 */
function z_companion_royal_shop_front_customize_register( $wp_customize ){
//Front Page
require Z_COMPANION_DIR_PATH . '/royal-shop/customizer/frontpage/top-slider.php';
require Z_COMPANION_DIR_PATH . '/royal-shop/customizer/frontpage/category-tab.php';
require Z_COMPANION_DIR_PATH . '/royal-shop/customizer/frontpage/product-slide.php';
require Z_COMPANION_DIR_PATH . '/royal-shop/customizer/frontpage/category-slider.php';
require Z_COMPANION_DIR_PATH . '/royal-shop/customizer/frontpage/product-list.php';
require Z_COMPANION_DIR_PATH . '/royal-shop/customizer/frontpage/banner.php';
require Z_COMPANION_DIR_PATH . '/royal-shop/customizer/frontpage/brand.php';
require Z_COMPANION_DIR_PATH . '/royal-shop/customizer/frontpage/higlight.php';
// product shown in front Page
 $wp_customize->add_setting('z_companion_prd_shw_no', array(
            'default'           =>'20',
            'capability'        => 'edit_theme_options',
            'sanitize_callback' =>'royal_shop_sanitize_number',
        )
    );
    $wp_customize->add_control('z_companion_prd_shw_no', array(
            'type'        => 'number',
            'section'     => 'royal-shop-woo-shop',
            'label'       => __( 'No. of product to show in Front Page', 'royal-shop' ),
            'input_attrs' => array(
                'min'  => 10,
                'step' => 1,
                'max'  => 1000,
            ),
        )
    ); 
}
add_action('customize_register','z_companion_royal_shop_front_customize_register');