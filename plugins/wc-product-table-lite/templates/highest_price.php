<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if( $product->get_type() == 'variable' ){
	$prices 		= $product->get_variation_prices( true );
	$max_price 	= end( $prices['price'] );	

}else if( $product->get_type() == 'grouped' ){
	$prices = wcpt_get_grouped_product_price();
	$max_price = $prices['max_price'];

}

$max_price = apply_filters('wcpt_product_get_highest_price', $max_price, $product);

echo '<span class="wcpt-highest-price '. $html_class .'">' . wcpt_price( $max_price ) . '</span>';