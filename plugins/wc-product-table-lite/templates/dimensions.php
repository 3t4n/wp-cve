<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if( 
	! isset( $variable_switch ) || // prev. version
	$variable_switch 
){
	$html_class .= ' wcpt-variable-switch '; 
}

if ( $product->has_dimensions() ) {
  echo '<div class="wcpt-dimensions '. $html_class .'" data-wcpt-default-dimensions="'. esc_attr( wc_format_dimensions( $product->get_dimensions( false ) ) ) .'">' . wc_format_dimensions( $product->get_dimensions( false ) ) . '</div>';
}