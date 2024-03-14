<?php

namespace FluentSupport\App\Models\Traits;

use FluentSupport\App\Models\Attachment;
use FluentSupport\App\Models\Conversation;
use FluentSupport\App\Models\Product;
use FluentSupport\App\Models\Ticket;
use FluentSupport\App\Modules\PermissionManager;
use FluentSupport\App\Models\Person;
use FluentSupport\App\Modules\Reporting\Reporting;
use FluentSupport\App\Modules\StatModule;
use FluentSupport\App\Services\TicketHelper;
use FluentSupport\Framework\Support\Arr;
use Exception;

trait AgentTrait
{
	public function getAgents($search)
    {
        $agents = static::latest()
            ->searchBy($search)
            ->paginate();

        foreach ($agents as $agent) {
            $agent->permissions = PermissionManager::getUserPermissions($agent->user_id);

            if ($agent->user_id) {
                $agent->user_profile = admin_url('user-edit.php?user_id=' . $agent->user_id);
            }

            $agent->replies_count = Conversation::where('person_id', $agent->id)->count();
            $agent->interactions_count = Conversation::where('person_id', $agent->id)->groupBy('ticket_id')->get()->count();

            $agent->telegram_chat_id = $agent->getMeta('telegram_chat_id');
            $agent->slack_user_id = $agent->getMeta('slack_user_id');
            $agent->whatsapp_number = $agent->getMeta('whatsapp_number');
        }

        return $agents;
    }

    public function createAgent(array $data)
    {
        if (!$user = get_user_by('email', $data['email'])) {
        	throw new \Exception('Sorry, Connected user could not be found with the provided email address');
        }

        $data = $this->setAgentInfo($data, $user);

        // check if another agent has same email address
        $person = Person::where('email', $data['email'])->first();

        if ($person) {
            throw new \Exception('Sorry, Another agent/person exist with the same email address. Please use a different email address');
        }


        return $this->setAgentMeta(
        	$user,
        	$data,
        	static::create($data)
        );

    }

    /**
     * Method to update agent info
     * @param array $data
     * @param $agent
     * @return object
     * @throws Exception
     */
    public function updateAgent(array $data, object $agent)
    {
        if (!$user = get_user_by('ID', $agent->user_id)) {
            throw new \Exception('Sorry, Connected user could not be found with the provided email address');
        }

        PermissionManager::attachPermissions($user, Arr::get($data, 'permissions', []));

        $updateData = Arr::only($data, ['first_name', 'last_name', 'title']);
        $updateData['email'] = $user->user_email;

        static::where('id', $agent->id)
            ->update($updateData);

        $agent = $this->setAgentMeta($user, $data, $agent);

        return $agent;
    }


    /**
     * This method will delete an agent by agent id
     * @param int $fallBackAgentId
     * @param int $agentId
     * @return void
     * @throws Exception
     */
    public function deleteAgent($fallBackAgentId, $agentId)
    {
        if ($fallBackAgentId == $agentId) {
            throw new \Exception('Old Agent and New agent is same person');
        }

        $agent = static::findOrFail($agentId);

        PermissionManager::attachPermissions($agent->user_id, []);

        try {
            $newAgent = static::findOrFail($fallBackAgentId);
        } catch (\Exception $e) {
            throw new \Exception('Fallback agent could not be found');
        }

        $this->assignDataToFallbackAgent($agent->id, $newAgent);

        $agent->deleteAllMeta();

        static::where('id', $agentId)->delete();
    }

    /**
     * This method will assign data to fallback agent
     * @param int $agentId
     * @param object $newAgent
     * @return void
     */
    private function assignDataToFallbackAgent($agentId, $newAgent)
    {
        Attachment::where('person_id', $agentId)->update([
            'person_id' => $newAgent->id
        ]);

        Conversation::where('person_id', $agentId)->update([
            'person_id' => $newAgent->id
        ]);

        Product::where('created_by', $agentId)->update([
            'created_by' => $newAgent->id
        ]);

        Ticket::where('agent_id', $agentId)->update([
            'agent_id' => $newAgent->id
        ]);
    }

    public function getAgentStat($stats, $with, $agentId)
    {
        if (PermissionManager::currentUserCan('fst_manage_unassigned_tickets')) {
            $stats['unassigned_tickets'] = [
                'title' => __('Unassigned Tickets', 'fluent-support'),
                'count' => Ticket::whereNull('agent_id')->where('status', '!=', 'closed')->count()
            ];
        }

        $data = [
            'stats' => $stats
        ];

        return $this->agentDashboardWidgets($data, $with, $agentId);
    }

    private function agentDashboardWidgets($data, $with, $agentId)
    {
        //If the request come with suggested_tickets
        if (in_array('suggested_tickets', $with)) {
            //Get suggested tickets from ticketHelper
            $data['suggested_tickets'] = TicketHelper::getSuggestedTickets($agentId);
        }

        //If the request come with mentioned_tickets
        if (defined('FLUENTSUPPORTPRO') && in_array('ticket_to_watch', $with)) {
            //Get the overall statistics by the agent
            $data['ticket_to_watch'] = TicketHelper::getTicketsToWatch();
        }

        //If the request come with overall_stats
        if (in_array('overall_stats', $with)) {
            //Get overall status
            $data['overall_stats'] = (new Reporting())->getActiveStats();
        }

        //If the request come with individual_stat
        if (in_array('individual_stat', $with)) {
            //get overall statistics by agent id
            $data['individual_stat'] = (new Reporting())->getActiveStatByAgent($agentId);
        }
        //If the request come with my_overall_stats
        if (in_array('my_overall_stats', $with)) {
            //Get the overall statistics by the agent
            $data['my_overall_stats'] = StatModule::getAgentOverallStats($agentId);
        }

        if (in_array('tickets_by_products', $with)) {
            //Get tickets by product which are waiting for agent reply
            $data['tickets_by_product'] = StatModule::getActiveTicketsByProductStats();
        }

        if(PermissionManager::currentUserCan('fst_agent_today_performance') && in_array('agent_today_stats', $with)) {
            //Get a list of agents with their today's stats
            $data['agent_today_stats'] = StatModule::getAgentTodayStats();
        }


        return $data;
    }

    /**
     * Method to set agent info
     * @param array $data
     * @param $user
     * @return array
     */
    private function setAgentInfo(array $data, $user)
    {
    	$data['user_id'] = $user->ID;

    	if (empty($data['first_name'])) {
            $data['first_name'] = $user->first_name;
        }

        if (empty($data['last_name'])) {
            $data['last_name'] = $user->last_name;
        }

        return $data;
    }

    /**
     * Method to set agent meta data
     * @param $user
     * @param array $data
     * @param object $agent
     * @return object
     */
    private function setAgentMeta($user, $data, $agent)
    {
    	PermissionManager::attachPermissions($user, Arr::get($data, 'permissions', []));

        if (isset($data['telegram_chat_id'])) {
            $chatId = sanitize_text_field($data['telegram_chat_id']);
            $agent->updateMeta('telegram_chat_id', $chatId);
            $agent->telegram_chat_id = $chatId;
        }

        if (isset($data['slack_user_id'])) {
            $chatId = sanitize_text_field($data['slack_user_id']);
            $agent->updateMeta('slack_user_id', $chatId);
            $agent->slack_user_id = $chatId;
        }

        if (isset($data['whatsapp_number'])) {
            $whatsappNumber = sanitize_text_field($data['whatsapp_number']);
            $agent->updateMeta('whatsapp_number', $whatsappNumber);
            $agent->whatsapp_number = $whatsappNumber;
        }

        return $agent;
    }
}
