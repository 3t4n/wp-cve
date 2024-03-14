<?php
namespace bridge_models;
defined( 'ABSPATH' ) || exit;

use WP_Roles;
use WC_Customer;
/**
 *
 */
class Staff_Management
{

    function __construct()
    {
        # code...
    }

    public function oliver_pos_getStaffMembers()
    {
        global $wpdb;
        $staff = array();
        $wp_roles = new WP_Roles();
        $get_names = $wp_roles->get_names();

        foreach ($get_names as $key => $name) {
            $roles[] = $key;
        }

        $get_users = get_users( array(
            'role__in' => $roles ,
        ) );

        foreach ($get_users as $key => $user) {
            $customer_id = (int)$user->ID;
            array_push($staff, $this->oliver_pos_getStaffMember( $customer_id ));
        }

        return $staff;
    }

    public function oliver_pos_getStaffMember( $customer_id )
    {
        $customer = new WC_Customer($customer_id);
        $get_display_name = $customer->get_display_name();
        $words = explode(" ", $get_display_name);
        $acronym = "";
        foreach ($words as $w) {
            $acronym .= $w[0];
        }
        $acronym = strtoupper($acronym);
        return array(
            'get_display_name' => $get_display_name,
            'get_role' => $customer->get_role(),
            'short_name' => $acronym,
            'staff_id' => $customer_id
        );
    }
}