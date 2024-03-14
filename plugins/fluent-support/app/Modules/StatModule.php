<?php

namespace FluentSupport\App\Modules;

use FluentSupport\App\Models\Agent;
use FluentSupport\App\Models\Conversation;
use FluentSupport\App\Models\Ticket;
use FluentSupport\App\Models\Product;

/**
 * StatModule class is responsible for getting data related to report
 * @package FluentSupport\App\Modules
 *
 * @version 1.0.0
 */
class StatModule
{
    /**
     * getAgentStat method will return ticket statistics by an agent id
     * This method will get agent id, start date and end date as parameter and fetch the ticket information and return
     * @param $agentId
     * @param false $startDate
     * @param false $endDate
     * @return array[]
     */
    public static function getAgentStat($agentId, $startDate = false, $endDate = false)
    {
        if (!$startDate) {
            $currentDate = current_time('mysql');
            $startDate = $currentDate;
            $endDate = $currentDate;
        }

        $startDate = date('Y-m-d 00:00.01', strtotime($startDate));
        $endDate = date('Y-m-d 23:59.59', strtotime($endDate));

        //Get list of new ticket by agent
        $newTickets = Ticket::where('agent_id', $agentId)
            ->where('status', 'new')
            ->count();

        //Get list of active ticket by agent
        $activeTickets = Ticket::where('agent_id', $agentId)
            ->where('status', 'active')
            ->count();

        //Get list of closed ticket by agent within a date range(default today)
        $closedTickets = Ticket::where('agent_id', $agentId)
            ->where('status', 'closed')
            ->whereBetween('resolved_at', [$startDate, $endDate])
            ->count();

        //Get list of response by agent id within a date range(default today)
        $responses = Conversation::where('conversation_type', 'response')
            ->where('person_id', $agentId)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->count();

        //Count the response in tickets within a date range(default today) for agent
        $interactions = Conversation::where('person_id', $agentId)
            ->where('conversation_type', 'response')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('ticket_id')
            ->get()
            ->count();

        return [
            'new_tickets'    => [
                'title' => __('New Tickets', 'fluent-support'),
                'count' => $newTickets
            ],
            'active_tickets' => [
                'title' => __('Active Tickets', 'fluent-support'),
                'count' => $activeTickets
            ],
            'closed_tickets' => [
                'title' => __('Closed Tickets', 'fluent-support'),
                'count' => $closedTickets
            ],
            'responses'      => [
                'title' => __('Responses', 'fluent-support'),
                'count' => $responses
            ],
            'interactions'   => [
                'title' => __('Interactions', 'fluent-support'),
                'count' => $interactions
            ]
        ];
    }

    /**
     * getOverAllStats method will return the overall statistics for all tickets
     * This method will count the ticket number by ticket status and return the array
     * @return array[]
     */
    public static function getOverAllStats()
    {
        $newTickets = Ticket::where('status', 'new')
            ->count();

        $activeTickets = Ticket::where('status', 'active')
            ->count();

        $closedTickets = Ticket::where('status', 'closed')
            ->count();

        $responses = Conversation::where('conversation_type', 'response')
            ->count();

        return [
            'new_tickets'    => [
                'title' => __('New Tickets', 'fluent-support'),
                'count' => $newTickets
            ],
            'active_tickets' => [
                'title' => __('Active Tickets', 'fluent-support'),
                'count' => $activeTickets
            ],
            'closed_tickets' => [
                'title' => __('Closed Tickets', 'fluent-support'),
                'count' => $closedTickets
            ],
            'responses'      => [
                'title' => __('Responses', 'fluent-support'),
                'count' => $responses
            ]
        ];
    }

    /**
     * getTodayStats method will return a stats of today's tickets
     * This method will count the ticket number by ticket status and return the array
     * @param bool|int $agentId By default value set to false however when it gets an agent id it will fetch
     * the result by this id
     * @return array result in array format
     */
    public static function getTodayStats($agentId = false)
    {
        $start = date('Y-m-d 00:00.01');
        $end = date('Y-m-d 23:59.59');

        $newTickets = Ticket::whereBetween('created_at', [$start, $end]);

        $closedTickets = Ticket::where('status', 'closed')->whereBetween('resolved_at', [$start, $end]);

        $responses = Conversation::where('conversation_type', 'response')->whereBetween('created_at', [$start, $end]);

        if ($agentId) {
            $newTickets->where('agent_id', $agentId);
            $closedTickets->where('agent_id', $agentId);
            $responses->where('person_id', $agentId);
        }

        return [
            'new_tickets'    => [
                'title' => __('New Tickets', 'fluent-support'),
                'count' => $newTickets->count()
            ],
            'closed_tickets' => [
                'title' => __('Closed Tickets', 'fluent-support'),
                'count' => $closedTickets->count()
            ],
            'responses'      => [
                'title' => __('Responses', 'fluent-support'),
                'count' => $responses->count()
            ]
        ];
    }

    /**
     * getAgentOverallStats method will produce overall statistics by agent
     * @param $agentId
     * @return array[]
     */
    public static function getAgentOverallStats($agentId)
    {
        //Get count of response by the agent
        $replies_count = Conversation::where('person_id', $agentId)->count();

        //Get the number of interactions/responses by agent with tickets
        $interactions_count = Conversation::where('person_id', $agentId)
            ->where('conversation_type', 'response')
            ->groupBy('ticket_id')
            ->get()
            ->count();

        //Get the number of tickets that are closed by this agent
        $total_closed = Ticket::where('agent_id', $agentId)->where('status', 'closed')->count();

        return [
            'replies_count'      => [
                'title' => __('Total Replies', 'fluent-support'),
                'count' => $replies_count
            ],
            'interactions_count' => [
                'title' => __('Total Interactions', 'fluent-support'),
                'count' => $interactions_count
            ],
            'total_closed'       => [
                'title' => __('Total Closed', 'fluent-support'),
                'count' => $total_closed
            ]
        ];
    }

    /**
     * This `getActiveTicketsByProductStats` method will count today's tickets by product that are waiting for reply
     * @return array $result
     */
    public static function getActiveTicketsByProductStats()
    {
        $products = Product::all();
        $result = [];

        foreach ($products as $product) {
            $result[$product->id] = [
                'title' => $product->title,
                'count' => static::countAwaitingTickets('product_id', $product->id)
            ];
        }

        return $result;
    }


    /**
     * This will count the number of tickets that are waiting for response also it can receive parameters
     * @param string $whereClause // This is the where clause that will be used in the query
     * @param string $whereClauseValue // This is the value of the where clause
     * @return int $awatingTicketCount
     */
    public static function countAwaitingTickets($whereClause = null, $whereClauseValue = null)
    {
        $ticket = new Ticket;

        if ($whereClause && $whereClauseValue) {
            $ticket = $ticket->where(sanitize_text_field($whereClause), sanitize_text_field($whereClauseValue));
        }

        $awatingTicketCount = $ticket->where('status', '!=', 'closed')
            ->where(function ($query) {
                $query->whereColumn('last_agent_response', '<', 'last_customer_response')
                    ->orWhereNull('last_agent_response')
                    ->orWhere('status', 'new');
            })->count();

        return $awatingTicketCount;
    }

    /**
     * This method will return a summary of today's stats of all agent
     * @return array
     */
    public static function getAgentTodayStats()
    {
        $stats = [];
        Agent::select(['id', 'first_name'])->get()->each(function ($agent) use (&$stats) {
            $agentStat = static::getTodayStats($agent->id);
            $waiting = static::countAwaitingTickets('agent_id', $agent->id);
            if(!empty($agentStat['responses']['count']) || $waiting) {
                $stats[] = [
                    'agent_name' => $agent->first_name,
                    'stats'      => array_merge(
                        [
                            'waiting_today' => [
                                'title' => __('Waiting Today', 'fluent-support'),
                                'count' => $waiting
                            ]
                        ],
                        $agentStat
                    )
                ];
            }
        });

        return $stats;
    }

}
