<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( 'no' === get_option( 'woocommerce_enable_review_rating' ) ) {
	return;
}

if( empty( $template ) ){
	if( isset( $output_format ) ){
		$template = $output_format;
	}else{
		return;
	}
}

$review_count = $product->get_review_count();
$rating_number = $product->get_average_rating();

if( 
	! empty( $rating_source ) && 
	$rating_source == 'custom_field'
){
	if( ! empty( $rating_number_custom_field ) ){
		$rating_number = get_post_meta( $product->get_id(), $rating_number_custom_field, true );
		if( $rating_number !== "" ){
			$rating_number = (float) $rating_number;		
		}
	}else{
		$rating_number = "";
	}

	if( ! empty( $review_count_custom_field ) ){
		$review_count = (int) get_post_meta( $product->get_id(), $review_count_custom_field, true );
	}else{
		$review_count = 0;
	}

}

if( $rating_number > 5 ){
	$rating_number = 5;
}

if( $rating_number < 0 ){
	$rating_number = 0;
}

$GLOBALS['wcpt_rating_number'] = $rating_number;
$GLOBALS['wcpt_review_count'] = $review_count;

if( 
	(
		$review_count &&
		$rating_number
	) ||
	(
		! empty( $rating_source ) && 
		$rating_source == 'custom_field'	&&
		$rating_number !== ""
	)

){
	echo '<div class="wcpt-rating '. $html_class .'" title="'. $rating_number . ' out of 5 stars">' . wcpt_parse_2( $template ) . '</div>';

}else{
	if( empty( $not_rated ) ){
		$not_rated = '';
	}

	echo '<div class="wcpt-rating '. $html_class .'">' . wcpt_parse_2( $not_rated ) . '</div>';

}

unset( $GLOBALS['wcpt_rating_number'], $GLOBALS['wcpt_review_count'] );