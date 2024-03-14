<?php
/**
 * TP Education Shortcode
 *
 * @package TP Education
 * @since 1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class TP_Education_Shortcode {

	/*
	 * SHORTCODES
	 *
	 * Defaults Atts :-
	 * category = '',
	 * no_of_posts = 2,
	 * post_ids = '', ( should be seperated by ','. ie: 15, 27 )
	 * column = 2 // max num value 4
	 *
	 * Class shortcode:
	 * [TP_EDUCATION_CLASS] || [TP_EDUCATION_CLASS category="cat-slug" no_of_posts="2" post_ids="217, 115" column="2"]
	 *
	 * Event shortcode:
	 * [TP_EDUCATION_EVENT] || [TP_EDUCATION_EVENT category="cat-slug" no_of_posts="2" post_ids="217, 115" column="2"]
	 *
	 * Course shortcode:
	 * [TP_EDUCATION_COURSE] || [TP_EDUCATION_COURSE category="cat-slug" no_of_posts="2" post_ids="217, 115" column="2"]
	 *
	 * Team shortcode:
	 * [TP_EDUCATION_TEAM] || [TP_EDUCATION_TEAM category="cat-slug" no_of_posts="2" post_ids="217, 115" column="2"]
	 *
	 * Excursion shortcode:
	 * [TP_EDUCATION_EXCURSION] || [TP_EDUCATION_EXCURSION category="cat-slug" no_of_posts="2" post_ids="217, 115" column="2"]
	 *
	 * Testimonial shortcode:
	 * [TP_EDUCATION_TESTIMONIAL] || [TP_EDUCATION_TESTIMONIAL no_of_posts="2" post_ids="217, 115" column="2"]
	 * 
	 * Search Tabs shortcode:
	 * [TP_EDUCATION_SEARCH_TAB]  
	 */
	

	public function __construct() 
	{
		$this->tp_education_create_shortcode();
	}

	public function tp_education_column_class( $int ) 
	{
		$count = array( 1, 2, 3, 4 );
		$class = array( 'one-column', 'two-columns', 'three-columns', 'four-columns' );
		$output = str_replace( $count, $class, $int );
		return $output;
	}

	public function tp_education_class_shortcode_function( $atts ) 
	{
		/*
		 * Class Shortcode Function
		 */
		
		ob_start();
			$input = shortcode_atts( array(
			    'category' 		=> '',
			    'no_of_posts' 	=> 2,
			    'post_ids'		=> '',
			    'column'		=> 2
			), $atts );
			$args = array(
				'post_type'			=> 'tp-class',
				'tp-class-category'	=> $input['category'],
				'posts_per_page'	=> $input['no_of_posts'],
				'post__in'			=> ! empty( $input['post_ids'] ) ? explode( ',', $input['post_ids'] ) : ''
				);
			$the_query = new WP_Query( $args );
			
			if ( $the_query -> have_posts() ) : ?>
				<ul class="course-lists <?php echo esc_attr( $this->tp_education_column_class( $input['column'] ) ); ?> os-animation clear">
					<?php  
					/* Start the Loop */
					while ( $the_query -> have_posts() ) : $the_query -> the_post();
						/**
						 * Hook - tp_education_archive_class_content_action.
						 *
						 * @hooked tp_education_archive_class_content -  10
						 */
						do_action( 'tp_education_archive_class_content_action' );
					endwhile; ?>
				</ul><!-- .course-lists -->
			<?php 
			endif;
			wp_reset_postdata();
		return ob_get_clean();
	}

	public function tp_education_event_shortcode_function( $atts ) 
	{
		/*
		 * Event Shortcode Function
		 */

		ob_start();
			$input = shortcode_atts( array(
			    'category' 		=> '',
			    'no_of_posts' 	=> 2,
			    'post_ids'		=> '',
			    'column'		=> 2
			), $atts );
			$args = array(
				'post_type'			=> 'tp-event',
				'tp-event-category'	=> $input['category'],
				'posts_per_page'	=> $input['no_of_posts'],
				'post__in'			=> ! empty( $input['post_ids'] ) ? explode( ',', $input['post_ids'] ) : ''
				);
			$the_query = new WP_Query( $args );
			if ( $the_query -> have_posts() ) : ?>
				<ul class="course-lists <?php echo esc_attr( $this->tp_education_column_class( $input['column'] ) ); ?> os-animation clear">
					<?php  
					/* Start the Loop */
					while ( $the_query -> have_posts() ) : $the_query -> the_post();
						/**
						 * Hook - tp_education_archive_event_content_action.
						 *
						 * @hooked tp_education_archive_event_content -  10
						 */
						do_action( 'tp_education_archive_event_content_action' );
					endwhile; ?>
				</ul><!-- .course-lists -->
			<?php 
			endif;
			wp_reset_postdata();
		return ob_get_clean();

	}

	public function tp_education_course_shortcode_function( $atts ) 
	{
		/*
		 * Course Shortcode Function
		 */

		ob_start();
			$input = shortcode_atts( array(
			    'category' 		=> '',
			    'no_of_posts' 	=> 2,
			    'post_ids'		=> '',
			    'column'		=> 2
			), $atts );
			$args = array(
				'post_type'				=> 'tp-course',
				'tp-course-category'	=> $input['category'],
				'posts_per_page'		=> $input['no_of_posts'],
				'post__in'				=> ! empty( $input['post_ids'] ) ? explode( ',', $input['post_ids'] ) : ''
				);
			$the_query = new WP_Query( $args );
			if ( $the_query -> have_posts() ) : ?>
				<ul class="course-lists <?php echo esc_attr( $this->tp_education_column_class( $input['column'] ) ); ?> os-animation clear">
					<?php  
					/* Start the Loop */
					while ( $the_query -> have_posts() ) : $the_query -> the_post();
						/**
						 * Hook - tp_education_archive_course_content_action.
						 *
						 * @hooked tp_education_archive_course_content -  10
						 */
						do_action( 'tp_education_archive_course_content_action' );
					endwhile; ?>
				</ul><!-- .course-lists -->
			<?php 
			endif;
			wp_reset_postdata();
		return ob_get_clean();
	}

	public function tp_education_excursion_shortcode_function( $atts ) 
	{
		/*
		 * Excursion Shortcode Function
		 */
		
		ob_start();
			$input = shortcode_atts( array(
			    'category' 		=> '',
			    'no_of_posts' 	=> 2,
			    'post_ids'		=> '',
			    'column'		=> 2
			), $atts );
			$args = array(
				'post_type'				=> 'tp-excursion',
				'tp-excursion-category'	=> $input['category'],
				'posts_per_page'		=> $input['no_of_posts'],
				'post__in'				=> ! empty( $input['post_ids'] ) ? explode( ',', $input['post_ids'] ) : ''
				);
			$the_query = new WP_Query( $args );
			if ( $the_query -> have_posts() ) :
			?>
				<ul class="course-lists <?php echo esc_attr( $this->tp_education_column_class( $input['column'] ) ); ?> os-animation clear">
					<?php  
					/* Start the Loop */
					while ( $the_query -> have_posts() ) : $the_query -> the_post();
						/**
						 * Hook - tp_education_archive_excursion_content_action.
						 *
						 * @hooked tp_education_archive_excursion_content -  10
						 */
						do_action( 'tp_education_archive_excursion_content_action' );
					endwhile; ?>
				</ul><!-- .course-lists -->
			<?php endif;
			wp_reset_postdata();
		return ob_get_clean();
	}

	public function tp_education_affiliation_shortcode_function( $atts ) 
	{
		/*
		 * Affiliation Shortcode Function
		 */
		
		ob_start();
			$input = shortcode_atts( array(
			    'category' 		=> '',
			    'no_of_posts' 	=> 2,
			    'post_ids'		=> '',
			    'column'		=> 2
			), $atts );
			$args = array(
				'post_type'					=> 'tp-affiliation',
				'tp-affiliation-category'	=> $input['category'],
				'posts_per_page'			=> $input['no_of_posts'],
				'post__in'					=> ! empty( $input['post_ids'] ) ? explode( ',', $input['post_ids'] ) : ''
				);
			$the_query = new WP_Query( $args );
			if ( $the_query -> have_posts() ) :
			?>
				<ul class="course-lists <?php echo esc_attr( $this->tp_education_column_class( $input['column'] ) ); ?> os-animation clear">
					<?php  
					/* Start the Loop */
					while ( $the_query -> have_posts() ) : $the_query -> the_post();
						/**
						 * Hook - tp_education_archive_affiliation_content_action.
						 *
						 * @hooked tp_education_archive_affiliation_content -  10
						 */
						do_action( 'tp_education_archive_affiliation_content_action' );
					endwhile; ?>
				</ul><!-- .course-lists -->
			<?php endif;
			wp_reset_postdata();
		return ob_get_clean();
	}

	public function tp_education_team_shortcode_function( $atts ) 
	{
		/*
		 * Team Shortcode Function
		 */
		
		ob_start();
			$input = shortcode_atts( array(
			    'category' 		=> '',
			    'no_of_posts' 	=> 2,
			    'post_ids'		=> '',
			    'column'		=> 2
			), $atts );
			$args = array(
				'post_type'			=> 'tp-team',
				'tp-team-category'	=> $input['category'],
				'posts_per_page'	=> $input['no_of_posts'],
				'post__in'			=> ! empty( $input['post_ids'] ) ? explode( ',', $input['post_ids'] ) : ''
				);
			$the_query = new WP_Query( $args );
			if ( $the_query -> have_posts() ) :
			?>
				<ul class="course-lists <?php echo esc_attr( $this->tp_education_column_class( $input['column'] ) ); ?> os-animation clear">
					<?php  
					/* Start the Loop */
					while ( $the_query -> have_posts() ) : $the_query -> the_post();
						/**
						 * Hook - tp_education_archive_team_content_action.
						 *
						 * @hooked tp_education_archive_team_content -  10
						 */
						do_action( 'tp_education_archive_team_content_action' );
					endwhile; ?>
				</ul><!-- .course-lists -->
			<?php endif;
			wp_reset_postdata();
		return ob_get_clean();
	}

	public function tp_education_testimonial_shortcode_function( $atts ) 
	{
		/*
		 * testimonial Shortcode Function
		 */
		
		ob_start();
			$input = shortcode_atts( array(
			    'no_of_posts' 	=> 2,
			    'post_ids'		=> '',
			    'column'		=> 2
			), $atts );
			$args = array(
				'post_type'			=> 'tp-testimonial',
				'posts_per_page'	=> $input['no_of_posts'],
				'post__in'			=> ! empty( $input['post_ids'] ) ? explode( ',', $input['post_ids'] ) : ''
				);
			$the_query = new WP_Query( $args );
			if ( $the_query -> have_posts() ) :
				?>
					<ul class="course-lists <?php echo esc_attr( $this->tp_education_column_class( $input['column'] ) ); ?> os-animation clear">
						<?php  
						/* Start the Loop */
						while ( $the_query -> have_posts() ) : $the_query -> the_post();
							/**
							 * Hook - tp_education_archive_testimonial_content_action.
							 *
							 * @hooked tp_education_archive_testimonial_content -  10
							 */
							do_action( 'tp_education_archive_testimonial_content_action' );
						endwhile; ?>
					</ul><!-- .course-lists -->
				<?php 
			endif;
			wp_reset_postdata();
		return ob_get_clean();
	}

	public function tp_education_search_shortcode_function() 
	{
		/*
		 * Search Tabs Function
		 */	
		ob_start();	?>
	        <ul class="tabs tp-education-search-tabs">
	        	<?php if ( post_type_exists( 'tp-event' ) ) : ?>
	            	<li class="tab-link active" data-tab="tab-1"><a href="#."><i class="fa fa-calendar"></i><?php _e( 'Events', 'tp-education' ); ?></a></li>
	        	<?php endif;
	        	if ( post_type_exists( 'tp-excursion' ) ) : ?>
	            	<li class="tab-link <?php echo ( ! post_type_exists( 'tp-event' ) ) ? 'active' : ''; ?>" data-tab="tab-2"><a href="#."><i class="fa fa-briefcase"></i><?php _e( 'Excursions', 'tp-education' ); ?></a></li>
	            <?php endif;
	        	if ( post_type_exists( 'tp-course' ) ) : ?>
	            	<li class="tab-link <?php echo ( ! post_type_exists( 'tp-event' ) && ! post_type_exists( 'tp-excursion' ) ) ? 'active' : ''; ?>" data-tab="tab-3"><a href="#."><i class="fa fa-book"></i><?php _e( 'Courses', 'tp-education' ); ?></a></li>
	            <?php endif;
	        	if ( post_type_exists( 'tp-team' ) ) : ?>
	            	<li class="tab-link <?php echo ( ! post_type_exists( 'tp-event' ) && ! post_type_exists( 'tp-excursion' ) && ! post_type_exists( 'tp-course' ) ) ? 'active' : ''; ?>" data-tab="tab-4"><a href="#."><i class="fa fa-users"></i><?php _e( 'Team', 'tp-education' ); ?></a></li>
	            <?php endif;
	        	if ( post_type_exists( 'tp-class' ) ) : ?>
	            	<li class="tab-link <?php echo ( ! post_type_exists( 'tp-event' ) && ! post_type_exists( 'tp-excursion' ) && ! post_type_exists( 'tp-course' ) && ! post_type_exists( 'tp-team' ) ) ? 'active' : ''; ?>" data-tab="tab-5"><a href="#."><i class="fa fa-bell"></i><?php _e( 'Classes', 'tp-education' ); ?></a></li>
	            <?php endif;
	        	if ( post_type_exists( 'tp-affiliation' ) ) : ?>
	            	<li class="tab-link <?php echo ( ! post_type_exists( 'tp-event' ) && ! post_type_exists( 'tp-excursion' ) && ! post_type_exists( 'tp-course' ) && ! post_type_exists( 'tp-team' ) && ! post_type_exists( 'tp-class' ) ) ? 'active' : ''; ?>" data-tab="tab-6"><a href="#."><i class="fa fa-certificate"></i><?php _e( 'Affiliation', 'tp-education' ); ?></a></li>
	            <?php endif; ?>
	        </ul><!-- .tabs -->

	        <?php if ( post_type_exists( 'tp-event' ) ) : ?>
	            <div id="tab-1" class="tab-content os-animation active">
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
	            <div id="tab-2" class="tab-content os-animation <?php echo ( ! post_type_exists( 'tp-event' ) ) ? 'active' : ''; ?>">
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
	            <div id="tab-3" class="tab-content os-animation <?php echo ( ! post_type_exists( 'tp-event' ) && ! post_type_exists( 'tp-excursion' ) ) ? 'active' : ''; ?>">
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
	            <div id="tab-4" class="tab-content os-animation <?php echo ( ! post_type_exists( 'tp-event' ) && ! post_type_exists( 'tp-excursion' ) && ! post_type_exists( 'tp-course' ) ) ? 'active' : ''; ?>">
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
	            <div id="tab-5" class="tab-content os-animation <?php echo ( ! post_type_exists( 'tp-event' ) && ! post_type_exists( 'tp-excursion' ) && ! post_type_exists( 'tp-course' ) && ! post_type_exists( 'tp-team' ) ) ? 'active' : ''; ?>">
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
	            <div id="tab-6" class="tab-content os-animation <?php echo ( ! post_type_exists( 'tp-event' ) && ! post_type_exists( 'tp-excursion' ) && ! post_type_exists( 'tp-course' ) && ! post_type_exists( 'tp-team' ) && ! post_type_exists( 'tp-class' ) ) ? 'active' : ''; ?>">
	                <?php  
	                /**
	                * Hook - tp_education_search_affiliation_form_action.
	                *
	                * @hooked tp_education_search_affiliation_form -  10
	                */
	                do_action( 'tp_education_search_affiliation_form_action' );
	                ?>
	            </div><!-- #tab-6 -->
	        <?php endif; 
		return ob_get_clean();
	}

	public function tp_education_create_shortcode() 
	{
		/*
		 * Create Shortcodes
		 */
		add_shortcode( 'TP_EDUCATION_CLASS', array( $this, 'tp_education_class_shortcode_function' ) );
		add_shortcode( 'TP_EDUCATION_EVENT', array( $this, 'tp_education_event_shortcode_function' ) );
		add_shortcode( 'TP_EDUCATION_COURSE', array( $this, 'tp_education_course_shortcode_function' ) );
		add_shortcode( 'TP_EDUCATION_TEAM', array( $this, 'tp_education_team_shortcode_function' ) );
		add_shortcode( 'TP_EDUCATION_EXCURSION', array( $this, 'tp_education_excursion_shortcode_function' ) );
		add_shortcode( 'TP_EDUCATION_AFFILIATION', array( $this, 'tp_education_affiliation_shortcode_function' ) );
		add_shortcode( 'TP_EDUCATION_TESTIMONIAL', array( $this, 'tp_education_testimonial_shortcode_function' ) );
		add_shortcode( 'TP_EDUCATION_SEARCH_TAB', array( $this, 'tp_education_search_shortcode_function' ) );
	}

}

new TP_Education_Shortcode();

