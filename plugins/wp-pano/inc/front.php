<?php 
if (!function_exists('add_action')) die('Access denied');

include_once('view/front-pano.php');

add_action("wp_ajax_wppano_GetPostContent", "wppano_GetPostContent");
add_action("wp_ajax_nopriv_wppano_GetPostContent", "wppano_GetPostContent");
function wppano_GetPostContent() {
	$id = intval($_REQUEST["id"]);
	$post_types = get_option('wppano_posttype');
	if( isset($post_types['window'][get_post_type($id)]) ) {
		$template = addslashes(get_template_directory() . '/wp-pano/templates/' . $post_types['window'][get_post_type($id)] . '.php');
		if (file_exists($template)) {
			if (function_exists ( 'pll_get_post' )) $id = pll_get_post( $id , pll_current_language());
			$query = new WP_Query( array( 'p' => $id, 'post_type'=>'any' ) );
			if ( $query->have_posts() ) {
				$query->the_post();
				return require_once($template); 	
			}
			wp_reset_postdata();
		} else {	
			$template = addslashes(__DIR__ . '/../templates/' . $post_types['window'][get_post_type($id)] . '.php');
			if (file_exists($template)) {
				if (function_exists ( 'pll_get_post' )) $id = pll_get_post( $id , pll_current_language());
				$query = new WP_Query( array( 'p' => $id, 'post_type'=>'any' ) );
				if ( $query->have_posts() ) {
					$query->the_post();
					return require_once($template); 	
				}
				wp_reset_postdata();
			}
		}
	}
	wp_die();
}

add_action("wp_ajax_wppano_GetAllHotspots", "wppano_GetAllHotspots");
add_action("wp_ajax_nopriv_wppano_GetAllHotspots", "wppano_GetAllHotspots");
function wppano_GetAllHotspots() {
	$vtourpath = sanitize_text_field($_REQUEST["vtourpath"]);
	$scene_name = sanitize_text_field($_REQUEST["scene_name"]);
	$pano_name = sanitize_text_field($_REQUEST["pano_name"]);
	$post_types = get_option('wppano_posttype');
	$hs_styles = $post_types['hs_style'];
	$post_type = $post_types['type'];
	$result['type'] = "error";
	$hotspots = wppano_get_hotspots($vtourpath, $pano_name, $scene_name);
	if ($hotspots) {
		foreach ($hotspots as $hotspot) 
			if( function_exists ('pll_get_post') ) $post_ids[] = pll_get_post( $hotspot['post_id'] , pll_current_language());
			else $post_ids[] = $hotspot['post_id'];		
		$args = array(
			'posts_per_page' => -1,
			'post__in' 		=> $post_ids,
			'post_type' 	=> $post_type,
			'post_status' 	=> array('inherit', 'publish')
		);
		$query = new WP_Query($args);
		if ( $query->have_posts() ) {
			while ( $query->have_posts() ) {
				$query->the_post();
				$id = get_the_ID();
				if( isset($hs_styles[get_post_type($id)]) ) 
					$hs_style = $hs_styles[get_post_type($id)];
				else {
					$hs_styles_val = array_values($hs_styles);
					$hs_style = $hs_styles_val[0];
				}
				foreach ( $hotspots as $hotspot ) {
					if ( ($hotspot['post_id'] == $id) || (function_exists ('pll_get_post') && (pll_get_post( $hotspot['post_id'] , pll_current_language()) == $id)) ) {
						$hotspot['data'] = unserialize ($hotspot['data']);
						$thumbnail[0] = "";
						if ( !is_null(get_post_thumbnail_id( $id )) ) $thumbnail = image_downsize( get_post_thumbnail_id( $id ), 'thumbnail');
						$AllHotspots[] = array(
							'ID' => $id, 
							'title' => get_the_title(), 
							'data' => $hotspot['data'], 
							'thumbnail' => $thumbnail[0],
							'hs_style' => $hs_style
							);
					}  // end if
				} // end foreach
			} //end while
		} // end if
		$result['type'] = "success";
		wp_reset_postdata();
		$result['hotspots'] = $AllHotspots;
	} else $result['type'] = "nohotspots";// end if
	$result = json_encode($result);
	echo $result;
	wp_die();
}
?>