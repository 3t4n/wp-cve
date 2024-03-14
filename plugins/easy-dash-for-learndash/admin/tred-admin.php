<?php
//Define options (keys) and fields vitals (values)
$options_array = [
    'tred_last_x_days' => [
        'type' => 'select',
        'kind' => '',
        'options' => ['7', '10', '14', '30', '60', '90', '120', '180', '365', 'all time'],
        'default' => '30',
        'description' => __('Some widgets in the dashboard limit queries to the last x days. Choose here how many days you want to limit.', 'learndash-easy-dash'),
        'obs' => __('All widgets with queries limited by days will be affected.', 'learndash-easy-dash'),
        'final' => __('Default: 30 days. Options above \'90\' days may not work due to memory size and timeout issues', 'learndash-easy-dash'),
        'order' => 1,
    ],
    'tred_cache_x_hours' => [
        'type' => 'select',
        'kind' => '',
        'options' => ['1', '3', '6', '12', '24', '48'],
        'default' => '6',
        'description' => __('Some queries are kept in cache for x hours. Choose here how many hours until cache should be refreshed.', 'learndash-easy-dash'),
        'obs' => __('All queries will be affected next time they are cached.', 'learndash-easy-dash'),
        'final' => __('Default: 6 hours.', 'learndash-easy-dash'),
        'order' => 2,
    ],
    'tred_select_x_items' => [
        'type' => 'select',
        'kind' => '',
        'options' => ['3', '5', '10', '15', '30'],
        'default' => '10',
        'description' => __('Some queries select only x items in the database. Choose here how many items you want to see selected', 'learndash-easy-dash'),
        'obs' => __('All queries that limit the number of items to be selected will be affected. Please note that your chart may look bad if too many items are queried.', 'learndash-easy-dash'),
        'final' => __('Default: 10 items.', 'learndash-easy-dash'),
        'order' => 3,
    ],
    'tred_exclude_admins_from_course_users' => [
        'type' => 'checkbox',
        'kind' => '',
        'default' => '',
        'description' => __('Exclude admins from course users stats', 'learndash-easy-dash'),
        'obs' => __('Changing this will only take effect after the cache has expired.', 'learndash-easy-dash'),
        'final' => '',
        'order' => 4,
    ]
];


//defining constant options
define("TRED_OPTIONS_ARRAY", $options_array);
foreach (TRED_OPTIONS_ARRAY as $op => $vals) {
    $option = (get_option($op)) ? get_option($op) : $vals['default'];
    define(strtoupper($op), $option);
}

function tred_admin_menu()
{
    global $tred_settings_page;
    $tred_settings_page = add_submenu_page(
        'learndash-lms', //The slug name for the parent menu
        __('Easy Dash', 'learndash-easy-dash'), //Page title
        __('Easy Dash', 'learndash-easy-dash'), //Menu title
        'manage_options', //capability
        'learndash-easy-dash', //menu slug 
        'tred_admin_page' //function to output the content
    );
}
add_action('admin_menu', 'tred_admin_menu');

function tred_register_plugin_settings()
{
    foreach (TRED_OPTIONS_ARRAY as $op => $vals) {
        register_setting('tred-settings-group', $op);
    }
}
//call register settings function
add_action('admin_init', 'tred_register_plugin_settings');


function tred_admin_page()
{ ?>

    <div class="tred-head-panel">
        <div id="tred-easydash-tabs" class="tred-tab-buttons">
            <a href="#" class="button active" data-target-content="tred-easydash-tab-global">
                <?php esc_html_e('Dash', 'learndash-easy-dash'); ?>
            </a>
            <a href="#" class="button" data-target-content="tred-easydash-tab-filter">
                <?php esc_html_e('Filter', 'learndash-easy-dash'); ?>
            </a>
            <a href="#" class="button" data-target-content="tred-easydash-tab-settings">
                <?php esc_html_e('Settings', 'learndash-easy-dash'); ?>
            </a>
            <a href="#" class="button" data-target-content="tred-easydash-tab-shortcode">
                <?php esc_html_e('Shortcode', 'learndash-easy-dash'); ?>
            </a>
        </div>
    </div>

    <div class="bg-gray-100 font-sans leading-normal tracking-normal" data-new-gr-c-s-check-loaded="14.1019.0"
        data-gr-ext-installed="" cz-shortcut-listen="true">

        <div class="flex flex-col md:flex-row">

            <!-- tred-main-content tred-easydash-tab-global -->
            <?php include_once('tred-dash.php'); ?>
            <!-- end tred-main-content tred-easydash-tab-global -->

            <!-- tred-main-content tred-easydash-tab-filter -->
            <?php include_once('tred-filter.php'); ?>
            <!-- end tred-main-content tred-easydash-tab-filter -->

            <!-- tred-main-content tred-easydash-tab-settings -->
            <?php include_once('tred-settings.php'); ?>
            <!-- end tred-main-content tred-easydash-tab-settings -->

            <!-- tred-main-content tred-easydash-tab-shortcode -->
            <?php include_once('tred-shortcode.php'); ?>
            <!-- end tred-main-content tred-easydash-tab-shortcode -->

        </div>
        <!-- end flex-row -->
    </div>
    <!-- end outter div -->

    <?php
}