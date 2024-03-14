<?php
if (!defined('ABSPATH')) {
	exit;
}

function woobsp_bestselling_list($productID, $thumbs, $stars) {
	$product = wc_get_product($productID);
	$sc_output = '<li class="woobsp-product">';
	$sc_output .= '<a href="'. get_permalink($productID->ID) .'" title="'. esc_attr($productID->post_title ? $productID->post_title : $productID->ID) .'">';
	if ($thumbs == '1' || $thumbs == 'yes') {
		if (has_post_thumbnail($productID->ID)) {
			$sc_output .= get_the_post_thumbnail($productID->ID, 'thumbnail');
		} else { 
			$sc_output .= '<img src="'. wc_placeholder_img_src('thumbnail') .'" alt="'. __( 'Placeholder', 'woo-best-selling-products' ) .'" width="38" height="38" />';
		}
	}

	$sc_output .= '<div class="product-meta">';
	$sc_output .= '<p class="product-title">'. $productID->post_title . '</p>';
	if ($stars == '1' || $stars == 'yes') {
	$sc_output .= '<div class="product-star-rating">'. wc_get_rating_html( $product->get_average_rating() ) . '</div>';
	}
	$sc_output .= '<div class="price-amount">'. $product->get_price_html() . '</div>';
	$sc_output .= '</div>';
	$sc_output .= '</a>';
	$sc_output .= '</li>';

	return $sc_output;
}