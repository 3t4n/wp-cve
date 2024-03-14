<?php
// Function for saving lesson order and modules
function update_lesson_order_and_meta($posts) {
    global $wpdb;
    $table_name = $wpdb->prefix . 'wpc_connections';

    foreach ($posts as $post) {
        $sql3 = $wpdb->prepare("DELETE FROM $table_name WHERE connection_type = 'lesson-to-module' AND post_from = %d", $post['postID']);
        $wpdb->query($sql3);
    }

    $current_module = null;
    foreach ($posts as $post) {
        $id = (int)$post['postID'];
        $course_id = (int)$post['courseID'];
        $order = (int)$post['menuOrder'];
        $postType = sanitize_title_with_dashes($post['postType']);
        if ($postType == 'lesson' || $postType == 'wpc-quiz') {
            $wpdb->query(
                $wpdb->prepare(
                    "UPDATE $table_name SET menu_order = %d WHERE connection_type IN ('lesson-to-course', 'quiz-to-course') AND post_from = %d AND post_to = %d",
                    $order,
                    $id,
                    $course_id
                )
            );

            // Connect lessons to module
            $sql = $wpdb->prepare("INSERT INTO $table_name (post_from, post_to, connection_type) VALUES (%d, %d, 'lesson-to-module')", $id, $current_module);
            $wpdb->query($sql);
        } elseif ($postType == 'wpc-module') {
            $current_module = $id;

            // Update module to course order
            $sql = $wpdb->prepare("UPDATE $table_name SET menu_order = %d WHERE connection_type = 'module-to-course' AND post_from = %d AND post_to = %d", $order, $current_module, $course_id);
            $wpdb->query($sql);
        }
    }
}

// Order lessons
add_action('wp_ajax_order_lessons', 'wpc_order_lessons_action_callback');

function wpc_order_lessons_action_callback() {
    check_ajax_referer('wpc_nonce', 'security');

    if (!current_user_can('administrator')) {
        wp_die();
    }

    update_lesson_order_and_meta($_POST['posts']);
    wp_die(); // Required
}

// Add module
add_action('wp_ajax_add_module', 'wpc_add_module_action_callback');

function wpc_add_module_action_callback() {
    check_ajax_referer('wpc_nonce', 'security');

    if (!current_user_can('administrator')) {
        wp_die();
    }

    $post_ID = wp_insert_post(array(
        'post_title'   => '',
        'post_type'    => 'wpc-module',
        'post_status'  => 'publish',
    ));

    $args = array(
        'post_from'         => $post_ID,
        'post_to'           => array((int)$_POST['course_id']),
        'connection_type'  => 'module-to-course',
    );

    wpc_create_connections($args);
    update_lesson_order_and_meta($_POST['posts']);
    echo json_encode($post_ID);
    wp_die(); // Required
}

// Delete module
add_action('wp_ajax_delete_module', 'wpc_delete_module_action_callback');

function wpc_delete_module_action_callback() {
    check_ajax_referer('wpc_nonce', 'security');

    if (!current_user_can('administrator')) {
        wp_die();
    }

    $module_id = (int)$_POST['module_id'];
    $course_id = (int)$_POST['course_id'];
    wp_delete_post($module_id);
    global $wpdb;
    $table_name = $wpdb->prefix . 'wpc_connections';
    $sql = $wpdb->prepare("DELETE FROM $table_name WHERE post_from = %d AND post_to = %d AND connection_type = 'module-to-course'", $module_id, $course_id);
    $wpdb->query($sql);

    $sql = $wpdb->prepare("DELETE FROM $table_name WHERE connection_type = 'lesson-to-module' AND post_to = %d", $module_id);
    $wpdb->query($sql);

    update_lesson_order_and_meta($_POST['posts']);
    wp_die(); // Required
}

// Set the module's name
add_action('wp_ajax_rename_module', 'wpc_rename_module_action_callback');

function wpc_rename_module_action_callback() {
    check_ajax_referer('wpc_nonce', 'security');

    if (!current_user_can('administrator')) {
        wp_die();
    }

    $my_post = array(
        'ID'           => (int)$_POST['module_id'],
        'post_title'   => sanitize_text_field($_POST['module_title']),
    );
    wp_update_post($my_post);
    wp_die(); // Required
}
?>
