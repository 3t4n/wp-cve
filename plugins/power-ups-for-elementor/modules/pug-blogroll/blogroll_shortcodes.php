<?php
/**
 * Shortcodes
 *
 *
 * @package bearr
 */

/*-----------------------------------------------------------------------------------*/
/*	Blogroll
/*-----------------------------------------------------------------------------------*/
function elpug_custom_excerpt_length( $length ) {
    return 20;
}

function pug_blogroll_shortcode($atts, $content = null) {
	extract(shortcode_atts(array(
		"postsperpage" => '',
		"display" => '',
		"category" => '',
		"posttype" => ''
	), $atts));

	//Owl Carousel
	wp_enqueue_style( 'owl-carousel-css', plugin_dir_url( __FILE__ ) . '../assets/js/owl.carousel/assets/owl.carousel.css' );
	wp_enqueue_style( 'owl-carousel-theme-css', plugin_dir_url( __FILE__ ) . '../assets/js/owl.carousel/assets/owl.theme.default.min.css' );
	wp_enqueue_script( 'owl-carousel-js', plugin_dir_url( __FILE__ ) . '../assets/js/owl.carousel/owl.carousel.min.js', array('jquery'), '20151215', true );
	
	//customs
	wp_enqueue_style( 'elpug-blogroll-css', plugin_dir_url( __FILE__ ) . 'css/elpug_blogroll.css' );
	wp_enqueue_script( 'elpug-blogroll-carousel-js', plugin_dir_url( __FILE__ ) . 'js/custom-blogroll.js', array('jquery'), '20151215', true );


	/*global $post;

	$post_display = $display;
	
	if ( $post_display == 'custom_cat') {
		$args = array(
			'post_type' => 'post',
			'posts_per_page' => $postsperpage,
			"cat" => $category,
			//'p' => $id
		);
	} else if ( $post_display == 'custom_posttype' ) {
		$args = array(
			'post_type' => $posttype,
			'posts_per_page' => $postsperpage
		);	
	} else {
		$args = array(
			'post_type' => 'post',
			'posts_per_page' => $postsperpage
		);	
	}*/

	
	$args = array(
		'post_type' => 'post',
        'category'          => '',
        'posts_per_page'    => 12,
        'paged'             => $paged,
        'offset'            => $new_offset,
    );

	$posts = get_posts($atts);


	if(count($posts)){

	    global $post;

        

			$retour ='';

			$retour .= '<div class="owl-carousel-wrapper elpug-blogroll-carousel-wrapper">';
			$retour .= '<div class="owl-carousel elpug-blogroll-carousel">';

			foreach($posts as $post){
			
				$post_permalink = get_permalink();
				$post_image = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'single-post-thumbnail' );
				$post_title = get_the_title();

				

				add_filter( 'excerpt_length', 'elpug_custom_excerpt_length', 999 );

				$post_excerpt = get_the_excerpt();	
				
				$retour .='<div class="elpug-blog-item">';
					//Featured Image
					
						$retour .='<a href="'.$post_permalink .'">' ;
							$retour .='<figure class="elpug-blog-item-img ' ;

								if ( has_post_thumbnail() ) { 
									$retour .=' elpug-blog-item-img-cover' ;
								}

								$retour .=' "' ;

								if ( has_post_thumbnail() ) { 
									$retour .=' style=" background-image: url('.$post_image[0] .')"' ;
								}
							$retour .='></figure>';
						$retour .='</a>';
					
					//Blog Content
					$retour .='<div class="elpug-blog-content"><article class="elpug-post">';
						$retour .='<h3 class="elpug-heading">'.$post_title .'</h3>' ;
						$retour .='<p>'.$post_excerpt .'</p>' ;
						$retour .='<a href="'.$post_permalink .'" class="elpug-primary-btn"><span>'. __('See More', 'bearr') .'</span></a>' ;
					$retour .='</article></div>';
					
				$retour .='</div>';

			}
	
			return $retour;
		
	}

	//Reset Query
    wp_reset_postdata();
}

add_shortcode("pug-blogroll", "pug_blogroll_shortcode");