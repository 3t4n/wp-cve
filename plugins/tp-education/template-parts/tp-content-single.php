<?php
/**
 * Template part for displaying custom post type posts.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package TP Education
 * @since 1.0
 */

function tp_education_single_content(){
?>
	<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
		<div class="blog-post-wrap">
			<?php
			if ( has_post_thumbnail() ) :
				the_post_thumbnail( 'large', array( 'alt' => the_title_attribute( array( 'echo' => false ) ) ) );
			endif; ?>
			<header class="entry-header">
				<?php
				if ( is_single() ) :
					the_title( '<h1 class="entry-title tp-education-header">', '</h1>' );
				else :
					the_title( '<h2 class="entry-title tp-education-header"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' );
				endif;
				?>

				<p class="entry-meta">
					<?php 
					// load post meta
					tp_education_posted_on(); 
					?>
				</p><!-- .entry-meta -->

				<?php
				$post_type = get_query_var( 'post_type' );
				switch ( $post_type ) {
					case 'tp-class': ?>
						<p class="tp-education-meta entry-meta">
							<?php  
							// class age group
							tp_class_age_group();

							// class size
							tp_class_size();

							if ( get_post_meta( get_the_id(), 'tp_class_cost_value', true ) != '' ) : 
                         
                                // class cost
                                tp_class_cost();    

                                // class period
                                tp_class_period();
                            
                            endif; ?>
						</p><!-- .tp-education-meta -->
					<?php break;

					case 'tp-course': ?>
						<p class="tp-education-meta entry-meta">
							<?php
							// course type
							tp_course_type();

							// course starting date
							tp_course_starting_date();

							// course duration
							tp_course_duration();

							// course price
							tp_course_price();

							// course students
							tp_course_students();

							// course language
							tp_course_language();

							// course assessment
							tp_course_assessment();

							// course skills
							tp_course_skills();

							// course professor
							tp_course_professor();

							// course counselors
							tp_course_counselors();
							?>
						</p><!-- .tp-education-meta -->
					<?php break;

					case 'tp-event': ?>
						<p class="tp-education-meta entry-meta">
							<?php  
							// event date
							tp_event_date();

							// event start time
							tp_event_start_time();

							// event end time
							tp_event_end_time();

							// event location
							tp_event_location();
							?>
						</p><!-- .tp-education-meta -->
					<?php break;

					case 'tp-excursion': ?>
						<p class="tp-education-meta entry-meta">
							<?php  
							// excursion start date
							tp_excursion_start_date();

							// excursion end date
							tp_excursion_end_date();

							// event end time
							tp_event_end_time();

							// excursion location
							tp_excursion_location();
							?>
						</p><!-- .tp-education-meta -->
					<?php break;

					case 'tp-team': ?>
						<p class="tp-education-meta entry-meta">
							<?php  
							// team designation
							tp_team_designation();
							 
							// team phone
							tp_team_phone();
							 
							// team skype
							tp_team_skype();
							 
							// team website
							tp_team_website();
							 
							// team courses
							tp_team_courses();

							// team social
							tp_team_social();
							?>
						</p><!-- .tp-education-meta -->
					<?php break;

					case 'tp-affiliation': ?>
						<p class="tp-education-meta entry-meta">
							<?php  
							// team designation
							tp_affiliation_link();
							?>
						</p><!-- .tp-education-meta -->
					<?php break;

					case 'tp-testimonial': ?>
						<p class="tp-education-meta entry-meta">
							<?php  
							// testimonial rating
							tp_testimonial_rating();

							// testimonial designation
							tp_testimonial_designation();

							// testimonial social
							tp_testimonial_social();
							?>
						</p><!-- .tp-education-meta -->
					<?php break;
					
					default:
					break;
				}

				?>

			</header><!-- .entry-header -->

			<div class="entry-content">
				<?php
					the_content( sprintf(
						/* translators: %s: Name of current post. */
						wp_kses( __( 'Continue reading %s <span class="meta-nav">&rarr;</span>', 'tp-education' ), array( 'span' => array( 'class' => array() ) ) ),
						the_title( '<span class="screen-reader-text">"', '"</span>', false )
					) );

					wp_link_pages( array(
						'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'tp-education' ),
						'after'  => '</div>',
					) );
				?>
			</div><!-- .entry-content -->

			<div class="about-author">
				<div class="author-image">
					<?php echo get_avatar( get_the_author_meta( 'ID' ), 200 ); ?>
				</div><!-- .author-image -->
				<div class="author-content">
					<div class="author-name clear">
						<h6><?php the_author_posts_link(); ?></h6>
					</div><!-- .author-name -->
					<?php 
					$author_description = get_the_author_meta( 'description');
					if( ! empty( $author_description ) ) : ?>
		        		<p><?php the_author_meta( 'description'); ?></p>
		        	<?php endif; ?>
				</div><!-- .author-content -->
			</div><!-- .about-author -->

		</div><!-- .blog-post-wrap -->
	</article><!-- #post-## -->
<?php
}
add_action( 'tp_education_single_content_action', 'tp_education_single_content', 10 );