<?php
defined( 'ABSPATH' ) || exit;

function yahman_addons_add_widget($option){


	$widget_area['post_type'] = array(
		'post' => esc_html_x('the post', 'widget' ,'yahman-add-ons' ),
		'page' => esc_html_x('the page', 'widget' ,'yahman-add-ons' ),
	);
	$widget_area['position_num'] = array(
		esc_html_x('the first', 'widget' ,'yahman-add-ons' ),
		esc_html_x('the second', 'widget' ,'yahman-add-ons' ),
		esc_html_x('the third', 'widget' ,'yahman-add-ons' ),
	);

	foreach ($widget_area['post_type'] as $post_type_key => $post_type_val) {
		$i = 1;
		foreach ($widget_area['position_num'] as $position_num_key => $position_num_val) {
			$widget_area['judge'] = isset($option['widget_area'][$post_type_key]['before_h2'][$i]) ? true: false;
			if($widget_area['judge']){
				register_sidebar(array(
					
					
					'name' => sprintf( esc_html__('Before %1$s H2 of %2$s', 'yahman-add-ons' ), $position_num_val, $post_type_val ),
					'id' => $post_type_key.'_before_h2_no_'.$i,
					
					
					'description' => sprintf( esc_html__('Add widgets before %1$s H2 in the contents of %2$s', 'yahman-add-ons' ), $position_num_val , $post_type_val ),
					'before_widget' => '<div id="%1$s" class="widget %2$s">',
					'after_widget' => '</div>',
					'before_title' => '<div class="widget_title mb_S fsS">',
					'after_title' => '</div>'
				));
			}
			++$i;
		}
	}


	if(isset($option['widget']['toc'])){

		require_once YAHMAN_ADDONS_DIR . 'inc/widget/widget-toc.php';
		register_widget( 'yahman_addons_toc_widget' );

	}

	if(isset($option['pv']['enable'])){
		if(isset($option['widget']['pp'])){

			require_once YAHMAN_ADDONS_DIR . 'inc/widget/widget-postlist-popular_posts.php';

			register_widget( 'yahman_addons_popular_post_widget' );

		}

		if(isset($option['widget']['pv'])){

			require_once YAHMAN_ADDONS_DIR . 'inc/widget/widget-page_view.php';
			register_widget( 'yahman_addons_page_view_widget' );

		}
	}

	if(isset($option['widget']['google_ad_responsive'])){

		require_once YAHMAN_ADDONS_DIR . 'inc/widget/widget-google_ad-responsive.php';
		register_widget( 'yahman_addons_google_ad_responsive_widget' );

	}

	if(isset($option['widget']['google_ad_infeed'])){

		require_once YAHMAN_ADDONS_DIR . 'inc/widget/widget-google_ad-infeed.php';
		register_widget( 'yahman_addons_google_ad_in_feed_widget' );

	}

	if(isset($option['widget']['google_ad_inarticle'])){
		require_once YAHMAN_ADDONS_DIR . 'inc/widget/widget-google_ad-inarticle.php';
		register_widget( 'yahman_addons_google_ad_in_article_widget' );
	}

	if(isset($option['widget']['google_ad_link'])){
		require_once YAHMAN_ADDONS_DIR . 'inc/widget/widget-google_ad-link.php';
		register_widget( 'yahman_addons_google_ad_link_widget' );
	}

	if(isset($option['widget']['google_ad_autorelaxed'])){
		require_once YAHMAN_ADDONS_DIR . 'inc/widget/widget-google_ad-autorelaxed.php';
		register_widget( 'yahman_addons_google_ad_matched_content_widget' );
	}

	if(isset($option['widget']['profile'])){

		require_once YAHMAN_ADDONS_DIR . 'inc/widget/widget-profile.php';
		register_widget( 'yahman_addons_profile_widget' );
		if(!YAHMAN_ADDONS_TEMPLATE){
			add_action( 'wp_footer', 'yahman_addons_enqueue_style_profile' );
		}

	}


	if(isset($option['widget']['sns_link'])){

		require_once YAHMAN_ADDONS_DIR . 'inc/widget/widget-social-links.php';
		register_widget( 'yahman_addons_social_links_widget' );

	}

	if(isset($option['widget']['another'])){

		require_once YAHMAN_ADDONS_DIR . 'inc/widget/widget-another-profile.php';
		register_widget( 'yahman_addons_another_profile_widget' );
		if(!YAHMAN_ADDONS_TEMPLATE){
			add_action( 'wp_footer', 'yahman_addons_enqueue_style_profile' );
		}
	}

	if(isset($option['widget']['recent'])){

		require_once YAHMAN_ADDONS_DIR . 'inc/widget/widget-postlist-recent_thum.php';
		register_widget( 'yahman_addons_recent_posts_with_thumbnail_widget' );

	}

	if(isset($option['widget']['update'])){

		require_once YAHMAN_ADDONS_DIR . 'inc/widget/widget-postlist-update.php';
		register_widget( 'yahman_addons_update_posts_with_thumbnail_widget' );

	}

	if(isset($option['widget']['recommend'])){

		require_once YAHMAN_ADDONS_DIR . 'inc/widget/widget-postlist-recommend.php';
		register_widget( 'yahman_addons_recommend_posts_widget' );

	}

	if(isset($option['widget']['dda'])){

		require_once YAHMAN_ADDONS_DIR . 'inc/widget/widget-dd-archives.php';
		register_widget( 'yahman_addons_dd_archives_widget' );
		if(!YAHMAN_ADDONS_TEMPLATE){
			add_action( 'wp_footer', 'yahman_addons_enqueue_style_dd' );
		}
	}

	if(isset($option['widget']['ddc'])){

		require_once YAHMAN_ADDONS_DIR . 'inc/widget/widget-dd-categories.php';
		register_widget( 'yahman_addons_dd_categories_widget' );
		if(!YAHMAN_ADDONS_TEMPLATE){
			add_action( 'wp_footer', 'yahman_addons_enqueue_style_dd' );
		}
	}

	if(isset($option['widget']['twitter'])){

		require_once YAHMAN_ADDONS_DIR . 'inc/widget/widget-twitter.php';
		register_widget( 'yahman_addons_twitter_widget' );

	}

	if(isset($option['widget']['facebook'])){

		require_once YAHMAN_ADDONS_DIR . 'inc/widget/widget-facebook.php';
		register_widget( 'yahman_addons_facebook_widget' );

	}

	if(isset($option['widget']['cse'])){

		require_once YAHMAN_ADDONS_DIR . 'inc/widget/widget-google_cse.php';
		register_widget( 'yahman_addons_google_cse_widget' );
		if(!YAHMAN_ADDONS_TEMPLATE){
			add_action( 'wp_footer', 'yahman_addons_enqueue_style_cse' );
		}
	}

	if(isset($option['widget']['art_2col'])){

		require_once YAHMAN_ADDONS_DIR . 'inc/widget/widget-art_2col.php';
		register_widget( 'yahman_addons_art_two_col_widget' );
		
			
		
	}

	if(isset($option['widget']['2lists_2col'])){

		require_once YAHMAN_ADDONS_DIR . 'inc/widget/widget-2lists_2col.php';
		register_widget( 'yahman_addons_two_lists_two_col_widget' );
		
			
		
	}

	if(isset($option['widget']['alu'])){

		require_once YAHMAN_ADDONS_DIR . 'inc/widget/widget-articles_line_up.php';
		register_widget( 'yahman_addons_articles_line_up_widget' );
		
			
		
	}

	if(isset($option['widget']['carousel_slider'])){

		require_once YAHMAN_ADDONS_DIR . 'inc/widget/widget-carousel_slider.php';
		register_widget( 'yahman_addons_carousel_slider_widget' );
		
		
		
	}
	

	
	

	

}

