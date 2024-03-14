<?php
/**
 * TP Education core functions
 *
 * @package TP Education
 * @since 1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/*
 * Check TP Education Page
 */
if( ! function_exists( 'is_tp_education' ) ):
	function is_tp_education() {
		$singular = is_singular( array( 'tp-affiliation', 'tp-class', 'tp-course', 'tp-event', 'tp-excursion', 'tp-team', 'tp-testimonial' ) );
		$archive =  is_post_type_archive( array( 'tp-affiliation', 'tp-class', 'tp-course', 'tp-event', 'tp-excursion', 'tp-team', 'tp-testimonial' ) );
		$tax_archive =  is_tax( array( 'tp-affiliation-category', 'tp-class-category', 'tp-course-category', 'tp-event-category', 'tp-excursion-category', 'tp-team-category', 'tp-testimonial-category' ) );
		
		return apply_filters( 'is_tp_education_filter', $singular || $archive || $tax_archive );
	}
endif;

/*
 * Event Details
 */
if( ! function_exists( 'tp_event_date' ) ):
	// Event date
	function tp_event_date( $post_id = '' ) {
		if ( empty( $post_id ) ) {
			GLOBAL $post;
			$post_id = $post->ID;
		}
		$tp_event_date = get_post_meta( $post_id, 'tp_event_date_value', true );
		if ( ! empty( $tp_event_date ) ) {
			echo '<small class="tp-event-date-label">' . esc_html__( 'Date: ', 'tp-education' ) . '</small><span class="tp-event-date">' . esc_html( $tp_event_date ) . '</span>';
		}
	}
endif;

if( ! function_exists( 'tp_event_start_time' ) ):
	// Event start time
	function tp_event_start_time( $post_id = '' ) {
		if ( empty( $post_id ) ) {
			GLOBAL $post;
			$post_id = $post->ID;
		}
		$tp_event_start_time = get_post_meta( $post_id, 'tp_event_time_from_value', true );
		if ( ! empty( $tp_event_start_time ) ) {
			echo '<small class="tp-event-start-time-label">' . esc_html__( 'From: ', 'tp-education' ) . '</small><span class="tp-event-start-time">' . esc_html( $tp_event_start_time ) . '</span>';
		}
	}
endif;

if( ! function_exists( 'tp_event_end_time' ) ):
	// Event end time
	function tp_event_end_time( $post_id = '' ) {
		if ( empty( $post_id ) ) {
			GLOBAL $post;
			$post_id = $post->ID;
		}
		$tp_event_end_time = get_post_meta( $post_id, 'tp_event_time_to_value', true );
		if ( ! empty( $tp_event_end_time ) ) {
			echo '<small class="tp-event-end-time-label">' . esc_html__( 'To: ', 'tp-education' ) . '</small><span class="tp-event-end-time">' . esc_html( $tp_event_end_time ) . '</span>';
		}
	}
endif;

if( ! function_exists( 'tp_event_location' ) ):
	// Event locaton
	function tp_event_location( $post_id = '' ) {
		if ( empty( $post_id ) ) {
			GLOBAL $post;
			$post_id = $post->ID;
		}
		$tp_event_location = get_post_meta( $post_id, 'tp_event_location_value', true );
		if ( ! empty( $tp_event_location ) ) {
			echo '<small class="tp-event-location-label">' . esc_html__( 'Location: ', 'tp-education' ) . '</small><span class="tp-event-location">' . strip_tags( htmlspecialchars_decode( $tp_event_location ) ) . '</span>';
		}
	}
endif;


/*
 * Class Details
 */
if( ! function_exists( 'tp_class_cost' ) ):
	// Class Cost
	function tp_class_cost( $post_id = '' ) {
		if ( empty( $post_id ) ) {
			GLOBAL $post;
			$post_id = $post->ID;
		}
		$tp_class_cost = get_post_meta( $post_id, 'tp_class_cost_value', true );
		if ( ! empty( $tp_class_cost ) ) {
			echo '<small class="tp-class-price-label">' . esc_html__( 'Price: ', 'tp-education' ) . '</small><span class="tp-class-price">' . esc_html( $tp_class_cost ) . '</span>';
		}
	}
endif;

if( ! function_exists( 'tp_class_period' ) ):
	// Class period
	function tp_class_period( $post_id = '' ) {
		if ( empty( $post_id ) ) {
			GLOBAL $post;
			$post_id = $post->ID;
		}
		$tp_class_period = get_post_meta( $post_id, 'tp_class_period_value', true );
		if ( ! empty( $tp_class_period ) ) {
			echo '<span class="tp-class-period">' . esc_html( $tp_class_period ) . '</span>';
		}
	}
endif;

if( ! function_exists( 'tp_class_size' ) ):
	// Class Size
	function tp_class_size( $post_id = '' ) {
		if ( empty( $post_id ) ) {
			GLOBAL $post;
			$post_id = $post->ID;
		}
		$tp_class_size = get_post_meta( $post_id, 'tp_class_size_value', true );
		if ( ! empty( $tp_class_size ) ) {
			echo '<small class="tp-class-size-label">' . esc_html__( 'Class Size: ', 'tp-education' ) . '</small><span class="tp-class-size">' . absint( $tp_class_size ) . '</span>';
		}
	}
endif;

if( ! function_exists( 'tp_class_age_group' ) ):
	// Class Age Group
	function tp_class_age_group( $post_id = '' ) {
		if ( empty( $post_id ) ) {
			GLOBAL $post;
			$post_id = $post->ID;
		}
		$tp_class_age_group = get_post_meta( $post_id, 'tp_class_age_group_value', true );
		if ( ! empty( $tp_class_age_group ) ) {
			echo '<small class="tp-class-age-group-label">' . esc_html__( 'Years Old: ', 'tp-education' ) . '</small><span class="tp-class-age-group">' . esc_html( $tp_class_age_group ) . '</span>';
		}
	}
endif;


/*
 * Excursion Details
 */

if( ! function_exists( 'tp_excursion_start_date' ) ):
	// Excursion start date
	function tp_excursion_start_date( $post_id = '' ) {
		if ( empty( $post_id ) ) {
			GLOBAL $post;
			$post_id = $post->ID;
		}
		$tp_excursion_start_date = get_post_meta( $post_id, 'tp_excursion_start_date_value', true );
		if ( ! empty( $tp_excursion_start_date ) ) {
			echo '<small class="tp-excursion-start-date-label">' . esc_html__( 'Start Date: ', 'tp-education' ) . '</small><span class="tp-excursion-start-date">' . esc_html( $tp_excursion_start_date ) . '</span>';
		}
	}
endif;

if( ! function_exists( 'tp_excursion_end_date' ) ):
	// Excursion end date
	function tp_excursion_end_date( $post_id = '' ) {
		if ( empty( $post_id ) ) {
			GLOBAL $post;
			$post_id = $post->ID;
		}
		$tp_excursion_end_date = get_post_meta( $post_id, 'tp_excursion_end_date_value', true );
		if ( ! empty( $tp_excursion_end_date ) ) {
			echo '<small class="tp-excursion-end-date-label">' . esc_html__( 'End Date: ', 'tp-education' ) . '</small><span class="tp-excursion-end-date">' . esc_html( $tp_excursion_end_date ) . '</span>';
		}
	}
endif;

if( ! function_exists( 'tp_excursion_location' ) ):
	// Excursion location
	function tp_excursion_location( $post_id = '' ) {
		if ( empty( $post_id ) ) {
			GLOBAL $post;
			$post_id = $post->ID;
		}
		$tp_excursion_location = get_post_meta( $post_id, 'tp_excursion_location_value', true );
		if ( ! empty( $tp_excursion_location ) ) {
			echo '<small class="tp-excursion-location-label">' . esc_html__( 'Location: ', 'tp-education' ) . '</small><span class="tp-excursion-location">' . strip_tags( htmlspecialchars_decode( $tp_excursion_location ) ) . '</span>';
		}
	}
endif;


/*
 * Team Details
 */

if( ! function_exists( 'tp_team_designation' ) ):
	// Team Designation
	function tp_team_designation( $post_id = '' ) {
		if ( empty( $post_id ) ) {
			GLOBAL $post;
			$post_id = $post->ID;
		}
		$tp_team_designation = get_post_meta( $post_id, 'tp_team_designation_value', true );
		if ( ! empty( $tp_team_designation ) ) {
			echo '<small class="tp-team-designation-label">' . esc_html__( 'Designation: ', 'tp-education' ) . '</small><span class="tp-team-designation">' . esc_html( $tp_team_designation ) . '</span>';
		}
	}
endif;

if( ! function_exists( 'tp_team_email' ) ):
	// Team Email
	function tp_team_email( $post_id = '' ) {
		if ( empty( $post_id ) ) {
			GLOBAL $post;
			$post_id = $post->ID;
		}
		$tp_team_email = get_post_meta( $post_id, 'tp_team_email_value', true );
		if ( ! empty( $tp_team_email ) ) {
			echo '<small class="tp-team-email-label">' . esc_html__( 'Email: ', 'tp-education' ) . '</small><span class="tp-team-email">' . esc_html( $tp_team_email ) . '</span>';
		}
	}
endif;

if( ! function_exists( 'tp_team_phone' ) ):
	// Team Phone
	function tp_team_phone( $post_id = '' ) {
		if ( empty( $post_id ) ) {
			GLOBAL $post;
			$post_id = $post->ID;
		}
		$tp_team_phone = get_post_meta( $post_id, 'tp_team_phone_value', true );
		if ( ! empty( $tp_team_phone ) ) {
			echo '<small class="tp-team-phone-label">' . esc_html__( 'Phone: ', 'tp-education' ) . '</small><span class="tp-team-phone">' . esc_html( $tp_team_phone ) . '</span>';
		}
	}
endif;

if( ! function_exists( 'tp_team_skype' ) ):
	// Team Skype
	function tp_team_skype( $post_id = '' ) {
		if ( empty( $post_id ) ) {
			GLOBAL $post;
			$post_id = $post->ID;
		}
		$tp_team_skype = get_post_meta( $post_id, 'tp_team_skype_value', true );
		if ( ! empty( $tp_team_skype ) ) {
			echo '<small class="tp-team-skype-label">' . esc_html__( 'Skype: ', 'tp-education' ) . '</small><span class="tp-team-skype">' . esc_html( $tp_team_skype ) . '</span>';
		}
	}
endif;

if( ! function_exists( 'tp_team_website' ) ):
	// Team Website
	function tp_team_website( $post_id = '' ) {
		if ( empty( $post_id ) ) {
			GLOBAL $post;
			$post_id = $post->ID;
		}
		$tp_team_website = get_post_meta( $post_id, 'tp_team_website_value', true );
		if ( ! empty( $tp_team_website ) ) {
			echo '<small class="tp-team-Website-label">' . esc_html__( 'website: ', 'tp-education' ) . '</small><span class="tp-team-Website">' . esc_html( $tp_team_website ) . '</span>';
		}
	}
endif;

if( ! function_exists( 'tp_team_courses' ) ):
	// Team Courses
	function tp_team_courses( $post_id = '' ) {
		if ( empty( $post_id ) ) {
			GLOBAL $post;
			$post_id = $post->ID;
		}
		$tp_team_courses = get_post_meta( $post_id, 'tp_team_courses_value', true );
		if ( ! empty( $tp_team_courses ) && $tp_team_courses[0] !== 0 ) {
			$args = array(
				'post_type' => 'tp-course',
				'post__in'	=> $tp_team_courses
				);
			$posts = get_posts( $args );
			echo '<small class="tp-team-courses-label">' . esc_html__( 'Courses: ', 'tp-education' ) . '</small><span class="tp-team-courses">'; 
			foreach ( $posts as $post ) :
				echo '<a href="' . esc_url( get_the_permalink( $post->ID ) ) . '">' . esc_html( $post->post_title ) . '</a> ';
			endforeach;
			echo '</span>';
			wp_reset_postdata();
		}
	}
endif;

if( ! function_exists( 'tp_team_social' ) ):
	// Team Social
	function tp_team_social( $post_id = '' ) {
		if ( empty( $post_id ) ) {
			GLOBAL $post;
			$post_id = $post->ID;
		}
		$stored_team_social     = get_post_meta( $post_id, 'tp_team_social_count_value', true );
		$stored_team_social     = ! empty( $stored_team_social ) ? $stored_team_social : 4;
		for ( $i = 1; $i <= $stored_team_social; $i++ ) {
		    $stored_social[$i]  = get_post_meta( $post_id, 'tp_team_social_value_' . $i, true );
		    $stored_social[$i]  = ! empty( $stored_social[$i] ) ? $stored_social[$i] : '';
		}
		if ( count( $stored_social ) > 0 ) {
		?>
			<div class="social-link clear">
				<ul class="tp-social social-icon">
					<?php foreach ( $stored_social as $stored_social_link ) : 
						if ( ! empty( $stored_social_link ) ) : ?>
						<li><a href="<?php echo esc_url( $stored_social_link ); ?>" target="_blank"></a></li>
						<?php endif;
					endforeach; ?>
				</ul><!--.social-icon-->
			</div><!--.social-link-->
		<?php 
		}
	}
endif;

/*
 * Testimonial Details
 */

if( ! function_exists( 'tp_testimonial_rating' ) ):
	// Testimonial ratings
	function tp_testimonial_rating( $post_id = '' ) {
		if ( empty( $post_id ) ) {
			GLOBAL $post;
			$post_id = $post->ID;
		}
		
		$tp_testimonail_rating = get_post_meta( $post_id, 'tp_testimonial_rating_value', true );
		for( $i=1; $i <= 5; $i++ ){
			if( $i <= $tp_testimonail_rating ) {
				$rating_class = 'fa-star';
			} else {
				$rating_class = 'fa-star-o';
			}
			echo '<i class="fa '. esc_attr( $rating_class ). '"></i>';
		}
	}
endif;

if( ! function_exists( 'tp_testimonial_designation' ) ):
	// Testimonial Designation
	function tp_testimonial_designation( $post_id = '' ) {
		if ( empty( $post_id ) ) {
			GLOBAL $post;
			$post_id = $post->ID;
		}
		$tp_testimonial_designation = get_post_meta( $post_id, 'tp_testimonial_designation_value', true );
		if ( ! empty( $tp_testimonial_designation ) ) {
			echo '<small class="tp-testimonial-designation-label">' . esc_html__( 'Designation: ', 'tp-education' ) . '</small><span class="tp-testimonial-designation">' . esc_html( $tp_testimonial_designation ) . '</span>';
		}
	}
endif;

if( ! function_exists( 'tp_testimonial_social' ) ):
	// Testimonial Social
	function tp_testimonial_social( $post_id = '' ) {
		if ( empty( $post_id ) ) {
			GLOBAL $post;
			$post_id = $post->ID;
		}
		$stored_testimonial_social     = get_post_meta( $post_id, 'tp_testimonial_social_count_value', true );
		$stored_testimonial_social     = ! empty( $stored_testimonial_social ) ? $stored_testimonial_social : 4;
		for ( $i = 1; $i <= $stored_testimonial_social; $i++ ) {
		    $stored_social[$i]  = get_post_meta( $post_id, 'tp_testimonial_social_value_' . $i, true );
		    $stored_social[$i]  = ! empty( $stored_social[$i] ) ? $stored_social[$i] : '';
		}
		if ( count( $stored_social ) > 0 ) {
		?>
			<div class="social-link clear">
				<ul class="tp-social social-icon">
					<?php foreach ( $stored_social as $stored_social_link ) : 
						if ( ! empty( $stored_social_link ) ) : ?>
						<li><a href="<?php echo esc_url( $stored_social_link ); ?>" target="_blank"></a></li>
						<?php endif;
					endforeach; ?>
				</ul><!--.social-icon-->
			</div><!--.social-link-->
		<?php 
		}
	}
endif;


/*
 * Course Details
 */

if( ! function_exists( 'tp_course_type' ) ):
	// Course type
	function tp_course_type( $post_id = '' ) {
		if ( empty( $post_id ) ) {
			GLOBAL $post;
			$post_id = $post->ID;
		}
		$tp_course_type = get_post_meta( $post_id, 'tp_course_type_value', true );
		if ( ! empty( $tp_course_type ) ) {
			echo '<small class="tp-course-type-label">' . esc_html__( 'Type: ', 'tp-education' ) . '</small><span class="tp-course-type">' . esc_html( $tp_course_type ) . '</span>';
		}
	}
endif;

if( ! function_exists( 'tp_course_duration' ) ):
	// Course duration
	function tp_course_duration( $post_id = '' ) {
		if ( empty( $post_id ) ) {
			GLOBAL $post;
			$post_id = $post->ID;
		}
		$tp_course_duration = get_post_meta( $post_id, 'tp_course_duration_value', true );
		if ( ! empty( $tp_course_duration ) ) {
			echo '<small class="tp-course-duration-label">' . esc_html__( 'Duration: ', 'tp-education' ) . '</small><span class="tp-course-duration">' . esc_html( $tp_course_duration ) . '</span>';
		}
	}
endif;

if( ! function_exists( 'tp_course_price' ) ):
	// Course price
	function tp_course_price( $post_id = '' ) {
		if ( empty( $post_id ) ) {
			GLOBAL $post;
			$post_id = $post->ID;
		}
		$tp_course_price = get_post_meta( $post_id, 'tp_course_price_value', true );
		if ( ! empty( $tp_course_price ) ) {
			echo '<small class="tp-course-price-label">' . esc_html__( 'Price: ', 'tp-education' ) . '</small><span class="tp-course-price">' . esc_html( $tp_course_price ) . '</span>';
		}
	}
endif;


if( ! function_exists( 'tp_course_starting_date' ) ):
	// Course price
	function tp_course_starting_date( $post_id = '' ) {
		if ( empty( $post_id ) ) {
			GLOBAL $post;
			$post_id = $post->ID;
		}
		$tp_course_starting_date = get_post_meta( $post_id, 'tp_course_starting_date_value', true );
		if ( ! empty( $tp_course_starting_date ) ) {
			echo '<small class="tp-course-starting-date-label">' . esc_html__( 'Starts on: ', 'tp-education' ) . '</small><span class="tp-course-starting-date">' . esc_html( $tp_course_starting_date ) . '</span>';
		}
	}
endif;

if( ! function_exists( 'tp_course_students' ) ):
	// Course no of students
	function tp_course_students( $post_id = '' ) {
		if ( empty( $post_id ) ) {
			GLOBAL $post;
			$post_id = $post->ID;
		}
		$tp_course_students = get_post_meta( $post_id, 'tp_course_students_value', true );
		if ( ! empty( $tp_course_students ) ) {
			echo '<small class="tp-course-students-label">' . esc_html__( 'Students: ', 'tp-education' ) . '</small><span class="tp-course-students">' . esc_html( $tp_course_students ) . '</span>';
		}
	}
endif;

if( ! function_exists( 'tp_course_language' ) ):
	// Course language
	function tp_course_language( $post_id = '' ) {
		if ( empty( $post_id ) ) {
			GLOBAL $post;
			$post_id = $post->ID;
		}
		$tp_course_language = get_post_meta( $post_id, 'tp_course_language_value', true );
		if ( ! empty( $tp_course_language ) ) {
			echo '<small class="tp-course-language-label">' . esc_html__( 'Language: ', 'tp-education' ) . '</small><span class="tp-course-language">' . esc_html( $tp_course_language ) . '</span>';
		}
	}
endif;

if( ! function_exists( 'tp_course_assessment' ) ):
	// Course assessment
	function tp_course_assessment( $post_id = '' ) {
		if ( empty( $post_id ) ) {
			GLOBAL $post;
			$post_id = $post->ID;
		}
		$tp_course_assessment = get_post_meta( $post_id, 'tp_course_assessment_value', true );
		if ( ! empty( $tp_course_assessment ) ) {
			echo '<small class="tp-course-assessment-label">' . esc_html__( 'Assessment: ', 'tp-education' ) . '</small><span class="tp-course-assessment">' . esc_html( $tp_course_assessment ) . '</span>';
		}
	}
endif;

if( ! function_exists( 'tp_course_skills' ) ):
	// Course skills
	function tp_course_skills( $post_id = '' ) {
		if ( empty( $post_id ) ) {
			GLOBAL $post;
			$post_id = $post->ID;
		}
		$tp_course_skills = get_post_meta( $post_id, 'tp_course_skills_value', true );
		if ( ! empty( $tp_course_skills ) ) {
			echo '<small class="tp-course-skills-label">' . esc_html__( 'Skills: ', 'tp-education' ) . '</small><span class="tp-course-skills">' . esc_html( $tp_course_skills ) . '</span>';
		}
	}
endif;

if( ! function_exists( 'tp_course_professor' ) ):
	// Course professor
	function tp_course_professor( $post_id = '' ) {
		if ( empty( $post_id ) ) {
			GLOBAL $post;
			$post_id = $post->ID;
		}
		$tp_course_professor = get_post_meta( $post_id, 'tp_course_professor_value', true );
		if ( ! empty( $tp_course_professor ) && $tp_course_professor[0] !== 0 ) {
			$args = array(
				'post_type' => 'tp-team',
				'p'			=> $tp_course_professor
				);
			$posts = get_posts( $args );
			foreach ( $posts as $post ) :
				echo '<small class="tp-course-professor-label">' . esc_html__( 'Professor: ', 'tp-education' ) . '</small><span class="tp-course-professor"><a href="' . esc_url( get_the_permalink( $post->ID ) ) . '">' . esc_html( get_the_title( $post->ID ) ) . '</a></span>';
			endforeach;
			wp_reset_postdata();
		}
	}
endif;

if( ! function_exists( 'tp_course_counselors' ) ):
	// Course counselors
	function tp_course_counselors( $post_id = '' ) {
		if ( empty( $post_id ) ) {
			GLOBAL $post;
			$post_id = $post->ID;
		}
		$tp_course_counselors = get_post_meta( $post_id, 'tp_course_counselors_value', true );
		if ( ! empty( $tp_course_counselors ) && $tp_course_counselors[0] !== 0 ) {
			$args = array(
				'post_type' => 'tp-team',
				'post__in'	=> $tp_course_counselors
				);
			$posts = get_posts( $args );
			echo '<small class="tp-course-counselors-label">' . esc_html__( 'counselors: ', 'tp-education' ) . '</small><span class="tp-course-counselors">'; 
			foreach ( $posts as $post ) :
				echo '<a href="' . esc_url( get_the_permalink( $post->ID ) ) . '">' . esc_html( $post->post_title ) . '</a> ';
			endforeach;
			echo '</span>';
			wp_reset_postdata();
		}
	}
endif;

/*
 * Affiliation Details
 */

if( ! function_exists( 'tp_affiliation_link' ) ):
	// Affiliation type
	function tp_affiliation_link( $post_id = '' ) {
		if ( empty( $post_id ) ) {
			GLOBAL $post;
			$post_id = $post->ID;
		}
		$tp_affiliation_link = get_post_meta( $post_id, 'tp_affiliation_link_value', true );
		if ( ! empty( $tp_affiliation_link ) ) {
			echo '<a href="' . esc_url( $tp_affiliation_link ) . '" target="_blank">' . apply_filters( 'tp_affiliation_link_filter', esc_html__( 'Click to visit site', 'tp-education' ) ). '</a>';
		}
	}
endif;

/*
 * Get Terms
 */

if( ! function_exists( 'tp_education_get_terms' ) ):
	// Terms name and slug
	function tp_education_get_terms( $taxonomy = '', $post_id = '' ) {
		if ( empty( $post_id ) ) {
			GLOBAL $post;
			$post_id = $post->ID;
		}
		$tp_taxonomies = wp_get_post_terms( $post_id, $taxonomy, array( "fields" => "all" ) );
		foreach ( $tp_taxonomies as $tp_taxonomy ) {
			echo '<a href="' . esc_url( get_term_link( $tp_taxonomy->slug, $taxonomy ) ) . '" class="category-name">' . esc_html( $tp_taxonomy->name ) . '</a> ';
		}
	}
endif;

if( ! function_exists( 'tp_education_like_button' ) ):
	// Post like button
	function tp_education_like_button( $post_id = '' ) {
		if ( empty( $post_id ) ) {
			GLOBAL $post;
			$post_id = $post->ID;
		}
		$like_button = new TP_Education_Like_Metabox;
		return $like_button->tp_education_custom_like( $post_id );
	}
endif;

if( ! function_exists( 'tp_education_posted_on' ) ):
	// Post Meta
	function tp_education_posted_on() {
		$time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';
		if ( get_the_time( 'U' ) !== get_the_modified_time( 'U' ) ) {
			$time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time><time class="updated" datetime="%3$s">%4$s</time>';
		}

		$time_string = sprintf( $time_string,
			esc_attr( get_the_date( 'c' ) ),
			esc_html( get_the_date() ),
			esc_attr( get_the_modified_date( 'c' ) ),
			esc_html( get_the_modified_date() )
		);
		$year  = get_the_time('Y');
	    $month = get_the_time('m');
	    $day   = get_the_time('d');
	    $post_type = get_query_var( 'post_type' );

		$like_string = tp_education_like_button();

		$comment_string = get_comments_number( 0, 1, '' );

		$posted_on = sprintf(
			esc_html_x( '%s', 'post date', 'tp-education' ),
			'<a href="' . esc_url( tp_education_post_type_date_link( $post_type, $year, $month, $day ) ) . '" rel="nofollow">' . $time_string . '</a>'
		);


		$output  = '<span class="posted-on">' . $posted_on . '</span>';
		$output .= '<span class="screen-reader-text">' . esc_html__( 'Likes', 'tp-education' ) . '</span>' . $like_string;
		$output .= '<span class="comments-links">
					<span class="screen-reader-text">' . esc_html__( 'Comments', 'tp-education' ) . '</span>
					<span class="comments-number"> ' . absint( $comment_string ) . '</span>
					</span>'; // WPCS: XSS OK.
		echo $output;

	}
endif;

if( ! function_exists( 'tp_education_post_type_date_link' ) ):
	/**
	 * This allows us to generate any archive link - plain, yearly, monthly, daily
	 * 
	 * @param string $post_type
	 * @param int $year
	 * @param int $month (optional)
	 * @param int $day (optional)
	 * @return string
	 */
	function tp_education_post_type_date_link( $post_type, $year, $month = 0, $day = 0 ) {
	    global $wp_rewrite;
	    $post_type_obj = get_post_type_object( $post_type );
	    $post_type_slug = $post_type_obj->rewrite['slug'] ? $post_type_obj->rewrite['slug'] : $post_type_obj->name;
	    if( $day ) { // day archive link
	        // set to today's values if not provided
	        if ( !$year )
	            $year = gmdate( 'Y', current_time( 'timestamp' ) );
	        if ( !$month )
	            $month = gmdate( 'm', current_time( 'timestamp' ) );
	        $link = $wp_rewrite->get_day_permastruct();
	    } else if ( $month ) { // month archive link
	        if ( !$year )
	            $year = gmdate( 'Y', current_time( 'timestamp' ) );
	        $link = $wp_rewrite->get_month_permastruct();
	    } else { // year archive link
	        $link = $wp_rewrite->get_year_permastruct();
	    }
	    if ( !empty($link) ) {
	        $link = str_replace( '%year%', $year, $link );
	        $link = str_replace( '%monthnum%', zeroise( intval( $month ), 2 ), $link );
	        $link = str_replace('%day%', zeroise( intval( $day ), 2 ), $link );
	        return home_url( "$post_type_slug$link" );
	    }
	    return home_url( "$post_type_slug" );
	}
endif;
