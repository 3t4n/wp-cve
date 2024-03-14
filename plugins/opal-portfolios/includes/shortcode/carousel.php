<?php
/**
 * 
 * @package  Opal Portfolios
 * @since 1.0.0
 */

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

function portfolio_carousel_shortcode( $atts, $content = null) {
	
	extract(shortcode_atts(array(
		'limit' 				=> '20',
		'category' 				=> '',
		'order'					=> 'DESC',
		'orderby'				=> 'date',
		'loop'					=> '1',
		'nav'					=> '1',
		'autoplay'				=> '0',
		'padding'				=> '80',
		'pagination'			=> '1'


	), $atts));
	

	$posts_per_page 		= !empty($limit) 						? $limit						: '20';
	$cat 					= (!empty($category))					? explode(',',$category) 		: '';
	$order 					= ( strtolower($order) == 'asc' ) 		? 'ASC' 						: 'DESC';
	$orderby 				= !empty($orderby) 						? $orderby 						: 'date';

	// Required enqueue_script
	wp_enqueue_style( 'swiper-css' );
	wp_enqueue_script( 'jquery-swiper' );

	global $post;

	if ( get_query_var( 'paged' ) ) {
    	$paged = get_query_var( 'paged' );
	} elseif ( get_query_var( 'page' ) ) {
	    $paged = get_query_var( 'page' );
	} else {
	    $paged = 1;
	} 

	$args = array ( 
        'post_type'      => PE_POST_TYPE,
        'orderby'        => $orderby, 
        'order'          => $order,
        'posts_per_page' => $posts_per_page,
        'paged'			 => $paged
    );
	     
	if($cat != "") {
		$args['tax_query'] = array(
			array(
				'taxonomy' 	=> PE_CAT,
				'field' 	=> 'slug',
				'terms' 	=> $cat
			)
		);
	}
	
    $query 			= new WP_Query($args);
	$post_count 	= $query->post_count;
	$_id = time()+rand();	
	$terms = get_terms( PE_CAT ,array('orderby' => 'id', 'slug' => $cat,));
 	ob_start();  

 	?>
	<div class="tm-grid-wrapper tm-portfolio  style-carousel-auto-wide" id="tm-portfolio-5bf518c7463f4" data-filter-type="static" data-type="swiper">
	 	<div class="tm-swiper nav-style-05" data-pagination="<?php echo $pagination ?>" data-lg-items="auto" data-centered="1" data-initial-slide="0" data-loop="<?php echo $loop ?>" data-lg-gutter="<?php echo $padding ?>" data-nav="<?php echo $nav ?>" data-autoplay="<?php echo $autoplay ?>" >
	 		<!-- Slider main container -->
			<div class="swiper-container swiper-container-horizontal">
			    <!-- Additional required wrapper -->
			    <div class="tm-grid swiper-wrapper">
			        <!-- Slides -->
			        
			        <?php
					while ($query->have_posts()) : $query->the_post(); ?>

						<div class="portfolio-item  swiper-slide post-283 portfolio">
							<div class="post-wrapper">
								<div class="post-thumbnail">
									<a href="<?php the_permalink(); ?>">
										<img src="<?php the_post_thumbnail_url('portfolio-large'); ?>" alt="<?php the_title(); ?>">
									</a>
								</div>

								<div class="post-info">
									<div class="post-categories">
										<?php 
											$categories = get_the_terms($post->id, PE_CAT, $args);
											$separator = ', ';
											$output = '';
											if ( ! empty( $categories ) ) {
				                                foreach( $categories as $category ) {
				                                    $output .=  esc_html( $category->name ) . $separator;
				                                }
				                                echo trim( $output, $separator );
				                              }
				    
										?>					
									</div>
									
									<h3 class="post-title secondary-font">
										<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
									</h3>

									<div class="post-read-more">
										<a href="<?php the_permalink(); ?>">
											<span class="button-text">Project details</span>
											<span class="button-icon fa fa-arrow-right"></span>
										</a>
									</div>

								</div>
							</div>
					    </div>

					<?php endwhile; ?>
			    </div>
				
			</div>
		</div>
	</div>

	<?php 	
		wp_reset_query();
		$content .= ob_get_clean();
	    return $content;
}
add_shortcode("portfolio_carousel", "portfolio_carousel_shortcode");