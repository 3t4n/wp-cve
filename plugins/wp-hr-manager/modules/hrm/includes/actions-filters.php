<?php

// Actions *****************************************************************/

add_action( 'user_register', 'wphr_hr_new_admin_as_manager' );
add_action( 'delete_user', 'wphr_hr_employee_on_delete' );
add_action( 'set_user_role', 'wphr_hr_existing_role_to_employee', 10, 2 );

// After create employee apply leave policy
add_action( 'wphr_hr_employee_new', 'wphr_hr_apply_policy_on_new_employee', 10, 1 );
add_action( 'wphr_daily_scheduled_events', 'wphr_hr_apply_scheduled_policies' );
add_action( 'wphr_daily_scheduled_events', 'wphr_hr_schedule_check_todays_birthday' );
add_action( 'wphr_daily_scheduled_events', 'wphr_hr_apply_entitlement_yearly' );
add_action( 'wphr_hr_leave_policy_new', 'wphr_hr_apply_policy_existing_employee', 10, 2 );
add_action( 'wphr_hr_schedule_announcement_email', 'wphr_hr_send_announcement_email', 10, 2 );

// Filters *****************************************************************/
add_filter( 'wphr_map_meta_caps', 'wphr_hr_map_meta_caps', 10, 4 );
add_filter( 'user_has_cap', 'wphr_user_get_caps_for_role', 10, 4 );
add_filter( 'editable_roles', 'wphr_hr_filter_editable_roles' );
add_filter( 'woocommerce_prevent_admin_access', 'wphr_hr_wc_prevent_admin_access' );
