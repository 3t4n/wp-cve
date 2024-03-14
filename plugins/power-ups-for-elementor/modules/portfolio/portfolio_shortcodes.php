<?php
/** ELPT
 * Shortcodes 
 *
 *
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

//esc_attr( get_option("elpt_color") ).

/*-----------------------------------------------------------------------------------*/
/*	portfolio Item
/*-----------------------------------------------------------------------------------*/
function elpt_portfolio_shortcode($atts, $content = null) {
	extract(shortcode_atts(array(
		//"id" => '',
		"postsperpage" => '',
		"showfilter" => '',
		"taxonomy" => '',
		"type" => '',
		"style" => '',
		"columns" => '',
		"margin" => '',
		"linkto" => '',
		
	), $atts));
	
	////Isotope
	//wp_enqueue_script( 'imagesloaded', plugin_dir_url( __FILE__ ) . 'js/vendor/imagesloaded.pkgd.min.js', array('jquery'), '20151215', true );
	//wp_enqueue_script( 'isotope', plugin_dir_url( __FILE__ ) . 'js/vendor/isotope/js/isotope.pkgd.min.js', array('jquery'), '20151215', true );
	
	//Image Lightbox
	//wp_enqueue_script( 'simple-lightbox-js', plugin_dir_url( __FILE__ ) .  '/js/vendor/simplelightbox/dist/simple-lightbox.min.js', array('jquery'), '20151218', true );
	//wp_enqueue_style( 'simple-lightbox-css', plugin_dir_url( __FILE__ ) .  '/js/vendor/simplelightbox/dist/simplelightbox.min.css' );
	
	//Custom JS
	//wp_enqueue_script( 'elpt-portfolio-elementor-js', plugin_dir_url( __FILE__ ) . 'js/custom-portfolio-elementor.js', array('jquery'), '20151215', true );

	//Custom CSS
	//wp_enqueue_style( 'elpt-portfolio-css', plugin_dir_url( __FILE__ ) .  '/css/elpt_portfolio_css.css' );
	
	$portfolio_type = $type;

	if ( $portfolio_type == 'yes') {
		$args = array(
			'post_type' => 'elemenfolio',
			'posts_per_page' => $postsperpage,		
			'tax_query' => array(
				array(
					'taxonomy' => 'elemenfoliocategory',
					'field'    => 'id',
					'terms'    => $taxonomy,
				),
			),		
			//'p' => $id
		); 	
	} else { 
		$args = array(
			'post_type' => 'elemenfolio',
			'posts_per_page' => $postsperpage,	
		);			
	}

	$portfolioposts = get_posts($args);
	
	if(count($portfolioposts)){    

		global $post;

			$retour ='';	

			$retour .='<div class="elpt-portfolio">';			

				if ($showfilter != 'no' && $portfolio_type != 'yes') {
					$retour .='<div class="elpt-portfolio-filter">';					

						$retour .='<button class="portfolio-filter-item item-active" data-filter="*" style="background-color:' .';">'.esc_html('All', 'elemenfolio').'</button>';

						$terms = get_terms( array(
						    'taxonomy' => 'elemenfoliocategory',
						    'hide_empty' => false,
						) );

						foreach ( $terms as $term ) :
							$thisterm = $term->name;
							$thistermslug = $term->slug;
							$retour .='<button class="portfolio-filter-item" style="background-color:' .';" data-filter=".elemenfoliocategory-'.esc_attr($thistermslug).'">'.esc_html($thisterm).'</button>';
						endforeach;		 
						
					$retour .='</div>';
				}				

				//Portfolio style
				if ($style == 'masonry' ) {
					$portfoliostyle = 'elpt-portfolio-style-masonry';
				}
				else {
					$portfoliostyle = 'elpt-portfolio-style-box';
				}
				if ($columns == '2') {
					$portfoliocolumns = 'elpt-portfolio-columns-2';
				}
				else if ($columns == '3') {
					$portfoliocolumns = 'elpt-portfolio-columns-3';
				}
				else {
					$portfoliocolumns = 'elpt-portfolio-columns-4';
				}
				if ($margin == 'yes' ) {
					$portfoliomargin = 'elpt-portfolio-margin';
				}
				else {
					$portfoliomargin = '';
				}

				$retour .='<div class="elpt-portfolio-content '.$portfoliostyle.' '.$portfoliocolumns.' '. $portfoliomargin.'">';

					foreach($portfolioposts as $post){

						$postid = $post->ID;

						$portfolio_image= wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), '' );	

						$portfolio_image_ready = $portfolio_image[0];

						//Fancybox or link
						$portfolio_link = get_the_permalink();

						$portfolio_link_class = '';
						$portfolio_link_rel = '';
						if ( $linkto == 'image') {
							$portfolio_link = $portfolio_image_ready;
							$portfolio_link_class = 'elpt-portfolio-lightbox';
							$portfolio_link_rel = 'rel="elpt-portfolio"';

						}
						
						$classes = join( '  ', get_post_class($postid) ); 
						
						$retour .='<div class="portfolio-item-wrapper '.$classes.'">';
							$retour .='<a href="'.esc_url($portfolio_link) .'" class="portfolio-item '.esc_attr($portfolio_link_class) .'" '.esc_attr($portfolio_link_rel) .' style="background-image: url('.esc_url($portfolio_image_ready).')" title="'.get_the_title().'">';
								$retour .='<img src="'.esc_url($portfolio_image_ready) .'" title="'.get_the_title().'" alt="'.get_the_title().'"/>';
								$retour .='<div class="portfolio-item-infos-wrapper" style="background-color:' .';"><div class="portfolio-item-infos">';
									$retour .='<div class="portfolio-item-title">'.get_the_title().'</div>';
									$retour .='<div class="portfolio-item-category">';
										$terms = get_the_terms( $post->ID , 'elemenfoliocategory' );
										if (is_array($terms) || is_object($terms)) {
										   foreach ( $terms as $term ) :
												$thisterm = $term->name;
												$retour .='<span class="elpt-portfolio-cat">' .esc_html($thisterm) .'</span>';
											endforeach;
										}									
									$retour .='</div>';
								$retour .='</div></div>';
							$retour .='</a>';
						$retour .='</div>';

					}

				$retour .='</div>';

			$retour .='</div>';		
		
		return $retour;

		//Reset Query
		wp_reset_postdata();

	}

	
}

add_shortcode("elemenfolio", "elpt_portfolio_shortcode");