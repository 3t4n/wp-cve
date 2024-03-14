<?php
/*
Plugin Name: Woo Donations
Description: Woo Donation is a plugin that is used to collect donations on your websites based on Woocommerce. You can add donation functionality in your site to ask your visitors/users community for financial support for the charity or non-profit programs, products, and organisation.
Author: Geek Code Lab
Version: 4.2
Author URI: https://geekcodelab.com/
WC tested up to: 8.6.1
Text Domain : woo-donations
*/

if (!defined('ABSPATH')) exit;

if (!defined("WDGK_PLUGIN_DIR_PATH"))

	define("WDGK_PLUGIN_DIR_PATH", plugin_dir_path(__FILE__));

if (!defined("WDGK_PLUGIN_URL"))

	define("WDGK_PLUGIN_URL", plugins_url() . '/' . basename(dirname(__FILE__)));

define("WDGK_BUILD", '4.2');

require_once(WDGK_PLUGIN_DIR_PATH . 'functions.php');

add_action('admin_menu', 'wdgk_admin_menu_donation_setting_page');

add_action('admin_enqueue_scripts', 'wdgk_admin_scripts');

/** Plugin Active Hook Start*/
register_activation_hook(__FILE__, 'wdgk_plugin_active_woocommerce_donation');

function wdgk_plugin_active_woocommerce_donation(){
	if (is_plugin_active('woo-donations-pro/woo-donations-pro.php')) {
		deactivate_plugins('woo-donations-pro/woo-donations-pro.php');
	}
	$btntext 			= "Add Donation";
	$textcolor 			= "#FFFFFF";
	$btncolor 			= "#289dcc";
	$form_title			= "Donation";
	$amount_placeholder	= "Ex.100";
	$note_placeholder	= "Note";
	$options 			= array();
	$setting 			= get_option('wdgk_donation_settings');

	if(isset($setting) && !empty($setting)) 	$options 			= $setting;

	if(!isset($setting['Text']))  			$options['Text'] 			= $btntext;
	if(!isset($setting['TextColor']))  		$options['TextColor'] 		= $textcolor;
	if(!isset($setting['Color']))  			$options['Color'] 			= $btncolor;
	if(!isset($setting['Formtitle'])) 		$options['Formtitle'] 		= $form_title;
	if(!isset($setting['AmtPlaceholder'])) 	$options['AmtPlaceholder'] 	= $amount_placeholder;
	if(!isset($setting['Noteplaceholder']))	$options['Noteplaceholder'] = $note_placeholder;


	if (!isset($setting['Product'])) {
		$id = wp_insert_post(array('post_title' => 'Donation', 'post_name' => 'donation', 'post_type' => 'product', 'post_status' => 'publish'));
		$sku = 'donation-' . $id;
		update_post_meta($id, '_sku', $sku);
		update_post_meta($id, '_tax_status', 'none');
		update_post_meta($id, '_tax_class', 'zero-rate');
		update_post_meta($id, '_visibility', 'hidden');
		update_post_meta($id, '_regular_price', 0);
		update_post_meta($id, '_price', 0);
		update_post_meta($id, '_virtual', 'yes');
		update_post_meta($id, '_sold_individually', 'yes');
		$options['Product'] = $id;
		$taxonomy = 'product_visibility';
		wp_set_object_terms($id, array( 'exclude-from-catalog', 'exclude-from-search' ), $taxonomy);
		wdgk_generate_featured_image(WDGK_PLUGIN_URL . '/assets/images/donation_thumbnail.jpg', $id);
		update_option('wdgk_set_order_flag_status',1);
	}
	if (count($options) > 0) {
		update_option('wdgk_donation_settings', $options);
	}
}

/** Add notice if woocommerce not activated */
if ( ! function_exists( 'wdgk_install_woocommerce_admin_notice' ) ) {
	/**
	 * Trigger an admin notice if WooCommerce is not installed.
	 */
	function wdgk_install_woocommerce_admin_notice() {
		?>
		<div class="error">
			<p>
				<?php
				// translators: %s is the plugin name.
				echo esc_html__( sprintf( '%s is enabled but not effective. It requires WooCommerce in order to work.', 'Woo Donations' ), 'woo-donations' );
				?>
			</p>
		</div>
		<?php
	}
}
add_action( 'plugins_loaded', 'wdgk_after_plugins_loaded' );
function wdgk_after_plugins_loaded() {
    // Check WooCommerce installation
	if ( ! function_exists( 'WC' ) ) {
		add_action( 'admin_notices', 'wdgk_install_woocommerce_admin_notice' );
		return;
	}
}

/** Update donation order unique flag for old users on admin init */
add_action( 'admin_init', 'wdgk_woocommerce_constructor' );
function wdgk_woocommerce_constructor() {
	$wdgk_set_order_flag_status = get_option( 'wdgk_set_order_flag_status' );

	if(!$wdgk_set_order_flag_status) {
		global $wpdb;
		$settings			= get_option('wdgk_donation_settings');
		$donation_product_id 	= $settings['Product'];
		$statuses = 'trash';
		
		if( wdgk_woocommerce_hpos_tables_used() ) {
			$sql = "SELECT *,order_items.order_id as order_id
            FROM {$wpdb->prefix}woocommerce_order_items as order_items
            LEFT JOIN {$wpdb->prefix}woocommerce_order_itemmeta as order_item_meta ON order_items.order_item_id = order_item_meta.order_item_id
            LEFT JOIN {$wpdb->prefix}wc_orders AS orders ON order_items.order_id = orders.id
            WHERE orders.type = 'shop_order'
            AND orders.status != '".$statuses."'
            AND order_items.order_item_type = 'line_item'
            AND order_item_meta.meta_key = '_product_id'
            AND order_item_meta.meta_value = $donation_product_id";
		}else{
			$sql = "SELECT *,order_items.order_id as order_id
            FROM {$wpdb->prefix}woocommerce_order_items as order_items
            LEFT JOIN {$wpdb->prefix}woocommerce_order_itemmeta as order_item_meta ON order_items.order_item_id = order_item_meta.order_item_id
            LEFT JOIN {$wpdb->posts} AS posts ON order_items.order_id = posts.ID
            WHERE posts.post_type = 'shop_order'
            AND posts.post_status != '".$statuses."'
            AND order_items.order_item_type = 'line_item'
            AND order_item_meta.meta_key = '_product_id'
            AND order_item_meta.meta_value = $donation_product_id";
		}

		$donation_order_result = $wpdb->get_results( $sql, 'ARRAY_A' );
		
		if(count($donation_order_result) != 0) {
			foreach($donation_order_result as $key => $item) {
				$order = wc_get_order($item['order_id']);

				$order->update_meta_data( 'wdgk_donation_order_flag', $donation_product_id );
				$order->save();
			}
		}
		
		update_option('wdgk_set_order_flag_status',1);
	}
}

add_action('wp_enqueue_scripts', 'wdgk_include_front_script');
function wdgk_include_front_script(){
	wp_enqueue_style("wdgk_front_style", WDGK_PLUGIN_URL . "/assets/css/wdgk-front-style.css", '',WDGK_BUILD);
	
	wp_enqueue_script('wdgk_donation_script', WDGK_PLUGIN_URL.'/assets/js/wdgk-front-script.js', array('jquery'),WDGK_BUILD);
	$decimal_separator = wc_get_price_decimal_separator();
    $thousand_separator = wc_get_price_thousand_separator();
    $wdgk_options = [ "decimal_sep"=>$decimal_separator, "thousand_sep"=>$thousand_separator ];
	wp_localize_script('wdgk_donation_script', 'wdgk_obj', array('ajaxurl' => admin_url( 'admin-ajax.php' ),'options' => $wdgk_options) );
}
function wdgk_admin_scripts($hook) {
	if ($hook == 'woocommerce_page_wdgk-donation-page') {
		$css = WDGK_PLUGIN_URL . '/assets/css/wdgk-admin-style.css';
		wp_enqueue_style('wdgk-admin-style', $css, '',WDGK_BUILD);
		wp_enqueue_style('wp-color-picker');
		wp_enqueue_script('wp-color-picker');

        wp_enqueue_style("wdgk_front_select2", WDGK_PLUGIN_URL . "/assets/css/select2.min.css", '',WDGK_BUILD);
	
	    wp_enqueue_script('wdgk_donation_select2', WDGK_PLUGIN_URL.'/assets/js/select2.min.js', array('jquery'),WDGK_BUILD);
	    wp_enqueue_script('wdgk-admin-custom-js', WDGK_PLUGIN_URL.'/assets/js/wdgk-admin-script.js', array('jquery'),WDGK_BUILD);
        wp_localize_script( 'wdgk-admin-custom-js', 'wdgkObj', [ 'ajaxurl' => admin_url('admin-ajax.php') ] );

	}
}
function wdgk_admin_menu_donation_setting_page(){
	add_submenu_page('woocommerce', 'Donation', 'Donation', 'manage_woocommerce', 'wdgk-donation-page', 'wdgk_donation_page_setting');
}
function wdgk_donation_page_setting() {
	include(WDGK_PLUGIN_DIR_PATH . 'options.php');
}
function wdgk_plugin_add_settings_link($links){
	$support_link = '<a href="https://geekcodelab.com/contact/"  target="_blank" >' . __('Support','woo-donations') . '</a>';
	array_unshift($links, $support_link);

	$pro_link = '<a href="https://geekcodelab.com/wordpress-plugins/woo-donation-pro/"  target="_blank" style="color:#46b450;font-weight: 600;">' . __('Premium Upgrade','woo-donations') . '</a>';
	array_unshift($links, $pro_link);

	$settings_link = '<a href="admin.php?page=wdgk-donation-page">' . __('Settings','woo-donations') . '</a>';
	array_unshift($links, $settings_link);
	return $links;
}

$plugin = plugin_basename(__FILE__);
add_filter("plugin_action_links_$plugin", 'wdgk_plugin_add_settings_link');

$product = "";
$cart = "";
$checkout = "";

$options = wdgk_get_wc_donation_setting();
if (isset($options['Product'])) {
	$product = $options['Product'];
}
if (isset($options['Cart'])) {
	$cart = $options['Cart'];
}
if (isset($options['Checkout'])) {
	$checkout = $options['Checkout'];
}
if (isset($options['Note'])) {
	$note = $options['Note'];
}
if (!empty($product) && $cart == 'on') {
	add_action('woocommerce_proceed_to_checkout', 'wdgk_donation_form_front_html');
}
if (!empty($product) && $checkout == 'on') {
	add_action('woocommerce_before_checkout_form', 'wdgk_add_donation_on_checkout_page');
}

add_shortcode('wdgk_donation', 'wdgk_donation_form_shortcode_html');


function wdgk_add_donation_on_checkout_page(){
	global $woocommerce;
    $checkout_url = function_exists('wc_get_checkout_url') ? wc_get_checkout_url() : $woocommerce->cart->get_checkout_url();
    wdgk_donation_form_front_html($checkout_url);
}

/**
 * Checkout page donation form html
 */
function wdgk_donation_form_front_html($redurl){

	global $woocommerce;
	$product = $text = $note = $note_html 	= $donation_price = $donation_note = "";
	$form_title			= "Donation";
	$amount_placeholder	= "Ex.100";
	$note_placeholder	= "Note";

	$options = wdgk_get_wc_donation_setting();

	if (isset($options['Product'])) {
		$product = $options['Product'];
	}

	if(wc()->cart){
		$cart_count = is_object($woocommerce->cart) ? $woocommerce->cart->get_cart_contents_count() : '';
		if ($cart_count != 0) {
			$cartitems = $woocommerce->cart->get_cart();
			if (!empty($cartitems) && isset($cartitems)) {
				foreach ($cartitems as $item => $values) {
					$product_id =  $values['product_id'];
					$donation_price = (isset($values['donation_price'])) ? $values['donation_price'] : '' ;
					if ($product_id == $product) {
						$donation_price = isset($_COOKIE['wdgk_product_display_price']) ? $_COOKIE['wdgk_product_display_price'] : $donation_price;
						if(isset($values['donation_note'])) $donation_note = str_replace("<br />","\n",$values['donation_note']);
					}
				}
			}
		}
	}

	if (isset($options['Text'])) {
		$text = $options['Text'];
	}
	if (isset($options['Note'])) {
		$note = $options['Note'];
	}	
	if(isset($options['Formtitle']) ){
		$form_title = $options['Formtitle'];
	}
	if(isset($options['AmtPlaceholder'])){
		$amount_placeholder = $options['AmtPlaceholder'];
	}
	if(isset($options['Noteplaceholder'])){
		$note_placeholder = $options['Noteplaceholder'];
	}
	if (!empty($product) && $note == 'on') {
		$note_html = '<textarea id="w3mission" rows="3" cols="20" placeholder="'.esc_attr(wp_unslash($note_placeholder),'woo-donations').'" name="donation_note" class="donation_note">'.wp_unslash($donation_note).'</textarea>';
	}

	if (!empty($redurl) && isset($redurl)) {
        $cart_url = $redurl;
    } else {
        $cart_url = function_exists('wc_get_cart_url') ? wc_get_cart_url() : $woocommerce->cart->get_cart_url();
    }
	

	if (!empty($product)) {

		$ajax_url		= admin_url('admin-ajax.php');
		$current_cur 	= get_woocommerce_currency();
		$cur_syambols 	= get_woocommerce_currency_symbols();
		if(!empty($donation_price))	{
			$decimal_separator = wc_get_price_decimal_separator();
			$thousand_separator = wc_get_price_thousand_separator();
			$price_decimals = wc_get_price_decimals();
			$donation_price = number_format($donation_price,$price_decimals,$decimal_separator,$thousand_separator);
		}
		
		printf('<div class="wdgk_donation_content"><h3>'.esc_attr__(wp_unslash($form_title),'woo-donations').'</h3><div class="wdgk_display_option"> <span>'.esc_attr($cur_syambols[$current_cur]).'</span><input type="text" name="donation-price" class="wdgk_donation" placeholder="'.esc_attr__(wp_unslash($amount_placeholder),'woo-donations').'" value="'.$donation_price.'" ></div>'.$note_html.'<a href="javascript:void(0)" class="button wdgk_add_donation" data-product-id="'.esc_attr($product).'" data-product-url="'.esc_attr($cart_url).'">'.esc_attr__(wp_unslash($text),'woo-donations').'</a><input type="hidden" name="wdgk_product_id" value="" class="wdgk_product_id"><input type="hidden" name="wdgk_ajax_url" value="'.esc_attr($ajax_url).'" class="wdgk_ajax_url"><img src="'.WDGK_PLUGIN_URL.'/assets/images/ajax-loader.gif" class="wdgk_loader wdgk_loader_img"><div class="wdgk_error_front"></div></div>');
	}
}

/**
 * 1. Donation form html of [wdgk_donation] shortcode 
 * 2. Cart page donation form html
 */
function wdgk_donation_form_shortcode_html(){
	global $woocommerce;

	$product 			= "";
	$text 				= "";
	$note 				= "";
	$note_html 			= "";
	$donation_price 	= "";
	$donation_note 		= "";
	$form_title			= "Donation";
	$amount_placeholder	= "Ex.100";
	$note_placeholder	= "Note";

	$options = wdgk_get_wc_donation_setting();

	if (isset($options['Product'])) {
		$product = $options['Product'];
	}
	if (isset($options['Text'])) {
		$text = $options['Text'];
	}
	if (isset($options['Note'])) {
		$note = $options['Note'];
	}	
	if(isset($options['Formtitle']) ){
		$form_title = $options['Formtitle'];
	}
	if(isset($options['AmtPlaceholder'])){
		$amount_placeholder = $options['AmtPlaceholder'];
	}
	if(isset($options['Noteplaceholder'])){
		$note_placeholder = $options['Noteplaceholder'];
	}

	if(wc()->cart){
		$cart_count = is_object($woocommerce->cart) ? $woocommerce->cart->get_cart_contents_count() : '';
		if ($cart_count != 0) {
			$cartitems = $woocommerce->cart->get_cart();
			if (!empty($cartitems) && isset($cartitems)) {
				foreach ($cartitems as $item => $values) {
					$product_id =  $values['product_id'];
					if ($product_id == $product) {
						$donation_price = isset($_COOKIE['wdgk_product_display_price']) ? $_COOKIE['wdgk_product_display_price'] : $values['donation_price'];
						if(isset($values['donation_note'])) $donation_note = str_replace("<br />","\n",$values['donation_note']);
					}
				}
			}
		}
	}

	if (!empty($product) && $note == 'on') {
		$note_html = '<textarea id="w3mission" rows="3" cols="20" placeholder="'.esc_attr__(wp_unslash($note_placeholder)).'" name="donation_note" class="donation_note">'.wp_unslash($donation_note).'</textarea>';
	}
	
	$cart_url = function_exists('wc_get_cart_url') ? wc_get_cart_url() : $woocommerce->cart->get_cart_url();

	if (!empty($product)) {
		$ajax_url= admin_url('admin-ajax.php');
		$current_cur = get_woocommerce_currency();
		$cur_syambols = get_woocommerce_currency_symbols();

		if(!empty($donation_price))	{
			$decimal_separator = wc_get_price_decimal_separator();
			$thousand_separator = wc_get_price_thousand_separator();
			$price_decimals = wc_get_price_decimals();
			$donation_price = number_format($donation_price,$price_decimals,$decimal_separator,$thousand_separator);
		}

		return '<div class="wdgk_donation_content"><h3>'.esc_attr__(wp_unslash($form_title),'woo-donations').'</h3><div class="wdgk_display_option"> <span>'.esc_attr($cur_syambols[$current_cur]).'</span><input type="text" name="donation-price" class="wdgk_donation" placeholder="'.esc_attr__(wp_unslash($amount_placeholder),'woo-donations').'" value="'.$donation_price.'" ></div>'.$note_html.'<a href="javascript:void(0)" class="button wdgk_add_donation" data-product-id="'.esc_attr($product).'" data-product-url="'.esc_attr($cart_url).'">'.esc_attr__(wp_unslash($text),'woo-donations').'</a><input type="hidden" name="wdgk_product_id" value="" class="wdgk_product_id"><input type="hidden" name="wdgk_ajax_url" value="'.esc_attr($ajax_url).'" class="wdgk_ajax_url"><img src="'.WDGK_PLUGIN_URL.'/assets/images/ajax-loader.gif" class="wdgk_loader wdgk_loader_img"><div class="wdgk_error_front"></div></div>';
	}
	
}

/** print style in wp_head */
add_action('wp_head', 'wdgk_set_button_text_color');
function wdgk_set_button_text_color() {
	$additional_style = wdgk_form_internal_style(); 
	if(isset($additional_style) && !empty($additional_style)) { ?>
        <style>
            <?php _e($additional_style); ?>
        </style>
        <?php
    }
}

add_filter('woocommerce_add_cart_item_data', 'wdgk_add_cart_item_data', 10, 3);
add_action('woocommerce_before_calculate_totals', 'wdgk_before_calculate_totals', 1000, 1);
function wdgk_add_cart_item_data($cart_item_data, $product_id, $variation_id){
	$pid = "";
	$options = wdgk_get_wc_donation_setting();
	if (isset($options['Product'])) {
		$pid = $options['Product'];
	}
	if (isset($_COOKIE['wdgk_product_price'])) {
		if ($product_id == $pid) {
			$donation_note = json_decode(stripslashes($_COOKIE['wdgk_donation_note']));
			$cart_item_data['donation_price'] = $_COOKIE['wdgk_product_price'];
			if(isset($donation_note) && !empty($donation_note))		$cart_item_data['donation_note'] = implode("<br />", $donation_note);
		}
	}
	return $cart_item_data;
}

function wdgk_before_calculate_totals($cart_obj){

	$pid = "";
	$options = wdgk_get_wc_donation_setting();
	if (isset($options['Product'])) {
		$pid = $options['Product'];
	}
	if (is_admin() && !defined('DOING_AJAX')) {
		return;
	}
	// Iterate through each cart item
	foreach ($cart_obj->get_cart() as $key => $value) {
		$id = $value['data'];

		if (isset($value['donation_price']) && $id->get_id() == $pid) {
			$price = $value['donation_price'];
			$value['data']->set_price(($price));
		}
	}
}

// Mini cart: Display custom price 
add_filter( 'woocommerce_cart_item_price', 'wdgk_filter_cart_item_price', 10, 3 );
function wdgk_filter_cart_item_price( $price_html, $cart_item, $cart_item_key ) {

    if( isset( $cart_item['donation_price'] ) ) {        
        return wc_price(  $cart_item['donation_price'] );    
	}
	return $price_html;

}

// Mini cart: Display Custom subtotal price 
add_filter( 'woocommerce_cart_item_subtotal', 'wdgk_show_product_discount_order_summary', 10, 3 );
 
function wdgk_show_product_discount_order_summary( $total, $cart_item, $cart_item_key ) {
     
    //Get product object
	if( isset(  $cart_item['donation_price']  ) ) {

		$total= wc_price($cart_item['donation_price']  * $cart_item['quantity']);
	}
    // Return the html
    return $total;
}

/**
 * Donation form ajax response
 */
add_action('wp_ajax_wdgk_donation_form', 'wdgk_donation_ajax_callback');    // If called from admin panel
add_action('wp_ajax_nopriv_wdgk_donation_form', 'wdgk_donation_ajax_callback');
function wdgk_donation_ajax_callback(){
	$product_id = sanitize_text_field($_POST['product_id']);
	$price = sanitize_text_field($_POST['price']);
	$redirect_url = sanitize_text_field($_POST['redirect_url']);
	wdgk_add_donation_product_to_cart($product_id);
	$response = array();
	$response['url'] = $redirect_url;
	$response = json_encode($response);
	_e($response,'woo-donations');
	wp_die();
}

/**
 * Display custom item data in the cart
 */
function wdgk_plugin_republic_get_item_data($item_data, $cart_item_data){
	if ( isset($cart_item_data['donation_note'])  && isset($cart_item_data['donation_price']) && !empty($cart_item_data['donation_note']) && !empty($cart_item_data['donation_note'])) {
		$item_data[] = array(
			'key' => __('Description', 'woo-donations'),
			'value' => wp_unslash($cart_item_data['donation_note'])
		);
	}
	return $item_data;
}
add_filter('woocommerce_get_item_data', 'wdgk_plugin_republic_get_item_data', 10, 2);

/**
 * Add custom meta to order
 */
function wdgk_plugin_republic_checkout_create_order_line_item($item, $cart_item_key, $values, $order){
	if (isset($values['donation_note'])) {
		$item->add_meta_data(
			__('Description', 'woo-donations'),
			wp_unslash($values['donation_note']),
			true
		);
	}
}
add_action('woocommerce_checkout_create_order_line_item', 'wdgk_plugin_republic_checkout_create_order_line_item', 10, 4);

/**
 * Add custom cart item data to emails
 */
function wdgk_plugin_republic_order_item_name($product_name, $item){
	if (isset($item['donation_note']) && isset($item['donation_price'])) {

		$product_name .= sprintf(
			'<ul><li>%s: %s</li></ul>',
			__('Description', 'woo-donations'),
			wp_unslash($item['donation_note'])
		);
	}
	return $product_name;
}
add_filter('woocommerce_order_item_name', 'wdgk_plugin_republic_order_item_name', 10, 2);

/* Add "Donation" column on admin side order list */
add_filter('manage_edit-shop_order_columns', 'wdgk_woo_admin_order_items_column');
add_filter('woocommerce_shop_order_list_table_columns', 'wdgk_woo_admin_order_items_column');	// hpos admin column
function wdgk_woo_admin_order_items_column($order_columns){
	$order_columns['order_products'] = __("Donation","woo-donations");
	return $order_columns;
}

/* hpos admin orders post type column on order listing screen */
add_action( 'woocommerce_shop_order_list_table_custom_column', function ( $column, $order ) {
	if ( 'order_products' !== $column )		return;

	wdgk_get_order_donation_flag($order);
	
}, 10, 2 );

/* admin orders post type column on order listing screen */
add_action('manage_shop_order_posts_custom_column', 'wdgk_order_items_column_cnt');
function wdgk_order_items_column_cnt($colname){
	global $the_order; // the global order object

	if ($colname == 'order_products') {
		wdgk_get_order_donation_flag($the_order);
	}
}

function wdgk_get_order_donation_flag($order) {
	$product = "";
	$options = wdgk_get_wc_donation_setting();
	if (isset($options['Product'])) {
		$product = $options['Product'];
	}
	$order_flag_meta = $order->get_meta("wdgk_donation_order_flag",$product,true);

	if(isset($order_flag_meta) && !empty($order_flag_meta)) {
		_e('<span class="dashicons dashicons-yes-alt wdgk_right_icon"></span>');
	}
}

add_action('wp_ajax_wdgk_product_select_ajax','wdgk_product_select_ajax_callback');
add_action( 'wp_ajax_nopriv_wdgk_product_select_ajax', 'wdgk_product_select_ajax_callback' );

function wdgk_product_select_ajax_callback() {	
    
    $result = array();
    $search = $_POST['search'];

	$search_product_args = array( 'post_type' => 'product', 'post_status' => 'publish', 'posts_per_page' => -1 );

	if(is_numeric($search)) {
		$search_product_args['p'] = (int) $search;
	}else{
		$search_product_args['s'] = $search;
	}
    $wdgk_get_page = get_posts( $search_product_args );

	foreach ($wdgk_get_page as $wdgk_product) {		
        $result[] = array(
            'id' => $wdgk_product->ID,
            'title' => $wdgk_product->post_title .  " ( #" . $wdgk_product->ID . " )"
        );
	}
    echo json_encode($result);

    wp_die();
}

/**
 * Added HPOS support for woocommerce
 */
add_action( 'before_woocommerce_init', function() {
	if ( class_exists( \Automattic\WooCommerce\Utilities\FeaturesUtil::class ) ) {
		\Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'custom_order_tables', __FILE__, true );
	}
} );

/** woocommerce after succefull order */
add_action( 'woocommerce_thankyou', 'wdgk_thankyou_change_order_status' );
function wdgk_thankyou_change_order_status($order_id) {
	$donation_product   = "";
	$options = wdgk_get_wc_donation_setting();

	if (isset($options['Product'])) 		        $donation_product   = $options['Product'];

	$order              =   wc_get_order( $order_id );
    $items              =   $order ->get_items();
    foreach ( $items as $item ) {
        $item_id = $item['product_id'];
        if($donation_product == $item_id) {
            $order->update_meta_data( 'wdgk_donation_order_flag', $item_id );   // set donation order flag 
			$order->save();
        }
    }
}

// Enqueue the block editor script
function wdgk_block_editor_script() {
	wp_enqueue_style( 'wdgk-block-style', plugins_url('assets/css/wdgk-front-style.css', __FILE__), array('wp-edit-blocks'), WDGK_BUILD );
    wp_enqueue_script( 'wdgk-block-script', plugins_url( 'assets/js/wdgk-block.js', __FILE__ ), array( 'wp-blocks', 'wp-element' ), WDGK_BUILD );
}
add_action( 'enqueue_block_editor_assets', 'wdgk_block_editor_script' );

function wdgk_gutenberg_render_callback( $block_attributes, $content ) {
	$donation_form_html = "";
	$additional_style = wdgk_form_internal_style();

	if($additional_style != "") {
		$donation_form_html .= '<style>'. esc_html($additional_style) .'</style>';
	}

	$donation_form_html .= stripslashes( do_shortcode('[wdgk_donation]') );

    return $donation_form_html;
}

/** Gutenberg block for Woo donation form */
function wdgk_wp_donation_block() {

    register_block_type( 'woo-donations-block/woo-donations', array(
        'api_version' => 3,
        'editor_script' => 'wdgk-block-script',
        'render_callback' => 'wdgk_gutenberg_render_callback'
    ) );

}
add_action( 'init', 'wdgk_wp_donation_block' );