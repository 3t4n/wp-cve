<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if( $product->get_type() == 'variable' ){
	$prices 		= $product->get_variation_prices( true );
	$min_price 	= current( $prices['price'] );

}else if( $product->get_type() == 'grouped' ){
	$prices = wcpt_get_grouped_product_price();
	$min_price = $prices['min_price'];

}

$min_price = apply_filters('wcpt_product_get_lowest_price', $min_price, $product);

echo '<span class="wcpt-lowest-price '. $html_class .'">' . wcpt_price( $min_price ) . '</span>';
