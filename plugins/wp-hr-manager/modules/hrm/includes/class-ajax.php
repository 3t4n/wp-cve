<?php

namespace WPHR\HR_MANAGER\HRM;

use WPHR\HR_MANAGER\Framework\Traits\Ajax;
use WPHR\HR_MANAGER\Framework\Traits\Hooker;
use WPHR\HR_MANAGER\HRM\Models\Dependents;
use WPHR\HR_MANAGER\HRM\Models\Education;
use WPHR\HR_MANAGER\HRM\Models\Work_Experience;

/**  
 * Ajax handler
 *
 * @package WP-wphr
 */
class Ajax_Handler {

    use Ajax;
    use Hooker;

    /**
     * Bind all the ajax event for HRM
     *
     * @since 0.1
     *
     * @return void
     */
    public function __construct() {

        // Department
        $this->action('wp_ajax_wphr-hr-new-dept', 'department_create');
        $this->action('wp_ajax_wphr-hr-del-dept', 'department_delete');
        $this->action('wp_ajax_wphr-hr-get-dept', 'department_get');
        $this->action('wp_ajax_wphr-hr-update-dept', 'department_create');

        // Role
        $this->action('wp_ajax_wphr-hr-new-desig', 'designation_create');
        $this->action('wp_ajax_wphr-hr-get-desig', 'designation_get');
        $this->action('wp_ajax_wphr-hr-update-desig', 'designation_create');
        $this->action('wp_ajax_wphr-hr-del-desig', 'designation_delete');

        // Employee
        $this->action('wp_ajax_wphr-hr-employee-new', 'employee_create');
        $this->action('wp_ajax_wphr-hr-employee-resend-email', 'employee_resend_email');
        $this->action('wp_ajax_wphr-hr-emp-get', 'employee_get');
        $this->action('wp_ajax_wphr-hr-emp-delete', 'employee_remove');
        $this->action('wp_ajax_wphr-hr-emp-restore', 'employee_restore');
        $this->action('wp_ajax_wphr-hr-emp-update-status', 'employee_update_employment');
        $this->action('wp_ajax_wphr-hr-emp-update-comp', 'employee_update_compensation');
        $this->action('wp_ajax_wphr-hr-emp-delete-history', 'employee_remove_history');
        $this->action('wp_ajax_wphr-hr-emp-update-jobinfo', 'employee_update_job_info');
        $this->action('wp_ajax_wphr-hr-empl-leave-history', 'get_employee_leave_history');
        $this->action('wp_ajax_wphr-hr-employee-new-note', 'employee_add_note');
         $this->action('wp_ajax_wphr-hr-emp-update-notes', 'employee_update_notes');
        $this->action('wp_ajax_wphr-load-more-notes', 'employee_load_note');
        $this->action('wp_ajax_wphr-delete-employee-note', 'employee_delete_note');
        $this->action('wp_ajax_wphr-hr-emp-update-terminate-reason', 'employee_terminate');
        $this->action('wp_ajax_wphr-hr-emp-activate', 'employee_termination_reactive');
        $this->action('wp_ajax_wphr-hr-convert-wp-to-employee', 'employee_create_from_wp_user');
        $this->action('wp_ajax_wphr_hr_check_user_exist', 'check_user');

        // Dashaboard 
        $this->action('wp_ajax_wphr_hr_announcement_mark_read', 'mark_read_announcement');
        $this->action('wp_ajax_wphr_hr_announcement_view', 'view_announcement');

        // Performance
        $this->action('wp_ajax_wphr-hr-emp-update-performance-reviews', 'employee_update_performance');
        $this->action('wp_ajax_wphr-hr-emp-update-performance-comments', 'employee_update_performance');
        $this->action('wp_ajax_wphr-hr-emp-update-performance-goals', 'employee_update_performance');
        $this->action('wp_ajax_wphr-hr-emp-delete-performance', 'employee_delete_performance');

        // work experience
        $this->action('wp_ajax_wphr-hr-create-work-exp', 'employee_work_experience_create');
        $this->action('wp_ajax_wphr-hr-emp-delete-exp', 'employee_work_experience_delete');

        // education
        $this->action('wp_ajax_wphr-hr-create-education', 'employee_education_create');
        $this->action('wp_ajax_wphr-hr-emp-delete-education', 'employee_education_delete');

        // dependents
        $this->action('wp_ajax_wphr-hr-create-dependent', 'employee_dependent_create');
        $this->action('wp_ajax_wphr-hr-emp-delete-dependent', 'employee_dependent_delete');

        // leave policy
        $this->action('wp_ajax_wphr-hr-leave-policy-create', 'leave_policy_create');
        $this->action('wp_ajax_wphr-hr-leave-policy-delete', 'leave_policy_delete');
        $this->action('wp_ajax_wphr-hr-leave-request-req-date', 'leave_request_dates');
        // check valid time range ( By Taslim )
        $this->action('wp_ajax_wphr-hr-leave-request-req-time', 'leave_request_times');
        $this->action('wp_ajax_wphr-hr-leave-employee-assign-policies', 'leave_assign_employee_policy');
        $this->action('wp_ajax_wphr-hr-leave-policies-availablity', 'leave_available_days');
        $this->action('wp_ajax_wphr-hr-leave-req-new', 'leave_request');

        //leave holiday
        $this->action('wp_ajax_wphr_hr_holiday_create', 'holiday_create');
        $this->action('wp_ajax_wphr-hr-get-holiday', 'get_holiday');
        $this->action('wp_ajax_wphr-hr-import-ical', 'import_ical');

        //leave hliday by location ( By Taslim )
        $this->action('wp_ajax_wphr-hr-get-holiday-by-location', 'get_holidayByLocation');

        //leave entitlement
        $this->action('wp_ajax_wphr-hr-leave-entitlement-delete', 'remove_entitlement');

        //leave rejected
        $this->action('wp_ajax_wphr_hr_leave_reject', 'leave_reject');

        // script reload
        $this->action('wp_ajax_wphr_hr_script_reload', 'employee_template_refresh');
        $this->action('wp_ajax_wphr_hr_new_dept_tmp_reload', 'new_dept_tmp_reload');
        $this->action('wp_ajax_wphr-hr-holiday-delete', 'holiday_remove');
    }

    function leave_reject() {
        $this->verify_nonce('wp-wphr-hr-nonce');

        // Check permission
        if (!current_user_can('wphr_leave_manage')) {
            $this->send_error(__('You do not have sufficient permissions to do this action', 'wphr'));
        }

        $request_id = isset($_POST['leave_request_id']) ? intval(sanitize_text_field( $_POST['leave_request_id']) ) : 0;
        $comments = isset($_POST['reason']) ? sanitize_text_field($_POST['reason']) : '';

        global $wpdb;
        $update = $wpdb->update($wpdb->prefix . 'wphr_hr_leave_requests', array('comments' => $comments), array('id' => $request_id)
        );
        wphr_hr_leave_request_update_status($request_id, 3);

        if ($update) {
            $this->send_success();
        }
    }

    /**
     * Remove Holiday
     *
     * @since 0.1
     *
     * @return json
     */
    function holiday_remove() {
        $this->verify_nonce('wp-wphr-hr-nonce');

        // Check permission
        if (!current_user_can('wphr_leave_manage')) {
            $this->send_error(__('You do not have sufficient permissions to do this action', 'wphr'));
        }

        $holiday = wphr_hr_delete_holidays(array('id' => intval( sanitize_text_field($_POST['id']))));
        $this->send_success();
    }

    /**
     * Get Holiday
     *
     * @since 0.1
     *
     * @return json
     */
    function get_holiday() {
        $this->verify_nonce('wp-wphr-hr-nonce');

        $holiday = wphr_hr_get_holidays([
            'id' => sanitize_text_field( absint($_POST['id']) ),
            'number' => -1
        ]);

        // Get holiday by Location
        if (empty($holiday)) {
            $holiday = wphr_hr_get_holidaysByLocation([
                'id' => sanitize_text_field( absint($_POST['id']) ),
                'number' => -1
            ]);
        }

        $holiday = (array) reset($holiday);
        $holiday['end'] = wphr_format_date( date('Y-m-d', strtotime($holiday['end'] . '-1day')) );
        $holiday['start'] = wphr_format_date( date('Y-m-d', strtotime($holiday['start'])) );

        $this->send_success(array('holiday' => $holiday));
    }

    /**
     * Get Holidays By Location ( By Taslim )
     *
     * @since 0.1
     *
     * @return json
     */
    function get_holidayByLocation() {
        $this->verify_nonce('wp-wphr-hr-nonce');

        $holiday = wphr_hr_get_holidaysByLocation([
            'id' => sanitize_text_field( absint($_POST['id']) ),
            'number' => -1
        ]);

        $holiday = (array) reset($holiday);
        $holiday['end'] = date('Y-m-d', strtotime($holiday['end'] . '-1day'));
        $holiday['start'] = date('Y-m-d', strtotime($holiday['start']));

        $this->send_success(array('holiday' => $holiday));
    }

    /**
     * Import ICal files
     *
     * @since 0.1
     *
     * @return json
     */
    function import_ical() {
        $this->verify_nonce('wp-wphr-hr-nonce');

        if (isset($_POST['location_id']) && sanitize_text_field($_POST['location_id']) != 0 && !empty($_POST['location_id'])) {
            $location_id = sanitize_text_field($_POST['location_id']);
        } else {
            $location_id = 0;
        }

        if (empty($_FILES['ics']['tmp_name'])) {
            $this->send_error(__('File upload error!', 'wphr'));
        }

        /*
         * An iCal may contain events from previous and future years.
         * We'll import only events from current year
         */
        $first_day_of_year = strtotime(date('Y-01-01 00:00:00'));
        $last_day_of_year = strtotime(date('Y-12-31 23:59:59'));

        /*
         * We'll ignore duplicate entries with the same title and
         * start date in the foreach loop when inserting an entry
         */
        $holiday_model = new \WPHR\HR_MANAGER\HRM\Models\Leave_Holiday();

        // create the ical parser object
        $ical = new \ICal( sanitize_text_field( $_FILES['ics']['tmp_name'] ) );
        $events = $ical->events();

        foreach ($events as $event) {
            $start = strtotime($event['DTSTART']);
            $end = strtotime($event['DTEND']);

            if (( $start >= $first_day_of_year ) && ( $end <= $last_day_of_year )) {

                $title = sanitize_text_field($event['SUMMARY']);
                $start = date('Y-m-d H:i:s', $start);
                $end = date('Y-m-d H:i:s', $end);
                $description = (!empty($event['DESCRIPTION']) ) ? $event['DESCRIPTION'] : $event['SUMMARY'];


                // check for duplicate entries
                $holiday = $holiday_model->where('title', '=', $title)
                        ->where('start', '=', $start)
                        ->where('location_id', '=', $location_id);

                // insert only unique one
                if (!$holiday->count()) {
                    wphr_hr_leave_insert_holiday(array(
                        'id' => 0,
                        'title' => $title,
                        'start' => $start,
                        'end' => $end,
                        'description' => sanitize_text_field($description),
                        'location_id' => $location_id
                    ));
                }
            }
        }

        $this->send_success();
    }

    /**
     * Remove entitlement
     *
     * @since 0.1
     *
     * @return json
     */
    public function remove_entitlement() {
        $this->verify_nonce('wp-wphr-hr-nonce');

        // Check permission
        if (!current_user_can('wphr_leave_manage')) {
            $this->send_error(__('You do not have sufficient permissions to do this action', 'wphr'));
        }

        $id = isset($_POST['id']) ? intval(sanitize_text_field($_POST['id']) ) : 0;
        $user_id = isset($_POST['user_id']) ? intval(sanitize_text_field($_POST['user_id'] ) ) : 0;
        $policy_id = isset($_POST['policy_id']) ? intval(sanitize_text_field($_POST['policy_id']) ) : 0;

        if ($id && $user_id && $policy_id) {
            wphr_hr_delete_entitlement($id, $user_id, $policy_id);
            $this->send_success();
        } else {
            $this->send_error(__('Somthing wrong !', 'wphr'));
        }
    }

    /**
     * Get employee template
     *
     * @since 0.1
     *
     * @return void
     */
    public function employee_template_refresh() {
        ob_start();
        include WPHR_HRM_JS_TMPL . '/new-employee.php';
        $this->send_success(array('content' => ob_get_clean()));
    }

    /**
     * Get department template
     *
     * @since 0.1
     *
     * @return void
     */
    public function new_dept_tmp_reload() {
        ob_start();
        include WPHR_HRM_JS_TMPL . '/new-dept.php';
        $this->send_success(array('content' => ob_get_clean()));
    }

    /**
     * Get a department
     *
     * @since 0.1
     *
     * @return void
     */
    public function department_get() {
        $this->verify_nonce('wp-wphr-hr-nonce');

        $id = isset($_POST['id']) ? intval( sanitize_text_field( $_POST['id'] ) ) : 0;

        if ($id) {
            $department = new Department($id);
            $this->send_success($department);
        }

        $this->send_success(__('Something went wrong!', 'wphr'));
    }

    /**
     * Create a new department
     *
     * @since 0.1
     *
     * @return void
     */
    public function department_create() {
        $this->verify_nonce('wphr-new-dept');

        //check permission
        if (!current_user_can('wphr_manage_department')) {
            $this->send_error(__('You do not have sufficient permissions to do this action', 'wphr'));
        }

        $title = isset($_POST['title']) ? sanitize_text_field( $_POST['title'] ) : '';
        $emp_profile_label = isset($_POST['emp_profile_label']) ? sanitize_text_field( $_POST['emp_profile_label'] ) : '';
        $desc = isset($_POST['dept-desc']) ? sanitize_text_field( $_POST['dept-desc'] ) : '';
        $dept_id = isset($_POST['dept_id']) ? intval( sanitize_text_field( $_POST['dept_id']) ) : 0;
        $lead = isset($_POST['lead']) ? intval( sanitize_text_field($_POST['lead']) ) : 0;
        $parent = isset($_POST['parent']) ? intval( sanitize_text_field ($_POST['parent']) ): 0;

        // on update, ensure $parent != $dept_id
        if ($dept_id == $parent) {
            $parent = 0;
        }

        $dept_id = wphr_hr_create_department(array(
            'id' => $dept_id,
            'title' => $title,
            'employee_label'  =>  $emp_profile_label,
            'description' => $desc,
            'lead' => $lead,
            'parent' => $parent
        ));

        if (is_wp_error($dept_id)) {
            $this->send_error($dept_id->get_error_message());
        }

        $this->send_success(array(
            'id' => $dept_id,
            'title' => $title,
            'lead' => $lead,
            'parent' => $parent,
            'employee' => 0
        ));
    }

    /**
     * Delete a department
     *
     * @return void
     */
    public function department_delete() {
        $this->verify_nonce('wp-wphr-hr-nonce');

        //check permission
        if (!current_user_can('wphr_manage_department')) {
            $this->send_error(__('You do not have sufficient permissions to do this action', 'wphr'));
        }

        $id = isset($_POST['id']) ? intval( sanitize_text_field( $_POST['id']) ) : 0;
        if ($id) {
            $deleted = wphr_hr_delete_department($id);

            if (is_wp_error($deleted)) {
                $this->send_error($deleted->get_error_message());
            }

            $this->send_success(__('Department has been deleted', 'wphr'));
        }

        $this->send_error(__('Something went worng!', 'wphr'));
    }

    /**
     * Create a new designnation
     *
     * @return void
     */
    function designation_create() {
        $this->verify_nonce('wphr-new-desig');

        //check permission
        if (!current_user_can('wphr_manage_designation')) {
            $this->send_error(__('You do not have sufficient permissions to do this action', 'wphr'));
        }

        $title = isset($_POST['title']) ? sanitize_text_field( $_POST['title'] ) : '';
        $desc = isset($_POST['desig-desc']) ? sanitize_text_field( $_POST['desig-desc'] ) : '';
        $desig_id = isset($_POST['desig_id']) ? intval(sanitize_text_field($_POST['desig_id']) ) : 0;

        $desig_id = wphr_hr_create_designation(array(
            'id' => $desig_id,
            'title' => $title,
            'description' => $desc
        ));

        if (is_wp_error($desig_id)) {
            $this->send_error($desig_id->get_error_message());
        }

        $this->send_success(array(
            'id' => $desig_id,
            'title' => $title,
            'employee' => 0
        ));
    }

    /**
     * Get a department
     *
     * @return void
     */
    public function designation_get() {
        $this->verify_nonce('wp-wphr-hr-nonce');

        $id = isset($_POST['id']) ? intval(sanitize_text_field($_POST['id']) ) : 0;

        if ($id) {
            $designation = new Designation($id);
            $this->send_success($designation);
        }

        $this->send_error(__('Something went wrong!', 'wphr'));
    }

    /**
     * Delete a department
     *
     * @return void
     */
    public function designation_delete() {
        $this->verify_nonce('wp-wphr-hr-nonce');

        //check permission
        if (!current_user_can('wphr_manage_designation')) {
            $this->send_error(__('You do not have sufficient permissions to do this action', 'wphr'));
        }

        $id = isset($_POST['id']) ? intval(sanitize_text_field($_POST['id']) ) : 0;
        if ($id) {
            // @TODO: check permission
            $deleted = wphr_hr_delete_designation($id);

            if (is_wp_error($deleted)) {
                $this->send_error($deleted->get_error_message());
            }

            $this->send_success(__('Role has been deleted', 'wphr'));
        }

        $this->send_error(__('Something went wrong!', 'wphr'));
    }

   /**
     * Create/update an employee
     *
     * @return void
     */
    public function employee_create() {

        $this->verify_nonce('wp-wphr-hr-employee-nonce');

        unset($_POST['_wp_http_referer']);
        unset($_POST['_wpnonce']);
        unset($_POST['action']);

        //$posted = array_map('sanitize_text_field', $_POST);
        $posted = custom_sanitize_array($_POST);

        $posted['type'] = 'customer';

        // Check permission for editing and adding new employee
        if (isset($posted['user_id']) && $posted['user_id']) {
            if (!current_user_can('wphr_edit_employee', $posted['user_id'])) {
                $this->send_error(__('You do not have sufficient permissions to do this action', 'wphr'));
            }
        } else {
            if (!current_user_can('wphr_create_employee')) {
                $this->send_error(__('You do not have sufficient permissions to do this action', 'wphr'));
            }
        }

        $employee_id = wphr_hr_employee_create($posted);

        if (is_wp_error($employee_id)) {
            $this->send_error($employee_id->get_error_message());
        }

        // we cached empty employee data right after creating, calling from wphr_hr_employee_create method
        wp_cache_delete('wphr-empl-' . $employee_id, 'wphr');

        $employee = new Employee($employee_id);
        $data = $employee->to_array();

        $data['work']['joined'] = $employee->get_joined_date();
        $data['work']['type'] = $employee->get_type();
        $data['url'] = $employee->get_details_url();

        // user notification email
        if (isset($posted['user_notification']) && $posted['user_notification'] == 'on') {
            $emailer = wphr()->emailer->get_email('New_Employee_Welcome');
            $send_login = isset($posted['login_info']) ? true : false;

            if (is_a($emailer, '\WPHR\HR_MANAGER\Email')) {
                $emailer->trigger($employee_id, $send_login);
            }
        }

        $this->send_success($data);
    }

    /**
     * Get an employee for ajax
     *
     * @return void
     */
    public function employee_get() {
        $this->verify_nonce('wp-wphr-hr-nonce');

        $employee_id = isset($_REQUEST['id']) ? intval(sanitize_text_field($_REQUEST['id']) ) : 0;
        $user = get_user_by('id', $employee_id);

        if (!$user) {
            $this->send_error(__('Employee does not exists.', 'wphr'));
        }

        $employee = new Employee($user);
        $this->send_success($employee->to_array());
    }

    /**
     * Remove an employee from the company
     *
     * @return void
     */
    public function employee_remove() {
        global $wpdb;

        $this->verify_nonce('wp-wphr-hr-nonce');

        // Check permission
        if (!current_user_can('wphr_delete_employee')) {
            $this->send_error(__('You do not have sufficient permissions to do this action', 'wphr'));
        }

        $employee_id = isset($_REQUEST['id']) ?  intval(sanitize_text_field($_REQUEST['id']) ) : 0;
        $hard = isset($_REQUEST['hard']) ?  intval(sanitize_text_field($_REQUEST['hard']) ) : 0;
        $user = get_user_by('id', $employee_id);

        if (!$user) {
            $this->send_error(__('No employee found', 'wphr'));
        }

        if (in_array('employee', $user->roles)) {
            $hard = apply_filters('wphr_employee_delete_hard', $hard);
            wphr_employee_delete($employee_id, $hard);
        }

        $this->send_success(__('Employee has been removed successfully', 'wphr'));
    }

    /**
     * Restore an employee from the company
     *
     * @since 1.1.1
     *
     * @return void
     */
    public function employee_restore() {
        $this->verify_nonce('wp-wphr-hr-nonce');

        global $wpdb;

        $employee_id = isset($_REQUEST['id']) ?  intval(sanitize_text_field($_REQUEST['id']) ) : 0;
        $user = get_user_by('id', $employee_id);

        if (!$user) {
            $this->send_error(__('No employee found', 'wphr'));
        }

        if (in_array('employee', $user->roles)) {
            wphr_employee_restore($employee_id);
        }

        $this->send_success(__('Employee has been restore successfully', 'wphr'));
    }

    /**
     * Update employment status
     *
     * @return void
     */
    public function employee_update_employment() {
        $this->verify_nonce('employee_update_employment');

        $employee_id = isset($_REQUEST['employee_id']) ? intval(sanitize_text_field( $_REQUEST['employee_id']) ) : 0;

        // Check permission
        if (!current_user_can('wphr_edit_employee', $employee_id)) {
            $this->send_error(__('You do not have sufficient permissions to do this action', 'wphr'));
        }

        $date = ( empty($_POST['date']) ) ? current_time('mysql') : sanitize_text_field($_POST['date']);
        $comment = sanitize_text_field($_POST['comment']);
        $status = sanitize_text_field($_POST['status']);
        $types = wphr_hr_get_employee_types();
        $additional = serialize($_POST['additional']);
    
        if (!array_key_exists($status, $types)) {
            $this->send_error(__('Status error', 'wphr'));
        }
        $employee = new Employee($employee_id);

        if ($employee->id) {
            do_action('wphr_hr_employee_employment_status_create', $employee->id);
            $employee->update_employment_status($status, convert_to_data_format( $date ), $comment, $additional);
            $this->send_success();
        }
        $this->send_error(__('Something went wrong!', 'wphr'));
    }

     /**
     * Update employee compensation
     *
     * @return void
     */
    public function employee_update_compensation() {
        $this->verify_nonce('employee_update_compensation');

        $employee_id = isset($_REQUEST['employee_id']) ? intval(sanitize_text_field( $_REQUEST['employee_id']) ) : 0;

        // Check permission
        if (!current_user_can('wphr_edit_employee', $employee_id)) {
            $this->send_error(__('You do not have sufficient permissions to do this action', 'wphr'));
        }

        $date = ( empty($_POST['date']) ) ? current_time('mysql') : sanitize_text_field($_POST['date']);
        $comment = sanitize_text_field($_POST['comment']);
        $pay_rate = number_format(sanitize_text_field( $_POST['pay_rate'] ), 2);
        $pay_type = sanitize_text_field($_POST['pay_type']);
        $reason = sanitize_text_field($_POST['change-reason']);
         $additional = serialize($_POST['additional']); 
        $types = wphr_hr_get_pay_type();
        $reasons = wphr_hr_get_pay_change_reasons();

        if (!$pay_rate) {
            $this->send_error(__('Enter a valid pay rate.', 'wphr'));
        }

        if (!array_key_exists($pay_type, $types)) {
            $this->send_error(__('Pay Type does not exists.', 'wphr'));
        }

        if (!array_key_exists($reason, $reasons)) {
            $this->send_error(__('Reason does not exists.', 'wphr'));
        }

        $employee = new Employee($employee_id);

        if ($employee->id) {
            do_action('wphr_hr_employee_compensation_create', $employee->id);
            $employee->update_compensation($pay_rate, $pay_type, $reason, convert_to_data_format( $date ), $comment,$additional);
            $this->send_success();
        }

        $this->send_error(__('Something went wrong!', 'wphr'));
    }

    /**
     * Remove an history
     *
     * @return void
     */
    public function employee_remove_history() {
        global $wpdb;

        $this->verify_nonce('wp-wphr-hr-nonce');

        $id = isset($_POST['id']) ? intval(sanitize_text_field($_POST['id']) ) : 0;
        $query = $wpdb->prepare( "SELECT module, user_id FROM {$wpdb->prefix}wphr_hr_employee_history WHERE id = %d", $id);
        $get_module = $wpdb->get_row($query);

        // Check permission
        if (!current_user_can('wphr_edit_employee', $get_module->user_id)) {
            $this->send_error(__('You do not have sufficient permissions to do this action', 'wphr'));
        }

        if ($get_module->module == 'employment') {
            do_action('wphr_hr_employee_employment_status_delete', $id);
        } elseif ($get_module->module == 'compensation') {
            do_action('wphr_hr_employee_compensation_delete', $id);
        } elseif ($get_module->module == 'job') {
            do_action('wphr_hr_employee_job_info_delete', $id);
        }

        wphr_hr_employee_remove_history($id);

        $this->send_success();
    }

      /**
     * Update job information
     *
     * @return void
     */
    public function employee_update_job_info() {
        $this->verify_nonce('employee_update_jobinfo');

        $employee_id = isset($_POST['employee_id']) ? intval(sanitize_text_field( $_POST['employee_id']) ) : 0;

        $location = isset($_POST['location']) ? intval(sanitize_text_field($_POST['location']) ) : 0;
        $department = isset($_POST['department']) ?  intval(sanitize_text_field($_POST['department']) ) : 0;
        $designation = isset($_POST['designation']) ?  intval(sanitize_text_field($_POST['designation']) ) : 0;
        $reporting_to = isset($_POST['reporting_to']) ?  intval(sanitize_text_field($_POST['reporting_to']) ) : 0;
        $date = ( empty($_POST['date']) ) ? current_time('mysql') : sanitize_text_field( $_POST['date'] );
        $additional=serialize($_POST['additional']);
        $employee = new Employee($employee_id);
        if ($employee->id) {
            // Check permission
            if (!current_user_can('wphr_edit_employee', $employee->id)) {
                $this->send_error(__('You do not have sufficient permissions to do this action', 'wphr'));
            }

            do_action('wphr_hr_employee_job_info_create', $employee->id);
            $employee->update_job_info($department, $designation, $reporting_to, $location, convert_to_data_format( $date ),$additional );
            $this->send_success();
        }

        $this->send_error(__('Something went wrong!', 'wphr'));
    }


 /**
     * Add a extra Field in note
     *
     * @return void
     */
    public function employee_update_notes() {
        $this->verify_nonce('wp-wphr-hr-employee-nonce');
        $employee_id = isset($_POST['employee_id']) ? intval(sanitize_text_field($_POST['employee_id']) ) : 0;
        // $note = isset($_POST['note']) ? sanitize_text_field( $_POST['note'] ) : 0;
        // $note_by = get_current_user_id();
        // $additional=isset($_POST['additional']) ? sanitize_text_field( $_POST['additional'] ) :0;
        $additional=serialize($_POST['additional']);
        $employee = new Employee($employee_id);
        print_r($additional);

        if ($employee->id) {
            // Check permission
            if (!current_user_can('wphr_edit_employee', $employee_id)) {
                $this->send_error(__('You do not have sufficient permissions to do this action', 'wphr'));
            }

            $employee->add_note_field($note_by,$additional);
        }

        $this->send_success();
    }







    /**
     * Add a new note
     *
     * @return void
     */
    public function employee_add_note() {  
         
        $this->verify_nonce('wp-wphr-hr-employee-nonce');

        $employee_id = isset($_POST['user_id']) ? intval(sanitize_text_field($_POST['user_id']) ) : 0;

        $note = isset($_POST['note']) ? sanitize_text_field($_POST['note']) : 0;
        $note_by = get_current_user_id();
        $additional=isset($_POST['additional']) ? sanitize_text_field( $_POST['additional'] ) : 0;
        $employee = new Employee($employee_id);
        if ($employee->id) {
            // Check permission
            if (!current_user_can('wphr_edit_employee', $employee_id)) {
                $this->send_error(__('You do not have sufficient permissions to do this action', 'wphr'));
            }
          $employee->add_note($note, $note_by, $additional);
        }

        $this->send_success();
    }
    /**
     * Employee Load more note
     *
     * @return json
     */
    public function employee_load_note() {
        $employee_id = isset($_POST['user_id']) ? intval(sanitize_text_field($_POST['user_id']) ) : 0;
        $total_no = isset($_POST['total_no']) ? intval(sanitize_text_field($_POST['total_no']) ) : 0;
        $offset_no = isset($_POST['offset_no']) ? intval(sanitize_text_field($_POST['offset_no']) ) : 0;

        $employee = new Employee($employee_id);

        $notes = $employee->get_notes($total_no, $offset_no);

        ob_start();
        include WPHR_HRM_VIEWS . '/employee/tab-notes-row.php';
        $content = ob_get_clean();

        $this->send_success(array('content' => $content));
    }

    /**
     * Delete Note
     *
     * @return json
     */
    public function employee_delete_note() {
        check_admin_referer('wp-wphr-hr-nonce');

        $note_id = isset($_POST['note_id']) ? intval(sanitize_text_field($_POST['note_id']) ) : 0;
        $employee = new Employee();

        // Check permission
        if (!current_user_can('wphr_edit_employee', $employee->id)) {
            $this->send_error(__('You do not have sufficient permissions to do this action', 'wphr'));
        }

        if ($employee->delete_note($note_id)) {
            $this->send_success();
        } else {
            $this->send_error();
        }
    }

    /**
     * Employee Termination
     *
     * @since 0.1
     *
     * @return json
     */
    public function employee_terminate() {
        $this->verify_nonce('employee_update_terminate');

        $employee_id = isset($_POST['employee_id']) ? intval(sanitize_text_field($_POST['employee_id']) ) : 0;
        $terminate_date = ( empty($_POST['terminate_date']) ) ? current_time('mysql') : sanitize_text_field($_POST['terminate_date']);
        $termination_type = isset($_POST['termination_type']) ? sanitize_text_field($_POST['termination_type']) : '';
        $termination_reason = isset($_POST['termination_reason']) ? sanitize_text_field($_POST['termination_reason']) : '';
        $eligible_for_rehire = isset($_POST['eligible_for_rehire']) ? sanitize_text_field($_POST['eligible_for_rehire']) : '';

        $fields = [
            'employee_id' => $employee_id,
            'terminate_date' => $terminate_date,
            'termination_type' => $termination_type,
            'termination_reason' => $termination_reason,
            'eligible_for_rehire' => $eligible_for_rehire
        ];

        // Check permission
        if (!current_user_can('wphr_edit_employee', $employee_id)) {
            $this->send_error(__('You do not have sufficient permissions to do this action', 'wphr'));
        }

        $result = wphr_hr_employee_terminate($fields);

        if (is_wp_error($result)) {
            $this->send_error($result->get_error_message());
        }

        $this->send_success();
    }

    /**
     * Reactive terminate employees
     *
     * @since 0.1
     *
     * @return json
     */
    public function employee_termination_reactive() {
        $this->verify_nonce('wp-wphr-hr-nonce');

        $id = isset($_POST['id']) ? intval(sanitize_text_field($_POST['id']) ) : 0;

        if (!$id) {
            $this->send_error(__('Something wrong', 'wphr'));
        }

        // Check permission
        if (!current_user_can('wphr_edit_employee', $id)) {
            $this->send_error(__('You do not have sufficient permissions to do this action', 'wphr'));
        }

        \WPHR\HR_MANAGER\HRM\Models\Employee::where('user_id', $id)->update(['status' => 'active']);

        delete_user_meta($id, '_wphr_hr_termination');

        $this->send_success();
    }

    /**
     * Check for created an employee
     *
     * @since 1.0
     *
     * @return json
     */
    public function check_user() {
        $email = isset($_REQUEST['email']) ? sanitize_text_field($_REQUEST['email']) : false;

        if (!$email) {
            $this->send_error(__('No email address provided', 'wphr'));
        }

        $user = get_user_by('email', $email);

        // we didn't found any user with this email address
        if (false === $user) {
            $this->send_success();
        }

        if (null != \WPHR\HR_MANAGER\HRM\Models\Employee::withTrashed()->whereUserId($user->ID)->first()) {
            $employee = new \WPHR\HR_MANAGER\HRM\Employee(intval($user->ID));
            $this->send_error(['type' => 'employee', 'data' => $employee->to_array()]);
        }

        // seems like we found one
        $this->send_error(['type' => 'wp_user', 'data' => $user]);
    }

    /**
     * Create wp user to emplyee
     *
     * @since 1.0
     *
     * @return json
     */
    public function employee_create_from_wp_user() {
        $this->verify_nonce('wp-wphr-hr-nonce');

        $id = isset($_POST['user_id']) ? sanitize_text_field( $_POST['user_id'] ) : 0;

        if (!$id) {
            $this->send_error(__('User not found', 'wphr'));
        }

        $user = get_user_by('id', intval($id));

        $user->add_role('employee');

        $employee = new \WPHR\HR_MANAGER\HRM\Models\Employee();
        $exists = $employee->where('user_id', '=', $user->ID)->first();

        if (null === $exists) {
            $employee = $employee->create([
                'user_id' => $user->ID,
                'designation' => 0,
                'department' => 0,
                'status' => 'active'
            ]);

            $this->send_success($employee);
        } else {
            $this->send_error(__('Employee already exist.', 'wphr'));
        }
    }

    /**
     * Mark Read Announcement
     *
     * @since 0.1
     *
     * @return json|boolean
     */
    public function mark_read_announcement() {
        $this->verify_nonce('wp-wphr-hr-nonce');
        $row_id = intval(sanitize_text_field($_POST['id']) );

        \WPHR\HR_MANAGER\HRM\Models\Announcement::find($row_id)->update(['status' => 'read']);

        return $this->send_success();
    }

    /**
     * View single announcment
     *
     * @since 0.1
     *
     * @return json [post array]
     */
    public function view_announcement() {
        global $post;

        $this->verify_nonce('wp-wphr-hr-nonce');
        $post_id = intval(sanitize_text_field($_POST['id']) );
        if (!$post_id) {
            $this->send_error();
        }

        \WPHR\HR_MANAGER\HRM\Models\Announcement::where('post_id', $post_id)->update(['status' => 'read']);

        $post = get_post($post_id);
        setup_postdata($post);

        $post_data = [
            'title' => get_the_title(),
            'content' => wpautop(get_the_content())
        ];

        wp_reset_postdata();

        $this->send_success($post_data);
    }

     /**
     * Employee Update Performance Reviews
     *
     * @since 0.1
     *
     * @return json
     */
    public function employee_update_performance() {

        // check permission for adding performance
        if (isset($_POST['employee_id']) && sanitize_text_field($_POST['employee_id']) && !current_user_can('wphr_edit_employee', $_POST['employee_id'])) {
            $this->send_error(__('You do not have sufficient permissions to do this action', 'wphr'));
        }

        $type = isset($_POST['type']) ? sanitize_text_field($_POST['type']) : '';

        if ($type && $type == 'reviews') {
            $employee_id = isset($_POST['employee_id']) ? intval(sanitize_text_field($_POST['employee_id']) ) : 0;
            $review_id = isset($_POST['review_id']) ? intval(sanitize_text_field($_POST['review_id']) ) : 0;
            $reporting_to = isset($_POST['reporting_to']) ? intval(sanitize_text_field($_POST['reporting_to']) ) : 0;
            $job_knowledge = isset($_POST['job_knowledge']) ? intval(sanitize_text_field($_POST['job_knowledge']) ) : 0;
            $work_quality = isset($_POST['work_quality']) ? intval(sanitize_text_field($_POST['work_quality']) ) : 0;
            $attendance = isset($_POST['attendance']) ? intval(sanitize_text_field($_POST['attendance']) ) : 0;
            $communication = isset($_POST['communication']) ? intval(sanitize_text_field($_POST['communication']) ) : 0;
            $dependablity = isset($_POST['dependablity']) ? intval(sanitize_text_field($_POST['dependablity']) ) : 0;
            $performance_date = ( empty($_POST['performance_date']) ) ? current_time('mysql') : sanitize_text_field( $_POST['performance_date'] );
           $additional=serialize($_POST['additional']);

            // some basic validations
            $requires = [
                'performance_date' => __('Review Date', 'wphr'),
                'reporting_to' => __('Reporting To', 'wphr'),
            ];

            $fields = [
                'employee_id' => $employee_id,
                'reporting_to' => $reporting_to,
                'job_knowledge' => $job_knowledge,
                'work_quality' => $work_quality,
                'attendance' => $attendance,
                'communication' => $communication,
                'dependablity' => $dependablity,
                'type' => $type,
                'performance_date' => convert_to_data_format( $performance_date ),
                'additional'=>$additional

            ];
          //  print_r($fields);
          //  die();
        }

        if ($type && $type == 'comments') {

            $employee_id = isset($_POST['employee_id']) ? intval(sanitize_text_field($_POST['employee_id']) ) : 0;
            $review_id = isset($_POST['review_id']) ? intval(sanitize_text_field($_POST['review_id']) ) : 0;
            $reviewer = isset($_POST['reviewer']) ? intval(sanitize_text_field($_POST['reviewer']) ) : 0;
            $comments = isset($_POST['comments']) ? esc_textarea($_POST['comments']) : '';
            $performance_date = ( empty($_POST['performance_date']) ) ? current_time('mysql') : sanitize_text_field( $_POST['performance_date'] );
            $additional=serialize($_POST['additional']);

            // some basic validations
            $requires = [
                'performance_date' => __('Reference Date', 'wphr'),
                'reviewer' => __('Reviewer', 'wphr'),
            ];

            $fields = [
                'employee_id' => $employee_id,
                'reviewer' => $reviewer,
                'comments' => $comments,
                'type' => $type,
                'performance_date' => convert_to_data_format( $performance_date ),
                'additional'=>$additional
            ];
        }

        if ($type && $type == 'goals') {

            $employee_id = isset($_POST['employee_id']) ? intval(sanitize_text_field($_POST['employee_id']) ) : 0;
            $review_id = isset($_POST['review_id']) ? intval(sanitize_text_field($_POST['review_id']) ) : 0;
            $completion_date = ( empty($_POST['completion_date']) ) ? current_time('mysql') : sanitize_text_field( $_POST['completion_date'] );
            $goal_description = isset($_POST['goal_description']) ? esc_textarea($_POST['goal_description']) : '';
            $employee_assessment = isset($_POST['employee_assessment']) ? esc_textarea($_POST['employee_assessment']) : '';
            $supervisor = isset($_POST['supervisor']) ? intval(sanitize_text_field($_POST['supervisor']) ) : 0;
            $supervisor_assessment = isset($_POST['supervisor_assessment']) ? esc_textarea($_POST['supervisor_assessment']) : '';
            $performance_date = ( empty($_POST['performance_date']) ) ? current_time('mysql') : sanitize_text_field($_POST['performance_date']);
            $additional=serialize($_POST['additional']);

            // some basic validations
            $requires = [
                'performance_date' => __('Reference Date', 'wphr'),
                'completion_date' => __('Completion Date', 'wphr'),
            ];

            $fields = [
                'employee_id' => $employee_id,
                'completion_date' => convert_to_data_format( $completion_date ),
                'goal_description' => $goal_description,
                'employee_assessment' => $employee_assessment,
                'supervisor' => $supervisor,
                'supervisor_assessment' => $supervisor_assessment,
                'type' => $type,
                'performance_date' => convert_to_data_format( $performance_date ),
                'additional'=>$additional

            ];
        }


        foreach ($requires as $var_name => $label) {
            if (!$$var_name) {
                $this->send_error(sprintf(__('%s is required', 'wphr'), $label));
            }
        }

        if (!$review_id) {
            \WPHR\HR_MANAGER\HRM\Models\Performance::create($fields);
        } else {
            \WPHR\HR_MANAGER\HRM\Models\Performance::find($review_id)->update($fields);
        }

        $this->send_success();
    }

    /**
     * Remove an Prformance
     *
     * @return void
     */
    public function employee_delete_performance() {
        $this->verify_nonce('wp-wphr-hr-nonce');

        $id = isset($_POST['id']) ? intval(sanitize_text_field($_POST['id']) ) : 0;

        if (!current_user_can('wphr_delete_review')) {
            $this->send_error(__('You do not have sufficient permissions to do this action', 'wphr'));
        }

        \WPHR\HR_MANAGER\HRM\Models\Performance::find($id)->delete();

        $this->send_success();
    }

    /**
     * Add/edit work experience
     *
     * @return void
     */
    public function employee_work_experience_create() {
        $this->verify_nonce('wphr-work-exp-form');

        $employee_id = isset($_POST['employee_id']) ? intval(sanitize_text_field($_POST['employee_id']) ) : 0;

        // Check permission
        if (!current_user_can('wphr_edit_employee', $employee_id)) {
            $this->send_error(__('You do not have sufficient permissions to do this action', 'wphr'));
        }

        $exp_id = isset($_POST['exp_id']) ? intval(sanitize_text_field($_POST['exp_id']) ): 0;
        $company_name = isset($_POST['company_name']) ? sanitize_text_field($_POST['company_name']) : '';
        $job_title = isset($_POST['job_title']) ? sanitize_text_field($_POST['job_title']) : '';
        $from = isset($_POST['from']) ? convert_to_data_format( sanitize_text_field($_POST['from']) ) : '';
        $to = isset($_POST['to']) ? convert_to_data_format( sanitize_text_field($_POST['to']) ) : '';
        $description = isset($_POST['description']) ? sanitize_text_field($_POST['description']) : '';

        // some basic validations
        $requires = [
            'company_name' => __('Company Name', 'wphr'),
            'job_title' => __('Role', 'wphr'),
            'from' => __('From date', 'wphr'),
            'to' => __('To date', 'wphr'),
        ];

        foreach ($requires as $var_name => $label) {
            if (!$$var_name) {
                $this->send_error(sprintf(__('%s is required', 'wphr'), $label));
            }
        }

        $fields = [
            'employee_id' => $employee_id,
            'company_name' => $company_name,
            'job_title' => $job_title,
            'from' => $from,
            'to' => $to,
            'description' => $description
        ];

        if (!$exp_id) {
            do_action('wphr_hr_employee_experience_new', $fields);
            Work_Experience::create($fields);
        } else {
            Work_Experience::find($exp_id)->update($fields);
        }

        $this->send_success();
    }

    /**
     * Delete a work experience
     *
     * @return void
     */
    public function employee_work_experience_delete() {
        $this->verify_nonce('wp-wphr-hr-nonce');

        $id = isset($_POST['id']) ? intval(sanitize_text_field($_POST['id']) ) : 0;
        $employee_id = isset($_POST['employee_id']) ? intval(sanitize_text_field($_POST['employee_id']) ) : 0;

        if (!$employee_id) {
            $this->send_error(__('No employee found', 'wphr'));
        }

        // Check permission
        if (!current_user_can('wphr_edit_employee', $employee_id)) {
            $this->send_error(__('You do not have sufficient permissions to do this action', 'wphr'));
        }

        if ($id) {
            do_action('wphr_hr_employee_experience_delete', $id);
            Work_Experience::find($id)->delete();
        }

        $this->send_success();
    }

    /**
     * Create/edit educational experiences
     *
     * @return void
     */
    public function employee_education_create() {
        $this->verify_nonce('wphr-hr-education-form');

        $employee_id = isset($_POST['employee_id']) ? intval(sanitize_text_field($_POST['employee_id']) ) : 0;

        // Check permission
        if (!current_user_can('wphr_edit_employee', $employee_id)) {
            $this->send_error(__('You do not have sufficient permissions to do this action', 'wphr'));
        }

        $edu_id = isset($_POST['edu_id']) ? intval(sanitize_text_field($_POST['edu_id']) ) : 0;
        $school = isset($_POST['school']) ? sanitize_text_field($_POST['school']) : '';
        $degree = isset($_POST['degree']) ? sanitize_text_field($_POST['degree']) : '';
        $field = isset($_POST['field']) ? sanitize_text_field($_POST['field']) : '';
        $finished = isset($_POST['finished']) ? intval(sanitize_text_field($_POST['finished']) ) : '';
        $notes = isset($_POST['notes']) ? sanitize_text_field($_POST['notes']) : '';
        $interest = isset($_POST['interest']) ? sanitize_text_field($_POST['interest']) : '';

        // some basic validations
        $requires = [
            'school' => __('School Name', 'wphr'),
            'degree' => __('Degree', 'wphr'),
            'field' => __('Field', 'wphr'),
            'finished' => __('Completion date', 'wphr'),
        ];

        foreach ($requires as $var_name => $label) {
            if (!$$var_name) {
                $this->send_error(sprintf(__('%s is required', 'wphr'), $label));
            }
        }

        $fields = [
            'employee_id' => $employee_id,
            'school' => $school,
            'degree' => $degree,
            'field' => $field,
            'finished' => $finished,
            'notes' => $notes,
            'interest' => $interest
        ];

        if (!$edu_id) {
            do_action('wphr_hr_employee_education_create', $fields);
            Education::create($fields);
        } else {
            Education::find($edu_id)->update($fields);
        }

        $this->send_success();
    }

    /**
     * Delete a work experience
     *
     * @return void
     */
    public function employee_education_delete() {
        $this->verify_nonce('wp-wphr-hr-nonce');

        $id = isset($_POST['id']) ? intval(sanitize_text_field( $_POST['id']) ) : 0;
        $employee_id = isset($_POST['employee_id']) ? intval(sanitize_text_field($_POST['employee_id']) ) : 0;

        if (!$employee_id) {
            $this->send_error(__('No employee found', 'wphr'));
        }

        // Check permission
        if (!current_user_can('wphr_edit_employee', $employee_id)) {
            $this->send_error(__('You do not have sufficient permissions to do this action', 'wphr'));
        }

        if ($id) {
            do_action('wphr_hr_employee_education_delete', $id);
            Education::find($id)->delete();
        }

        $this->send_success();
    }

    /**
     * Create/edit dependents
     *
     * @return void
     */
    public function employee_dependent_create() {
        $this->verify_nonce('wphr-hr-dependent-form');
        $employee_id = isset($_POST['employee_id']) ? intval(sanitize_text_field($_POST['employee_id']) ) : 0;

        // Check permission
        if (!current_user_can('wphr_edit_employee', $employee_id)) {
            $this->send_error(__('You do not have sufficient permissions to do this action', 'wphr'));
        }

        $dep_id = isset($_POST['dep_id']) ? intval(sanitize_text_field($_POST['dep_id']) ): 0;
        $name = isset($_POST['name']) ? sanitize_text_field($_POST['name']) : '';
        $relation = isset($_POST['relation']) ? sanitize_text_field($_POST['relation']) : '';
        $dob = isset($_POST['dob']) ? convert_to_data_format( sanitize_text_field($_POST['dob']) ): '';

        // some basic validations
        $requires = [
            'name' => __('Name', 'wphr'),
            'relation' => __('Relation', 'wphr'),
        ];

        foreach ($requires as $var_name => $label) {
            if (!$$var_name) {
                $this->send_error(sprintf(__('%s is required', 'wphr'), $label));
            }
        }

        $fields = [
            'employee_id' => $employee_id,
            'name' => $name,
            'relation' => $relation,
            'dob' => $dob,
        ];

        if (!$dep_id) {
            do_action('wphr_hr_employee_dependents_create', $fields);
            Dependents::create($fields);
        } else {
            Dependents::find($dep_id)->update($fields);
        }

        $this->send_success();
    }

    /**
     * Delete a dependent
     *
     * @return void
     */
    public function employee_dependent_delete() {
        $this->verify_nonce('wp-wphr-hr-nonce');

        $id = isset($_POST['id']) ? intval(sanitize_text_field($_POST['id']) ) : 0;
        $employee_id = isset($_POST['employee_id']) ? intval(sanitize_text_field($_POST['employee_id']) ) : 0;

        if (!$employee_id) {
            $this->send_error(__('No employee found', 'wphr'));
        }

        // Check permission
        if (!current_user_can('wphr_edit_employee', $employee_id)) {
            $this->send_error(__('You do not have sufficient permissions to do this action', 'wphr'));
        }

        if ($id) {
            do_action('wphr_hr_employee_dependents_delete', $id);
            Dependents::find($id)->delete();
        }

        $this->send_success();
    }

    /**
     * Create or update a leave policy
     *
     * @since 0.1
     *
     * @return void
     */
    public function leave_policy_create() {
        $this->verify_nonce('wphr-leave-policy');

        if (!current_user_can('wphr_leave_create_request')) {
            $this->send_error(__('You do not have sufficient permissions to do this action', 'wphr'));
        }

        $policy_id = isset($_POST['policy-id']) ? intval(sanitize_text_field($_POST['policy-id']) ) : 0;
        $name = isset($_POST['name']) ? sanitize_text_field($_POST['name']) : '';
        $days = isset($_POST['days']) ? intval(sanitize_text_field($_POST['days']) ) : '';
        $color = isset($_POST['color']) ? sanitize_text_field($_POST['color']) : '';
        $department = isset($_POST['department']) ? intval(sanitize_text_field($_POST['department']) ) : 0;
        $designation = isset($_POST['designation']) ? intval(sanitize_text_field($_POST['designation']) ) : 0;
        $gender = isset($_POST['gender']) ? sanitize_text_field($_POST['gender']) : 0;
        $marital_status = isset($_POST['maritial']) ? sanitize_text_field($_POST['maritial']) : 0;
        $activate = isset($_POST['rateTransitions']) ? intval(sanitize_text_field($_POST['rateTransitions']) ) : 1;
        $description = isset($_POST['description']) ? sanitize_text_field($_POST['description']) : '';
        $after_x_day = isset($_POST['no_of_days']) ? intval(sanitize_text_field($_POST['no_of_days']) ) : '';
        $effective_date = isset($_POST['effective_date']) ? convert_to_data_format( sanitize_text_field($_POST['effective_date']) ) : '';
        $location = isset($_POST['location']) ? sanitize_text_field($_POST['location']) : '';
        $instant_apply = isset($_POST['apply']) ? sanitize_text_field($_POST['apply']) : '';

        $policy_id = wphr_hr_leave_insert_policy(array(
            'id' => $policy_id,
            'name' => $name,
            'description' => $description,
            'value' => $days,
            'color' => $color,
            'department' => $department,
            'designation' => $designation,
            'gender' => $gender,
            'marital' => $marital_status,
            'activate' => $activate,
            'execute_day' => $after_x_day,
            'effective_date' => $effective_date,
            'location' => $location,
            'instant_apply' => $instant_apply
        ));

        if (is_wp_error($policy_id)) {
            $this->send_error($policy_id->get_error_message());
        }

        $this->send_success();
    }

    /**
     * Create or update a holiday
     *
     * @since 0.1
     *
     * @return void
     */
    public function holiday_create() {


        $this->verify_nonce('wphr-leave-holiday');

        if (!current_user_can('wphr_leave_manage')) {
            $this->send_error(__('You do not have sufficient permissions to do this action', 'wphr'));
        }

        $holiday_id = isset($_POST['holiday_id']) ? intval(sanitize_text_field( $_POST['holiday_id']) ) : 0;
        $title = isset($_POST['title']) ? sanitize_text_field($_POST['title']) : '';
        $start_date = isset($_POST['start_date']) ? convert_to_data_format( sanitize_text_field($_POST['start_date']) ): '';
        $end_date = isset($_POST['end_date']) && !empty($_POST['end_date']) ? convert_to_data_format( sanitize_text_field($_POST['end_date']) ) : $start_date;
        $end_date = date('Y-m-d H:i:s', strtotime($end_date . ' +1 day'));
        $description = isset($_POST['description']) ? sanitize_text_field($_POST['description']) : '';
        $range_status = isset($_POST['range']) ? sanitize_text_field($_POST['range']) : 'off';
        $location_id = isset($_POST['location_id']) ? sanitize_text_field($_POST['location_id']) : 0;
        $error = true;


        if ($range_status == 'off') {
            $end_date = date('Y-m-d H:i:s', strtotime($start_date . ' +1 day'));
        }

        if (is_wp_error($error)) {
            $this->send_error($error->get_error_message());
        }

        $holiday_data = array(
            'id' => $holiday_id,
            'title' => $title,
            'start' =>  $start_date,
            'end' => $end_date,
            'description' => $description,
            'location_id' => $location_id,
        );

        $holiday_id = wphr_hr_leave_insert_holiday($holiday_data);

        if (is_wp_error($holiday_id)) {
            $this->send_error($holiday_id->get_error_message());
        }

        $this->send_success();
    }

    /**
     * Delete a leave policy
     *
     * @since 0.1
     *
     * @return void
     */
    public function leave_policy_delete() {
        $this->verify_nonce('wp-wphr-hr-nonce');

        if (!current_user_can('wphr_leave_manage')) {
            $this->send_error(__('You do not have sufficient permissions to do this action', 'wphr'));
        }

        $id = isset($_POST['id']) ? intval(sanitize_text_field($_POST['id']) ) : 0;
        if ($id) {
            wphr_hr_leave_policy_delete($id);

            $this->send_success(__('Policy has been deleted', 'wphr'));
        }

        $this->send_error(__('Something went worng!', 'wphr'));
    }

    /**
     * Gets the leave dates
     *
     * Returns the date list between the start and end date of the
     * two dates
     *
     * @since 0.1
     *
     * @return void
     */
    public function leave_request_dates() {

        $this->verify_nonce('wp-wphr-hr-nonce');

        $id = isset($_POST['employee_id']) && $_POST['employee_id'] ? intval(sanitize_text_field( $_POST['employee_id']) ) : false;

        if (!$id) {
            $this->send_error(__('Please select an employee', 'wphr'));
        }

        $location_id = \WPHR\HR_MANAGER\HRM\Models\Employee::select('location')->where('user_id', $id)->get()->toArray();
        if (count($location_id)) {
            $location_id = $location_id[0]['location'];
        } else {
            $location_id = 0;
        }
        $office_timing = \WPHR\HR_MANAGER\Admin\Models\Company_Locations::select('*')->where('id', $location_id)->get()->toArray();

        if (is_array($office_timing)) {
            $office_start_time = isset($office_timing[0]['office_start_time']) ? strtotime($office_timing[0]['office_start_time']) : 0;
            $office_end_time = isset($office_timing[0]['office_end_time']) ? strtotime($office_timing[0]['office_end_time']) : 0;
        }

        $start = '12:00AM';
        $end = '11:59PM';
        $interval = '+15 minutes';

        $start_str = strtotime($start);
        $end_str = strtotime($end);
        $now_str = $start_str;
        $time_slot = array();
        $counter = 1;
        while ($now_str <= $end_str) {
            $timevalue = date('H:i:s', $now_str);
            if ($office_start_time && $office_end_time) {
                if (( $office_start_time <= $now_str && $office_end_time >= $now_str ) || ( $office_start_time == $office_end_time )) {
                    $time_slot[$counter]['key'] = $timevalue;
                    $time_slot[$counter]['value'] = date('h:i A', $now_str);
                    $counter++;
                }
            } else {
                $time_slot[$counter]['key'] = $timevalue;
                $time_slot[$counter]['value'] = date('h:i A', $now_str);
                $counter++;
            }
            $now_str = strtotime($interval, $now_str);
        }

        $policy_id = isset($_POST['type']) && sanitize_text_field($_POST['type']) ? sanitize_text_field($_POST['type']) : false;

        if (!$policy_id) {
            $this->send_error(__('Please select a policy', 'wphr'));
        }

        $start_date = isset($_POST['from']) ? convert_to_data_format( sanitize_text_field($_POST['from']) ) : date_i18n('Y-m-d');
        $end_date = isset($_POST['to']) ? convert_to_data_format( sanitize_text_field($_POST['to']) ) : date_i18n('Y-m-d');
        $valid_date_range = true;
        //$valid_date_range = wphr_hrm_is_valid_leave_date_range_within_financial_date_range($start_date, $end_date);
        $financial_start_date = date('Y-m-d', strtotime(wphr_financial_start_date()));
        $financial_end_date = date('Y-m-d', strtotime(wphr_financial_end_date()));

        $employee_id = isset($_POST['employee_id']) && sanitize_text_field( $_POST['employee_id'] ) > 0 ? intval(sanitize_text_field($_POST['employee_id']) ) : false;

        if ($start_date > $end_date) {
            $this->send_error(__('Invalid date range', 'wphr'));
        }

        if (!$valid_date_range) {
            $this->send_error(sprintf(__('Date range must be within %s to %s', 'wphr'), wphr_format_date($financial_start_date), wphr_format_date($financial_end_date)));
        }

        $leave_record_exist = wphr_hrm_is_leave_recored_exist_between_date($start_date, $end_date, $id);

        if ($leave_record_exist) {
            $this->send_error(__('Existing Leave Record found within selected range!', 'wphr'));
        }

        if ($start_date < $financial_end_date && $end_date > $financial_end_date) {
            $new_end_date = $financial_end_date;
            $new_start_date = date('Y-m-d', strtotime("$financial_end_date +1 day"));
            $is_policy_valid = wphr_hrm_is_valid_leave_duration($start_date, $new_end_date, $policy_id, $id);
            if ($is_policy_valid) {
                $is_policy_valid = wphr_hrm_is_valid_leave_duration($new_start_date, $end_date, $policy_id, $id);
            }
        } elseif ($start_date < $financial_start_date && $end_date > $financial_start_date) {
            $new_start_date = $financial_start_date;
            $new_end_date = date('Y-m-d', strtotime("$financial_start_date -1 day"));
            $is_policy_valid = wphr_hrm_is_valid_leave_duration($start_date, $new_end_date, $policy_id, $id);
            if ($is_policy_valid) {
                $is_policy_valid = wphr_hrm_is_valid_leave_duration($new_start_date, $end_date, $policy_id, $id);
            }
        } else {
            $is_policy_valid = wphr_hrm_is_valid_leave_duration($start_date, $end_date, $policy_id, $id);
        }

        if (!$is_policy_valid) {
            $this->send_error(__('Sorry! You do not have any leave left under this leave policy', 'wphr'));
        }

        $days = wphr_hr_get_work_days_between_dates($start_date, $end_date, $employee_id);

        if (is_wp_error($days)) {
            $this->send_error($days->get_error_message());
        }

        // just a bit more readable date format
        foreach ($days['days'] as &$date) {

            $date['date'] = wphr_format_date($date['date'], 'D, M d, Y');
        }

        $leave_count = $days['total'];
        $days['total'] = sprintf('%d %s', $days['total'], _n('day', 'days', $days['total'], 'wphr'));
		
		/**
		* Display leave message base on leave request finacial year
		*/
        $balance = wphr_hr_leave_get_balance($employee_id, $start_date);
		$financial_year_dates = wphr_get_financial_year_dates_by_user( $employee_id, $start_date );
		$financial_start_date = wphr_format_date( $financial_year_dates['start'] );
		$financial_end_date = wphr_format_date( $financial_year_dates['end'] );
        if (array_key_exists($policy_id, $balance)) {
            $available = $balance[$policy_id]['entitlement'] - $balance[$policy_id]['total'];
            $working_hours = $balance[$policy_id]['working_hours'];
            $total_approved_minutes = $balance[$policy_id]['total_approved_minutes'];
            $total_minutes = $balance[$policy_id]['total_minutes'];
            $available_arr = wphr_get_balance_details_from_minutes(( $total_minutes - $total_approved_minutes), $working_hours);

            if (!empty($available_arr)) {
                $available = $available_arr['days'];
                $hours = $available_arr['hours'];
                $minutes = $available_arr['minutes'];
            } else {
                $available = 0;
                $hours = 0;
                $minutes = 0;
            }
        }

        if ($available < 0) {
            $content = ( $available_arr['balance_string'] ) ? '<span class=\'description red\'>' . $available_arr['balance_string'] . ' Disponible </span>' : '-';
        } elseif ($available > 0) {
            $content = ( $available_arr['balance_string'] ) ? '<span class=\'description green\'>' . $available_arr['balance_string'] . ' Disponible </span>' : '-';
        } else {
            $leave_policy_day = \WPHR\HR_MANAGER\HRM\Models\Leave_Policies::select('value')->where('id', $policy_id)->pluck('value');
            $content = sprintf('<span class=\'description\'>%d %s ( %s to %s )</span>', number_format_i18n($leave_policy_day), __('days are available', 'wphr'), $financial_start_date, $financial_end_date );
        }

        $this->send_success(array('print' => $days, 'leave_count' => $leave_count, 'time_slot' => $time_slot, 'message' => $content));
    }

    /**
     * Checks valid leave Time range
     *
     * success if valid time range 
     */
    public function leave_request_times() {

        $this->verify_nonce('wp-wphr-hr-nonce');

        $flag = true;
        $id = isset($_POST['employee_id']) && sanitize_text_field( $_POST['employee_id'] ) ? intval(sanitize_text_field($_POST['employee_id']) ) : false;

        if (!$id) {
            $flag = false;
            $this->send_error(__('Please select an employee', 'wphr'));
        }

        $policy_id = isset($_POST['type']) && sanitize_text_field($_POST['type']) ? sanitize_text_field($_POST['type']) : false;

        if (!$policy_id) {
            $flag = false;
            $this->send_error(__('Please select a policy', 'wphr'));
        }

        $start_time = isset($_POST['fromTime']) ? strtotime( sanitize_text_field( $_POST['fromTime']) ) : '';
        $end_time = isset($_POST['toTime']) ? strtotime( sanitize_text_field( $_POST['toTime']) ) : '';
        $time_diff = ( ( $end_time - $start_time ) / 60 ) / 60;


        if ($start_time == $end_time) {
            $flag = false;
            $this->send_error(__('Start-time and End-time can not be same', 'wphr'));
        }

        if ($start_time > $end_time) {
            $flag = false;
            $this->send_error(__('Invalid Time range', 'wphr'));
        }

        if ($time_diff > 8) {
            //$flag = false;
            //$this->send_error( sprintf( __( 'Leave Duration Should Be maximum 8 Hours', 'wphr' ) ) );
        }

        $start_date = sanitize_text_field($_POST['start_date']);
        $end_date = sanitize_text_field($_POST['end_date']);

        $start = $start_date . ' ' . date('H:i:s', $start_time);
        $end = $end_date . ' ' . date('H:i:s', $end_time);

        $leave_record_exist = wphr_hrm_is_leave_recored_exist_between_dateTime($start, $end, $id);

        if ($leave_record_exist) {
            $this->send_error(__('Existing Leave Record found within selected Time range!', 'wphr'));
        }

        if ($flag == true) {
            $this->send_success();
        }
    }

    /**
     * Fetch assigning policy dropdown html
     * according to employee id
     *
     * @since 0.1
     *
     * @return html|json
     */
    public function leave_assign_employee_policy() {
        $this->verify_nonce('wp-wphr-hr-nonce');
        $employee_id = isset($_POST['employee_id']) && sanitize_text_field($_POST['employee_id']) ? intval(sanitize_text_field($_POST['employee_id']) ) : false;

        if (!$employee_id) {
            $this->send_error(__('Please select an employee', 'wphr'));
        }

        $location_id = \WPHR\HR_MANAGER\HRM\Models\Employee::select('location')->where('user_id', $employee_id)->get()->toArray();
        if (count($location_id)) {
            $location_id = $location_id[0]['location'];
        } else {
            $location_id = 0;
        }
        $office_timing = \WPHR\HR_MANAGER\Admin\Models\Company_Locations::select('*')->where('id', $location_id)->get()->toArray();

        if (is_array($office_timing)) {
            $office_start_time = isset($office_timing[0]['office_start_time']) ? strtotime($office_timing[0]['office_start_time']) : 0;
            $office_end_time = isset($office_timing[0]['office_end_time']) ? strtotime($office_timing[0]['office_end_time']) : 0;
        }

        $start = '12:00AM';
        $end = '11:59PM';
        $interval = '+15 minutes';

        $start_str = strtotime($start);
        $end_str = strtotime($end);
        $now_str = $start_str;
        $time_slot = array();
        $counter = 1;
        while ($now_str <= $end_str) {
            $timevalue = date('H:i:s', $now_str);
            if ($office_start_time && $office_end_time) {
                if (( $office_start_time <= $now_str && $office_end_time >= $now_str ) || ( $office_end_time == $office_start_time )) {
                    $time_slot[$counter]['key'] = $timevalue;
                    $time_slot[$counter]['value'] = date('h:i A', $now_str);
                    $counter++;
                }
            } else {
                $time_slot[$counter]['key'] = $timevalue;
                $time_slot[$counter]['value'] = date('h:i A', $now_str);
                $counter++;
            }
            $now_str = strtotime($interval, $now_str);
        }

        $policies = wphr_hr_get_assign_policy_from_entitlement($employee_id);

        if ($policies) {
            ob_start();
            wphr_html_form_input(array(
                'label' => __('Leave Type', 'wphr'),
                'name' => 'leave_policy',
                'id' => 'wphr-hr-leave-req-leave-policy',
                'value' => '',
                'required' => true,
                'type' => 'select',
                'options' => array('' => __('- Select -', 'wphr')) + $policies
            ));
            $content = ob_get_clean();

            return $this->send_success(array('data' => $content, 'time_slot' => $time_slot));
        }

        return $this->send_error(__('Selected user is not entitled to any leave policy. Set leave entitlement to apply for leave', 'wphr'));
    }

    /**
     * Get available day for users leave policy
     *
     * @since 0.1
     *
     * @return json
     */
    public function leave_available_days() {

        $this->verify_nonce('wp-wphr-hr-nonce');

        $employee_id = isset($_POST['employee_id']) && sanitize_text_field($_POST['employee_id']) ? intval(sanitize_text_field($_POST['employee_id']) ) : false;
        $policy_id = isset($_POST['policy_id']) && sanitize_text_field($_POST['policy_id']) ? intval(sanitize_text_field($_POST['policy_id']) ) : false;
        $available = 0;

        if (!$employee_id) {
            $this->send_error(__('Please select an employee', 'wphr'));
        }

        if (!$policy_id) {
            $this->send_error(__('Please select a policy', 'wphr'));
        }

        $balance = wphr_hr_leave_get_balance($employee_id);

        if (array_key_exists($policy_id, $balance)) {
            $available = $balance[$policy_id]['entitlement'] - $balance[$policy_id]['total'];
            $working_hours = $balance[$policy_id]['working_hours'];
            $total_approved_minutes = $balance[$policy_id]['total_approved_minutes'];
            $total_minutes = $balance[$policy_id]['total_minutes'];
            $available_arr = wphr_get_balance_details_from_minutes(( $total_minutes - $total_approved_minutes), $working_hours);

            if (!empty($available_arr)) {
                $available = $days = $available_arr['days'];
                $hours = $available_arr['hours'];
                $minutes = $available_arr['minutes'];
            } else {
                $available = $days = 0;
                $hours = 0;
                $minutes = 0;
            }
        }
		$financial_year_dates = wphr_get_financial_year_dates_by_user( $employee_id );
		$start = wphr_format_date( $financial_year_dates['start'] );
		$end = wphr_format_date( $financial_year_dates['end'] );
        if ($available < 0) {
            $content = ( $available_arr['balance_string'] ) ? '<span class="description red">' . $available_arr['balance_string'] . ' Disponible </span>' : '-';
        } elseif ($available > 0) {
            $content = ( $available_arr['balance_string'] ) ? '<span class="description green">' . $available_arr['balance_string'] . ' Disponible (  )</span>' : '-';
        } else {
            $leave_policy_day = \WPHR\HR_MANAGER\HRM\Models\Leave_Policies::select('value')->where('id', $policy_id)->pluck('value');
            $content = sprintf('<span class="description">%d </span>', number_format_i18n($leave_policy_day), __('Dias Disponible', 'wphr'), $start, $end);
        }
        $this->send_success($content);
    }

    /**
     * Insert leave request for users
     *
     * Save leave request data from employee dashboard
     * overview area
     *
     * @since 0.1
     *
     * @return json
     */
    public function leave_request() {

        if (!wp_verify_nonce( sanitize_text_field( $_POST['_wpnonce'] ), 'wphr-leave-req-new')) {
            $this->send_error(__('Something went wrong!', 'wphr'));
        }

        if (!current_user_can('wphr_leave_create_request')) {
            $this->send_error(__('You do not have sufficient permissions to do this action', 'wphr'));
        }
        $date_array = array();
        $working_hours = get_employee_working_hours( sanitize_text_field( $_POST['employee_id'] ) );
        $length_hours = $working_hours;
        $from_time = !empty($_POST['from_time']) ? strtotime( sanitize_text_field( $_POST['from_time'] ) ) : 0;
        $to_time = !empty($_POST['to_time']) ? strtotime( sanitize_text_field( $_POST['to_time']) ) : 0;

        $employee_id = isset($_POST['employee_id']) ? intval(sanitize_text_field($_POST['employee_id']) ) : 0;
        $leave_policy = isset($_POST['leave_policy']) ? intval(sanitize_text_field($_POST['leave_policy']) ) : 0;
        // @todo: date format may need to be changed when partial leave introduced
        $start_date = isset($_POST['leave_from']) ? sanitize_text_field( convert_to_data_format( $_POST['leave_from'] ) . ' 00:00:00') : date_i18n('Y-m-d 00:00:00');
        $end_date = isset($_POST['leave_to']) ? sanitize_text_field(convert_to_data_format( $_POST['leave_to'] ). ' 23:59:59') : date_i18n('Y-m-d 23:59:59');

        $date_array = array();
        if ( sanitize_text_field($_POST['leave_from']) != sanitize_text_field($_POST['leave_to'])) {
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
            } elseif ($start_date < $financial_start_date && $end_date > $financial_start_date) {
                
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
                            $length_hours = ( $difference_in_minutes / 60 ) > $working_hours ? $working_hours : ( $difference_in_minutes / 60 );
                            $date_array[$count]['length_hours'] = $length_hours;
                        } else {
                            $count--;
                            $date_array[$count]['to_time'] = date('Y-m-d', $i) . ' 23:59:59';
                            $date_array[$count]['length_hours'] += $working_hours;
                        }
                        $count++;
                        $loop_count++;
                    }
                }
            }
        } else {
            $day_wise_from_times = custom_sanitize_array( $_POST['day_wise_from_times'] );
            $day_wise_to_times   = custom_sanitize_array( $_POST['day_wise_to_times'] );

            if (is_array($day_wise_from_times) && count($day_wise_from_times)) {
                foreach ($day_wise_from_times as $key => $value) {
                    $from_time = strtotime($value);
                }
            }

            if (is_array($day_wise_from_times) && count($day_wise_from_times)) {
                foreach ($day_wise_to_times as $key => $value) {
                    $to_time = strtotime($value);
                }
            }
        }

        if ($from_time) {
            $start_date = isset($_POST['leave_from']) ? sanitize_text_field(convert_to_data_format( $_POST['leave_from'] ) . ' ' . date('H:i:s', $from_time)) : date_i18n('Y-m-d') . ' ' . date('H:i:s', $from_time);
        }
        if ($to_time) {
            $end_date = isset($_POST['leave_to']) ? sanitize_text_field(convert_to_data_format( $_POST['leave_to'] ) . ' ' . date('H:i:s', $to_time)) : date_i18n('Y-m-d') . ' ' . date('H:i:s', $to_time);
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
            $this->send_error(__('Sorry! You do not have any leave left under this leave policy', 'wphr'));
        }

        if ($to_time != 0 && $from_time != 0) {
            $difference_in_minutes = ( $to_time - $from_time ) / 60;
            $length_hours = ( $difference_in_minutes / 60 ) > $working_hours ? $working_hours : ( $difference_in_minutes / 60 );
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


                if (!is_wp_error($request_id[$request_count]) && $request_id[$request_count] != false) {
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
                $this->send_success(__('Leave request has been submitted successfully!', 'wphr'));
            } else {
                if ($request_id[$request_count] == false) {
                    $this->send_error(__('Existing Leave Record found within selected range!', 'wphr'));
                } else {
                    $this->send_error(__('Something went wrong, please try again.', 'wphr'));
                }
            }
        } else {
			wphr_hr_add_custom_leave_entitlements( $employee_id, $leave_policy, $start_date, $end_date );
			
            $request_id = wphr_hr_leave_insert_request(array(
                'user_id' => $employee_id,
                'leave_policy' => $leave_policy,
                'start_date' => $start_date,
                'end_date' => $end_date,
                'reason' => $leave_reason,
                'length_hours' => $length_hours,
                'start_time' => date('H:i:s', $from_time),
                'end_time' => date('H:i:s', $to_time)
            ));

            if (!is_wp_error($request_id) && $request_id != false) {

                // notification email
                $emailer = wphr()->emailer->get_email('New_Leave_Request');

                if (is_a($emailer, '\WPHR\HR_MANAGER\Email')) {
                    $emailer->trigger($request_id);
                }

                $this->send_success(__('Leave request has been submitted successfully!', 'wphr'));
            } else {
                if ($request_id == false) {
                    $this->send_error(__('Existing Leave Record found within selected range!', 'wphr'));
                } else {
                    $this->send_error(__('Something went wrong, please try again.', 'wphr'));
                }
            }
        }
    }

    /**
     * Get employee leave history
     *
     * @since 0.1
     *
     * @return void
     */
    public function get_employee_leave_history() {
        global $wpdb;
        $this->verify_nonce('wphr-hr-empl-leave-history');

        $year = isset($_POST['year']) ? intval(sanitize_text_field($_POST['year']) ) : date('Y');
        $employee_id = isset($_POST['employee_id']) ? intval(sanitize_text_field($_POST['employee_id']) ) : 0;
        $policy = isset($_POST['leave_policy']) ? intval(sanitize_text_field($_POST['leave_policy']) ) : 'all';

        $args = array(
            'year' => $year,
            'user_id' => $employee_id,
            'status' => 1,
            'orderby' => 'req.start_date'
        );

        if ($policy != 'all') {
            $args['policy_id'] = $policy;
        }

        $working_hours = get_employee_working_hours($employee_id);

        $requests = wphr_hr_get_leave_requests($args);

        ob_start();
        include WPHR_HRM_VIEWS . '/employee/tab-leave-history.php';
        $content = ob_get_clean();

        $this->send_success($content);
    }

    /**
     * Resend welcome email
     *
     * @since 0.1.11
     *
     * @return void 
     */
    public function employee_resend_email() {
        global $wpdb;
        $this->verify_nonce('bulk-employees');
        $emailer = wphr()->emailer->get_email('New_Employee_Welcome');
        $send_login = true;
        $employee_id = (int) sanitize_text_field( $_POST['user_id'] );
        if (is_a($emailer, '\WPHR\HR_MANAGER\Email')) {
            $emailer->trigger($employee_id, $send_login);
            $this->send_success(1);
        }
    }

}
