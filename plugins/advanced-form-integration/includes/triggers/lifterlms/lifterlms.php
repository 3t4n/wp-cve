<?php

// Get Lifter LMS Triggers
function adfoin_lifterlms_get_forms( $form_provider ) {
    if( $form_provider != 'lifterlms' ) {
        return;
    }

    $triggers = array(
        'quizAttempted' => __( 'Quiz Attempted', 'advanced-form-integration' ),
        'quizPassed' => __( 'Quiz Passed', 'advanced-form-integration' ),
        'quizFailed' => __( 'Quiz Failed', 'advanced-form-integration' ),
        'lessonCompleted' => __( 'Lesson Completed', 'advanced-form-integration' ),
        'courseCompleted' => __( 'Course Completed', 'advanced-form-integration' ),
        'courseEnrolled' => __( 'Course Enrolled', 'advanced-form-integration' ),
        'courseUnEnrolled' => __( 'Course Unenrolled', 'advanced-form-integration' ),
        'membershipCancelled' => __( 'Membership Cancelled', 'advanced-form-integration' ),
    );

    return $triggers;
}

// Get Lifter LMS Fields
function adfoin_lifterlms_get_form_fields( $form_provider, $form_id ) {

    if( $form_provider != 'lifterlms' ) {
        return;
    }

    $fields = array(
        'first_name' => __( 'First Name', 'advanced-form-integration' ),
        'last_name' => __( 'Last Name', 'advanced-form-integration' ),
        'nickname' => __( 'Nick Name', 'advanced-form-integration' ),
        'avatar_url' => __( 'Avatar URL', 'advanced-form-integration' ),
        'user_email' => __( 'Email', 'advanced-form-integration' )
    );

    if( in_array( $form_id, array( 'quizAttempted', 'quizPassed', 'quizFailed' ) ) ) {
        $fields = array_merge( $fields, array(
            'user_id' => __( 'User Id', 'advanced-form-integration' ),
            'quiz_id' => __( 'Quiz Id', 'advanced-form-integration' ),
            'quiz_title' => __( 'Quiz Title', 'advanced-form-integration' )
        ) );
    } elseif( in_array( $form_id, array( 'lessonCompleted' ) ) ) {
        $fields = array_merge( $fields, array(
            'user_id' => __( 'User Id', 'advanced-form-integration' ),
            'lesson_title' => __( 'Lesson Title', 'advanced-form-integration' ),
            'lesson_id' => __( 'Lesson Id', 'advanced-form-integration' )
        ) );
    } elseif( in_array( $form_id, array( 'courseCompleted', 'courseEnrolled', 'courseUnEnrolled' ) ) ) {
        $fields = array_merge( $fields, array(
            'user_id' => __( 'User Id', 'advanced-form-integration' ),
            'course_title' => __( 'Course Title', 'advanced-form-integration' ),
            'course_id' => __( 'Course Id', 'advanced-form-integration' )
        ) );
    } elseif( in_array( $form_id, array( 'membershipCancelled' ) ) ) {
        $fields = array_merge( $fields, array(
            'user_id' => __( 'User Id', 'advanced-form-integration' ),
            'membership_title' => __( 'Membership Title', 'advanced-form-integration' ),
            'membership_id' => __( 'Membership Id', 'advanced-form-integration' )
        ) );
    }

    return $fields;
}

// Get User Data
function adfoin_lifterlms_get_userdata( $user_id ) {
    $user = get_userdata( $user_id );
    $user_data = array(
        'first_name' => $user->first_name,
        'last_name' => $user->last_name,
        'nickname' => $user->nickname,
        'avatar_url' => get_avatar_url( $user_id ),
        'user_email' => $user->user_email
    );

    return $user_data;
}

// Send Data
function adfoin_lifterlms_send_data( $saved_records, $posted_data ) {
    $job_queue = get_option( 'adfoin_general_settings_job_queue' );

    foreach( $saved_records as $record ) {
        $action_provider = $record['action_provider'];

        if( $job_queue ) {
            as_enqueue_async_action( "adfoin_{$action_provider}_job_queue", array(
                'data' => array(
                    'record' => $record,
                    'posted_data' => $posted_data
                )
            ) );
        } else {
            call_user_func( "adfoin_{$action_provider}_send_data", $record, $posted_data );
        }
    }
}

add_action( 'lifterlms_quiz_completed', 'adfoin_lifterlms_quiz_completed', 10, 3 );

function adfoin_lifterlms_quiz_completed( $user_id, $quiz_id, $attempt_id ) {
    $integration   = new Advanced_Form_Integration_Integration();
    $saved_records = $integration->get_by_trigger( 'lifterlms', 'quizAttempted' );

    if( empty( $saved_records ) ) {
        return;
    }

    $user_data = adfoin_lifterlms_get_userdata( $user_id );
    $quiz = new LLMS_Quiz( $quiz_id );
    $quiz_data = array(
        'user_id' => $user_id,
        'quiz_id' => $quiz_id,
        'quiz_title' => $quiz->get( 'title' )
    );

    $posted_data = array_merge( $user_data, $quiz_data );

    adfoin_lifterlms_send_data( $saved_records, $posted_data );
}

add_action( 'lifterlms_quiz_passed', 'adfoin_lifterlms_quiz_passed', 10, 3 );

function adfoin_lifterlms_quiz_passed( $user_id, $quiz_id, $quiz ) {
    $integration   = new Advanced_Form_Integration_Integration();
    $saved_records = $integration->get_by_trigger( 'lifterlms', 'quizPassed' );

    if( empty( $saved_records ) ) {
        return;
    }

    $user_data = adfoin_lifterlms_get_userdata( $user_id );
    $quiz = new LLMS_Quiz( $quiz_id );
    $quiz_data = array(
        'user_id' => $user_id,
        'quiz_id' => $quiz_id,
        'quiz_title' => $quiz->get( 'title' )
    );

    $posted_data = array_merge( $user_data, $quiz_data );

    adfoin_lifterlms_send_data( $saved_records, $posted_data );
}

add_action( 'lifterlms_quiz_failed', 'adfoin_lifterlms_quiz_failed', 10, 3 );

function adfoin_lifterlms_quiz_failed( $user_id, $quiz_id, $quiz ) {
    $integration   = new Advanced_Form_Integration_Integration();
    $saved_records = $integration->get_by_trigger( 'lifterlms', 'quizFailed' );

    if( empty( $saved_records ) ) {
        return;
    }

    $user_data = adfoin_lifterlms_get_userdata( $user_id );
    $quiz = new LLMS_Quiz( $quiz_id );
    $quiz_data = array(
        'user_id' => $user_id,
        'quiz_id' => $quiz_id,
        'quiz_title' => $quiz->get( 'title' )
    );

    $posted_data = array_merge( $user_data, $quiz_data );

    adfoin_lifterlms_send_data( $saved_records, $posted_data );
}

add_action( 'lifterlms_lesson_completed', 'adfoin_lifterlms_lesson_completed', 10, 2 );

function adfoin_lifterlms_lesson_completed( $user_id, $lesson_id ) {
    $integration   = new Advanced_Form_Integration_Integration();
    $saved_records = $integration->get_by_trigger( 'lifterlms', 'lessonCompleted' );

    if( empty( $saved_records ) ) {
        return;
    }

    $user_data = adfoin_lifterlms_get_userdata( $user_id );
    $lesson = new LLMS_Lesson( $lesson_id );
    $lesson_data = array(
        'user_id' => $user_id,
        'lesson_title' => $lesson->get( 'title' ),
        'lesson_id' => $lesson_id
    );

    $posted_data = array_merge( $user_data, $lesson_data );

    adfoin_lifterlms_send_data( $saved_records, $posted_data );
}

add_action( 'lifterlms_course_completed', 'adfoin_lifterlms_course_completed', 10, 2 );

function adfoin_lifterlms_course_completed( $user_id, $course_id ) {
    $integration   = new Advanced_Form_Integration_Integration();
    $saved_records = $integration->get_by_trigger( 'lifterlms', 'courseCompleted' );

    if( empty( $saved_records ) ) {
        return;
    }

    $user_data = adfoin_lifterlms_get_userdata( $user_id );
    $course = new LLMS_Course( $course_id );
    $course_data = array(
        'user_id' => $user_id,
        'course_title' => $course->get( 'title' ),
        'course_id' => $course_id
    );

    $posted_data = array_merge( $user_data, $course_data );

    adfoin_lifterlms_send_data( $saved_records, $posted_data );
}

add_action( 'llms_user_enrolled_in_course', 'adfoin_llms_user_enrolled_in_course', 10, 2 );

function adfoin_llms_user_enrolled_in_course( $user_id, $course_id ) {
    $integration   = new Advanced_Form_Integration_Integration();
    $saved_records = $integration->get_by_trigger( 'lifterlms', 'courseEnrolled' );

    if( empty( $saved_records ) ) {
        return;
    }

    $user_data = adfoin_lifterlms_get_userdata( $user_id );
    $course = new LLMS_Course( $course_id );
    $course_data = array(
        'user_id' => $user_id,
        'course_title' => $course->get( 'title' ),
        'course_id' => $course_id
    );

    $posted_data = array_merge( $user_data, $course_data );

    adfoin_lifterlms_send_data( $saved_records, $posted_data );
}

add_action( 'llms_user_removed_from_course', 'adfoin_llms_user_removed_from_course', 10, 4 );

function adfoin_llms_user_removed_from_course( $user_id, $course_id, $status, $old_status ) {
    $integration   = new Advanced_Form_Integration_Integration();
    $saved_records = $integration->get_by_trigger( 'lifterlms', 'courseUnEnrolled' );

    if( empty( $saved_records ) ) {
        return;
    }

    $user_data = adfoin_lifterlms_get_userdata( $user_id );
    $course = new LLMS_Course( $course_id );
    $course_data = array(
        'user_id' => $user_id,
        'course_title' => $course->get( 'title' ),
        'course_id' => $course_id
    );

    $posted_data = array_merge( $user_data, $course_data );

    adfoin_lifterlms_send_data( $saved_records, $posted_data );
}

add_action( 'llms_subscription_cancelled_by_student', 'adfoin_llms_subscription_cancelled_by_student', 10, 4 );

function adfoin_llms_subscription_cancelled_by_student( $user_id, $subscription_id, $subscription, $old_status ) {
    $integration   = new Advanced_Form_Integration_Integration();
    $saved_records = $integration->get_by_trigger( 'lifterlms', 'membershipCancelled' );

    if( empty( $saved_records ) ) {
        return;
    }

    $user_data = adfoin_lifterlms_get_userdata( $user_id );
    $membership = new LLMS_Access_Plan( $subscription_id );
    $membership_data = array(
        'user_id' => $user_id,
        'membership_title' => $membership->get( 'title' ),
        'membership_id' => $subscription_id
    );

    $posted_data = array_merge( $user_data, $membership_data );

    adfoin_lifterlms_send_data( $saved_records, $posted_data );
}