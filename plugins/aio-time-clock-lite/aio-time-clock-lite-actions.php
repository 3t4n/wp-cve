<?php
class AIO_Time_Clock_Lite_Actions
{
    public $dateFormat = 'Y-m-d H:i:s';
    public $safeDateFormat = 'm/d/Y g:i A';
    public $defaultDateFormat = 'Y-m-d g:i A';
    public $mysqlDateFormat = 'Y-m-d H:i:s';
    public $prettyDateTime = 'Y-m-d h:i A';
    public $widgets;

    public function setup()
    {
        register_activation_hook(__FILE__, array($this, 'aio_time_clock_lite_plugin_update'));
        add_action("admin_menu", array($this, 'addMenuItem'));
        add_action('init', array($this, 'pluginInit'));
        add_action('wp_enqueue_scripts', array($this, 'frontEndScripts'));
        add_action('admin_enqueue_scripts', array($this, 'adminScripts'));
        add_action('admin_init', array($this, 'aio_timeclock_lite_plugin_redirect'));
        //Shortcode
        add_shortcode('show_aio_time_clock_lite', array($this, 'show_aio_time_clock_lite'));
        add_shortcode('show_aio_employee_profile_lite', array($this, 'show_aio_employee_profile_lite'));
        //Ajax
        add_action('wp_ajax_aio_time_clock_lite_js', array($this, 'aio_time_clock_lite_js'));
        add_action('wp_ajax_nopriv_aio_time_clock_lite_js', array($this, 'aio_time_clock_lite_js'));
        add_action('wp_ajax_aio_time_clock_lite_admin_js', array($this, 'aio_time_clock_lite_admin_js'));
        add_action('wp_ajax_nopriv_aio_time_clock_lite_admin_js', array($this, 'aio_time_clock_lite_admin_js'));
        //Shift Meta
        add_action('admin_init', array($this, 'aio_tc_custom_post_shift_lite'));
        add_action("admin_init", array($this, "aio_timeclock_admin_init_lite"));
        add_action('add_meta_boxes', array($this, 'aio_shift_info_box_meta_lite'));
        add_action('admin_menu', array($this, 'removeMetaBoxes'));
        add_action('admin_init', array($this, 'register_aio_timeclock_lite_settings'));
        add_filter('user_contactmethods', array($this, 'modifyWageLite'));
        add_action('admin_menu', array($this, 'remove_my_post_metaboxes_aio_lite'));
        add_action('admin_notices', array($this, 'adminNoticesLite'));
        add_action('plugins_loaded', array($this, 'pluginInitLite'));

        if (is_admin()) {
            add_action('save_post', array($this, 'aio_save_shift_meta_lite'));
        }

        if (get_option('aio_timeclock_redirect_employees') == "enabled") {
            add_action('login_redirect', array($this, 'memberLoginRedirect'), 10, 3);
        }

        add_filter('post_row_actions', array($this, 'aio_remove_row_actions'), 10, 1);

        //Taxonomy
        add_action('init', array($this, 'aio_lite_register_user_taxonomy'));
        add_action('personal_options_update', array($this, 'aio_tc_lite_save_user_department_terms'));
        add_action('edit_user_profile_update', array($this, 'aio_tc_lite_save_user_department_terms'));
        add_action('admin_menu', array($this, 'aio_lite_add_department_admin_page'));
        add_action('show_user_profile', array($this, 'aio_lite_edit_user_department_section'));
        add_action('edit_user_profile', array($this, 'aio_lite_edit_user_department_section'));
        add_action('personal_options_update', array($this, 'aio_lite_save_user_department_terms'));
        add_action('edit_user_profile_update', array($this, 'aio_lite_save_user_department_terms'));
        add_action('manage_department_custom_column', array($this, 'aio_lite_manage_department_column', 10, 3));

        add_filter('manage_edit-departments_columns', array($this, 'aio_lite_manage_department_user_column'));
        add_filter('sanitize_user', array($this, 'aio_lite_disable_username'));

        //Get the widgets
        if (!class_exists('AIO_Time_Clock_Lite_Widgets')) {
            require_once plugin_dir_path(__FILE__) . 'aio-time-clock-lite-widgets.php';
            $this->widgets = new AIO_Time_Clock_Lite_Widgets();
            add_action('widgets_init', array($this, 'loadWidgets'));
        }

        $this->handleRoles();
    }

    public function getVersion()
    {
        return "1.3.323";
    }

    public function handleRoles()
    {
        remove_role('manager');
        $result = add_role(
            'manager',
            esc_attr_x('Manager', 'aio-time-clock-lite'),
            array(
                'read' => true,
                'create_posts' => true,
                'edit_posts' => true,
                'edit_others_posts' => true,
                'publish_posts' => true,
                'manage_categories' => true,
            )
        );

        remove_role('volunteer');
        $result = add_role(
            'volunteer',
            esc_attr_x('Volunteer', 'aio-time-clock-lite'),
            array(
                'read' => true,
            )
        );

        remove_role('employee');
        $result = add_role(
            'employee',
            esc_attr_x('Employee', 'aio-time-clock-lite'),
            array(
                'read' => true,
            )
        );

        remove_role('time_clock_admin');
        $result = add_role(
            'time_clock_admin',
            esc_attr_x('Time Clock Admin', 'aio-time-clock-lite'),
            array(
                'read' => true, // Allows a user to read
                'create_shifts' => true, // Allows user to create new posts
                'edit_posts' => true, // Allows user to edit their own posts
                'edit_shifts' => true, // Allows user to edit their own posts
                'edit_others_posts' => true, // Allows user to edit others posts too
                'edit_others_shifts' => true, // Allows user to edit others posts too
                'publish_posts' => true, // Allows the user to publish posts
                'publish_shifts' => true, // Allows the user to publish posts
                'manage_categories' => true, // Allows user to manage post categories
                'edit_private_posts' => true, // Allows user to manage post categories
                'edit_private_shifts' => true, // Allows user to manage post categories
                'read_private_posts' => true, // Allows user to manage post categories
                'read_private_shifts' => true, // Allows user to manage post categories
                'edit_published_posts' => true, // Allows user to manage post categories
                'edit_published_shifts' => true, // Allows user to manage post categories
            )
        );
    }

    public function pluginInitLite()
    {
        load_plugin_textdomain('aio-time-clock-lite', false, dirname(plugin_basename(__FILE__)) . '/languages/');
    }

    public function addMenuItem()
    {
        $page_hook_suffix = add_menu_page('Time Clock Lite', 'Time Clock Lite', 'edit_posts', 'aio-tc-lite', array($this, 'settingsPageLite'), 'dashicons-clock');
        add_submenu_page('aio-tc-lite', esc_attr_x('Settings', 'aio-time-clock-lite'), esc_attr_x('Settings', 'aio-time-clock-lite'), 'edit_posts', 'aio-tc-lite', array($this, 'settingsPageLite'));
        add_submenu_page('aio-tc-lite', esc_attr_x('Real Time Monitoring', 'aio-time-clock-lite'), esc_attr_x('Real Time Monitoring', 'aio-time-clock-lite'), 'edit_posts', 'aio-monitoring-sub', array($this, 'montioringPage'));
        add_submenu_page('aio-tc-lite', esc_attr_x('Employees', 'aio-time-clock-lite'), esc_attr_x('Employees', 'aio-time-clock-lite'), 'edit_posts', 'aio-employees-sub', array($this, 'employeePage'));
        add_submenu_page('aio-tc-lite', esc_attr_x('Departments', 'aio-time-clock-lite'), esc_attr_x('Departments', 'aio-time-clock-lite'), 'edit_posts', 'aio-department-sub', array($this, 'departmentPage'));
        add_submenu_page('aio-tc-lite', esc_attr_x('Shifts', 'aio-time-clock-lite'), esc_attr_x('Shifts', 'aio-time-clock-lite'), 'edit_posts', 'aio-shifts-sub', array($this, 'shiftsPage'));
        add_submenu_page('aio-tc-lite', esc_attr_x('Reports', 'aio-time-clock-lite'), esc_attr_x('Reports', 'aio-time-clock-lite'), 'edit_posts', 'aio-reports-sub', array($this, 'reportsPageLite'));
    }

    public function frontEndScripts()
    {
        //Styles
        wp_register_style('datetimepicker-style', plugins_url('css/jquery.datetimepicker.css', __FILE__));
        wp_register_style('aio-tc-site-style', plugins_url('css/aio-site.css', __FILE__), $this->getVersion());
        wp_enqueue_style('datetimepicker-style');
        wp_enqueue_style('aio-tc-site-style');

        //Scripts
        wp_enqueue_script('aio_time_clock_lite_js', plugins_url('/js/time-clock-lite.js', __FILE__), array('jquery'), $this->getVersion());
        wp_enqueue_script('jquery');
        wp_enqueue_script('nert-aio-timepicker', plugins_url('js/jquery.datetimepicker.js', __FILE__), array('jquery'));
        wp_enqueue_script('aio_time_clock_lite_js');
        wp_localize_script('aio_time_clock_lite_js',
            'timeClockAjax',
            $this->getTranslationStrings()
        );
    }

    public function adminScripts()
    {
        //Styles
        wp_register_style('datetimepicker-style', plugins_url('css/jquery.datetimepicker.css', __FILE__));
        wp_enqueue_style('datetimepicker-style');
        wp_register_style('aio-tc-admin-style', plugins_url('css/aio-admin.css', __FILE__), $this->getVersion());
        wp_register_style('aio-tc-lite-steps-style', plugins_url('css/jquery.steps.css', __FILE__));
        wp_enqueue_style('aio-tc-admin-style');
        wp_enqueue_style('aio-tc-lite-steps-style');

        //Scripts
        wp_enqueue_script('jquery');
        wp_register_script("aio_time_clock_lite_steps", plugins_url('/js/jquery.steps.min.js', __FILE__), array('jquery'));
        wp_enqueue_script('aio_time_clock_lite_admin_js', plugins_url('/js/time-clock-lite-admin.js', __FILE__), array('jquery'), $this->getVersion());
        wp_enqueue_script('nert-aio-timepicker', plugins_url('js/jquery.datetimepicker.js', __FILE__), array('jquery'));
        wp_enqueue_script('aio_time_clock_lite_admin_js');
        wp_enqueue_script('aio_time_clock_lite_steps');

        wp_localize_script('aio_time_clock_lite_admin_js',
            'timeClockAdminAjax',
            $this->getTranslationStrings()
        );
    }

    public function pluginInit()
    {
        load_plugin_textdomain('aio-time-clock-update-manager', false, dirname(plugin_basename(__FILE__)) . '/languages/');
    }

    public function aio_time_clock_lite_plugin_update()
    {
        add_option('aio_time_clock_lite_update_redirect', true);
    }

    public function aio_timeclock_lite_plugin_redirect()
    {
        if (get_option('aio_time_clock_lite_update_redirect', false)) {
            delete_option('aio_time_clock_lite_update_redirect');
            wp_redirect('?page=aio-tc-lite&tab=news');
        }
    }

    public function remove_my_post_metaboxes_aio_lite()
    {
        remove_meta_box('authordiv', 'shift', 'normal');
        remove_meta_box('commentstatusdiv', 'shift', 'normal');
        remove_meta_box('commentsdiv', 'shift', 'normal');
    }

    public function aio_remove_row_actions($actions)
    {
        if (get_post_type() === 'shift') {
            unset($actions['view']);
        }

        return $actions;
    }

    public function show_aio_time_clock_lite($atts)
    {
        $tc_page = $this->aio_check_tc_shortcode_lite();
        $nonce = wp_create_nonce("clock_in_nonce");
        $link = admin_url('admin-ajax.php?action=clock_in_nonce&post_id=' . get_the_ID() . '&nonce=' . esc_attr($nonce));
        require_once "templates/time-clock-style1.php";
    }

    public function show_aio_employee_profile_lite($atts)
    {
        $ep_page = $this->check_eprofile_shortcode_lite();
        $nonce = wp_create_nonce("clock_in_nonce");
        $link = admin_url('admin-ajax.php?action=clock_in_nonce&post_id=' . get_the_ID() . '&nonce=' . esc_attr($nonce));
        require_once "aio-employee-profile.php";
    }

    public function aio_time_clock_lite_js()
    {
        global $current_user;
        $clock_action = (isset($_POST["clock_action"])) ? sanitize_text_field($_POST["clock_action"]) : null;
        $employee_clock_in_time = null;
        $employee_clock_out_time = null;
        $is_clocked_in = false;
        $open_shift_id = (isset($_POST["open_shift_id"])) ? intval($_POST["open_shift_id"]) : null;
        $new_shift_created = false;        
        $employee =(isset($_POST["employee"])) ? intval($_POST["employee"]) : intval($current_user->ID);
        $message = null;
        $time_total = strtotime(0);

        if ($clock_action == "check_shifts") {
            $found_shift_id = null;
            $args = array(
                'post_type' => 'shift',
                'orderby' => 'ID'
            );

            $query = new WP_Query($args);

            if ($query->have_posts()) {
                while ($query->have_posts()) {
                    $query->the_post();
                    $custom = get_post_custom($query->post->ID);
                    $author_id = get_post_field('post_author', $query->post->ID);
                    $employee_clock_in_time = (isset($custom["employee_clock_in_time"][0])) ? sanitize_text_field($custom["employee_clock_in_time"][0]) : null;
                    $employee_clock_out_time = (isset($custom["employee_clock_out_time"][0])) ? sanitize_text_field($custom["employee_clock_out_time"][0]) :  null;
                    if ($employee_clock_in_time != null && $employee_clock_out_time == null && $employee == $author_id) {
                        $found_shift_id = $query->post->ID;
                        $is_clocked_in = true;
                        break;
                    }
                }
            }

            echo json_encode(
                array(
                    "response" => "success",
                    "message" => $message,
                    "employee" => $employee,
                    "clock_action" => $clock_action,
                    "is_clocked_in" => $is_clocked_in,
                    "open_shift_id" => $found_shift_id,
                    "employee_clock_in_time" => (($employee_clock_in_time != null) ? $this->cleanDate($employee_clock_in_time) : null),
                    "employee_clock_out_time" => (($employee_clock_in_time != null) ? $this->cleanDate($employee_clock_out_time) : null),
                )
            );
        } elseif ($clock_action == "clock_in") {
            $device_time = (isset($_POST["device_time"])) ? sanitize_text_field($_POST["device_time"]) : null;            
            $current_time = $this->getCurrentTime();

            $aio_new_shift = array(
                'post_type' => 'shift',
                'post_title' => 'Employee Shift',
                'post_status' => 'publish',
                'post_author' => $employee,
            );
            $new_post_id = wp_insert_post($aio_new_shift);
            $department = "";
            $terms = get_the_terms($current_user->ID, 'department');
            if (!empty($terms)) {
                foreach ($terms as $term) {
                    $department = $term->name;
                }
            }
            update_post_meta($new_post_id, 'employee_clock_in_time', sanitize_text_field($current_time["current_time"]));
            update_post_meta($new_post_id, 'employee_clock_out_time', null);
            if ($department != null) {
                add_post_meta($new_post_id, 'department', $department, true);
            }
            add_post_meta($new_post_id, 'ip_address_in', sanitize_text_field($_SERVER['REMOTE_ADDR']), true);
            $open_shift_id = $new_post_id;
            $is_clocked_in = true;

            echo json_encode(
                array(
                    "response" => "success",
                    "message" => $message,
                    "employee" => $employee,
                    "open_shift_id" => $open_shift_id,
                    "clock_action" => $clock_action,
                    "is_clocked_in" => $is_clocked_in,
                    "employee_clock_in_time" => $this->cleanDate(sanitize_text_field($current_time["current_time"])),
                    "employee_clock_out_time" => null,
                )
            );
        } elseif ($clock_action == "clock_out") {
            $device_time = isset($_POST["device_time"]) ? sanitize_text_field($_POST["device_time"]) : null;
            $current_time = $this->getCurrentTime();
            $is_clocked_in = false;
            $employee_clock_in_time = get_post_meta($open_shift_id, 'employee_clock_in_time', true);
            update_post_meta($open_shift_id, 'employee_clock_out_time', sanitize_text_field($current_time["current_time"]));
            add_post_meta($open_shift_id, 'ip_address_out', sanitize_text_field($_SERVER['REMOTE_ADDR']), true);
            $shift_sum = $this->dateDifference($employee_clock_in_time, sanitize_text_field($current_time["current_time"]));
            $time_total = $shift_sum;

            echo json_encode(
                array(
                    "response" => "success",
                    "message" => $message,
                    "employee" => $employee,
                    "clock_action" => $clock_action,
                    "employee_clock_in_time" => $this->cleanDate($employee_clock_in_time),
                    "employee_clock_out_time" => $this->cleanDate(sanitize_text_field($current_time["current_time"])),
                    "time_total" => $this->secondsToTime($time_total),
                    "is_clocked_in" => $is_clocked_in,
                )
            );
        } else {
            echo json_encode(
                array(
                    "response" => "failed",
                    "message" => esc_attr_x("action does not exist", 'aio-time-clock-lite'),
                    "employee" => $employee,
                    "clock_action" => $clock_action,
                )
            );
        }

        wp_reset_postdata();
        die();
    }

    public function secondsToTime($seconds)
    {
        if ($seconds != null) {
            $dtF = new DateTime('@0');
            $dtT = new DateTime("@$seconds");
            $days = $dtF->diff($dtT)->format('%D');
            $hours = $dtF->diff($dtT)->format('%H');
            $minutes = $dtF->diff($dtT)->format('%I');
            $hours = $hours + ($days * 24);
            return $hours . ":" . $minutes;
        } else {
            return '00:00';
        }
    }

    public function aio_time_clock_lite_admin_js()
    {
        global $current_user;
        $report_action = isset($_POST["report_action"]) ? sanitize_text_field($_POST["report_action"]) : null;
        $employee = isset($_POST["employee"]) ? intval($_POST["employee"]) : intval($current_user->ID); 
        $date_range_start = date($this->mysqlDateFormat, strtotime(sanitize_text_field($_POST["aio_pp_start_date"])));
        $date_range_end = date($this->mysqlDateFormat, strtotime(sanitize_text_field($_POST["aio_pp_end_date"])));
        $errors = null;

        if ($report_action != null) {
            echo json_encode(
                array(
                    "response" => "success",
                    "employee" => $employee,
                    "date_range_start" => $date_range_start,
                    "date_range_end" => $date_range_end,
                    "report_action" => $report_action,
                    "shifts" =>
                    $this->getShiftTotalFromRange(
                        $employee,
                        $date_range_start,
                        $date_range_end
                    ),
                )
            );
        } else {
            echo json_encode(
                array(
                    "response" => "failed",
                    "employee" => $employee,
                    "date_range_start" => $date_range_start,
                    "date_range_end" => $date_range_end,
                    "message" => esc_attr_x("report action cannot be null", "aio-time-clock"),
                    "errors" => $errors,
                )
            );
        }

        die();
    }

    public function aio_tc_custom_post_shift_lite()
    {
        $labels = array(
            'name' => esc_attr_x('Shifts', 'aio-time-clock-lite'),
            'singular_name' => esc_attr_x('Shift', 'aio-time-clock-lite'),
            'add_new' => esc_attr_x('Add New', 'shift', 'aio-time-clock-lite'),
            'add_new_item' => esc_attr_x('Clock Out', 'aio-time-clock-lite'),
            'edit_item' => esc_attr_x('Edit Shift', 'aio-time-clock-lite'),
            'new_item' => esc_attr_x('Clock In', 'aio-time-clock-lite'),
            'all_items' => esc_attr_x('All Shifts', 'aio-time-clock-lite'),
            'view_item' => esc_attr_x('View Shift', 'aio-time-clock-lite'),
            'search_items' => esc_attr_x('Search Shifts', 'aio-time-clock-lite'),
            'not_found' => esc_attr_x('No shifts found', 'aio-time-clock-lite'),
            'not_found_in_trash' => esc_attr_x('No shifts found in the Trash', 'aio-time-clock-lite'),
            'parent_item_colon' => '',
            'menu_name' => esc_attr_x('Employee Shifts', 'aio-time-clock-lite'),
        );
        $args = array(
            'labels' => $labels,
            'description' => esc_attr_x('Employee shifts dates and times', 'aio-time-clock-lite'),
            'query_var' => true,
            'public' => false,
            'show_ui' => true,
            'supports' => array('title', 'author'),
            'has_archive' => true,
            'show_tagcloud' => false,
            'rewrite' => array('slug' => 'shifts'),
            'show_in_nav_menus' => false,
            'supports' => false,
        );
        register_post_type('shift', $args);
    }

    public function aio_timeclock_admin_init_lite()
    {
        add_filter('manage_edit-shift_columns', array($this, 'shiftColumnsFilter'), 10, 1);
        add_action('manage_shift_posts_custom_column', array($this, 'shiftColumn'), 10, 2);
    }

    public function shiftColumnsFilter($columns)
    {
        unset($columns['date']);
        unset($columns['author']);
        $columns['employee'] = esc_attr_x('Employee', 'aio-time-clock-lite');
        $columns['department'] = esc_attr_x('Department', 'aio-time-clock-lite');
        $columns['employee_clock_in_time'] = esc_attr_x('Clock In Time', 'aio-time-clock-lite');
        $columns['employee_clock_out_time'] = esc_attr_x('Clock Out Time', 'aio-time-clock-lite');
        $columns['total_shift_time'] = esc_attr_x('Total Time', 'aio-time-clock-lite');
        return $columns;
    }

    public function shiftColumn($column, $post_id)
    {
        global $post;
        $custom = get_post_custom($post_id);
        $employee_clock_in_time = isset($custom['employee_clock_in_time'][0]) ? $this->cleanDate(sanitize_text_field($custom['employee_clock_in_time'][0])) : null;
        $employee_clock_out_time = isset($custom['employee_clock_out_time'][0]) ? $this->cleanDate(sanitize_text_field($custom['employee_clock_out_time'][0])) : null;
        $shift_sum = "00:00";
        if ($employee_clock_in_time != null && $employee_clock_out_time != null) {
            $shift_sum = $this->secondsToTime($this->dateDifference($employee_clock_in_time, $employee_clock_out_time));
        }

        $author_id = get_the_author_meta('ID');

        switch ($column) {
            case 'employee':
                echo esc_attr($this->getEmployeeName($author_id));
                break;
            case 'department':
                echo esc_attr($this->getDepartmentColumn($author_id));
                break;
            case 'employee_clock_in_time':
                echo esc_attr($employee_clock_in_time);
                break;
            case 'employee_clock_out_time':
                echo esc_attr($employee_clock_out_time);
                break;
            case 'total_shift_time':
                echo esc_attr($shift_sum);
                break;
        }
    }

    public function getDepartmentColumn($author_id)
    {
        $department = "";
        global $user;
        global $wordpress;

        $terms = get_terms('department', array('hide_empty' => false));

        if (!empty($terms)) {
            foreach ($terms as $term) {
                if (is_object_in_term(intval($author_id), 'department', $term)) {
                    $department .= sanitize_text_field($term->name) . " ";
                }
            }
        }

        return $department;
    }

    public function getShiftTotal($post_id)
    {
        $employee_clock_in_time = get_post_meta($post_id, 'employee_clock_in_time', true);
        $employee_clock_out_time = get_post_meta($post_id, 'employee_clock_out_time', true);
        $total_shift_time = strtotime(0);
        if ($employee_clock_in_time != null && $employee_clock_out_time != null) {
            if (strtotime($employee_clock_in_time) > strtotime(0) && strtotime($employee_clock_out_time) > strtotime(0)) {
                $total_shift_time = $this->dateDifference($employee_clock_in_time, $employee_clock_out_time);
            }
        }

        return $total_shift_time;
    }

    public function aio_shift_info_box_meta_lite()
    {
        add_meta_box(
            'shift_info_box',
            esc_attr_x('Shift Info', 'aio-time-clock-lite'),
            array($this, 'aio_shift_info_box_content'),
            'shift',
            'normal',
            'high'
        );
    }

    public function aio_shift_info_box_content()
    {
        require_once("aio-time-clock-box-content.php");
    }

    public function getEmployeeSelect($selected = null)
    {
        $selected = json_decode($selected);
        $count = 0;
        $users = $this->getUsers();
        foreach ($users as $user) {
            $active = "";
            if ($selected == $user["employee_id"]) {
                $active = "selected";
            }
            $user_id = isset($user["employee_id"]) ? intval($user["employee_id"]) : 0;
            $user_first = isset($user["first_name"]) ? sanitize_text_field($user["first_name"]) : "";
            $user_last = isset($user["last_name"]) ? sanitize_text_field($user["last_name"]) : "";
            echo '<option value="' . esc_attr($user_id) . '" ' . esc_attr($active) . '>' . esc_attr($user_first) . ", " . esc_attr($user_last) . '</option>';
            $count++;
        }
    }

    public function getBuiltInRoles()
    {
        return array('aio_tc_employee', 'aio_tc_manager', 'time_clock_admin', 'aio_tc_volunteer', 'aio_tc_contractor', 'employee', 'manager', 'volunteer', 'contractor', 'administrator');
    }

    public function aio_filter_roles_lite($user)
    {
        $roles = $this->getBuiltInRoles();
        return array_intersect($user->roles, $roles);
    }

    public function aio_save_shift_meta_lite($post_id)
    {
        $clock_in = (isset($_REQUEST['clock_in'])) ? sanitize_text_field($_REQUEST['clock_in']) : null;
        $clock_out = (isset($_REQUEST['clock_out'])) ? sanitize_text_field($_REQUEST['clock_out']) : null;

        if ($clock_in != null){
            $clock_in = str_replace("/", "-", $clock_in);
            update_post_meta($post_id, 'employee_clock_in_time', date($this->dateFormat, strtotime($clock_in)));
        }
        else{
            update_post_meta($post_id, 'employee_clock_in_time', null);
        }

        if ($clock_out != null){
            $clock_out = str_replace("/", "-", $clock_out);
            update_post_meta($post_id, 'employee_clock_out_time', date($this->dateFormat, strtotime($clock_out)));
        }
        else{
            update_post_meta($post_id, 'employee_clock_out_time', null);
        }

        if (isset($_REQUEST['employee_id'])) {
            remove_action('save_post', array($this, 'aio_save_shift_meta_lite'));
            $arg = array(
                'ID' => $post_id,
                'post_author' => intval($_REQUEST['employee_id']),
            );
            wp_update_post($arg);
            add_action('save_post', array($this, 'aio_save_shift_meta_lite'));
        }
    }

    public function removeMetaBoxes()
    {
        remove_meta_box('authordiv', 'shift', 'normal');
    }

    public function settingsPageLite()
    {
        include "aio-settings.php";
    }

    public function montioringPage()
    {
        include "aio-monitoring.php";
    }

    public function reportsPageLite()
    {
        include "aio-reports.php";
    }

    function register_aio_timeclock_lite_settings()
    {
        register_setting('nertworks-timeclock-settings-group', 'aio_company_name', array( 'sanitize_callback' => array($this, 'clean_text')));
        register_setting('nertworks-timeclock-settings-group', 'aio_pay_schedule', array( 'sanitize_callback' => array($this, 'clean_text')));
        register_setting('nertworks-timeclock-settings-group', 'aio_wage_manage', array( 'sanitize_callback' => array($this, 'clean_text')));
        register_setting('nertworks-timeclock-settings-group', 'aio_timeclock_time_zone', array( 'sanitize_callback' => array($this, 'clean_text')));
        register_setting('nertworks-timeclock-settings-group', 'aio_timeclock_text_align', array( 'sanitize_callback' => array($this, 'clean_text')));
        register_setting('nertworks-timeclock-settings-group', 'aio_timeclock_redirect_employees', array( 'sanitize_callback' => array($this, 'clean_text')));
        register_setting('nertworks-timeclock-settings-group', 'aio_timeclock_show_avatar', array( 'sanitize_callback' => array($this, 'clean_text')));
    }

    function clean_text($value){
        $value = str_replace('"', "", $value);
        return sanitize_text_field($value);
    }

    public function aio_check_tc_shortcode_lite()
    {
        $loop = new WP_Query(array('post_type' => 'page', 'posts_per_page' => -1));
        while ($loop->have_posts()): $loop->the_post();
            $content = get_the_content();
            if (has_shortcode($content, 'show_aio_time_clock_lite')) {
                return $loop->post->ID;
                break;
            } else {
                //echo "none";
            }
        endwhile;
        wp_reset_query();
    }

    public function check_eprofile_shortcode_lite()
    {
        $loop = new WP_Query(array('post_type' => 'page', 'posts_per_page' => -1));
        while ($loop->have_posts()): $loop->the_post();
            $content = get_the_content();
            if (has_shortcode($content, 'show_aio_employee_profile_lite')) {
                return $loop->post->ID;
            } else {
                //echo "none";
            }
        endwhile;
        wp_reset_query();
    }

    public function memberLoginRedirect($url, $request, $user)
    {
        $tc_page = $this->aio_check_tc_shortcode_lite();
        $roles = $this->getBuiltInRoles();
        $roles = $this->arrayDelete(array('administrator'), $roles);

        if (isset($user->roles)){
            if (is_array($user->roles) && $tc_page != null) {
                if (array_intersect($roles, $user->roles)) {
                    return esc_url(get_permalink($tc_page));
                }                
            }
        }

        return $url;
    }

    public function arrayDelete($del_val, $array)
    {
        if(is_array($del_val)) {
             foreach ($del_val as $del_key => $del_value) {
                foreach ($array as $key => $value){
                    if ($value == $del_value) {
                        unset($array[$key]);
                    }
                }
            }
        } else {
            foreach ($array as $key => $value){
                if ($value == $del_val) {
                    unset($array[$key]);
                }
            }
        }
        return array_values($array);
    }

    public function getTimeZoneListLite()
    {
        $timezones = $tzlist = DateTimeZone::listIdentifiers(DateTimeZone::ALL);
        return $timezones;
    }

    public function shiftsPage()
    {
        wp_redirect(admin_url() . '/edit.php?post_type=shift');
        echo '<script>
                window.location="' . admin_url() . '/edit.php?post_type=shift";
        </script>';
        exit;
    }

    public function employeePage()
    {
        include "aio-employees.php";
    }

    public function departmentPage()
    {
        wp_redirect(admin_url() . '/edit-tags.php?taxonomy=department');

        echo '<script>
                window.location="' . admin_url() . '/edit-tags.php?taxonomy=department";
            </script>';
        exit;
    }

    public function dateDifference($start, $end)
    {
        if (isset($start) && isset($end)) {
            if ($start != null && $end != null) {
                $start = sanitize_text_field($start);
                $end = sanitize_text_field($end);
                if ($this->isValidDate($start) && $this->isValidDate($end)) {
                    $start = str_replace('/', '-', $start);
                    $end = str_replace('/', '-', $end);
                    $s = new DateTime();
                    $start_date = $s->setTimestamp(intval(strtotime($start)));
                    $e = new DateTime();
                    $end_date = $e->setTimestamp(intval(strtotime($end)));
                    $diff = $end_date->diff($start_date);
                    $diff_sec = $diff->format('%r') . ( // prepend the sign - if negative, change it to R if you want the +, too
                        ($diff->s) + // seconds (no errors)
                        (60 * ($diff->i)) + // minutes (no errors)
                        (60 * 60 * ($diff->h)) + // hours (no errors)
                        (24 * 60 * 60 * ($diff->d)) + // days (no errors)
                        (30 * 24 * 60 * 60 * ($diff->m)) + // months (???)
                        (365 * 24 * 60 * 60 * ($diff->y)) // years (???)
                    );
                    return $diff_sec;
                }
            }
        }
    }

    public function addTwoTimes($time1 = "00:00", $time2 = "00:00")
    {
        $times = array($time1, $time2);
        $hours = 0;
        $minutes = 0;        
        $seconds = 0;
        foreach ($times as $time)
        {
            if ($this->Contains($time, ":")){
                list($hour,$minute) = explode(':', $time);
                $seconds += $hour*3600;
                $seconds += $minute*60;                
            }            
        }
        $hours = floor($seconds/3600);
        $seconds -= $hours*3600;
        $minutes  = floor($seconds/60);
        $seconds -= $minutes*60;
        if($seconds < 9)
        {
        $seconds = "0".$seconds;
        }
        if($minutes < 9)
        {
        $minutes = "0".$minutes;
        }
          if($hours < 9)
        {
        $hours = "0".$hours;
        }
        return "{$hours}:{$minutes}";
    }

    public function Contains($haystack, $needle)
    {
        if (strpos($haystack, $needle) !== false) {
            return true;
        } else {
            return false;
        }
    }

    public function getShiftTotalFromRange($employee, $date_range_start, $date_range_end)
    {
        $shift_total_time = "00:00";
        $shift_array = array();
        $wage_total = floatval(0);
        $count = 0;
        $loop = new WP_Query(array('post_type' => 'shift', 'author' => $employee, 'posts_per_page' => -1));

        while ($loop->have_posts()): $loop->the_post();
            $shift_sum = "00:00";
            $shift_id = $loop->post->ID;
            $custom = get_post_custom($shift_id);
            $employee_clock_in_time = isset($custom["employee_clock_in_time"][0]) ? sanitize_text_field($custom["employee_clock_in_time"][0]) : null;
            $employee_clock_out_time = isset($custom["employee_clock_out_time"][0]) ? sanitize_text_field($custom["employee_clock_out_time"][0]) : null;
            $searchDateBegin = sanitize_text_field($date_range_start);
            $searchDateEnd = sanitize_text_field($date_range_end);
            if ((strtotime($employee_clock_in_time) >= strtotime($searchDateBegin)) && (strtotime($employee_clock_in_time) <= strtotime($searchDateEnd))) {
                $author_id = $loop->post->post_author;
                $last_name = sanitize_text_field(get_the_author_meta('last_name', $author_id));
                $first_name = sanitize_text_field(get_the_author_meta('first_name', $author_id));
                $wage = sanitize_text_field(get_the_author_meta('employee_wage', $author_id));
                
                if ($employee_clock_in_time != null && $employee_clock_out_time != null) {
                    $shift_sum = $this->secondsToTime($this->dateDifference($employee_clock_in_time, $employee_clock_out_time));
                    if (!$this->isNull($wage)){
                        $decimal_total = $this->TimeToDecimal($shift_sum);
                        $wage_total += (floatval($wage) * floatval($decimal_total));
                    }                    
                    $shift_total_time = $this->addTwoTimes($shift_total_time, $shift_sum);
                }
                array_push($shift_array,
                    array(
                        "shift_id" => $shift_id,
                        "employee_clock_in_time" => $this->cleanDate($employee_clock_in_time),
                        "employee_clock_out_time" => $this->cleanDate($employee_clock_out_time),
                        "first_name" => $first_name,
                        "last_name" => $last_name,
                        "shift_sum" => $shift_sum,
                    )
                );
                $count++;
            }
        endwhile;
        wp_reset_query();        

        return array(
            "response" => "success",
            "shift_count" => $count,
            "shift_total_time" => $shift_total_time,
            "wage_total" => $this->Money($wage_total),
            "shift_array" => $shift_array,
        );
    }

    function TimeToDecimal($time) 
    {
        $timeArr = explode(':', $time);
        $decTime = ($timeArr[0]*60) + ($timeArr[1]);        
     
        return ($decTime / 60);
    }

    public function Money($amount)
    {
        return number_format($amount, 2);
    }

    public function isNull($string)
    {
        if ($string == null || $string == ''){
            return true;
        }
        else{
            return false;
        }
    }

    public function modifyWageLite($profile_fields)
    {
        $profile_fields['employee_wage'] = esc_attr_x('Wage', 'aio-time-clock-lite');
        return $profile_fields;
    }

    public function adminNoticesLite()
    {
        /*
        if (get_option('aio_time_clock_lite_update_redirect', false)) {
        ?>
        <div class="notice notice-success is-dismissible">
        <p><?php esc_attr_x( 'You have recently updated the AIO Time Clock Lite. Visit your update page to make sure you don\'t miss anything import.', 'aio-time-clock-lite' ); ?></p>
        </div>
        <?php
        }
     */
    }

    public function getCurrentTime()
    {
        $timezone = null;
        $current_time = null;
        $timezone_option = get_option('aio_timeclock_time_zone');
        if ($timezone_option != null) {
            date_default_timezone_set($timezone_option);
            $current_time = date($this->mysqlDateFormat);
            $time_type = "timezone option";
        } else {
            $timezone = 'UTC';
            date_default_timezone_set($timezone);
            $current_time = date($this->mysqlDateFormat);
            $time_type = "default utc";
        }

        return array(
            "response" => "success",
            "timezone_option" => $timezone_option,
            "current_time" => $current_time,
            "time_type" => $time_type,
            "timezone" => $timezone
        );
    }

    public function getDateTimeFormat()
    {
        $date_format = sanitize_text_field(get_option('date_format'));
        $time_format = sanitize_text_field(get_option('time_format'));
        if ($date_format == null) {
            $date_format = 'Y-m-d';
        }
        if ($time_format == null) {
            $time_format = 'h:i:s A';
        }

        return $date_format . " " . $time_format;
    }

    public function getDateFormat()
    {
        $date_format = get_option('date_format');
        if ($date_format == null) {
            $date_format = 'Y-m-d';
        }

        return $date_format;
    }

    public function getTimeFormat()
    {
        $time_format = get_option('time_format');
        if ($time_format == null) {
            $time_format = 'h:i:s A';
        }

        return $time_format;
    }

    public function isTimeMachine($date)
    {
        $now = date("Y-m-d h:i:s A");
        $end_date = date("Y-m-d", strtotime(date("Y-m-d", strtotime($now)) . " - 10 years"));
        return (strtotime($date) <= strtotime($end_date));
    }    

    public function aio_lite_update_department_count($terms, $taxonomy)
    {
        global $wpdb;

        foreach ((array) $terms as $term) {

            $count = $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM $wpdb->term_relationships WHERE term_taxonomy_id = %d", intval($term)));

            do_action('edit_term_taxonomy', $term, $taxonomy);
            $wpdb->update($wpdb->term_taxonomy, compact('count'), array('term_taxonomy_id' => $term));
            do_action('edited_term_taxonomy', $term, $taxonomy);
        }
    }

    public function aio_lite_register_user_taxonomy()
    {

        register_taxonomy(
            'department',
            'user',
            array(
                'public' => true,
                'labels' => array(
                    'name' => esc_attr_x('Department', 'aio-time-clock-lite'),
                    'singular_name' => esc_attr_x('Department', 'aio-time-clock-lite'),
                    'menu_name' => esc_attr_x('Departments', 'aio-time-clock-lite'),
                    'search_items' => esc_attr_x('Search Departments', 'aio-time-clock-lite'),
                    'popular_items' => esc_attr_x('Popular Departments', 'aio-time-clock-lite'),
                    'all_items' => esc_attr_x('All Departments', 'aio-time-clock-lite'),
                    'edit_item' => esc_attr_x('Edit Department', 'aio-time-clock-lite'),
                    'update_item' => esc_attr_x('Update Department', 'aio-time-clock-lite'),
                    'add_new_item' => esc_attr_x('Add New Department', 'aio-time-clock-lite'),
                    'new_item_name' => esc_attr_x('New Department Name', 'aio-time-clock-lite'),
                    'separate_items_with_commas' => esc_attr_x('Separate departments with commas', 'aio-time-clock-lite'),
                    'add_or_remove_items' => esc_attr_x('Add or remove departments', 'aio-time-clock-lite'),
                    'choose_from_most_used' => esc_attr_x('Choose from the most popular departments', 'aio-time-clock-lite'),
                ),
                'rewrite' => array(
                    'with_front' => true,
                    'slug' => 'author/department', // Use 'author' (default WP user slug).
                ),
                'capabilities' => array(
                    'manage_terms' => 'edit_users', // Using 'edit_users' cap to keep this simple.
                    'edit_terms' => 'edit_users',
                    'delete_terms' => 'edit_users',
                    'assign_terms' => 'read',
                ),
                'update_count_callback' => 'aio_lite_update_department_count', // Use a custom function to update the count.
            )
        );
    }

    public function aio_tc_lite_save_user_department_terms($user_id)
    {

        $tax = get_taxonomy('department');

        if (!current_user_can('edit_user', $user_id) && current_user_can($tax->cap->assign_terms)) {
            return false;
        }

        if (isset($_POST['department'])){
            $term = sanitize_text_field($_POST['department']);
            wp_set_object_terms($user_id, array($term), 'department', false);
        }

        clean_object_term_cache($user_id, 'department');
    }

    public function aio_lite_add_department_admin_page()
    {

        $tax = get_taxonomy('department');

        add_users_page(
            esc_attr($tax->labels->menu_name),
            esc_attr($tax->labels->menu_name),
            $tax->cap->manage_terms,
            'edit-tags.php?taxonomy=' . $tax->name
        );
    }

    public function aio_lite_manage_department_user_column($columns)
    {

        unset($columns['posts']);

        $columns['users'] = esc_attr_x('Users', 'aio-time-clock-lite');

        return $columns;
    }

    public function aio_lite_manage_department_column($display, $column, $term_id)
    {

        if ('users' === $column) {
            $term = get_term($term_id, 'department');
            echo esc_attr($term->count);
        }
    }

    public function aio_lite_save_user_department_terms($user_id)
    {

        $tax = get_taxonomy('department');

        if (!current_user_can('edit_user', $user_id) && current_user_can($tax->cap->assign_terms)) {
            return false;
        }

        if (isset($_POST['department'])){
            $term = sanitize_text_field($_POST['department']);
            wp_set_object_terms(intval($user_id), array($term), 'department', false);
        }

        clean_object_term_cache(intval($user_id), 'department');
    }

    public function aio_lite_disable_username($username)
    {

        if ('department' === $username) {
            $username = '';
        }

        return esc_attr($username);
    }

    public function isValidDate($date)
    {
        if ($date != null) {
            $date = str_replace('/', '-', $date);
            return (bool) strtotime($date);
        } else {
            return false;
        }
    }

    public function loadWidgets()
    {
        register_widget('AIO_Time_Clock_Lite_Widgets');
    }

    public function checkPermission($user_roles)
    {
        $found = 0;
        global $current_user;

        $original_roles = $this->getBuiltInRoles();
        if (isset($original_roles)) {
            foreach ($user_roles as $r) {
                if (in_array($r, $original_roles, false)) {
                    $found++;
                }
            }
        }

        if (current_user_can('administrator')) {
            $found++;
        }

        if ($found > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function cleanDate($date)
    {
        if ($this->isValidDate($date) && $date != null) {
            return date($this->getDateTimeFormat(), strtotime($date));
        } else {
            return null;
        }
    }

    public function getUsers()
    {
        $users = array();
        $blog_id = get_current_blog_id();
        $built_in_roles = $this->getBuiltInRoles();

        $args = array(
            'blog_id' => $blog_id,
            'role' => '',
            'role__in' => $built_in_roles,
            'role__not_in' => array(),
            'meta_key' => 'last_name',
            'meta_value' => '',
            'meta_compare' => '',
            'meta_query' => array(),
            'date_query' => array(),
            'include' => array(),
            'exclude' => array(),
            'orderby' => 'meta_value',
            'order' => 'ASC',
            'offset' => '',
            'search' => '',
            'number' => '',
            'count_total' => false,
            'fields' => 'all',
            'who' => '',
        );
        $your_users = get_users($args);
        foreach ($your_users as $user) {
            array_push(
                $users,
                array(
                    "employee_id" => $user->ID,
                    "first_name" => $user->first_name,
                    "last_name" => $user->last_name,
                    "department" => $this->getDepartment($user->ID),
                    "roles" => $user->roles,
                )
            );
        }
        return $users;
    }

    public function getEmployeeName($employee_id)
    {
        $full_name = null;
        $employee_id = intval($employee_id);
        $first_name = sanitize_text_field(get_the_author_meta('first_name', $employee_id));
        $last_name = sanitize_text_field(get_the_author_meta('last_name', $employee_id));

        if (($first_name == null || $last_name == null)) {
            if ($employee_id != null) {
                $user_info = get_userdata($employee_id);
                if (isset($user_info)) {
                    $full_name = sanitize_text_field($user_info->last_name) . ", " . sanitize_text_field($user_info->first_name);
                }
            }
        } else {
            $full_name = $last_name . ", " . $first_name;
        }

        return $full_name;
    }

    public function getDepartment($user_id)
    {
        $department = "";
        $user_groups = wp_get_object_terms($user_id, 'department', array('fields' => 'all_with_object_id')); // Get user group detail
        foreach ($user_groups as $user_gro) {
            $department = $user_gro->name; // Get current user group name
        }

        return $department;
    }

    public function aio_lite_edit_user_department_section($user)
    {
        require_once("templates/department-section.php");
    }

    public function getMonitoringShiftColumn($employee_id){
        return '<div class="shiftStatus">      
            <span>' . esc_attr_x('Working', 'aio-time-clock') . '</span>
        </div>';
    }

    public function getTranslationStrings()
    {
        return array(
            'Nonce' => wp_create_nonce("clock_in_nonce"),
            'ajaxurl' => admin_url('admin-ajax.php'),
            'isClockedIn' => esc_attr_x('You are currently clocked in', 'aio-time-clock-lite'),
            'clockInTime' => esc_attr_x('Clock In Time', 'aio-time-clock-lite'),
            'updateNote' => esc_attr_x('Update Note', 'aio-time-clock-lite'),
            'addNote' => esc_attr_x('Add Note', 'aio-time-clock-lite'),
            'clockInMessage' => esc_attr_x('Click CLOCK IN to START your shift', 'aio-time-clock-lite'),
            'locationError' => esc_attr_x('Location Required', 'aio-time-clock-lite'),
            'clockedOutMessage' => esc_attr_x('You have been clocked out', 'aio-time-clock-lite'),
            'clockOutFail' => esc_attr_x('Clock out failed', 'aio-time-clock-lite'),
            'clockInFail' => esc_attr_x('Clock In failed', 'aio-time-clock-lite'),
            'currentTime' => esc_attr_x('Current Time', 'aio-time-clock-lite'),
            'clockIn' => esc_attr_x('Clock In', 'aio-time-clock-lite'),
            'clockOut' => esc_attr_x('Clock Out', 'aio-time-clock-lite'),
            'Name' => esc_attr_x('Name', 'aio-time-clock-lite'),
            'Options' => esc_attr_x('Options', 'aio-time-clock-lite'),
            'ShiftTotal' => esc_attr_x('Shift Total', 'aio-time-clock-lite'),
            'TotalShifts' => esc_attr_x('Total Shifts', 'aio-time-clock-lite'),
            'TotalShiftTime' => esc_attr_x('Total Shift Time', 'aio-time-clock-lite'),
            'WageTotal' => esc_attr_x('Wage Total', 'aio-time-clock-lite'),
            'TimeClockDetected' => esc_attr_x('Widget time clock disabled while on time clock page', 'aio-time-clock-lite')
        );
    }

    public function getProFeatures()
    {
        $features = array(
            array(
                "title" => esc_attr_x('More Settings', 'aio-time-clock-lite'),
                "description" => esc_attr_x('Easily customize the time clock for your company', 'aio-time-clock-lite'),
                "image" => "/images/pro/1-settings.png"
            ),
            array(
                "title" => esc_attr_x('Custom Roles', 'aio-time-clock-lite'),
                "description" => esc_attr_x('Add your own custom roles for time clock access', 'aio-time-clock-lite'),
                "image" => "/images/pro/2-custom-roles.png"
            ),
            array(
                "title" => esc_attr_x('Manager Profiles', 'aio-time-clock-lite'),
                "description" => esc_attr_x('Manager profiles that can export, import and edit shifts', 'aio-time-clock-lite'),
                "image" => "/images/pro/3-manager-profile.png"
            ),
            array(
                "title" => esc_attr_x('Improved Reports', 'aio-time-clock-lite'),
                "description" => esc_attr_x('Custom Simple and Advanced Shift Reports', 'aio-time-clock-lite'),
                "image" => "/images/pro/4-reports.png"
            ),
            array(
                "title" => esc_attr_x('Charts and Graphs', 'aio-time-clock-lite'),
                "description" => esc_attr_x('View activity in the form of a chart', 'aio-time-clock-lite'),
                "image" => "/images/pro/5-charts.png"            
            ),
            array(
                "title" => esc_attr_x('Multiple Shift Views', 'aio-time-clock-lite'),
                "description" => esc_attr_x('View, edit or create bulk shifts', 'aio-time-clock-lite'),
                "image" => "/images/pro/6-shift-views.png"
            ),
            array(
                "title" => esc_attr_x('Predefined Locations', 'aio-time-clock-lite'),
                "description" => esc_attr_x('Unlimited Clock in Locations', 'aio-time-clock-lite'),
                "image" => "/images/pro/7-locations.png"
            ),
            array(
                "title" => esc_attr_x('IP And GPS Tracking', 'aio-time-clock-lite'),
                "description" => esc_attr_x('Shifts can show the location of an IP address and a GPS coordinate', 'aio-time-clock-lite'),
                "image" => "/images/pro/8-shift-info.png"
            ),
            array(
                "title" => esc_attr_x('Quick Pick Time Clock', 'aio-time-clock-lite'),
                "description" => esc_attr_x('List style time clock page with pin pad entry instead of a login', 'aio-time-clock-lite'),
                "image" => "/images/pro/9-quick-pick.png"
            ),
            array(
                "title" => esc_attr_x('Predefined Shifts', 'aio-time-clock-lite'),
                "description" => esc_attr_x('Create prefined shifts that employees can choose from instead of a dynamic clock', 'aio-time-clock-lite'),
                "image" => "/images/pro/10-predefined-shifts.png"
            ),
            array(
                "title" => esc_attr_x('Extendable', 'aio-time-clock-lite'),
                "description" => esc_attr_x('Extensions/Addons Supported to allow anything to be possible', 'aio-time-clock-lite'),
                "image" => "/images/pro/11-extendable.png"                
            )   
        );        
        return $features;
    }
}