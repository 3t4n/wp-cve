<?php

namespace FluentSupport\App\Hooks\Handlers;

use FluentSupport\App\Modules\PermissionManager;
use FluentSupport\App\Services\Helper;

class PermissionFilterManager
{
    public function init()
    {
        add_action('fluent_support/tickets_query_by_permission_ref', array($this, 'filterAgentTickets'), 10, 2);
    }

    public function filterAgentTickets($ticketsQuery, $userId = false)
    {
        $permissionLevel = PermissionManager::agentTicketPermissionLevel($userId);
        if ($permissionLevel != 'all') {
            $agent = Helper::getAgentByUserId();
            if ($permissionLevel == 'own') {
                $ticketsQuery->where('agent_id', $agent->id);
            } else {
                $ticketsQuery->where(function ($q) use ($agent) {
                    $q->where('agent_id', $agent->id);
                    $q->orWhereNull('agent_id');
                });
            }
        }
    }
}
