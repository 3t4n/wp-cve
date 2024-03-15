<?php namespace Premmerce\UsersRoles\Models;

/**
 * Class AdminModel
 * @package Premmerce\UsersRoles\Models
 */
class AdminModel
{
    /**
     * Get list of all roles or if set $roleName get current role
     *
     * @param string $roleName
     *
     * @return array|mixed
     */
    public function getRoles($roleName = '')
    {
        global $wpdb;

        $roles = array();

        $data = $wpdb->get_var("SELECT option_value FROM {$wpdb->options} WHERE option_name = '{$wpdb->prefix}user_roles'");

        if ($data) {
            if ($roleName != "") {
                $queryRoles = unserialize($data);

                $roles = $queryRoles[$roleName];
            } else {
                $roles = unserialize($data);
            }
        }

        return $roles;
    }

    /**
     * Update role data
     *
     * @param string $roleName
     * @param array $roleData
     */
    public function setRoles($roleName, $roleData)
    {
        global $wpdb;

        $roles = $this->getRoles();

        $roles[$roleName] = $roleData;

        $wpdb->update(
            $wpdb->options,
            array('option_value' => serialize($roles)),
            array('option_name' => "{$wpdb->prefix}user_roles")
        );
    }
}
