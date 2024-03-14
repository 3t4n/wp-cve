<?php
defined( 'ABSPATH' ) || exit;


add_action( 'wp_head', 'yahman_addons_wp_head' );
function yahman_addons_wp_head(){

	$option = get_option('yahman_addons');



	
	if( is_active_widget( '', '', 'ya_ad_autorelaxed') || is_active_widget( '', '', 'ya_ad_in_article') || is_active_widget( '', '', 'ya_ad_in_feed') || is_active_widget( '', '', 'ya_ad_link') || is_active_widget( '', '', 'ya_ad_responsive')){

		echo '<link rel="preconnect dns-prefetch" href="//pagead2.googlesyndication.com"><link rel="preconnect dns-prefetch" href="//googleads.g.doubleclick.net"><link rel="preconnect dns-prefetch" href="//tpc.googlesyndication.com"><link rel="preconnect dns-prefetch" href="//ad.doubleclick.net"><link rel="preconnect dns-prefetch" href="//www.gstatic.com"><link rel="preconnect dns-prefetch" href="//www.doubleclickbygoogle.com">' ;

	}


	
	if(!empty( $option['faster']['preconnect_url'] ) ){

		$preconnect_url = explode(',',$option['faster']['preconnect_url']);

		foreach ($preconnect_url as $key => $url) {
			echo '<link rel="preconnect dns-prefetch" href="'.trim( $url ).'">';
		}
	}

	
	if( is_active_widget( '', '', 'ya_cse')){

		echo '<link rel="preconnect dns-prefetch" href="//cse.google.com">' ;

	}

	if(!is_admin() && isset($option['pwa']['enable'])){

		require_once YAHMAN_ADDONS_DIR . 'inc/pwa.php';


		
		if( !empty($option['pwa']['post_in'])){

			$post_in = explode(',', $option['pwa']['post_in']);

			if( in_array ( get_the_ID() , $post_in  ) ) {

				yahman_addons_load_pwa($option);

			}else{

				$option['pwa']['enable'] = null;

			}

		}else{

			$post_not_in = array();

			if( !empty($option['pwa']['post_not_in']))

				$post_not_in = explode(',', $option['pwa']['post_not_in']);

			$pwa_page = true;

			if( isset($option['pwa']['parent_not_in']) ){

				$parent_num = get_the_ID();

				$parents_id = array_reverse ( get_post_ancestors( $parent_num ) );

				if( !empty($parents_id) ){
					
					$parent_num = $parents_id[0];
				}

				$parent_not_in = explode(',', $option['pwa']['parent_not_in']);

				if( in_array ( $parent_num , $parent_not_in  ) ) {
					$pwa_page = false;
				}



			}






			if( !in_array ( get_the_ID() , $post_not_in  ) && $pwa_page ) {

				yahman_addons_load_pwa($option);

			}else{

				$option['pwa']['enable'] = null;

			}

		}








	}




	if(isset($option['javascript']['pel'])){
		echo '<script>document.addEventListener("touchstart",function(){},{passive:true});</script>';
	}

	if(!is_admin() && isset($option['pwa']['enable']) && isset( $option['pwa']['service_worker'] ) ){

		yahman_addons_pwa_sw( $option['pwa']['service_worker'] );

	}



	$meta_description = false;

	if(isset($option['header']['meta_description'])){

		if ( is_singular() ){

			$meta_description = esc_attr( get_post_meta( get_the_ID(), 'ya_description', true ) );

		}else{

			$meta_description = get_the_archive_description();

			if($meta_description === '' )
				$meta_description = get_bloginfo ( 'description' );

		}

		if( $meta_description ){
			echo '<meta name="description" content="'. esc_attr( str_replace( array("\r\n","\n","\r") , '' , wp_strip_all_tags( $meta_description ) ) ) .'">';
		}
	}

	if(isset($option['ogp']['meta'])){
		require_once YAHMAN_ADDONS_DIR . 'inc/ogp.php';
		yahman_addons_meta_ogp( $option['ogp'] , $option['sns_account'] , $meta_description );
	}



	if(isset($option['ga']['verification'])){
		require_once YAHMAN_ADDONS_DIR . 'inc/g_verification.php';
		yahman_addons_meta_g_verification( $option['ga'] );
	}

	if ( get_option('blog_public') != '0' && !empty($option['robot']) ){
		require_once YAHMAN_ADDONS_DIR . 'inc/blog_public.php';
		yahman_addons_blog_public($option['robot']);
	}

	if(isset($option['header']['meta_thum'])){

		if ( is_singular() ){

			require_once YAHMAN_ADDONS_DIR . 'inc/get_thumbnail.php';
			$meta_thum = yahman_addons_get_thumbnail('' , 'full');

		}else{

			$meta_thum[0] = !empty($option['ogp']['image']) ? $option['ogp']['image'] : YAHMAN_ADDONS_URI . 'assets/images/ogp.jpg';

		}

		if(isset($meta_thum[0])){
			echo '<meta name="thumbnail" content="' .esc_url($meta_thum[0]). '" />' . "\n";
		}

	}



}



