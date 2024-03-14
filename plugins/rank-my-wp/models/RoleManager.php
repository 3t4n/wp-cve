<?php

class RKMW_Models_RoleManager {

    public $roles;

    public function __construct() {
        add_action('admin_init', array($this, 'addRKMWRoles'), 99);
    }

    /**
     * Get all the Caps
     * @param $role
     * @return array
     */
    public function getRKMWCaps($role = '') {
        $caps = array();

        $caps['rkmw_seo_author'] = array(
            'rkmw_manage_research' => true,
            'rkmw_manage_settings' => false,
            'rkmw_manage_audit' => false,
            'rkmw_manage_rankings' => false,
        );

        $caps['rkmw_seo_editor'] = array(
            'rkmw_manage_research' => true,
            'rkmw_manage_settings' => false,
            'rkmw_manage_audit' => true,
            'rkmw_manage_rankings' => true,
        );

        $caps['rkmw_seo_admin'] = array(
            'rkmw_manage_research' => true,
            'rkmw_manage_settings' => true,
            'rkmw_manage_audit' => true,
            'rkmw_manage_rankings' => true,
        );

        $caps = array_filter($caps);

        if (isset($caps[$role])) {
            return $caps[$role];
        }

        return $caps;
    }

    /**
     * Register Roles and Caps
     * in case they don't exists
     */
    public function addRKMWRoles() {
        /** @var $wp_roles WP_Roles */
        global $wp_roles;

        //$this->removeRKMWCaps();
        if (function_exists('wp_roles')) {
            $allroles = wp_roles()->get_names();
            if (!empty($allroles)) {
                $allroles = array_keys($allroles);
            }

            if (!empty($allroles)) {
                foreach ($allroles as $role) {
                    if ($role == 'administrator' || $role == 'rkmw_seo_admin') {
                        $this->updateRKMWCap('rkmw_seo_admin', $role);
                        continue;
                    }

                    switch ($role) {
                        case 'editor':
                        case 'rkmw_seo_editor':
                            $this->updateRKMWCap('rkmw_seo_editor', $role);
                            break;
                        case 'author':
                        case 'rkmw_seo_author':
                        case 'contributor':
                        default:
                            $role_object = get_role($role);
                            if ($role_object->has_cap( 'edit_posts' )) {
                                $this->updateRKMWCap('rkmw_seo_author', $role);
                            }
                            break;

                    }
                }
            }

            if (!$wp_roles || !isset($wp_roles->roles) || !method_exists($wp_roles, 'is_role')) {
                return;
            }

            if (!$wp_roles->is_role('rkmw_seo_editor') || !$wp_roles->is_role('rkmw_seo_admin')) {
                //get all roles and caps
                $this->addRKMWRole('rkmw_seo_editor', esc_html__("RKMW Editor", RKMW_PLUGIN_NAME), 'editor');
                $this->addRKMWRole('rkmw_seo_admin', esc_html__("RKMW Admin", RKMW_PLUGIN_NAME), 'editor');

            }

        }
    }

    /**
     * Remove Roles and Caps
     */
    public function removeRKMWRoles() {
        global $wp_roles;

        //get all roles and caps
        $rkmwcaps = $this->getRKMWCaps();

        if (!empty($rkmwcaps)) {
            foreach (array_keys($rkmwcaps) as $role) {
                if ($wp_roles->is_role($role)) {
                    $this->removeRole($role);
                }

            }
        }

    }

    public function removeRKMWCaps() {
        if (function_exists('wp_roles')) {
            $allroles = wp_roles()->get_names();
            if (!empty($allroles)) {
                $allroles = array_keys($allroles);
            }

            if (!empty($allroles)) {
                foreach ($allroles as $role) {
                    $this->removeCap($role, $this->getRKMWCaps('rkmw_seo_admin'));
                    $this->removeCap($role, $this->getRKMWCaps('rkmw_seo_editor'));
                    $this->removeCap($role, $this->getRKMWCaps('rkmw_seo_author'));
                }
            }
        }

    }

    /**
     * Add Role and Caps
     * @param $rkmwrole
     * @param $title
     * @param $wprole
     */
    public function addRKMWRole($rkmwrole, $title, $wprole) {
        $wpcaps = $this->getWpCaps($wprole);
        $rkmwcaps = $this->getRKMWCaps($rkmwrole);

        $this->addRole($rkmwrole, $title, array_merge($wpcaps, $rkmwcaps));
    }

    /**
     * Update the Squirlly Caps into WP Roles
     * @param $rkmwrole
     * @param $wprole
     */
    public function updateRKMWCap($rkmwrole, $wprole) {
        $rkmwcaps = $this->getRKMWCaps($rkmwrole);

        $this->addCap($wprole, $rkmwcaps);
    }

    /**
     * Add a role into WP
     * @param $name
     * @param $title
     * @param $capabilities
     */
    public function addRole($name, $title, $capabilities) {
        add_role($name, $title, $capabilities);
    }

    /**
     * Add a cap into WP for a role
     * @param $name
     * @param $capabilities
     */
    public function addCap($name, $capabilities) {
        $role = get_role($name);

        if (!$role || !method_exists($role, 'add_cap')) {
            return;
        }

        foreach ($capabilities as $capability => $grant) {
            if (!$role->has_cap($capability)) {
                $role->add_cap($capability, $grant);
            }
        }
    }

    /**
     * Remove the caps for a role
     * @param $name
     * @param $capabilities
     */
    public function removeCap($name, $capabilities) {
        $role = get_role($name);

        if (!$role || !method_exists($role, 'remove_cap')) {
            return;
        }

        if ($role) {
            foreach ($capabilities as $capability => $grant) {
                $role->remove_cap($capability);
            }
        }
    }

    /**
     * Remove the role
     * @param $name
     */
    public function removeRole($name) {
        remove_role($name);
    }

    public function getWpCaps($role) {

        if ($wprole = get_role($role)) {
            return $wprole->capabilities;
        }

        return array();
    }

}