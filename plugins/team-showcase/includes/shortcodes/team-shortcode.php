<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

# shortocde
function team_manager_free_register_shortcode( $atts, $content = null ) {
	$atts = shortcode_atts(
		array(
			'id' => "",
		),
		$atts
	);

	global $post, $paged, $query;	
	$post_id = $atts['id'];

    $team_manager_free_category_select 			= get_post_meta( $post_id, 'team_manager_free_category_select', true);
    $team_manager_free_post_themes 				= get_post_meta( $post_id, 'team_manager_free_post_themes', true);
    $team_manager_free_post_column 				= get_post_meta( $post_id, 'team_manager_free_post_column', true);
    $team_manager_free_margin_bottom 			= get_post_meta( $post_id, 'team_manager_free_margin_bottom', true);
    $team_manager_free_margin_lfr 				= get_post_meta( $post_id, 'team_manager_free_margin_lfr', true);
    $team_manager_free_imagesize 				= get_post_meta( $post_id, 'team_manager_free_imagesize', true);
    $team_manager_free_header_font_size 		= get_post_meta( $post_id, 'team_manager_free_header_font_size', true);
    $team_manager_free_designation_font_size 	= get_post_meta( $post_id, 'team_manager_free_designation_font_size', true);
    $team_manager_free_biography_option 		= get_post_meta( $post_id, 'team_manager_free_biography_option', true);
    $team_manager_free_biography_font_size 		= get_post_meta( $post_id, 'team_manager_free_biography_font_size', true);
    $team_manager_free_social_target 			= get_post_meta( $post_id, 'team_manager_free_social_target', true);
    $team_manager_free_header_font_color 		= get_post_meta( $post_id, 'team_manager_free_header_font_color', true);
    $team_manager_free_name_hover_font_color 	= get_post_meta( $post_id, 'team_manager_free_name_hover_font_color', true);
    $team_manager_name_font_case 				= get_post_meta( $post_id, 'team_manager_name_font_case', true);
    $team_manager_desig_font_case 				= get_post_meta( $post_id, 'team_manager_desig_font_case', true);
    $team_manager_free_designation_font_color 	= get_post_meta( $post_id, 'team_manager_free_designation_font_color', true);
    $team_manager_free_biography_font_color 	= get_post_meta( $post_id, 'team_manager_free_biography_font_color', true);
    $team_manager_free_overlay_bg_color 		= get_post_meta( $post_id, 'team_manager_free_overlay_bg_color', true);
    $team_manager_free_img_height 				= get_post_meta( $post_id, 'team_manager_free_img_height', true);
    $team_fbackground_color 					= get_post_meta( $post_id, 'team_fbackground_color', true);
    $teamf_orderby 								= get_post_meta( $post_id, 'teamf_orderby', true);

    if( is_array( $team_manager_free_category_select ) ){
		$tmfree =  array();
		$num 	= count((array)$team_manager_free_category_select);
		for($j=0; $j<$num; $j++){
			array_push($tmfree, $team_manager_free_category_select[$j]);
		}

		$args = array(
			'post_type' => 'team_mf',
			'post_status' => 'publish',
			'posts_per_page' => 10,
			'orderby'	=> $teamf_orderby,
		    'tax_query' => [
		        'relation' => 'OR',
		        [
		            'taxonomy' => 'team_mfcategory',
		            'field' => 'id',
		            'terms' => $tmfree,
		        ],
		        // [
		        //     'taxonomy' => 'team_mfcategory',
		        //     'field' => 'id',
		        //     'operator' => 'NOT EXISTS',
		        // ],
		    ],
		);
    }else{
		$args = array(
			'post_type' => 'team_mf',
			'post_status' => 'publish',
			'posts_per_page' => 10,
			'orderby'	=> $teamf_orderby,
		);
    }

  	$tmf_query = new WP_Query( $args );

	ob_start();
	switch ( $team_manager_free_post_themes ) {
	    case 'theme1':

	    	include __DIR__ . '/template/theme-1.php';

	        break;
	    case 'theme2':

	    	include __DIR__ . '/template/theme-2.php';

	        break;
	    case 'theme3':

			include __DIR__ . '/template/theme-3.php';
		
	        break; 
	    case 'theme4':

			include __DIR__ . '/template/theme-4.php';

	    break;
	}
	return ob_get_clean();
}
add_shortcode( 'tmfshortcode', 'team_manager_free_register_shortcode' );