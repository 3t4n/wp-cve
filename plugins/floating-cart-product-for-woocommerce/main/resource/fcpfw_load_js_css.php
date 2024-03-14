<?php
//Add JS and CSS on Backend
add_action( 'admin_enqueue_scripts','FCPFW_loadAdminScriptStyle');
function FCPFW_loadAdminScriptStyle() {
  	wp_enqueue_script( 'FCPFW-admin-script', FCPFW_PLUGIN_DIR . '/assets/js/fcpfw_admin_script.js', array( 'jquery', 'select2') );
  	wp_enqueue_style( 'FCPFW-admin-style', FCPFW_PLUGIN_DIR . '/assets/css/fcpfw_admin_style.css', false, '1.0.0' );
  	wp_localize_script( 'ajaxloadpost', 'ajax_postajax', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );
  	wp_enqueue_style( 'woocommerce_admin_styles-css', WP_PLUGIN_URL. '/woocommerce/assets/css/admin.css',false,'1.0',"all");
  	wp_enqueue_style( 'wp-color-picker' );
    wp_enqueue_script( 'wp-color-picker-alpha', FCPFW_PLUGIN_DIR . '/assets/js/wp-color-picker-alpha.js', array( 'wp-color-picker' ), '1.0.0', true );
}
add_action( 'wp_enqueue_scripts','FCPFW_loadScriptStyle');
function FCPFW_loadScriptStyle() {
	global $fcpfw_comman;
	wp_enqueue_script('jquery', false, array(), false, false);
	if (class_exists('WooCommerce')) {
        wp_enqueue_script('wc-cart-fragments', WC()->plugin_url() . '/assets/js/frontend/cart-fragments.min.js', array('jquery'), WC_VERSION, true);
    }
	wp_enqueue_script( 'owlcarousel', FCPFW_PLUGIN_DIR . '/assets/js/owl.carousel.js', false, '1.0.0' );
	wp_enqueue_style( 'owlcarousel-min', FCPFW_PLUGIN_DIR . '/assets/css/owl.carousel.min.css', false, '1.0.0' );
  	wp_enqueue_style( 'owlcarousel-theme', FCPFW_PLUGIN_DIR . '/assets/css/owl.theme.default.min.css', false, '1.0.0' );
  	wp_enqueue_style( 'FCPFW-front_css', FCPFW_PLUGIN_DIR . '/assets/css/fcpfw_front_style.css', false, '1.0.0' );
  	wp_enqueue_script( 'FCPFW-front_js', FCPFW_PLUGIN_DIR . '/assets/js/fcpfw_front_script.js', false, '1.0.0' );
  	wp_localize_script( 'FCPFW-front_js', 'ajax_postajax', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );
    wp_enqueue_script( 'jquery-effects-core' );
	wp_localize_script('FCPFW-front_js', 'fcpfw_urls', array(
	    'pluginsUrl' => FCPFW_PLUGIN_DIR,
	));
	wp_localize_script('FCPFW-front_js', 'fcpfw_sidebar_width', array(
	    'fcpfw_width' => $fcpfw_comman[ 'fcpfw_sidecart_width'].'px',
	    'fcpfw_auto_open' => $fcpfw_comman['fcpfw_auto_open'],
	    'fcpfw_cart_open_from'=>$fcpfw_comman['fcpfw_cart_open_from'],
	    'fcpfw_trigger_class' => $fcpfw_comman['fcpfw_trigger_class'],
	));
}