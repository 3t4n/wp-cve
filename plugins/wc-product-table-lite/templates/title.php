<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

$title = get_the_title( $product->get_id() );

if( empty( $html_tag ) ){
	$html_tag = 'span';
}

$html_class_attr = " class='wcpt-title $html_class' ";
$url = false;

if( 
	! empty( $product_link_enabled ) ||
	! empty( $link )
){
	$target = ! empty( $target_new_page ) ? ' target="_blank" ' : '';

	$url = get_the_permalink( $product->get_id() );

	if( 
		! empty( $link ) &&
		$link == 'custom_field' &&
		! empty( $custom_field )
	){
		$post_meta = get_post_meta( $product->get_id(), $custom_field, true );

		if( ! $post_meta ){
			if( ! empty( $custom_field_default_product_page ) ){
				$url = get_the_permalink( $product->get_id() );
			}else{
				$url = false;				
			}

		}else{
			$url = $post_meta;		

		}

	}

}

if( $url ){

	$href = "href='$url'";

	$esc_title = esc_attr( $title );

	$title_attr = "title='$esc_title'";

	$attr = "$href $target $title_attr";

	if( $html_tag == 'span' ){
		$attr .= " $html_class_attr";		

		echo "<a $attr>$title</a>";
		return;
	}

	$title = "<a $attr>$title</a>";
}

echo "<$html_tag $html_class_attr>$title</$html_tag>";