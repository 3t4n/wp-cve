<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

// don't exit this script if there is no sale price for the product because the parsed template for sale element is still needed by variation switch 

$on_sale = apply_filters('wcpt_product_is_on_sale', $product->is_on_sale(), $product);

if( ! $on_sale ){
	$sale_price = 0;

}else{
	$sale_price = wc_get_price_to_display( $product, array(
		'qty' => 1,
		'price' => $product->get_sale_price(),
	) );

}

$sale_price = apply_filters('wcpt_product_get_sale_price', $sale_price, $product);

echo '<span class="wcpt-sale-price '. $html_class .'">' . wcpt_price( $sale_price ) . '</span>';
