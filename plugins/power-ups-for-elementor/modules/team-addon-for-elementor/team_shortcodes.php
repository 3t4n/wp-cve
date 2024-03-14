<?php
/**
 * Shortcodes
 *
 *
 */
/*-----------------------------------------------------------------------------------*/
/*	Testimonial Item Shortcode
/*-----------------------------------------------------------------------------------*/
function elpug_team_member($atts, $content = null) {
	extract(shortcode_atts(array(
		//"ids" => ''
		"name" => '',
		"position" => '',
		"description" => '',
		"links" => '',
		"image" => '',
	), $atts));

	//Enqueue Scripts
	wp_enqueue_style( 'owl-carousel-css', plugin_dir_url( __FILE__ ) . 'js/vendor/owl.carousel/assets/owl.carousel.css' );
	wp_enqueue_style( 'owl-carousel-theme-css', plugin_dir_url( __FILE__ ) . 'js/vendor/owl.carousel/assets/owl.theme.default.min.css' );
	wp_enqueue_script( 'owl-carousel-js', plugin_dir_url( __FILE__ ) . 'js/vendor/owl.carousel/owl.carousel.min.js', array('jquery'), '20151215', true );
	
	//team module
	wp_enqueue_script( 'elpug-custom-team-js', plugin_dir_url( __FILE__ ) . 'js/custom-team.js', array('jquery'), '20151215', true );
	wp_enqueue_style( 'elpug-team-css', plugin_dir_url( __FILE__ ) . 'css/elpug_team.css' );

	$output = '';
	
	$team_member_name = $name;	
	$team_member_position = $position;
	$team_member_description = $description;
	$team_member_photo_url = $image;

	
	$output .='<div class="elpug-team-item-wrapper">';
		$output .='<figure class="elpug-team-item">';
			$output .='<img src="'.esc_url($team_member_photo_url) .'">' ;
			$output .='<figcaption><div class="team-caption"><h4 class="team-item-heading">'.esc_html($team_member_name).'</h4>';
			$output .='<div class="team-item-position">' .esc_html($team_member_position) .'</div>';
			$output .='<p class="team-item-desc">' .esc_html($team_member_description) .'</p>';
			$output .='</div></figcaption>';
		$output .='</figure>';

	$output .='</div>';

	return $output;
}

add_shortcode("pug-team-member", "elpug_team_member");



/*-----------------------------------------------------------------------------------*/
/*	Testimonial Carousel Shortcode
/*-----------------------------------------------------------------------------------*/
function elpug_team_carousel($atts, $content = null) {
	extract(shortcode_atts(array(
		//"ids" => ''
	), $atts));

	//Enqueue Scripts
	wp_enqueue_style( 'owl-carousel-css', plugin_dir_url( __FILE__ ) . 'js/vendor/owl.carousel/assets/owl.carousel.css' );
	wp_enqueue_style( 'owl-carousel-theme-css', plugin_dir_url( __FILE__ ) . 'js/vendor/owl.carousel/assets/owl.theme.default.min.css' );
	wp_enqueue_script( 'owl-carousel-js', plugin_dir_url( __FILE__ ) . 'js/vendor/owl.carousel/owl.carousel.min.js', array('jquery'), '20151215', true );
	
	//team module
	wp_enqueue_script( 'elpug-custom-team-js', plugin_dir_url( __FILE__ ) . 'js/custom-team.js', array('jquery'), '20151215', true );
	wp_enqueue_style( 'elpug-team-css', plugin_dir_url( __FILE__ ) . 'css/elpug_.css' );

	$output = '';
	$output .= '<div class="elementor-team-carousel">';
		$output .= '<div id="team-carousel" class="owl-carousel team-carousel common-carousel owl-theme">';

			global $post;
			
			$args = array(
				'post_type' => 'team',
				'posts_per_page' => 24,
			);

			$my_query = new WP_Query($args);

			if( $my_query->have_posts() ) :
				while ($my_query->have_posts()) : $my_query->the_post();
				
					$team_member_name = rwmb_meta( 'elpug_team_name' );	
					$team_member_position = rwmb_meta( 'elpug_team_position' );
					$team_member_photos = rwmb_meta( 'elpug_team_photo', 'size=elpug_team_photo' );		
					foreach ( $team_member_photos as $team_member_photo ) {
					   $team_member_photo_url = $team_member_photo['url'];
					}
					
					$output .='<figure class="team-item">';
						$output .='<img src="'.$team_member_photo_url .'">' ;
						$output .='<figcaption><div class="team-caption"><h4 class="team-item-heading">'.$team_member_name.'</h4>';
						$output .='<p class="team-item-text">' .$team_member_position .'</p>';
						$output .='</div></figcaption>';
					$output .='</figure>';

				endwhile; else:
				$output ='';
				$output .= "nothing found.";
			endif;

			//Reset Query
		    wp_reset_query();

		$output .= '</div>';
	$output .= '</div>';
	return $output;
}

//add_shortcode("pug-team-carousel", "elpug_team_carousel");
