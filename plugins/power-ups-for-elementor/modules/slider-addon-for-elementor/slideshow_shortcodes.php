<?php
/**
 * Shortcodes
 *
 *
 * @package bearr
 */

/*-----------------------------------------------------------------------------------*/
/*	Pando Slider
/*-----------------------------------------------------------------------------------*/
function bearr_slider_scripts() { 
	//Deenqueue files
	wp_dequeue_style( 'owl-theme' );
	wp_dequeue_style( 'owl-carousel' );

	//Enqueue Files
	wp_enqueue_style( 'owl-carousel', plugin_dir_url( __FILE__ ) . 'js/vendor/owl.carousel/assets/owl.carousel.css', array(), '9.9' );
	wp_enqueue_style( 'owl-theme', plugin_dir_url( __FILE__ ) . 'js/vendor/owl.carousel/assets/owl.theme.default.min.css',  array(), '9.9'  );
	wp_enqueue_script( 'owl-carousel', plugin_dir_url( __FILE__ ) . 'js/vendor/owl.carousel/owl.carousel.min.js', array('jquery'), '20151215', true );
	//enqueue css
	wp_enqueue_style( 'bearr-custom-slider-css', plugin_dir_url( __FILE__ ) . 'css/bearr_slideshow.css' );
	if ( file_exists( get_template_directory() . '/framework/js/custom/custom-slider.js' ) ) {
		wp_enqueue_script( 'bearr-custom-slider-js', get_template_directory_uri() . '/framework/js/custom/custom-slider.js', array('jquery'), '20151215', true );
	}
	else {
		wp_enqueue_script( 'bearr-custom-slider-js', plugin_dir_url( __FILE__ ) . 'js/custom-slider.js', array('jquery'), '20151215', true );
	}
	//King Composer
	if ( is_plugin_active( 'kingcomposer/kingcomposer.php' ) ){
	  add_action( 'admin_enqueue_scripts', 'bearr_slideshow_admin_js' );
	}
}

add_action( 'wp_enqueue_scripts', 'bearr_slider_scripts', 999 );

function pando_slider($atts, $content = null) {
	extract(shortcode_atts(array(
		//"ids" => '',
		"heightstyle" => '',
	), $atts));

	//$slider_ids = explode(",", $ids);
	if ( $heightstyle == '1' ) {
		$slider_height = 'sliderviewport';
	} 
	else {
		$slider_height = '';
	}	

	//Output
	wp_reset_query();

	$output = '';
	$output .= '<div class="main-carousel-wrapper '.$slider_height .'"><div class="owl-carousel main-carousel owl-theme">';
	
		//posts
		global $post;

		$args = array(
			'post_type' => 'slider',
			'posts_per_page' => 8,
		);

		$my_query = new WP_Query($args);
		if( $my_query->have_posts() ) :
			while ($my_query->have_posts()) : $my_query->the_post();
			
			
				//Get Meta fields
				$slide_title = wp_kses_post( rwmb_meta( 'bearr_slide_title' ) );	
				$slide_text = wp_kses_post( rwmb_meta( 'bearr_slide_text' ) );
				$slide_link_text = esc_html( rwmb_meta( 'bearr_slide_link_text') );
				$slide_link = esc_url( rwmb_meta( 'bearr_slide_link') );

				$slide_picture_url = '';
				$slide_pictures = rwmb_meta( 'bearr_slide_image', 'size=full_hd' );
				foreach ( $slide_pictures as $slide_picture ) {
				   $slide_picture_url = esc_url( $slide_picture['url'] );
				}


				$slide_video_mp4 = esc_url( rwmb_meta( 'bearr_slide_video_mp4') );
				$slide_video_webm = esc_url( rwmb_meta( 'bearr_slide_video_webm') );
				$slide_video_ogv = esc_url( rwmb_meta( 'bearr_slide_video_ogv') );

				$slide_extra = rwmb_meta( 'bearr_slide_extra');

				$slide_textalign = esc_attr( rwmb_meta( 'bearr_slide_text_align') );

				$slide_overlay = '';

				$slide_overlay = esc_attr( rwmb_meta( 'bearr_slide_overlay') );				

				
				$output .='<div class="featured-slide slide bg-cover ' .$slide_overlay .' viewport" style="background-image: url(' .$slide_picture_url .');">';
					
					//Slide Video
					if ( !empty($slide_video_mp4) || !empty($slide_video_webm) || !empty($slide_video_ogv) ) {
						$output .='<div class="slide-videobg">';
							$output .='<video class="slide-video" preload="preload" autoplay="autoplay" loop="loop">';
								if ( !empty($slide_video_webm) ) {
									//webm
									$output .='<source src="'.$slide_video_webm .'" type="video/webm"></source>';
								}
								if ( !empty($slide_video_mp4) ) {
									//webm
									$output .='<source src="'.$slide_video_mp4 .'" type="video/mp4"></source>';
								}
								if ( !empty($slide_video_ogv) ) {
									//webm
									$output .='<source src="'.$slide_video_ogv .'" type="video/ogv"></source>';
								}		
							$output .='</video>';
						$output .='</div>';
					}

					//Slide Content
					$output .='<div class="container">';
						$output .='<div class="slide-inner" style="text-align: '.$slide_textalign .'">';

							$output .='<div class="slide-icon"></div>';
							if ( !empty($slide_title) ) {
								$output .='<h1 class="slide-title">'.$slide_title .'</h1>' ;
							}
							if ( !empty($slide_text) ) {
								$output .='<p class="slide-text">'.$slide_text.'</p>';
							}
							
							if (!empty($slide_link) && !empty($slide_link_text)) {
								$output .='<a class="primary-btn light-color" href="'.$slide_link .'"><span>' .$slide_link_text .'</span></a>';
							}

							if ( !empty($slide_extra) ) {
								$output .='<div class="slide-extra">'.do_shortcode( $slide_extra ).'</div>';
							}

						$output .='</div>';
					$output .='</div>';
				$output .='</div>';

			endwhile; else:
				$output ='';
				$output .= "nothing found.";
		endif;

		//Reset Query
	    wp_reset_query();

	$output .= '</div></div>';

	return $output;
}

add_shortcode("pando-slider", "pando_slider");