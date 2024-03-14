<?php
defined( 'ABSPATH' ) || exit;

include_once OLIVER_POS_ABSPATH . 'includes/models/class-staff-management.php';
/**
 * its responsible for all satff relative operations
 */
class Staff_Management
{
    private $staff_management;

    public function __construct()
    {
        $this->staff_management = new bridge_models\Staff_Management;
    }

	public function oliver_pos_getStaffMembers()
	{
		$get_data = $this->staff_management->oliver_pos_getStaffMembers();
		return $get_data;
	}
}