<?php

namespace FluentSupport\App\Modules;

use FluentSupport\App\Services\Helper;

/**
 *  PermissionManager class is responsible for getting/settings data related to permission
 * @package FluentSupport\App\Modules
 *
 * @version 1.0.0
 */

class PermissionManager
{
    /**
     * pluginPermissions method will return the list of permissions support by Fluent Support Plugin
     * @return string[]
     */
    public static function pluginPermissions()
    {
        return [
            'fst_view_dashboard',
            'fst_manage_own_tickets',
            'fst_manage_unassigned_tickets',
            'fst_manage_other_tickets',
            'fst_delete_tickets',
            'fst_assign_agents',
            'fst_manage_settings',
            'fst_sensitive_data',
            'fst_manage_workflows',
            'fst_run_workflows',
            'fst_view_all_reports',
            'fst_manage_saved_replies',
            'fst_view_activity_logs',
            'fst_merge_tickets',
            'fst_split_ticket',
            'fst_agent_today_performance',
            'fst_draft_reply',
            'fst_approve_draft_reply'
        ];
    }

    /**
     * attachPermissions method will add selected permission to the user
     * @param $user
     * @param $permissions
     * @return false|mixed
     */
    public static function attachPermissions($user, $permissions)
    {
        if (is_numeric($user)) {
            $user = get_user_by('ID', $user);
        }

        if (!$user) {
            return false;
        }

        if (user_can($user, 'manage_options')) {
            return $user;
        }

        $allPermissions = self::pluginPermissions();
        foreach ($allPermissions as $permission) {
            $user->remove_cap($permission);
        }

        $permissions = array_intersect($allPermissions, $permissions);

        foreach ($permissions as $permission) {
            $user->add_cap($permission);
        }

        return $user;
    }

    /**
     * getUserPermissions method will get all permissions for a user
     * @param false $user
     * @return array|string[]
     */
    public static function getUserPermissions($user = false)
    {
        if (is_numeric($user)) {
            $user = get_user_by('ID', $user);
        }

        if (!$user) {
            return [];
        }

        $pluginPermission = self::pluginPermissions();

        if ($user->has_cap('manage_options')) {
            $pluginPermission[] = 'administrator';
            $pluginPermission = array_values(array_diff($pluginPermission, ['fst_draft_reply'])); //Remove draft reply permission from here
            return $pluginPermission;
        }

        return array_values(array_intersect(array_keys($user->allcaps), $pluginPermission));
    }

    /**
     * currentUserPermissions method will return the permission of logged-in user
     * @param bool $cached
     * @return array|mixed|string[]
     */
    public static function currentUserPermissions($cached = true)
    {
        static $permissions;

        if ($permissions && $cached) {
            return $permissions;
        }

        $permissions = self::getUserPermissions(get_current_user_id());

        return $permissions;
    }

    /**
     * currentUserCan method will return whether a user has the selected permission or not
     * @param $permission
     * @return bool
     */
    public static function currentUserCan($permission)
    {
        if (current_user_can('manage_options')) {
            return true;
        }

        return current_user_can($permission);
    }

    /**
     * currentUserTicketsPermissionLevel method will return the permission level for a user in tickets
     * @return string
     */
    public static function currentUserTicketsPermissionLevel()
    {
        $permissions = self::currentUserPermissions();

        if (in_array('fst_manage_other_tickets', $permissions)) {
            return 'all';
        }

        if (in_array('fst_manage_unassigned_tickets', $permissions)) {
            return 'own_plus';
        }

        return 'own';
    }

    /**
     * agentTicketPermissionLevel method will return the access level of an agent in tickets
     * @param false $userId
     * @return string
     */
    public static function agentTicketPermissionLevel($userId = false)
    {
        if(!$userId) {
            $userId = get_current_user_id();
        }

        $permissions = self::getUserPermissions($userId);

        if (in_array('fst_manage_other_tickets', $permissions)) {
            return 'all';
        }

        if (in_array('fst_manage_unassigned_tickets', $permissions)) {
            return 'own_plus';
        }

        return 'own';
    }

    /**
     * hasTicketPermission method will return whether the selected user has permission in selected ticket or not
     * @param $ticket
     * @return bool
     */
    public static function hasTicketPermission($ticket)
    {
        $permissionLevel = self::currentUserTicketsPermissionLevel();

        if ($permissionLevel == 'all') {
            return true;
        }

        $agent = Helper::getAgentByUserId();

        if ($ticket->agent_id == $agent->id) {
            return true;
        }

        return !$ticket->agent_id && $permissionLevel == 'own_plus';
    }

    /**
     * getReadablePermissionGroups method will return the permission group as array
     * @return array[]
     */
    public static function getReadablePermissionGroups()
    {
        return [
            [
                'title'       => __('Tickets Permissions', 'fluent-support'),
                'permissions' => [
                    'fst_view_dashboard'            => __('View Dashboard', 'fluent-support'),
                    'fst_manage_own_tickets'        => __('Manage Own Tickets', 'fluent-support'),
                    'fst_manage_unassigned_tickets' => __('Manage Unassigned Tickets', 'fluent-support'),
                    'fst_manage_other_tickets'      => __('Manage Others Tickets', 'fluent-support'),
                    'fst_assign_agents'             => __('Assign Agents', 'fluent-support'),
                    'fst_delete_tickets'            => __('Delete Tickets', 'fluent-support'),
                    'fst_merge_tickets'             => __('Merge Tickets', 'fluent-support'),
                    'fst_split_ticket'              => __('Split Ticket', 'fluent-support'),
                    'fst_draft_reply'               => __('Draft Reply', 'fluent-support'),
                    'fst_approve_draft_reply'       => __('Approve Draft Reply', 'fluent-support'),
                ]
            ],
            [
                'title'       => __('Workflow Permissions', 'fluent-support'),
                'permissions' => [
                    'fst_manage_workflows'     => __('Manage Workflows', 'fluent-support'),
                    'fst_run_workflows'        => __('Run workflows', 'fluent-support'),
                    'fst_manage_saved_replies' => __('Manage Saved Replies', 'fluent-support')
                ]
            ],
            [
                'title'       => __('Settings', 'fluent-support'),
                'permissions' => [
                    'fst_manage_settings' => __('Manage Overall Settings', 'fluent-support'),
                    'fst_sensitive_data'  => __('Access Private Data (Customers, Agents)', 'fluent-support')
                ]
            ],
            [
                'title'       => __('Reporting', 'fluent-support'),
                'permissions' => [
                    'fst_view_all_reports'   => __('View All Reports', 'fluent-support'),
                    'fst_view_activity_logs' => __('View Activity Logs', 'fluent-support'),
                    'fst_agent_today_performance' => __('View Agent Today Performance', 'fluent-support'),
                ]
            ]
        ];
    }
}
