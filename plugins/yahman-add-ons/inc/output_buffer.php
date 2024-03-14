<?php
defined( 'ABSPATH' ) || exit;

add_filter( 'after_setup_theme', function(){ ob_start( 'yahman_addons_output_buffer_start' ); } );
add_filter( 'shutdown', 'yahman_addons_ob_end_flush' );

function yahman_addons_output_buffer_start($the_content){

	global $post;
	
	if(!$post) return $the_content;

	$option =  get_option('yahman_addons');



	if( isset($option['javascript']['lazy']) && $option['javascript']['lazy'] === 'lozad' ){
		$judge = preg_match_all('{<img.+?>}is', $the_content, $match);

		if($judge){

			$overlap = array();

			foreach ($match[0] as $str) {

				
				if (in_array($str, $overlap, true)) continue;
				
				$overlap[] = $str;

				$lozad = $str;
				$lozad = preg_replace('{ src=["\'](.+?)["\']}i', ' data-src="$1"', $lozad);

				$judge = preg_match('/class=/i', $lozad, $match_lozad);
				if($judge){
					$lozad = preg_replace('{class=["\'](.*?)["\']}i', 'class="$1 ya_lozad"', $lozad);
				}else{
					$lozad = str_replace('<img', '<img class="ya_lozad"', $lozad);
				}

				if(strpos($lozad,'alt="') === false){
					$lozad = str_replace('<img', '<img alt=""', $lozad);
				}

				$lozad = str_replace(' srcset=', ' data-srcset=', $lozad);

				$lozad .= '<noscript>'.$str.'</noscript>';

				$the_content = preg_replace('{'.preg_quote($str).'(?!<noscript>)}', $lozad , $the_content);
			}
		}


	}



	if( isset($option['faster']['remove_line_breaks']) ){

		if( !current_user_can('switch_themes') && !is_feed() && !is_admin() ) {

			
			preg_match_all( "/<script.*?>(.*?)<\/script>/s", $the_content, $script_tag );

			
			preg_match_all( "/<code.*?>(.*?)<\/code>/s", $the_content, $search );

			
			$the_content = preg_replace('/(^[\s\t]+|[\s\t]+$|^\n)/mu', '', $the_content);

			
			$the_content = preg_replace('/>\n/mu', '>', $the_content);

			
			$the_content = preg_replace('/[\s\t]{2,}/u', ' ', $the_content);

			
			$the_content = str_replace(array("\r", "\n"), '', $the_content);

			
			$count = -1;
			$the_content = preg_replace_callback(
				'/<script.*?>.*?<\/script>/s',
				function ($matches) use (&$count, &$script_tag) {
					$count++;
					return $script_tag[0][$count];
				},
				$the_content
			);


			$count = -1;
			$the_content = preg_replace_callback(
				'/<code.*?>.*?<\/code>/s',
				function ($matches) use (&$count, &$search) {
					$count++;
					return $search[0][$count];
				},
				$the_content
			);
		}

	}



	if( isset($option['faster']['cache']) && is_singular() && !is_user_logged_in() ){

		yahman_addons_make_cache( 'faster' , $the_content , $option['faster'] );

	}


	return $the_content;

}

function yahman_addons_make_cache( $type , $the_content , $option ){

	
	$post_ID = get_the_ID();

	$post_type = get_post_type( $post_ID );

	
	if( !$post_type || ( $post_type !== 'post' && $post_type !== 'page' ) ) return;

	
	$post_not_in = array();
	$judge = true;

	
	if( !empty($option['cache_post_not_in']))

		$post_not_in = explode(',', $option['cache_post_not_in']);

	if( in_array ( $post_ID , $post_not_in  ) ) {
		$judge = false;
	}

	
	if( $judge && isset($option['cache_parent_not_in']) ){

		$parents_id = array_reverse ( get_post_ancestors($post_ID) );

		
		if( isset( $parents_id[0] ) && in_array ( $parents_id[0] , explode(',', $option['cache_parent_not_in'])  ) ) {
			$judge = false;
		}


	}

	if( 'embed' === basename( untrailingslashit ( strtok ( $_SERVER["REQUEST_URI"], '?' ) ) ) )
		$judge = false;

	if($judge){
		$page_num = '';
		global $multipage;
		if($multipage){
			global $page;
			if($page !== 1)
				$page_num = '-'.$page;
		}

		set_transient( 'ya_'.$type.'_cache_' . $post_ID.$page_num,
			$the_content,
			60 * 60 * 24 * ( ( int )$option['cache_period'] )
		);
	}


}
