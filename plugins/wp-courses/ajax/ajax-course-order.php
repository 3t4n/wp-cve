<?php
add_action('admin_footer', 'wpc_order_course_javascript');
function wpc_order_course_javascript()
{
    $ajax_nonce = esc_js(wp_create_nonce('wpc-order-course')); ?>
    <script type="text/javascript">
        jQuery(document).ready(function () {
            jQuery(".wpc-course-list").sortable({
                axis: 'y',
                update: function (event, ui) {
                    var posts = [];
                    jQuery('.wpc-course-list li').each(function (key, value) {
                        posts.push({
                            'postID': jQuery(this).attr('data-id'),
                            'menuOrder': key,
                        });
                    });
                    var data = {
                        'action': 'order_course',
                        'posts': posts,
                        'security': '<?php echo $ajax_nonce; ?>',
                    };

                    wpcShowAjaxIcon();
                    jQuery.post(ajaxurl, data, function (response) {
                        wpcHideAjaxIcon();
                    });
                }
            });
        });
    </script>
<?php
}

add_action('wp_ajax_order_course', 'wpc_order_course_action_callback');
function wpc_order_course_action_callback()
{
    check_ajax_referer('wpc-order-course', 'security');

    if (!current_user_can('administrator')) {
        wp_die();
    }

    global $wpdb;
    $posts = isset($_POST['posts']) ? wp_unslash($_POST['posts']) : array();

    foreach ($posts as $post) {
        $order = (int)$post['menuOrder'];
        $id = (int)$post['postID'];
        $wpdb->query(
            $wpdb->prepare(
                "UPDATE $wpdb->posts SET menu_order = %d WHERE ID = %d",
                $order,
                $id
            )
        );
    }
    wp_die(); // required
}