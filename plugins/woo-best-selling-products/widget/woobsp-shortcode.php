<?php

if (!defined('ABSPATH')) {
	exit;
}


function woobsp_sc( $atts ) {
	$a = shortcode_atts( array(
		'category' => '',
		'posts' => 3,
		'thumbnail' => '',
		'stars' => '',
		), $atts );

	$sc_cat = $a['category'];
	$sc_posts = $a['posts'];
	$sc_thumbs = $a['thumbnail'];
		$sc_stars = $a['stars'];

	$output = '<ul class="woobsp_bestselling_list';
	if(empty($sc_thumbs)){
		$output .= ' woobsp_nothumb';
	}
	$output .= ' woobsp_sc">';

	$loop_args = array(
		'post_type' => 'product',
		'posts_per_page' => $sc_posts,
		'product_cat' => $sc_cat,
		'meta_key' => 'total_sales',
		'orderby' => 'meta_value_num',
		'thumbs' => $sc_thumbs
		);
	$loop = new WP_Query($loop_args);
	while ($loop->have_posts()):
		$loop->the_post();
	//global $product;
	include_once('functions/woobsp-func-list.php');
	$output .= woobsp_bestselling_list($loop->post, $sc_thumbs, $sc_stars);
	endwhile;
	wp_reset_query();

	$output .= '</ul>';
	return $output;
}
add_shortcode( 'woobsp', 'woobsp_sc' );