<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// product variation
if( in_array( $product->get_type(), array( 'variation', 'subscription_variation' ) ) ){
  $product_id_data = ' data-wcpt-product-id="'. wp_get_post_parent_id( $product->get_id() ) .'" ';
	$variation_id_data = ' data-wcpt-variation-id="'. $product->get_id() .'" ';
	$variation_attributes_data = '';
	$variation_attributes = $product->get_variation_attributes();
	if( $variation_attributes ){
		$variation_attributes_data = ' data-wcpt-variation-attributes="'. esc_attr( json_encode( $variation_attributes ) ) .'" ';
	}

// other product type
}else{
	$product_id_data = ' data-wcpt-product-id="'. $product->get_id() .'" ';
	$variation_id_data = '';
	$variation_attributes_data = '';

}

$product_type_html_class = 'wcpt-product-type-' . $product->get_type();

$in_cart = wcpt_get_cart_item_quantity($product->get_id());

$stock = $product->get_stock_quantity();

$html_class = ' wcpt-row '; // main row class

global $wcpt_products;

$html_class .= ' wcpt-'. ( $wcpt_products->current_post % 2 ? 'odd' : 'even' )  .' '; // even / odd class

$html_class .= ' '. $product_type_html_class .' '; // product type

if( $product->get_type() == 'variable-subscription' ){
	$html_class .= ' wcpt-product-type-variable ';
}

if( 
	$product->get_type() == 'variable' &&
	wcpt_all_variations_out_of_stock( $product->get_id() )
){
	$html_class .= ' wcpt-all-variations-out-of-stock ';
}

if( $product->get_sold_individually() ){
	$html_class .= ' wcpt-sold-individually ';
}

$html_class = apply_filters( 'wcpt_product_row_html_class', $html_class, $product, $wcpt_products );

$attributes = apply_filters( 
	'wcpt_product_row_attributes', 
	// attribute list start
	' '. $variation_id_data .' 
	'. $variation_attributes_data .' 
	'. $product_id_data .' 
	data-wcpt-type    ="'. $product->get_type() .'" 
	data-wcpt-in-cart ="'. $in_cart .'" 
	data-wcpt-stock   ="'. $stock .'"
	data-wcpt-price   ="'. wcpt_get_price_to_display($product) .'" '
	, // attribute list end
	$product,
	$wcpt_products
);

echo '<tr '. $attributes .' class="'. $html_class .'">';