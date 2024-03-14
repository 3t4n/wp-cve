<?php
defined( 'ABSPATH' ) || exit;

function yahman_addons_get_id_by_slug() {

	$cache['ID'] = $cache['page_num'] = '';

	$req_url = untrailingslashit ( strtok ( $_SERVER["REQUEST_URI"], '?' ) );

	
	if($req_url === '') return $cache;

	$result = get_page_by_path(  $req_url  , "ARRAY_A" , 'page' );

	if($result === null) $result = get_page_by_path(  basename( $req_url )  , "ARRAY_A" , 'post' );

	if($result === null){

		$page_num = basename( $req_url );

		if( ( substr($req_url, -2, 1) === '/' || substr($req_url, -3, 1) === '/' ) && ctype_digit($page_num) ){
			
			$page_break_url = substr($req_url, 0, -(int)strlen( $page_num ) );

			$result = get_page_by_path(  $page_break_url  , "ARRAY_A" , 'page' );

			if($result === null) $result = get_page_by_path(  basename( $page_break_url )  , "ARRAY_A" , 'post' );

			if( isset($result['ID']) ){
				$cache['ID'] = $result['ID'];
				$cache['page_num'] = '-'.$page_num;
			}

		}elseif( function_exists('pll_get_post') && substr($req_url, 0, 1) === '/' && substr($req_url, 3, 1) === '/'){
			

			
			

			$result = get_page_by_path(  substr($req_url, 4)  , "ARRAY_A" , 'page' );

			if($result === null) $result = get_page_by_path(  basename( substr($req_url, 4) )  , "ARRAY_A" , 'post' );

			if( isset($result['ID']) ){
				$post_num = pll_get_post( $result['ID'] );
				if( $post_num ) $cache['ID'] = $post_num;
			}

		}


	}else{

		if(function_exists('pll_get_post')){
			$pll = pll_get_post( $result['ID'] );
			if( $pll ) $result['ID'] = $pll;
		}

		$cache['ID'] = $result['ID'];

	}




	return $cache;



}
