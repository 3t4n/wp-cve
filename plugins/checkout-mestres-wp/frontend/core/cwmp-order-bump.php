<?php
function cwmp_order_bump_fee( $cart ) {
	global $wpdb;
	global $table_prefix;
    foreach ( WC()->cart->get_cart() as $cart_item ) {
		$get_product = $wpdb->get_results(
			$wpdb->prepare(
				"SELECT * FROM {$wpdb->prefix}cwmp_order_bump WHERE produto LIKE %s",
				$cart_item['product_id']
			)
		);
		foreach($get_product as $pedido){
		foreach ( WC()->cart->get_cart() as $cart_item2 ) {
			if($cart_item2['product_id']==$pedido->bump){
				$product_new_price = wc_get_product($pedido->bump);
				$product_price = $product_new_price->get_price();
				$discount = ( $product_price * $pedido->valor ) / 100;
				$cart->add_fee( 'Desconto', -$discount, false, '' );
			}
		}
		}
    }
}
add_action( 'woocommerce_cart_calculate_fees', 'cwmp_order_bump_fee', 10, 1 );
function cwmp_order_bump_add() {
	global $wpdb;
    $table_name = $wpdb->prefix . 'cwmp_order_bump';
    $add_bump = $wpdb->insert($table_name, array(
		'chamada' => $_POST['chamada'],
		'produto' => $_POST['produto'],
		'bump' => $_POST['bump'],
		'valor' => $_POST['valor']
    ));
	echo $add_bump;
	die();
}
add_action( 'wp_ajax_cwmp_order_bump_add', 'cwmp_order_bump_add' );
add_action( 'wp_ajax_nopriv_cwmp_order_bump_add', 'cwmp_order_bump_add' );
function cwmp_order_bump_edit() {
	global $wpdb;
    $table_name = $wpdb->prefix . 'cwmp_order_bump';
    $add_bump = $wpdb->update($table_name, array(
		'chamada' => $_POST['chamada'],
		'produto' => $_POST['produto'],
		'bump' => $_POST['bump'],
		'valor' => $_POST['valor']
    ),array('id'=>$_POST['id']));
	die();
}
add_action( 'wp_ajax_cwmp_order_bump_edit', 'cwmp_order_bump_edit' );
add_action( 'wp_ajax_nopriv_cwmp_order_bump_edit', 'cwmp_order_bump_edit' );
function cwmp_order_bump_delete() {
	global $wpdb;
    $table_name = $wpdb->prefix . 'cwmp_order_bump';
    $add_bump = $wpdb->delete($table_name, array(
		'id' => $_POST['id']
    ));
	die();
}
add_action( 'wp_ajax_cwmp_order_bump_delete', 'cwmp_order_bump_delete' );
add_action( 'wp_ajax_nopriv_cwmp_order_bump_delete', 'cwmp_order_bump_delete' );
function cwmp_add_order_bump() {
	global $wpdb;
	global $woocommerce;
	$woocommerce->cart->add_to_cart( $_POST['product'], '1' );
	die();
}
add_action( 'wp_ajax_cwmp_add_order_bump', 'cwmp_add_order_bump' );
add_action( 'wp_ajax_nopriv_cwmp_add_order_bump', 'cwmp_add_order_bump' );