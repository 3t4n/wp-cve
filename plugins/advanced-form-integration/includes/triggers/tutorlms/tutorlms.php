<?php

// Get Tutor LMS triggers
function adfoin_tutorlms_get_forms( $form_provider ) {
    if( $form_provider != 'tutorlms' ) {
        return;
    }

    $triggers = array(
        'courseEnrolled' => __( 'Course Enrolled', 'advanced-form-integration' ),
        'quizAttempted' => __( 'Quiz Attempted', 'advanced-form-integration' ),
        'lessonCompleted' => __( 'Lesson Completed', 'advanced-form-integration' ),
        'courseCompleted' => __( 'Course Completed', 'advanced-form-integration' ),
    );

    return $triggers;
}

// Get Tutor LMS fields
function adfoin_tutorlms_get_form_fields( $form_provider, $form_id ) {

    if( $form_provider != 'tutorlms' ) {
        return;
    }

    $fields = array();

    if( in_array( $form_id, array( 'courseEnrolled' ) ) ) {
        $fields = array_merge( $fields, array(
            'course_id' => __( 'Course ID', 'advanced-form-integration' ),
            'course_title' => __( 'Course Title', 'advanced-form-integration' ),
            'course_author' => __( 'Course Author ID', 'advanced-form-integration' ),
            'course_author_name' => __( 'Course Author Name', 'advanced-form-integration' ),
            'course_author_email' => __( 'Course Author Email', 'advanced-form-integration' ),
            'student_id' => __( 'Student ID', 'advanced-form-integration' ),
            'student_name' => __( 'Student Name', 'advanced-form-integration' ),
            'student_first_name' => __( 'Student First Name', 'advanced-form-integration' ),
            'student_last_name' => __( 'Student Last Name', 'advanced-form-integration' ),
            'student_email' => __( 'Student Email', 'advanced-form-integration' ),
            'maximum_students' => __( 'Maximum Students', 'advanced-form-integration' ),
            'course_duration' => __( 'Course Duration', 'advanced-form-integration' ),
            'tutor_course_level' => __( 'Course Level', 'advanced-form-integration' ),
            'tutor_course_benefits' => __( 'Course Benefits', 'advanced-form-integration' ),
            'tutor_course_requirements' => __( 'Course Requirements', 'advanced-form-integration' ),
            'tutor_course_material_includes' => __( 'Course Material Includes', 'advanced-form-integration' ),
            // 'tutor_course_product_id' => __( 'Course Product ID', 'advanced-form-integration' ),
            // 'tutor_course_price_type' => __( 'Course Price Type', 'advanced-form-integration' ),
        ) );
    } elseif( in_array( $form_id, array( 'quizAttempted' ) ) ) {
        $fields = array_merge( $fields, array(
            'attempt_id' => __( 'Attempt ID', 'advanced-form-integration' ),
            'quiz_id' => __( 'Quiz ID', 'advanced-form-integration' ),
            'course_id' => __( 'Course ID', 'advanced-form-integration' ),
            'course_name' => __( 'Course Name', 'advanced-form-integration' ),
            'course_url' => __( 'Course URL', 'advanced-form-integration' ),
            'course_description' => __( 'Course Description', 'advanced-form-integration' ),
            'course_author' => __( 'Course Author ID', 'advanced-form-integration' ),
            'course_author_name' => __( 'Course Author Name', 'advanced-form-integration' ),
            'course_author_email' => __( 'Course Author Email', 'advanced-form-integration' ),
            'user_id' => __( 'User ID', 'advanced-form-integration' ),
            'total_questions' => __( 'Total Questions', 'advanced-form-integration' ),
            'total_answered_questions' => __( 'Total Answered Questions', 'advanced-form-integration' ),
            'total_marks' => __( 'Total Marks', 'advanced-form-integration' ),
            'earned_marks' => __( 'Earned Marks', 'advanced-form-integration' ),
            'attempt_started_at' => __( 'Attempt Started At', 'advanced-form-integration' ),
            'attempt_ended_at' => __( 'Attempt Ended At', 'advanced-form-integration' ),
            'passing_grade' => __( 'Passing Grade', 'advanced-form-integration' ),
            'result_status' => __( 'Result Status', 'advanced-form-integration' ),
        ) );
    } elseif( in_array( $form_id, array( 'lessonCompleted' ) ) ) {
        $fields = array_merge( $fields, array(
            'lesson_id' => __( 'Lesson ID', 'advanced-form-integration' ),
            'lesson_title' => __( 'Lesson Title', 'advanced-form-integration' ),
            'lesson_description' => __( 'Lesson Description', 'advanced-form-integration' ),
            'lesson_url' => __( 'Lesson URL', 'advanced-form-integration' ),
            'topic_id' => __( 'Topic ID', 'advanced-form-integration' ),
            'topic_title' => __( 'Topic Title', 'advanced-form-integration' ),
            'topic_description' => __( 'Topic Description', 'advanced-form-integration' ),
            'topic_url' => __( 'Topic URL', 'advanced-form-integration' ),
            'course_id' => __( 'Course ID', 'advanced-form-integration' ),
            'course_name' => __( 'Course Name', 'advanced-form-integration' ),
            'course_url' => __( 'Course URL', 'advanced-form-integration' ),
            'course_description' => __( 'Course Description', 'advanced-form-integration' ),
            'course_author' => __( 'Course Author ID', 'advanced-form-integration' ),
            'course_author_name' => __( 'Course Author Name', 'advanced-form-integration' ),
            'course_author_email' => __( 'Course Author Email', 'advanced-form-integration' ),
            'student_id' => __( 'Student ID', 'advanced-form-integration' ),
            'student_name' => __( 'Student Name', 'advanced-form-integration' ),
            'student_first_name' => __( 'Student First Name', 'advanced-form-integration' ),
            'student_last_name' => __( 'Student Last Name', 'advanced-form-integration' ),
            'student_email' => __( 'Student Email', 'advanced-form-integration' )
        ) );
    } elseif( in_array( $form_id, array( 'courseCompleted' ) ) ) {
        $fields = array_merge( $fields, array(
            'course_id' => __( 'Course ID', 'advanced-form-integration' ),
            'course_name' => __( 'Course Name', 'advanced-form-integration' ),
            'course_author' => __( 'Course Author ID', 'advanced-form-integration' ),
            'course_author_name' => __( 'Course Author Name', 'advanced-form-integration' ),
            'course_author_email' => __( 'Course Author Email', 'advanced-form-integration' ),
            'student_id' => __( 'Student ID', 'advanced-form-integration' ),
            'student_name' => __( 'Student Name', 'advanced-form-integration' ),
            'student_first_name' => __( 'Student First Name', 'advanced-form-integration' ),
            'student_last_name' => __( 'Student Last Name', 'advanced-form-integration' ),
            'student_email' => __( 'Student Email', 'advanced-form-integration' )
        ) );
    }

    return $fields;
}

// Get User Data
function adfoin_tutorlms_get_userdata( $user_id ) {
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
function adfoin_tutorlms_send_data( $saved_records, $posted_data ) {
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

add_action( 'tutor_after_enrolled', 'adfoin_tutorlms_handle_course_enroll', 10, 3 );

function adfoin_tutorlms_handle_course_enroll( $course_id, $user_id, $enrollment_id ) {
    $integration   = new Advanced_Form_Integration_Integration();
    $saved_records = $integration->get_by_trigger( 'tutorlms', 'courseEnrolled' );

    if( empty( $saved_records ) ) {
        return;
    }

    $course = get_post( $course_id );
    $student = get_userdata( $user_id );
    $author = adfoin_tutorlms_get_userdata( $course->post_author );

    $course_data = array(
        'course_id' => $course_id,
        'course_title' => $course->post_title,
        'course_author' => $course->post_author,
        'course_author_name' => $author['first_name'] . ' ' . $author['last_name'],
        'course_author_email' => $author['user_email'],
        'student_id' => $user_id,
        'student_name' => $student->display_name,
        'student_first_name' => $student->first_name,
        'student_last_name' => $student->last_name,
        'student_email' => $student->user_email,
        'maximum_students' => get_post_meta( $course_id, '_tutor_course_settings', true )['maximum_students'] ?? '',
        'course_duration' => get_post_meta( $course_id, '_course_duration', true ),
        'course_level' => get_post_meta( $course_id, '_tutor_course_level', true ),
        'course_benefits' => get_post_meta( $course_id, '_tutor_course_benefits', true ),
        'course_requirements' => get_post_meta( $course_id, '_tutor_course_requirements', true ),
        'course_material_includes' => get_post_meta( $course_id, '_tutor_course_material_includes', true )
    );

    adfoin_tutorlms_send_data( $saved_records, $course_data );
    
}

add_action( 'tutor_quiz/attempt_ended', 'adfoin_tutorlms_handle_quiz_attempt', 10, 1 );

function adfoin_tutorlms_handle_quiz_attempt( $attempt_id ) {
    $integration   = new Advanced_Form_Integration_Integration();
    $saved_records = $integration->get_by_trigger( 'tutorlms', 'quizAttempted' );

    if( empty( $saved_records ) ) {
        return;
    }

    $attempt = tutor_utils()->get_attempt( $attempt_id );
    $quiz_id = $attempt->quiz_id;

    if( get_post_type( $quiz_id ) !== 'tutor_quiz' && $attempt->attempt_status !== 'attempt_ended' ) {
        return;
    }

    $quiz = get_post( $quiz_id );
    $topic = get_post( $quiz->post_parent );
    $course = get_post( $attempt->course_id );
    $student = get_userdata( $attempt->user_id );
    $author = adfoin_tutorlms_get_userdata( $course->post_author );
    $attempt_info = array();

    if( isset( $attempt->attempt_info ) ) {
        $attempt_info = maybe_unserialize( $attempt->attempt_info );
    }

    $attempt_data = array(
        'attempt_id' => $attempt_id,
        'quiz_id' => $quiz->ID,
        'quiz_title' => $quiz->post_title,
        'topic_id' => $topic->ID,
        'topic_title' => $topic->post_title,
        'course_id' => $course->ID,
        'course_name' => $course->post_title,
        'course_url' => get_the_permalink( $course->ID ),
        'course_description' => $course->post_content,
        'course_author' => $course->post_author,
        'course_author_name' => $author['first_name'] . ' ' . $author['last_name'],
        'course_author_email' => $author['user_email'],
        'total_questions' => $attempt->total_questions,
        'total_answered_questions' => $attempt->total_answered_questions,
        'total_marks' => $attempt->total_marks,
        'earned_marks' => $attempt->earned_marks,
        'attempt_started_at' => $attempt->attempt_started_at,
        'attempt_ended_at' => $attempt->attempt_ended_at,
        'student_id' => $student->ID,
        'student_name' => $student->display_name,
        'student_first_name' => $student->first_name,
        'student_last_name' => $student->last_name,
        'student_email' => $student->user_email,
        'passing_grade' => isset( $attempt_info['passing_grade'] ) ? $attempt_info['passing_grade'] : '',
    );

    adfoin_tutorlms_send_data( $saved_records, $attempt_data );
}

add_action( 'tutor_lesson_completed_after', 'adfoin_tutorlms_handle_lesson_complete', 10, 1 );

function adfoin_tutorlms_handle_lesson_complete( $lesson_id ) {
    $integration   = new Advanced_Form_Integration_Integration();
    $saved_records = $integration->get_by_trigger( 'tutorlms', 'lessonCompleted' );

    if( empty( $saved_records ) ) {
        return;
    }

    $lesson = get_post( $lesson_id );
    $topic = get_post( $lesson->post_parent );
    $course = get_post( $topic->post_parent );
    $student = get_userdata( get_current_user_id() );
    $author = adfoin_tutorlms_get_userdata( $course->post_author );

    $lesson_data = array(
        'lesson_id' => $lesson_id,
        'lesson_title' => $lesson->post_title,
        'lesson_description' => $lesson->post_content,
        'lesson_url' => get_the_permalink( $lesson_id ),
        'topic_id' => $topic->ID,
        'topic_title' => $topic->post_title,
        'topic_description' => $topic->post_content,
        'topic_url' => get_the_permalink( $topic->ID ),
        'course_id' => $course->ID,
        'course_name' => $course->post_title,
        'course_url' => get_the_permalink( $course->ID ),
        'course_description' => $course->post_content,
        'course_author' => $course->post_author,
        'course_author_name' => $author['first_name'] . ' ' . $author['last_name'],
        'course_author_email' => $author['user_email'],
        'student_id' => $student->ID,
        'student_name' => $student->display_name,
        'student_first_name' => $student->first_name,
        'student_last_name' => $student->last_name,
        'student_email' => $student->user_email,
    );

    $posted_data = array_merge( $lesson_data, adfoin_tutorlms_get_userdata( get_current_user_id() ) );

    adfoin_tutorlms_send_data( $saved_records, $posted_data );
}

add_action( 'tutor_course_complete_after', 'adfoin_tutorlms_handle_course_complete', 10, 1 );

function adfoin_tutorlms_handle_course_complete( $course_id ) {
    $integration   = new Advanced_Form_Integration_Integration();
    $saved_records = $integration->get_by_trigger( 'tutorlms', 'courseCompleted' );

    if( empty( $saved_records ) ) {
        return;
    }

    $course = get_post( $course_id );
    $student = get_userdata( get_current_user_id() );
    $author = adfoin_tutorlms_get_userdata( $course->post_author );

    $course_data = array(
        'course_id' => $course_id,
        'course_name' => $course->post_title,
        'course_author' => $course->post_author,
        'course_author_name' => $author['first_name'] . ' ' . $author['last_name'],
        'course_author_email' => $author['user_email'],
        'student_id' => $student->ID,
        'student_name' => $student->display_name,
        'student_first_name' => $student->first_name,
        'student_last_name' => $student->last_name,
        'student_email' => $student->user_email
    );

    adfoin_tutorlms_send_data( $saved_records, $course_data );
}
