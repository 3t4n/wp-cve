<?php

/*
$args = array(
'post_from'			=> 953,
'post_to'           => 321,
'connection_type'   => array('lesson-to-course', 'module-to-course'),
'order_by'          => 'menu_order',
'order'             => 'asc',
'limit'				=> 10,
'join'              => false,
'join_on'			=> "post_to"
);
*/

function wpc_get_connected($args)
{
    global $wpdb;
    $table_name = $wpdb->prefix . "wpc_connections";
    $posts_table = $wpdb->prefix . "posts";

    if (isset($args['join']) && $args['join'] === true) {
        $join_on = isset($args['join_on']) ? $args['join_on'] : "post_from";
        $sql = "SELECT * FROM {$table_name} LEFT JOIN {$posts_table} ON {$posts_table}.ID={$table_name}.{$join_on} ";
    } else {
        $sql = "SELECT * FROM {$table_name} ";
    }

    if (isset($args['connection_type'])) {
        $where = " WHERE ";
        $count = 1;
        $length = count($args['connection_type']);
        foreach ($args['connection_type'] as $type) {
            $where .= "{$table_name}.connection_type = '{$type}' ";
            $where .= isset($args['post_from']) ? "AND {$table_name}.post_from = {$args['post_from']} " : '';
            $where .= isset($args['post_to']) ? "AND {$table_name}.post_to = {$args['post_to']} " : '';
            $where .= $count < $length ? ' OR ' : '';
            $count++;
        }
    } elseif (isset($args['post_from']) || isset($args['post_to'])) {
        $where = " WHERE ";
        $where .= isset($args['post_from']) ? "{$table_name}.post_from = {$args['post_from']} " : '';
        $where .= isset($args['post_from']) && isset($args['post_to']) ? ' AND ' : '';
        $where .= isset($args['post_to']) ? "{$table_name}.post_to = {$args['post_to']} " : '';
    } else {
        $where = '';
    }

    $order_table = (isset($args['order_posts_table']) && $args['order_posts_table'] === true) ? $posts_table : $table_name;
    $order = isset($args['order_by']) ? " ORDER BY {$order_table}.{$args['order_by']} " : '';
    $order .= isset($args['order']) ? $args['order'] : '';
    $order .= isset($args['limit']) ? " LIMIT " . $args['limit'] : '';

    $sql = $sql . $where . $order;
    $results = $wpdb->get_results($sql);
    return empty($results) ? false : $results;
}

function wpc_get_connected_course_ids($lesson_id, $connection_type = 'lesson-to-course')
{
    $course_ids = array();
    global $wpdb;
    $table_name = $wpdb->prefix . "wpc_connections";
    $sql = "SELECT post_to FROM $table_name WHERE post_from = %d AND connection_type = '{$connection_type}' ";
    $results = $wpdb->get_results(
        $wpdb->prepare(
            $sql,
            $lesson_id
        ),
        ARRAY_N
    );
    if (!empty($results)) {
        foreach ($results as $result) {
            $course_ids[] = $result[0];
        }
        return $course_ids;
    } else {
        return array();
    }
}

function wpc_get_first_connected_course($lesson_id, $connection_type = 'lesson-to-course')
{
    global $wpdb;
    $table_name = $wpdb->prefix . "wpc_connections";
    $sql = "SELECT post_to FROM $table_name WHERE post_from = %d AND connection_type = '{$connection_type}' LIMIT 1";
    $results = $wpdb->get_results(
        $wpdb->prepare(
            $sql,
            $lesson_id
        )
    );
    if (!empty($results)) {
        return $results[0]->post_to;
    } else {
        return false;
    }
}

function wpc_count_connected($course_id, $connection_type = 'lesson-to-course')
{
    global $wpdb;
    $table_name = $wpdb->prefix . "wpc_connections";
    $posts_table = $wpdb->prefix . "posts";
    $sql = "SELECT post_from, post_status FROM {$table_name} LEFT JOIN {$posts_table} ON {$posts_table}.ID={$table_name}.post_from WHERE connection_type = '{$connection_type}' AND post_to = {$course_id} AND post_status = 'publish'";

    $results = $wpdb->get_results($sql);
    $count = count($results);
    return $count;
}

function wpc_get_previous_and_next_lesson_ids($lesson_id, $course_id)
{
    $args = array(
        'post_to' => $course_id,
        'connection_type' => array('lesson-to-course', 'quiz-to-course'),
        'order_by' => 'menu_order',
        'order' => 'asc',
        'join' => true,
        'join_on' => "post_from"
    );

    $lessons_all = wpc_get_connected($args);
    $lessons = [];

    if ($lessons_all) {
        foreach ($lessons_all as $lesson) {
            if ($lesson->post_type == 'lesson' || $lesson->post_type == 'wpc-quiz') {
                array_push($lessons, $lesson);
            }
        }
    }

    $prev_id = false;
    $next_id = false;

    if (!empty($lessons)) {
        $lessons_number = count($lessons);

        for ($lesson_index = 0; $lesson_index < $lessons_number; $lesson_index++) {
            if ($lessons[$lesson_index]->post_from == $lesson_id) {
                $index_prev = $lesson_index - 1;
                $index_next = $lesson_index + 1;

                if ($index_prev >= 0) {
                    $prev_id = $lessons[$index_prev]->post_from;
                }

                if ($index_next < $lessons_number) {
                    $next_id = $lessons[$index_next]->post_from;
                }
            }
        }
    }

    return array(
        'prev_id' => $prev_id,
        'next_id' => $next_id
    );
}

/*
$args = array(
'post_from'			=> (int),
'post_to'			=> array(),
'connection_type'	=> 'lesson-to-course'
'exclude_from'      => array() // optional
);
*/

function wpc_create_connections($args)
{

    global $wpdb;
    $table_name = $wpdb->prefix . 'wpc_connections';

    if (count($args['post_to']) < 1) {
        $sql = "DELETE FROM $table_name WHERE connection_type = %s AND post_from = %d";
        $wpdb->query(
            $wpdb->prepare(
                $sql,
                $args['connection_type'],
                $args['post_from']
            )
        );
        return;
    }

    $insert_to = !empty($args['exclude_from']) ? array_diff($args['post_to'], $args['exclude_from']) : $args['post_to'];
    $delete_to = !empty($args['exclude_from']) ? array_diff($args['exclude_from'], $args['post_to']) : $args['post_to'];

    $del_sql = "DELETE FROM $table_name WHERE connection_type = %s AND post_from = %d AND post_to = %d";

    foreach ($delete_to as $delete) {
        $wpdb->query(
            $wpdb->prepare(
                $del_sql,
                $args['connection_type'],
                $args['post_from'],
                $delete
            )
        );
    }

    foreach ($insert_to as $insert) {
        $wpdb->insert(
            $table_name,
            array(
                "post_from" => $args['post_from'],
                "post_to" => $insert,
                "connection_type" => $args['connection_type'],
            ),
            array("%d", "%d", "%s")
        );
    }
}

function wpc_get_course_first_lesson_id($course_id)
{
    global $wpdb;
    $table_name = $wpdb->prefix . 'wpc_connections';
    $posts_table = $wpdb->posts;
    $sql = "SELECT {$table_name}.post_from FROM $table_name INNER JOIN $posts_table ON {$posts_table}.ID={$table_name}.post_from WHERE {$table_name}.post_to = $course_id AND {$table_name}.connection_type = 'lesson-to-course' AND {$posts_table}.post_status = 'publish' ORDER BY {$table_name}.menu_order ASC LIMIT 1";

    $result = $wpdb->get_results($sql);

    if (isset($result[0])) {
        return (int) $result[0]->post_from;
    } else {
        return false;
    }
}

function wpc_get_course_first_uncompleted_lesson_id($course_id)
{
    global $wpdb;
    $table_name = $wpdb->prefix . 'wpc_connections';
    $posts_table = $wpdb->posts;
    $table_tracking = $wpdb->prefix . 'wpc_tracking';
    $user_id = get_current_user_id();

    if ($user_id) { // If user is logged in, return first uncompleted lesson
        // 1) Get completed status of user
        $sqlCompletedStatus = "
            SELECT 
                post_id, completed
            FROM 
                $table_tracking
            WHERE 
                course_id = $course_id 
                AND user_id = $user_id
        ";
        $resultCompletedStatus = $wpdb->get_results($sqlCompletedStatus);

        $resultCompletedStatusIds = [];
        foreach ($resultCompletedStatus as $key => $value ) {
            $resultCompletedStatusIds[$value->post_id] = $value->completed;
        }

        // 2) Get lessons in correct order, if combined / joined with query above then lesson order is wrong
        $sqlLessonIds = "
            SELECT 
                {$table_name}.post_from
            FROM 
                $table_name 
            INNER JOIN $posts_table ON {$table_name}.post_from={$posts_table}.ID
            WHERE 
                {$table_name}.post_to = $course_id 
                AND {$table_name}.connection_type = 'lesson-to-course' 
                AND {$posts_table}.post_status = 'publish' 
            ORDER BY 
                {$table_name}.menu_order ASC
        ";
        $resultLessonIds = $wpdb->get_results($sqlLessonIds);

        if (count($resultLessonIds) === 0) {
            return false;
        }

        // 3) Find last uncompleted lesson
        $lastLessonId = null;
        foreach ($resultLessonIds as $key => $value) {
            $completed = isset($resultCompletedStatusIds[$value->post_from]) ? $resultCompletedStatusIds[$value->post_from] : null;

            if (($completed === '0' || $completed == null) && empty($lastLessonId)) {
                $lastLessonId = $value->post_from;
                break;
            }
        }

        if ($lastLessonId == null) {
            $lastLesson = end($resultLessonIds);
            $lastLessonId = $lastLesson->post_from;
        }

        return $lastLessonId;
    } else {
        return wpc_get_course_first_lesson_id($course_id); // If user is not logged in, return first lesson
    }
}

function wpc_get_connected_teachers($course_id)
{
    global $wpdb;
    $table_name = $wpdb->prefix . "wpc_connections";
    $sql = "SELECT post_to FROM $table_name WHERE post_from = {$course_id} AND connection_type = 'course-to-teacher'";
    $results = $wpdb->get_results($sql, ARRAY_N);
    if (!empty($results)) {
        foreach ($results as $result) {
            if ($result[0] === -1) {
                return false;
            }
            $ids[] = $result[0];
        }
        return $ids;
    } else {
        return false;
    }
}

function wpc_get_connected_lessons($course_id)
{
    $args = array(
        'post_to' => $course_id,
        'connection_type' => array('lesson-to-course', 'quiz-to-course'),
        'order_by' => 'menu_order',
        'order' => 'asc',
        'join' => true,
        'join_on' => "post_from"
    );

    $lessons_all = wpc_get_connected($args);
    $lessons = [];

    if ($lessons_all) {
        foreach ($lessons_all as $lesson) {
            if ($lesson->post_type == 'lesson' || $lesson->post_type == 'wpc-quiz') {
                array_push($lessons, $lesson);
            }
        }
    }

    return $lessons;
}