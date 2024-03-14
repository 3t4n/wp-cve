<?php
function z_companion_register_scripts() {
wp_register_style('owl.carousel-css', Z_COMPANION_PLUGIN_DIR_URL.'assets/css/owl.carousel.min.css', array(), '1.0.0' );

wp_register_script('owl.carousel-js', Z_COMPANION_PLUGIN_DIR_URL.'assets/js/owl.carousel.min.js', array( 'jquery' ), '',false);
wp_register_script('jssor.slider-js', Z_COMPANION_PLUGIN_DIR_URL.'assets/js/jssor.slider.min.js', array( 'jquery' ), '',false);
wp_register_script('z_companion_widget_js', Z_COMPANION_PLUGIN_DIR_URL .'assets/js/widget.js', array("jquery"), '', true);
}
add_action('init', 'z_companion_register_scripts');

function z_companion_royal_shop_scripts(){
wp_enqueue_style('owl.carousel-css');

wp_enqueue_script('owl.carousel-js');
wp_enqueue_script( 'jssor.slider-js');
wp_enqueue_script( 'z_companion_royal-shop-custom-js', Z_COMPANION_PLUGIN_DIR_URL.'royal-shop/assets/js/custom.js', array( 'jquery' ), '',true );
$royalshoplocalize = z_companion_localize_settings();
wp_localize_script( 'z_companion_royal-shop-custom-js', 'royalshop_obj',  $royalshoplocalize);
}