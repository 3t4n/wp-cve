<?php

/**
 * HR Capabilities
 *
 * The functions in this file are used primarily as convenient wrappers for
 * capability output in user profiles. This includes mapping capabilities and
 * groups to human readable strings,
 *
 * @package WPHR Manager
 * @subpackage HR
 */

/**
 * The manager role for HR employees
 *
 * @return string
 */
function wphr_hr_get_manager_role() {
    return apply_filters( 'wphr_hr_get_manager_role', 'wphr_hr_manager' );
}

/**
 * The manager role for HR employees
 *
 * @return string
 */
function wphr_hr_get_employee_role() {
    return apply_filters( 'wphr_hr_get_employee_role', 'employee' );
}

/**
 * Get dynamic roles for HR
 *
 * @return array
 */
function wphr_hr_get_roles() {
    $roles = [
        wphr_hr_get_manager_role() => [
            'name'         => __( 'HR Manager', 'wphr' ),
            'public'       => false,
            'capabilities' => wphr_hr_get_caps_for_role( wphr_hr_get_manager_role() )
        ],

        wphr_hr_get_employee_role() => [
            'name'         => __( 'Employee', 'wphr' ),
            'public'       => true,
            'capabilities' => wphr_hr_get_caps_for_role( wphr_hr_get_employee_role() )
        ]
    ];
 
    return apply_filters( 'wphr_hr_get_roles', $roles );
}

/**
 * Returns an array of capabilities based on the role that is being requested.
 *
 * @param  string  $role
 *
 * @return array
 */
function wphr_hr_get_caps_for_role( $role = '' ) {
    $caps = [];

    // Which role are we looking for?
    switch ( $role ) {

        case wphr_hr_get_manager_role():
            $caps = [
                'read'                     => true,

                // Upload file
                'upload_files'             => true,

                // employee
                'wphr_list_employee'        => true,
                'wphr_create_employee'      => true,
                'wphr_view_employee'        => true,
                'wphr_edit_employee'        => true,
                'wphr_delete_employee'      => true,

                'wphr_create_review'        => true,
                'wphr_delete_review'        => true,
                'wphr_manage_review'        => true,

                'wphr_manage_announcement'  => true,

                'wphr_manage_jobinfo'       => true,
                'wphr_view_jobinfo'         => true,

                // department
                'wphr_manage_department'    => true,

                // designation
                'wphr_manage_designation'   => true,

                // leave and holidays
                'wphr_leave_create_request' => true,
                'wphr_leave_manage'         => true,
				'wphr_leave_mails'         	=> true,
				
                'wphr_manage_hr_settings'   => true,

                // document
                'wphr_create_document'      => true,
                'wphr_edit_document'        => true,
                'wphr_view_document'        => true,
                'wphr_delete_document'      => true
            ];
            break;

        case wphr_hr_get_employee_role():

            $caps = [
                'read'                     => true,
                'upload_files'             => true,
                'wphr_list_employee'        => true,
                'wphr_view_employee'        => true,
                'wphr_edit_employee'        => true,
                'wphr_view_jobinfo'         => true,
                'wphr_leave_create_request' => true,
                // document
                'wphr_create_document'      => true,
                'wphr_edit_document'        => true,
                'wphr_view_document'        => true,
                'wphr_delete_document'      => true
            ];

            break;
    }

    return apply_filters( 'wphr_hr_get_caps_for_role', $caps, $role );
}

/**
 * Maps HR capabilities to employee or HR manager
 *
 * @param array $caps Capabilities for meta capability
 * @param string $cap Capability name
 * @param int $user_id User id
 * @param mixed $args Arguments
 *
 * @return array Actual capabilities for meta capability
 */
function wphr_hr_map_meta_caps( $caps = array(), $cap = '', $user_id = 0, $args = array() ) {
    // What capability is being checked?
    switch ( $cap ) {

        case 'wphr_view_employee':
        case 'wphr_edit_employee':
        case 'wphr_edit_document':
            $employee_id = isset( $args[0] ) ? $args[0] : false;

            if ( $user_id == $employee_id ) {
                $caps = [ $cap ];
            } else {
                $hr_manager_role = wphr_hr_get_manager_role();
                // HR manager can read any employee
                if ( user_can( $user_id, $hr_manager_role ) ) {
                    $caps = array( $hr_manager_role );
                } else {
                    $caps = ['do_not_allow'];
                }
            }

            break;

        case 'wphr_create_review':
            $employee_id = isset( $args[0] ) ? $args[0] : false;
            $employee    = new \WPHR\HR_MANAGER\HRM\Employee( $employee_id );

            if ( $employee->get_reporting_to() && $employee->get_reporting_to()->ID == $user_id ) {
                $caps = [ 'employee' ];
            } else {
                $caps = [ $cap ];
            }

            break;
    }

    return apply_filters( 'wphr_hr_map_meta_caps', $caps, $cap, $user_id, $args );
}

/**
 * Removes the non-public HR roles from the editable roles array
 *
 * @param array $all_roles All registered roles
 *
 * @return array
 */
function wphr_hr_filter_editable_roles( $all_roles = [] ) {
    $roles = wphr_hr_get_roles();
	if( is_array( $roles ) ){
		foreach ($roles as $hr_role_key => $hr_role) {

			if ( isset( $hr_role['public'] ) && $hr_role['public'] === false ) {

				// Loop through WordPress roles
				foreach ( array_keys( $all_roles ) as $wp_role ) {

					// If keys match, unset
					if ( $wp_role === $hr_role_key ) {
						unset( $all_roles[$wp_role] );
					}
				}
			}

		}
    }
    return $all_roles;
}

/**
 * Return a user's HR role
 *
 * @param int $user_id
 *
 * @return string
 */
function wphr_hr_get_user_role( $user_id = 0 ) {

    // Validate user id
    $user = get_userdata( $user_id );
    $role = false;

    // User has roles so look for a HR one
    if ( ! empty( $user->roles ) ) {

        // Look for a HR role
        $roles = array_intersect(
            array_values( $user->roles ),
            array_keys( wphr_hr_get_roles() )
        );
       
        // If there's a role in the array, use the first one. This isn't very
        // smart, but since roles aren't exactly hierarchical, and HR
        // does not yet have a UI for multiple user roles, it's fine for now.
        if ( !empty( $roles ) ) {
            $role = array_shift( $roles );
        }
    }

    return apply_filters( 'wphr_hr_get_user_role', $role, $user_id, $user );
}

/**
 * Create a new employee when a user role is changed to employee
 *
 * @param  int  $user_id
 * @param  string  $role
 *
 * @return void
 */
function wphr_hr_existing_role_to_employee( $user_id, $role ) {
    if ( 'employee' != $role ) {
        return;
    }

    // check if a employee of that ID exists, otherwise create one
    $employee = new \WPHR\HR_MANAGER\HRM\Models\Employee();
    $exists = $employee->where( 'user_id', '=', $user_id )->get()->first();

    if ( null === $exists ) {
        $employee->create([
            'user_id'     => $user_id,
            'designation' => 0,
            'department'  => 0,
            'status'      => 'active'
        ]);
    }
}

/**
 * When a new administrator is created, make him HR Manager by default
 *
 * @param  int  $user_id
 *
 * @return void
 */
function wphr_hr_new_admin_as_manager( $user_id ) {
    $user = get_user_by( 'id', $user_id );

    if ( $user && in_array('administrator', $user->roles) ) {
        $user->add_role( wphr_hr_get_manager_role() );
    }
}
