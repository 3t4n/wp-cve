<?php
defined( 'ABSPATH' ) || exit;
/**
 * Related Posts
 *
 * @package YAHMAN Add-ons
 */

function yahman_addons_related_post($post_type = 'post'){

	if(is_front_page() || is_attachment())return;

	
	$defaults = array(
		'post'    => false,
		'post_num'    => 9,
		'post_title'    => __( 'Related Posts', 'yahman-add-ons' ),
		'post_style'    => '3',
		'page'    => false,
		'page_num'    => 9,
		'page_title'    => __( 'Related Pages', 'yahman-add-ons' ),
		'page_style'    => '3',
	);

	$option = get_option('yahman_addons');

	$related_posts = wp_parse_args( $option['related_posts'], $defaults );






	if($post_type === 'post'){
		$related_title = apply_filters('yahman_addons_related_posts_post_title', $related_posts['post_title'] );
	}else{
		$related_title = apply_filters('yahman_addons_related_posts_page_title', $related_posts['page_title'] );
	}
	$max_post_num = $related_posts[$post_type.'_num'];
	$display_style = $related_posts[$post_type.'_style'];
	//$max_post_num = get_theme_mod( 'simple_days_posts_related_post_number',9);
	//if( $max_post_num < 0 ) return;
	//$post_type = 'post';
	//$related_title = __( 'Related Posts', 'yahman-add-ons' );
	//$display_style = get_theme_mod( 'simple_days_posts_related_post_style','over_thum');




	if(!YAHMAN_ADDONS_TEMPLATE){
		add_action( 'wp_footer', 'yahman_addons_enqueue_style_post_list' );
	}

	$rel_count = 0;
	$rel_posts = array();
	$cat_posts = array();
	$tag_ids = array();
	$get_page_id = get_the_ID();

	if ( has_tag() ) {
		
		$tags = wp_get_post_tags($get_page_id);
		foreach($tags as $tag):
			array_push( $tag_ids, $tag->term_id);
		endforeach ;
		$tag_args = array(
			'fields'         => 'ids',
			'post_type' => $post_type,
			'post__not_in' => array($get_page_id),
			'posts_per_page'=> $max_post_num,
			'tag__in' => $tag_ids,
			//'orderby' => 'rand',
		);
		$rel_posts = get_posts($tag_args);



		$rel_count = count($rel_posts);
	}



	if(!has_tag() || $max_post_num > $rel_count){
		
		


		$categories = get_the_category($get_page_id);
		$category_ID = array();
		foreach($categories as $category):
			array_push( $category_ID, $category ->cat_ID);
		endforeach ;
		


		$cat_args = array(
			'fields'         => 'ids',
			'post_type' => $post_type,
			'post__not_in' => array($get_page_id),
			'tag__not_in' => $tag_ids,
			'posts_per_page'=> ($max_post_num - $rel_count),
			'category__in' => $category_ID,
			//'orderby' => 'rand',
		);

		$cat_posts = get_posts($cat_args);


		$rel_posts = array_merge($rel_posts, $cat_posts);
		//shuffle($rel_posts);
	}


	$posts = new WP_Query( array(
		'post_type' => $post_type,
		'ignore_sticky_posts'   => 'true',
		'post__in'  => $rel_posts,
		//'post__not_in' => get_option( 'sticky_posts' ),
		'orderby'   => 'rand',
		'posts_per_page'=> $max_post_num,
		//'order'     => 'DESC'
	) );


	
	
	if(  count( $rel_posts ) > 0 ):

		require_once YAHMAN_ADDONS_DIR . 'inc/classes/post_list.php';

		ob_start();

		?>
		<aside id="rp_wrap" class="post_item mb_L">
			<div class="item_title fw8 mb_S"><?php echo esc_html( $related_title ); ?></div>

					<?php
			
				

					$settings = array(
						'popular_post_title' => '',
						'post_not_in' => '',
						'time_period'   => 'all',
						'category_not_in'   => '',
						'number_post'   => $max_post_num,
						'archive_rank'   => '',
						'display_style' => $display_style,
						'pv' => false,
						'include_page' => false,
						'cache' => false,
						'ranking' => false,
						'update' => true,
						'ul_class'   => ' f_box f_wrap jc_sb rp_box_tt',
						'li_class' => ' f_123',
					);

					if($display_style === '1' || $display_style === '2'){
						$settings['ul_class'] = '';
						$settings['li_class'] = '';
					}else if($display_style === '3' || $display_style === '4'){
						$settings['ul_class'] = esc_attr( $settings['ul_class'] . ' o_s_t sstx' );
						$settings['li_class'] = esc_attr( $settings['li_class'] . ' ssac' );
					}

				//require_once YAHMAN_ADDONS_DIR . 'inc/classes/post_list.php';
				//YAHMAN_ADDONS_POST_LIST::yahman_addons_post_list_output($posts,$display_style,false,false,$max_post_num);
					$back_data =  YAHMAN_ADDONS_POST_LIST::yahman_addons_post_list_output($posts,$settings);
					echo $back_data[0];


					?>

				</aside>
				<?php
				return ob_get_clean();
			endif;
		}

