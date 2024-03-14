<?php
//AJAX ACTIONS CALLBACKS
function tred_ld_posts_dropdown($type, $only_published = true)
{
    if (!isset($_GET['_wpnonce']) || !wp_verify_nonce($_GET['_wpnonce'], 'tred_nonce')) {
        die(__('Security check', 'learndash-easy-dash'));
    }
    if (!isset($_GET['item_type'])) {
        die(__('Post Type absent...', 'learndash-easy-dash'));
    }

    $type = $_GET['item_type'];
    $post_id = isset($_GET['post_id']) ? intval($_GET['post_id']) : 0;

    $args = [
        'post_type' => $type,
        'numberposts' => -1
    ];
    if ($only_published) {
        $args['post_status'] = 'publish';
    }

    //2.4.3
    /**
     * Filter: 'tred_ld_posts_dropdown_args_filter'
     * Allows other plugins to modify the arguments before get_posts() is called.
     * 
     * @param array $args Current arguments.
     * @param string $type The post type.
     * @param int $post_id The post ID.
     * 
     * Example usage:
     * add_filter('tred_ld_posts_dropdown_args_filter', function($args, $type, $post_id) {
     *     // Modify $args as needed
     *     return $args;
     * }, 10, 3);
     */
    $args = apply_filters('tred_ld_posts_dropdown_args_filter', $args, $type, $post_id);

    $ld_posts = get_posts($args);

    //2.4.3
    /**
     * Filter: 'tred_ld_posts_dropdown_posts_filter'
     * Allows other plugins to modify the posts after get_posts() is called.
     * 
     * @param array $ld_posts Current posts.
     * @param string $type The post type.
     * @param int $post_id The post ID.
     * 
     * Example usage:
     * add_filter('tred_ld_posts_dropdown_posts_filter', function($ld_posts, $type, $post_id) {
     *     // Modify $ld_posts as needed
     *     return $ld_posts;
     * }, 10, 3);
     */
    $ld_posts = apply_filters('tred_ld_posts_dropdown_posts_filter', $ld_posts, $type, $post_id);


    if (empty($ld_posts)) {
        echo 'no post';
        die();
    }
    echo json_encode($ld_posts);
    die();
}


function tred_users_dropdown()
{
    if (!isset($_GET['_wpnonce']) || !wp_verify_nonce($_GET['_wpnonce'], 'tred_nonce')) {
        die(__('Security check', 'learndash-easy-dash'));
    }
    if (empty($_GET['q'])) {
        die(__('No term for search...', 'learndash-easy-dash'));
    }
    //{"q":"ivana","action":"tred_users_dropdown","_wpnonce":"68e313734b","_fs_blog_admin":"true"}

    $search_string = esc_attr(trim($_GET['q']));
    $post_id = isset($_GET['post_id']) ? intval($_GET['post_id']) : 0;

    // Prepare the initial query parameters
    $query_args = array(
        'search' => '*' . $search_string . '*',
        'fields' => array(
            'ID',
            'display_name',
            'user_email'
        )
    );

    //2.4.3
    /**
     * Filter: 'tred_users_dropdown_query_args_filter'
     * Allows other plugins to modify the query arguments before the WP_User_Query is executed.
     * 
     * @param array $query_args Current query arguments.
     * @param string $search_string The search string.
     * @param int $post_id The post ID. 
     * 
     * Example usage:
     * add_filter('tred_users_dropdown_query_args_filter', function($query_args, $search_string, $post_id) {
     *     // Modify $query_args as needed
     *     return $query_args;
     * }, 10, 3);
     */
    $query_args = apply_filters('tred_users_dropdown_query_args_filter', $query_args, $search_string, $post_id);

    $users = new WP_User_Query($query_args);
    $users_found = $users->get_results();

    //2.4.3
    /**
     * Filter: 'tred_users_dropdown_users_found_filter'
     * Allows other plugins to modify the users found after the WP_User_Query is executed.
     * 
     * @param array $users_found Current users found.
     * @param string $search_string The search string.
     * @param int $post_id The post ID.
     * 
     * Example usage:
     * add_filter('tred_users_dropdown_users_found_filter', function($users_found, $search_string, $post_id) {
     *     // Modify $users_found as needed
     *     return $users_found;
     * }, 10, 3);
     */
    $users_found = apply_filters('tred_users_dropdown_users_found_filter', $users_found, $search_string, $post_id);

    echo json_encode($users_found);
    die();
}


function tred_ld_students_courses()
{
    if (!isset($_REQUEST['_wpnonce']) || !wp_verify_nonce($_REQUEST['_wpnonce'], 'tred_nonce')) {
        die(__('Security check', 'learndash-easy-dash'));
    }

    //2.4.2
    /**
     * Filter: 'tred_ld_students_courses_before_processing_request'
     * Allows other plugins to modify the request data before processing.
     * 
     * @param array $request_data Current request data.
     * 
     * Example usage:
     * add_filter('tred_ld_students_courses_before_processing_request', function($data) {
     *     // Modify $data as needed
     *     return $data;
     * });
     */
    $request_data = apply_filters('tred_ld_students_courses_before_processing_request', $_REQUEST);
    // 2.4.2
    $action = isset($request_data['action']) ? sanitize_text_field($request_data['action']) : ''; //2.4.2
    //2.4.3
    $post_id = isset($request_data['post_id']) ? intval($request_data['post_id']) : 0;

    $response = [];
    $response['action'] = $action;
    $data_students_courses = tred_get_students_number_all_courses();
    if ($data_students_courses) {
        $response['result'] = 'success';
        $response['data'] = [];
        $response['data']['top_boxes'] = [];
        $response['data']['charts'] = [];
        $response['data']['tables'] = [];
        $response['data']['top_boxes']['top-students-total'] = $data_students_courses['students']['total'];
        $response['data']['top_boxes']['top-courses-total'] = $data_students_courses['courses']['total'];

        $students_courses_items = (!empty($data_students_courses['courses']['items'])) ? $data_students_courses['courses']['items'] : [];

        foreach ($students_courses_items as $access_mode => $sci) {
            //order by students, descending
            tred_sort_desc($sci, 'students');
            $courses_titles = array_map(function ($val) {
                return $val['title'];
            }, $sci);
            $courses_students = array_map(function ($val) {
                return $val['students'];
            }, $sci);
            $courses_students_completed = array_map(function ($val) {
                return $val['students_completed'];
            }, $sci);

            $chart = [];
            $chart['id'] = "chart-$access_mode-courses-students-completions";
            $chart['labels'] = $courses_titles;
            $datasets = [
                [
                    'label' => esc_html__('Students', 'learndash-easy-dash'),
                    'data' => $courses_students,
                    'borderColor' => 'rgb(54, 162, 235)',
                    'backgroundColor' => 'rgba(255, 99, 132, 0.2)'
                ],
                [
                    "label" => esc_html__('Completions', 'learndash-easy-dash'),
                    "data" => $courses_students_completed,
                    "type" => "line",
                    "fill" => false,
                    "borderColor" => "rgb(54, 162, 235)"
                ]
            ];
            $chart['datasets'] = $datasets;
            $response['data']['charts'][] = $chart;
        } //end foreach

    } else {
        $response['result'] = 'error';
    }


    //2.4.2
    // Before sending the response
    /**
     * Filter: 'tred_ld_students_courses_before_response'
     * Allows other plugins to modify the response before it is sent.
     * 
     * @param array $response     Data of the response to be sent.
     * @param array $request_data Original request data.
     * @param int $post_id        The post ID.
     * 
     * Example usage:
     * add_filter('tred_ld_students_courses_before_response', function($response, $request_data, $post_id) {
     *     // Modify $response as needed, possibly using $request_data
     *     return $response;
     * }, 10, 3);
     */
    $response = apply_filters('tred_ld_students_courses_before_response', $response, $request_data, $post_id);

    echo json_encode($response);

    die();
}

function tred_ld_posts()
{
    if (!isset($_REQUEST['_wpnonce']) || !wp_verify_nonce($_REQUEST['_wpnonce'], 'tred_nonce')) {
        die(__('Security check', 'learndash-easy-dash'));
    }

    //2.4.2
    /**
     * Filter: 'tred_ld_posts_before_processing_request'
     * Allows other plugins to modify the request data before processing.
     * 
     * @param array $request_data Current request data.
     * 
     * Example usage:
     * add_filter('tred_ld_posts_before_processing_request', function($data) {
     *     // Modify $data as needed
     *     return $data;
     * });
     */
    $request_data = apply_filters('tred_ld_posts_before_processing_request', $_REQUEST);
    // 2.4.2
    $action = isset($request_data['action']) ? sanitize_text_field($request_data['action']) : ''; //2.4.2
    //2.4.3
    $post_id = isset($request_data['post_id']) ? intval($request_data['post_id']) : 0;

    $response = [];
    $response['action'] = $action;
    $ld_posts = tred_get_lessons_topics_quizzes_number();
    if ($ld_posts) {
        $response['result'] = 'success';
        $response['data'] = [];
        $response['data']['top_boxes'] = [];
        $response['data']['charts'] = [];
        $response['data']['tables'] = [];
        $response['data']['top_boxes']['top-lessons-total'] = $ld_posts['lessons'];
        $response['data']['top_boxes']['top-topics-total'] = $ld_posts['topics'];
        $response['data']['top_boxes']['top-quizzes-total'] = $ld_posts['quizzes'];
    } else {
        $response['result'] = 'error';
    }

    //2.4.2
    // Before sending the response
    /**
     * Filter: 'tred_ld_posts_before_response'
     * Allows other plugins to modify the response before it is sent.
     * 
     * @param array $response     Data of the response to be sent.
     * @param array $request_data Original request data.
     * @param int $post_id        The post ID.
     * 
     * Example usage:
     * add_filter('tred_ld_posts_before_response', function($response, $request_data, $post_id) {
     *     // Modify $response as needed, possibly using $request_data
     *     return $response;
     * }, 10, 3);
     */
    $response = apply_filters('tred_ld_posts_before_response', $response, $request_data, $post_id);

    echo json_encode($response);

    die();
}

function tred_ld_essays_assignments()
{
    if (!isset($_REQUEST['_wpnonce']) || !wp_verify_nonce($_REQUEST['_wpnonce'], 'tred_nonce')) {
        die(__('Security check', 'learndash-easy-dash'));
    }

    //2.4.2
    /**
     * Filter: 'tred_ld_essays_assignments_before_processing_request'
     * Allows other plugins to modify the request data before processing.
     * 
     * @param array $request_data Current request data.
     * 
     * Example usage:
     * add_filter('tred_ld_essays_assignments_before_processing_request', function($data) {
     *     // Modify $data as needed
     *     return $data;
     * });
     */
    $request_data = apply_filters('tred_ld_essays_assignments_before_processing_request', $_REQUEST);
    // 2.4.2
    $action = isset($request_data['action']) ? sanitize_text_field($request_data['action']) : ''; //2.4.2
    // 2.4.3
    $post_id = isset($request_data['post_id']) ? intval($request_data['post_id']) : 0;

    $response = [];
    $response['action'] = $action;

    $essays_pending_count = learndash_get_essays_pending_count();
    $assignments_pending_count = learndash_get_assignments_pending_count();
    if (is_numeric($essays_pending_count) && is_numeric($assignments_pending_count)) {
        $response['result'] = 'success';
        $response['data'] = [];
        $response['data']['top_boxes'] = [];
        $response['data']['charts'] = [];
        $response['data']['tables'] = [];
        $response['data']['top_boxes']['top-essays-pending'] = $essays_pending_count;
        $response['data']['top_boxes']['top-assignments-pending'] = $assignments_pending_count;
    } else {
        $response['result'] = 'error';
    }

    //2.4.2
    // Before sending the response
    /**
     * Filter: 'tred_ld_essays_assignments_before_response'
     * Allows other plugins to modify the response before it is sent.
     * 
     * @param array $response     Data of the response to be sent.
     * @param array $request_data Original request data.
     * @param int $post_id        The post ID.
     * 
     * Example usage:
     * add_filter('tred_ld_essays_assignments_before_response', function($response, $request_data, $post_id) {
     *     // Modify $response as needed, possibly using $request_data
     *     return $response;
     * }, 10, 3);
     */
    $response = apply_filters('tred_ld_essays_assignments_before_response', $response, $request_data, $post_id);
    echo json_encode($response);

    die();
}

function tred_ld_groups()
{
    if (!isset($_REQUEST['_wpnonce']) || !wp_verify_nonce($_REQUEST['_wpnonce'], 'tred_nonce')) {
        die(__('Security check', 'learndash-easy-dash'));
    }

    //2.4.2
    /**
     * Filter: 'tred_ld_groups_before_processing_request'
     * Allows other plugins to modify the request data before processing.
     * 
     * @param array $request_data Current request data.
     * 
     * Example usage:
     * add_filter('tred_ld_groups_before_processing_request', function($data) {
     *     // Modify $data as needed
     *     return $data;
     * });
     */
    $request_data = apply_filters('tred_ld_groups_before_processing_request', $_REQUEST);
    // 2.4.2
    $action = isset($request_data['action']) ? sanitize_text_field($request_data['action']) : ''; //2.4.2
    // 2.4.3
    $post_id = isset($request_data['post_id']) ? intval($request_data['post_id']) : 0;

    $response = [];
    $response['action'] = $action;

    $data_students_groups = tred_get_students_number_all_groups();
    $students_groups_total = (!empty($data_students_groups['students']['total'])) ? $data_students_groups['students']['total'] : 0;
    $groups_total = (!empty($data_students_groups['groups']['total'])) ? $data_students_groups['groups']['total'] : 0;
    $students_groups_items = (!empty($data_students_groups['groups']['items'])) ? $data_students_groups['groups']['items'] : [];
    tred_sort_desc($students_groups_items, 'students');
    $groups_titles = array_map(function ($val) {
        return $val['title'];
    }, $students_groups_items);
    $groups_students = array_map(function ($val) {
        return $val['students'];
    }, $students_groups_items);

    if ($data_students_groups) {
        $response['result'] = 'success';
        $response['data'] = [];
        $response['data']['top_boxes'] = [];
        $response['data']['top_boxes']['top-groups-total'] = $groups_total;
        $response['data']['charts'] = [];
        $response['data']['tables'] = [];

        //First chart
        $chart = [];
        $chart['id'] = 'chart-groups-students';
        $chart['labels'] = $groups_titles;
        $chart['datasets'] = [];
        $dataset = [
            'label' => esc_html__('Students', 'learndash-easy-dash'),
            'data' => $groups_students,
            'borderColor' => 'rgb(54, 162, 235)',
            'backgroundColor' => 'rgba(255, 99, 132, 0.2)'
        ];
        $chart['datasets'][] = $dataset;
        $chart['obs'] = sprintf(__('Total students in groups: %s', 'learndash-easy-dash'), $students_groups_total);
        //End first chart
        $response['data']['charts'][] = $chart;


    } else {
        $response['result'] = 'error';
    }

    //2.4.2
    // Before sending the response
    /**
     * Filter: 'tred_ld_groups_before_response'
     * Allows other plugins to modify the response before it is sent.
     * 
     * @param array $response     Data of the response to be sent.
     * @param array $request_data Original request data.
     * @param int $post_id        The post ID.
     * 
     * Example usage:
     * add_filter('tred_ld_groups_before_response', function($response, $request_data, $post_id) {
     *     // Modify $response as needed, possibly using $request_data
     *     return $response;
     * }, 10, 3);
     */
    $response = apply_filters('tred_ld_groups_before_response', $response, $request_data, $post_id);

    echo json_encode($response);

    die();
}

function tred_ld_comments()
{
    if (!isset($_REQUEST['_wpnonce']) || !wp_verify_nonce($_REQUEST['_wpnonce'], 'tred_nonce')) {
        die(__('Security check', 'learndash-easy-dash'));
    }

    //2.4.2
    /**
     * Filter: 'tred_ld_comments_before_processing_request'
     * Allows other plugins to modify the request data before processing.
     * 
     * @param array $request_data Current request data.
     * 
     * Example usage:
     * add_filter('tred_ld_comments_before_processing_request', function($data) {
     *     // Modify $data as needed
     *     return $data;
     * });
     */
    $request_data = apply_filters('tred_ld_comments_before_processing_request', $_REQUEST);
    // 2.4.2
    $action = isset($request_data['action']) ? sanitize_text_field($request_data['action']) : ''; //2.4.2
    // 2.4.3
    $post_id = isset($request_data['post_id']) ? intval($request_data['post_id']) : 0;

    $response = [];
    $response['action'] = $action;

    $comments_learndash_post_types = tred_get_learndash_post_types_comments();
    if ($comments_learndash_post_types) {
        $comments_total = (!empty($comments_learndash_post_types['total'])) ? $comments_learndash_post_types['total'] : 0;
        $response['result'] = 'success';
        $response['data'] = [];
        $response['data']['top_boxes'] = [];
        $response['data']['charts'] = [];
        $response['data']['tables'] = [];
        $response['data']['top_boxes']['top-comments-total'] = $comments_total;
        $comments_by_course = tred_comments_by_course($comments_learndash_post_types['items']);
        $comments_authors = (!empty($comments_by_course['users'])) ? $comments_by_course['users'] : [];
        $comments_courses = (!empty($comments_by_course['courses'])) ? $comments_by_course['courses'] : [];
        $most_commenting_courses_titles = array_map(function ($val) {
            return $val['course_title'];
        }, $comments_courses);
        $most_commenting_courses_totals = array_map(function ($val) {
            return $val['total'];
        }, $comments_courses);
        $most_commenting_courses_approveds = array_map(function ($val) {
            return $val['approve'];
        }, $comments_courses);
        $most_commenting_courses_holds = array_map(function ($val) {
            return $val['hold'];
        }, $comments_courses);
        $most_commenting_users_emails = array_keys($comments_authors);
        $most_commenting_users_totals = array_values($comments_authors);

        $chart = [];
        $chart['id'] = 'chart-most-commenting-users';
        $chart['labels'] = $most_commenting_users_emails;
        $datasets = [
            [
                'label' => esc_html__('Students', 'learndash-easy-dash'),
                'data' => $most_commenting_users_totals,
                'borderColor' => 'rgb(54, 162, 235)',
                'backgroundColor' => 'rgba(255, 99, 132, 0.2)'
            ],
        ];
        $chart['datasets'] = $datasets;
        $response['data']['charts'][] = $chart;

        $chart = [];
        $chart['id'] = 'chart-courses-with-more-comments';
        $chart['labels'] = $most_commenting_courses_titles;
        $datasets = [
            [
                'label' => esc_html__('Comments', 'learndash-easy-dash'),
                'data' => $most_commenting_courses_totals,
                'borderColor' => 'rgb(54, 162, 235)',
                'backgroundColor' => 'rgba(255, 99, 132, 0.2)'
            ],
            [
                "label" => esc_html__('Approved', 'learndash-easy-dash'),
                "data" => $most_commenting_courses_approveds,
                "type" => "line",
                "fill" => false,
                "borderColor" => "#44976A"
            ],
            [
                "label" => esc_html__('Hold', 'learndash-easy-dash'),
                "data" => $most_commenting_courses_holds,
                "type" => "line",
                "fill" => false,
                "borderColor" => "#D9782A"
            ]
        ];
        $chart['datasets'] = $datasets;
        $response['data']['charts'][] = $chart;

    } else {
        $response['result'] = 'error';
    }

    //2.4.2
    // Before sending the response
    /**
     * Filter: 'tred_ld_comments_before_response'
     * Allows other plugins to modify the response before it is sent.
     * 
     * @param array $response     Data of the response to be sent.
     * @param array $request_data Original request data.
     * @param int $post_id        The post ID.
     * 
     * Example usage:
     * add_filter('tred_ld_comments_before_response', function($response, $request_data, $post_id) {
     *     // Modify $response as needed, possibly using $request_data
     *     return $response;
     * }, 10, 3);
     */
    $response = apply_filters('tred_ld_comments_before_response', $response, $request_data, $post_id);

    echo json_encode($response);

    die();
}

function tred_ld_activity()
{
    if (!isset($_REQUEST['_wpnonce']) || !wp_verify_nonce($_REQUEST['_wpnonce'], 'tred_nonce')) {
        die(__('Security check', 'learndash-easy-dash'));
    }
    //2.4.2
    /**
     * Filter: 'tred_ld_activity_before_processing_request'
     * Allows other plugins to modify the request data before processing.
     * 
     * @param array $request_data Current request data.
     * 
     * Example usage:
     * add_filter('tred_ld_activity_before_processing_request', function($data) {
     *     // Modify $data as needed
     *     return $data;
     * });
     */
    $request_data = apply_filters('tred_ld_activity_before_processing_request', $_REQUEST);
    // 2.4.2
    $action = isset($request_data['action']) ? sanitize_text_field($request_data['action']) : ''; //2.4.2
    // 2.4.3
    $post_id = isset($request_data['post_id']) ? intval($request_data['post_id']) : 0;

    $response = [];
    $response['action'] = $action;
    $response['result'] = 'success';
    $response['data'] = [];
    $response['data']['top_boxes'] = [];
    $response['data']['charts'] = [];
    $response['data']['tables'] = [];

    $activity = tred_learndash_get_activity();
    if (!is_array($activity)) {
        $response['result'] = $activity;
        echo json_encode($response);
        die();
    }

    $course_activities_number = tred_learndash_get_item_activities_number($activity, 'course');
    $course_completions_last_x_days = tred_get_from_array_or_object($course_activities_number, 'completions');
    $course_starts_last_x_days = tred_get_from_array_or_object($course_activities_number, 'starts');
    $course_enrolls_last_x_days = tred_get_from_array_or_object($course_activities_number, 'enrolls');
    $users_activities_last_x_days = tred_learndash_rank_users_all_activities($activity);
    $users_activities_emails = tred_get_from_array_or_object($users_activities_last_x_days, 'emails', []);
    $users_activities_totals = tred_get_from_array_or_object($users_activities_last_x_days, 'totals', []);
    $users_activities_starts = tred_get_from_array_or_object($users_activities_last_x_days, 'starts', []);
    $users_activities_enrolls = tred_get_from_array_or_object($users_activities_last_x_days, 'enrolls', []);
    $users_activities_completions = tred_get_from_array_or_object($users_activities_last_x_days, 'completions', []);

    $response['data']['top_boxes']['top-course-completions'] = $course_completions_last_x_days;
    $response['data']['top_boxes']['top-course-starts'] = $course_starts_last_x_days;
    $response['data']['top_boxes']['top-course-enrolls'] = $course_enrolls_last_x_days;
    $response['data']['users_activities_last_x_days'] = $users_activities_last_x_days;

    $courses_ranked_by_activity_last_x_days = tred_learndash_rank_courses_by_activity($activity);
    $items_ranked_by_completions_last_x_days = tred_learndash_rank_courses_items_by_completion($activity);

    foreach (['courses', 'lessons', 'topics', 'quizzes'] as $ldi) {
        $ldi_ranked_by_completions_last_x_days = tred_get_from_array_or_object($items_ranked_by_completions_last_x_days, $ldi);
        $most_completed_ldi_titles = array_map(function ($val) {
            return $val['title'];
        }, $ldi_ranked_by_completions_last_x_days);
        $most_completed_ldi_totals = array_map(function ($val) {
            return $val['total'];
        }, $ldi_ranked_by_completions_last_x_days);

        $chart = [];
        $chart['id'] = 'chart-most-completed-' . $ldi;
        $chart['labels'] = $most_completed_ldi_titles;
        $datasets = [
            [
                'label' => esc_html__('Completions', 'learndash-easy-dash'),
                'data' => $most_completed_ldi_totals,
                'borderColor' => 'rgb(54, 162, 235)',
                'backgroundColor' => 'rgba(255, 99, 132, 0.2)'
            ],
        ];
        $chart['datasets'] = $datasets;
        $response['data']['charts'][] = $chart;
    } // end foreach

    $chart = [];
    $chart['id'] = 'chart-most-active-students';
    $chart['labels'] = $users_activities_emails;
    $datasets = [
        [
            'label' => esc_html__('all', 'learndash-easy-dash'),
            'data' => $users_activities_totals,
            'borderColor' => 'rgb(54, 162, 235)',
            'backgroundColor' => 'rgba(255, 99, 132, 0.2)'
        ],
        [
            "label" => esc_html__('Enrolls', 'learndash-easy-dash'),
            "data" => $users_activities_enrolls,
            "type" => "line",
            "fill" => false,
            "borderColor" => "#44976A"
        ],
        [
            "label" => esc_html__('Starts', 'learndash-easy-dash'),
            "data" => $users_activities_starts,
            "type" => "line",
            "fill" => false,
            "borderColor" => "#D9782A"
        ],
        [
            "label" => esc_html__('Completions', 'learndash-easy-dash'),
            "data" => $users_activities_completions,
            "type" => "line",
            "fill" => false,
            "borderColor" => "rgb(54, 162, 235)"
        ],
    ];
    $chart['datasets'] = $datasets;
    $response['data']['charts'][] = $chart;

    $table_data = [];
    for ($i = 0; $i < count($users_activities_emails); $i++) {
        $a = [];
        $a['email'] = $users_activities_emails[$i];
        // $a['total'] = $users_activities_last_x_days['totals'][$i];
        $a['enrolls'] = $users_activities_enrolls[$i];
        $a['starts'] = $users_activities_starts[$i];
        $a['completions'] = $users_activities_completions[$i];
        $table_data[] = $a;
    }

    $table = [];
    $table['type'] = 'global';
    $table['id'] = 'table-students-activity-last-x-days';
    $table['data'] = $table_data;
    $table['keys_labels'] = [
        'email' => esc_html__('Email', 'learndash-easy-dash'),
        // 'total' => 'Total',
        'enrolls' => esc_html__('Enrolls', 'learndash-easy-dash'),
        'starts' => esc_html__('Starts', 'learndash-easy-dash'),
        'completions' => esc_html__('Completions', 'learndash-easy-dash'),
    ];
    $table['obs'] = esc_html__('Enrolls: courses | Starts and completions: courses, lessons, topics, quizzes', 'learndash-easy-dash');
    $response['data']['tables'][] = $table;

    //2.4.2
    // Before sending the response
    /**
     * Filter: 'tred_ld_activity_before_response'
     * Allows other plugins to modify the response before it is sent.
     * 
     * @param array $response     Data of the response to be sent.
     * @param array $request_data Original request data.
     * @param int $post_id        The post ID.
     * 
     * Example usage:
     * add_filter('tred_ld_activity_before_response', function($response, $request_data, $post_id) {
     *     // Modify $response as needed, possibly using $request_data
     *     return $response;
     * }, 10, 3);
     */
    $response = apply_filters('tred_ld_activity_before_response', $response, $request_data, $post_id);

    echo json_encode($response);
    die();
}

//pass id and get just for the course selected
function tred_ld_courses_completions_stats($course_id = 0)
{

    $class_call = !empty($course_id) && is_numeric($course_id);
    if ($class_call) {
        if (get_post_type($course_id) !== 'sfwd-courses') {
            return false;
        }
        return tred_learndash_get_course_completions_stats($course_id);
    }

    if (!isset($_REQUEST['_wpnonce']) || !wp_verify_nonce($_REQUEST['_wpnonce'], 'tred_nonce')) {
        die(__('Security check', 'learndash-easy-dash'));
    }
    $response = [];
    $response['action'] = sanitize_text_field($_REQUEST['action']);
    $course_completions_stats = tred_learndash_get_course_completions_stats();

    if ($course_completions_stats) {
        $response['result'] = 'success';
        $response['data'] = [];
        $response['data']['top_boxes'] = [];
        $response['data']['charts'] = [];
        $response['data']['tables'] = [];

        $course_completions_same_day = $course_completions_stats['same_day'];
        $course_completions_same_day_courses = $course_completions_same_day['courses'];
        //order by times, descending
        arsort($course_completions_same_day_courses);
        $c_sd_titles = array_keys($course_completions_same_day_courses);
        $c_sd_values = array_values($course_completions_same_day_courses);

        $chart = [];
        $chart['id'] = 'chart-courses-completions-same-day';
        $chart['labels'] = $c_sd_titles;
        $datasets = [
            [
                'label' => esc_html__('times completed', 'learndash-easy-dash'),
                'data' => $c_sd_values,
                'borderColor' => 'rgb(54, 162, 235)',
                'backgroundColor' => 'rgba(255, 99, 132, 0.2)'
            ],
        ];
        $chart['datasets'] = $datasets;
        $response['data']['charts'][] = $chart;

        $table = [];
        $table['type'] = 'global';
        $table['id'] = 'table-completion-course-stats';
        $table['data'] = $course_completions_stats['courses'];
        $table['keys_labels'] = [
            'title' => esc_html__('Course', 'learndash-easy-dash'),
            'mode' => esc_html__('Mode', 'learndash-easy-dash'),
            'students' => esc_html__('#Enrolled', 'learndash-easy-dash'),
            'total_completed' => esc_html__('#Completed', 'learndash-easy-dash'),
            'total_completed_percentage' => esc_html__('Students', 'learndash-easy-dash'),
            'average_days' => esc_html__('#Days/avg', 'learndash-easy-dash'),
        ];
        $table['obs'] = esc_html__('completion average days (all courses): ', 'learndash-easy-dash');
        $table['obs'] .= $course_completions_stats['average_days'];
        $response['data']['tables'][] = $table;

    } else {
        $response['result'] = 'error';
    }

    //2.4.2
    // Before sending the response
    /**
     * Filter: 'tred_ld_courses_completions_stats_before_response'
     * Allows other plugins to modify the response before it is sent.
     * 
     * @param array $response     Data of the response to be sent.
     * @param array $course_id Original course_id.
     * 
     * Example usage:
     * add_filter('tred_ld_courses_completions_stats_before_response', function($response, $course_id) {
     *     // Modify $response as needed, possibly using $course_id
     *     return $response;
     * }, 10, 2);
     */
    $response = apply_filters('tred_ld_courses_completions_stats_before_response', $response, $course_id);

    echo json_encode($response);
    die();
}

function tred_ld_items_stats_over_time($post_id = 0)
{

    $class_call = !empty($post_id) && is_numeric($post_id);
    $post_type = ($class_call) ? get_post_type($post_id) : '';
    $data_type = str_replace('sfwd-', '', $post_type); //courses, lessons, topic, quiz

    $response = [];
    $request_data = [];
    //if no post_id, it will be an ajax call; if post_id, class will call this function
    if (!$class_call) {
        if (!isset($_REQUEST['_wpnonce']) || !wp_verify_nonce($_REQUEST['_wpnonce'], 'tred_nonce')) {
            die(__('Security check', 'learndash-easy-dash'));
        }
        //2.4.2
        /**
         * Filter: 'tred_ld_items_stats_over_time_before_processing_request'
         * Allows other plugins to modify the request data before processing.
         * 
         * @param array $request_data Current request data.
         * 
         * Example usage:
         * add_filter('tred_ld_items_stats_over_time_before_processing_request', function($data) {
         *     // Modify $data as needed
         *     return $data;
         * });
         */
        $request_data = apply_filters('tred_ld_items_stats_over_time_before_processing_request', $_REQUEST);
        // 2.4.2
        $action = isset($request_data['action']) ? sanitize_text_field($request_data['action']) : ''; //2.4.2
        // 2.4.3
        $post_id = isset($request_data['post_id']) ? intval($request_data['post_id']) : 0;
        $response['action'] = $action; //2.4.2
    }


    $response['result'] = 'success';
    $response['data'] = [];
    $response['data']['top_boxes'] = [];
    $response['data']['charts'] = [];
    $response['data']['tables'] = [];

    $activity_array = tred_learndash_get_activity_last_12_months($post_id);
    if (!is_array($activity_array) || empty($activity_array)) {
        echo json_encode($response);
        die();
    }
    //sort activity by key date	
    uksort($activity_array, function ($a, $b) {
        $atime = DateTime::createFromFormat("Y_m", $a);
        $btime = DateTime::createFromFormat("Y_m", $b);
        return $atime->getTimestamp() - $btime->getTimestamp();
    });

    $act_array = [];
    $courses_array = [];
    $types = ['course' => 'courses', 'lesson' => 'lessons', 'topic' => 'topics', 'quiz' => 'quizzes'];
    foreach ($activity_array as $key => $activities) {
        $act_array[$key] = [];
        $act_array[$key]['course_enrolls'] = 0;
        foreach ($types as $type => $type_plural) {
            $act_array[$key][$type . '_starts'] = 0;
            $act_array[$key][$type . '_completions'] = 0;
        }
        //foreach here
        foreach ($activities as $act) {

            $act_user_id = tred_get_from_array_or_object($act, 'user_id');
            $act_course_id = tred_get_from_array_or_object($act, 'course_id');
            $act_post_id = tred_get_from_array_or_object($act, 'post_id');
            $act_type = tred_get_from_array_or_object($act, 'activity_type');
            $act_started = tred_get_from_array_or_object($act, 'activity_started');
            $act_completed = tred_get_from_array_or_object($act, 'activity_completed');
            $act_status = tred_get_from_array_or_object($act, 'activity_status');

            //for $courses_array
            //TODO: find a way and when to get the course title
            if (!empty($act_course_id)) {
                if (empty($courses_array[$act_course_id])) {
                    $courses_array[$act_course_id] = [];
                }
                if (empty($courses_array[$act_course_id][$key])) {
                    $courses_array[$act_course_id][$key] = [];
                }
                if (empty($courses_array[$act_course_id][$key][$act_user_id])) {
                    $courses_array[$act_course_id][$key][$act_user_id] = [];
                    $courses_array[$act_course_id][$key][$act_user_id]['course_enrolls'] = 0;
                    foreach ($types as $type => $type_plural) {
                        $courses_array[$act_course_id][$key][$act_user_id][$type . '_starts'] = 0;
                        $courses_array[$act_course_id][$key][$act_user_id][$type . '_completions'] = 0;
                    }
                }
            } //END - for $courses_array

            if (empty($act_post_id) && empty($act_course_id)) {
                //one of them had to be present...
                continue;
            }
            if (empty($act_type)) {
                continue;
            }
            if ($act_type == 'access') {
                $act_array[$key]['course_enrolls'] += 1;
                $courses_array[$act_course_id][$key][$act_user_id]['course_enrolls'] += 1;
                continue;
            }
            if (!in_array($act_type, array_keys($types))) {
                continue;
            }
            if (!empty($act_course_id) && !empty($act_user_id)) {
                if (!empty($act_completed) && !empty($act_status)) {
                    $act_array[$key][$act_type . '_completions'] += 1;
                    $courses_array[$act_course_id][$key][$act_user_id][$type . '_completions'] += 1;
                    continue;
                }
                if (!empty($act_started) && empty($act_status)) {
                    $act_array[$key][$act_type . '_starts'] += 1;
                    $courses_array[$act_course_id][$key][$act_user_id][$type . '_starts'] += 1;
                    continue;
                }
            }

        } //end inner foreach (activities)
    } //end outter foreach

    foreach ($types as $type => $type_plural) {
        $chart = [];
        $datasets = [];
        $chart['id'] = 'chart-' . $type_plural . '-stats-over-time';
        $chart['labels'] = array_map(function ($v) {
            return tred_year_month_numbers_to_string_month_slash_year($v);
        }, array_keys($act_array));
        $chart['slice'] = 'last';
        if ($type === 'course') {
            $datasets[] = [
                'label' => esc_html__('Enrolls', 'learndash-easy-dash'),
                'data' => array_values(array_map(function ($v) use ($type) {
                    return $v[$type . '_enrolls'];
                }, $act_array)),
                'borderColor' => 'rgb(54, 162, 235)',
                'backgroundColor' => 'rgba(255, 99, 132, 0.2)'
            ];
        }
        $datasets[] = [
            "label" => esc_html__('Starts', 'learndash-easy-dash'),
            "data" => array_values(array_map(function ($v) use ($type) {
                return $v[$type . '_starts'];
            }, $act_array)),
            "type" => "line",
            "fill" => false,
            "borderColor" => "#44976A"
        ];
        $datasets[] = [
            "label" => esc_html__('Completions', 'learndash-easy-dash'),
            "data" => array_values(array_map(function ($v) use ($type) {
                return $v[$type . '_completions'];
            }, $act_array)),
            "type" => "line",
            "fill" => false,
            "borderColor" => "#D9782A"
        ];
        $chart['datasets'] = $datasets;
        $response['data']['charts'][] = $chart;

        if ($class_call && strpos($chart['id'], $data_type) !== false) {
            return $chart;
        }
    } // end foreach types


    //2.4.2
    // Before sending the response
    /**
     * Filter: 'tred_ld_items_stats_over_time_before_response'
     * Allows other plugins to modify the response before it is sent.
     * 
     * @param array $response     Data of the response to be sent.
     * @param array $request_data Original request data.
     * @param int $post_id        The post ID.
     * 
     * Example usage:
     * add_filter('tred_ld_items_stats_over_time_before_response', function($response, $request_data, $post_id) {
     *     // Modify $response as needed, possibly using $request_data
     *     return $response;
     * }, 10, 3);
     */
    $response = apply_filters('tred_ld_items_stats_over_time_before_response', $response, $request_data, $post_id);

    echo json_encode($response);
    die();
}

function tred_ld_students_stats_for_course_table_data($course_object, $lessons_number = 0, $topics_number = 0)
{
    $table_data = [];
    $students_stats = $course_object->get_students_stats_for_course();
    if (empty($lessons_number) || empty($topics_number)) {
        $content = $course_object->get_course_content_numbers();
        $lessons_number = $content['lessons'];
        $topics_number = $content['topics'];
    }

    $not_started = 0;

    foreach ($students_stats as $stats) {
        if ($stats['status'] === 'not_started') {
            $not_started++;
        }
        $a = [];
        $a['id'] = $stats['id'];
        $a['email'] = $stats['email'];
        $a['first_name'] = $stats['first_name'];
        $a['last_name'] = $stats['last_name'];
        $a['status'] = $stats['status'];
        $a['completed'] = $stats['completed'];
        $a['percentage'] = $stats['percentage'];
        $spent = tred_timestamp($stats['time_spent_in_course']);
        $a['days_spent'] = $spent['days'];
        $a['hours_spent'] = $spent['hours'];
        $a['seconds_spent'] = $stats['time_spent_in_course'];

        $table_data[] = $a;
    }

    $table = [];
    $table['type'] = 'course';
    $table['id'] = 'table-course-filtered-students-stats';
    $table['data'] = $table_data;
    $table['keys_labels'] = [
        'id' => esc_html__('ID', 'learndash-easy-dash'),
        'first_name' => esc_html__('First Name', 'learndash-easy-dash'),
        'last_name' => esc_html__('Last Name', 'learndash-easy-dash'),
        'email' => esc_html__('Email', 'learndash-easy-dash'),
        'status' => esc_html__('Status', 'learndash-easy-dash'),
        'completed' => esc_html__('Completed Steps', 'learndash-easy-dash'),
        'percentage' => esc_html__('Percentage', 'learndash-easy-dash'),
        'days_spent' => esc_html__('Days Spent', 'learndash-easy-dash'),
        'hours_spent' => esc_html__('Hours Spent', 'learndash-easy-dash'),
    ];
    $table['obs'] = 'Total Steps in course: ' . ($lessons_number + $topics_number);

    return [
        'table_data' => $table,
        'not_started' => $not_started
    ];
}

function tred_ld_item_filtered_get_numbers()
{
    if (!isset($_GET['_wpnonce']) || !wp_verify_nonce($_GET['_wpnonce'], 'tred_nonce')) {
        die(__('Security check', 'learndash-easy-dash'));
    }

    //2.4.2
    /**
     * Filter: 'tred_ld_item_filtered_get_numbers_before_processing_request'
     * Allows other plugins to modify the request data before processing.
     * 
     * @param array $request_data Current request data.
     * 
     * Example usage:
     * add_filter('tred_ld_item_filtered_get_numbers_before_processing_request', function($data) {
     *     // Modify $data as needed
     *     return $data;
     * });
     */
    $request_data = apply_filters('tred_ld_item_filtered_get_numbers_before_processing_request', $_REQUEST);

    // 2.4.2
    $action = isset($request_data['action']) ? sanitize_text_field($request_data['action']) : ''; //2.4.2
    $post_id = isset($request_data['post_id']) ? intval($request_data['post_id']) : 0; //2.4.2
    $post_type = isset($request_data['post_type']) ? sanitize_text_field($request_data['post_type']) : ''; //2.4.2
    $post_title = isset($request_data['post_title']) ? sanitize_text_field($request_data['post_title']) : ''; //2.4.2

    $response = [];
    $response['action'] = $action; //2.4.2
    $response['result'] = 'success';
    $response['data'] = [];
    $response['data']['top_boxes'] = [];
    $response['data']['charts'] = [];
    $response['data']['tables'] = [];


    if (empty($post_id) || empty($post_type)) {
        $response['result'] = 'error';
    } //2.4.2




    if ($post_type === 'sfwd-courses') {
        include_once(WP_PLUGIN_DIR . '/easy-dash-for-learndash/includes/class-tred-filtered-ld-item.php');
        $item = new Tred_Filtered_Ld_Item($post_type, $post_id, $post_title);

        //TODO: pie chart for status (not_started, in_progress_completed)

        $comments = tred_comments_by_course_id_or_title($post_id);
        $comments_total = (isset($comments['total'])) ? $comments['total'] : 0;

        $course = $item;
        $completion_stats = $course->get_course_completion_stats();
        $response['data']['top_boxes']['box-course-students-enrolled'] = $completion_stats['students'];
        $response['data']['top_boxes']['box-course-completed'] = $completion_stats['total_completed'];
        $response['data']['top_boxes']['box-course-completed-obs'] = $completion_stats['total_completed_percentage'] . ' of enrolled students';
        $response['data']['top_boxes']['box-course-days'] = $completion_stats['average_days'];
        $response['data']['top_boxes']['box-course-same-day'] = $completion_stats['same_day'];
        $response['data']['top_boxes']['box-course-same-day-obs'] = 'times ' . '(' . $completion_stats['same_day_average_minutes'] . ' minutes/avg)';
        $response['data']['top_boxes']['box-course-comments'] = $comments_total;
        $response['data']['fillers']['tred-fillers-filtered'] = "ID $post_id | " . esc_html__('Mode', 'learndash-easy-dash') . " " . $completion_stats['mode'];

        $content = $course->get_course_content_numbers();
        $groups = $course->get_course_groups();

        $stats_over_time = $course->get_stats_over_time();

        $dissected = $course->dissec_course_lessons_by_activity_completed();

        //lessons completed chart
        $lessons_completed_chart = [];
        $datasets = [];
        $lessons_completed = $dissected['items_completed']['items'];
        $lessons_completed_titles = [];
        $lessons_completed_times = [];
        foreach ($lessons_completed as $c => $v) {
            $lessons_completed_titles[] = $v['title'];
            $lessons_completed_times[] = $v['times'];
        }
        $lessons_completed_chart['id'] = 'chart-filtered-most-completed-lessons';
        $lessons_completed_chart['labels'] = $lessons_completed_titles;
        $datasets[] = [
            'label' => esc_html__('Times', 'learndash-easy-dash'),
            'data' => $lessons_completed_times,
            'borderColor' => 'rgb(54, 162, 235)',
            'backgroundColor' => 'rgba(255, 99, 132, 0.2)'
        ];
        $lessons_completed_chart['datasets'] = $datasets;

        //top groups chart
        $top_groups_chart = [];
        $datasets = [];
        foreach ($groups['groups'] as $v) {
            $groups_titles[] = $v['title'];
            $groups_users[] = $v['users'];
        }
        $top_groups_chart['id'] = 'chart-filtered-item-top-groups';
        $top_groups_chart['labels'] = $groups_titles;
        $datasets[] = [
            'label' => esc_html__('Users', 'learndash-easy-dash'),
            'data' => $groups_users,
            'borderColor' => 'rgb(54, 162, 235)',
            'backgroundColor' => 'rgba(255, 99, 132, 0.2)'
        ];
        $top_groups_chart['datasets'] = $datasets;

        /*
        //COMMENTED OUT FOR NOW
        //students completed chart
        $students_completed_chart = [];
        $datasets = [];
        $students_completed = $dissected['items_completed']['users']; //users? where from?
        $students_completed_emails = [];
        $students_completed_times = [];
        foreach($students_completed as $c => $v){
        $students_completed_emails[] = $v['email'];
        $students_completed_times[] = $v['times'];
        } 
        $students_completed_chart['id'] = 'chart-filtered-students-who-completed-more-lessons';
        $students_completed_chart['labels'] = $students_completed_emails;
        $datasets[] = [
        'label' => 'Times',
        'data' => $students_completed_times,
        'borderColor' => 'rgb(54, 162, 235)',
        'backgroundColor' => 'rgba(255, 99, 132, 0.2)'
        ];
        $students_completed_chart['datasets'] = $datasets;
        */

        //alocating
        $response['data']['top_boxes']['box-course-lessons'] = $content['lessons'];
        $response['data']['top_boxes']['box-course-topics'] = $content['topics'];
        $response['data']['top_boxes']['box-course-quizzes'] = $content['quizzes'];
        $response['data']['top_boxes']['box-course-groups'] = $groups['number'];
        $response['data']['charts'][] = $stats_over_time;
        $response['data']['charts'][] = $lessons_completed_chart;
        $response['data']['charts'][] = $top_groups_chart;
        // $response['data']['charts'][] = $students_completed_chart;

        $table_data = [];
        $students_stats = $course->get_students_stats_for_course();
        $students_for_course_data = tred_ld_students_stats_for_course_table_data($course, $content['lessons'], $content['topics']);
        $not_started = $students_for_course_data['not_started'];
        $table_data = $students_for_course_data['table_data'];
        $response['data']['tables'][] = $table_data;

        $response['data']['top_boxes']['box-course-not-started'] = $not_started;
        $not_started_percentage = tred_percentage($not_started, $completion_stats['students'], 2);
        $response['data']['top_boxes']['box-course-not-started-obs'] = $not_started_percentage . ' of enrolled students';
        //end if is course
    } else if ($post_type === 'groups') {

        include_once(WP_PLUGIN_DIR . '/easy-dash-for-learndash/includes/class-tred-filtered-ld-group.php');
        $group = new Tred_Filtered_Ld_Group($post_id, $post_title);

        $group_courses_data = $group->get_group_courses();
        $group_leaders_data = $group->get_group_administrators();
        $group_users_data = $group->get_group_users();
        $group_quizzes_data = $group->get_group_quizzes();
        $group_course_completions = 0;

        // foreach ($group_users_data['users'] as $user) {
        //     $group_course_completions += $user['progress']['completed'];
        // }

        $response['data']['top_boxes']['box-group-courses'] = $group_courses_data['number'];
        $response['data']['top_boxes']['box-group-quizzes'] = $group_quizzes_data['number'];
        $response['data']['top_boxes']['box-group-leaders'] = $group_leaders_data['number'];
        $response['data']['top_boxes']['box-group-students-members'] = $group_users_data['number'];



        $course_table_data = [];
        $user_table_data = [];
        $group_course_completions = 0;
        $group_users_and_courses = [];

        foreach ($group_courses_data['courses'] as $course) {
            $course_id = $course['id'];
            $course_total_students_from_group = learndash_get_course_groups_users_access($course_id); //return array of ids
            if (!empty($course_total_students_from_group)) {
                //get the array of IDs that are present in $group_users_data['users']
                $course_total_students_from_group = array_intersect($course_total_students_from_group, array_column($group_users_data['users'], 'id'));
            }
            $completions = 0;
            foreach ($course_total_students_from_group as $user_id) {
                $completion_date = learndash_user_get_course_completed_date($user_id, $course_id);
                if ($completion_date) {
                    $completions++;
                }
                if (!isset($group_users_and_courses[$user_id])) {
                    $group_users_and_courses[$user_id] = [];
                }
                $group_users_and_courses[$user_id][] = [
                    'course_id' => $course_id,
                    'completion_date' => $completion_date
                ];
            }
            $group_course_completions += $completions;

            $a = [];
            $a['id'] = $course_id;
            $a['title'] = $course['title'];
            $a['mode'] = $course['mode'];
            $a['students'] = count($course_total_students_from_group);
            $a['completions'] = $completions;
            $a['students_ids'] = $course_total_students_from_group;
            $course_table_data[] = $a;
        }

        $table_courses = [];
        $table_courses['type'] = 'group';
        $table_courses['id'] = 'table-group-course-completions';
        $table_courses['data'] = $course_table_data;
        $table_courses['keys_labels'] = [
            'id' => esc_html__('ID', 'learndash-easy-dash'),
            'title' => esc_html__('Title', 'learndash-easy-dash'),
            'mode' => esc_html__('Mode', 'learndash-easy-dash'),
            'students' => esc_html__('Students', 'learndash-easy-dash'),
            'completions' => esc_html__('Completions', 'learndash-easy-dash'),
            // 'percentage' => esc_html__('Percentage', 'learndash-easy-dash'),
            // 'days_spent' => esc_html__('Days Spent (avg)', 'learndash-easy-dash')
        ];
        $response['data']['tables'][] = $table_courses;


        foreach ($group_users_data['users'] as $user) {
            $a = [];
            $a['id'] = $user['id'];
            $a['name'] = $user['display_name'];
            $a['firstname'] = $user['first_name'];
            $a['lastname'] = $user['last_name'];
            $a['email'] = $user['email'];
            $a['courses'] = 0;
            $a['completions'] = 0;
            if (is_array($group_users_and_courses[$user['id']]) && !empty($group_users_and_courses[$user['id']])) {
                $a['courses'] = count($group_users_and_courses[$user['id']]);
                foreach ($group_users_and_courses[$user['id']] as $course) {
                    if (!empty($course['completion_date'])) {
                        $a['completions']++;
                    }
                }
            }
            $user_table_data[] = $a;
        }

        $table_users = [];
        $table_users['type'] = 'group';
        $table_users['id'] = 'table-group-users';
        $table_users['data'] = $user_table_data;
        $table_users['keys_labels'] = [
            'id' => esc_html__('ID', 'learndash-easy-dash'),
            'name' => esc_html__('Name', 'learndash-easy-dash'),
            'firstname' => esc_html__('First Name', 'learndash-easy-dash'),
            'lastname' => esc_html__('Last Name', 'learndash-easy-dash'),
            'email' => esc_html__('Email', 'learndash-easy-dash'),
            'courses' => esc_html__('Courses', 'learndash-easy-dash'),
            'completions' => esc_html__('Completions', 'learndash-easy-dash'),
            // 'percentage' => esc_html__('Percentage', 'learndash-easy-dash'),
            // 'days_spent' => esc_html__('Days Spent (avg)', 'learndash-easy-dash')
        ];
        $response['data']['tables'][] = $table_users;
        $response['data']['top_boxes']['box-group-course-completions'] = $group_course_completions;
    }

    //2.4.2
    // Before sending the response
    /**
     * Filter: 'tred_ld_item_filtered_get_numbers_before_response'
     * Allows other plugins to modify the response before it is sent.
     * 
     * @param array $response     Data of the response to be sent.
     * @param array $request_data Original request data.
     * @param int $post_id        The post ID.
     * 
     * Example usage:
     * add_filter('tred_ld_item_filtered_get_numbers_before_response', function($response, $request_data, $post_id) {
     *     // Modify $response as needed, possibly using $request_data
     *     return $response;
     * }, 10, 3);
     */
    $response = apply_filters('tred_ld_item_filtered_get_numbers_before_response', $response, $request_data, $post_id);


    echo json_encode($response);
    die();

}

function tred_user_filtered_get_numbers()
{
    if (!isset($_GET['_wpnonce']) || !wp_verify_nonce($_GET['_wpnonce'], 'tred_nonce')) {
        die(__('Security check', 'learndash-easy-dash'));
    }

    //2.4.2
    /**
     * Filter: 'tred_user_filtered_get_numbers_before_processing_request'
     * Allows other plugins to modify the request data before processing.
     * 
     * @param array $request_data Current request data.
     * 
     * Example usage:
     * add_filter('tred_user_filtered_get_numbers_before_processing_request', function($data) {
     *     // Modify $data as needed
     *     return $data;
     * });
     */
    $request_data = apply_filters('tred_user_filtered_get_numbers_before_processing_request', $_REQUEST);

    // 2.4.2
    $action = isset($request_data['action']) ? sanitize_text_field($request_data['action']) : ''; //2.4.2
    $user_id = isset($request_data['user_id']) ? intval($request_data['user_id']) : 0; //2.4.2
    $post_id = isset($request_data['post_id']) ? intval($request_data['post_id']) : 0; //2.4.3

    $response = [];
    $response['action'] = $action; //2.4.2
    $response['result'] = 'success';
    $response['data'] = [];
    $response['data']['top_boxes'] = [];
    $response['data']['charts'] = [];
    $response['data']['tables'] = [];

    if (empty($user_id)) {
        $response['result'] = 'error';
    }

    include_once(WP_PLUGIN_DIR . '/easy-dash-for-learndash/includes/class-tred-filtered-user.php');
    $user = new Tred_Filtered_User($user_id);

    // var_dump($user->data);
    // exit;

    // if (empty($user->data)) {
    //     $response['result'] = 'error';
    //     echo json_encode($response);
    //     die();
    // }


    // $table_data = [
    //     'login' => $user->data->user_login,
    //     'id' => $user->data->ID,
    //     'email' => $user->data->user_email,
    //     'roles' => implode(', ', $user->data->roles),
    //     'registered' => $user->data->user_registered,
    // ];
    // $table = [];
    // $table['type'] = 'user'; 
    // $table['id'] = 'table-user-overview';
    // $table['data'] = [$table_data];
    // $table['keys_labels'] = [
    //     'login' => esc_html__('Login', 'learndash-easy-dash'),
    //     'id' => esc_html__('ID', 'learndash-easy-dash'),
    //     'email' => esc_html__('Email', 'learndash-easy-dash'),
    //     'roles' => esc_html__('Roles', 'learndash-easy-dash'),
    //     'registered' => esc_html__('Registered', 'learndash-easy-dash'),
    // ];
    // $table['obs'] =   '';
    // $response['data']['tables'][] = $table;


    //Table courses overview
    $courses_progress_data = $user->get_courses_progress();
    $courses_progress = $courses_progress_data['progress_numbers'];
    $courses_data = $courses_progress_data['courses_data'];

    $table_courses_data = [];
    $courses_seconds_total = 0; //for top-box
    $courses_titles = []; //for later
    foreach ($courses_progress as $course_id => $progress) {
        $seconds_spent = learndash_get_user_course_attempts_time_spent($user_id, $course_id);
        $courses_seconds_total += intval($seconds_spent);
        $course_title = get_the_title($course_id);
        $courses_titles[$course_id] = $course_title;
        $cp = [];
        $cp['id'] = $course_id;
        $cp['course'] = $course_title;
        $cp['status'] = learndash_course_status_label($progress['status']);
        $cp['total_steps'] = intval($progress['total']);
        $cp['completed'] = intval($progress['completed']);
        $cp['percentage'] = tred_percentage($cp['completed'], $cp['total_steps'], 2);
        $cp['last'] = (!empty($progress['last_id'])) ? learndash_get_content_label(get_post_type($progress['last_id'])) . ': ' . get_the_title($progress['last_id']) : '';
        $cp['seconds_spent'] = $seconds_spent;
        $cp['time'] = tred_timestamp($seconds_spent)['hours'];
        $table_courses_data[] = $cp;
    }

    $table_courses = [];
    $table_courses['type'] = 'user';
    $table_courses['id'] = 'table-user-courses-overview';
    $table_courses['data'] = $table_courses_data;
    $table_courses['keys_labels'] = [
        'course' => esc_html__('Course', 'learndash-easy-dash'),
        'status' => esc_html__('Status', 'learndash-easy-dash'),
        'total_steps' => esc_html__('Total Steps', 'learndash-easy-dash'),
        'completed' => esc_html__('Completed', 'learndash-easy-dash'),
        'percentage' => esc_html__('Percentage', 'learndash-easy-dash'),
        'last' => esc_html__('Last', 'learndash-easy-dash'),
        'time' => esc_html__('Hours Spent', 'learndash-easy-dash'),
    ];
    $table_courses['obs'] = '';
    $response['data']['tables'][] = $table_courses;


    //Table groups overview
    $groups = $user->get_groups_and_its_users();
    $table_groups_data = [];
    foreach ($groups as $g) {
        $group_courses = learndash_group_enrolled_courses($g['group']);
        $group_courses_completed = 0;
        foreach ($group_courses as $course_id) {
            if (!empty($courses_progress[$course_id])) {
                if ($courses_progress[$course_id]['status'] == 'completed') {
                    $group_courses_completed++;
                }
            }
        }
        $time_spent = 0;
        foreach ($table_courses_data as $cp) {
            if (in_array($cp['id'], $group_courses)) {
                $time_spent += $cp['seconds_spent'];
            }
        }

        $gp = [];
        $gp['group'] = get_the_title($g['group']);
        $gp['users'] = $g['users'];
        $gp['courses'] = count($group_courses);
        $gp['completed'] = $group_courses_completed;
        $gp['time'] = round($time_spent / 3600, 1);
        $gp['seconds_spent'] = $time_spent;
        $table_groups_data[] = $gp;
    }

    $table_groups = [];
    $table_groups['type'] = 'user';
    $table_groups['id'] = 'table-user-groups-overview';
    $table_groups['data'] = $table_groups_data;
    $table_groups['keys_labels'] = [
        'group' => esc_html__('Group', 'learndash-easy-dash'),
        'users' => esc_html__('Total Members', 'learndash-easy-dash'),
        'courses' => esc_html__('Associated Courses', 'learndash-easy-dash'),
        'completed' => esc_html__('Completed Courses', 'learndash-easy-dash'),
        'time' => esc_html__('Hours Spent', 'learndash-easy-dash'),
    ];
    $table_groups['obs'] = '';
    $response['data']['tables'][] = $table_groups;

    //Table activity overview
    //courses titles are saved in $courses_titles
    $activity = $user->get_activity();
    $table_activity_data = [];
    foreach ($activity as $a) {
        $group_courses = learndash_group_enrolled_courses($g['group']);
        $group_courses_completed = 0;
        foreach ($group_courses as $course_id) {
            if (!empty($courses_progress[$course_id])) {
                if ($courses_progress[$course_id]['status'] == 'completed') {
                    $group_courses_completed++;
                }
            }
        }
        $time_spent = 0;
        foreach ($table_courses_data as $cp) {
            if (in_array($cp['id'], $group_courses)) {
                $time_spent += $cp['seconds_spent'];
            }
        }

        $gp = [];
        $gp['group'] = get_the_title($g['group']);
        $gp['users'] = $g['users'];
        $gp['courses'] = count($group_courses);
        $gp['completed'] = $group_courses_completed;
        $gp['time'] = round($time_spent / 60, 1);
        $gp['seconds_spent'] = $time_spent;
        $table_activity_data[] = $gp;
    }

    $table_groups = [];
    $table_groups['type'] = 'user';
    $table_groups['id'] = 'table-user-groups-overview';
    $table_groups['data'] = $table_activity_data;
    $table_groups['keys_labels'] = [
        'group' => esc_html__('Group', 'learndash-easy-dash'),
        'users' => esc_html__('Total Members', 'learndash-easy-dash'),
        'courses' => esc_html__('Associated Courses', 'learndash-easy-dash'),
        'completed' => esc_html__('Completed Courses', 'learndash-easy-dash'),
        'time' => esc_html__('Hours Spent', 'learndash-easy-dash'),
    ];
    $table_groups['obs'] = '';
    $response['data']['tables'][] = $table_groups;


    //Boxes
    $response['data']['top_boxes']['box-user-courses-enrolled'] = count($table_courses_data);
    $response['data']['top_boxes']['box-user-groups-member'] = count($table_groups_data);
    $response['data']['top_boxes']['box-user-courses-points'] = $user->get_course_points();
    $response['data']['top_boxes']['box-user-courses-hours'] = round($courses_seconds_total / 3600, 1);
    $response['data']['top_boxes']['box-user-courses-comments'] = tred_comments_by_user_id_or_email($user_id);


    //2.4.2
    // Before sending the response
    /**
     * Filter: 'tred_user_filtered_get_numbers_before_response'
     * Allows other plugins to modify the response before it is sent.
     * 
     * @param array $response     Data of the response to be sent.
     * @param array $request_data Original request data.
     * @param int $user_id        The user ID.
     * @param int $post_id        The post ID.
     * 
     * Example usage:
     * add_filter('tred_user_filtered_get_numbers_before_response', function($response, $request_data, $user_id, $post_id) {
     *     // Modify $response as needed, possibly using $request_data
     *     return $response;
     * }, 10, 4);
     */
    $response = apply_filters('tred_user_filtered_get_numbers_before_response', $response, $request_data, $user_id, $post_id);

    echo json_encode($response);
    die();
}

function tred_ld_save_panel()
{
    if (!isset($_POST['_wpnonce']) || !wp_verify_nonce($_POST['_wpnonce'], 'tred_nonce')) {
        die(__('Security check', 'learndash-easy-dash'));
    }
    $response = [];
    $response['action'] = sanitize_text_field($_POST['action']);
    $response['result'] = 'success';

    if (!is_array($_POST['visible_widgets']) || empty($_POST['visible_widgets'])) {
        $response['result'] = 'error in widgets';
    }
    if (empty($_POST['panel_type']) || empty($_POST['panel_item'])) {
        $response['result'] = 'error in panels';
    }

    if ($response['result'] == 'success') {
        $widgets_to_show = $_POST['visible_widgets'];
        //make sure all widgets are integers
        foreach ($widgets_to_show as $k => $v) {
            $widget = intval($v);
            if (!$widget) {
                continue;
            }
            $widgets_to_show[$k] = $widget;
        }
        $panel_type = sanitize_text_field($_POST['panel_type']);
        $panel_item = sanitize_text_field($_POST['panel_item']);

        //Get entire array
        $opts = get_option('tred_panel_widgets_to_show');
        if (!is_array($opts) || empty($opts)) {
            $opts = [];
        }
        //Alter the options array appropriately
        $opts[$panel_type][$panel_item] = $widgets_to_show;
        //Update entire array
        $updated = update_option('tred_panel_widgets_to_show', $opts);

        if (!$updated) {
            $response['result'] = 'nothing to update or error in saving';
        }
    }

    echo json_encode($response);
    die();
}

function tred_ld_get_widget_options()
{
    if (!isset($_GET['_wpnonce']) || !wp_verify_nonce($_GET['_wpnonce'], 'tred_nonce')) {
        die(__('Security check', 'learndash-easy-dash'));
    }
    $response = [];
    $response['action'] = sanitize_text_field($_GET['action']);
    $response['result'] = 'success';

    if (empty($_GET['panel_type']) || empty($_GET['panel_item'])) {
        $response['result'] = 'error in panels';
    }

    if ($response['result'] == 'success') {
        $panel_type = sanitize_text_field($_GET['panel_type']);
        $panel_item = sanitize_text_field($_GET['panel_item']);
        $response['widgets'] = [];

        //Get entire array options
        $opts = get_option('tred_panel_widgets_to_show');
        if (is_array($opts) && !empty($opts)) {
            if (isset($opts[$panel_type][$panel_item])) {
                $response['widgets'] = $opts[$panel_type][$panel_item];
            }
        }
    }

    echo json_encode($response);
    die();

}

//not in use...
function tred_ld_compliance_report()
{
    if (!isset($_REQUEST['_wpnonce']) || !wp_verify_nonce($_REQUEST['_wpnonce'], 'tred_nonce')) {
        die(__('Security check', 'learndash-easy-dash'));
    }
    $response = [];
    $response['action'] = sanitize_text_field($_REQUEST['action']);

    $data_array = tredCustom_get_compliance_table_report_data();
    $table_data = [];
    $table = [];
    foreach ($data_array as $d) {
        $td = [];
        $td['student'] = $d['name'];
        foreach ($d['courses_statuses'] as $course => $status) {
            $td[$course] = $status;
        }
        $table_data[] = $td;
    }
    $table['keys_labels'] = [
        'student' => esc_html__('Student', 'learndash-easy-dash')
    ];
    foreach ($data_array[0]['courses_statuses'] as $course => $status) {
        $table['keys_labels'][$course] = $course;
    }

    $table['type'] = 'global';
    $table['id'] = 'table-students-courses-compliance-report';
    $table['data'] = $table_data;
    $table['obs'] = '';

    $response['result'] = 'success';
    $response['data'] = [];
    $response['data']['tables'][] = $table;


    echo json_encode($response);
    die();
}