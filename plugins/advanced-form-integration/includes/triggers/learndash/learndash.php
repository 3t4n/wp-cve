<?php
/*
 * Get LearnDash triggers
 */
function adfoin_learndash_get_forms( $form_provider ) {
    if ( $form_provider != 'learndash' ) {
        return;
    }

    $triggers = array(
        'enrolledCourse'      => __( 'User enrolled in a course', 'advanced-form-integration' ),
        'unenrolledCourse'    => __( 'User unenrolled from a course', 'advanced-form-integration' ),
        'completedCourse'     => __( 'User completed a course', 'advanced-form-integration' ),
        'completedLesson'     => __( 'User completed a lesson', 'advanced-form-integration' ),
        'completedTopic'      => __( 'User completed a topic', 'advanced-form-integration' ),
        'attemptedQuiz'       => __( 'User attempted (pass or fail) a quiz', 'advanced-form-integration' ),
        'passedQuiz'          => __( 'User passed a quiz', 'advanced-form-integration' ),
        'failedQuiz'          => __( 'User failed a quiz', 'advanced-form-integration' ),
        'addedToGroup'        => __( 'User added to a group', 'advanced-form-integration' ),
        'removedFromGroup'    => __( 'User removed from a group', 'advanced-form-integration' ),
        'submittedAssignment' => __( 'User submitted assignment for a lesson', 'advanced-form-integration' ),
    );

    return $triggers;
}

/*
 * Get LearnDash fields
 */
function adfoin_learndash_get_form_fields( $form_provider, $form_id ) {
    if( $form_provider != 'learndash' ) {
        return;
    }

    $fields = array();

    if( in_array( $form_id, array( 'enrolledCourse', 'unenrolledCourse', 'completedCourse', 'completedLesson', 'completedTopic', 'attemptedQuiz', 'passedQuiz', 'failedQuiz', 'submittedAssignment' ) ) ) {
        $fields['course_id']    = __( 'Course ID', 'advanced-form-integration' );
        $fields['course_title'] = __( 'Course Title', 'advanced-form-integration' );
        $fields['course_url']   = __( 'Course URL', 'advanced-form-integration' );
    }

    if( in_array( $form_id, array( 'addedToGroup', 'removedFromGroup' ) ) ) {
        $fields['group_id']    = __( 'Group ID', 'advanced-form-integration' );
        $fields['group_title'] = __( 'Group Title', 'advanced-form-integration' );
        $fields['group_url']   = __( 'Group URL', 'advanced-form-integration' );
    }

    if( in_array( $form_id, array( 'completedLesson', 'completedTopic', 'attemptedQuiz', 'passedQuiz', 'failedQuiz', 'submittedAssignment' ) ) ) {
        $fields['lesson_id']    = __( 'Lesson ID', 'advanced-form-integration' );
        $fields['lesson_title'] = __( 'Lesson Title', 'advanced-form-integration' );
        $fields['lesson_url']   = __( 'Lesson URL', 'advanced-form-integration' );
    }

    if( in_array( $form_id, array( 'completedTopic' ) ) ) {
        $fields['topic_id']    = __( 'Topic ID', 'advanced-form-integration' );
        $fields['topic_title'] = __( 'Topic Title', 'advanced-form-integration' );
        $fields['topic_url']   = __( 'Topic URL', 'advanced-form-integration' );
    }

    if( in_array( $form_id, array( 'attemptedQuiz', 'passedQuiz', 'failedQuiz' ) ) ) {
        $fields['quiz_id']      = __( 'Quiz ID', 'advanced-form-integration' );
        $fields['quiz_title']   = __( 'Quiz Title', 'advanced-form-integration' );
        $fields['quiz_url']     = __( 'Quiz URL', 'advanced-form-integration' );
        $fields['score']        = __( 'Score', 'advanced-form-integration' );
        $fields['pass']         = __( 'Pass', 'advanced-form-integration' );
        $fields['points']       = __( 'Points', 'advanced-form-integration' );
        $fields['total_points'] = __( 'Total Points', 'advanced-form-integration' );
        $fields['percentage']   = __( 'Percentage', 'advanced-form-integration' );
    }

    if( in_array( $form_id, array( 'submittedAssignment' ) ) ) {
        $fields['file_name'] = __( 'File Name', 'advanced-form-integration' );
        $fields['file_path'] = __( 'File Path', 'advanced-form-integration' );
        $fields['file_link'] = __( 'File Link', 'advanced-form-integration' );
    }

    $fields['user_id']    = __( 'User ID', 'advanced-form-integration' );
    $fields['first_name'] = __( 'First Name', 'advanced-form-integration' );
    $fields['last_name']  = __( 'Last Name', 'advanced-form-integration' );
    $fields['user_email'] = __( 'Email', 'advanced-form-integration' );

    
    // if ( adfoin_fs()->is__premium_only() ) {
    //     if ( adfoin_fs()->is_plan( 'professional', true ) ) {
    //         global $wpdb;

    //         $custom_fields = array();
    //         $results       = $wpdb->get_col( "SELECT label FROM {$wpdb->prefix}xxxxxxx" );

    //         foreach ( $results as $field ){   
    //             $custom_fields[$field] = $field;
    //         }

    //         $fields = $fields + $custom_fields;
    //     }
    // }

    return $fields;
}

function adfoin_learndash_get_post_data( $post_id, $post_type ) {
    $data      = array();
    $post_data = get_post($post_id);

    if ( $post_data ) {
        $data["{$post_type}_id"]    = $post_data->ID;
        $data["{$post_type}_title"] = $post_data->post_title;
        $data["{$post_type}_url"]   = get_permalink($post_id);
    }

    return $data;
}

function adfoin_learndash_get_userdata( $user_id ) {
    $user_data = array();
    $user      = get_userdata($user_id);

    if ($user) {
        $user_data["user_id"]    = $user->ID;
        $user_data["first_name"] = $user->first_name;
        $user_data["last_name"]  = $user->last_name;
        $user_data["user_email"] = $user->user_email;
    }

    return $user_data;
}

function adfoin_learndash_send_data( $saved_records, $posted_data ) {
    $job_queue = get_option( 'adfoin_general_settings_job_queue' );

    foreach ( $saved_records as $record ) {
        $action_provider = $record['action_provider'];

        if ( $job_queue ) {
            as_enqueue_async_action("adfoin_{$action_provider}_job_queue", array(
                'data' => array(
                    'record'      => $record,
                    'posted_data' => $posted_data
                )
            ));
        } else {
            call_user_func( "adfoin_{$action_provider}_send_data", $record, $posted_data );
        }
    }
}

function adfoin_learndash_course_enroll_completed($user_id, $course_id, $type) {
    $integration = new Advanced_Form_Integration_Integration();
    $saved_records = $integration->get_by_trigger('learndash', $type);

    if (empty($saved_records)) {
        return;
    }

    $posted_data = adfoin_learndash_get_post_data($course_id, 'course');
    $user_data = adfoin_learndash_get_userdata($user_id);

    if (!empty($user_data)) {
        $posted_data = array_merge($posted_data, $user_data);
    }

    adfoin_learndash_send_data($saved_records, $posted_data);
}

add_action('learndash_update_course_access', 'adfoin_learndash_course_enroll', 10, 4);
function adfoin_learndash_course_enroll($user_id, $course_id, $access_list, $remove) {
    $type = $remove ? 'unenrolledCourse' : 'enrolledCourse';
    adfoin_learndash_course_enroll_completed($user_id, $course_id, $type);
}

add_action('learndash_course_completed', 'adfoin_learndash_course_completed', 10, 1);
function adfoin_learndash_course_completed($data) {
    $integration = new Advanced_Form_Integration_Integration();
    $saved_records = $integration->get_by_trigger('learndash', 'completedCourse');

    if (empty($saved_records)) {
        return;
    }

    $user = $data['user']->data;
    $course = $data['course'];
    $course_id = $course->ID;
    $user_id = $user->ID;

    $posted_data = adfoin_learndash_get_post_data($course_id, 'course');
    $user_data = adfoin_learndash_get_userdata($user_id);

    if (!empty($user_data)) {
        $posted_data = array_merge($posted_data, $user_data);
    }

    adfoin_learndash_send_data($saved_records, $posted_data);
}

add_action('learndash_lesson_completed', 'adfoin_learndash_lesson_completed', 10, 1);
function adfoin_learndash_lesson_completed($data) {
    if (empty($data)) {
        return;
    }

    $integration = new Advanced_Form_Integration_Integration();
    $saved_records = $integration->get_by_trigger('learndash', 'completedLesson');

    if (empty($saved_records)) {
        return;
    }

    $user = $data['user']->data;
    $course = $data['course'];
    $lesson = $data['lesson'];
    $course_id = $course->ID;
    $lesson_id = $lesson->ID;
    $user_id = $user->ID;

    $posted_data = adfoin_learndash_get_post_data($course_id, 'course');
    $lesson_data = adfoin_learndash_get_post_data($lesson_id, 'lesson');
    $user_data = adfoin_learndash_get_userdata($user_id);

    if (!empty($lesson_data)) {
        $posted_data = array_merge($posted_data, $lesson_data);
    }

    if (!empty($user_data)) {
        $posted_data = array_merge($posted_data, $user_data);
    }

    adfoin_learndash_send_data($saved_records, $posted_data);
}

add_action('learndash_topic_completed', 'adfoin_learndash_topic_completed', 10, 1);
function adfoin_learndash_topic_completed($data) {
    if (empty($data)) {
        return;
    }

    $integration = new Advanced_Form_Integration_Integration();
    $saved_records = $integration->get_by_trigger('learndash', 'completedLesson');

    if (empty($saved_records)) {
        return;
    }

    $user = $data['user']->data;
    $course = $data['course'];
    $lesson = $data['lesson'];
    $topic = $data['topic'];

    $course_id = $course->ID;
    $lesson_id = $lesson->ID;
    $topic_id = $topic->ID;
    $user_id = $user->ID;

    $posted_data = adfoin_learndash_get_post_data($course_id, 'course');
    $lesson_data = adfoin_learndash_get_post_data($lesson_id, 'lesson');
    $topic_data = adfoin_learndash_get_post_data($topic_id, 'topic');
    $user_data = adfoin_learndash_get_userdata($user_id);

    if (!empty($lesson_data)) {
        $posted_data = array_merge($posted_data, $lesson_data);
    }

    if (!empty($topic_data)) {
        $posted_data = array_merge($posted_data, $topic_data);
    }

    if (!empty($user_data)) {
        $posted_data = array_merge($posted_data, $user_data);
    }

    adfoin_learndash_send_data($saved_records, $posted_data);
}

add_action( 'learndash_quiz_submitted',  'adfoin_learndash_quiz_attempt', 10, 2);

function adfoin_learndash_quiz_attempt( $data, $user ) {
    $user   = $user->data;
    $course = $data['course'];
    $lesson = $data['lesson'];

    if ( $course && $user ) {
        $course_id    = $course->ID;
        $lesson_id    = $lesson->ID;
        $user_id      = $user->ID;
        $quiz_id      = $data['quiz'];
        $score        = $data['score'];
        $pass         = $data['pass'];
        $total_points = $data['total_points'];
        $points       = $data['points'];
        $percentage   = $data['percentage'];
    }

    $events = array( 'attemptedQuiz', 'passedQuiz', 'failedQuiz' );
    $integration = new Advanced_Form_Integration_Integration();

    foreach ( $events as $event ) {
        $records = $integration->get_by_trigger( 'learndash', $event );

        if( empty( $records ) ) {
            continue;
        }

        if ( $event == 'passedQuiz' && !$pass ) {
            continue;
        }

        if ($event == 'failedQuiz' && $pass ) {
            continue;
        }

        $posted_data = adfoin_learndash_get_post_data($course_id, 'course');
        $lesson_data = adfoin_learndash_get_post_data($lesson_id, 'lesson');
        $user_data   = adfoin_learndash_get_userdata($user_id);

        if (!empty($lesson_data)) {
            $posted_data = array_merge($posted_data, $lesson_data);
        }

        if (!empty($user_data)) {
            $posted_data = array_merge($posted_data, $user_data);
        }

        $quiz_url = get_permalink($quiz_id);

        $quiz_query_args = [
            'post_type'      => 'sfwd-quiz',
            'post_status'    => 'publish',
            'orderby'        => 'post_title',
            'order'          => 'ASC',
            'posts_per_page' => 1,
            'ID'             => $quiz_id,
        ];

        $quizList = get_posts($quiz_query_args);

        $posted_data['quiz_id']      = $quiz_id;
        $posted_data['quiz_title']   = $quizList[0]->post_title;
        $posted_data['quiz_url']     = $quiz_url;
        $posted_data['score']        = $score;
        $posted_data['pass']         = $pass;
        $posted_data['total_points'] = $total_points;
        $posted_data['points']       = $points;
        $posted_data['percentage']   = $percentage;

        adfoin_learndash_send_data( $records, $posted_data );
    }
}

add_action( 'ld_added_group_access',  'adfoin_learndash_added_group', 10, 2);

function adfoin_learndash_added_group( $user_id, $group_id ) {
    if ( !$group_id || !$user_id ) {
        return;
    }

    adfoin_learndash_group_access( $user_id, $group_id, 'addedToGroup' );
}

add_action( 'ld_removed_group_access',  'adfoin_learndash_removed_group', 10, 2);

function adfoin_learndash_removed_group( $user_id, $group_id ) {
    if ( !$group_id || !$user_id ) {
        return;
    }

    adfoin_learndash_group_access( $user_id, $group_id, 'removedFromGroup' );
}

function adfoin_learndash_group_access( $user_id, $group_id, $trigger ) {
    if ( !$group_id || !$user_id ) {
        return;
    }
    
    $integration   = new Advanced_Form_Integration_Integration();
    $saved_records = $integration->get_by_trigger('learndash', $trigger);

    if ( empty( $saved_records ) ) {
        return;
    }

    $posted_data = adfoin_learndash_get_post_data( $group_id, 'group' );
    $user_data   = adfoin_learndash_get_userdata( $user_id );

    if (!empty($user_data)) {
        $posted_data = array_merge($posted_data, $user_data);
    }

    adfoin_learndash_send_data( $saved_records, $posted_data );
}

add_action( 'learndash_assignment_uploaded',  'adfoin_learndash_assignment_submit', 10, 2);

function adfoin_learndash_assignment_submit( $id, $data ) {
    if ( !$id || !$data ) {
        return;
    }

    $file_name = $data['file_name'];
    $file_link = $data['file_link'];
    $file_path = $data['file_path'];
    $user_id   = $data['user_id'];
    $lesson_id = $data['lesson_id'];
    $course_id = $data['course_id'];

    $integration   = new Advanced_Form_Integration_Integration();
    $saved_records = $integration->get_by_trigger('learndash', 'submittedAssignment');

    if ( empty( $saved_records ) ) {
        return;
    }

    $posted_data = adfoin_learndash_get_post_data($course_id, 'course');
    $lesson_data = adfoin_learndash_get_post_data($lesson_id, 'lesson');
    $user_data   = adfoin_learndash_get_userdata($user_id);

    if (!empty($lesson_data)) {
        $posted_data = array_merge($posted_data, $lesson_data);
    }

    if (!empty($user_data)) {
        $posted_data = array_merge($posted_data, $user_data);
    }

    $posted_data['assignment_id'] = $id;
    $posted_data['file_name'] = $file_name;
    $posted_data['file_link'] = $file_link;
    $posted_data['file_path'] = $file_path;
    
    adfoin_learndash_send_data( $saved_records, $posted_data );
}

// add_action( 'adfoin_trigger_extra_fields', 'adfoin_learndash_trigger_fields' );

function adfoin_learndash_trigger_fields() {
    ?>
    <template v-if="trigger.formProviderId == 'learndash'" v-bind:trigger="trigger" v-bind:action="action" v-bind:fielddata="fieldData">
    <tr valign="top" class="alternate" id="learndash">
                <td scope="row-title">
                    <label for="tablecell">
                        <?php esc_attr_e( 'LearnDash', 'advanced-form-integration' ); ?>
                    </label>
                </td>
                <td>
                    <input type="text" class="large-text">
                </td>
            </tr>
    </template>
    <?php
}

// add_action( "adfoin_trigger_templates", "adfoin_learndash_trigger_template" );

function adfoin_learndash_trigger_template() {
    ?>
        <script type="text/template" id="learndash-template">
            <tr valign="top" class="alternate" id="learndash">
                <td scope="row-title">
                    <label for="tablecell">
                        <?php esc_attr_e( 'LearnDash', 'advanced-form-integration' ); ?>
                    </label>
                </td>
                <td>
                    <input type="text" class="large-text"  v-model="trigger.formFields.course">
                </td>
            </tr>
        </script>
    <?php
} 