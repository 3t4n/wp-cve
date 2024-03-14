<?php

/**
 * @copyright Copyright (c) 2009-2020 ThemeCatcher (https://www.themecatcher.net)
 */

class Quform_Zapier_Permissions
{
    /**
     * Get the list of all plugin capabilities
     *
     * @return array
     */
    public function getAllCapabilities()
    {
        return apply_filters('quform_zapier_all_capabilities', array(
            'quform_zapier_list_integrations',
            'quform_zapier_add_integrations',
            'quform_zapier_edit_integrations',
            'quform_zapier_delete_integrations',
            'quform_zapier_settings'
        ));
    }

    /**
     * Get the list of all plugin capabilities (with descriptions)
     *
     * Due to a conflict with WPML, this needs to be separate from getAllCapabilities.
     *
     * @return array
     */
    public function getAllCapabilitiesWithDescriptions()
    {
        return apply_filters('quform_zapier_all_capabilities_with_descriptions', array(
            'quform_zapier_list_integrations' => __('List Integrations', 'quform-zapier'),
            'quform_zapier_add_integrations' => __('Add Integrations', 'quform-zapier'),
            'quform_zapier_edit_integrations' => __('Edit Integrations', 'quform-zapier'),
            'quform_zapier_delete_integrations' => __('Delete Integrations', 'quform-zapier'),
            'quform_zapier_settings' => __('Edit Settings', 'quform-zapier')
        ));
    }

    /**
     * On activation give the 'administrator' role the capabilities to manage integrations
     */
    public function activate()
    {
        $role = get_role('administrator');

        if ( ! empty($role)) {
            foreach ($this->getAllCapabilities() as $cap) {
                $role->add_cap($cap);
            }
        }

        // Refresh the capabilities for the current user, so that the "Zapier" menu item appears on the first request
        // after activating the plugin, otherwise a page refresh is needed
        wp_get_current_user()->get_role_caps();
    }

    /**
     * Update the permissions based on the given array
     *
     * @param array $permissions
     */
    public function update(array $permissions)
    {
        $caps = $this->getAllCapabilities();

        /* @var $wp_roles WP_Roles */
        global $wp_roles;
        $roles = $wp_roles->get_names();

        foreach ($roles as $key => $name) {
            if ($key === 'administrator') {
                continue;
            }

            $role = get_role($key);

            if ( ! $role instanceof WP_Role) {
                continue;
            }

            foreach ($caps as $cap) {
                $add = isset($permissions[$key][$cap]) && $permissions[$key][$cap];

                if ( ! $role->has_cap($cap) && $add) {
                    $role->add_cap($cap);
                } elseif ($role->has_cap($cap) && ! $add) {
                    $role->remove_cap($cap);
                }
            }
        }
    }

    /**
     * On plugin uninstall, remove all capabilities from all roles
     */
    public function uninstall()
    {
        $caps = $this->getAllCapabilities();

        /* @var $wp_roles WP_Roles */
        global $wp_roles;
        $roles = $wp_roles->get_names();

        foreach ($roles as $key => $name) {
            $role = get_role($key);

            if ( ! $role instanceof WP_Role) {
                continue;
            }

            foreach ($caps as $cap) {
                if ($role->has_cap($cap)) {
                    $role->remove_cap($cap);
                }
            }
        }
    }
}
