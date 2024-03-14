<?php

namespace WPPayForm\App\Services;

use WPPayForm\Framework\Support\Arr;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Managing Access Control
 * This is not complete on version 1.0.0 but we will definately add this feature.
 * @since 1.0.0
 */
class AccessControl
{
    public static function hasGrandAccess()
    {
        $grandPermissions = array(
            'manage_options',
            'wpf_full_access'
        );

        foreach ($grandPermissions as $grandPermission) {
            if (current_user_can($grandPermission)) {
                return $grandPermission;
            }
        }
        return false;
    }

    public static function hasTopLevelMenuPermission()
    {
        $menuPermissions = array(
            'manage_options',
            'wpf_full_access'
        );
        foreach ($menuPermissions as $menuPermission) {
            if (current_user_can($menuPermission)) {
                return $menuPermission;
            }
        }
        return false;
    }

    public static function isPaymatticUser() 
    {
        $menuPermission = self::hasTopLevelMenuPermission();
        $current_user = wp_get_current_user();
        $userRole = $current_user->roles[0];
        if ($userRole == 'paymattic_donor' || $userRole == 'paymattic_user' || $userRole == 'paymattic_subscriber') {
            return true;
        }

        return false;
    }

    public static function checkUserPermission($permission = '')
    {
        if (current_user_can($permission)) {
            return true;
        }

        return false;
    }

    public static function checkAndPresponseError($endpoint = false, $group = false, $message = '')
    {
        if (self::hasEndPointPermission($endpoint, $group)) {
            return true;
        }

        wp_send_json_error(array(
            'message' => ($message) ? $message : __('Sorry, You do not have permission to do this action: ', 'wp-payment-form') . $endpoint,
            'action' => $endpoint
        ), 423);
    }

    public static function hasEndPointPermission($endpoint = false, $group = false)
    {

        if ($grandAccess = self::hasGrandAccess()) {
            return apply_filters('wppayform/has_endpoint_access', $grandAccess, $endpoint, $group);
        } elseif ($roleAccess = self::giveCustomAccess()['has_access']) {
            return apply_filters('wppayform/has_endpoint_access', $roleAccess, $endpoint, $group);
        }

        $permissions = self::getEndpointPermissionMaps($group);

        if (isset($permissions[$endpoint])) {
            $relatedPermission = $permissions[$endpoint];
            foreach ($relatedPermission as $permission) {
                if (current_user_can($permission)) {
                    return apply_filters('wppayform/has_endpoint_access', $permission, $endpoint, $group);
                }
            }
        }
        return apply_filters('wppayform/has_endpoint_access', false, $endpoint, $group);
    }

    public static function getEndpointPermissionMaps($group = false)
    {
        $permissionGroups = array(
            'forms' => array(
                'get_forms' => array(
                    'wpf_can_edit_all_forms',
                    'wpf_can_add_form',
                    'wpf_can_edit_own_form',
                    'wpf_can_delete_all_forms',
                    'wpf_can_delete_own_created_forms',
                ),
                'create_form' => array(
                    'wpf_can_add_form'
                ),
                'update_form' => array(
                    'wpf_can_edit_all_forms',
                    'wpf_can_edit_own_form',
                    'wpf_can_add_form'
                ),
                'get_form' => array(
                    'wpf_can_edit_all_forms',
                    'wpf_can_edit_own_form',
                    'wpf_can_add_form'
                ),
                'save_form_settings' => array(
                    'wpf_can_edit_all_forms',
                    'wpf_can_edit_own_form',
                    'wpf_can_add_form'
                ),
                'save_form_builder_settings' => array(
                    'wpf_can_edit_all_forms',
                    'wpf_can_edit_own_form',
                    'wpf_can_add_form'
                ),
                'get_custom_form_settings' => array(
                    'wpf_can_edit_all_forms',
                    'wpf_can_edit_own_form',
                    'wpf_can_add_form'
                ),
                'delete_form' => array(
                    'wpf_can_delete_all_forms',
                    'wpf_can_delete_own_created_forms'
                ),
                'get_form_settings' => array(
                    'wpf_can_edit_all_forms',
                    'wpf_can_edit_own_form',
                    'wpf_can_add_form'
                ),
                'get_design_settings' => array(
                    'wpf_can_edit_all_forms',
                    'wpf_can_edit_own_form',
                    'wpf_can_add_form'
                ),
                'update_design_settings' => array(
                    'wpf_can_edit_all_forms',
                    'wpf_can_edit_own_form',
                    'wpf_can_add_form'
                )
            ),
            'submissions' => array(
                'get_submissions' => array(
                    'wpf_can_view_all_entries',
                    'wpf_can_view_entries_of_own_created_forms',
                    'wpf_can_edit_all_form_entries',
                    'wpf_can_edit_entries_of_own_created_forms',
                    'wpf_can_delete_all_entries',
                    'wpf_can_delete_entries_of_own_created_forms',
                ),
                'get_submission' => array(
                    'wpf_can_view_all_entries',
                    'wpf_can_view_entries_of_own_created_forms',
                ),
                'get_available_forms' => array(
                    'wpf_can_view_all_entries',
                    'wpf_can_view_entries_of_own_created_forms',
                    'wpf_can_edit_all_form_entries',
                    'wpf_can_edit_entries_of_own_created_forms',
                    'wpf_can_delete_all_entries',
                    'wpf_can_delete_entries_of_own_created_forms',
                ),
                'get_next_prev_submission' => array(
                    'wpf_can_view_all_entries',
                    'wpf_can_view_entries_of_own_created_forms',
                ),
                'add_submission_note' => array(
                    'wpf_can_edit_all_form_entries',
                    'wpf_can_edit_entries_of_own_created_forms',
                ),
                'change_payment_status' => array(
                    'wpf_can_edit_all_form_entries',
                    'wpf_can_edit_entries_of_own_created_forms',
                ),
                'delete_submission' => array(
                    'wpf_can_delete_all_entries',
                    'wpf_can_delete_entries_of_own_created_forms',
                )
            ),
            'global' => array(
                'get_global_currency_settings' => array(
                    'wpf_can_change_global_settings'
                ),
                'update_global_currency_settings' => array(
                    'wpf_can_change_global_settings'
                ),
                'wpf_upload_image' => array(
                    'wpf_can_change_global_settings',
                    'wpf_can_view_menus'
                ),
                'set_payment_settings' => array(
                    'wpf_can_change_payment_settings'
                )
            )
        );

        if (!$group || !isset($permissionGroups[$group])) {
            return array_merge(
                $permissionGroups['forms'],
                $permissionGroups['submissions'],
                $permissionGroups['global']
            );
        }

        return $permissionGroups[$group];
    }

    public static function getPermissionLists()
    {
        return array(
            'wpf_full_access',
            'wpf_can_view_menus',
            // FOrm Related Actions
            'wpf_can_edit_all_forms',
            'wpf_can_add_form',
            'wpf_can_edit_own_form',
            'wpf_can_delete_all_forms',
            'wpf_can_delete_own_created_forms',
            // Submission Related Actions
            'wpf_can_view_all_entries',
            'wpf_can_view_entries_of_own_created_forms',
            'wpf_can_delete_all_entries',
            'wpf_can_delete_entries_of_own_created_forms',
            'wpf_can_edit_all_form_entries',
            'wpf_can_edit_entries_of_own_created_forms',
            // Global Settings Related
            'wpf_can_change_global_settings',
            'wpf_can_change_payment_settings'
        );
    }

    public static function giveCustomAccess()
    {
        $customRoles = get_option('_wppayform_form_permission');
        if (is_string($customRoles)) {
            $customRoles = [];
        }

        if (!$customRoles) {
            return array(
                'has_access' => false,
                'role'  => ''
            );
        }

        $hasAccess = false;
        $menuPermission = "";
        foreach ($customRoles as $roleName) {
            if (current_user_can($roleName)) {
                $hasAccess = true;
                $menuPermission = $roleName;
            }
        }

        return array(
                'has_access' => $hasAccess,
                'role'  => $menuPermission
            );
    }

    public function getAccessRoles()
    {
        if (!current_user_can('manage_options')) {
            return array(
                'capability' => array(),
                'roles' => array()
            );
        }

        if (!function_exists('get_editable_roles')) {
            require_once ABSPATH . 'wp-admin/includes/user.php';
        }
        $roles = \get_editable_roles();

        $formatted = array();
        foreach ($roles as $key => $role) {
            if ($key == 'administrator') {
                continue;
            }
            if ($key != 'subscriber') {
                $formatted[] = array(
                    'name' => $role['name'],
                    'key' => $key
                );
            }
        }

        $capability = get_option('_wppayform_form_permission');

        if (is_string($capability)) {
            $capability = [];
        }
        return array(
            'capability' => $capability,
            'roles' => $formatted
        );
    }

    public function setAccessRoles($request)
    {
        if (current_user_can('manage_options')) {
            $capability = Arr::get($request, 'capability', []);
            update_option('_wppayform_form_permission', $capability, 'no');
            return array(
                'message' => __('Successfully updated the role(s).', 'wp-payment-form')
            );
        } else {
            throw new \Exception(__('Sorry, You can not update permissions. Only administrators can update permissions', 'wp-payment-form'));
        }
    }
}
