<?php  
/**
 * Search Content.
 *
 * @package TP Education
 * @since 1.0
 */

add_action( 'tp_education_search_content_action', 'tp_education_search_content', 10 );
function tp_education_search_content() {

	switch ( get_query_var( 'post_type' ) ) {
		case 'tp-event':
			/**
			 * Hook - tp_education_archive_event_content_action.
			 *
			 * @hooked tp_education_archive_event_content -  10
			 */
			do_action( 'tp_education_archive_event_content_action' );
		break;

		case 'tp-excursion':
			/**
			 * Hook - tp_education_archive_excursion_content_action.
			 *
			 * @hooked tp_education_archive_excursion_content -  10
			 */
			do_action( 'tp_education_archive_excursion_content_action' );
		break;

		case 'tp-course':
			/**
			 * Hook - tp_education_archive_course_content_action.
			 *
			 * @hooked tp_education_archive_course_content -  10
			 */
			do_action( 'tp_education_archive_course_content_action' );
		break;

		case 'tp-team':
			/**
			 * Hook - tp_education_archive_team_content_action.
			 *
			 * @hooked tp_education_archive_team_content -  10
			 */
			do_action( 'tp_education_archive_team_content_action' );
		break;

		case 'tp-class':
			/**
			 * Hook - tp_education_archive_class_content_action.
			 *
			 * @hooked tp_education_archive_class_content -  10
			 */
			do_action( 'tp_education_archive_class_content_action' );
		break;

		case 'tp-affiliation':
			/**
			 * Hook - tp_education_archive_affiliation_content_action.
			 *
			 * @hooked tp_education_archive_affiliation_content -  10
			 */
			do_action( 'tp_education_archive_affiliation_content_action' );
		break;
		
		default:
		break;
	}
}
?>