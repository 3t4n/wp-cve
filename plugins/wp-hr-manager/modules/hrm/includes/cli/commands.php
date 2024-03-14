<?php
namespace WPHR\HR_MANAGER\HRM\CLI;

/**
 * HRM CLI class
 */
class Commands extends \WP_CLI_Command {

    /**
     * Clean HRM tables
     *
     * @since 1.2.0
     *
     * @return void
     */
    public function clean() {
        global $wpdb;

        $tables = [
            'wphr_hr_depts',
            'wphr_hr_designations',
            'wphr_hr_employees',
            'wphr_hr_employee_history',
            'wphr_hr_employee_notes',
            'wphr_hr_leave_policies',
            'wphr_hr_holiday',
            'wphr_hr_leave_entitlements',
            'wphr_hr_leaves',
            'wphr_hr_leave_requests',
            'wphr_hr_work_exp',
            'wphr_hr_education',
            'wphr_hr_dependents',
            'wphr_hr_employee_performance',
            'wphr_hr_announcement',
        ];

        foreach ($tables as $table) {
            $wpdb->query( 'TRUNCATE TABLE ' . $wpdb->prefix . $table);
        }

        \WP_CLI::success( "Table deleted successfully!" );
    }

}

\WP_CLI::add_command( 'hr', 'WPHR\HR_MANAGER\HRM\CLI\Commands' );
