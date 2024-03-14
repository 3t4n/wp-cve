<?php
/**
 * TP Education core hooks
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
add_action( 'tp_event_date_action', 'tp_event_date', 10 );
add_action( 'tp_event_start_time_action', 'tp_event_start_time', 10 );
add_action( 'tp_event_end_time_action', 'tp_event_end_time', 10 );
add_action( 'tp_event_location_action', 'tp_event_location', 10 );

/*
 * Class Details
 */
add_action( 'tp_class_cost_action', 'tp_class_cost', 10 );
add_action( 'tp_class_period_action', 'tp_class_period', 10 );
add_action( 'tp_class_size_action', 'tp_class_size', 10 );
add_action( 'tp_class_age_group_action', 'tp_class_age_group', 10 );

/*
 * Excursion Details
 */
add_action( 'tp_excursion_start_date_action', 'tp_excursion_start_date', 10 );
add_action( 'tp_excursion_end_date_action', 'tp_excursion_end_date', 10 );
add_action( 'tp_excursion_location_action', 'tp_excursion_location', 10 );

/*
 * Team Details
 */
add_action( 'tp_team_designation_action', 'tp_team_designation', 10 );
add_action( 'tp_team_email_action', 'tp_team_email', 10 );
add_action( 'tp_team_phone_action', 'tp_team_phone', 10 );
add_action( 'tp_team_skype_action', 'tp_team_skype', 10 );
add_action( 'tp_team_website_action', 'tp_team_website', 10 );
add_action( 'tp_team_courses_action', 'tp_team_courses', 10 );
add_action( 'tp_team_social_action', 'tp_team_social', 10 );

/*
 * Testimonial Details
 */
add_action( 'tp_testimonial_rating_action', 'tp_testimonial_rating', 10 );
add_action( 'tp_testimonial_designation_action', 'tp_testimonial_designation', 10 );
add_action( 'tp_testimonial_social_action', 'tp_testimonial_social', 10 );

/*
 * Course Details
 */
add_action( 'tp_course_type_action', 'tp_course_type', 10 );
add_action( 'tp_course_duration_action', 'tp_course_duration', 10 );
add_action( 'tp_course_price_action', 'tp_course_price', 10 );
add_action( 'tp_course_students_action', 'tp_course_students', 10 );
add_action( 'tp_course_language_action', 'tp_course_language', 10 );
add_action( 'tp_course_assessment_action', 'tp_course_assessment', 10 );
add_action( 'tp_course_skills_action', 'tp_course_skills', 10 );
add_action( 'tp_course_professor_action', 'tp_course_professor', 10 );
add_action( 'tp_course_counselors_action', 'tp_course_counselors', 10 );

/*
 * Affiliation Details
 */
add_action( 'tp_affiliation_link_action', 'tp_affiliation_link', 10 );

/*
 * Meta Details
 */
add_action( 'tp_education_posted_on_action', 'tp_education_posted_on', 10 );

