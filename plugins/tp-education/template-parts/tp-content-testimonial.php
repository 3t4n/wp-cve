<?php  
/**
 * Archive Testimonial Content.
 *
 * @package TP Education
 * @since 1.0
 */

add_action( 'tp_education_archive_testimonial_content_action', 'tp_education_archive_testimonial_content', 10 );
function tp_education_archive_testimonial_content() { 
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
				</div><!-- .course-header-contents -->
				<div class="course-footer-contents">
					<p><?php echo esc_html( wp_trim_words( get_the_excerpt(), 25 ) ); ?></p>
				</div><!-- .course-footer-contents -->
			</div><!-- .course-contents -->
		</div><!-- .course-item -->
	</li><!-- .column-wrapper -->
<?php
}
