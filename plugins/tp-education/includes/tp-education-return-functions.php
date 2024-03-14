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
 * Event Details
 */
if( ! function_exists( 'get_tp_event_date' ) ):
	// Event date
	function get_tp_event_date( $post_id = '' ) {
		if ( empty( $post_id ) ) {
			GLOBAL $post;
			$post_id = $post->ID;
		}
		$tp_event_date = get_post_meta( $post_id, 'tp_event_date_value', true );
		return ! empty( $tp_event_date ) ? $tp_event_date : '';
	}
endif;

if( ! function_exists( 'get_tp_event_start_time' ) ):
	// Event start time
	function get_tp_event_start_time( $post_id = '' ) {
		if ( empty( $post_id ) ) {
			GLOBAL $post;
			$post_id = $post->ID;
		}
		$tp_event_start_time = get_post_meta( $post_id, 'tp_event_time_from_value', true );
		return ! empty( $tp_event_start_time ) ? $tp_event_start_time : '';
	}
endif;

if( ! function_exists( 'get_tp_event_end_time' ) ):
	// Event end time
	function get_tp_event_end_time( $post_id = '' ) {
		if ( empty( $post_id ) ) {
			GLOBAL $post;
			$post_id = $post->ID;
		}
		$tp_event_end_time = get_post_meta( $post_id, 'tp_event_time_to_value', true );
		return ! empty( $tp_event_end_time ) ? $tp_event_end_time : '';
	}
endif;

if( ! function_exists( 'get_tp_event_location' ) ):
	// Event locaton
	function get_tp_event_location( $post_id = '' ) {
		if ( empty( $post_id ) ) {
			GLOBAL $post;
			$post_id = $post->ID;
		}
		$tp_event_location = get_post_meta( $post_id, 'tp_event_location_value', true );
		return ! empty( $tp_event_location ) ? $tp_event_location : '';
	}
endif;


/*
 * Class Details
 */
if( ! function_exists( 'get_tp_class_cost' ) ):
	// Class Cost
	function get_tp_class_cost( $post_id = '' ) {
		if ( empty( $post_id ) ) {
			GLOBAL $post;
			$post_id = $post->ID;
		}
		$tp_class_cost = get_post_meta( $post_id, 'tp_class_cost_value', true );
		return ! empty( $tp_class_cost ) ? $tp_class_cost : '';
	}
endif;

if( ! function_exists( 'get_tp_class_period' ) ):
	// Class period
	function get_tp_class_period( $post_id = '' ) {
		if ( empty( $post_id ) ) {
			GLOBAL $post;
			$post_id = $post->ID;
		}
		$tp_class_period = get_post_meta( $post_id, 'tp_class_period_value', true );
		return ! empty( $tp_class_period ) ? $tp_class_period : '';
	}
endif;

if( ! function_exists( 'get_tp_class_size' ) ):
	// Class Size
	function get_tp_class_size( $post_id = '' ) {
		if ( empty( $post_id ) ) {
			GLOBAL $post;
			$post_id = $post->ID;
		}
		$tp_class_size = get_post_meta( $post_id, 'tp_class_size_value', true );
		return ! empty( $tp_class_size ) ? $tp_class_size : '';
	}
endif;

if( ! function_exists( 'get_tp_class_age_group' ) ):
	// Class Age Group
	function get_tp_class_age_group( $post_id = '' ) {
		if ( empty( $post_id ) ) {
			GLOBAL $post;
			$post_id = $post->ID;
		}
		$tp_class_age_group = get_post_meta( $post_id, 'tp_class_age_group_value', true );
		return ! empty( $tp_class_age_group ) ? $tp_class_age_group : '';
	}
endif;


/*
 * Excursion Details
 */

if( ! function_exists( 'get_tp_excursion_start_date' ) ):
	// Excursion start date
	function get_tp_excursion_start_date( $post_id = '' ) {
		if ( empty( $post_id ) ) {
			GLOBAL $post;
			$post_id = $post->ID;
		}
		$tp_excursion_start_date = get_post_meta( $post_id, 'tp_excursion_start_date_value', true );
		return ! empty( $tp_excursion_start_date ) ? $tp_excursion_start_date : '';
	}
endif;

if( ! function_exists( 'get_tp_excursion_end_date' ) ):
	// Excursion end date
	function get_tp_excursion_end_date( $post_id = '' ) {
		if ( empty( $post_id ) ) {
			GLOBAL $post;
			$post_id = $post->ID;
		}
		$tp_excursion_end_date = get_post_meta( $post_id, 'tp_excursion_end_date_value', true );
		return ! empty( $tp_excursion_end_date ) ? $tp_excursion_end_date : '';
	}
endif;

if( ! function_exists( 'get_tp_excursion_location' ) ):
	// Excursion location
	function get_tp_excursion_location( $post_id = '' ) {
		if ( empty( $post_id ) ) {
			GLOBAL $post;
			$post_id = $post->ID;
		}
		$tp_excursion_location = get_post_meta( $post_id, 'tp_excursion_location_value', true );
		return ! empty( $tp_excursion_location ) ? $tp_excursion_location : '';
	}
endif;


/*
 * Team Details
 */

if( ! function_exists( 'get_tp_team_designation' ) ):
	// Team Designation
	function get_tp_team_designation( $post_id = '' ) {
		if ( empty( $post_id ) ) {
			GLOBAL $post;
			$post_id = $post->ID;
		}
		$tp_team_designation = get_post_meta( $post_id, 'tp_team_designation_value', true );
		return ! empty( $tp_team_designation ) ? $tp_team_designation : '';
	}
endif;

if( ! function_exists( 'get_tp_team_email' ) ):
	// Team Email
	function get_tp_team_email( $post_id = '' ) {
		if ( empty( $post_id ) ) {
			GLOBAL $post;
			$post_id = $post->ID;
		}
		$tp_team_email = get_post_meta( $post_id, 'tp_team_email_value', true );
		return ! empty( $tp_team_email ) ? $tp_team_email : '';
	}
endif;

if( ! function_exists( 'get_tp_team_phone' ) ):
	// Team Phone
	function get_tp_team_phone( $post_id = '' ) {
		if ( empty( $post_id ) ) {
			GLOBAL $post;
			$post_id = $post->ID;
		}
		$tp_team_phone = get_post_meta( $post_id, 'tp_team_phone_value', true );
		return ! empty( $tp_team_phone ) ? $tp_team_phone : '';
	}
endif;

if( ! function_exists( 'get_tp_team_skype' ) ):
	// Team Skype
	function get_tp_team_skype( $post_id = '' ) {
		if ( empty( $post_id ) ) {
			GLOBAL $post;
			$post_id = $post->ID;
		}
		$tp_team_skype = get_post_meta( $post_id, 'tp_team_skype_value', true );
		return ! empty( $tp_team_skype ) ? $tp_team_skype : '';
	}
endif;

if( ! function_exists( 'get_tp_team_website' ) ):
	// Team Website
	function get_tp_team_website( $post_id = '' ) {
		if ( empty( $post_id ) ) {
			GLOBAL $post;
			$post_id = $post->ID;
		}
		$tp_team_website = get_post_meta( $post_id, 'tp_team_website_value', true );
		return ! empty( $tp_team_website ) ? $tp_team_website : '';
	}
endif;

/*
 * Testimonial Details
 */

if( ! function_exists( 'get_tp_testimonial_designation' ) ):
	// Testimonial Designation
	function get_tp_testimonial_designation( $post_id = '' ) {
		if ( empty( $post_id ) ) {
			GLOBAL $post;
			$post_id = $post->ID;
		}
		$tp_testimonial_designation = get_post_meta( $post_id, 'tp_testimonial_designation_value', true );
		return ! empty( $tp_testimonial_designation ) ? $tp_testimonial_designation : '';
	}
endif;


/*
 * Course Details
 */

if( ! function_exists( 'get_tp_course_type' ) ):
	// Course type
	function get_tp_course_type( $post_id = '' ) {
		if ( empty( $post_id ) ) {
			GLOBAL $post;
			$post_id = $post->ID;
		}
		$tp_course_type = get_post_meta( $post_id, 'tp_course_type_value', true );
		return ! empty( $tp_course_type ) ? $tp_course_type : '';
	}
endif;

if( ! function_exists( 'get_tp_course_duration' ) ):
	// Course duration
	function get_tp_course_duration( $post_id = '' ) {
		if ( empty( $post_id ) ) {
			GLOBAL $post;
			$post_id = $post->ID;
		}
		$tp_course_duration = get_post_meta( $post_id, 'tp_course_duration_value', true );
		return ! empty( $tp_course_duration ) ? $tp_course_duration : '';
	}
endif;

if( ! function_exists( 'get_tp_course_price' ) ):
	// Course price
	function get_tp_course_price( $post_id = '' ) {
		if ( empty( $post_id ) ) {
			GLOBAL $post;
			$post_id = $post->ID;
		}
		$tp_course_price = get_post_meta( $post_id, 'tp_course_price_value', true );
		return ! empty( $tp_course_price ) ? $tp_course_price : '';
	}
endif;

if( ! function_exists( 'get_tp_course_students' ) ):
	// Course no of students
	function get_tp_course_students( $post_id = '' ) {
		if ( empty( $post_id ) ) {
			GLOBAL $post;
			$post_id = $post->ID;
		}
		$tp_course_students = get_post_meta( $post_id, 'tp_course_students_value', true );
		return ! empty( $tp_course_students ) ? $tp_course_students : '';
	}
endif;

if( ! function_exists( 'get_tp_course_language' ) ):
	// Course language
	function get_tp_course_language( $post_id = '' ) {
		if ( empty( $post_id ) ) {
			GLOBAL $post;
			$post_id = $post->ID;
		}
		$tp_course_language = get_post_meta( $post_id, 'tp_course_language_value', true );
		return ! empty( $tp_course_language ) ? $tp_course_language : '';
	}
endif;

if( ! function_exists( 'get_tp_course_assessment' ) ):
	// Course assessment
	function get_tp_course_assessment( $post_id = '' ) {
		if ( empty( $post_id ) ) {
			GLOBAL $post;
			$post_id = $post->ID;
		}
		$tp_course_assessment = get_post_meta( $post_id, 'tp_course_assessment_value', true );
		return ! empty( $tp_course_assessment ) ? $tp_course_assessment : '';
	}
endif;

if( ! function_exists( 'get_tp_course_skills' ) ):
	// Course skills
	function get_tp_course_skills( $post_id = '' ) {
		if ( empty( $post_id ) ) {
			GLOBAL $post;
			$post_id = $post->ID;
		}
		$tp_course_skills = get_post_meta( $post_id, 'tp_course_skills_value', true );
		return ! empty( $tp_course_skills ) ? $tp_course_skills : '';
	}
endif;

/*
 * Affiliation Details
 */

if( ! function_exists( 'get_tp_affiliation_link' ) ):
	// Affiliation type
	function get_tp_affiliation_link( $post_id = '' ) {
		if ( empty( $post_id ) ) {
			GLOBAL $post;
			$post_id = $post->ID;
		}
		$tp_affiliation_link = get_post_meta( $post_id, 'tp_affiliation_link_value', true );
		return ! empty( $tp_affiliation_link ) ? $tp_affiliation_link : '';
	}
endif;
