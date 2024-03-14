<?php

namespace WPHR\HR_MANAGER\HRM\Admin;

use  WPHR\HR_MANAGER\HRM\Employee ;
/**
 * Admin Menu
 */
class Admin_Menu
{
    /**
     * Kick-in the class
     */
    public function __construct()
    {
        add_action( 'admin_menu', array( $this, 'admin_menu' ) );
    }
    
    /**
     * Add menu items
     *
     * @return void
     */
    public function admin_menu()
    {
        /** HR Management **/
        $calendar = false;
        add_menu_page(
            __( 'WPHR Manager', 'wphr' ),
            __( 'WPHR Manager', 'wphr' ),
            'wphr_list_employee',
            'wphr-hr',
            array( $this, 'dashboard_page' ),
            'dashicons-hr-icon',
            70
        );
        $overview = add_submenu_page(
            'wphr-hr',
            __( 'Overview', 'wphr' ),
            __( 'Overview', 'wphr' ),
            'wphr_list_employee',
            'wphr-hr',
            array( $this, 'dashboard_page' )
        );
        add_submenu_page(
            'wphr-hr',
            __( 'Employees', 'wphr' ),
            __( 'Employees', 'wphr' ),
            'wphr_list_employee',
            'wphr-hr-employee',
            array( $this, 'employee_page' )
        );
        if ( current_user_can( 'employee' ) ) {
            add_submenu_page(
                'wphr-hr',
                __( 'My Profile', 'wphr' ),
                __( 'My Profile', 'wphr' ),
                'wphr_list_employee',
                'wphr-hr-my-profile',
                array( $this, 'employee_my_profile_page' )
            );
        }
        add_submenu_page(
            'wphr-hr',
            __( 'Departments', 'wphr' ),
            __( 'Departments', 'wphr' ),
            'wphr_manage_department',
            'wphr-hr-depts',
            array( $this, 'department_page' )
        );
        add_submenu_page(
            'wphr-hr',
            __( 'Roles', 'wphr' ),
            __( 'Roles', 'wphr' ),
            'wphr_manage_designation',
            'wphr-hr-designation',
            array( $this, 'designation_page' )
        );
        add_submenu_page(
            'wphr-hr',
            __( 'Announcement', 'wphr' ),
            __( 'Announcement', 'wphr' ),
            'wphr_manage_announcement',
            'edit.php?post_type=wphr_hr_announcement'
        );
        /** Leave Management **/
        
        if ( current_user_can( 'wphr_leave_manage' ) ) {
            add_menu_page(
                __( 'Leave Management', 'wphr' ),
                __( 'WPHR Leave', 'wphr' ),
                'wphr_leave_manage',
                'wphr-leave',
                array( $this, 'empty_page' ),
                'dashicons-hr-leave-icon',
                71
            );
            $leave_request = add_submenu_page(
                'wphr-leave',
                __( 'Requests', 'wphr' ),
                __( 'Requests', 'wphr' ),
                'wphr_leave_manage',
                'wphr-leave',
                array( $this, 'leave_requests' )
            );
        }
        
        
        if ( current_user_can( 'wphr_manage_hr_settings' ) ) {
            add_submenu_page(
                'wphr-leave',
                __( 'Leave Entitlements', 'wphr' ),
                __( 'Leave Entitlements', 'wphr' ),
                'wphr_leave_manage',
                'wphr-leave-assign',
                array( $this, 'leave_entitilements' )
            );
            add_submenu_page(
                'wphr-leave',
                __( 'Holidays', 'wphr' ),
                __( 'Holidays', 'wphr' ),
                'wphr_leave_manage',
                'wphr-holiday-assign',
                array( $this, 'holiday_page' )
            );
            add_submenu_page(
                'wphr-leave',
                __( 'Policies', 'wphr' ),
                __( 'Policies', 'wphr' ),
                'wphr_leave_manage',
                'wphr-leave-policies',
                array( $this, 'leave_policy_page' )
            );
            $calendar = add_submenu_page(
                'wphr-leave',
                __( 'Calendar', 'wphr' ),
                __( 'Calendar', 'wphr' ),
                'wphr_leave_manage',
                'wphr-leave-calendar',
                array( $this, 'leave_calendar_page' )
            );
        }
        
        // add_submenu_page( 'wphr-leave', __( 'Leave Calendar', 'wphr' ), __( 'Leave Calendar', 'wphr' ), 'manage_options', 'wphr-leave-calendar', array( $this, 'empty_page' ) );
        add_action( 'admin_print_styles-' . $overview, array( $this, 'hr_calendar_script' ) );
        if ( $calendar ) {
            add_action( 'admin_print_styles-' . $calendar, array( $this, 'hr_calendar_script' ) );
        }
    }
    
    /**
     * Handles HR calendar script
     *
     * @return void
     */
    public function hr_calendar_script()
    {
        wp_enqueue_script( 'wphr-momentjs' );
        wp_enqueue_script( 'wphr-fullcalendar' );
        wphr_enqueue_fullcalendar_locale();
        wp_enqueue_style( 'wphr-fullcalendar' );
    }
    
    /**
     * Handles the dashboard page
     *
     * @return void
     */
    public function dashboard_page()
    {
        include WPHR_HRM_VIEWS . '/dashboard.php';
    }
    
    /**
     * Handles HR members page
     *
     * @since 0.1.8
     *
     * @return void
     */
    public function hr_managers_list()
    {
        $template = WPHR_HRM_VIEWS . '/hr_managers.php';
        if ( file_exists( $template ) ) {
            include $template;
        }
    }
    
    /**
     * Handles Line members page
     *
     * @since 0.1.8
     *
     * @return void
     */
    public function line_managers_list()
    {
        $template = WPHR_HRM_VIEWS . '/line_managers.php';
        if ( file_exists( $template ) ) {
            include $template;
        }
    }
    
    /**
     * Handles the dashboard page
     *
     * @return void
     */
    public function employee_page()
    {
        $action = ( isset( $_GET['action'] ) ? sanitize_text_field( $_GET['action'] ) : 'list' );
        $id = ( isset( $_GET['id'] ) ? intval( sanitize_text_field( $_GET['id'] ) ) : 0 );
        switch ( $action ) {
            case 'view':
                $employee = new Employee( intval( $id ) );
                if ( !$employee->id ) {
                    wp_die( __( 'Employee not found!', 'wphr' ) );
                }
                $template = WPHR_HRM_VIEWS . '/employee/single.php';
                break;
            default:
                $template = WPHR_HRM_VIEWS . '/employee.php';
                break;
        }
        $template = apply_filters(
            'wphr_hr_employee_templates',
            $template,
            $action,
            $id
        );
        if ( file_exists( $template ) ) {
            include $template;
        }
    }
    
    /**
     * Employee my profile page template
     *
     * @since 0.1
     *
     * @return void
     */
    public function employee_my_profile_page()
    {
        $action = ( isset( $_GET['action'] ) ? sanitize_text_field( $_GET['action'] ) : 'view' );
        $id = ( isset( $_GET['id'] ) ? intval( sanitize_text_field( $_GET['id'] ) ) : intval( get_current_user_id() ) );
        switch ( $action ) {
            case 'view':
                $employee = new Employee( $id );
                if ( !$employee->id ) {
                    wp_die( __( 'Employee not found!', 'wphr' ) );
                }
                $template = WPHR_HRM_VIEWS . '/employee/single.php';
                break;
            default:
                $template = WPHR_HRM_VIEWS . '/employee/single.php';
                break;
        }
        $template = apply_filters(
            'wphr_hr_employee_my_profile_templates',
            $template,
            $action,
            $id
        );
        
        if ( file_exists( $template ) ) {
            $is_my_profile_page = true;
            include $template;
            wphr_get_js_template( WPHR_HRM_JS_TMPL . '/new-leave-request.php', 'wphr-new-leave-req' );
            wphr_get_js_template( WPHR_HRM_JS_TMPL . '/leave-days.php', 'wphr-leave-days' );
        }
    
    }
    
    /**
     * Handles the dashboard page
     *
     * @return void
     */
    public function department_page()
    {
        $action = ( isset( $_GET['action'] ) ? sanitize_text_field( $_GET['action'] ) : 'list' );
        $id = ( isset( $_GET['id'] ) ? intval( sanitize_text_field( $_GET['id'] ) ) : 0 );
        switch ( $action ) {
            case 'view':
                $template = WPHR_HRM_VIEWS . '/departments/single.php';
                break;
            default:
                $template = WPHR_HRM_VIEWS . '/departments.php';
                break;
        }
        $template = apply_filters(
            'wphr_hr_department_templates',
            $template,
            $action,
            $id
        );
        if ( file_exists( $template ) ) {
            include $template;
        }
    }
    
    /**
     * Render the designation page
     *
     * @return void
     */
    public function designation_page()
    {
        include WPHR_HRM_VIEWS . '/designation.php';
    }
    
    /**
     * Render the leave policy page
     *
     * @return void
     */
    public function leave_policy_page()
    {
        include WPHR_HRM_VIEWS . '/leave/leave-policies.php';
    }
    
    /**
     * Render the holiday page
     *
     * @return void
     */
    public function holidayByLocation_page()
    {
        include WPHR_HRM_VIEWS . '/leave/holidayByLocation.php';
    }
    
    /**
     * Render the holiday page ( By Location )
     *
     * @return void
     */
    public function holiday_page()
    {
        
        if ( isset( $_GET['subpage'] ) && sanitize_text_field( $_GET['subpage'] ) == 'location' ) {
            include WPHR_HRM_VIEWS . '/leave/holidayByLocation.php';
        } else {
            include WPHR_HRM_VIEWS . '/leave/holiday.php';
        }
    
    }
    
    /**
     * Render the leave entitlements page
     *
     * @return void
     */
    public function leave_entitilements()
    {
        include WPHR_HRM_VIEWS . '/leave/leave-entitlements.php';
    }
    
    /**
     * Render the leave entitlements calendar
     *
     * @return void
     */
    public function leave_calendar_page()
    {
        include WPHR_HRM_VIEWS . '/leave/calendar.php';
    }
    
    /**
     * Render the leave requests page
     *
     * @return void
     */
    public function leave_requests()
    {
        $view = ( isset( $_GET['view'] ) ? sanitize_text_field( $_GET['view'] ) : 'list' );
        switch ( $view ) {
            case 'new':
                include WPHR_HRM_VIEWS . '/leave/new-request.php';
                break;
            default:
                include WPHR_HRM_VIEWS . '/leave/requests.php';
                break;
        }
    }
    
    /**
     * An empty page for testing purposes
     *
     * @return void
     */
    public function empty_page()
    {
    }

}