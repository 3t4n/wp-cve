<?php  
/**
 * Archive Class Content.
 *
 * @package TP Education
 * @since 1.0
 */

add_action( 'tp_education_archive_class_content_action', 'tp_education_archive_class_content', 10 );
function tp_education_archive_class_content() { 
?>
	<li class="column-wrapper">
		<div class="course-item">
			<div class="image-wrapper">
				<a href="<?php the_permalink(); ?>">
					<?php  
					if ( has_post_thumbnail() ) { 
						the_post_thumbnail( 'post-thumbnail', array( 'alt' => the_title_attribute( array( 'echo' => false ) ) ) );
					} else {
						echo '<img src="' . esc_url( TP_EDUCATION_URL_PATH ) . '/assets/images/demo-300x200.jpg" alt="' . the_title_attribute( array( 'echo' => false ) ) . '">';
					}
					?>
					<div class="white-overlay"></div>
				</a>
				<a href="<?php the_permalink(); ?>" class="btn btn-blue"><?php _e( 'Learn More', 'tp-education' ); ?></a>
			</div><!-- .image-wrapper -->
			<div class="course-contents">
				<div class="course-header-contents">
					<h5 class="title">
						<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
					</h5> 
					<div class="categories">
						<b><?php esc_html_e( 'Posted In: ', 'tp-education' ); ?></b>
						<?php 
						// get all terms
						tp_education_get_terms( 'tp-class-category' );
						?>
					</div><!-- .categories -->
					<div class="tags">
						<b><?php esc_html_e( 'Tags: ', 'tp-education' ); ?></b>
						<?php 
						// get all terms
						tp_education_get_terms( 'tp-class-tag' );
						?>
					</div><!-- .tags -->
				</div><!-- .course-header-contents -->
				<div class="course-footer-contents">
					<ul>
						<li>
							<?php  
							// class age group
							tp_class_age_group();
							?>
						</li>
						<li>
							<?php  
							// class size
							tp_class_size();
							?>
						</li>
					</ul>
					<?php if ( get_post_meta( get_the_id(), 'tp_class_cost_value', true ) != '' ) : ?>
						<div class="price">
							<?php  
							// class cost
							tp_class_cost();	

							// class period
							tp_class_period();
							?>
						</div><!-- .price -->
					<?php endif; ?>
				</div><!-- .course-footer-contents -->
			</div><!-- .course-contents -->
		</div><!-- .course-item -->
	</li><!-- .column-wrapper -->
<?php
}
