<?php 
if ( ! defined( 'ABSPATH' ) ) exit; 
/***************************/
//category product section product ajax filter
/***************************/
if(!function_exists('z_companion_cat_filter_ajax')){
add_action('wp_ajax_z_companion_cat_filter_ajax', 'z_companion_cat_filter_ajax');
add_action('wp_ajax_nopriv_z_companion_cat_filter_ajax', 'z_companion_cat_filter_ajax');
function z_companion_cat_filter_ajax(){
if(isset($_POST['data_cat_slug'])){  
$prdct_optn = get_theme_mod('royal_shop_category_optn','recent');
$data_cat_slug = sanitize_key($_POST['data_cat_slug']);
$args = z_companion_product_query($data_cat_slug,$prdct_optn);
z_companion_product_filter_loop($args);
}
    exit;
  }
}