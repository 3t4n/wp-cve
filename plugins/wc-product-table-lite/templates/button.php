<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// default
if( ! empty( $use_default_template ) ){
	echo '<div class="woocommerce">';
	woocommerce_template_loop_add_to_cart();
	echo '</div>';
	return;
}

// label
if( empty( $label ) ){
	$label = '';
}else{
	$label = '<span class="wcpt-button-label">'. wcpt_parse_2( $label ) .'</span>';
}

// link
if( empty( $link ) ){
	$link = 'product_link';
}

if(
	in_array( $product->get_type(), array( 'external', 'grouped' ) )	&&
	(
		empty( $link ) ||
		in_array( $link, array( 'cart_ajax', 'cart_refresh', 'cart_redirect', 'cart_checkout', 'cart_custom', ) )
	)
){
	$link = 'product_link';
}

switch ( $link ) {
	case 'cart_checkout':
		$href = wc_get_checkout_url();
		break;

	case 'product_link':
		$href = get_permalink( $product->get_id() );
		break;

	case 'external_link':
		if( $product->get_type() !== 'external' || ! $product->get_product_url() ){
			return;
		}else{
			$href = $product->get_product_url();
		}
		break;

	case 'cart_refresh':
		$href = '';
		break;

	case 'custom_field':
		if( empty( $custom_field ) || ! $href = get_post_meta( $product->get_id(), $custom_field, true ) ){
			if( 
				! empty( $custom_field_empty_relabel ) &&
				$empty_relabel = wcpt_parse_2( $custom_field_empty_relabel )
			){
				echo '<span class="wcpt-button-cf-empty">' . $empty_relabel . '</span>';
			}
			return;
		}
		break;

	case 'custom_field_media_id':
		if( empty( $custom_field ) || ! ( $field_value = get_post_meta( $product->get_id(), $custom_field, true ) ) ){
			if( 
				! empty( $custom_field_empty_relabel ) &&
				$empty_relabel = wcpt_parse_2( $custom_field_empty_relabel )
			){
				echo '<span class="wcpt-button-cf-empty">' . $empty_relabel . '</span>';
			}
			return;
		}

		$href = wp_get_attachment_url( $field_value );

		break;

	case 'custom_field_acf':
		if( ! function_exists('get_field_object') ){
			return;
		}

		$acf_field_object = get_field_object( $custom_field );

		if(
			$product->get_type() == 'variation' &&
			! $acf_field_object
		){
			$acf_field_object = get_field_object( $custom_field, $product->get_parent_id() );
		}

		if( ! $acf_field_object ){
			return;
		}

		if( empty( $acf_field_object['value'] ) ){
			return;
		}

		if( 
			$acf_field_object['type'] == 'file' &&
			! empty( $acf_field_object['return_format'] )
		){
			switch ( $acf_field_object['return_format'] ) {
				case 'array':
					$href = $acf_field_object['value']['url'];
					break;

				case 'url':
					$href = $acf_field_object['value'];
					break;

				case 'id':
					$href = wp_get_attachment_url( $acf_field_object['value'] );
					break;
			}

		}else if( in_array( gettype( $acf_field_object['value'] ), array('boolean', 'integer', 'double', 'string') )  ){
			$href = $acf_field_object['value'];
		}

		if( empty( $href ) ){
			return;
		}

		break;
	
	case 'custom':
		if( empty( $custom_url ) ){
			$custom_url = '';
		}

		$href = wcpt_general_placeholders__parse( $custom_url );

		break;

	case 'cart_custom':
		if( empty( $custom_url ) ){
			$custom_url = '';
		}

		$href = wcpt_general_placeholders__parse( $custom_url );

		break;

	default:
		$href = wc_get_cart_url();
		break;
}

// target / download
if( empty( $target ) ){
	$target = ' target="_self" ';

}else if( $target === 'download' ){
	$target = ' download="'. basename( $href ) .'" ';
}else{
	$target = ' target="'. $target .'" ';
}

// disabled class
if(
	! in_array( $link, array( 'product_link', 'external_link', 'custom_field', 'custom_field_media_id', 'custom_field_acf', 'custom' ) ) &&
	(	
		(
			method_exists( $product, 'is_in_stock' ) &&
			! $product->is_in_stock()
		) ||
		(
			method_exists( $product, 'is_purchasable' ) &&
			! $product->is_purchasable()
		)
	)
){
	$disabled_class = ' xxx wcpt-disabled wcpt-out-of-stock';

}else{
	$disabled_class = '';
}

// no follow tag
$nofollow = '';
if(
	$link === 'external_link' &&
	$product->get_type() == 'external' &&
	! empty( $external_link_nofollow )
){
	$nofollow = ' rel="nofollow" ';
}

echo '<a class="wcpt-button wcpt-noselect wcpt-button-'. $link .' ' . $html_class . $disabled_class . '" data-wcpt-link-code="'. $link .'" href="'. $href .'" '. $target .' '. $nofollow .' >' . $label . '</a>';
