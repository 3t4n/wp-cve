<?php
if ( ! defined( 'ABSPATH' ) ) { exit; } 
global $foxtool_options;
# ajax cho nut trong trang sản phẩm
function foxtool_enqueue_woojs(){
	global $foxtool_options;
	if (isset($foxtool_options['woo-aja1']) || isset($foxtool_options['woo-aja2'])){
	wp_enqueue_script('woo-js', FOXTOOL_URL . 'link/custom-woo.js', array(), FOXTOOL_VERSION,true);
	}
}
add_action('wp_enqueue_scripts', 'foxtool_enqueue_woojs');
if ( isset($foxtool_options['woo-aja1'])){
function foxtool_ajax_add_to_cart_handler() {
	WC_Form_Handler::add_to_cart_action();
	WC_AJAX::get_refreshed_fragments();
}
add_action( 'wc_ajax_ace_add_to_cart', 'foxtool_ajax_add_to_cart_handler' );
add_action( 'wc_ajax_nopriv_ace_add_to_cart', 'foxtool_ajax_add_to_cart_handler' );
remove_action( 'wp_loaded', array( 'WC_Form_Handler', 'add_to_cart_action' ), 20 );
function foxtool_ajax_add_to_cart_add_fragments( $fragments ) {
	$all_notices  = WC()->session->get( 'wc_notices', array() );
	$notice_types = apply_filters( 'woocommerce_notice_types', array( 'error', 'success', 'notice' ) );
	ob_start();
	foreach ( $notice_types as $notice_type ) {
		if ( wc_notice_count( $notice_type ) > 0 ) {
			wc_get_template( "notices/{$notice_type}.php", array(
				'notices' => array_filter( $all_notices[ $notice_type ] ),
			) );
		}
	}
	$fragments['notices_html'] = ob_get_clean();
	wc_clear_notices();
	return $fragments;
}
add_filter( 'woocommerce_add_to_cart_fragments', 'foxtool_ajax_add_to_cart_add_fragments' );
}
# thêm nút + - ajax vào woocommerce
if (isset($foxtool_options['woo-aja2'])){
function foxtool_quantify_ajax_minus() {
    echo '<button type="button" class="qty_button minus">-</button>';
}
add_action('woocommerce_before_quantity_input_field', 'foxtool_quantify_ajax_minus');
function foxtool_quantify_ajax_plus() { ?>
	<button type="button" class="qty_button plus">+</button>
	<style>
	.quantity {
	  border: 1px solid #ddd;
	  display: inline-flex;
	  border-radius: 7px;
	}
	.quantity button {
	  outline:none;
	  -webkit-appearance: none;
	  background-color: transparent;
	  border: none;
	  align-items: center;
	  justify-content: center;
	  width: 25px;
	  height: 35px;
	  cursor: pointer;
	  margin: 0;
	  position: relative;
	  color:#444;
	  padding:0px;
	}
	.quantity input[type=number] {
	  font-family: sans-serif;
	  max-width: 50px;
	  border: solid #ddd;
	  border-width: 0 1px;
	  font-size: 15px;
	  height: 35px;
	  font-weight: bold;
	  text-align: center;
	}
	.quantity input::-webkit-outer-spin-button,
	.quantity input::-webkit-inner-spin-button {
		display: none;
		margin: 0;
	}
	</style>
	<?php
}
add_action('woocommerce_after_quantity_input_field', 'foxtool_quantify_ajax_plus');
}
# thay đoi loading ajax
function foxtool_enqueue_woo_css_js(){
    global $foxtool_options;
    if (isset($foxtool_options['woo-load1']) && $foxtool_options['woo-load1'] != 'None') {
	$img = ''; 
        if (isset($foxtool_options['woo-load1']) && $foxtool_options['woo-load1'] == 'Loading 1') {
            $img = 'background-image: url('. FOXTOOL_URL . 'img/load1.gif' .')';
        } elseif (isset($foxtool_options['woo-load1']) && $foxtool_options['woo-load1'] == 'Loading 2') {
            $img = 'background-image: url('. FOXTOOL_URL . 'img/load2.gif' .')';
        } elseif (isset($foxtool_options['woo-load1']) && $foxtool_options['woo-load1'] == 'Loading 3') {
            $img = 'background-image: url('. FOXTOOL_URL . 'img/load3.gif' .')';
        } elseif (isset($foxtool_options['woo-load1']) && $foxtool_options['woo-load1'] == 'Loading 4') {
            $img = 'background-image: url('. FOXTOOL_URL . 'img/load4.gif' .')';
        } elseif (isset($foxtool_options['woo-load1']) && $foxtool_options['woo-load1'] == 'Loading 5') {
            $img = 'background-image: url('. FOXTOOL_URL . 'img/load5.gif' .')';
        }
    $load = '<style>.woocommerce .blockOverlay{background:none !important;}.woocommerce .blockUI.blockOverlay:before, .woocommerce .loader:before {' . $img . ';}</style>';
    echo $load;
	}
}
add_action('wp_footer', 'foxtool_enqueue_woo_css_js');
# thay doi nut mua hang ở trang xem sản phẩm
if(!empty($foxtool_options['woo-text1'])){
function foxtool_sing_to_cart_text(){
	global $foxtool_options;
	$name = !empty($foxtool_options['woo-text1']) ? $foxtool_options['woo-text1'] : null;
    return $name;	
}
add_filter( 'woocommerce_product_single_add_to_cart_text', 'foxtool_sing_to_cart_text' ); 
}
# thay doi nut mua hang ở trang xem sản phẩm
if(!empty($foxtool_options['woo-text2'])){
function foxtool_pro_to_cart_text(){
	global $foxtool_options;
	$name = !empty($foxtool_options['woo-text2']) ? $foxtool_options['woo-text2'] : null;
    return $name;
}
add_filter( 'woocommerce_product_add_to_cart_text', 'foxtool_pro_to_cart_text' ); 
}

# liên hệ thay cho 0đ
if(!empty($foxtool_options['woo-text3'])){
function foxtool_product_zero( $price, $product ) {
	global $foxtool_options;
    if ( $product->get_price() == 0 ) {
        if ( $product->is_on_sale() && $product->get_regular_price() ) {
            $regular_price = wc_get_price_to_display( $product, array( 'qty' => 1, 'price' => $product->get_regular_price() ) );
            $price = wc_format_price_range( $regular_price, __( 'Free!', 'woocommerce' ) );
        } else {
			$name = !empty($foxtool_options['woo-text3']) ? $foxtool_options['woo-text3'] : null;
            $price = '<span class="woozero">' . $name . '</span>';
        }
    }
    return $price;
}
add_filter( 'woocommerce_get_price_html', 'foxtool_product_zero', 10, 2 );
}
# liên hệ thay cho hết hàng
if(!empty($foxtool_options['woo-text4'])){
function foxtool_product_end( $price, $product ) {
	global $foxtool_options;
    if ( !is_admin() && !$product->is_in_stock()) {
	   $name = !empty($foxtool_options['woo-text4']) ? $foxtool_options['woo-text4'] : null;
       $price = '<span class="woozero">' . $name . '</span>';
    }
    return $price;
}
add_filter( 'woocommerce_get_price_html', 'foxtool_product_end', 99, 2 );
}
# thông báo telegram khi có đơn hàng
if (isset($foxtool_options['woo-tele1'])){
function foxtool_woo_telegram($order_id) {
    global $foxtool_options;
    if (!$order_id) return;
    $site = get_site_url();
    $order = wc_get_order($order_id);
    $order_data = $order->get_data();
    $first_name = $order_data['billing']['first_name'];
    $last_name = $order_data['billing']['last_name'];
    $phone = $order_data['billing']['phone'];
    $current_time = date('d/m/Y H:i:s');
    $msg = __('From', 'foxtool') . ": $site\n" .
           __('Code orders', 'foxtool') . ": $order_id\n" .
           __('Buyer', 'foxtool') . ": $last_name $first_name\n" .
           __('Contact', 'foxtool') . ": $phone\n" .
           __('Time', 'foxtool') . ": $current_time";

    $token = !empty($foxtool_options['woo-tele11']) ? $foxtool_options['woo-tele11'] : null;
    $chatID = !empty($foxtool_options['woo-tele12']) ? $foxtool_options['woo-tele12'] : null;
    $url = "https://api.telegram.org/bot" . $token . "/sendMessage?parse_mode=html&chat_id=" . $chatID;
    $url = $url . "&text=" . urlencode($msg);
    file_get_contents($url);
}
add_action('woocommerce_checkout_order_processed', 'foxtool_woo_telegram');
}
# chuyen đ sang VNĐ
if (isset($foxtool_options['woo-ntext1'])){
function foxtool_vnd_currency_symbol( $currency_symbol, $currency ) {
	 switch( $currency ){
	 case 'VND': $currency_symbol = 'VNĐ'; break;
	 }
	 return $currency_symbol;
}
add_filter('woocommerce_currency_symbol', 'foxtool_vnd_currency_symbol', 10, 2);
}









