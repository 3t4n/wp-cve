<?php
add_action('admin_footer', 'wpc_change_course_javascript');
function wpc_change_course_javascript()
{
    $ajax_nonce = wp_create_nonce("request-is-good-wpc"); ?>
    <script type="text/javascript">
        jQuery(document).ready(function () {
            jQuery('.wpc-course-multiselect').change(function () {
                var parent = jQuery(this).parent().parent();
                var data = {
                    'security': "<?php echo esc_js($ajax_nonce); ?>",
                    'action': 'change_course',
                    'course_id': jQuery(this).val(),
                    'lesson_id': parent.attr('id').replace('post-', ''),
                    'connection_type': jQuery(this).prop('class')
                }

                wpcShowAjaxIcon();
                jQuery.post(ajaxurl, data, function (response) {
                    wpcHideAjaxIcon();
                });

            });
        });
    </script>
    <?php
}

add_action('wp_ajax_change_course', 'wpc_change_course_action_callback');
function wpc_change_course_action_callback()
{
    check_ajax_referer('request-is-good-wpc', 'security');

    if (!current_user_can('administrator')) {
        wp_die();
    }

    $lesson_id = isset($_POST['lesson_id']) ? absint($_POST['lesson_id']) : 0;
    $course_ids = !empty($_POST['course_id']) ? array_map('absint', $_POST['course_id']) : array(-1);

    // Don't allow selection of "none" as well as other connected courses
    if (count($course_ids) > 1) {
        $course_ids = array_diff($course_ids, array(-1));
    }

    $connection_type = sanitize_text_field($_POST['connection_type']);
    $connection_type = str_contains($connection_type, 'wpc-lesson-type-quiz') ? 'quiz-to-course' : 'lesson-to-course';

    $exclude_ids = wpc_get_connected_course_ids($lesson_id, $connection_type);

    $args = array(
        'post_from' => $lesson_id,
        'post_to' => $course_ids,
        'connection_type' => $connection_type,
        'exclude_from' => $exclude_ids
    );

    wpc_create_connections($args);

    wp_die(); // Required
}
