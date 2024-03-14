<?php
/**
 * Template Name: Movie Single Page
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package wpfilm-studio
 */

get_header();

?>

<div class="page-wrapper clear wpfilm-movie-details">
	<div class="container">
		<div class="row">
			<?php
				if ( have_posts() ) : 
                        while( have_posts() ):the_post();
                            $videot = get_post_meta( get_the_ID(),'_wpfilm_movie_duration', true ); 
                    	?>
                    	<div class="col-md-4">
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
	                                <?php if( !empty( $videot ) ){ echo '<span>'. esc_html($videot).'</span>'; }?>
	                            </div>
	                        </div>
                        </div>
                    <?php endwhile; ?>
                    <!-- Pagination -->
					<div class="col-md-12">
						<div class="movie-pagination">
							<?php  wpfilm_movie_pagination();?>
						</div>
					</div>
				<?php endif; ?>
        </div>
	</div>
</div>

<?php

get_footer();