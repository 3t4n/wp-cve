<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly	 		 		 		 		 		 	
add_action( 'wp_ajax_silconfolio_ajax_request', 'silconfolio_ajax_request' );
add_action( 'wp_ajax_nopriv_silconfolio_ajax_request', 'silconfolio_ajax_request' );

function silconfolio_ajax_request() {
wp_reset_postdata();
global $post;
$result['new_posts'] ='';
if ($_GET['st_sf_tag'] =="All"){
$args = array(
		'post_type' => 'portfolio',
		'posts_per_page' => $_GET['st_sf_load_post_count'],
		'offset' => $_GET['st_sf_modal']
);}else{
$e_tag = $_GET['st_sf_tag'];
$p_tag = get_term_by('name', $e_tag, 'portfolio-tags');

	$args = array(
		'post_type' => 'portfolio',
		'posts_per_page' => $_GET['st_sf_load_post_count'],
		'offset' => $_GET['st_sf_modal'],
		'tax_query' => array(
			array(
				'taxonomy' => 'portfolio-tags',
				'terms'    => $p_tag->term_id,
			),
		),
);
	}
	$the_query = new WP_Query( $args );
	if ( $the_query->have_posts() ) {
	while ( $the_query->have_posts() ) {
		$the_query->the_post();
			$catt = get_the_terms( $post->ID, 'portfolio-category' );
			$slugg = '';
			$slug = ''; 
			foreach($catt  as $vallue=>$key){
				$slugg .= strtolower($key->slug) . " ";
				$slug  .= ''.$key->name.', ';
			}
			
		
		
		$large_image_url = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), '');
   	$portfolio_layout = $_GET['st_sf_layout_mode'];
   	$portfolio_item_width = get_post_meta($post->ID, 'st_sf_th', 1);
	
	if ($portfolio_item_width == 'portfolio-squre'){ $col='st_sf_col col-md-3 st_sf_x1'; };
	if ($portfolio_item_width == 'portfolio-squrex2'){ $col='st_sf_col col-md-6 st_sf_x2'; };
	if ($portfolio_item_width == 'portfolio-wide'){ $col='st_sf_col col-md-6 st_sf_x1'; };
	if ($portfolio_item_width == 'portfolio-long'){ $col='st_sf_col col-md-3 st_sf_x2'; };
	if($portfolio_layout == 'Square Thumbnails Without Spaces'){
 		$col='st_sf_col col-md-4 st_sf_x1'; 
	}
	elseif($portfolio_layout == 'Square Thumbnails With Spaces'){
		$col='col-md-4 st_sf_x1';
	}
	elseif($portfolio_layout == '4 Square Thumbnails Without Spaces'){
		$col='st_sf_col col-md-3 st_sf_x1';
	}
	elseif($portfolio_layout == '4 Square Thumbnails With Spaces'){
		$col='col-md-3 st_sf_x1';

	}
	elseif($portfolio_layout == 'Half Thumbnails With Spaces'){
		$col='col-md-6 st_sf_x1'; 
	
	}
	elseif($portfolio_layout == 'Half Thumbnails Without Spaces'){
		$col='st_sf_col col-md-6 st_sf_x1';
	}
		$result['new_posts'] .='<div class="st_sf_strange_portfolio_item st_sf_port_style_ii '.$col.' '.$slugg.'">';
            $result['new_posts'] .='<div class="st_sf_vc_potrfolio" style="background:url('.$large_image_url[0].')">';
            	$result['new_posts'] .='<a href="'.get_the_permalink($post->ID).'">';
                $result['new_posts'] .='<div class="st_sf_vc_port_mask"  style="background:'.get_post_meta($post->ID, 'port-bg', true).'">';
                    $result['new_posts'] .='<div class="text-center">';
						$result['new_posts'] .='<i class="fa fa-eye" style="color:'.get_post_meta($post->ID, 'port-text-color', true).'"></i>';
						$result['new_posts'] .='<div class="hover_overlay">';
							$result['new_posts'] .= '<h3 class="st_sf_sub_legend" style="color:'.get_post_meta($post->ID, 'port-text-color', true).'">'.get_the_title($post->ID).'</h3>';
							$result['new_posts'] .= '<div class="st_sf_vc_sep" style="background:'.get_post_meta($post->ID, 'port-text-color', true).'"></div>';
							$result['new_posts'] .= '<div class="st_sf_vc_port_cat" style="color:'.get_post_meta($post->ID, 'port-text-color', true).'">'.substr($slug, '0', '-2').'</div>';
                    	$result['new_posts'] .='</div>';
					$result['new_posts'] .='</div>';
                $result['new_posts'] .='</div>';
                $result['new_posts'] .='</a>';
            $result['new_posts'] .='</div>';
        $result['new_posts'] .='</div>';


		}
	}
$result['count_new_posts'] = $_GET['st_sf_post_count'] + $_GET['st_sf_load_post_count'];
$result['loading'] = __("Load More", "orangeidea");
$result['all_loaded'] = __("", "orangeidea");

wp_reset_postdata();
print json_encode($result);
die();
}
?>