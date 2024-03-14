<?php

namespace FluentSupport\App\Services;

use FluentSupport\App\Models\Agent;
use FluentSupport\App\Models\Conversation;
use FluentSupport\App\Models\Meta;
use FluentSupport\App\Models\TagPivot;
use FluentSupport\App\Models\Ticket;
use FluentSupport\App\Modules\PermissionManager;

class TicketHelper
{
    /**
     * getActivity method will return the activity in a ticket by agent
     * @param $ticketId
     * @param false $currentAgentId
     * @return array
     */
    public static function getActivity($ticketId, $currentAgentId = false)
    {
        //Get the ticket meta information
        $meta = Meta::where('object_type', 'ticket_meta')
            ->where('key', '_live_activity')
            ->where('object_id', $ticketId)
            ->first();

        $activities = [];
        if ($meta) {
            $activities = maybe_unserialize($meta->value);
        }

        foreach ($activities as $index => $activity) {
            if ((time() - $activity) > 60) {
                unset($activities[$index]);
            }
        }

        if (!$currentAgentId) {
            return self::getAgentsInfoFromActivities($activities);
        }

        $activities[$currentAgentId] = time();

        if ($meta) {
            $meta->value = maybe_serialize($activities);
            $meta->save();
        } else {
            Meta::insert([
                'object_type' => 'ticket_meta',
                'key' => '_live_activity',
                'object_id' => $ticketId,
                'value' => maybe_serialize($activities)
            ]);
        }

        return self::getAgentsInfoFromActivities($activities);
    }

    public static function getAgentsInfoFromActivities($activities)
    {
        if (!$activities) {
            return [];
        }

        return Agent::select(['email', 'first_name', 'last_name'])
            ->whereIn('id', array_keys($activities))
            ->get();
    }

    /**
     * removeFromActivities method will remove the old live activity by agent and ticket id
     * @param $ticketId
     * @param $agentId
     * @return bool
     */
    public static function removeFromActivities($ticketId, $agentId)
    {
        $meta = Meta::where('object_type', 'ticket_meta')
            ->where('key', '_live_activity')
            ->where('object_id', $ticketId)
            ->first();

        $activities = [];
        if ($meta) {
            $activities = maybe_unserialize($meta->value);
        }

        if (!$activities) {
            return false;
        }

        unset($activities[$agentId]);
        foreach ($activities as $index => $activity) {
            if ((time() - $activity) > 60) {
                unset($activities[$index]);
            }
        }

        $meta->value = maybe_serialize($activities);
        $meta->save();

        return true;
    }

    /**
     * getSuggestedTickets method will return the list of Suggested tickets
     * This method will get the agent id as parameter and fetch ticket information that are waiting to reply or unassigned
     * @param $agentId
     * @param int $limit
     * @return mixed
     */
    public static function getSuggestedTickets($agentId, $limit = 5)
    {
        //Get lis of tickets which are waiting for reply
        $tickets = Ticket::where('agent_id', $agentId)
            ->where('status', '!=', 'closed')
            ->applyFilters([
                'waiting_for_reply' => 'yes'
            ])
            ->oldest('last_customer_response')
            ->limit($limit)
            ->with('customer')
            ->get();

        //If no ticket is available for reply and logged-in user has permission to manage unassigned tickets
        if($tickets->isEmpty() && PermissionManager::currentUserCan('fst_manage_unassigned_tickets')) {
            //Get the ticket list which status is not closed and agent id is null or 0
            $tickets = Ticket::where('status', '!=', 'closed')
                ->oldest('id')
                ->where(function ($q) {
                    $q->whereNull('agent_id');
                    $q->orWhere('agent_id', '0');
                })
                ->with('customer')
                ->limit($limit)
                ->get();
        }


        return $tickets;

    }

    // This method will return all tagged/mentioned/watcher ticket's ids for filtering
    public static function getWatcherTicketIds($agentId)
    {
        $mentioned  = TagPivot::where('source_type', 'ticket_watcher')
            ->where('tag_id', $agentId)
            ->latest('id')
            ->get(['source_id']);

        $ticketIds = array_column($mentioned->toArray(), 'source_id');
        return $ticketIds;
    }

    // This method will return all tagged/mentioned/watcher tickets of logged in agent
    public static function getTicketsToWatch()
    {
        $agent = Helper::getCurrentAgent();

        $tickets = Ticket::with('customer')
            ->limit(5)
            ->join('fs_tag_pivot', 'fs_tag_pivot.source_id', '=', 'fs_tickets.id')
            ->where('fs_tag_pivot.source_type', '=', 'ticket_watcher')
            ->where('fs_tag_pivot.tag_id', '=', $agent->id)
            ->select(['fs_tickets.*'])
            ->get();

        return $tickets;
    }

    // This method will return all ticket watcher inside a ticket
    public static function getWatchers($watchers)
    {
        $watcherAgents = [];

        foreach ($watchers as $watcher) {
            $watcherAgents[] = Agent::where('id', absint($watcher->tag_id))->select(['id', 'first_name', 'last_name'])->first();
        }

        return $watcherAgents;
    }

    public static function getCarbonCopyCustomerInfo($ticketId){
        $existing = Meta::where('object_type', 'beginning_cc_info')->where('object_id', $ticketId)->first();
        if($existing){
            return maybe_unserialize($existing->value, []);
        }

        return [];
    }

    // This method will count total tickets
    public static function countAllTickets()
    {
        return (new Ticket())->count();
    }
    // This method will count all un-assigned tickets
    public static function countUnassignedTickets()
    {
        return Ticket::whereNull('agent_id')->count();
    }
    // This method will count all closed tickets
    public static function countClosedTickets()
    {
        return Ticket::where('status', 'closed')->count();
    }

    // This method will count all New tickets
    public static function countNewTickets()
    {
        return Ticket::where('status', 'new')->count();
    }

    // This method will count all Active tickets
    public static function countActiveTickets()
    {
        return Ticket::where('status', 'active')->count();
    }
}
