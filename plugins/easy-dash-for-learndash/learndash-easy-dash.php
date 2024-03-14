<?php
/**
 * Plugin Name:  Easy Dash for LearnDash
 * Plugin URI: https://wptrat.com/easy-dash-for-learndash /
 * Description:  Easy Dash for LearnDash: an improved (and easy) dashboard for your LearnDash site
 * Author: Luis Rock
 * Author URI: https://wptrat.com/
 * Version: 2.4.3
 * Text Domain: learndash-easy-dash
 * Domain Path: /languages
 * License: GNU General Public License v3.0
 * License URI: http://www.gnu.org/licenses/gpl-3.0.html
 *
 * @package   Easy Dash for LearnDash
 */

//Template: https://www.tailwindtoolbox.com/templates/admin-template

if (!defined('ABSPATH'))
    exit;

define("TRED_VERSION", "2.4.3");

// Check if LearnDash is active. If not, deactivate...
include_once(ABSPATH . 'wp-admin/includes/plugin.php');
if (!is_plugin_active('sfwd-lms/sfwd_lms.php')) {
    add_action('admin_init', 'tred_deactivate');
    add_action('admin_notices', 'tred_admin_notice');
    function tred_deactivate()
    {
        deactivate_plugins(plugin_basename(__FILE__));
    }
    // Notice
    function tred_admin_notice()
    { ?>
        <div class="notice notice-error is-dismissible">
            <p>
                <strong>
                    <?php esc_html_e('LearnDash LMS is not active: EASY DASH FOR LEARNDASH needs it, that\'s why was deactivated', 'learndash-easy-dash'); ?>
                </strong>
            </p>
            <button type="button" class="notice-dismiss">
                <span class="screen-reader-text">
                    Dismiss this notice.
                </span>
            </button>
        </div>
        <?php
        if (isset($_GET['activate'])) {
            unset($_GET['activate']);
        }
    } //end function tred_admin_notice
} //end if( !is_plugin_active('sfwd-lms/sfwd_lms.php' ) )

add_action('init', 'tred_load_textdomain');
function tred_load_textdomain()
{
    load_plugin_textdomain('learndash-easy-dash', false, dirname(plugin_basename(__FILE__)) . '/languages');
}

$tredActions = [
    'tred_ld_posts',
    'tred_ld_students_courses',
    'tred_ld_essays_assignments',
    'tred_ld_groups',
    'tred_ld_comments',
    'tred_ld_activity',
    'tred_ld_courses_completions_stats',
    'tred_ld_items_stats_over_time',
];

$tredFilteredActions = [
    'tred_ld_posts_dropdown',
    'tred_users_dropdown',
    'tred_ld_item_filtered_get_numbers',
    'tred_user_filtered_get_numbers',
];

$tredOptionsActions = [
    'tred_ld_save_panel',
    'tred_ld_get_widget_options',
];

$tredColors = [
    'red' => 'rgb(255, 99, 132)',
    'red_t' => 'rgb(255, 99, 132, 0.2)',
    'orange' => 'rgb(255, 159, 64)',
    'orange_t' => 'rgb(255, 159, 64, 0.2)',
    'yellow' => 'rgb(255, 205, 86)',
    'yellow_t' => 'rgb(255, 205, 86, 0.2)',
    'green' => 'rgb(75, 192, 192)',
    'green_t' => 'rgb(75, 192, 192, 0.2)',
    'blue' => 'rgb(54, 162, 235)',
    'blue_t' => 'rgb(54, 162, 235, 0.2)',
    'purple' => 'rgb(153, 102, 255)',
    'purple_t' => 'rgb(153, 102, 255, 0.2)',
    'grey' => 'rgb(201, 203, 207)',
    'grey_t' => 'rgb(201, 203, 207, 0.2)',
    'pink' => 'rgb(251, 207, 232)',
    'pink_t' => 'rgb(251, 207, 232, 0.2)',
];

if (!defined('TRED_ACTIONS')) {
    define('TRED_ACTIONS', $tredActions);
}
if (!defined('TRED_FILTERED_ACTIONS')) {
    define('TRED_FILTERED_ACTIONS', $tredFilteredActions);
}
if (!defined('TRED_OPTIONS_ACTIONS')) {
    define('TRED_OPTIONS_ACTIONS', $tredOptionsActions);
}
if (!defined('TRED_COLORS')) {
    define('TRED_COLORS', $tredColors);
}
if (!defined('TRED_LOADING_IMG_URL')) {
    define('TRED_LOADING_IMG_URL', admin_url('images/wpspin_light.gif'));
}
if (!defined('TRED_PRO_ACTIVATED')) {
    define('TRED_PRO_ACTIVATED', is_plugin_active('easy-dash-for-learndash-pro/learndash-easy-dash-pro.php'));
}
if (!defined('TRED_GLOBAL')) {
    define('TRED_GLOBAL', file_get_contents(WP_PLUGIN_DIR . '/easy-dash-for-learndash/json/global/global.json'));
}
if (!defined('TRED_FILTERED_COURSE')) {
    define('TRED_FILTERED_COURSE', file_get_contents(WP_PLUGIN_DIR . '/easy-dash-for-learndash/json/filtered/course.json'));
}
if (!defined('TRED_FILTERED_USER')) {
    define('TRED_FILTERED_USER', file_get_contents(WP_PLUGIN_DIR . '/easy-dash-for-learndash/json/filtered/user.json'));
}

// 2.4.0
if (!defined('TRED_FILTERED_GROUP')) {
    define('TRED_FILTERED_GROUP', file_get_contents(WP_PLUGIN_DIR . '/easy-dash-for-learndash/json/filtered/group.json'));
}

if (!defined('TRED_WIDGETS_TO_SHOW')) {
    define('TRED_WIDGETS_TO_SHOW', get_option('tred_panel_widgets_to_show'));
}

// Requiring plugin files
require_once('admin/tred-admin.php'); //settings options are defined here
require_once('includes/functions.php');
require_once('includes/callbacks-actions.php');

function tred_register_all_scripts_and_styles()
{

    wp_register_script('tred_chartjs', plugins_url('assets/js/Chart.js', __FILE__), [], '3.4.1', false);
    wp_register_script('tred_admin_js', plugins_url('assets/js/tred-admin.js', __FILE__), ['tred_chartjs', 'jquery'], '1.0.0', true);
    wp_register_script('datatables_js', plugins_url('assets/DataTables/datatables.min.js', __FILE__), ['jquery'], '', true);
    wp_register_style('datatables_css', plugins_url('assets/DataTables/datatables.min.css', __FILE__));
    wp_register_style('fontawsome', 'https://use.fontawesome.com/releases/v5.3.1/css/all.css');
    wp_register_style('tred_tailwind_css', plugins_url('assets/css/tred-output.css', __FILE__));
    wp_register_style('tred_admin_css', plugins_url('assets/css/tred-admin.css', __FILE__));
    wp_register_script('notify_js', plugins_url('assets/js/notify.min.js', __FILE__), ['jquery'], '1.0.0', false);
    wp_localize_script(
        'tred_admin_js',
        'tred_js_object',
        array(
            'ajaxurl' => admin_url('admin-ajax.php'),
            '_wpnonce' => wp_create_nonce('tred_nonce'),
            'sliceNumberItems' => TRED_SELECT_X_ITEMS, //defined in tred-admin.php
            'sliceNumberDays' => TRED_LAST_X_DAYS, //defined in tred-admin.php
            'tredActions' => TRED_ACTIONS,
            'tredFilteredActions' => TRED_FILTERED_ACTIONS,
            'tredColors' => TRED_COLORS,
            'tredLoadingImgUrl' => TRED_LOADING_IMG_URL,
            'tredCourseJson' => TRED_FILTERED_COURSE,
            'tredGlobalJson' => TRED_GLOBAL,
            'tredUserJson' => TRED_FILTERED_USER,
            // 2.3.1
            'tredGroupJson' => TRED_FILTERED_GROUP,
            'tredWidgetsToShow' => TRED_WIDGETS_TO_SHOW,
            'tredAccessModes' => tred_get_access_modes_existent(),
            'tredWidgetsTranslation' => tred_widgets_translation(),
            'tredElementsTranslation' => tred_elements_translation(),
            'tredTableTranslation' => tred_table_translation(),
            'tredItemsLabels' => tred_items_labels_translation(),
            'tredSiteName' => get_bloginfo('name'),
            'tredProActivated' => TRED_PRO_ACTIVATED,
            'tredCsvLabels' => tred_csv_labels_translation()
        )
    );
}
add_action('wp_loaded', 'tred_register_all_scripts_and_styles');

//Scripts end styles
function tred_enqueue_admin_script($hook)
{
    global $tred_settings_page;
    if ($hook != $tred_settings_page) {
        return;
    }
    wp_enqueue_style('tred_tailwind_css');
    wp_enqueue_style('tred_admin_css');
    wp_enqueue_style('fontawsome');
    wp_enqueue_script('tred_chartjs');
    wp_enqueue_script('tred_admin_js');
    wp_enqueue_script('notify_js');
    wp_enqueue_style('datatables_css');
    wp_enqueue_script('datatables_js');
    //Select2
    wp_enqueue_style('select2', 'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css');
    wp_enqueue_script('select2', 'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js', array('jquery'));
}
add_action('admin_enqueue_scripts', 'tred_enqueue_admin_script');

//general actions
foreach (TRED_ACTIONS as $action) {
    add_action("wp_ajax_$action", $action);
}

//actions for filter tab
foreach (TRED_FILTERED_ACTIONS as $action) {
    add_action("wp_ajax_$action", $action);
}

//actions for options tab
foreach (TRED_OPTIONS_ACTIONS as $action) {
    add_action("wp_ajax_$action", $action);
}