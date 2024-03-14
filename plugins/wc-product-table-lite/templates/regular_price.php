<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if( $product->get_type() == 'variable' ){
	$prices 				= $product->get_variation_prices( true );
	$regular_price 	= current( $prices['regular_price'] );

}else{
	$regular_price = wc_get_price_to_display( $product, array(
		'qty' => 1,
		'price' => $product->get_regular_price(),
	) );

}

$regular_price = apply_filters('wcpt_product_get_regular_price', $regular_price, $product);

$inner_markup = '';

if ( $regular_price ){
	$inner_markup = wcpt_price( $regular_price );
}else{
	$inner_markup = apply_filters( 'woocommerce_empty_price_html', wcpt_price( $regular_price ), $product );
}

echo "<span class=\"wcpt-regular-price $html_class \">$inner_markup</span>";