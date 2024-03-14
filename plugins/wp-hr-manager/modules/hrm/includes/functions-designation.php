<?php

/**
 * Create a new designation
 *
 * @param  array   arguments
 *
 * @return int|false
 */
function wphr_hr_create_designation( $args = array() ) {
    global $wpdb;

    $defaults = array(
        'id'          => 0,
        'title'       => '',
        'description' => '',
        'status'      => 1
    );

    $fields = wp_parse_args( $args, $defaults );

    // validation
    if ( empty( $fields['title'] ) ) {
        return new WP_Error( 'no-name', __( 'No designation name provided.', 'wphr' ) );
    }

    // unset the department id
    $desig_id = $fields['id'];
    unset( $fields['id'] );

    $designation = new \WPHR\HR_MANAGER\HRM\Models\Designation();

    if ( ! $desig_id ) {

        $desig = $designation->create( $fields );

        do_action( 'wphr_hr_desig_new', $desig->id, $fields );

        return $desig->id;

    } else {

        do_action( 'wphr_hr_desig_before_updated', $desig_id, $fields );

        $designation->find( $desig_id )->update( $fields );

        do_action( 'wphr_hr_desig_after_updated', $desig_id, $fields );

        return $desig_id;

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
function wphr_hr_get_designations( $args = array() ) {

    $defaults = array(
        'number'     => 20,
        'offset'     => 0,
        'orderby'    => 'title',
        'order'      => 'ASC',
    );

    $args = wp_parse_args( $args, $defaults );

    $cache_key = 'wphr-designations';
    $designations = wp_cache_get( $cache_key, 'wphr' );

    $designation = new \WPHR\HR_MANAGER\HRM\Models\Designation();

    if ( false === $designations ) {

        // Check if want all data without any pagination
        if ( $args['number'] != '-1' ) {
            $designation = $designation->skip( $args['offset'] )->take( $args['number'] );
        }

        $results = $designation
                ->orderBy( $args['orderby'], $args['order'] )
                ->get()
                ->toArray();

        $designations = wphr_array_to_object( $results );

        wp_cache_set( $cache_key, $designations, 'wphr' );
    }


    return $designations;
}

/**
 * Delete a department
 *
 * @param  int  department id
 *
 * @return bool
 */
function wphr_hr_delete_designation( $designation_id ) {

    if ( is_array( $designation_id ) ) {
        $exist_employee = [];
        $not_exist_employee = [];

        foreach ( $designation_id as $key => $designation ) {
            $desig = new \WPHR\HR_MANAGER\HRM\Designation( intval( $designation ) );

            if ( $desig->num_of_employees() ) {
                $exist_employee[] = $designation;
            } else {
                do_action( 'wphr_hr_desig_delete', $desig );
                $not_exist_employee[] = $designation;
            }
        }

        if ( $not_exist_employee ) {
            \WPHR\HR_MANAGER\HRM\Models\Designation::destroy( $not_exist_employee );
        }

        return $exist_employee;

    } else {
        $designation = new \WPHR\HR_MANAGER\HRM\Designation( $designation_id );
        if ( $designation->num_of_employees() ) {
            return new WP_Error( 'not-empty', __( 'You can not delete this designation because it contains employees.', 'wphr' ) );
        }

        do_action( 'wphr_hr_desig_delete', $designation );

        return \WPHR\HR_MANAGER\HRM\Models\Designation::find( $designation_id )->delete();
    }


}

/**
 * Get the raw designations dropdown
 *
 * @param  int  company id
 *
 * @return array  the key-value paired designations
 */
function wphr_hr_get_designation_dropdown_raw( $select_text = '' ) {
    $select_text = empty( $select_text ) ? __( '- Select Role -', 'wphr' ) : $select_text;
    $designations = wphr_hr_get_designations( ['number' => -1 ] );
    $dropdown     = array( '-1' => $select_text );

    if ( $designations ) {
        foreach ($designations as $key => $designation) {
            $dropdown[$designation->id] = stripslashes( $designation->title );
        }
    }

    return $dropdown;
}

/**
 * Get company designations dropdown
 *
 * @param  int  company id
 * @param  string  selected designation
 *
 * @return string  the dropdown
 */
function wphr_hr_get_designation_dropdown( $selected = '' ) {
    $designations = wphr_hr_get_designation_dropdown_raw();
    $dropdown     = '';

    if ( $designations ) {
        foreach ($designations as $key => $title) {
            $dropdown .= sprintf( "<option value='%s'%s>%s</option>\n", $key, selected( $selected, $key, false ), $title );
        }
    }

    return $dropdown;
}

function wphr_hr_count_designation() {
    return \WPHR\HR_MANAGER\HRM\Models\Designation::count();
}
