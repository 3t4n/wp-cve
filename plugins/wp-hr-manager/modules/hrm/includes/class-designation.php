<?php
namespace WPHR\HR_MANAGER\HRM;

/**
 * The designation class
 */
class Designation extends \WPHR\HR_MANAGER\Item {

    /**
     * Get a company by ID
     *
     * @param  int  company id
     *
     * @return object  wpdb object
     */
    protected function get_by_id( $designation_id ) {
        global $wpdb;

        return $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}wphr_hr_designations WHERE id = %d", $designation_id ) );
    }

    /**
     * Get number of employee belongs to this department
     *
     * @return int
     */
    public function num_of_employees() {
        return \WPHR\HR_MANAGER\HRM\Models\Employee::where( array( 'status' => 'active', 'designation' => $this->id ) )->count();
    }
}
