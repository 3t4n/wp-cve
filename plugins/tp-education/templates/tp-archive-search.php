<?php
/**
 * The template for displaying archive pages.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package TP Education
 * @since 1.0
 */

get_header(); ?>
<div id="content" class="site-content background-image-properties">
	<div id="primary" class="content-area os-animation" data-os-animation="fadeIn">
		<main id="main" class="site-main" role="main">
			
			<section id="search-course-tab" class="page-section no-padding-bottom os-animation" data-os-animation="fadeIn">
				<div class="container">
					<div class="entry-content">

						<ul class="tabs">
							<?php if ( post_type_exists( 'tp-event' ) ) : ?>
								<li class="tab-link <?php echo ( get_query_var( 'post_type' ) == 'tp-event' ) ? 'active' : ''; ?>" data-tab="tab-1"><a href="#."><i class="fa fa-calendar"></i><?php _e( 'Events', 'tp-education' ); ?></a></li>
							<?php endif;
        					if ( post_type_exists( 'tp-excursion' ) ) : ?>
								<li class="tab-link <?php echo ( get_query_var( 'post_type' ) == 'tp-excursion' ) ? 'active' : ''; ?>" data-tab="tab-2"><a href="#."><i class="fa fa-briefcase"></i><?php _e( 'Excursions', 'tp-education' ); ?></a></li>
							<?php endif;
        					if ( post_type_exists( 'tp-course' ) ) : ?>
								<li class="tab-link <?php echo ( get_query_var( 'post_type' ) == 'tp-course' ) ? 'active' : ''; ?>" data-tab="tab-3"><a href="#."><i class="fa fa-book"></i><?php _e( 'Courses', 'tp-education' ); ?></a></li>
							<?php endif;
        					if ( post_type_exists( 'tp-team' ) ) : ?>
								<li class="tab-link <?php echo ( get_query_var( 'post_type' ) == 'tp-team' ) ? 'active' : ''; ?>" data-tab="tab-4"><a href="#."><i class="fa fa-users"></i><?php _e( 'Team', 'tp-education' ); ?></a></li>
							<?php endif;
        					if ( post_type_exists( 'tp-class' ) ) : ?>
								<li class="tab-link <?php echo ( get_query_var( 'post_type' ) == 'tp-class' ) ? 'active' : ''; ?>" data-tab="tab-5"><a href="#."><i class="fa fa-bell"></i><?php _e( 'Classes', 'tp-education' ); ?></a></li>
							<?php endif;
        					if ( post_type_exists( 'tp-affiliation' ) ) : ?>
								<li class="tab-link <?php echo ( get_query_var( 'post_type' ) == 'tp-affiliation' ) ? 'active' : ''; ?>" data-tab="tab-6"><a href="#."><i class="fa fa-certificate"></i><?php _e( 'Affiliation', 'tp-education' ); ?></a></li>
							<?php endif; ?>
			            </ul><!-- .tabs -->

			            <?php if ( post_type_exists( 'tp-event' ) ) : ?>
				            <div id="tab-1" class="tab-content os-animation <?php echo ( get_query_var( 'post_type' ) == 'tp-event' ) ? 'active' : ''; ?>">
								<?php  
								/**
								 * Hook - tp_education_search_event_form_action.
								 *
								 * @hooked tp_education_search_event_form -  10
								 */
								do_action( 'tp_education_search_event_form_action' );
								?>
				            </div><!-- #tab-1 -->
			            <?php endif;
    					if ( post_type_exists( 'tp-excursion' ) ) : ?>
				            <div id="tab-2" class="tab-content os-animation <?php echo ( get_query_var( 'post_type' ) == 'tp-excursion' ) ? 'active' : ''; ?>">
				              	<?php  
								/**
								 * Hook - tp_education_search_excursion_form_action.
								 *
								 * @hooked tp_education_search_excursion_form -  10
								 */
								do_action( 'tp_education_search_excursion_form_action' );
								?>
				            </div><!-- #tab-2 -->
			            <?php endif;
    					if ( post_type_exists( 'tp-course' ) ) : ?>
				            <div id="tab-3" class="tab-content os-animation <?php echo ( get_query_var( 'post_type' ) == 'tp-course' ) ? 'active' : ''; ?>">
				              	<?php  
								/**
								 * Hook - tp_education_search_course_form_action.
								 *
								 * @hooked tp_education_search_course_form -  10
								 */
								do_action( 'tp_education_search_course_form_action' );
								?>
				            </div><!-- #tab-3 -->
			            <?php endif;
    					if ( post_type_exists( 'tp-team' ) ) : ?>
				            <div id="tab-4" class="tab-content os-animation <?php echo ( get_query_var( 'post_type' ) == 'tp-team' ) ? 'active' : ''; ?>">
				              	<?php  
								/**
								 * Hook - tp_education_search_team_form_action.
								 *
								 * @hooked tp_education_search_team_form -  10
								 */
								do_action( 'tp_education_search_team_form_action' );
								?>
				            </div><!-- #tab-4 -->
			            <?php endif;
    					if ( post_type_exists( 'tp-class' ) ) : ?>
				            <div id="tab-5" class="tab-content os-animation <?php echo ( get_query_var( 'post_type' ) == 'tp-class' ) ? 'active' : ''; ?>">
				              	<?php  
								/**
								 * Hook - tp_education_search_class_form_action.
								 *
								 * @hooked tp_education_search_class_form -  10
								 */
								do_action( 'tp_education_search_class_form_action' );
								?>
				            </div><!-- #tab-5 -->
			            <?php endif;
    					if ( post_type_exists( 'tp-affiliation' ) ) : ?>
				            <div id="tab-6" class="tab-content os-animation <?php echo ( get_query_var( 'post_type' ) == 'tp-affiliation' ) ? 'active' : ''; ?>">
				              	<?php  
								/**
								 * Hook - tp_education_search_affiliation_form_action.
								 *
								 * @hooked tp_education_search_affiliation_form -  10
								 */
								do_action( 'tp_education_search_affiliation_form_action' );
								?>
				            </div><!-- #tab-6 -->
						<?php endif; ?>
						
						<?php if ( have_posts() ) : ?>

							<ul id="two-column" class="course-lists three-columns os-animation">
								<?php  
								/* Start the Loop */
								$i = 1;
								while ( have_posts() ) : the_post();
									/**
									 * Hook - tp_education_search_content_action.
									 *
									 * @hooked tp_education_search_content -  10
									 */
									do_action( 'tp_education_search_content_action' );
									if ( $i % 3 == 0 ) echo '<div class="clear"></div>';
									$i++;
								endwhile; ?>
							</ul><!-- .course-lists -->
							<?php
							if ( has_action( 'tp_education_pagination_action' ) ) :
								/**
								 * Hook - tp_education_pagination_action.
								 */
								do_action( 'tp_education_pagination_action' );
							else :
								the_posts_navigation();
							endif;
						else :

							get_template_part( 'template-parts/content', 'none' );

						endif; 
						?>
					</div><!-- .entry-content -->

				</div><!-- .container -->
			</section><!-- .search-course-tab -->

		</main><!-- #main -->
	</div><!-- #primary -->
	<?php if ( apply_filters( 'tp_education_is_sidebar_enable_filter', true ) ) :
		get_sidebar();
	endif; ?>
</div><!--end .site-content-->
<?php
get_footer();
