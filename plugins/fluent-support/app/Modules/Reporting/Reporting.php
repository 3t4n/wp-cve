<?php

namespace FluentSupport\App\Modules\Reporting;

use FluentSupport\App\Api\Classes\Tickets;
use FluentSupport\App\Models\Agent;
use FluentSupport\App\Models\Conversation;
use FluentSupport\App\Models\MailBox;
use FluentSupport\App\Models\Product;
use FluentSupport\App\Models\Ticket;
use FluentSupport\App\Models\Meta;
use FluentSupport\App\Services\Helper;
use FluentSupport\Framework\Support\Arr;

/**
 * Reporting class is responsible for getting data related to report
 * @package FluentSupport\App\Modules\Reporting
 *
 * @version 1.0.0
 */
class Reporting
{
    use ReportingHelperTrait;

    /**
     * getTicketsGrowth will generate tickets statistics and return
     * @param false $from
     * @param false $to
     * @param array $filters
     * @return array
     */
    public function getTicketsGrowth($from = false, $to = false, $filters = [])
    {
        //Generate report period
        $period = $this->makeDatePeriod(
            $from = $this->makeFromDate($from),//Date from
            $to = $this->makeToDate($to),//Date to
            $frequency = $this->getFrequency($from, $to)// frequency P1D, P1W, P1M
        );

        //Get group by and order by i.e date,week, month
        list($groupBy, $orderBy) = $this->getGroupAndOrder($frequency);

        //get all tickets statistics within the date range
        $query = $this->db()->table('fs_tickets')
            ->select($this->prepareSelect($frequency))
            ->whereBetween('created_at', $this->prepareBetween($frequency, $from, $to))
            ->groupBy($groupBy)
            ->oldest($orderBy);

        //If filter by product or agent or status selected
        if ($filters) {
            if (!empty($filters['statuses'])) {
                $query->whereIn('status', $filters['statuses']);
            }

            if (!empty($filters['product_id'])) {
                $query->where('product_id', $filters['product_id']);
            }

            if (!empty($filters['agent_id'])) {
                $query->where('agent_id', $filters['agent_id']);
            }

            if (!empty($filters['mailbox_id'])) {
                $query->where('mailbox_id', $filters['mailbox_id']);
            }
        }

        $items = $query->get();

        return $this->getResult($period, $items);
    }

    /**
     * getTicketResolveGrowth method will get the statistics for resolved/closed tickets
     * @param false $from
     * @param false $to
     * @param array $filters
     * @return array
     */
    public function getTicketResolveGrowth($from = false, $to = false, $filters = [], $type = '')
    {
        $period = $this->makeDatePeriod(
            $from = $this->makeFromDate($from),//Date from
            $to = $this->makeToDate($to),//date to
            $frequency = $this->getFrequency($from, $to)// frequency P1D, P1W, P1M
        );

        list($groupBy, $orderBy) = $this->getGroupAndOrder($frequency);//Get group by and order by i.e date,week, month

        $filterColumn = (!empty($type)) ? $type.'_id' : 'id';

        //get the closed ticket statistics within the date range
        $query = $this->db()->table('fs_tickets')
            ->select($this->prepareSelect($frequency, 'resolved_at'))
            ->whereBetween('resolved_at', $this->prepareBetween($frequency, $from, $to))
            ->where('status', 'closed')
            ->where($filterColumn, '>', 0)
            ->groupBy($groupBy)
            ->oldest($orderBy);

        //If filter by product or agent is selected
        if ($filters) {
            if (!empty($filters['product_id'])) {
                $query->where('product_id', $filters['product_id']);
            }

            if (!empty($filters['agent_id'])) {
                $query->where('agent_id', $filters['agent_id']);
            }

            if (!empty($filters['mailbox_id'])) {
                $query->where('mailbox_id', $filters['mailbox_id']);
            }
        }

        $items = $query->get();

        return $this->getResult($period, $items);
    }

    /**
     * getResponseGrowth method will generate the statistics for response
     * @param false $from
     * @param false $to
     * @param array $filters
     * @return array
     */
    public function getResponseGrowth($from = false, $to = false, $filters = [])
    {
        $period = $this->makeDatePeriod(
            $from = $this->makeFromDate($from),
            $to = $this->makeToDate($to),
            $frequency = $this->getFrequency($from, $to)
        );

        list($groupBy, $orderBy) = $this->getGroupAndOrder($frequency);

        $query = $this->db()->table('fs_conversations')
            ->select($this->prepareSelect($frequency))
            ->whereBetween('created_at', $this->prepareBetween($frequency, $from, $to))
            ->where('conversation_type', 'response')
            ->groupBy($groupBy)
            ->oldest($orderBy);

        if ($filters) {
            if (!empty($filters['person_id'])) {
                $query->where('person_id', $filters['person_id']);
            }

            if (!empty($filters['product_id'])) {
                $query->where('product_id', $filters['product_id']);
            }
        }

        $items = $query->get();


        return $this->getResult($period, $items);
    }

    public function getResponseGrowthChart($from = false, $to = false, $filters = [], $type = ''): array
    {
        $period = $this->makeDatePeriod(
            $from = $this->makeFromDate($from),
            $to = $this->makeToDate($to),
            $frequency = $this->getFrequency($from, $to)
        );

        list($groupBy, $orderBy) = $this->getGroupAndOrder($frequency);

        $filterColumn = $type."_id";

        $query = $this->db()->table('fs_tickets')
              ->select($this->prepareSelect($frequency,'created_at','response_count'))
              ->whereBetween('created_at', $this->prepareBetween($frequency, $from, $to))
              ->havingRaw('COUNT(response_count)> 0')
              ->where($filterColumn, '>', 0)
              ->groupBy($groupBy)
              ->oldest($orderBy);

        if ($filters) {
            if (!empty($filters['product_id'])) {
                $query->where('product_id', $filters['product_id']);
            }

            if (!empty($filters['mailbox_id'])) {
                $query->where('mailbox_id', $filters['mailbox_id']);
            }
        }

        $items = $query->get();

        return $this->getResult($period, $items);
    }

    /**
     * agentSummary method will prepare ticket summary with responses by agent
     * @param false $from
     * @param false $to
     * @param false $agent
     * @return mixed
     */
    public function agentSummary($from = false, $to = false, $agent = false)
    {
        if(!$from) {
            $from = current_time('Y-m-d');
        }

        if(!$to) {
            $to = current_time('Y-m-d');
        }

        $from .= ' 00:00:00';
        $to .= ' 23:59:59';
        $reports = [];

        //Get tickets statistics that are closed
        $resolves = $this->db()->table('fs_tickets')
            ->select([
                $this->db()->raw('COUNT(id) AS count'),
                'agent_id',
            ])
            ->groupBy('agent_id')
            ->where('status', 'closed')
            ->whereBetween('resolved_at', [$from, $to])
            ->get();

        $reports = $this->pushReportData('closed', $resolves, $reports, 'agent_id');

        //get statistics for all except closed ticket
        $openTickets = $this->db()->table('fs_tickets')
            ->select([
                $this->db()->raw('COUNT(id) AS count'),
                'agent_id'
            ])
            ->groupBy('agent_id')
            ->where('status', '!=', 'closed')
            ->get();

        $reports = $this->pushReportData('opens', $openTickets, $reports, 'agent_id');
        //Get response by agent
        $responses = Conversation::select([
            $this->db()->raw('COUNT(id) AS count'),
            $this->db()->raw('person_id as agent_id'),
            $this->db()->raw('created_at')
        ])
            ->whereHas('person', function ($q) {
                $q->where('person_type', '=', 'agent');
            })
            ->whereBetween('created_at', [$from, $to])
            ->where('conversation_type', 'response')
            ->groupBy('agent_id')
            ->get();

        $reports = $this->pushReportData('responses', $responses, $reports, 'agent_id');
        //Get interactions/responses by individual agents
        foreach ($responses as $response) {
            $reports[$response->agent_id]['interactions'] = Conversation::where('person_id', $response->agent_id)
                ->where('conversation_type', 'response')
                ->whereBetween('created_at', [$from, $to])
                ->groupBy('ticket_id')
                ->get()
                ->count();
        }

        $agentIds = array_keys($reports);

        if ($agent) {
            $agentIds = array_map('intval', explode(',', $agent));
        }

        // get agent feedback statistics
        $agentFeedbackRatingEnabled = Helper::getBusinessSettings('agent_feedback_rating') === 'yes';
        if (defined('FLUENTSUPPORTPRO_PLUGIN_VERSION') && $agentFeedbackRatingEnabled) {
            $agentConversations = Conversation::select([
                $this->db()->raw('person_id as agent_id'),
                $this->db()->raw('GROUP_CONCAT(id) as conversation_ids')
            ])
                ->whereIn('person_id', $agentIds)
                ->whereHas('person', function ($q) {
                    $q->where('person_type', '=', 'agent');
                })
                ->where('conversation_type', 'response')
                ->groupBy('agent_id')
                ->get();

            foreach ($agentConversations as $conversation) {
                $conversationIds = array_map('intval', explode(',', $conversation->conversation_ids));

                $feedbackMeta = Meta::whereIn('object_id', $conversationIds)
                    ->where('key', 'agent_feedback_ratings')
                    ->whereBetween('created_at', [$from, $to])
                    ->get();

                $likeCount = 0;
                $dislikeCount = 0;

                foreach ($feedbackMeta as $feedback) {
                    $feedbackStatus = $feedback->value;

                    if ($feedbackStatus === 'like') {
                        $likeCount++;
                    } elseif ($feedbackStatus === 'dislike') {
                        $dislikeCount++;
                    }
                }

                $agentId = $conversation->agent_id;
                $reports[$agentId]['likes'] = $likeCount;
                $reports[$agentId]['dislikes'] = $dislikeCount;
            }
        }

        $agents = Agent::select(['id', 'first_name', 'last_name'])
            ->whereIn('id', $agentIds)
            ->get();

        foreach ($agents as $agent) {
            $report = NULL;
            if(isset($reports[$agent->id])) {
                $reportFields = [
                    'interactions' => 0,
                    'responses' => 0,
                    'opens' => 0,
                    'closed' => 0,
                    'waiting_tickets' => 0,
                ];

                $additionalFields = defined('FLUENTSUPPORTPRO_PLUGIN_VERSION') && $agentFeedbackRatingEnabled ? ['likes' => 0, 'dislikes' => 0] : [];
                $reportFields = $reportFields + $additionalFields;

                $report = wp_parse_args($reports[$agent->id], $reportFields);
            }
            $agent->stats = $report;
            $agent->active_stat = $this->getActiveStatByAgent($agent->id);
        }
        return $agents;
    }

    public function getSummary($type, $from = null, $to = null)
    {
        global $wpdb;
        $tablePrefix = $wpdb->prefix;

        if (!$from) {
            $from = current_time('Y-m-d');
        }

        if (!$to) {
            $to = current_time('Y-m-d');
        }

        $from .= ' 00:00:00';
        $to .= ' 23:59:59';
        $reports = [];

        $groupByField = $type == 'product' ? 'product_id' : 'mailbox_id';

        $resolves = $this->db()->table('fs_tickets')
                         ->select([
                             $this->db()->raw('COUNT(id) AS count'),
                             $groupByField,
                         ])
                         ->groupBy($groupByField)
                         ->where('status', 'closed')
                         ->whereBetween('resolved_at', [$from, $to])
                         ->get();

        $reports = $this->pushReportData('closed', $resolves, $reports, $groupByField);

        $openTickets = $this->db()->table('fs_tickets')
                            ->select([
                                $this->db()->raw('COUNT(id) AS count'),
                                $groupByField
                            ])
                            ->groupBy($groupByField)
                            ->where('status', '!=', 'closed')
                            ->whereBetween('created_at', [$from, $to])
                            ->get();

        $reports = $this->pushReportData('opens', $openTickets, $reports, $groupByField);

        $responses = $this->db()->table('fs_conversations')
            ->join('fs_tickets', 'fs_tickets.id', '=', 'fs_conversations.ticket_id')
            ->select([
                $this->db()->raw('COUNT(' . $tablePrefix . 'fs_conversations.id) AS count'),
                'fs_tickets.' . $groupByField,
            ])
            ->groupBy('fs_tickets.' . $groupByField)
            ->whereBetween('fs_conversations.created_at', [$from, $to])
            ->get();

        $reports = $this->pushReportData('responses', $responses, $reports, $groupByField);

        $ticketIds = $this->db()->table('fs_tickets')
                          ->select([
                              $groupByField,
                              $this->db()->raw('GROUP_CONCAT(id) AS ticket_ids'),
                          ])
                          ->where($groupByField, '!=', 0)
                          ->groupBy($groupByField)
                          ->get();

        $result = [];
        foreach ($ticketIds as $item) {
            $result[$item->{$groupByField}] = explode(',', $item->ticket_ids);
        }

        foreach ($result as $id => $ticketIds) {
            $interactions = Conversation::whereIn('ticket_id', $ticketIds)
                ->where('conversation_type', 'response')
                ->whereBetween('created_at', [$from, $to])
                ->groupBy('ticket_id')
                ->get()
                ->count();

            $reports[$id]['interactions'] = $interactions;
        }

        $ids = array_keys($reports);

        $types = [
            'product' => [
                'model' => Product::class,
                'fields' => ['id', 'title'],
            ],
            'mailbox' => [
                'model' => MailBox::class,
                'fields' => ['id', 'name'],
            ],
        ];

        $model = $types[$type]['model'];
        $fields = $types[$type]['fields'];

        $items = $model::select($fields)
                       ->whereIn('id', $ids)
                       ->get();

        foreach ($items as $item) {
            $report = isset($reports[$item->id]) ? $reports[$item->id] : [];

            $report = wp_parse_args($report, [
                'responses' => 0,
                'opens' => 0,
                'closed' => 0,
                'interactions' => 0
            ]);
            $item->stats = $report;
            $item->active_stat = '';
        }

        return $items;
    }

    /**
     * pushReportData method will format the ticket summary report
     * @param $type
     * @param $tickets
     * @param $reports
     * @param $groupByField
     * @return array
     */
    private function pushReportData($type, $tickets, $reports, $groupByField): array
    {
        foreach ($tickets as $ticket) {
            $groupKey = $ticket->{$groupByField};

            if (!$groupKey) {
                continue;
            }

            if (!isset($reports[$groupKey])) {
                $reports[$groupKey] = [];
            }

            $reports[$groupKey][$type] = $ticket->count;
        }

        return $reports;
    }

    /**
     * getActiveStats method will return the statistics for active tickets
     * This method will get the list of open tickets calculate the wait times and return results
     * @return array|false
     */
    public function getActiveStats()
    {
        // We will calculate the wait times for open waiting tickets
        $waitStat = Ticket::waitingOnly()
            ->where('status', '!=', 'closed')
            ->whereNotNull('waiting_since')
            ->select([
                $this->db()->raw('avg(UNIX_TIMESTAMP(waiting_since)) as avg_waiting'),
                $this->db()->raw('MIN(UNIX_TIMESTAMP(waiting_since)) as max_waiting'),
                $this->db()->raw('COUNT(*) as total_tickets')
            ])
            ->first();

        if(!$waitStat) {
            return false;
        }

        $waitStat->avg_waiting = intval($waitStat->avg_waiting);
        if($waitStat->avg_waiting > 0) {
            $waitSeconds = time() -  $waitStat->avg_waiting;
            if( $waitSeconds < 172800 && $waitSeconds > 7200) {
                $avgWait = ceil($waitSeconds / 3600) . ' hours';
            } else {
                $avgWait = human_time_diff($waitStat->avg_waiting, time());
            }
        } else {
            $avgWait = 0;
        }

        return [
            'average_waiting' => $avgWait,
            'max_waiting' => (intval($waitStat->max_waiting)) ? human_time_diff(intval($waitStat->max_waiting), time()) : 0,
            'waiting_tickets' => $waitStat->total_tickets
        ];
    }

    /**
     * getActiveStatByAgent method will return the statistics of active tickets for an agent
     * This method will get  agent id as parameter, fetch the list of open tickets by agent id, calculate the wait times and return results
     * @param $agentId
     * @return array|false
     */
    public function getActiveStatByAgent($agentId)
    {
        $waitStat = Ticket::waitingOnly()
            ->where('status', '!=', 'closed')
            ->whereNotNull('waiting_since')
            ->where('agent_id', $agentId)
            ->select([
                $this->db()->raw('avg(UNIX_TIMESTAMP(waiting_since)) as avg_waiting'),
                $this->db()->raw('MIN(UNIX_TIMESTAMP(waiting_since)) as max_waiting'),
                $this->db()->raw('COUNT(*) as total_tickets')
            ])
            ->first();

        if(!$waitStat) {
            return false;
        }

        $waitStat->avg_waiting = intval($waitStat->avg_waiting);
        if($waitStat->avg_waiting > 0) {
            $waitSeconds = time() -  $waitStat->avg_waiting;
            if( $waitSeconds < 172800 && $waitSeconds > 7200) {
                $avgWait = ceil($waitSeconds / 3600) . ' hours';
            } else {
                $avgWait = human_time_diff($waitStat->avg_waiting, time());
            }
        } else {
            $avgWait = 0;
        }

        return [
            'average_waiting' => $avgWait,
            'max_waiting' => (intval($waitStat->max_waiting)) ? human_time_diff(intval($waitStat->max_waiting), time()) : 0,
            'waiting_tickets' => $waitStat->total_tickets
        ];
    }
}
