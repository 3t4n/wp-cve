<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$sku = $product->get_sku();

// if( 
// 	$product->get_type() == 'variable' &&
// 	$default_variation = wcpt_get_default_variation( $product )
// ){
// 	$product_variation = wc_get_product( $default_variation['variation_id']);
// 	if( $product_variation->get_sku() ){
// 		$sku = $product_variation->get_sku();
// 	}
// }

if( ! empty( $variable_switch ) ){
	$html_class .= ' wcpt-variable-switch '; 
}

if( ! empty( $product_link_enabled ) ){
	$target = empty( $target_new_page ) ? '_self' : '_blank';

	echo '<a href="'. get_permalink() .'" target="'. $target .'" class="wcpt-sku '. $html_class .'" data-wcpt-sku="'. $product->get_sku() .'">' . $sku . '</a>';
	
}else{
	echo '<span class="wcpt-sku '. $html_class .'" data-wcpt-sku="'. $product->get_sku() .'">' . $sku . '</span>';

}

