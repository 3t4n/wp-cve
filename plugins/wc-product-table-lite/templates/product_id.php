<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$product_id = $product->get_id();

// if( 
// 	$product->get_type() == 'variable' &&
// 	$default_variation = wcpt_get_default_variation( $product )
// ){
// 	$product_variation = wc_get_product( $default_variation['variation_id']);
// 	$product_id = $product_variation->get_id();
// }

if( ! empty( $variable_switch ) ){
	$html_class .= ' wcpt-variable-switch '; 
}

echo '<span class="wcpt-product-id '. $html_class .'" data-wcpt-product-id="'. $product->get_id() .'">'. $product_id  .'</span>';  