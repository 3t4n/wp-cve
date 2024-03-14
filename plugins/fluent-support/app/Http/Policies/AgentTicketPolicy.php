<?php

namespace FluentSupport\App\Http\Policies;

use FluentSupport\App\Modules\PermissionManager;
use FluentSupport\Framework\Request\Request;
use FluentSupport\Framework\Foundation\Policy;

class AgentTicketPolicy extends Policy
{
    /**
     * Check user permission for any method
     * @param \FluentSupport\Framework\Request\Request $request
     * @return Boolean
     */
    public function verifyRequest(Request $request)
    {
        $permissions = PermissionManager::currentUserPermissions();
        $acceptedPermissions = ['fst_manage_own_tickets', 'fst_manage_unassigned_tickets', 'fst_manage_other_tickets', 'fst_merge_tickets'];
        $status = !!array_intersect($permissions, $acceptedPermissions);

        return apply_filters('fluent_support/agent_has_access', $status, $request);
    }

    public function doBulkActions(Request $request)
    {
        $action = $request->get('bulk_action');

        if ($action == 'delete_tickets') {
            return PermissionManager::currentUserCan('fst_delete_tickets');
        }

        return $this->verifyRequest($request);
    }
}
