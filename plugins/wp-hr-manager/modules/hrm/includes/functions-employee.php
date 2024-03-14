<?php

/**
 * Delete an employee if removed from WordPress usre table
 *
 * @param  int  the user id
 *
 * @return void
 */
function wphr_hr_employee_on_delete( $user_id, $hard = 0 ) {
    global $wpdb;

    $user = get_user_by( 'id', $user_id );

    if ( ! $user ) {
        return;
    }

    $role = reset( $user->roles );

    if ( 'employee' == $role ) {
        \WPHR\HR_MANAGER\HRM\Models\Employee::where( 'user_id', $user_id )->withTrashed()->forceDelete();
    }
}

/**
 * Create a new employee
 *
 * @param  array  arguments
 *
 * @return int  employee id
 */
function wphr_hr_employee_create( $args = array() ) {
    global $wpdb;

    $defaults = array(
        'user_email'      => '',
        'work'            => array(
            'designation'   => 0,
            'department'    => 0,
            'job_title_detail' => '',
            'location'      => '',
            'hiring_source' => '',
            'hiring_date'   => '',
            'date_of_birth' => '',
            'reporting_to'  => 0,
            'pay_rate'      => '',
            'pay_type'      => '',
            'type'          => '',
            'status'        => '',
        ),
        'personal'        => array(
            'photo_id'        => 0,
            'user_id'         => 0,
            'employee_id'     => '',
            'first_name'      => '',
            'middle_name'     => '',
            'last_name'       => '',
            'other_email'     => '',
            'phone'           => '',
            'work_phone'      => '',
            'mobile'          => '',
            'address'         => '',
            'gender'          => '',
            'marital_status'  => '',
            'nationality'     => '',
            'driving_license' => '',
            'hobbies'         => '',
            'user_url'        => '',
            'description'     => '',
            'street_1'        => '',
            'street_2'        => '',
            'city'            => '',
            'country'         => '',
            'state'           => '',
            'postal_code'     => '',
        )
    );

    $posted = array_map( 'strip_tags_deep', $args );
    $posted = array_map( 'trim_deep', $posted );
    $data   = wphr_parse_args_recursive( $posted, $defaults );
   

    // some basic validation
    if ( empty( $data['personal']['first_name'] ) ) {
        return new WP_Error( 'empty-first-name', __( 'Please provide the first name.', 'wphr' ) );
    }

    if ( empty( $data['personal']['last_name'] ) ) {
        return new WP_Error( 'empty-last-name', __( 'Please provide the last name.', 'wphr' ) );
    }

    if ( ! is_email( $data['user_email'] ) ) {
        return new WP_Error( 'invalid-email', __( 'Please provide a valid email address.', 'wphr' ) );
    }

    // attempt to create the user
    $userdata = array(
        'user_login'   => $data['user_email'],
        'user_email'   => $data['user_email'],
        'first_name'   => $data['personal']['first_name'],
        'middle_name'   => $data['personal']['middle_name'],
        'last_name'    => $data['personal']['last_name'],
        'user_url'     => $data['personal']['user_url'],
        'display_name' => $data['personal']['first_name'] . ' ' . $data['personal']['middle_name'] . ' ' . $data['personal']['last_name'],
    );

    // if user id exists, do an update
    $user_id = isset( $posted['user_id'] ) ? intval( $posted['user_id'] ) : 0;
    $update  = false;

    if ( $user_id ) {
        $update = true;
        $userdata['ID'] = $user_id;

    } else {
        // when creating a new user, assign role and passwords
        $userdata['user_pass'] = wp_generate_password( 12 );
        $userdata['role'] = 'employee';
    }

    $userdata = apply_filters( 'wphr_hr_employee_args', $userdata );

    $wp_user = get_user_by( 'email', $userdata['user_login'] );

    /**
     * We hook `wphr_hr_existing_role_to_employee` to the `set_user_role` action
     * in action-fiters.php file. Since we have set `$userdata['role'] = 'employee'`
     * after insert/update a wp user, `wphr_hr_existing_role_to_employee` function will
     * create an employee immediately
     */
    if ( $wp_user ) {
        unset( $userdata['user_url'] );
        unset( $userdata['user_pass'] );
        $userdata['ID'] = $wp_user->ID;

        $user_id = wp_update_user( $userdata );

    } else {
        $user_id  = wp_insert_user( $userdata );
    }

    if ( is_wp_error( $user_id ) ) {
        return $user_id;
    }

    // if reached here, seems like we have success creating the user
    $employee = new \WPHR\HR_MANAGER\HRM\Employee( $user_id );

    // inserting the user for the first time
    $hiring_date = ! empty( $data['work']['hiring_date'] ) ? $data['work']['hiring_date'] : current_time( 'mysql' );
    if ( ! $update ) {

        $work        = $data['work'];

        if ( ! empty( $work['type'] ) ) {
            $employee->update_employment_status( $work['type'], $hiring_date );
        }

        // update compensation
        if ( ! empty( $work['pay_rate'] ) ) {
            $pay_type = ( ! empty( $work['pay_type'] ) ) ? $work['pay_type'] : 'monthly';
            $employee->update_compensation( $work['pay_rate'], $pay_type, '', $hiring_date );
        }

        // update job info
        $employee->update_job_info( $work['department'], $work['designation'], $work['reporting_to'], $work['location'], $hiring_date );
    }


    $employee_table_data = array(
        'hiring_source' => $data['work']['hiring_source'],
        'job_title_detail' => $data['work']['job_title_detail'],
        'hiring_date'   => convert_to_data_format( $hiring_date ),
        'date_of_birth' => isset( $data['work']['date_of_birth'] ) ? convert_to_data_format( $data['work']['date_of_birth'] ) : '',
        'employee_id'   => $data['personal']['employee_id'],
        'location'      => $data['work']['location'],
        'reporting_to'  => $data['work']['reporting_to'],
        'manage_leave_by_reporter' => isset( $data['work']['manage_leave_by_reporter'] ) ? $data['work']['manage_leave_by_reporter'] : '',
        'anniversary_permission' => isset( $data['work']['anniversary_permission'] ) ? $data['work']['anniversary_permission'] : '',
        'work_permission' => isset( $data['work']['work_permission'] ) ? $data['work']['work_permission'] : '',
        'inout_office' => isset( $data['work']['inout_office'] ) ? $data['work']['inout_office'] : '',

        'send_mail_to_reporter' => isset( $data['work']['send_mail_to_reporter'] ) ? $data['work']['send_mail_to_reporter'] : '',
    );

    // employees should not be able to change hiring date, unset when their profile
    if ( $update && ! current_user_can( wphr_hr_get_manager_role() ) ) {
        unset( $employee_table_data['hiring_date'] );
    }

    if ( ! $update ) {
        $employee_table_data['status'] = $data['work']['status'];
    }

    // update the wphr table
    $employee_row_id = $wpdb->get_var( $wpdb->prepare('SELECT id FROM '.$wpdb->prefix . 'wphr_hr_employees WHERE user_id = %d', $user_id ));
    if( $employee_row_id ){
        $wpdb->update( $wpdb->prefix . 'wphr_hr_employees', $employee_table_data, array( 'user_id' => $user_id ) );
    }else{
        $employee_table_data['user_id'] = $user_id;
        $wpdb->insert( $wpdb->prefix . 'wphr_hr_employees', $employee_table_data );
    }
    // update the wphr leave table
        $employee_leave_data=array('user_id'=>isset( $data['work']['inout_office'] ) ? $data['work']['inout_office'] : '');
        isset( $data['work']['inout_office'] ) ? $data['work']['inout_office'] : '';

    $employee_row_id1 = $wpdb->get_var( $wpdb->prepare('SELECT id FROM '.$wpdb->prefix .'wphr_hr_leave_requests WHERE user_id = %d', $user_id ));
    /*if( $employee_row_id1){
        $wpdb->update( $wpdb->prefix . 'wphr_hr_leave_requests', $employee_table_data, array( 'user_id' => $user_id ) );
    }
    else
    {
        $employee_table_data['user_id'] = $user_id;
        $wpdb->insert( $wpdb->prefix . 'wphr_hr_leave_requests', $employee_table_data );
    }*/
    foreach ( $data['personal'] as $key => $value ) {

        if ( in_array( $key, [ 'employee_id', 'user_url' ] ) ) {
            continue;
        }

        update_user_meta( $user_id, $key, $value );
    }

    if ( $update ) {
        do_action( 'wphr_hr_employee_update', $user_id, $data );
    } else {
        do_action( 'wphr_hr_employee_new', $user_id, $data );
    }

    return $user_id;
}


/**
 * Get all employees from a company
 *
 * @param  int   $company_id  company id
 * @param bool $no_object     if set true, Employee object will be
 *                            returned as array. $wpdb rows otherwise
 *
 * @return array  the employees
 */
function wphr_hr_get_employees( $args = array() ) {
    global $wpdb;

    $defaults = array(
        'number'     => 20,
        'offset'     => 0,
        'orderby'    => 'hiring_date',
        'order'      => 'DESC',
        'no_object'  => false,
        'count'      => false
    );

    $args  = wp_parse_args( $args, $defaults );
    $where = array();

    $employee_tbl = $wpdb->prefix . 'wphr_hr_employees';
    $employees = \WPHR\HR_MANAGER\HRM\Models\Employee::select( array( $employee_tbl. '.user_id', 'display_name' ) )
                    ->leftJoin( $wpdb->users, $employee_tbl . '.user_id', '=', $wpdb->users . '.ID' );

    if ( isset( $args['designation'] ) && $args['designation'] != '-1' ) {
        $employees = $employees->where( 'designation', $args['designation'] );
    }

    if ( isset( $args['department'] ) && $args['department'] != '-1' ) {
        $employees = $employees->where( 'department', $args['department'] );
    }

    if ( isset( $args['location'] ) && $args['location'] != '-1' ) {
        $employees = $employees->where( 'location', $args['location'] );
    }

    if ( isset( $args['type'] ) && $args['type'] != '-1' ) {
        $employees = $employees->where( 'type', $args['type'] );
    }
    
    if ( isset( $args['reporting_to'] ) ){
        $employees = $employees->where( 'reporting_to', '>', 0 );
    }

    if ( isset( $args['status'] ) && ! empty( $args['status'] ) ) {
        if ( $args['status'] == 'trash' ) {
            $employees = $employees->onlyTrashed();
        } else {
            if ( $args['status'] != 'all' ) {
                $employees = $employees->where( 'status', $args['status'] );
            }
        }
    } else {
        $employees = $employees->where( 'status', 'active' );
    }

    if ( isset( $args['s'] ) && ! empty( $args['s'] ) ) {
        $arg_s = $args['s'];
        $employees = $employees->where( 'display_name', 'LIKE', "%$arg_s%" );
    }

    if ( 'employee_name' === $args['orderby'] ) {
        $employees = $employees->leftJoin( $wpdb->usermeta .' as umeta', function ( $join ) use ( $wpdb, $employee_tbl ) {
                        $join->on( $employee_tbl . '.user_id', '=', 'umeta.user_id' )
                             ->where( 'umeta.meta_key', '=', 'first_name' );
                     } );

        $args['orderby'] = 'umeta.meta_value';
    }

    $cache_key = 'wphr-get-employees-' . md5( serialize( $args ) );
    $results   = wp_cache_get( $cache_key, 'wphr' );
    $users     = array();

    // Check if want all data without any pagination
    if ( $args['number'] != '-1' && ! $args['count'] ) {
        $employees = $employees->skip( $args['offset'] )->take( $args['number'] );
    }

    // Check if args count true, then return total count customer according to above filter
    if ( $args['count'] ) {
        return $employees->count();
    }

    if ( false === $results ) {

        $results = $employees
                    ->orderBy( $args['orderby'], $args['order'] )
                    ->get()
                    ->toArray();

        $results = wphr_array_to_object( $results );
        wp_cache_set( $cache_key, $results, 'wphr', HOUR_IN_SECONDS );
    }

    if ( $results ) {
        foreach ($results as $key => $row) {

            if ( true === $args['no_object'] ) {
                $users[] = $row;
            } else {
                $users[] = new \WPHR\HR_MANAGER\HRM\Employee( intval( $row->user_id ) );
            }
        }
    }

    return $users;
}


/**
 * Get all employees from a company
 *
 * @param  int   $company_id  company id
 * @param bool $no_object     if set true, Employee object will be
 *                            returned as array. $wpdb rows otherwise
 *
 * @return array  the employees
 */
function wphr_hr_count_employees() {

    $where = array();

    $employee = new \WPHR\HR_MANAGER\HRM\Models\Employee();

    if ( isset( $args['designation'] ) && ! empty( $args['designation'] ) ) {
        $designation = array( 'designation' => $args['designation'] );
        $where = array_merge( $designation, $where );
    }

    if ( isset( $args['department'] ) && ! empty( $args['department'] ) ) {
        $department = array( 'department' => $args['department'] );
        $where = array_merge( $where, $department );
    }

    if ( isset( $args['location'] ) && ! empty( $args['location'] ) ) {
        $location = array( 'location' => $args['location'] );
        $where = array_merge( $where, $location );
    }

    if ( isset( $args['status'] ) && ! empty( $args['status'] ) ) {
        $status = array( 'status' => $args['status'] );
        $where = array_merge( $where, $status );
    }

    $counts = $employee->where( $where )->count();

    return $counts;
}


/**
 * Get Employee status count
 *
 * @since 0.1
 *
 * @return array
 */
function wphr_hr_employee_get_status_count() {
    global $wpdb;

    $statuses = array( 'all' => __( 'All', 'wphr' ) ) + wphr_hr_get_employee_statuses();
    $counts   = array();

    foreach ( $statuses as $status => $label ) {
        $counts[ $status ] = array( 'count' => 0, 'label' => $label );
    }

    $cache_key = 'wphr-hr-employee-status-counts';
    $results = wp_cache_get( $cache_key, 'wphr' );

    if ( false === $results ) {

        $employee = new \WPHR\HR_MANAGER\HRM\Models\Employee();
        $db = new \WPHR\ORM\Eloquent\Database();

        $results = $employee->select( array( 'status', $db->raw('COUNT(id) as num') ) )
                            ->where( 'status', '!=', '0' )
                            ->groupBy('status')
                            ->get()->toArray();

        wp_cache_set( $cache_key, $results, 'wphr' );
    }

    foreach ( $results as $row ) {
        if ( array_key_exists( $row['status'], $counts ) ) {
            $counts[ $row['status'] ]['count'] = (int) $row['num'];
        }

        $counts['all']['count'] += (int) $row['num'];
    }

    return $counts;
}

/**
 * Count trash employee
 *
 * @since 0.1
 *
 * @return int [no of trash employee]
 */
function wphr_hr_count_trashed_employees() {
    $employee = new \WPHR\HR_MANAGER\HRM\Models\Employee();

    return $employee->onlyTrashed()->count();
}

/**
 * Employee Restore from trash
 *
 * @since 0.1
 *
 * @param  array|int $employee_ids
 *
 * @return void
 */
function wphr_employee_restore( $employee_ids ) {
    if ( empty( $employee_ids ) ) {
        return;
    }

    if ( is_array( $employee_ids ) ) {
        foreach ( $employee_ids as $key => $user_id ) {
            \WPHR\HR_MANAGER\HRM\Models\Employee::withTrashed()->where( 'user_id', $user_id )->restore();
        }
    }

    if ( is_int( $employee_ids ) ) {
        \WPHR\HR_MANAGER\HRM\Models\Employee::withTrashed()->where( 'user_id', $employee_ids )->restore();
    }
}

/**
 * Employee Delete
 *
 * @since 1.0.0
 * @since 1.2.0 After delete an employee, remove HR roles instead of
 *              remove the related wp user
 *
 * @param  array|int $employee_ids
 *
 * @return void
 */
function wphr_employee_delete( $employee_ids, $hard = false ) {

    if ( empty( $employee_ids ) ) {
        return;
    }

    $employees = [];

    if ( is_array( $employee_ids ) ) {
        foreach ( $employee_ids as $key => $user_id ) {
            $employees[] = $user_id;
        }
    } else if ( is_int( $employee_ids ) ) {
        $employees[] = $employee_ids;
    }

    // still do we have any ids to delete?
    if ( ! $employees ) {
        return;
    }

    // seems like we got some
    foreach ( $employees as $employee_wp_user_id ) {

        do_action( 'wphr_hr_delete_employee', $employee_wp_user_id, $hard );
        $ver = (float)phpversion();
        if ( $hard || $ver >= 8.0 ) {
            \WPHR\HR_MANAGER\HRM\Models\Employee::where( 'user_id', $employee_wp_user_id )->withTrashed()->forceDelete();
            $wp_user = get_userdata( $employee_wp_user_id );
            $wp_user->remove_role( wphr_hr_get_manager_role() );
            $wp_user->remove_role( wphr_hr_get_employee_role() );

            // find leave entitlements and leave requests and delete them as well
            \WPHR\HR_MANAGER\HRM\Models\Leave_request::where( 'user_id', '=', $employee_wp_user_id )->delete();
            \WPHR\HR_MANAGER\HRM\Models\Leave_Entitlement::where( 'user_id', '=', $employee_wp_user_id )->delete();

        } else {
            \WPHR\HR_MANAGER\HRM\Models\Employee::where( 'user_id', $employee_wp_user_id )->delete();
        }

        do_action( 'wphr_hr_after_delete_employee', $employee_wp_user_id, $hard );
    }

}


/**
 * Get Todays Anniversary
 *
 * @since 0.1
 * @since 1.1.14 Add where condition to remove terminated employees
 *
 * @return object collection of user_id
 */
function wphr_hr_get_todays_anniversary() {

    $db = new \WPHR\ORM\Eloquent\Database();

     return wphr_array_to_object( \WPHR\HR_MANAGER\HRM\Models\Employee::select('user_id')
            ->where( $db->raw("DATE_FORMAT( `hiring_date`, '%m %d' )" ), \Carbon\Carbon::today()->format('m d') )
            ->where( 'termination_date', '0000-00-00' )
             ->where('anniversary_permission','on')
            ->get()
            ->toArray() );
}

/**
 * Get next seven days Anniversary
 *
 * @since 0.1
 * @since 1.1.14 Add where condition to remove terminated employees
 *
 * @return object user_id, date_of_birth
 */
function wphr_hr_get_next_seven_days_anniversary() {

    $db = new \WPHR\ORM\Eloquent\Database();

    return wphr_array_to_object( \WPHR\HR_MANAGER\HRM\Models\Employee::select( array( 'user_id', 'date_of_birth' ) )
            ->where( $db->raw("DATE_FORMAT( `hiring_date`, '%m %d' )" ), '>', \Carbon\Carbon::today()->format('m d') )
            ->where( $db->raw("DATE_FORMAT( `hiring_date`, '%m %d' )" ), '<=', \Carbon\Carbon::tomorrow()->addWeek()->format('m d') )
            ->where( 'termination_date', '0000-00-00' )
             ->where('anniversary_permission','on')
            ->get()
            ->toArray() );
}





/**
 * Get Todays Birthday
 *
 * @since 0.1
 * @since 1.1.14 Add where condition to remove terminated employees
 *
 * @return object collection of user_id
 */
function wphr_hr_get_todays_birthday() {

    $db = new \WPHR\ORM\Eloquent\Database();

    return wphr_array_to_object( \WPHR\HR_MANAGER\HRM\Models\Employee::select('user_id')
            ->where( $db->raw("DATE_FORMAT( `date_of_birth`, '%m %d' )" ), \Carbon\Carbon::today()->format('m d') )
            ->where( 'termination_date', '0000-00-00' )
             ->where('anniversary_permission','on')
            ->get()
            ->toArray() );
}

/**
 * Get next seven days birthday
 *
 * @since 0.1
 * @since 1.1.14 Add where condition to remove terminated employees
 *
 * @return object user_id, date_of_birth
 */
function wphr_hr_get_next_seven_days_birthday() {

    $db = new \WPHR\ORM\Eloquent\Database();

    return wphr_array_to_object( \WPHR\HR_MANAGER\HRM\Models\Employee::select( array( 'user_id', 'date_of_birth' ) )
            ->where( $db->raw("DATE_FORMAT( `date_of_birth`, '%m %d' )" ), '>', \Carbon\Carbon::today()->format('m d') )
            ->where( $db->raw("DATE_FORMAT( `date_of_birth`, '%m %d' )" ), '<=', \Carbon\Carbon::tomorrow()->addWeek()->format('m d') )
            ->where( 'termination_date', '0000-00-00' )
             ->where('anniversary_permission','on')
            ->get()
            ->toArray() );
}

/**
 * Get the raw employees dropdown
 *
 * @param  int  company id
 *
 * @return array  the key-value paired employees
 */
function wphr_hr_get_employees_dropdown_raw( $exclude = null, $include = array() ) {
    $employees = wphr_hr_get_employees( [ 'number' => -1 , 'no_object' => true ] );
    $dropdown  = array( 0 => __( '- Select Employee -', 'wphr' ) );

    if ( $employees ) {
        foreach ($employees as $key => $employee) {
            if ( $exclude && $employee->user_id == $exclude ) {
                continue;
            }
            if( is_array( $include ) && count( $include ) ){
                if( in_array( $employee->user_id, $include ) ){
                    $dropdown[$employee->user_id] = $employee->display_name;    
                }   
            }else{
                $dropdown[$employee->user_id] = $employee->display_name;    
            }
        }
    }

    return $dropdown;
}

/**
 * Get company employees dropdown
 *
 * @param  int  company id
 * @param  string  selected department
 *
 * @return string  the dropdown
 */
function wphr_hr_get_employees_dropdown( $selected = '' ) {
    $employees = wphr_hr_get_employees_dropdown_raw();
    $dropdown  = '';

    if ( $employees ) {
        foreach ($employees as $key => $title) {
            $dropdown .= sprintf( "<option value='%s'%s>%s</option>\n", $key, selected( $selected, $key, false ), $title );
        }
    }

    return $dropdown;
}

/**
 * Get the registered employee statuses
 *
 * @return array the employee statuses
 */
function wphr_hr_get_employee_statuses() {
    $statuses = array(
        'active'     => __( 'Active', 'wphr' ),
        'terminated' => __( 'Terminated', 'wphr' ),
        'deceased'   => __( 'Deceased', 'wphr' ),
        'resigned'   => __( 'Resigned', 'wphr' )
    );

    return apply_filters( 'wphr_hr_employee_statuses', $statuses );
}

/**
 * Get the registered employee statuses
 *
 * @return array the employee statuses
 */
function wphr_hr_get_employee_statuses_icons( $selected = NULL ) {
    $statuses = apply_filters( 'wphr_hr_employee_statuses_icons', array(
        'active'     => sprintf( '<span class="wphr-tips dashicons dashicons-yes" title="%s"></span>', __( 'Active', 'wphr' ) ),
        'terminated' => sprintf( '<span class="wphr-tips dashicons dashicons-dismiss" title="%s"></span>', __( 'Terminated', 'wphr' ) ),
        'deceased'   => sprintf( '<span class="wphr-tips dashicons dashicons-marker" title="%s"></span>', __( 'Deceased', 'wphr' ) ),
        'resigned'   => sprintf( '<span class="wphr-tips dashicons dashicons-warning" title="%s"></span>', __( 'Resigned', 'wphr' ) )
    ) );

    if ( $selected && array_key_exists( $selected, $statuses ) ) {
        return $statuses[$selected];
    }

    return false;
}


/**
 * Get the registered employee statuses
 *
 * @return array the employee statuses
 */
function wphr_hr_get_employee_types() {
    $types = array(
        'permanent' => __( 'Full Time', 'wphr' ),
        'parttime'  => __( 'Part Time', 'wphr' ),
        'contract'  => __( 'On Contract', 'wphr' ),
        'temporary' => __( 'Temporary', 'wphr' ),
        'trainee'   => __( 'Trainee', 'wphr' )
    );

    return apply_filters( 'wphr_hr_employee_types', $types );
}

/**
 * Get the registered employee hire sources
 *
 * @return array the employee hire sources
 */
function wphr_hr_get_employee_sources() {
    $sources = array(
        'direct'        => __( 'Direct', 'wphr' ),
        'referral'      => __( 'Referral', 'wphr' ),
        'web'           => __( 'Web', 'wphr' ),
        'newspaper'     => __( 'Newspaper', 'wphr' ),
        'advertisement' => __( 'Advertisement', 'wphr' ),
        'social'        => __( 'Social Network', 'wphr' ),
        'other'         => __( 'Other', 'wphr' ),
    );

    return apply_filters( 'wphr_hr_employee_sources', $sources );
}

/**
 * Get marital statuses
 *
 * @return array all the statuses
 */
function wphr_hr_get_marital_statuses( $select_text = null ) {

    if ( $select_text ) {
        $statuses = array(
            '-1'      => $select_text,
            'single'  => __( 'Single', 'wphr' ),
            'married' => __( 'Married', 'wphr' ),
            'widowed' => __( 'Widowed', 'wphr' )
        );
    } else {
        $statuses = array(
            'single'  => __( 'Single', 'wphr' ),
            'married' => __( 'Married', 'wphr' ),
            'widowed' => __( 'Widowed', 'wphr' )
        );
    }

    return apply_filters( 'wphr_hr_marital_statuses',  $statuses );
}

/**
 * Get Terminate Type
 *
 * @return array all the type
 */
function wphr_hr_get_terminate_type( $selected = NULL ) {
    $type = apply_filters( 'wphr_hr_terminate_type', [
        'voluntary'   => __( 'Voluntary', 'wphr' ),
        'involuntary' => __( 'Involuntary', 'wphr' )
    ] );

    if ( $selected ) {
        return ( isset( $type[$selected] ) ) ? $type[$selected] : '';
    }

    return $type;
}

/**
 * Get Terminate Reason
 *
 * @return array all the reason
 */
function wphr_hr_get_terminate_reason( $selected = NULL ) {
    $reason = apply_filters( 'wphr_hr_terminate_reason', [
        'attendance'            => __( 'Attendance', 'wphr' ),
        'better_employment'     => __( 'Better Employment Conditions', 'wphr' ),
        'career_prospect'       => __( 'Career Prospect', 'wphr' ),
        'death'                 => __( 'Death', 'wphr' ),
        'desertion'             => __( 'Desertion', 'wphr' ),
        'dismissed'             => __( 'Dismissed', 'wphr' ),
        'dissatisfaction'       => __( 'Dissatisfaction with the job', 'wphr' ),
        'higher_pay'            => __( 'Higher Pay', 'wphr' ),
        'other_employement'     => __( 'Other Employment', 'wphr' ),
        'personality_conflicts' => __( 'Personality Conflicts', 'wphr' ),
        'relocation'            => __( 'Relocation', 'wphr' ),
        'retirement'            => __( 'Retirement', 'wphr' ),
    ] );

    if ( $selected ) {
        return ( isset( $reason[$selected] ) ) ? $reason[$selected] : '';
    }

    return $reason;
}

/**
 * Get Terminate Reason
 *
 * @return array all the reason
 */
function wphr_hr_get_terminate_rehire_options( $selected = NULL ) {
    $reason = apply_filters( 'wphr_hr_terminate_rehire_option', array(
        'yes'         => __( 'Yes', 'wphr' ),
        'no'          => __( 'No', 'wphr' ),
        'upon_review' => __( 'Upon Review', 'wphr' )
    ) );

    if ( $selected ) {
        return ( isset( $reason[$selected] ) ) ? $reason[$selected] : '';
    }

    return $reason;
}

/**
 * Employee terminated action
 *
 * @since 1.0
 *
 * @param  array $data
 *
 * @return void | WP_Error
 */
function wphr_hr_employee_terminate( $data ) {

    if ( ! $data['terminate_date'] ) {
        return new WP_Error( 'no-date', 'Termination date is required' );
    }

    if ( ! $data['termination_type'] ) {
        return new WP_Error( 'no-type', 'Termination type is required' );
    }

    if ( ! $data['termination_reason'] ) {
        return new WP_Error( 'no-reason', 'Termination reason is required' );
    }

    if ( ! $data['eligible_for_rehire'] ) {
        return new WP_Error( 'no-eligible-for-rehire', 'Eligible for rehire field is required' );
    }

    $result = \WPHR\HR_MANAGER\HRM\Models\Employee::where( 'user_id', $data['employee_id'] )->update( [ 'status'=>'terminated', 'termination_date' => $data['terminate_date'] ] );

    $comments = sprintf( '%s: %s; %s: %s; %s: %s',
                        __( 'Termination Type', 'wphr' ),
                        wphr_hr_get_terminate_type( $data['termination_type'] ),
                        __( 'Termination Reason', 'wphr' ),
                        wphr_hr_get_terminate_reason( $data['termination_reason'] ),
                        __( 'Eligible for Hire', 'wphr' ),
                        wphr_hr_get_terminate_rehire_options( $data['eligible_for_rehire'] ) );

    wphr_hr_employee_add_history( [
        'user_id'  => $data['employee_id'],
        'module'   => 'employment',
        'category' => '',
        'type'     => 'terminated',
        'comment'  => $comments,
        'data'     => '',
        'date'     => $data['terminate_date']
    ] );

    update_user_meta( $data['employee_id'], '_wphr_hr_termination', $data );

    return $result;
}

/**
 * Get marital statuses
 *
 * @return array all the statuses
 */
function wphr_hr_get_genders( $select_text = null ) {

    if ( $select_text ) {
        $genders = array(
            '-1'     => $select_text,
            'male'   => __( 'Male', 'wphr' ),
            'female' => __( 'Female', 'wphr' ),
            'other'  => __( 'Other', 'wphr' )
        );
    } else {
        $genders = array(
            'male'   => __( 'Male', 'wphr' ),
            'female' => __( 'Female', 'wphr' ),
            'other'  => __( 'Other', 'wphr' )
        );
    }

    return apply_filters( 'wphr_hr_genders', $genders );
}

/**
 * Get marital statuses
 *
 * @return array all the statuses
 */
function wphr_hr_get_pay_type() {
    $types = array(
        'hourly'   => __( 'Hourly', 'wphr' ),
        'daily'    => __( 'Daily', 'wphr' ),
        'weekly'   => __( 'Weekly', 'wphr' ),
        'biweekly' => __( 'Biweekly', 'wphr' ),
        'monthly'  => __( 'Monthly', 'wphr' ),
        'contract' => __( 'Contract', 'wphr' ),
    );

    return apply_filters( 'wphr_hr_pay_type', $types );
}

/**
 * Get marital statuses
 *
 * @return array all the statuses
 */
function wphr_hr_get_pay_change_reasons() {
    $reasons = array(
        'promotion'   => __( 'Promotion', 'wphr' ),
        'performance' => __( 'Performance', 'wphr' ),
        'increment'   => __( 'Increment', 'wphr' )
    );

    return apply_filters( 'wphr_hr_pay_change_reasons', $reasons );
}

/**
 * Add a new item in employee history table
 *
 * @param  array   $args
 *
 * @return void
 */
function wphr_hr_employee_add_history( $args = array() ) {
    global $wpdb;

    $defaults = array(
        'user_id'  => 0,
        'module'   => '',
        'category' => '',
        'type'     => '',
        'comment'  => '',
        'data'     => '',
        'date'     => current_time( 'mysql' ),
        'additional'     => '',
    );

     $data = wp_parse_args( $args, $defaults );
   //  print_r($data);
      //die();
    
    $format = array(
        '%d',
        '%s',
        '%s',
        '%s',
        '%s',
        '%s',
        '%s',
        '%s'
    );

    $wpdb->insert( $wpdb->prefix . 'wphr_hr_employee_history', $data, $format );
}

/**
 * Remove an item from the history
 *
 * @param  int  $history_id
 *
 * @return bool
 */
function wphr_hr_employee_remove_history( $history_id ) {
    global $wpdb;

    return $wpdb->delete( $wpdb->prefix . 'wphr_hr_employee_history', array( 'id' => $history_id ) );
}

/**
 * Individual employee url
 *
 * @param  int  employee id
 *
 * @return string  url of the employee details page
 */
function wphr_hr_url_single_employee( $employee_id, $tab = null ) {
    if ( $tab ) {
        $tab = '&tab=' . $tab;
    }

    $user = wp_get_current_user();

    if (in_array( 'employee' , (array) $user->roles)) {
        $url = admin_url( 'admin.php?page=wphr-hr-my-profile&action=view&id=' . $employee_id . $tab );
    } else {
        $url = admin_url( 'admin.php?page=wphr-hr-employee&action=view&id=' . $employee_id . $tab );
    }

    return apply_filters( 'wphr_hr_url_single_employee', $url, $employee_id );
}

/**
 * Individual employee tab url
 *
 * @param string $tab
 * @param int employee id
 *
 * @since  1.1.10
 *
 * @return string
 */
function wphr_hr_employee_tab_url( $tab, $employee_id ) {
    $emp_url = wphr_hr_url_single_employee( intval( $employee_id ) );
    $tab_url = add_query_arg( array( 'tab' => $tab ), $emp_url );

    return apply_filters( 'wphr_hr_employee_tab_url', $tab_url, $tab, $employee_id );
}

/**
 * Get Employee Announcement List
 *
 * @since 0.1
 *
 * @param  integer $user_id
 *
 * @return array
 */
function wphr_hr_employee_dashboard_announcement( $user_id ) {
    global $wpdb;

    return wphr_array_to_object( \WPHR\HR_MANAGER\HRM\Models\Announcement::join( $wpdb->posts, 'post_id', '=', $wpdb->posts . '.ID' )
            ->where( 'user_id', '=', $user_id )
            ->orderby( $wpdb->posts . '.post_date', 'desc' )
            ->take(8)
            ->get()
            ->toArray() );
}

/**
 * [wphr_hr_employee_single_tab_general description]
 *
 * @return void
 */
function wphr_hr_employee_single_tab_general( $employee ) {
    include WPHR_HRM_VIEWS . '/employee/tab-general.php';
}

/**
 * [wphr_hr_employee_single_tab_job description]
 *
 * @return void
 */
function wphr_hr_employee_single_tab_job( $employee ) {
    include WPHR_HRM_VIEWS . '/employee/tab-job.php';
}

/**
 * [wphr_hr_employee_single_tab_leave description]
 *
 * @return void
 */
function wphr_hr_employee_single_tab_leave( $employee ) {
    include WPHR_HRM_VIEWS . '/employee/tab-leave.php';
}

/**
 * [wphr_hr_employee_single_tab_notes description]
 *
 * @return void
 */
function wphr_hr_employee_single_tab_notes( $employee ) {
    include WPHR_HRM_VIEWS . '/employee/tab-notes.php';
}

/**
 * [wphr_hr_employee_single_tab_performance description]
 *
 * @return void
 */
function wphr_hr_employee_single_tab_performance( $employee ) {
    include WPHR_HRM_VIEWS . '/employee/tab-performance.php';
}

/**
 * [wphr_hr_employee_single_tab_permission description]
 *
 * @return void
 */
function wphr_hr_employee_single_tab_permission( $employee ) {
    include WPHR_HRM_VIEWS . '/employee/tab-permission.php';
}
/**
 * [wphr_hr_employee_single_tab_permission description]
 *
 * @return void
 */
function wphr_hr_employee_single_tab_documents( $employee ) {
    include WPHR_HRM_VIEWS . '/employee/tab-documents.php';
}


/**
 * restrict and extend user capabities
 *
 * @return array
 */
function wphr_user_get_caps_for_role( $caps = array(), $cap = '', $args = array(), $obj ){
    $employee_details = wp_get_current_user();
    if( $employee_details->ID ){
        $employee_id = $employee_details->ID;
        if( in_array( wphr_hr_get_manager_role(), $employee_details->roles ) ){
            $is_manage_leave_of_employees = get_user_meta( $employee_id, 'manage_leave_of_employees', true );
            $is_receive_mail_for_leaves = get_user_meta( $employee_id, 'receive_mail_for_leaves', true );
            if( $is_manage_leave_of_employees != '' && $is_manage_leave_of_employees == 0 ){
                unset( $caps['wphr_leave_create_request'] );
                unset( $caps['wphr_leave_manage'] );
            }
            if( $is_receive_mail_for_leaves != '' && $is_receive_mail_for_leaves == 0 ){
                unset( $caps['wphr_leave_mails'] );
            }
            if( $is_manage_leave_of_employees == 1 ){
                $caps['wphr_leave_create_request'] = true;
                $caps['wphr_leave_manage'] = true;
            }
            if( $is_receive_mail_for_leaves == 1 ){
                $caps['wphr_leave_mails'] = true;
            }
        }elseif( in_array( wphr_hr_get_employee_role(), $employee_details->roles ) ){
            if( get_users_under_line_manager( $employee_id, true ) ){
                $caps['wphr_leave_manage'] = true;
            }
        }
    }
    return $caps;
}


/**
 * check line manager has leave managment tab
 *
 * @since 0.1.8
 *
 * @param $user_id int loggin use id
 * @return boolean
 */
function get_users_under_line_manager( $user_id = 0, $count = false ){
    if( $user_id ){
        $user_id = get_current_user_id();
    }
    $employees = \WPHR\HR_MANAGER\HRM\Models\Employee::select( 'user_id' );
    $employees = $employees->where( 'reporting_to', $user_id );
    $employees = $employees->where( 'manage_leave_by_reporter', 'on' ); 
    if( $count ){
        return $employees->count();
    }

    $results = $employees
                ->get()
                ->toArray();

    $results = wphr_array_to_object( $results );
    $users = array();
    if ( $results ) {
        foreach ($results as $key => $row) {
            $users[] = $row->user_id;
        }
    }
    return $users;
}
