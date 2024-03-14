<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$stock = $product->get_stock_quantity();

$label = $stock;

if( empty( $range_labels ) ){
	$range_labels = '';
}

$rules = preg_split( '/\r\n|[\r\n]/', trim( $range_labels ) );
$_rules = array();

$found_rule = false;

foreach( $rules as $rule ){
	if( ! $rule ){
		continue;
	}
	$exploded_rule = array_map( 'trim', explode( ':', $rule ) );
	$range = $exploded_rule[0];
	$_label = ! empty( $exploded_rule[1] ) ? $exploded_rule[1] : $label;

	$exploded_range = array_map( 'trim', explode( ' - ', $range ) );
	$min = intval( $exploded_range[0] );
	$max = ! empty( $exploded_range[1] ) ? $exploded_range[1] : 99999999999999;

	if( 
		NULL !== $stock &&
		$min <= $stock && 
		$max >= $stock 
	){
		$label = $_label;
		$found_rule = true;		
	}

	$_rules[] = array( $min, $max, $_label );
}

$range_labels_attr = ! empty( $_rules ) ? 'data-wcpt-stock-range-labels="'. esc_attr( json_encode( $_rules ) ) .'"' : '';

$label = trim( str_replace( '[stock]', $stock, $label ) );

if( 
	! isset( $variable_switch ) || // prev. version
	$variable_switch 
){
	$html_class .= ' wcpt-variable-switch '; 
}

echo '<span class="wcpt-stock '. $html_class .'" data-wcpt-stock="'. $stock .'" '. $range_labels_attr .'>'. $label .'</span>';
