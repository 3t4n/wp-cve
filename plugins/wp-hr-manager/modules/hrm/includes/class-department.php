<?php
namespace WPHR\HR_MANAGER\HRM;

/**
 * The department class
 */
class Department extends \WPHR\HR_MANAGER\Item {


    /**
     * Get a company by ID
     *
     * @param  int  company id
     *
     * @return object  wpdb object
     */
    protected function get_by_id( $department_id ) {
        return \WPHR\HR_MANAGER\HRM\Models\Department::find( $department_id );
    }

    /**
     * Get number of employee belongs to this department
     *
     * @return int
     */
    public function num_of_employees() {
        return \WPHR\HR_MANAGER\HRM\Models\Employee::where( array( 'status' => 'active', 'department' => $this->id ) )->count();
    }

    /**
     * Get the name of department lead
     *
     * @return string
     */
    public function get_lead() {

        $employee = new Employee( intval( $this->lead ) );

        if ( ! $employee->id ) {
            return false;
        }

        return $employee;
    }

    /**
     * Get the name of department lead
     *
     * @return string
     */
    public function get_depth( $department, $max_depth = 5 ) {
        $depth = 0;
        $parent_id = $department->parent;
        while( $parent_id > 0 ){

            if ( $depth > $max_depth ) {
                continue;
            }
            $parent_id = $this->get_parent_id( $parent_id );
            $depth++;
        }

        return $depth;
    }

    function get_parent_id( $parent_id ) {
        return \WPHR\HR_MANAGER\HRM\Models\Department::select( array( 'parent' ) )->find( $parent_id )->parent;
    }
}
