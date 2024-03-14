<?php
namespace WPHR\HR_MANAGER\HRM;

/**
 * Handle the form submissions
 *
 * Although our most of the forms uses ajax and popup, some
 * are needed to submit via regular form submits. This class
 * Handles those form submission in this module
 *
 * @package WPHR Manager
 * @subpackage HRM
 */
class Form_Handler {

    /**
     * Hook 'em all
     */
    public function __construct() {
        add_action('wphr_action_hr-leave-assign-policy', array($this, 'leave_entitlement'));
        add_action('wphr_action_hr-leave-req-new', array($this, 'leave_request'));

        // permission
        add_action('wphr_action_wphr-hr-employee-permission', array($this, 'employee_permission'));

        add_action('admin_init', array($this, 'leave_request_status_change'));
        add_action('admin_init', array($this, 'handle_employee_status_update'));
        add_action('admin_init', array($this, 'handle_leave_calendar_filter'));

        $hr_management = sanitize_title(__('WPHR Manager', 'wphr'));
        add_action("load-{$hr_management}_page_wphr-hr-employee", array($this, 'employee_bulk_action'));
        add_action("load-{$hr_management}_page_wphr-hr-designation", array($this, 'designation_bulk_action'));
        add_action("load-{$hr_management}_page_wphr-hr-depts", array($this, 'department_bulk_action'));
        add_action("load-{$hr_management}_page_wphr-hr-reporting", array($this, 'reporting_headcount_bulk_action'));

        $leave = sanitize_title(__('WPHR Leave', 'wphr'));
        add_action('load-toplevel_page_wphr-leave', array($this, 'leave_request_bulk_action'));
        add_action("load-{$leave}_page_wphr-leave-assign", array($this, 'entitlement_bulk_action'));
        add_action("load-{$leave}_page_wphr-holiday-assign", array($this, 'holiday_action'));
        //add_action( "load-{$leave}_page_wphr-holiday-by-location-assign", array( $this, 'holiday_action' ) );
        add_action("load-{$leave}_page_wphr-leave-policies", array($this, 'leave_policies'));
    }

    /**
     * Hnadle leave calendar filter
     *
     * @since 0.1
     *
     * @return void
     */
    public function handle_leave_calendar_filter() {
        if (!isset($_POST['wphr_leave_calendar_filter'])) {
            return;
        }
        $designation = isset($_POST['designation']) ? sanitize_text_field($_POST['designation']) : '';
        $department = isset($_POST['department']) ? sanitize_text_field($_POST['department']) : '';
        $url = admin_url("admin.php?page=wphr-leave-calendar&designation=$designation&department=$department");
        wp_redirect($url);
        exit();
    }

    /**
     * Check is current page actions
     *
     * @since 0.1
     *
     * @param  integer $page_id
     * @param  integer $bulk_action
     *
     * @return boolean
     */
    public function verify_current_page_screen($page_id, $bulk_action) {



        if (!isset($_REQUEST['_wpnonce']) || !isset($_GET['page'])) {
            return false;
        }

        if ( sanitize_text_field($_GET['page']) != $page_id) {
            return false;
        }

        if (!wp_verify_nonce(sanitize_text_field( $_REQUEST['_wpnonce'] ), $bulk_action)) {
            return false;
        }

        return true;
    }

    /**
     * Handle leave policies bulk action
     *
     * @since 0.1
     *
     * @return void [redirection]
     */
    public function leave_policies() {
        // Check nonce validaion
        if (!$this->verify_current_page_screen('wphr-leave-policies', 'bulk-leave_policies')) {
            return;
        }

        // Check permission
        if (!current_user_can('wphr_leave_manage')) {
            wp_die(__('You do not have sufficient permissions to do this action', 'wphr'));
        }

        if (isset($_POST['action']) && sanitize_text_field($_POST['action']) == 'trash') {

            if (isset($_POST['policy_id'])) {
                wphr_hr_leave_policy_delete( sanitize_text_field( $_POST['policy_id'] ) );
            }
        }

        return true;
    }

    /**
     * Handle entitlement bulk actions
     *
     * @since 0.1
     *
     * @return void
     */
    public function entitlement_bulk_action() {
        if (!$this->verify_current_page_screen('wphr-leave-assign', 'bulk-entitlements')) {
            return;
        }

        // Check permission
        if (!current_user_can('wphr_leave_manage')) {
            wp_die(__('You do not have sufficient permissions to do this action', 'wphr'));
        }

        $employee_table = new \WPHR\HR_MANAGER\HRM\Entitlement_List_Table();
        $action = $employee_table->current_action();

        if ($action) {
            $redirect = remove_query_arg(array('_wp_http_referer', '_wpnonce', 'filter_entitlement'), wp_unslash($_SERVER['REQUEST_URI']));

            if ($action == 'filter_entitlement') {
                wp_redirect($redirect);
                exit();
            }

            if ($action == 'entitlement_delete') {
                if (isset($_GET['entitlement_id']) && !empty($_GET['entitlement_id'])) {
                    $entitlement_ids = custom_sanitize_array( $_GET['entitlement_id'] );
                    foreach ( $entitlement_ids as $key => $ent_id) {
                        $entitlement_data = \WPHR\HR_MANAGER\HRM\Models\Leave_Entitlement::select('user_id', 'policy_id')->find($ent_id)->toArray();
                        wphr_hr_delete_entitlement($ent_id, $entitlement_data['user_id'], $entitlement_data['policy_id']);
                    }
                }

                wp_redirect($redirect);
                exit();
            }
        }
    }

    /**
     * Leave request bulk actions
     *
     * @since 1.0
     *
     * @return void redirect
     */
    public function leave_request_bulk_action() {
        // Check nonce validaion
        if (!$this->verify_current_page_screen('wphr-leave', 'bulk-leaves')) {
            return;
        }

        // Check permission
        if (!current_user_can('wphr_leave_manage')) {
            wp_die(__('You do not have sufficient permissions to do this action', 'wphr'));
        }

        $leave_request_table = new \WPHR\HR_MANAGER\HRM\Leave_Requests_List_Table();
        $action = $leave_request_table->current_action();

        if ($action) {

            $redirect = remove_query_arg(array('_wp_http_referer', '_wpnonce'), wp_unslash($_SERVER['REQUEST_URI']));

            switch ($action) {

                case 'delete' :

                    if (isset($_GET['request_id']) && !empty($_GET['request_id'])) {
                        $request_ids = custom_sanitize_array( $_GET['request_id'] );
                        foreach ( $request_ids as $key => $request_id ) {
                            \WPHR\HR_MANAGER\HRM\Models\Leave_request::find($request_id)->delete();
                        }
                    }

                    wp_redirect($redirect);
                    exit();

                case 'approved' :
                    if (isset($_GET['request_id']) && !empty($_GET['request_id'])) {
                        $request_ids = custom_sanitize_array( $_GET['request_id'] );
                        foreach ( $request_ids as $key => $request_id ) {
                            wphr_hr_leave_request_update_status($request_id, 1);

                            $approved_email = wphr()->emailer->get_email('Approved_Leave_Request');

                            if (is_a($approved_email, '\WPHR\HR_MANAGER\Email')) {
                                $approved_email->trigger($request_id);
                            }
                        }
                    }
                    
                    wp_redirect($redirect);
                    exit();

                case 'reject' :
                    if (isset($_GET['request_id']) && !empty($_GET['request_id'])) {
                        $request_ids = custom_sanitize_array( $_GET['request_id'] );
                        foreach ( $request_ids as $key => $request_id) {
                            wphr_hr_leave_request_update_status($request_id, 3);

                            $rejected_email = wphr()->emailer->get_email('Rejected_Leave_Request');

                            if (is_a($rejected_email, '\WPHR\HR_MANAGER\Email')) {
                                $rejected_email->trigger($request_id);
                            }
                        }
                    }

                    wp_redirect($redirect);
                    exit();

                case 'pending':
                    if (isset($_GET['request_id']) && !empty($_GET['request_id'])) {
                        $request_ids = custom_sanitize_array( $_GET['request_id'] );
                        foreach ( $request_ids as $key => $request_id) {
                            wphr_hr_leave_request_update_status($request_id, 2);
                        }
                    }

                    wp_redirect($redirect);
                    exit();

                case 'archive':
                    if (isset($_GET['request_id']) && !empty($_GET['request_id'])) {
                        $request_ids = custom_sanitize_array( $_GET['request_id'] );
                        foreach ($request_ids as $key => $request_id) {
                            wphr_hr_archive_unarchive_approved_leaves($request_id);
                        }
                    }
                    
                    wp_redirect($redirect.'&status=1');
                    exit();

                case 'unarchive':
                    if (isset($_GET['request_id']) && !empty($_GET['request_id'])) {
                        $request_ids = custom_sanitize_array( $_GET['request_id'] );
                        foreach ( $request_ids as $key => $request_id) {
                            wphr_hr_archive_unarchive_approved_leaves($request_id, 0);
                        }
                    }
                    
                    wp_redirect($redirect.'&status=4');
                    exit();

                case 'Delete':
                    if (isset($_GET['request_id']) && !empty($_GET['request_id'])) {
                        $request_ids = custom_sanitize_array( $_GET['request_id'] );
                        foreach ( $request_ids as $key => $request_id) {
                            wphr_hr_leave_request_update_status($request_id, 5);
                        }
                    }

                    wp_redirect($redirect);
                    exit();
            }
        }
    }

    /**
     * Handle Employee Bulk actions
     *
     * @since 0.1
     *
     * @return void [redirection]
     */
    public function employee_bulk_action() {
        // Nonce validation
        if (!$this->verify_current_page_screen('wphr-hr-employee', 'bulk-employees')) {
            return;
        }

        // Check permission if not hr manager then go out from here
        if (!current_user_can(wphr_hr_get_manager_role())) {
            wp_die(__('You do not have sufficient permissions to do this action', 'wphr'));
        }

        $employee_table = new \WPHR\HR_MANAGER\HRM\Employee_List_Table();
        $action = $employee_table->current_action();

        if ($action) {

            $redirect = remove_query_arg(array('_wp_http_referer', '_wpnonce', 'filter_employee'), wp_unslash($_SERVER['REQUEST_URI']));

            switch ($action) {

                case 'delete' :

                    if (isset($_GET['employee_id']) && !empty($_GET['employee_id'])) {
                        wphr_employee_delete( sanitize_text_field( $_GET['employee_id'] ), false);
                    }

                    wp_redirect($redirect);
                    exit();

                case 'permanent_delete' :
                    if (isset($_GET['employee_id']) && !empty($_GET['employee_id'])) {
                        wphr_employee_delete( sanitize_text_field( $_GET['employee_id'] ), true);
                    }

                    wp_redirect($redirect);
                    exit();

                case 'restore' :
                    if (isset($_GET['employee_id']) && !empty($_GET['employee_id'])) {
                        wphr_employee_restore( sanitize_text_field( $_GET['employee_id'] ) );
                    }

                    wp_redirect($redirect);
                    exit();

                case 'filter_employee':
                    wp_redirect($redirect);
                    exit();

                case 'employee_search':
                    $redirect = remove_query_arg(array('employee_search'), $redirect);
                    wp_redirect($redirect);
                    exit();
            }
        }
    }

    /**
     * Handle designation bulk action
     *
     * @since 0.1
     *
     * @return void [redirection]
     */
    public function designation_bulk_action() {
        if (!$this->verify_current_page_screen('wphr-hr-designation', 'bulk-designations')) {
            return;
        }

        // Check permission if not hr manager then go out from here
        if (!current_user_can(wphr_hr_get_manager_role())) {
            wp_die(__('You do not have sufficient permissions to do this action', 'wphr'));
        }

        $employee_table = new \WPHR\HR_MANAGER\HRM\Designation_List_Table();
        $action = $employee_table->current_action();

        if ($action) {

            $redirect = remove_query_arg(array('_wp_http_referer', '_wpnonce', 'action', 'action2'), wp_unslash($_SERVER['REQUEST_URI']));

            switch ($action) {

                case 'designation_delete' :

                    if (isset($_GET['desig']) && !empty($_GET['desig'])) {
                        $not_deleted_item = wphr_hr_delete_designation( sanitize_text_field( $_GET['desig'] ) );
                    }

                    if (!empty($not_deleted_item)) {
                        $redirect = add_query_arg(array('desig_delete' => implode(',', $not_deleted_item)), $redirect);
                    }

                    wp_redirect($redirect);
                    exit();
            }
        }
    }

    /**
     * Department handle bulk action
     *
     * @since 0.1
     *
     * @return void [redirection]
     */
    public function department_bulk_action() {
        // Check nonce validation
        if (!$this->verify_current_page_screen('wphr-hr-depts', 'bulk-departments')) {
            return;
        }

        // Check permission if not hr manager then go out from here
        if (!current_user_can(wphr_hr_get_manager_role())) {
            wp_die(__('You do not have sufficient permissions to do this action', 'wphr'));
        }


        $employee_table = new \WPHR\HR_MANAGER\HRM\Department_List_Table();
        $action = $employee_table->current_action();

        if ($action) {

            $redirect = remove_query_arg(array('_wp_http_referer', '_wpnonce', 'action', 'action2'), wp_unslash($_SERVER['REQUEST_URI']));
            $resp = [];

            switch ($action) {

                case 'delete_department' :

                    if (isset($_GET['department_id']) && custom_sanitize_array( $_GET['department_id'] ) ) {
                        $department_id = custom_sanitize_array( $_GET['department_id'] );
                        foreach ($department_id as $key => $dept_id) {
                            $resp[] = wphr_hr_delete_department($dept_id);
                        }
                    }

                    if (in_array(false, $resp)) {
                        $redirect = add_query_arg(array('department_delete' => 'item_deleted'), $redirect);
                    }

                    wp_redirect($redirect);
                    exit();
            }
        }
    }

    /**
     * Remove all holiday
     *
     * @since 0.1
     *
     * @return void
     */
    public function holiday_action() {


        // Check nonce validation
        if (!$this->verify_current_page_screen('wphr-holiday-assign', 'bulk-holiday')) {
            return;
        }

        // Check permission
        if (!current_user_can('wphr_leave_manage')) {
            wp_die(__('You do not have sufficient permissions to do this action', 'wphr'));
        }

        $this->remove_holiday(sanitize_text_field($_GET));

        $query_arg = add_query_arg(array('s' => sanitize_text_field($_GET['s']), 'from' => sanitize_text_field($_GET['from']), 'to' => sanitize_text_field($_GET['to'])), sanitize_text_field($_GET['_wp_http_referer']));
        wp_redirect($query_arg);
        exit();
    }

    /**
     * Handle hoiday remove functionality
     *
     * @since 0.1
     *
     * @param array $get
     *
     * @return boolean
     */
    public function remove_holiday($get) {

        // Check permission
        if (!current_user_can('wphr_leave_manage')) {
            wp_die(__('You do not have sufficient permissions to do this action', 'wphr'));
        }

        if (isset($get['action']) && $get['action'] == 'trash') {
            if (isset($get['holiday_id'])) {
                wphr_hr_delete_holidays($get['holiday_id']);
                return true;
            }
        }

        if (isset($get['action2']) && $get['action2'] == 'trash') {
            if (isset($get['holiday_id'])) {
                wphr_hr_delete_holidays($get['holiday_id']);
                return true;
            }
        }

        return false;
    }

    /**
     * Add entitlement with leave policies to employees
     *
     * @since 0.1
     *
     * @return void
     */
    public function leave_entitlement() {

        if (!wp_verify_nonce( sanitize_text_field( $_POST['_wpnonce'] ), 'wphr-hr-leave-assign')) {
            die(__('Something went wrong!', 'wphr'));
        }

        if (!current_user_can('wphr_leave_manage')) {
            wp_die(__('You do not have sufficient permissions to do this action', 'wphr'));
        }

        $affected = 0;
        $errors = array();
        $employees = array();
        $cur_year = (int) date('Y');
        $page_url = admin_url('admin.php?page=wphr-leave-assign&tab=assignment');

        $is_single = !isset($_POST['assignment_to']);
        $leave_policy = isset($_POST['leave_policy']) ? intval(sanitize_text_field($_POST['leave_policy']) ) : '-1';
        $leave_period = isset($_POST['leave_period']) ? sanitize_text_field($_POST['leave_period']) : '-1';
        $single_employee = isset($_POST['single_employee']) ? intval(sanitize_text_field($_POST['single_employee']) ) : '-1';
        $location = isset($_POST['location']) ? intval(sanitize_text_field($_POST['location']) ) : '-1';
        $department = isset($_POST['department']) ? intval(sanitize_text_field($_POST['department']) ) : '-1';
        $comment = isset($_POST['comment']) ? wp_kses_post($_POST['comment']) : '-1';

        if (!$leave_policy) {
            $errors[] = 'invalid-policy';
        }

        if (!in_array(date('Y',strtotime($leave_period)), array($cur_year - 1, $cur_year, $cur_year + 1))) {
            $errors[] = 'invalid-period';
        }

        if ($is_single && !$single_employee) {
            $errors[] = 'invalid-employee';
        }

        // bail out if error found
        if ($errors) {
            $first_error = reset($errors);
            $redirect_to = add_query_arg(array('error' => $first_error), $page_url);
            wp_safe_redirect($redirect_to);
            exit;
        }

        // fetch employees if not single
        if (!$is_single) {

            $employees = wphr_hr_get_employees(array(
                'location' => $location,
                'department' => $department
            ));
        } else {

            $user = get_user_by('id', $single_employee);
            $emp = new \stdClass();
            $emp->id = $user->ID;
            $emp->display_name = $user->display_name;

            $employees[] = $emp;
        }

        if ($employees) {
            $from_date = $leave_period;
            $to_date = date('Y-m-t 23:59:59', strtotime('+11 month', strtotime($leave_period)));
            $policy = wphr_hr_leave_get_policy($leave_policy);

            if (!$policy) {
                return;
            }
            $affected = 0;
            foreach ($employees as $employee) {
                $data = array(
                    'user_id' => $employee->id,
                    'policy_id' => $leave_policy,
                    'days' => $policy->value,
                    'from_date' => $from_date,
                    'to_date' => $to_date,
                    'comments' => $comment,
                    'status' => 1
                );

                $inserted = wphr_hr_leave_insert_entitlement($data);

                if (!is_wp_error($inserted)) {
                    $affected += 1;
                }
            }
            if( $affected ){
                wphr_hr_update_leave_entities();
            }
            $redirect_to = add_query_arg(array('affected' => $affected), $page_url);
            wp_safe_redirect($redirect_to);
            exit;
        }
    }

    /**
     * Submit a new leave request
     *
     * @since 0.1
     *
     * @return void
     */
    public function leave_request() {

        if (!wp_verify_nonce($_POST['_wpnonce'], 'wphr-leave-req-new')) {
            die(__('Something went wrong!', 'wphr'));
        }

        if (!current_user_can('wphr_leave_create_request')) {
            wp_die(__('You do not have sufficient permissions to do this action', 'wphr'));
        }
        $financial_end_date = '';
        $financial_start_date = '';
        $length_hours = 8;
        $from_time = !empty($_POST['from_time']) ? sanitize_text_field( strtotime($_POST['from_time']) ) : 0;
        $to_time = !empty($_POST['to_time']) ? sanitize_text_field( strtotime($_POST['to_time']) ) : 0;

        $employee_id = isset($_POST['employee_id']) ? sanitize_text_field( intval($_POST['employee_id']) ) : 0;
        $leave_policy = isset($_POST['leave_policy']) ? sanitize_text_field( intval($_POST['leave_policy']) ) : 0;

        // @todo: date format may need to be changed when partial leave introduced
        $start_date = isset($_POST['leave_from']) ? sanitize_text_field(convert_to_data_format( $_POST['leave_from'] ) . ' 00:00:00') : date_i18n('Y-m-d 00:00:00');
        $end_date = isset($_POST['leave_to']) ? sanitize_text_field(convert_to_data_format( $_POST['leave_to'] ) . ' 23:59:59') : date_i18n('Y-m-d 23:59:59');

        $date_array = array();
        if (sanitize_text_field($_POST['leave_from']) != sanitize_text_field($_POST['leave_to'])) {
            $day_wise_from_times = custom_sanitize_array( $_POST['day_wise_from_times'] );
            $day_wise_to_times   = custom_sanitize_array( $_POST['day_wise_to_times'] );

            foreach ($day_wise_from_times as $key => $value) {
                unset($day_wise_from_times[$key]);
                $day_wise_from_times[strtotime($key)] = strtotime($value);
            }

            foreach ($day_wise_to_times as $key => $value) {
                unset($day_wise_to_times[$key]);
                $day_wise_to_times[strtotime($key)] = strtotime($value);
            }

            $startFrom = strtotime($start_date);
            $endFrom = strtotime($end_date);
			$finacial_year = wphr_get_financial_year_dates_by_user( $employee_id, $start_date );
            $financial_start_date = date('Y-m-d 00:00:00', strtotime($finacial_year['start']));
            $financial_end_date = date('Y-m-d 23:59:59', strtotime($finacial_year['end']));
            $count = 0;
            $loop_count = 1;
            $inserted = false;
            $request_days_list[] = array($startFrom, $endFrom);
            if ($start_date < $financial_end_date && $end_date > $financial_end_date) {
                $new_end_date = $financial_end_date;
                $new_start_date = date('Y-m-d', strtotime("$financial_end_date +1 Day"));
                $request_days_list = array(
                    array($startFrom, strtotime($new_end_date)),
                    array(strtotime($new_start_date), $endFrom),
                );
            }
            elseif ($start_date < $financial_start_date && $end_date > $financial_start_date) {
                $new_start_date = $financial_start_date;
                $new_end_date = date('Y-m-d', strtotime("$financial_start_date -1 day"));
                
                $request_days_list = array(
                    array($startFrom, strtotime($new_end_date)),
                    array(strtotime($new_start_date), $endFrom),
                );
            }
            //to get array of leave dates between selected range
            foreach ($request_days_list as $key => $_dates) {
                $startFrom = $_dates[0];
                $endFrom = $_dates[1];
                if ($key) {
                    $count++;
                    $loop_count = 1;
                }
                for ($i = $startFrom; $i <= $endFrom; $i = strtotime('+1 day', $i)) {
                    if (!empty($day_wise_from_times[$i]) && !empty($day_wise_to_times)) {
                        $date_array[$count]['from_time'] = date('Y-m-d', $i) . ' ' . date('H:i:s', $day_wise_from_times[$i]);
                        $date_array[$count]['to_time'] = date('Y-m-d', $i) . ' ' . date('H:i:s', $day_wise_to_times[$i]);

                        $difference_in_minutes = ( strtotime($date_array[$count]['to_time']) - strtotime($date_array[$count]['from_time']) ) / 60;

                        $length_hours = ( $difference_in_minutes / 60 );

                        $date_array[$count]['length_hours'] = $length_hours;

                        $count++;
                        $loop_count = 1;
                    } else {
                        if ($loop_count == 1) {

                            $date_array[$count]['from_time'] = date('Y-m-d', $i) . ' 00:00:00';
                            $date_array[$count]['to_time'] = date('Y-m-d', $i) . ' 23:59:59';

                            $difference_in_minutes = ( strtotime($date_array[$count]['to_time']) - strtotime($date_array[$count]['from_time']) ) / 60;
                            $length_hours = ( $difference_in_minutes / 60 ) > 8 ? 8 : ( $difference_in_minutes / 60 );
                            $date_array[$count]['length_hours'] = $length_hours;
                        } else {
                            $count--;
                            $date_array[$count]['to_time'] = date('Y-m-d', $i) . ' 23:59:59';
                            $date_array[$count]['length_hours'] += 8;
                        }
                        $count++;
                        $loop_count++;
                    }
                }
            }
        } else {
            $day_wise_from_times = custom_sanitize_array( $_POST['day_wise_from_times'] );
            $day_wise_to_times   = custom_sanitize_array( $_POST['day_wise_to_times'] );
            /*$from_times_total       = explode(" ", $day_wise_from_times);  
            $to_times_total         = explode(" ", $day_wise_to_times);
            echo '<pre>';
            print_r( $from_times_total);
            print_r( $from_times_total);
            print_r($day_wise_to_times);
            print_r(count($day_wise_to_times));

            die;*/
            if (is_array($day_wise_from_times) && count($day_wise_from_times)) {
                foreach ($day_wise_from_times as $key => $value) {
                    if($value != ''){
                        $from_time = strtotime($value);
                    }
                }
            }
            if (is_array($day_wise_from_times) && count($day_wise_to_times)) {
                foreach ($day_wise_to_times as $key => $value) {
                    if($value != ''){
                        $to_time = strtotime($value);
                    }
                }
            }
        }
        if ($from_time) {
            $start_date = isset($_POST['leave_from']) ? sanitize_text_field( convert_to_data_format( $_POST['leave_from'] ) . ' ' . date('H:i:s', $from_time)) : date_i18n('Y-m-d') . ' ' . date('H:i:s', $from_time);
        }
        if ($to_time) {
            $end_date = isset($_POST['leave_to']) ? sanitize_text_field( convert_to_data_format( $_POST['leave_to'] ) . ' ' . date('H:i:s', $to_time)) : date_i18n('Y-m-d') . ' ' . date('H:i:s', $to_time);
        }
        $leave_reason = isset($_POST['leave_reason']) ? sanitize_text_field($_POST['leave_reason']) : '';
        if ($start_date < $financial_end_date && $end_date > $financial_end_date) {
            $new_end_date = $financial_end_date;
            $new_start_date = date('Y-m-d', strtotime("$financial_end_date +1 day"));
            $is_policy_valid = wphr_hrm_is_valid_leave_duration($start_date, $new_end_date, $leave_policy, $employee_id, true);
            if ($is_policy_valid) {
                $is_policy_valid = wphr_hrm_is_valid_leave_duration($new_start_date, $end_date, $leave_policy, $employee_id, true);
				wphr_hr_add_custom_leave_entitlements( $employee_id, $policy_id, $new_start_date, $end_date );
            }else{
            	wphr_hr_add_custom_leave_entitlements( $employee_id, $policy_id, $start_date, $new_end_date );
            }
        } elseif ($start_date < $financial_start_date && $end_date > $financial_start_date) {
            $new_start_date = $financial_start_date;
            $new_end_date = date('Y-m-d', strtotime("$financial_start_date -1 day"));
            $is_policy_valid = wphr_hrm_is_valid_leave_duration($start_date, $new_end_date, $policy_id, $id);
            if ($is_policy_valid) {
                $is_policy_valid = wphr_hrm_is_valid_leave_duration($new_start_date, $end_date, $policy_id, $id);
				wphr_hr_add_custom_leave_entitlements( $employee_id, $policy_id, $new_start_date, $end_date );
            }else{
            	wphr_hr_add_custom_leave_entitlements( $employee_id, $policy_id, $start_date, $new_end_date );
            }
        } else {
			wphr_hr_add_custom_leave_entitlements( $employee_id, $leave_policy, $start_date, $end_date );
            $is_policy_valid = wphr_hrm_is_valid_leave_duration($start_date, $end_date, $leave_policy, $employee_id, true);
        }
        if (!$is_policy_valid) {
            wp_die(__('Sorry! You do not have any leave left under this leave policy', 'wphr'));
        }

        if ($to_time != 0 && $from_time != 0) {
            $difference_in_minutes = ( $to_time - $from_time ) / 60;
            $length_hours = ( $difference_in_minutes / 60 ) > 8 ? 8 : ( $difference_in_minutes / 60 );
        }

        //insert new leaves
        $request_success = false;
        $request_count = 0;
		if (sizeof($date_array) > 1) {
            $inserted = true;
            foreach ($date_array as $key => $value) {
				wphr_hr_add_custom_leave_entitlements( $employee_id, $leave_policy, $value['from_time'], $value['to_time'] );
				
                $request_id[$request_count] = wphr_hr_leave_insert_request(array(
                    'user_id' => $employee_id,
                    'leave_policy' => $leave_policy,
                    'start_date' => $value['from_time'],
                    'end_date' => $value['to_time'],
                    'reason' => $leave_reason,
                    'length_hours' => $value['length_hours'],
                    'start_time' => date('H:i:s', $value['from_time']),
                    'end_time' => date('H:i:s', $value['to_time'])
                ));


                if (!is_wp_error($request_id[$request_count]) && $request_id[$request_count] != FALSE) {
                    $request_success = true;
                    // notification email
                    $emailer = wphr()->emailer->get_email('New_Leave_Request');

                    if (is_a($emailer, '\WPHR\HR_MANAGER\Email')) {
                        $emailer->trigger($request_id[$request_count]);
                    }
                }
                $request_count++;
            }

            if ($request_success == true) {
                $redirect_to = admin_url('admin.php?page=wphr-leave&view=new&msg=submitted');
            } else {
                $redirect_to = admin_url('admin.php?page=wphr-leave&view=new&msg=error');
            }
        } else {
			wphr_hr_add_custom_leave_entitlements( $employee_id, $leave_policy, $start_date, $end_date );
			
            $insert = wphr_hr_leave_insert_request(array(
                'user_id' => $employee_id,
                'leave_policy' => $leave_policy,
                'start_date' => $start_date,
                'end_date' => $end_date,
                'reason' => $leave_reason,
                'length_hours' => $length_hours,
                'start_time' => date('H:i:s', $from_time),
                'end_time' => date('H:i:s', $to_time)
            ));

            if (!is_wp_error($insert) && $insert != false) {
                $redirect_to = admin_url('admin.php?page=wphr-leave&view=new&msg=submitted');
            } else {
                if ($insert == false) {
                    $redirect_to = admin_url('admin.php?page=wphr-leave&view=new&msg=leave_exist');
                } else {
                    $redirect_to = admin_url('admin.php?page=wphr-leave&view=new&msg=error');
                }
            }
        }

        wp_redirect($redirect_to);
        exit;
    }

    /**
     * Leave Request Status change
     *
     * @since 0.1
     *
     * @return void
     */
    public function leave_request_status_change() {

        // If not leave bulk action then go out from here
        if (!isset($_GET['leave_action'])) {
            return;
        }

        // Verify the nonce validation
        if (!wp_verify_nonce($_GET['_wpnonce'], 'wphr-hr-leave-req-nonce')) {
            return;
        }

        // Check permission if not have then bell out :)
        if (!current_user_can('wphr_leave_manage')) {
            wp_die(__('You do not have sufficient permissions to do this action', 'wphr'));
        }

        $action = sanitize_text_field($_GET['leave_action']);
        $stauses = array(
            'delete',
            'reject',
            'approve',
            'pending',
            'archive',
            'unarchive',
        );

        if (!in_array($action, $stauses)) {
            return;
        }

        if (empty($_GET['id'])) {
            return;
        }

        $request_id = sanitize_text_field( absint($_GET['id']) );
        $status = null;
        $is_archive = 0;
        
        switch ($action) {
            case 'delete':
                \WPHR\HR_MANAGER\HRM\Models\Leave_request::find( sanitize_text_field( $_GET['id']) )->delete();
                break;

            case 'reject':
                $status = 3;
                break;

            case 'approve':
                $status = 1;
                break;

            case 'pending':
                $status = 2;
                break;

            case 'archive':
                $status = sanitize_text_field($_GET['alllist']) ? 'all' : 1;
                wphr_hr_archive_unarchive_approved_leaves($request_id);
                break;

            case 'unarchive':
                $status = sanitize_text_field($_GET['alllist']) ? 'all' : 4;
                wphr_hr_archive_unarchive_approved_leaves($request_id, 0);
                break;
        }

        if (null !== $status && $status != 4 && $status != 'all') {
            wphr_hr_leave_request_update_status($request_id, $status);
        }

        // redirect the user back
        $redirect_to = remove_query_arg(array('status'), admin_url('admin.php?page=wphr-leave'));
        $redirect_to = add_query_arg(array('status' => $status), $redirect_to);

        wp_redirect($redirect_to);
        exit;
    }

    /**
     * Employee Status Update
     *
     * @since 0.1
     *
     * @return void
     */
    public function handle_employee_status_update() {
        // If not submit this form then return
        if (!isset($_POST['employee_status'])) {
            return;
        }

        // Nonce validaion
        if (!wp_verify_nonce($_POST['_wpnonce'], 'wp-wphr-hr-employee-update-nonce')) {
            return;
        }

        // Check permission
        if (!current_user_can(wphr_hr_get_manager_role())) {
            wp_die(__('You do not have sufficient permissions to do this action', 'wphr'));
        }

        if ( sanitize_text_field( $_POST['employee_status'] ) == 'terminated') {
            \WPHR\HR_MANAGER\HRM\Models\Employee::where('user_id', '=', sanitize_text_field( $_POST['user_id'] ) )->update(['status' => sanitize_text_field( $_POST['employee_status'] ), 'termination_date' => current_time('mysql')]);
        } else {
            \WPHR\HR_MANAGER\HRM\Models\Employee::where('user_id', '=', sanitize_text_field( $_POST['user_id'] ) )->update(['status' => sanitize_text_field( $_POST['employee_status'] ), 'termination_date' => '']);
        }

        wp_redirect($_POST['_wp_http_referer']);
        exit();
    }

     /**
     * Employee Permission Management
     *
     * @since 0.1
     *
     * @return void
     */
    public function employee_permission() {
       
       

        if (!wp_verify_nonce( sanitize_text_field( $_POST['_wpnonce'] ), 'wp-wphr-hr-employee-permission-nonce')) {
            return;
        }
        global $wpdb;
        $hr_manager_role = wphr_hr_get_manager_role();

        if (!current_user_can($hr_manager_role)) {
            wp_die(__('Permission Denied!', 'wphr'));
        }

        $employee_id = isset($_POST['employee_id']) ? sanitize_text_field( absint($_POST['employee_id']) ) : 0;
        ?>
        

        <?php
        $enable_manager = isset($_POST['enable_manager']) ? filter_var($_POST['enable_manager'], FILTER_VALIDATE_BOOLEAN) : false;
        $receive_mail_for_leaves = isset($_POST['receive_mail_for_leaves']) ? filter_var($_POST['receive_mail_for_leaves'], FILTER_VALIDATE_BOOLEAN) : 0;
        $manage_leave_of_employees = isset($_POST['manage_leave_of_employees']) ? filter_var($_POST['manage_leave_of_employees'], FILTER_VALIDATE_BOOLEAN) : 0;
        $enable_profile_redirect = isset($_POST['enable_profile_redirect']) ? filter_var($_POST['enable_profile_redirect'], FILTER_VALIDATE_BOOLEAN) : 0;
        $enable_profile_redirect = isset($_POST['enable_profile_redirect']) ? filter_var($_POST['enable_profile_redirect'], FILTER_VALIDATE_BOOLEAN) : 0;
        $additional = serialize($_POST['additional']);

    
        if(isset($_POST['additional']))
        {
     update_user_meta($employee_id, 'additional', $additional);
        }

        $additional = serialize($_POST['additional']);
        update_user_meta($employee_id, 'enable_profile_redirect', $enable_profile_redirect);


        $user = get_user_by('id', $employee_id);

        if ($enable_manager && !user_can($user, $hr_manager_role)) {

            $user->add_role($hr_manager_role);
        } else if (!$enable_manager && user_can($user, $hr_manager_role)) {

            $user->remove_role($hr_manager_role);
        }
        if ($enable_manager) {
            update_user_meta($employee_id, 'receive_mail_for_leaves', $receive_mail_for_leaves);
            update_user_meta($employee_id, 'manage_leave_of_employees', $manage_leave_of_employees);
        }

        /**
        * Update the emp leave year
        */
        $apply_leave_year =  isset($_POST['emp_apply_leave_year']) ? 'on' : '';
        $current_apply_leave_year =  isset($_POST['current_emp_apply_leave_year']) ? 'on' : '';
        $employee_table_data = [
            'leave_year' => sanitize_text_field($_POST['emp_leave_year']),
            'apply_leave_year' =>  isset($_POST['emp_apply_leave_year']) ? 'on' : '',
        ];
        $employee_row_id = $wpdb->get_var( $wpdb->prepare( 'SELECT id FROM '.$wpdb->prefix . 'wphr_hr_employees WHERE user_id = %d', $employee_id ));
        if( $employee_row_id ){
            $wpdb->update( $wpdb->prefix . 'wphr_hr_employees', $employee_table_data, array( 'user_id' => $employee_id ) );
        }else{
            $employee_table_data['user_id'] = $employee_id;
            $wpdb->insert( $wpdb->prefix . 'wphr_hr_employees', $employee_table_data );
        }
        if( $apply_leave_year != $current_apply_leave_year || sanitize_text_field($_POST['current_emp_leave_year']) != sanitize_text_field($_POST['emp_leave_year']) ){
            wphr_hr_update_leave_entities( $employee_id );
        }

        do_action('wphr_hr_after_employee_permission_set', $_POST, $user);
    }

    /**
     * Reporting Headcount Form Submit Handler
     *
     * @since 0.1
     *
     * @return void
     */
    public function reporting_headcount_bulk_action() {

        if (isset($_REQUEST['filter_headcount'])) {

            if (!$this->verify_current_page_screen('wphr-hr-reporting', 'epr-rep-headcount')) {
                return;
            }

            $redirect = remove_query_arg(array('_wp_http_referer', '_wpnonce', 'filter_headcount'), wp_unslash($_SERVER['REQUEST_URI']));

            wp_redirect($redirect);
        }
    }

}

new Form_Handler();
