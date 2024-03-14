<?php
// Get the query data.
$query = FLBuilderLoop::query( $settings );
// Render the posts.
if ( $query->have_posts() ) :
	?>
    <div class="njba-content-grid-section-wrapper">
        <div class="njba-blog-posts-grid">
            <div class="njba-blog-row">
                <div class="njba-blog-posts-wrapper njba-content-post-grid">
					<?php
					$i = 1;
					while ( $query->have_posts() ) {
					$query->the_post();
					?>
                    <div class="njba-content-post njba-blog-posts-col-<?php echo $settings->show_col; ?> njba-post-wrapper">
                        <div class="njba-content-grid">
							<?php $module->njba_post_image_render(); ?>
                            <div class="njba-content-grid-contant">
                                <div class="njba-content-grid-contant-sub">
                                    <div class="njba-content-grid-vertical-center">
                                        <<?php echo $settings->post_title_tag; ?>><a href="<?php the_permalink(); ?>"
                                                                                     title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a>
                                    </<?php echo $settings->post_title_tag; ?>>
									<?php
									$module->post_meta( get_the_ID() );
									?>
                                </div>
                            </div>
                        </div>
                        <div class="njba-content-grid-contant njba-content-grid-contant_section">
                            <div class="njba-content-grid-contant-sub">
                                <div class="njba-content-grid-vertical-center">
									<?php

									$module->njba_excerpt_text( get_the_ID() );
									$module->njba_button_render();
									?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
				<?php
				$i ++;
				}
				?>
            </div>

        </div>
    </div>
    </div>
<?php endif; ?>
<?php
// Render the pagination.
if ( $settings->pagination !== 'none' && $query->have_posts() ) :
	?>
    <div class="njba-pagination">
		<?php FLBuilderLoop::pagination( $query ); ?>
    </div>
<?php endif; ?>
<?php
// Render the empty message.
if ( ! $query->have_posts() && ( defined( 'DOING_AJAX' ) || isset( $_REQUEST['fl_builder'] ) ) ) :
	?>
    <div class="njba--post-grid-empty">
		<?php
		if ( isset( $settings->no_results_message ) ) :
			echo $settings->no_results_message;
		else :
			_e( 'No posts found.', 'bb-njba' );
		endif;
		?>
    </div>

<?php
endif;
wp_reset_postdata();
?>
<?php

?>
