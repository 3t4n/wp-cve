<?php

// Get Academy LMS Triggers
function adfoin_academylms_get_forms( $form_provider ) {
    if( $form_provider != 'academylms' ) {
        return;
    }

    $triggers = array(
        'enrolledCourse' => __( 'Course Enrolled', 'advanced-form-integration' ),
        'attemptQuiz' => __( 'Quiz Attempted', 'advanced-form-integration' ),
        'completeLesson' => __( 'Lesson Completed', 'advanced-form-integration' ),
        'completeCourse' => __( 'Course Completed', 'advanced-form-integration' ),
        'achieveQuizTarget' => __( 'Quiz Target Achieved', 'advanced-form-integration' )

    );

    return $triggers;
}

// Get Academy LMS Fields
function adfoin_academylms_get_form_fields( $form_provider, $form_id ) {
    if( $form_provider != 'academylms' ) {
        return;
    }

    $fileds = array();

    if ($form_id === 'enrolledCourse') {
        $fields = [
            'course_id' => __('Course ID', 'advanced-form-integration'),
            'course_title' => __('Course Title', 'advanced-form-integration'),
            'course_author' => __('Course Author', 'advanced-form-integration'),
            'student_id' => __('Student ID', 'advanced-form-integration'),
            'student_name' => __('Student Name', 'advanced-form-integration'),
            'maximum_students' => __('Maximum Students', 'advanced-form-integration'),
            'course_curriculum' => __('Course Curriculum', 'advanced-form-integration'),
            'course_duration' => __('Course Duration', 'advanced-form-integration'),
            'course_level' => __('Course Level', 'advanced-form-integration'),
            'course_benefits' => __('Course Benefits', 'advanced-form-integration'),
            'course_requirements' => __('Course Requirements', 'advanced-form-integration'),
            'course_material_included' => __('Course Material Included', 'advanced-form-integration'),
            'course_product_id' => __('Course Product ID', 'advanced-form-integration'),
            'course_price_type' => __('Course Price Type', 'advanced-form-integration'),
        ];
    } elseif ($form_id === 'attemptQuiz' || $form_id === 'achieveQuizTarget') {
        $fields = [
            'attempt_id' => __('Attempt ID', 'advanced-form-integration'),
            'course_id' => __('Course ID', 'advanced-form-integration'),
            'quiz_id' => __('Quiz ID', 'advanced-form-integration'),
            'user_id' => __('User ID', 'advanced-form-integration'),
            'total_questions' => __('Total Questions', 'advanced-form-integration'),
            'total_answered_questions' => __('Total Answered Questions', 'advanced-form-integration'),
            'total_marks' => __('Total Marks', 'advanced-form-integration'),
            'earned_marks' => __('Earned Marks', 'advanced-form-integration'),
            'attempt_started_at' => __('Attempt Started At', 'advanced-form-integration'),
            'attempt_ended_at' => __('Attempt Ended At', 'advanced-form-integration'),
            'attempt_info' => __('Attempt Info', 'advanced-form-integration'),
            'attempt_status' => __('Result Status(Passed/Failed)', 'advanced-form-integration'),
        ];
    
        if ($form_id === 'achieveQuizTarget') {
            $fieldsTmp = [
                'achived_status' => __('Achived Status', 'advanced-form-integration'),
            ];
            $fields = $fields + $fieldsTmp;
        }
    } elseif ($form_id === 'completeLesson') {
        $fields = [
            'lesson_id' => __('Lesson ID', 'advanced-form-integration'),
            'lesson_title' => __('Lesson Title', 'advanced-form-integration'),
            'lesson_description' => __('Lesson Description', 'advanced-form-integration'),
            'lesson_status' => __('Lesson Status', 'advanced-form-integration'),
            'quiz_id' => __('Quiz ID', 'advanced-form-integration'),
            'quiz_title' => __('Quiz Title', 'advanced-form-integration'),
            'quiz_description' => __('Quiz Description', 'advanced-form-integration'),
            'quiz_url' => __('Quiz URL', 'advanced-form-integration'),
            'course_id' => __('Course ID', 'advanced-form-integration'),
            'course_name' => __('Course Name', 'advanced-form-integration'),
            'course_description' => __('Course Description', 'advanced-form-integration'),
            'course_url' => __('Course URL', 'advanced-form-integration'),
            'first_name' => __('First Name', 'advanced-form-integration'),
            'last_name' => __('Last Name', 'advanced-form-integration'),
            'nickname' => __('Nick Name', 'advanced-form-integration'),
            'avatar_url' => __('Avatar URL', 'advanced-form-integration'),
            'user_email' => __('Email', 'advanced-form-integration'),
        ];
    } elseif ($form_id === 'completeCourse') {
        $fields = [
            'course_id' => __('Course ID', 'advanced-form-integration'),
            'course_title' => __('Course Title', 'advanced-form-integration'),
            'first_name' => __('First Name', 'advanced-form-integration'),
            'last_name' => __('Last Name', 'advanced-form-integration'),
            'nickname' => __('Nick Name', 'advanced-form-integration'),
            'avatar_url' => __('Avatar URL', 'advanced-form-integration'),
            'user_email' => __('Email', 'advanced-form-integration'),
        ];
    }

    return $fields;
    
}

// Get User data
function adfoin_academylms_get_userdata( $user_id ) {
    $user_data = array();
    $user      = get_userdata($user_id);

    if( $user ) {
        $user_data['first_name'] = $user->first_name;
        $user_data['last_name']  = $user->last_name;
        $user_data['nickname']   = $user->nickname;
        $user_data['avatar_url'] = get_avatar_url($user_id);
        $user_data['user_email'] = $user->user_email;
        $user_data['user_id']    = $user_id;
    }

    return $user_data;
}

// Send data
function adfoin_academylms_send_data( $saved_records, $posted_data ) {
    $job_queue = get_option( 'adfoin_general_settings_job_queue' );

    foreach ($saved_records as $record) {
        $action_provider = $record['action_provider'];
        if ($job_queue) {
            as_enqueue_async_action( "adfoin_{$action_provider}_job_queue", array(
                'data' => array(
                    'record' => $record,
                    'posted_data' => $posted_data
                )
            ) );
        } else {
            call_user_func("adfoin_{$action_provider}_send_data", $record, $posted_data);
        }
    }
}

add_action( 'academy/course/after_enroll', 'adfoin_academylms_handle_course_enroll', 10, 2 );

// Handle Course Enroll
function adfoin_academylms_handle_course_enroll( $course_id, $enrollment_id ) {
    $integration = new Advanced_Form_Integration_Integration();
    $saved_records = $integration->get_by_trigger( 'academylms', 'enrolledCourse' );

    if( empty( $saved_records ) ) {
        return;
    }

    $posted_data = array(
        'course_id' => $course_id,
        'enrollment_id' => $enrollment_id,
    );

    $author_id = get_post_field( 'post_author', $course_id );
    $author_name = get_the_author_meta( 'display_name', $author_id );
    $student_id = get_post_field( 'post_author', $enrollment_id );
    $student_name = get_the_author_meta( 'display_name', $student_id );

    if( $student_id && $student_name ) {
        $posted_data['student_id'] = $student_id;
        $posted_data['student_name'] = $student_name;
    }

    $course = get_post( $course_id );

    if( $course ) {
        $posted_data['course_id'] = $course_id;
        $posted_data['course_title'] = $course->post_title;
        $posted_data['course_author'] = $author_name;
        $posted_data['maximum_students'] = get_post_meta( $course_id, 'maximum_students', true );
        $posted_data['course_curriculum'] = get_post_meta( $course_id, 'course_curriculum', true );
        $posted_data['course_duration'] = get_post_meta( $course_id, 'course_duration', true );
        $posted_data['course_level'] = get_post_meta( $course_id, 'course_level', true );
        $posted_data['course_benefits'] = get_post_meta( $course_id, 'course_benefits', true );
        $posted_data['course_requirements'] = get_post_meta( $course_id, 'course_requirements', true );
        $posted_data['course_material_included'] = get_post_meta( $course_id, 'course_material_included', true );
        $posted_data['course_product_id'] = get_post_meta( $course_id, 'course_product_id', true );
        $posted_data['course_price_type'] = get_post_meta( $course_id, 'course_price_type', true );
    }

    $posted_data['post_id'] = $enrollment_id;

    adfoin_academylms_send_data( $saved_records, $posted_data );
}

add_action( 'academy_quizzes/api/after_quiz_attempt_finished', 'adfoin_academylms_handle_quiz_attempt', 10, 1 );

// Handle Quiz Attempt
function adfoin_academylms_handle_quiz_attempt( $attempt ) {
    $integration = new Advanced_Form_Integration_Integration();
    $saved_records = $integration->get_by_trigger( 'academylms', 'attemptQuiz' );

    if( empty( $saved_records ) ) {
        return;
    }

    $quiz_id = $attempt->quiz_id;

    if( 'academy_quiz' != get_post_type( $quiz_id ) ) {
        return;
    }

    if( 'pending' == $attempt->attempt_status ) {
        return;
    }

    $posted_data = array(
        'attempt_id' => $attempt->id ?? null,
        'course_id' => $attempt->course_id ?? null,
        'quiz_id' => $attempt->quiz_id ?? null,
        'user_id' => $attempt->user_id ?? null,
        'total_questions' => $attempt->total_questions ?? null,
        'total_answered_questions' => $attempt->total_answered_questions ?? null,
        'total_marks' => $attempt->total_marks ?? null,
        'earned_marks' => $attempt->earned_marks ?? null,
        'attempt_started_at' => $attempt->attempt_started_at ?? null,
        'attempt_ended_at' => $attempt->attempt_ended_at ?? null,
        'attempt_info' => $attempt->attempt_info ?? null,
        'attempt_status' => $attempt->attempt_status ?? null,
    );

    $posted_data['post_id'] = $attempt->id;

    adfoin_academylms_send_data( $saved_records, $posted_data );
}

add_action( 'academy/frontend/after_mark_topic_complete', 'adfoin_academylms_handle_lesson_complete', 10, 4 );

// Handle Lesson Complete
function adfoin_academylms_handle_lesson_complete( $lesson_id, $lesson_status, $quiz_id, $course_id ) {
    $integration = new Advanced_Form_Integration_Integration();
    $saved_records = $integration->get_by_trigger( 'academylms', 'completeLesson' );

    if( empty( $saved_records ) ) {
        return;
    }

    $posted_data = array(
        'lesson_id' => $lesson_id,
        'lesson_status' => $lesson_status,
        'quiz_id' => $quiz_id,
        'course_id' => $course_id,
    );

    $lesson = get_post( $lesson_id );

    if( $lesson ) {
        $posted_data['lesson_title'] = $lesson->post_title;
        $posted_data['lesson_description'] = $lesson->post_content;
    }

    $quiz = get_post( $quiz_id );

    if( $quiz ) {
        $posted_data['quiz_title'] = $quiz->post_title;
        $posted_data['quiz_description'] = $quiz->post_content;
        $posted_data['quiz_url'] = get_permalink( $quiz_id );
    }

    $course = get_post( $course_id );

    if( $course ) {
        $posted_data['course_name'] = $course->post_title;
        $posted_data['course_description'] = $course->post_content;
        $posted_data['course_url'] = get_permalink( $course_id );
    }

    $user_data = adfoin_academylms_get_userdata( get_current_user_id() );

    if( $user_data ) {
        $posted_data = array_merge( $posted_data, $user_data );
    }

    $posted_data['post_id'] = $lesson_id;

    adfoin_academylms_send_data( $saved_records, $posted_data );
}

add_action( 'academy/admin/course_complete_after', 'adfoin_academylms_handle_course_complete', 10, 1 );

// Handle Course Complete
function adfoin_academylms_handle_course_complete( $course_id ) {
    $integration = new Advanced_Form_Integration_Integration();
    $saved_records = $integration->get_by_trigger( 'academylms', 'completeCourse' );

    if( empty( $saved_records ) ) {
        return;
    }

    $posted_data = array(
        'course_id' => $course_id,
    );

    $course = get_post( $course_id );

    if( $course ) {
        $posted_data['course_title'] = $course->post_title;
        $posted_data['course_url'] = $course->guid;
    }

    $user_data = adfoin_academylms_get_userdata( get_current_user_id() );

    if( $user_data ) {
        $posted_data = array_merge( $posted_data, $user_data );
    }

    $posted_data['post_id'] = $course_id;

    adfoin_academylms_send_data( $saved_records, $posted_data );
}

add_action( 'academy_quizzes/api/after_quiz_attempt_finished', 'adfoin_academylms_handle_quiz_target', 10, 1 );

// Handle Quiz Target
function adfoin_academylms_handle_quiz_target( $attempt ) {
    $integration = new Advanced_Form_Integration_Integration();
    $saved_records = $integration->get_by_trigger( 'academylms', 'achieveQuizTarget' );

    if( empty( $saved_records ) ) {
        return;
    }

    $quiz_id = $attempt->quiz_id;

    if( 'academy_quiz' != get_post_type( $quiz_id ) ) {
        return;
    }

    if( 'pending' == $attempt->attempt_status ) {
        return;
    }

    $posted_data = array(
        'attempt_id' => $attempt->id ?? null,
        'course_id' => $attempt->course_id ?? null,
        'quiz_id' => $attempt->quiz_id ?? null,
        'user_id' => $attempt->user_id ?? null,
        'total_questions' => $attempt->total_questions ?? null,
        'total_answered_questions' => $attempt->total_answered_questions ?? null,
        'total_marks' => $attempt->total_marks ?? null,
        'earned_marks' => $attempt->earned_marks ?? null,
        'attempt_started_at' => $attempt->attempt_started_at ?? null,
        'attempt_ended_at' => $attempt->attempt_ended_at ?? null,
        'attempt_info' => $attempt->attempt_info ?? null,
        'attempt_status' => $attempt->attempt_status ?? null,
        'achived_status' => $attempt->achived_status ?? null,
    );
    

    $posted_data['post_id'] = $attempt->id;

    adfoin_academylms_send_data( $saved_records, $posted_data );
}

