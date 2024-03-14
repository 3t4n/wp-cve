<?php
defined( 'ABSPATH' ) || exit;


add_action( 'plugins_loaded', 'yahman_addons_plugins_loaded', 1 );

function yahman_addons_plugins_loaded() {

	
	if (!defined('YAHMAN_ADDONS_TEMPLATE'))
		define( 'YAHMAN_ADDONS_TEMPLATE', in_array( get_template() , array(
			'simple-days',
			'neatly',
			'laid-back',
		) , true ) );



	if( is_admin() ){

		require_once YAHMAN_ADDONS_DIR . 'inc/admin.php';

		
		if(YAHMAN_ADDONS_VERSION !== get_option('yahman_addons_version') ){
			require_once YAHMAN_ADDONS_DIR . 'inc/admin/update_option.php';
			yahman_addons_update_options();
		}

	}else{

		$option =  get_option('yahman_addons');

		require_once YAHMAN_ADDONS_DIR . 'inc/get_id_by_slug.php';
		$cache = yahman_addons_get_id_by_slug();

		if( !is_user_logged_in() ){

			
			if( isset($option['faster']['cache']) ){

				if( $cache['ID'] ){

					$cache_content = get_transient( 'ya_faster_cache_' . $cache['ID'].$cache['page_num'] );

					if ( $cache_content ) {

						echo $cache_content;
					//require_once YAHMAN_ADDONS_DIR . 'inc/remove_actions.php';
					//yahman_addons_remove_all_actions();

						if(isset($option['pv']['enable'])){
							require_once YAHMAN_ADDONS_DIR . 'inc/page_view.php';
							yahman_addons_page_view( $cache['ID'] );
						}

						exit;

					}

				}


			}

		}


		
		require_once YAHMAN_ADDONS_DIR . 'inc/action_get_header.php';


		if ( !in_array( $GLOBALS['pagenow'], array( 'wp-login.php', 'wp-register.php'  ) ) && !wp_is_json_request() ) {
			
			
			require_once YAHMAN_ADDONS_DIR . 'inc/output_buffer.php';

			require_once YAHMAN_ADDONS_DIR . 'inc/extra-content.php';
		}

	}

	
	if ( defined( 'POLYLANG_VERSION' )  ) {
		require_once YAHMAN_ADDONS_DIR . 'inc/third/polylang.php';
		$yahman_addons_polylang = new YAHMAN_ADDONS_Polylang();
	}

	
	require_once YAHMAN_ADDONS_DIR . 'inc/action_init.php';

	
	require_once YAHMAN_ADDONS_DIR . 'inc/action_wp_header.php';

	
	require_once YAHMAN_ADDONS_DIR . 'inc/action_wp.php';

	
	require_once YAHMAN_ADDONS_DIR . 'inc/action_widgets_init.php';

	
	require_once YAHMAN_ADDONS_DIR . 'inc/action_template_redirect.php';

	
	require_once YAHMAN_ADDONS_DIR . 'inc/action_wp_footer.php';

}

function yahman_addons_ob_end_flush() {
	if (ob_get_contents()) ob_end_flush();
	if (ob_get_length()) ob_end_flush();
}
/*
function yahman_addons_get_post_url() {

	$cache['ID'] = $cache['page_num'] = '';
var_dump((empty($_SERVER['HTTPS']) ? 'http://' : 'https://').$_SERVER['HTTP_HOST'].$_SERVER["REQUEST_URI"]);
//var_dump(url_to_postid($_SERVER["REQUEST_URI"]));
var_dump(url_to_postid((empty($_SERVER['HTTPS']) ? 'http://' : 'https://').$_SERVER['HTTP_HOST'].$_SERVER["REQUEST_URI"]));
global $post;
	var_dump($post);
	var_dump( function_exists('url_to_postid' ) );
	$req_url = untrailingslashit ( strtok ( $_SERVER["REQUEST_URI"], '?' ) ) ;

	$base_name = basename( $req_url );

	$page_num = get_page_by_path( $req_url , "ARRAY_A" , 'page' );

	if($page_num === null){


	}
	var_dump($page_num);

	$post_num = get_page_by_path( $req_url , "ARRAY_A" , array('post','page')  )
	$post_num_post = get_page_by_path( basename( $req_url )  , "ARRAY_A" , array('post','page')  );
	var_dump($req_url);
	var_dump($post_num);
	var_dump( $post_num_post );
	var_dump( function_exists('get_posts' ) );
	if($post_num === null){

		$last_num = basename( $req_url );
		if( strlen($last_num) === strlen( (int) $last_num) ){

			$page_req_url = substr($req_url, 0, -(int)strlen( $last_num ) );
			$post_num = get_page_by_path(  basename( $page_req_url )  , "ARRAY_A" , array('post','page')  );
			if( isset($post_num['ID']) ){
				$cache['ID'] = $post_num['ID'];
				$cache['page_num'] = '-'.$last_num;
			}
		}elseif(function_exists('pll_get_post')){

			if( substr($req_url, 0, 1) === '/' && substr($req_url, 3, 1) === '/' ){
				$lang = substr($req_url, 1, 2);

				$post_num = get_page_by_path( substr($req_url, 4) );
				if( isset($post_num->ID) ){
					$post_num = pll_get_post( $post_num->ID );
					if( $post_num ) $cache['ID'] = $post_num;
				}
			}
		}

	}else{

		if(function_exists('pll_get_post')){
			$pll = pll_get_post( $post_num['ID'] );
			if( $pll ) $post_num['ID'] = $pll;
		}

		$cache['ID'] = $post_num['ID'];
	}

	return $cache;


}
*/