<?php

if ( !defined( "ABSPATH" ) ) exit;

/**
 * Upgrade function
 */
function al_upgrade () {
    // upgrade process
}

/**
 * Checks if a parent course or child course
 */
function is_parent_course ( $course ) {
	if ( !$course->ID ) {
		return false;
	}

	$course_prerequisite = array();

	if( learndash_get_course_prerequisite_enabled($course->ID) ) {
        $course_prerequisite = learndash_get_course_prerequisite($course->ID);
    }

	return empty($course_prerequisite);
}

/**
 * Fetches quiz IDs by course ID
 */
function get_quiz_ids_for_course ( $id ) {
	
	if ( !$id ) {
		return false;
	}
	
	$arr = array();
	$quizzes = learndash_get_course_quiz_list( $id, null );
	if ( is_array( $quizzes ) && count( $quizzes ) > 0 ) {
		foreach ( $quizzes as $quiz ) {
			$quiz_rec = $quiz['post'];
			$arr[] = $quiz_rec->ID;
		}
	}
	
	$lessons = learndash_get_lesson_list( $id );
	if( is_array( $lessons ) && count( $lessons ) > 0 ) {
		foreach( $lessons as $lesson ) {
			$topics  = learndash_get_topic_list( $lesson->ID, $id ); 
			if( is_array( $topics ) && count( $topics ) > 0 ) {
				foreach ( $topics as $topic ) {
					$quizzes = learndash_get_lesson_quiz_list( $topic->ID, null, $id );
					if ( is_array( $quizzes ) && count( $quizzes ) > 0 ) {
						foreach ( $quizzes as $quiz ) {
							$quiz_post = $quiz['post'];
							$arr[] = $quiz_post->ID;
						}
					}
				}
			}

			$quizzes = learndash_get_lesson_quiz_list( $lesson->ID, null, $id );
			if ( is_array( $quizzes ) && count( $quizzes ) > 0 ) {
				foreach ( $quizzes as $quiz ) {
					$quiz_post = $quiz['post'];
					$arr[] = $quiz_post->ID;
				}
			}
		}
	}
	return $arr;
}

function debug_log($var, $print=true) {
    ob_start();
    if( $print ) {
        if( is_object($var) || is_array($var) ) {
            echo print_r($var, true);
        } else {
            echo $var;
        }
    } else {
        var_dump($var);
    }
    error_log(ob_get_clean());
}