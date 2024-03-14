<?php
/* Adding Shortcode Support

*/
function virp_shortcode( $atts, $content ) {
	if(!empty($atts['cat'])){
		$cat_ids= explode(',', $atts['cat']);
		$cat_count = count($cat_ids);
		$atts['cat']=array();
		for($i=0; $i<$cat_count; $i++){
			array_push($atts['cat'],$cat_ids[$i]);
		}
	}
	$args = shortcode_atts( virp_get_default_args(), $atts );
	return virp_get_random_posts( $args );
}
add_shortcode( 'virp', 'virp_shortcode' );