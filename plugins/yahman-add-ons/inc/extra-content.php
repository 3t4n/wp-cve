<?php
defined( 'ABSPATH' ) || exit;
/**
 * Extra content
 *
 * @package YAHMAN Add-ons
 */

add_filter('the_content','yahman_addons_replace_content', 1000);


function yahman_addons_replace_content($the_content) {

	if( !is_singular() ) return $the_content;

	$option = get_option('yahman_addons') ;




	if(isset($option['blogcard']['internal']) || isset($option['blogcard']['external'])){

		require_once YAHMAN_ADDONS_DIR . 'inc/get_thumbnail.php';
		require_once YAHMAN_ADDONS_DIR . 'inc/blog_card.php';
		$the_content = yahman_addons_blog_card($the_content,$option);

	}




	if( is_single() ){

		if(isset($option['toc']['post']) ){

			//$option['toc']['post_not_in'] = isset($option['toc']['post_not_in']) ? $option['toc']['post_not_in']: '';
			$judge = true;

			$post_not_in = explode(',', $option['toc']['post_not_in']);
			if( in_array ( get_the_ID(), $post_not_in  ) ) {
				$judge = false;
			}

			if( $judge && isset($option['toc']['parent_not_in']) ){
				$parents_id = array_reverse ( get_post_ancestors(get_the_ID()) );
				
				if( isset( $parents_id[0] ) && in_array ( $parents_id[0] , explode(',', $option['toc']['parent_not_in'])  ) ) {
					$judge = false;
				}
			}

			if( $judge ) {
				require_once YAHMAN_ADDONS_DIR . 'inc/toc.php';
				$the_content = yahman_addons_toc($the_content,$option);
			}


		}

		if ( is_active_sidebar( 'post_before_h2_no_1' ) || is_active_sidebar( 'post_before_h2_no_2' ) ||is_active_sidebar( 'post_before_h2_no_3' ) ) {

			require_once YAHMAN_ADDONS_DIR . 'inc/heading_widget.php';
			$the_content = yahman_addons_heading_widget($the_content,'post');

		}


		if( isset($option['cta_social']['post']) ){

			require_once YAHMAN_ADDONS_DIR . 'inc/get_thumbnail.php';
			require_once YAHMAN_ADDONS_DIR . 'inc/cta_social.php';
			if(!YAHMAN_ADDONS_TEMPLATE){
				$the_content .= yahman_addons_cta_social();

				add_action( 'wp_footer', 'yahman_addons_enqueue_style_cta' );
			}
		}

		if( isset($option['share']['post']) ){

			require_once YAHMAN_ADDONS_DIR . 'inc/social-share.php';
			if(!YAHMAN_ADDONS_TEMPLATE){
				$the_content .= yahman_addons_social_share();

			}
		}

		if( isset($option['related_posts']['post']) ){

			require_once YAHMAN_ADDONS_DIR . 'inc/related_posts.php';

			if(!YAHMAN_ADDONS_TEMPLATE){
				$the_content .= yahman_addons_related_post();
			}
		}

	}elseif( is_page() ){

		if(isset($option['sitemap']['enable'])){
			if(!empty($option['sitemap']['slug'])){
				if ($GLOBALS['post']->post_name == $option['sitemap']['slug']) {
					require_once YAHMAN_ADDONS_DIR . 'inc/site_map.php';
					$the_content = yahman_addons_site_map();
				}
			}
		}

		if(isset($option['toc']['page']) ){

			//$option['toc']['post_not_in'] = isset($option['toc']['post_not_in']) ? $option['toc']['post_not_in']: '';
			$judge = true;

			$post_not_in = explode(',', $option['toc']['post_not_in']);
			if( in_array ( get_the_ID(), $post_not_in  ) ) {
				$judge = false;
			}

			if( $judge && isset($option['toc']['parent_not_in']) ){
				$parents_id = array_reverse ( get_post_ancestors(get_the_ID()) );
				
				if( isset( $parents_id[0] ) && in_array ( $parents_id[0] , explode(',', $option['toc']['parent_not_in'])  ) ) {
					$judge = false;
				}
			}

			if( $judge ) {
				require_once YAHMAN_ADDONS_DIR . 'inc/toc.php';
				$the_content = yahman_addons_toc($the_content,$option);
			}


		}



		if ( is_active_sidebar( 'page_before_h2_no_1' ) || is_active_sidebar( 'page_before_h2_no_2' ) ||is_active_sidebar( 'page_before_h2_no_3' ) ) {

			
			$privacy_page = true;
			if ( function_exists( 'get_privacy_policy_url' ) ){
				if( get_privacy_policy_url() === get_the_permalink() ){
					$privacy_page = false;
				}
			}

			if($privacy_page){
				require_once YAHMAN_ADDONS_DIR . 'inc/heading_widget.php';
				$the_content = yahman_addons_heading_widget($the_content,'page');
			}

		}

		if( isset($option['cta_social']['page']) ){

			require_once YAHMAN_ADDONS_DIR . 'inc/get_thumbnail.php';
			require_once YAHMAN_ADDONS_DIR . 'inc/cta_social.php';
			if(!YAHMAN_ADDONS_TEMPLATE){
				$the_content .= yahman_addons_cta_social();

				add_action( 'wp_footer', 'yahman_addons_enqueue_style_cta' );
			}
		}

		if( isset($option['share']['page']) ){

			require_once YAHMAN_ADDONS_DIR . 'inc/social-share.php';
			if(!YAHMAN_ADDONS_TEMPLATE){
				$the_content .= yahman_addons_social_share();

			}
		}

		if( isset($option['related_posts']['page']) ){


			require_once YAHMAN_ADDONS_DIR . 'inc/related_posts.php';

			if(!YAHMAN_ADDONS_TEMPLATE){
				$the_content .= yahman_addons_related_post();
			}
		}

	}



	if( isset($option['javascript']['lightbox']) ){

		if( $option['javascript']['lightbox'] === 'lity'){
			$pattern ='/<a(.*?)href=[\'"](.*?).(png|jpe?g|gif|svg|webp|bmp|ico|tiff?)[\'"](.*?)><img/i';
			$replacement = '<a$1href="$2.$3"$4 data-lity><img';
			$the_content = preg_replace($pattern, $replacement, $the_content);
			add_action( 'wp_footer', 'yahman_addons_lightbox_lity');

		}elseif( $option['javascript']['lightbox'] === 'luminous' ){
			$pattern ='/<a(.*?)href=[\'"].*?.(png|jpe?g|gif|svg|webp|bmp|ico|tiff?)[\'"](.*?)><img/i';
			if(preg_match_all($pattern, $the_content, $match)){
				foreach ($match[0] as $str) {

					$luminous = $str;
					if ( strpos($luminous, 'class="') ){
						$luminous = str_replace('class="','class="luminous ',$luminous);
					}else{
						$luminous = str_replace('a ','a class="luminous" ',$luminous);
					}
					$the_content = preg_replace('{'.$str.'}', $luminous , $the_content, 1);

				}
				add_action( 'wp_footer', 'yahman_addons_lightbox_luminous');
			}
		}
	}



	if( isset($option['javascript']['code']) ){
		if( $option['javascript']['code'] === 'highlight'){

			$highlight = (strpos($the_content,'<pre>') !== false ? true : false);
			if ($highlight == true){
				$highlight_style = isset($option['javascript']['highlight_style']) ? $option['javascript']['highlight_style'] : 'default';

				add_action( 'wp_footer', 'yahman_addons_highlight_load');

			}
		}
	}

	if(isset($option['ga']['enable'])){
		
		if ( !function_exists( 'wp_body_open' ) ){
			
			require_once YAHMAN_ADDONS_DIR . 'inc/ga_gtag.php';
			$ga_code = yahman_addons_ga_gtag_noscript();
			$the_content = preg_replace('/<body(.*?)>/iu', '<body$1>'.$ga_code, $the_content);
		}
	}





	return $the_content;
}

