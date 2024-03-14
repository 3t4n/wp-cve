<?php
/**
 * Template Name: Movie Single Page
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package wpfilm-studio
 */

get_header();?>
<div class="page-wrapper clear">

	<?php

		while ( have_posts() ) : the_post();

			$postid = get_the_ID();
			$movie_detailtrailer  = get_post_meta( get_the_ID(),'_wpfilm_select_trailer', true );
			$movie_movie_banner_img  = get_post_meta( get_the_ID(),'_wpfilm_movie_banner_img',true );
			$movie_publish_date  = get_post_meta( get_the_ID(),'_wpfilm_publish_date', true );
			$movie_duration  = get_post_meta( get_the_ID(),'_wpfilm_movie_duration', true );
			$relatedtitle = wpfilm_get_option( 'wpfilm_posts_related_title', 'settings' );
			$wpfilm_relate_movie_show = wpfilm_get_option( 'wpfilm_relate_movie_show', 'settings' );
			$wpfilm_posts_movie_name_title = wpfilm_get_option( 'wpfilm_posts_movie_name_title', 'settings' );
			$wpfilm_posts_trailer_name_title = wpfilm_get_option( 'wpfilm_posts_trailer_name_title', 'settings' );


if( \Elementor\Plugin::$instance->db->is_built_with_elementor( $postid ) ){
	  the_content();


}else{


	?>
		<!-- Movie Details Area Start -->
		<div class="movie-details-area">
			<div class="container">
				<div class="row">
					<?php if( !empty($movie_movie_banner_img) ){ ?>
					<div class="col-md-12">
						<div class="movie-details-content">
							<div class="movie-details-image">
								<img src="<?php echo esc_attr( $movie_movie_banner_img ); ?>" alt="<?php echo esc_attr( the_title() ); ?>">
							</div>   
						</div>
					</div>
					<?php } ?>
					<div class="col-lg-12">
						<div class="movie-details-meta">
							<h3><?php echo esc_html($wpfilm_posts_movie_name_title).' '; the_title(); ?></h3>
							<ul>
								<li><?php echo esc_html($movie_publish_date); echo esc_html__(' -','wpfilm-studio'); ?></li>
								<li> 
									<?php
									$taxonomy = 'wpfilm_movie_category';
									$terms = get_terms($taxonomy); // Get all terms of a taxonomy

									if ( $terms && !is_wp_error( $terms ) ) :
									        foreach ( $terms as $term ) { ?>
									             <?php echo $term->name; ?>
									        <?php } ?>
									<?php endif; ?>
								</li>
								<li><?php echo esc_html__(' - ','wpfilm-studio'); echo esc_html($movie_duration)?></li>
							</ul>
						</div>
					</div>
					<div class="col-lg-7">
		                <div class="movie-details-content">
		                    <?php the_content(); ?>
		                </div>
		            </div>
					<div class="col-lg-5">
						<?php
	                        $argst = array(
	                            'post_type'  => 'wpfilm_trailer',
	                            'post__in' => array($movie_detailtrailer),
	                        );
	                        $postst = new WP_Query($argst);
	                        while( $postst->have_posts() ):$postst->the_post();
	                        	$movie_detailtrailer_url  = get_post_meta( get_the_ID(),'_wpfilm_trailer_video', true );
								?>
								<div class="movie-popup-video trailer-single">
		                            <div class="trailer-img">
		                                <?php the_post_thumbnail('wpfilm_img550x348'); ?>
		                                <a class="popup-youtube" href="<?php echo esc_url( $movie_detailtrailer_url ); ?>">
		                                    <i class="icofont icofont-play-alt-2"></i>
		                                </a>
		                            </div>
								</div>
								<h5 class="trailer-title"><?php echo esc_html($wpfilm_posts_trailer_name_title). ' '; the_title();?></h5>
						<?php
	                        wp_reset_postdata();
	                        endwhile;
						?>						
		            </div>
		            <div class="col-md-12">
		            	<div class="movie-details-info">
		                    <ul>
                                <?php
                               $movie_detailsas  = get_post_meta( get_the_ID(),'_wpfilm_moviedetails', true );
                               if($movie_detailsas){
                                foreach( (array) $movie_detailsas as $movie_detaisitem => $movie_item ){
                                    $movie_item1 = $movie_item2 ='';
                                    if ( isset( $movie_item['_wpfilm_movie_d_title'] ) ) {
                                        $movie_item1 =  $movie_item['_wpfilm_movie_d_title']; 
                                    }
                                    if ( isset( $movie_item['_wpfilm_movie_d_content'] ) ) {
                                        $movie_item2 =  $movie_item['_wpfilm_movie_d_content'];    
                                    }?>
                                    <li><span><?php echo esc_html($movie_item1);?> </span> <?php echo esc_html($movie_item2);?></li>

                                  <?php }  }?>
		                    </ul>
		                </div>
		            </div>
				</div>
			</div>
		</div>


	<?php } ?>
		<!-- Movie Details Area End -->
		<!-- Related Movie Area Start -->
		<?php
          
		$related = array(
		    'post_type'  => 'wpfilm_movie',
		    'post__not_in' =>array(get_the_ID()),
		);
		$relatedd = new WP_Query($related);

		if($wpfilm_relate_movie_show =='yes' && !empty( $relatedd )){
		?>
		<div class="related-area-movie">
			<div class="container">
				<?php if(!empty($relatedtitle)){?>
					<div class="related-title">
						<h3><?php echo esc_html($relatedtitle);?> </h3>
					</div>
				<?php } ?>
                <div class="related-trailer-active indicator-style-two">
					<?php
                        while($relatedd->have_posts()): $relatedd->the_post();
                            $videot = get_post_meta( get_the_ID(),'_wpfilm_movie_duration', true ); 
                    	?>
                        <div class="trailer-single">
                            <div class="trailer-img">
                                <?php the_post_thumbnail('wpfilm_img550x348'); ?>
                                <a class="popup-movie-link" href=" <?php the_permalink();?>">
                                    <i class="icofont icofont-link"></i>
                                </a>
                            </div>
                            <div class="trailer-titel">
                                <h5>
                                    <a href="<?php the_permalink(); ?>"><?php the_title();?></a>
                                </h5>
                                <?php if( !empty( $videot ) ){ echo '<span>'. esc_html($videot) .'</span>'; }?>
                            </div>
                        </div>
                        <!-- Trailer Single -->
                    <?php endwhile; ?>
                </div>
            </div>
		</div>
		<!-- Related Movie AreaArea End -->
	<?php
}
		endwhile; // End of the loop.
	?>
</div><!-- #primary -->
<?php
get_footer();