<?php

/**
 * Plugin Name: WP Courses LMS
 * Description: Create unlimited online courses on your WordPress website with WP Courses LMS.
 * Version: 3.2.17
 * Author: WP Courses
 * Plugin URI: https://wpcoursesplugin.com
 * Author URI: https://wpcoursesplugin.com
 * Text Domain: wp-courses
 * Domain Path: /lang
 * License: GPL2
 */

defined('ABSPATH') or die("No script kiddies please!");
define("WPC_PLUGIN_URL", plugin_dir_url(__FILE__));

function wpc_is_premium_active()
{

    if (!defined('WPCP_VERSION')) {
        define('WPCP_VERSION', false);
    }

    if (function_exists('wpc_woo_has_bought') || function_exists('wpcp_ext_admin_notice_failure')) {
        define('WPCP_ACTIVE', true);
    } else {
        define('WPCP_ACTIVE', false);
    }
}

add_action('plugins_loaded', 'wpc_is_premium_active');

unregister_post_type('wpc-quiz');

include 'functions/functions.php';
include 'functions/security.php';
include 'functions/requirements.php';
include 'functions/tracking.php';
include 'functions/connections.php';
include 'functions/output.php';
include 'functions/quizzes.php';
include 'functions/render-ajax-components.php';
include 'integrations/pmpro.php';
include 'integrations/woo.php';
include 'legacy/update.php';
include 'legacy/depricated.php';
include 'db/db-tables.php';
include 'init/cp-types.php';
include 'init/taxonomies.php';
include 'init/enqueue.php';
include 'classes/WPC_Ajax.php';
include 'classes/WPC_Ajax_Components.php';
include 'classes/WPCQ_Ajax.php';
include 'classes/WPC_Shortcodes.php';
include 'init/templates.php';
include 'init/style-options.php';
include 'admin/wpc-options.php';
include 'admin/lesson-meta.php';
include 'admin/course-meta.php';
include 'admin/requirements-meta.php';
include 'admin/admin-menu.php';
include 'admin/columns.php';
include 'admin/front-end-editor.php';
include 'admin/widgets.php';
include 'admin/quiz-meta.php';
include 'cron/cron.php';
include 'ajax/ajax.php';
include 'ajax/ajax-survey.php';
include 'ajax/ajax-lesson-order.php';
include 'ajax/ajax-lesson-change-restriction.php';
include 'ajax/ajax-course-order.php';
include 'ajax/ajax-course-change.php';
include 'ajax/ajax-user-meta.php';

// Redirect on activation to welcome page
register_activation_hook(__FILE__, 'wpc_plugin_activate');
add_action('admin_init', 'wpc_plugin_redirect');

function wpc_plugin_activate()
{
    set_transient('wpc_dismiss_survey_short', 'true', 86400); // Skip rating screen for plugin activation
    add_option('wpc_plugin_do_activation_redirect', true);
    }

function wpc_plugin_redirect()
{
    if (get_option('wpc_plugin_do_activation_redirect', false)) {
        delete_option('wpc_plugin_do_activation_redirect');
        if (!isset($_GET['activate-multi'])) {
            wp_redirect("admin.php?page=wpc_help");
        }
    }
}

function wpc_old_woo_admin_notice_failure()
{
    // Check if PMPro is active
    if (is_plugin_active('wp-courses-woocommerce/wp-courses-woocommerce.php')) { ?>
        <div class="notice notice-error is-dismissible">
            <p>WP Courses WooCommerce Integration is not fully compatible with your version of WP Courses. All add-ons for WP
                Courses now reside in one add-on called WP Courses Premium. <a href="https://wpcoursesplugin.com/lesson/upgrading-wp-courses-woocommerce-integration-for-3-0/?course_id=958">Update
                    instructions can be found here</a>.</p>
        </div>
    <?php
    }
}
add_action('admin_notices', 'wpc_old_woo_admin_notice_failure');

// AJAX compatibility with DIVI
add_filter('et_builder_load_requests', function ($builder_load_requests) {
    $builder_load_requests['action'][] = 'wpc_lesson';
    $builder_load_requests['action'][] = 'wpc_teacher';
    $builder_load_requests['action'][] = 'wpc_course';
    return $builder_load_requests;
});

// Append course_id GET to post edit link
function wpc_append_query_string($url, $post)
{
    if (is_admin() == true) {
        $post_type = get_post_type($post->ID);
        if ('lesson' == $post_type || 'wpc-quiz' == $post_type) {
            $last_viewed_course_id = get_user_meta(get_current_user_id(), 'wpc-last-viewed-course', true);
            if (isset($_GET['course_id'])) {
                $course_id = $_GET['course_id'];
            } else {
                $course_id = wpc_get_first_connected_course($post->ID);
            }

            if (!empty($course_id)) {
                return add_query_arg(array('course_id' => $course_id), $url);
            } else {
                return $url;
            }
        }
        return $url;
    } else {
        return $url;
    }
}
add_filter('post_type_link', 'wpc_append_query_string', 10, 2);

// Changing excerpt more
function wpc_remove_read_more_excerpt($more)
{
    global $post;
    if (get_post_type($post->ID) === 'course') {
        remove_filter('excerpt_more', 'new_excerpt_more');
        return '';
    } else {
        return $more;
    }
}
add_filter('excerpt_more', 'wpc_remove_read_more_excerpt', 11);

// Add links below plugin on plugins page
function wpc_action_links($links)
{
    $premium_button = '<a style="font-weight:bold; color: #e21772;" href="' . esc_url(admin_url('/admin.php?page=wpc_premium')) . '">' . __('Upgrade to Premium', 'wp-courses') . '</a>';
    $links = array_merge(
        array(
            '<a href="' . esc_url(admin_url('/admin.php?page=wpc_help')) . '">' . __('Setup and Help', 'wp-courses') . '</a>',
            '<a href="' . esc_url(admin_url('/edit.php?post_type=course')) . '">' . __('All Courses', 'wp-courses') . '</a>',
            '<a href="' . esc_url(admin_url('/edit.php?post_type=lesson')) . '">' . __('All Lessons', 'wp-courses') . '</a>',
        ),
        $links
    );

    if (WPCP_ACTIVE === false) {
        array_unshift($links, $premium_button);
    }

    return $links;
}
add_action('plugin_action_links_' . plugin_basename(__FILE__), 'wpc_action_links');

// Use ajax in the front-end
add_action('wp_head', 'wp_courses_ajaxurl');

function wp_courses_ajaxurl()
{
    echo '<script type="text/javascript">
           var ajaxurl = "' . admin_url('admin-ajax.php') . '";
         </script>';
}

// Add empty lightbox and ajax save icon to footer
add_action('wp_footer', 'wpc_ui_components');

add_action('admin_footer', 'wpc_ui_components');

function wpc_ui_components()
{ ?>
    <div id="wpc-right-toggle-sidebar" class="wpc-right-toggle-sidebar" data-visible="false">
        <div class="wpc-toggle-sidebar-header"><i class="fa-solid fa-xmark wpc-toggle-sidebar"></i></div>
        <div class="wpc-toggle-sidebar-content"></div>
    </div>
    <div id="wpc-bottom-toggle-sidebar" class="wpc-bottom-toggle-sidebar" data-visible="false">
        <div class="wpc-bottom-toggle-sidebar-header"><i class="fa-solid fa-xmark wpc-close-bottom-sidebar"></i></div>
        <div class="wpc-toggle-bottom-sidebar-content"></div>
    </div>
    <div style="display:none;" id="wpc-full-screen-loader">
        <div id="wpc-full-screen-loader-inner"><span class="plus-loader">Loading&#8230;</span></div>
    </div>

    <div class="wpc-lightbox-wrapper" style="display: none;">
        <div class="wpc-lightbox">
            <div class="wpc-lightbox-close-wrapper">
                <h2 class="wpc-lightbox-title"></h2>
                <div class="wpc-lightbox-close"><i class="fa fa-times"></i></div>
            </div>
            <div class="wpc-lightbox-content">

            </div>
            <div style="display: none;" class="wpc-pagination wpc-lightbox-pagination"></div>
        </div>
    </div>

    <div id="wpc-ajax-save" class="fa-2x" style="display: none;"><i></i></div>
<?php }

// Admin lesson course filter
add_action('restrict_manage_posts', 'wpc_course_filter_select');

function wpc_course_filter_select()
{
    if (!empty($_GET['post_type'])) {
        $post_type = sanitize_title_with_dashes($_GET['post_type']);
        if ($post_type == 'lesson') {
            global $wpdb;
            $sql = 'SELECT DISTINCT ID, post_title, post_status FROM ' . $wpdb->posts . ' WHERE post_type = "course" AND post_status = "publish" OR post_type = "course" AND post_status = "draft" ORDER By post_title';
            $results = $wpdb->get_results($sql);

            echo '<select name="wpc-course-filter" class="wpc-admin-select">';

            echo '<option value="all">' . __('All Courses', 'wp-courses') . '</option>';
            echo '<option value="none">' . __('None', 'wp-courses') . '</option>';

            foreach ($results as $result) {
                echo '<option value="' . (int) $result->ID . '">' . esc_html($result->post_title) . '</option>';
            }

            echo '</select>';
        }
    }
}

add_action('pre_get_posts', 'wpc_admin_filter_lessons_by_course');

function wpc_admin_filter_lessons_by_course($query)
{

    if (is_post_type_archive('lesson')) {

        if (isset($_GET['wpc-course-filter'])) {

            $value = sanitize_title_with_dashes($_GET['wpc-course-filter']);

            if ($value === 'none' || $value === 'all') {
                return;
            }

            $args = array(
                'post_to' => $value,
                'connection_type' => array('lesson-to-course'),
                'order_by' => 'menu_order',
                'order' => 'asc',
            );

            $lessons = wpc_get_connected($args);
            $lesson_ids = array();

            if (!empty($lessons)) {
                foreach ($lessons as $lesson) {
                    $lesson_ids[] = $lesson->post_from;
                }
                $query->set('post__in', $lesson_ids);
            }
        }
    }
};

// Courses and teachers per page
function wpc_num_posts($query)
{

    $wpc_teachers_per_page = (int) get_option('wpc_teachers_per_page');

    if (is_post_type_archive('teacher') && !is_admin() && !empty($wpc_teachers_per_page)) {
        $query->set('posts_per_page', $wpc_teachers_per_page);
    }

    if (is_post_type_archive('course') || is_tax('course-category')) {
        $wpc_courses_per_page = (int) get_option('wpc_courses_per_page');
        if (!is_admin() && !empty($wpc_courses_per_page)) {
            $query->set('posts_per_page', $wpc_courses_per_page);
        }
    }

    return $query;
}
add_filter('pre_get_posts', 'wpc_num_posts', 100);

// Add localization
add_action('plugins_loaded', 'wpc_load_textdomain');
function wpc_load_textdomain()
{
    load_plugin_textdomain('wp-courses', false, dirname(plugin_basename(__FILE__)) . '/lang/');
}

function wpc_remove_lesson_rest_api_data($data, $post, $context)
{

    $can_edit = current_user_can('edit_posts');

    if ($can_edit != true) {

        $wpc_enable_rest_lesson = get_option('wpc_enable_rest_lesson');

        if ($wpc_enable_rest_lesson != 'true') {
            unset($data->data['content']);
            unset($data->data['excerpt']);
        }
    }

    return $data;
}

add_filter('rest_prepare_lesson', 'wpc_remove_lesson_rest_api_data', 12, 3);

// Order courses by menu order in course archive and coures category archive unless different order selected by user
add_action('pre_get_posts', 'wpc_change_courses_sort_order');

function wpc_change_courses_sort_order($query)
{

    if (is_post_type_archive('course') && $query->is_main_query() || is_tax() == 'course-category' && $query->is_main_query()) {

        if (isset($_GET['order'])) {
            $value = sanitize_title_with_dashes($_GET['order']);

            if ($value == 'default') {
                $query->set('order', 'ASC');
                $query->set('orderby', 'menu_order');
            } elseif ($value == 'newest') {
                $query->set('orderby', 'date');
                $query->set('order', 'desc');
            } elseif ($value == 'oldest') {
                $query->set('orderby', 'date');
                $query->set('order', 'asc');
            } elseif ($value == 'alphabetical') {
                $query->set('orderby', 'title');
                $query->set('order', 'asc');
            }
        } else {
            $query->set('orderby', 'title');
            $query->set('order', 'asc');
        }

        if (isset($_GET['search'])) {
            $search = sanitize_text_field($_GET['search']);
            $query->set('s', $search);
        }
    }
}

// Order lessons by menu order in lesson archive unless different order selected by user
add_action('pre_get_posts', 'wpc_change_lesson_archive_sort_order');

function wpc_change_lesson_archive_sort_order($query)
{

    if (is_post_type_archive('lesson') && $query->is_main_query()) {

        if (isset($_GET['order'])) {
            $value = sanitize_title_with_dashes($_GET['order']);

            if ($value == 'default') {
                $query->set('order', 'ASC');
                $query->set('orderby', 'menu_order');
            } elseif ($value == 'newest') {
                $query->set('orderby', 'date');
                $query->set('order', 'desc');
            } elseif ($value == 'oldest') {
                $query->set('orderby', 'date');
                $query->set('order', 'asc');
            } elseif ($value == 'alphabetical') {
                $query->set('orderby', 'title');
                $query->set('order', 'asc');
            }
        } else {
            $query->set('orderby', 'title');
            $query->set('order', 'asc');
        }

        if (isset($_GET['search'])) {
            $search = sanitize_text_field($_GET['search']);
            $query->set('s', $search);
        }
    }
}

// Order quizzes by menu order in lesson archive unless different order selected by user
add_action('pre_get_posts', 'wpc_change_quiz_archive_sort_order');

function wpc_change_quiz_archive_sort_order($query)
{

    if (is_post_type_archive('wpc-quiz') && $query->is_main_query()) {

        if (isset($_GET['order'])) {
            $value = sanitize_title_with_dashes($_GET['order']);

            if ($value == 'default') {
                $query->set('order', 'ASC');
                $query->set('orderby', 'menu_order');
            } elseif ($value == 'newest') {
                $query->set('orderby', 'date');
                $query->set('order', 'desc');
            } elseif ($value == 'oldest') {
                $query->set('orderby', 'date');
                $query->set('order', 'asc');
            } elseif ($value == 'alphabetical') {
                $query->set('orderby', 'title');
                $query->set('order', 'asc');
            }
        } else {
            $query->set('orderby', 'title');
            $query->set('order', 'asc');
        }

        if (isset($_GET['search'])) {
            $search = sanitize_text_field($_GET['search']);
            $query->set('s', $search);
        }
    }
}

// Order teachers by menu order in lesson archive unless different order selected by user
add_action('pre_get_posts', 'wpc_change_teacher_archive_sort_order');

function wpc_change_teacher_archive_sort_order($query)
{

    if (is_post_type_archive('teacher') && $query->is_main_query()) {

        if (isset($_GET['order'])) {
            $value = sanitize_title_with_dashes($_GET['order']);

            if ($value == 'default') {
                $query->set('order', 'ASC');
                $query->set('orderby', 'menu_order');
            } elseif ($value == 'newest') {
                $query->set('orderby', 'date');
                $query->set('order', 'desc');
            } elseif ($value == 'oldest') {
                $query->set('orderby', 'date');
                $query->set('order', 'asc');
            } elseif ($value == 'alphabetical') {
                $query->set('orderby', 'title');
                $query->set('order', 'asc');
            }
        } else {
            $query->set('orderby', 'title');
            $query->set('order', 'asc');
        }

        if (isset($_GET['search'])) {
            $search = sanitize_text_field($_GET['search']);
            $query->set('s', $search);
        }
    }
}

// Add video to content in single lessons.  This way PMPro can filter the lesson content including the video.
add_filter('the_content', 'wpc_filter_content', 1);

function wpc_filter_content($content)
{

    if (get_post_type() == 'lesson' && is_single()) {
        $lesson_id = get_the_ID();
        $video = wpc_get_video($lesson_id);
        return '<div id="video-wrapper" class="wpc-vid-wrapper" style="margin: 0; width: 100%;">' . wpc_sanitize_video($video) . '</div>' . $content;
    } elseif (get_post_type() === 'wpc-quiz') {
        return $content . '<div id="wpc-fe-quiz-container"></div>';
    } else {
        return $content;
    }
}

add_filter('the_content', 'wpc_content_restriction_filter');

function wpc_content_restriction_filter($content)
{

    $post_type = get_post_type();

    if ($post_type !== 'lesson' && $post_type !== 'wpc-quiz') {
        return $content;
    }

    $restriction = get_post_meta(get_the_ID(), 'wpc-lesson-restriction', true);
    $custom_logged_out_message = get_option('wpc_logged_out_message');
    $login_url = wp_login_url(get_permalink());
    $register_url = wp_registration_url();

    $restricted_message = '<p class="wpc-content-restricted wpc-free-account-required wpc-alert-message">';
    $restricted_message .= !empty($custom_logged_out_message) ? $custom_logged_out_message : '<a href="' . $login_url . '">' . __('Log in', 'wp-courses') . ' </a> or <a href="' . $register_url . '">' . __('Register', 'wp-courses') . '</a> to view this lesson';
    $restricted_message .= '</p>';

    if ($restriction == 'free-account' && !is_user_logged_in()) {
        return wp_kses($restricted_message, 'post');
    } else {
        return $content;
    }
}

// Show top admin nav menu across differen cp types
function wpc_admin_nav_menu_display_logic()
{
    $show = false;

    if (isset($_GET['taxonomy'])) {
        if ($_GET['taxonomy'] == 'course-difficulty' || $_GET['taxonomy'] == 'course-category') {
            $show = true;
        }
    }

    $post_type = get_post_type();

    if ($post_type == 'course' || $post_type == 'lesson' || $post_type == 'wpc-quiz' || $post_type == 'teacher' || $post_type == 'wpc-email' || $post_type == 'wpc-badge' || $post_type == 'wpc-certificate') {
        $show = true;
    }

    $post_type_query_string = isset($_GET['post_type']) ? $_GET['post_type'] : false;

    if ($post_type_query_string == 'course' || $post_type_query_string == 'lesson' || $post_type_query_string == 'wpc-quiz' || $post_type_query_string == 'teacher' || $post_type_query_string == 'wpc-email' || $post_type_query_string == 'wpc-badge' || $post_type_query_string == 'wpc-certificate') {
        $show = true;
    }

    if (!is_admin()) {
        $show = false;
    }

    return $show;
}

add_action('in_admin_header', 'wpc_admin_nav_menu');

function wpc_admin_nav_menu()
{
    $show = wpc_admin_nav_menu_display_logic();
    if ($show == true) {
        include 'admin/admin-nav-menu.php';
    }
}

// Shows the admin nav menu after screen options
add_action('admin_footer', 'wpc_admin_screen_options_styling');

function wpc_admin_screen_options_styling()
{
    $show = wpc_admin_nav_menu_display_logic();
    if ($show == true) {
        echo '<script>
            jQuery(document).ready(function($){
                var nav = $(".wpc-admin-nav-menu");
                var navClone = nav.clone();
                nav.remove();
                $("#screen-meta-links").after(nav);
            });

        </script>';
    }
}

// Display license expiration notice
add_action('admin_notices', 'wpc_show_admin_notices');

function wpc_show_admin_notices()
{
    $wpc_admin_notice_dismissed = get_option('wpc_admin_notice_dismissed');
    if ($wpc_admin_notice_dismissed == 'true') {
        return;
    }

    if (is_plugin_active('wp-courses-premium/wp-courses-premium.php')) {
        $plugin_data = get_plugin_data(plugin_dir_path(__DIR__) . 'wp-courses-premium/wp-courses-premium.php', false, false);

        if (!empty($plugin_data['Version'])) {
            if (version_compare('3.2.4', $plugin_data['Version'], '>')) {
                $output = 'You are using an outdated version of WP Courses LMS Premium. Please update to the latest version to ensure compatibility and security.';

                $output2 = 'a) If your license is still valid, insert your license key and email address under WP Courses > Settings > Plugin Updates (update check might take up to 24 hours)';

                $output3 = 'b) If your license has expired, you can get a new license ';

                $output4 = 'https://wpcoursesplugin.com/cart/?add-to-cart=829';

                $output5 = 'here';
            } else {
                return; // Premium plugin does not need to be updated
            }
        } else {
            return; // Premium plugin did not return version
        }
    } else {
        return; // Premium plugin not installed
    }

    printf(
        '<div class="notice notice-warning is-dismissible wpc-admin-notice">
                <p>%1$s</p>
                <p>%2$s</p>
                <p>%3$s<a target= "_blank" href="%4$s">%5$s</a></p>
			</div>',
        $output,
        $output2,
        $output3,
        $output4,
        $output5
    );
}

add_action('wp_ajax_wpc_admin_notice_dismiss', 'wpc_dismiss_admin_notice');

function wpc_dismiss_admin_notice()
{
    check_ajax_referer('wpc_nonce', 'security');

    update_option('wpc_admin_notice_dismissed', 'true');

    wp_die();
}

// Helper
function wpc_log($object = null)
{
    try {
        $pluginlog = plugin_dir_path(__FILE__) . 'debug.log';

        ob_start();
        var_dump($object);
        $contents = ob_get_contents();
        ob_end_clean();

        $date   = new DateTime();
        $date_string = $date->format('Y-m-d H:i:s');

        error_log($date_string . ' ' . $contents, 3, $pluginlog);
    } catch (\Error $e) {
    }
}

function wpc_array_insert_after($key, array &$array, $new_key, $new_value)
{
    if (array_key_exists($key, $array)) {
        $new = array();

        foreach ($array as $k => $value) {
            $new[$k] = $value;

            if ($k === $key) {
                $new[$new_key] = $new_value;
            }
        }

        return $new;
    }

    return FALSE;
}