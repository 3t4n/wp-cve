<?php
/*
Plugin Name: C4D Woocommerce Wishlist
Plugin URI: http://coffee4dev.com/
Description: Add quickview button for product.
Author: Coffee4dev.com
Author URI: http://coffee4dev.com/
Text Domain: c4d-woo-wishlist
Version: 2.0.3
*/

define( 'C4DWWISHLIST_PLUGIN_URI', plugins_url('', __FILE__));
define( 'C4DWISHLIST_USER_META', 'c4d-woo-wishlist');
add_action( 'wp_enqueue_scripts', 'c4d_woo_wishlist_safely_add_stylesheet_to_frontsite');
add_action( 'wp_ajax_c4d_woo_wishlist_cart', 'c4d_woo_wishlist_cart');
add_action( 'wp_ajax_nopriv_c4d_woo_wishlist_cart', 'c4d_woo_wishlist_cart');
add_action( 'wp_ajax_c4d_woo_delete_user_meta', 'c4d_woo_delete_user_meta');
add_action( 'wp_ajax_nopriv_c4d_woo_delete_user_meta', 'c4d_woo_delete_user_meta');
add_action( 'c4d-plugin-manager-section', 'c4d_woo_wishlist_section_options');
add_shortcode( 'c4d-woo-wishlist-cart', 'c4d_woo_wishlist_shortcode_cart');
add_shortcode( 'c4d-woo-wishlist-button', 'c4d_woo_wishlist_shortcode_button');
add_filter( 'plugin_row_meta', 'c4d_woo_wishlist_plugin_row_meta', 10, 2 );

function c4d_woo_wishlist_plugin_row_meta( $links, $file ) {
    if ( strpos( $file, basename(__FILE__) ) !== false ) {
        $new_links = array(
            'visit' => '<a href="http://coffee4dev.com">Visit Plugin Site</<a>',
            'premium' => '<a href="http://coffee4dev.com">Premium Support</<a>'
        );
        $links = array_merge( $links, $new_links );
    }
    return $links;
}

function c4d_woo_wishlist_safely_add_stylesheet_to_frontsite( $page ) {
	
	wp_enqueue_style( 'c4d-woo-wishlist-frontsite-style', C4DWWISHLIST_PLUGIN_URI.'/assets/default.css' );
	wp_enqueue_script( 'c4d-woo-wishlist-frontsite-plugin-js', C4DWWISHLIST_PLUGIN_URI.'/assets/default.js', array( 'jquery' ), false, true ); 
	wp_enqueue_script( 'jquery-cookie', 'https://cdnjs.cloudflare.com/ajax/libs/jquery-cookie/1.4.1/jquery.cookie.min.js', array( 'jquery' ), false, true ); 
	wp_localize_script( 'jquery', 'c4d_woo_wishlist',
            array( 'ajax_url' => admin_url( 'admin-ajax.php' ) ) );
}

function c4d_woo_wishlist_shortcode_cart($params, $content) {
	global $woocommerce, $c4d_plugin_manager;
	$current = c4d_woo_wishlist_current();
	$icon = isset($c4d_plugin_manager['c4d-woo-wishlist-cart-icon']) ? $c4d_plugin_manager['c4d-woo-wishlist-cart-icon'] : 'fa fa-heart-o';
	$params['icon'] = isset($params['icon']) ? $params['icon'] : $icon;
	$params['label'] = isset($params['label']) ? $params['label'] : '';
	
	$html = '<div class="c4d-woo-wishlist-cart">';
	$html .= '<div class="c4d-woo-wishlist-cart__icon"><i class="'.esc_attr($params['icon']).'"></i><span class="number">'.count($current).'</span><span class="text">'.$params['label'].'</span></div>';
	$html .= '<div class="c4d-woo-wishlist-cart__list">';
	$html .= '</div>';
	$html .= '</div>';
	return $html;
}
function c4d_woo_wishlist_shortcode_button($params, $content) {
	global $product, $c4d_plugin_manager;
	$current = c4d_woo_wishlist_current();
	$pid = get_the_ID();
	$added = in_array($pid, $current) ? 'added' : '';
	$icon = isset($c4d_plugin_manager['c4d-woo-wishlist-button-icon']) ? $c4d_plugin_manager['c4d-woo-wishlist-button-icon'] : 'fa fa-heart-o';
	$params['icon'] = isset($params['icon']) ? $params['icon'] : $icon;
	$params['label'] = isset($params['label']) ? '<span class="label">'.$params['label'].'</label>' : '';
	return '<a class="c4d-woo-wishlist-button '.($added).'" 
				data-id="'.esc_attr($pid).'"
				href="#"><span class="icon"><i class="'.esc_attr($params['icon']).'"></i></span>'.$params['label'].'</a>';
}
function c4d_woo_wishlist_current() {
	$current = array();
	if (isset($_COOKIE['c4d-woo-wishlist-cookie']) && $_COOKIE['c4d-woo-wishlist-cookie'] != '') {
		$current = $_COOKIE['c4d-woo-wishlist-cookie'];
		$current = explode(',', $current);
	}
	$userId = get_current_user_id();
	if ($userId) {
		$uCurrent = get_user_meta($userId, C4DWISHLIST_USER_META, true);
		$uCurrent = $uCurrent ? $uCurrent : array();
		if (count($current) > 0) {
			$uNew = array_unique(array_merge($uCurrent, $current));
			update_user_meta($userId, C4DWISHLIST_USER_META, $uNew, $uCurrent );
		}
		$uData = get_user_meta($userId, C4DWISHLIST_USER_META, true);
		$current = is_array($uData) ? $uData : $current;
	}

	return $current;
}
function c4d_woo_wishlist_cart(){
	$current = c4d_woo_wishlist_current();
	if (is_array($current)) {
		require dirname(__FILE__). '/templates/default.php';
	}
	wp_die();
}

function c4d_woo_delete_user_meta() {
	$userId = get_current_user_id();
	if ($userId) {
		$pid = intval($_GET['pid']);	
		$uCurrent = get_user_meta($userId, C4DWISHLIST_USER_META, true);
		if (count($uCurrent) > 0) {
			if(($key = array_search($pid, $uCurrent)) !== false) {
			    unset($uCurrent[$key]);
			}
			update_user_meta($userId, C4DWISHLIST_USER_META, $uCurrent);
		}
	}
	wp_die();
}

function c4d_woo_wishlist_section_options(){
    $opt_name = 'c4d_plugin_manager';
    Redux::setSection( $opt_name, array(
        'title'            => esc_html__( 'Wishlist', 'c4d-woo-wishlist' ),
        'id'               => 'c4d-woo-wishlist',
        'desc'             => '',
        'customizer_width' => '400px',
        'icon'             => 'el el-home',
        // 'subsection'       => true,
        'fields'           => array(
            array(
                'id'       => 'c4d-woo-wishlist-cart-icon',
                'type'     => 'text',
                'title'    => esc_html__('Default Cart Icon', 'c4d-woo-wishlist'),
                'subtitle' => esc_html__('Set default icon. Support icon font only, insert the class of icon', 'c4d-woo-wishlist'),
                'default'  => 'fa fa-heart'
            ),
            array(
                'id'       => 'c4d-woo-wishlist-button-icon',
                'type'     => 'text',
                'title'    => esc_html__('Default Button Icon', 'c4d-woo-wishlist'),
                'subtitle' => esc_html__('Set default button icon. Support icon font only, insert the class of icon', 'c4d-woo-wishlist'),
                'default'  => 'fa fa-heart'
            ),
            array(
                'id'       => 'c4d-woo-wishlist-remove-icon',
                'type'     => 'text',
                'title'    => esc_html__('Remove Icon', 'c4d-woo-wishlist'),
                'default'  => 'fa fa-trash-o'
            )
        )
    ));
}