<?php

/**
 * Create a new department
 *
 * @param  array   arguments
 *
 * @return int|false
 */
function wphr_hr_create_department( $args = array() ) {

    $defaults = array(
        'id'          => 0,
        'title'       => '',
        'description' => '',
        'lead'        => 0,
        'parent'      => 0,
        'status'      => 1
    );

    $fields = wp_parse_args( $args, $defaults );

    // validation
    if ( empty( $fields['title'] ) ) {
        return new WP_Error( 'no-name', __( 'No department name provided.', 'wphr' ) );
    }

    // unset the department id
    $dept_id = $fields['id'];
    unset( $fields['id'] );

    $department = new \WPHR\HR_MANAGER\HRM\Models\Department();

    if ( ! $dept_id ) {
        $dept = $department->create( $fields );

        do_action( 'wphr_hr_dept_new', $dept->id, $fields );

        return $dept->id;

    } else {

        do_action( 'wphr_hr_dept_before_updated', $dept_id, $fields );

        $department->find( $dept_id )->update( $fields );

        do_action( 'wphr_hr_dept_after_updated', $dept_id, $fields );

        return $dept_id;
    }

    return false;
}

/**
 * Get all the departments of a company
 *
 * @param  int  the company id
 *
 * @return array  list of departments
 */
function wphr_hr_get_departments( $args = [] ) {

    $defaults = array(
        'number'     => 20,
        'offset'     => 0,
        'orderby'    => 'id',
        'order'      => 'asc',
        'no_object'  => false
    );

    $args  = wp_parse_args( $args, $defaults );

    $cache_key = 'wphr-get-departments';
    $results   = wp_cache_get( $cache_key, 'wphr' );

    $department = new \WPHR\HR_MANAGER\HRM\Models\Department();

    if ( isset( $args['s'] ) ) {
        $results = $department
                ->where( 'title', 'LIKE', '%'.sanitize_text_field($_GET['s']).'%' )
                ->get()
                ->toArray();
        $results = wphr_array_to_object( $results );
    }

    if ( false === $results ) {
        $results = $department
                ->get()
                ->toArray();

        $results = wphr_array_to_object( $results );
        wp_cache_set( $cache_key, $results, 'wphr' );
    }

    $results = wphr_parent_sort( $results );
    $departments = [];
    if ( $results ) {
        foreach ($results as $key => $row) {

            if ( true === $args['no_object'] ) {
                $departments[] = $row;
            } else {

                $departments[] = new WPHR\HR_MANAGER\HRM\Department( intval( $row->id ));
            }
        }
    }

    return $departments;
}

/**
 * Get all department from a company
 *
 * @param  int   $company_id  company id
 * @param bool $no_object     if set true, Department object will be
 *                            returned as array. $wpdb rows otherwise
 *
 * @return array  the department
 */
function wphr_hr_count_departments() {
    return \WPHR\HR_MANAGER\HRM\Models\Department::count();
}

/**
 * Delete a department
 *
 * @param  int  department id
 *
 * @return bool
 */
function wphr_hr_delete_department( $department_id ) {

    $department = new \WPHR\HR_MANAGER\HRM\Department( intval( $department_id ) );

    if ( $department->num_of_employees() ) {
        return false;
    }

    do_action( 'wphr_hr_dept_delete', $department_id );
    $parent_id = \WPHR\HR_MANAGER\HRM\Models\Department::where( 'id', '=', $department_id )->pluck('parent');

    if ( $parent_id ) {
        \WPHR\HR_MANAGER\HRM\Models\Department::where( 'parent', '=', $department_id )->update( ['parent' => $parent_id ] );
    } else {
        \WPHR\HR_MANAGER\HRM\Models\Department::where( 'parent', '=', $department_id )->update( ['parent' => 0 ] );
    }

    $resp = \WPHR\HR_MANAGER\HRM\Models\Department::find( $department_id )->delete();

    return $resp;
}

/**
 * Get the raw departments dropdown
 *
 * @param  int  company id
 * @param string  $select_label pass any string to be as the first element
 *
 * @return array  the key-value paired departments
 */
function wphr_hr_get_departments_dropdown_raw( $select_label = null ) {
    $departments = wphr_hr_get_departments();
    $dropdown    = array( '-1' => __( '- Select Department -', 'wphr' ) );

    if ( $select_label ) {
        $dropdown    = array( '-1' => $select_label );
    }

    if ( $departments ) {
        foreach ($departments as $key => $department) {
            $dropdown[$department->id] = stripslashes( $department->title );
        }
    }

    return $dropdown;
}

/**
 * Get company departments dropdown
 *
 * @param  string  selected department
 *
 * @return string  the dropdown
 */
function wphr_hr_get_departments_dropdown( $selected = '' ) {
    $departments = wphr_hr_get_departments_dropdown_raw();
    $dropdown    = '';
    if ( $departments ) {
        foreach ($departments as $key => $title) {
            $dropdown .= sprintf( "<option value='%s'%s>%s</option>\n", $key, selected( $selected, $key, false ), $title );
        }
    }

    return $dropdown;
}


