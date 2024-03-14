<?php

/**
 * Get company work days
 *
 * @since 1.0.0
 * @since 1.1.14 Using settings saved in wphr Settings > HR > Workdays tab
 *
 * @return array
 */
function wphr_hr_get_work_days() {
    $default = [
        'mon' => 8,
        'tue' => 8,
        'wed' => 8,
        'thu' => 8,
        'fri' => 8,
        'sat' => 0,
        'sun' => 0
    ];

    $option_key = 'wphr_settings_wphr-hr_workdays';

    $wizard_settings = get_option( $option_key, $default );

    return [
        'mon' => get_option( 'mon', $wizard_settings['mon'] ),
        'tue' => get_option( 'tue', $wizard_settings['tue'] ),
        'wed' => get_option( 'wed', $wizard_settings['wed'] ),
        'thu' => get_option( 'thu', $wizard_settings['thu'] ),
        'fri' => get_option( 'fri', $wizard_settings['fri'] ),
        'sat' => get_option( 'sat', $wizard_settings['sat'] ),
        'sun' => get_option( 'sun', $wizard_settings['sun'] )
    ];
}

/**
 * Get working day without off day
 *
 * @since  0.1
 *
 * @param  string $start_date
 * @param  string $end_date
 *
 * @return array
 */
function wphr_hr_get_work_days_without_off_day( $start_date, $end_date ) {

    $between_dates = wphr_extract_dates( $start_date, $end_date );

    if ( is_wp_error( $between_dates ) ) {
        return $between_dates;
    }

    $dates         = array( 'days' => array(), 'total' => 0 );
    $work_days     = wphr_hr_get_work_days();
    $holiday_exist = wphr_hr_leave_get_holiday_between_date_range( $start_date, $end_date );

    foreach ( $between_dates as $date ) {

        $key       = strtolower( date( 'D', strtotime( $date ) ) );
        $is_holidy = ( $work_days[$key] =='0' ) ? true : false;

        if ( ! $is_holidy ) {
            $is_holidy = in_array( $date, $holiday_exist ) ? true : false;
        }

        if ( ! $is_holidy ) {

            $dates['days'][] = array(
                'date'  => $date,
                'count' => (int) ! $is_holidy
            );

            $dates['total'] += 1;
        }
    }

    return $dates;
}

/**
 * Get working day with off day
 *
 * @since  0.1
 *
 * @param  string $start_date
 * @param  string $end_date
 *
 * @return array
 */
function wphr_hr_get_work_days_between_dates( $start_date, $end_date, $employee_id = 0) {

    $between_dates = wphr_extract_dates( $start_date, $end_date );
    
    if ( is_wp_error( $between_dates ) ) {
        return $between_dates;
    }

    $dates         = array( 'days' => array(), 'total' => 0 );
    $work_days     = wphr_hr_get_work_days();
    $holiday_exist = wphr_hr_leave_get_holiday_between_date_range( $start_date, $end_date, $employee_id );

    foreach ( $between_dates as $date ) {

        $key       = strtolower( date( 'D', strtotime( $date ) ) );
        $is_holidy = ( $work_days[$key] == '0' ) ? true : false;

        if ( ! $is_holidy ) {
            $is_holidy = in_array( $date, $holiday_exist ) ? true : false;
        }

        $dates['days'][] = array(
            'date'  => $date,
            'count' => (int) ! $is_holidy
        );

        if ( ! $is_holidy ) {
            $dates['total'] += 1;
        }
    }

    return $dates;
}



/**
 * Sort parents before children
 *
 * @since 1.0
 *
 * @param array   $objects input objects with attributes 'id' and 'parent'
 * @param array   $result  (optional, reference) internal
 * @param integer $parent  (optional) internal
 * @param integer $depth   (optional) internal
 *
 * @return array           output
 */
function wphr_parent_sort( array $objects, array &$result=array(), $parent=0, $depth=0 ) {
    foreach ($objects as $key => $object) {
        if ($object->parent == $parent) {
            $object->depth = $depth;
            array_push($result, $object);
            unset($objects[$key]);
            wphr_parent_sort($objects, $result, $object->id, $depth + 1);
        }
    }
    return $result;
}

/**
 * Check today's birthday through schedule job.
 *
 * @since 1.1
 *
 * @return array
 */
function wphr_hr_schedule_check_todays_birthday() {
    $birthdays = wphr_hr_get_todays_birthday();

    // Do the action if someone's birthday today run only in cron
    if ( defined( 'DOING_CRON' ) && DOING_CRON && ! empty( $birthdays ) ) {
        do_action( 'wphr_hr_happened_birthday_today_all', $birthdays );

        foreach ( $birthdays as $birthday ) {
            do_action( 'wphr_hr_happened_birthday_today', $birthday->user_id );
        }
    }

    return $birthdays;
}

/**
 * Prevent redirect to woocommerce my account page
 *
 * @param boolean $prevent_access
 *
 * @since 1.1.18
 *
 * @return boolean
 */
function wphr_hr_wc_prevent_admin_access( $prevent_access ) {
    if ( current_user_can( wphr_hr_get_manager_role() ) || current_user_can( wphr_hr_get_employee_role() ) ) {
        return false;
    }

    return $prevent_access;
}
