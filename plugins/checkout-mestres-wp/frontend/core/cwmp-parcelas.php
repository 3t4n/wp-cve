<?php
if(get_option('parcelas_mwp_type_tax')=="fixed"){
	include "includes/parcelas-taxa-fixa.php";
	include "includes/view-parcels-fixed.php";
}
if(get_option('parcelas_mwp_type_tax')=="variable"){
	include "includes/parcelas-taxa-variavel.php";
	include "includes/view-parcels-variable.php";
}
function pmwp_hooks() {
		remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_price', 10 );
		remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_price', 10 );
		add_action( 'woocommerce_after_shop_loop_item_title', 'pmwp_price_catalog', 10 );
		add_action( 'woocommerce_single_product_summary', 'get_pmwp_price', 10 );
		add_action( 'woocommerce_single_product_summary', 'get_pmwp_box', 20 );
}
add_action( 'after_setup_theme', 'pmwp_hooks', 99 );
function pmwp_price_catalog(){
	if(isset($_GET['action'])){
		$args = array(
			'post_type'      => 'product',
			'posts_per_page' => 1
		);
		$products = wc_get_products( $args );
		return get_pmwp_price_catalog($products[0]->get_id());
	}else{
		global $product;
		return get_pmwp_price_catalog($product->get_id());
	}
	
}
add_shortcode('pmwp_price_catalog','pmwp_price_catalog');



function get_pmwp_price_catalog($product_id){
	$product = wc_get_product($product_id);
	$product_type = $product->get_type();
	$html = "";
	echo "<input type='hidden' class='cwmp_product_id' name='cwmp_product_id' value='".$product->get_ID()."' />";
	$html .= "<div class='pmwp_price_catalog'>";
	$html .= cwmp_html_price($product->get_ID());
	$html .= "</div>";
	echo $html;
}

function get_pmwp_price(){
	if(isset($_GET['action'])){
		$args = array(
			'post_type'      => 'product',
			'posts_per_page' => 1
		);
		$products = wc_get_products( $args );
		return pmwp_price($products[0]->get_id());

	}else{
		global $product;
		if(isset($product)){
		return pmwp_price($product->get_id());
		}
	}
}
add_shortcode('get_pmwp_price','get_pmwp_price');

function pmwp_price($product){
	$product = wc_get_product($product);
	$html = "";
	$html .= "<div class='pmwp_price_product'>";
	$html .= cwmp_html_price($product->get_id());
	$html .= "</div>";
	echo $html;
}

function get_pmwp_box(){
	if(isset($_GET['action'])){
		$args = array(
			'post_type'      => 'product',
			'posts_per_page' => 1
		);
		$products = wc_get_products( $args );
		echo get_parcels_box($products[0]->get_id());
	}else{
		global $post;
		if(isset($post)){
		$id = $post->ID;
		echo get_parcels_box($post->ID);
		}
	}
}
add_shortcode('get_pmwp_box','get_pmwp_box');

