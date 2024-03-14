<?php 

if ( ! defined( 'ABSPATH' ) )
	exit; # Exit if accessed directly

# shortocde
function carpro_slider_post_query($atts, $content = null){
	$atts = shortcode_atts(
		array(
			'id' => "",
			), $atts);
	global $post;

	$postid = $atts['id'];

	$carpro_slider_postoptions = get_post_meta( $postid, 'carpro_slider_postoptions', true );

	if(!empty($carpro_slider_postoptions['post_types'])){
		$post_types = $carpro_slider_postoptions['post_types'];
	}
	else{
		$post_types = array('post');
	}

	if(!empty($carpro_slider_postoptions['categories'])){
		$categories = $carpro_slider_postoptions['categories'];
	}
	else{
		$categories = array();
	}


	$carpro_slider_order_cat    = get_post_meta($postid, 'carpro_slider_order_cat', true);
	$carpro_slider_order    	= get_post_meta($postid, 'carpro_slider_order', true);
	$carpro_slider_styles   	= get_post_meta($postid, 'carpro_slider_styles', true);
	$excerpt_lenght      		= get_post_meta($postid, 'excerpt_lenght', true);
	$btn_readmore 		 		= get_post_meta($postid, 'btn_readmore', true);

	foreach($categories as $category){
		$tax_cat = explode(',',$category);
		$tax_terms[$tax_cat[0]][] = $tax_cat[1];
	}

	if(empty($tax_terms)){
		$tax_terms = array(); 
	}

	foreach($tax_terms as $taxonomy=>$terms){
		$tax_query[] = array(
						'taxonomy' => $taxonomy,
						'field'    => 'term_id',
						'terms'    => $terms
						);
	}

	if(empty($tax_query)){
		$tax_query = array();
	}

	$args = array (
		'post_type' 	 => $post_types,
		'post_status' 	 => 'publish',
		'tax_query' 	 => $tax_query,
		'posts_per_page' => -1,
		'orderby'	   	 =>$carpro_slider_order_cat,
		'order'			 => $carpro_slider_order,
	);

	$query = new WP_Query($args);	
	$html='';

	switch ($carpro_slider_styles) {
	    case '1':
	        require_once(themepoints_carousel_plugin_dir.'themes/theme-1.php');
	        break;
	    case '7':
	        require_once(themepoints_carousel_plugin_dir.'themes/theme-7.php');
	        break;
	}

	return $html; 

}
add_shortcode('carousel_composer', 'carpro_slider_post_query');