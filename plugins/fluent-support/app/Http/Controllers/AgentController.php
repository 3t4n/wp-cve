<?php

namespace FluentSupport\App\Http\Controllers;

use FluentSupport\App\Models\Agent;
use FluentSupport\App\Modules\StatModule;
use FluentSupport\App\Services\AvatarUploder;
use FluentSupport\App\Services\Helper;
use FluentSupport\Framework\Request\Request;
use FluentSupport\App\Modules\PermissionManager;
use FluentSupport\App\Http\Requests\AgentCreateRequest;

/**
 *  AgentController class for REST API
 * This class is responsible for getting data for all request related to agent
 *
 * @package FluentSupport\App\Http\Controllers
 *
 * @version 1.0.0
 */
class AgentController extends Controller
{
    public function index(Request $request, Agent $agent)
    {
        return [
            'agents' => $agent->getAgents($request->getSafe('search','sanitize_text_field')),
            'permissions' => PermissionManager::getReadablePermissionGroups()
        ];
    }

    /**
     * addAgent method will add new agent in person table
     * @param AgentCreateRequest $request
     * @return \WP_REST_Response | array
     * @throws \FluentSupport\Framework\Validator\ValidationException
     */
    public function addAgent(AgentCreateRequest $request, Agent $agent)
    {
        $data = [
            'email' => $request->getSafe('email', 'sanitize_email'),
            'first_name' => $request->getSafe('first_name', 'sanitize_text_field'),
            'last_name' => $request->getSafe('last_name', 'sanitize_text_field'),
            'title' => $request->getSafe('title', 'sanitize_text_field'),
            'permissions' => $request->getSafe('permissions', null, []),
        ];

        try {
            return [
                'message' => __('Support Staff has been added', 'fluent-support'),
                'agent'   => $agent->createAgent($data)
            ];

        } catch (\Exception $e) {
            return $this->sendError([
                'message' => $e->getMessage()
            ]);
        }
    }

    /**
     * updateAgent method will update the information of an exiting agent
     * @param AgentCreateRequest $request
     * @param Agent $agent
     * @param $agent_id
     * @return \WP_REST_Response | array
     * @throws \FluentSupport\Framework\Validator\ValidationException
     */
    public function updateAgent(AgentCreateRequest $request, Agent $agent, $agent_id)
    {
        $agent = $agent::findOrFail($agent_id);
        $data = [
            'first_name' => $request->getSafe('first_name', 'sanitize_text_field'),
            'last_name' => $request->getSafe('last_name', 'sanitize_text_field'),
            'title' => $request->getSafe('title', 'sanitize_text_field'),
            'permissions' => $request->getSafe('permissions', null, []),
            'telegram_chat_id' => $request->getSafe('telegram_chat_id', 'sanitize_text_field'),
            'slack_user_id' => $request->getSafe('slack_user_id', 'sanitize_text_field'),
            'whatsapp_number' => $request->getSafe('whatsapp_number', 'sanitize_text_field'),
        ];

        if ($agent) {
            try {
                return [
                    'message' => __('Support Staff has been updated', 'fluent-support'),
                    'agent'   => $agent->updateAgent($data, $agent)
                ];
            } catch (\Exception $e) {
                return $this->sendError([
                    'message' => $e->getMessage()
                ]);
            }
        }

    }

    /**
     * deleteAgent will delete an exiting agent and add an alternative agent as replacement
     * @param Request $request
     * @param Agent $agent
     * @param $agent_id
     * @return \WP_REST_Response | array
     * @throws \FluentSupport\Framework\Validator\ValidationException
     */
    public function deleteAgent(Request $request, Agent $agent, $agent_id)
    {
        try {
            $agent->deleteAgent($request->getSafe('fallback_agent_id', 'intval'), $agent_id);

            return [
                'message' => __('Support Staff has been deleted', 'fluent-support')
            ];

        } catch (\Exception $e) {
            return $this->sendError([
                'message' => $e->getMessage()
            ]);
        }

    }

    /**
     * @param Request $request
     * @return \WP_REST_Response | array
     */
    public function myStats(Request $request)
    {

        $agent = Helper::getAgentByUserId();//Get logged in agent information

        try {
            $stats = StatModule::getAgentStat($agent->id); //Get ticket statistics

            $with = $request->getSafe('with');

            $response = (new Agent())->getAgentStat($stats, $with, $agent->id);

            if (defined('FLUENTSUPPORTPRO')) {
                $response['dashboard_notice'] = apply_filters('fluent_support/dashboard_notice', '', $agent);
            }
            return $response;
        } catch (\Exception $e) {
            return $this->sendError([
                'message' => $e->getMessage()
            ]);
        }
    }

    // /**
    //  * getAgentStat method will return ticket statistics by an agent id
    //  *
    //  * @param Request $request
    //  * @param $agent_id
    //  * @return array
    //  */
    // public function getAgentStat(Request $request, $agent_id)
    // {

    //     $stats = StatModule::getAgentStat($agent_id); //Get ticket statistics

    //     $with = $request->getSafe('with', []);

    //     return (new Agent())->getAgentStat($stats, $with, $agent_id);
    // }

    /**
     * addOrUpdateProfileImage method will upload profile picture for a given agent id
     * For a successful upload it's required to send file object, agent id and the user type(agent)
     * @param Request $request
     * @param AvatarUploder $avatarUploder
     * @return \WP_REST_Response | array
     */
    public function addOrUpdateProfileImage(Request $request, AvatarUploder $avatarUploder)
    {
        try {
            return $avatarUploder->addOrUpdateProfileImage( $request->files(), $request->getSafe('agent_id', 'intval'), 'agent');
        } catch (\Exception $e) {
            return $this->sendError([
                'message' => $e->getMessage()
            ]);
        }
    }

    /**
     * resetAvatar method will restore a Support Staff avatar
     * For a successful upload it's required to send file object, Support Staff id and the user type(Support Staff)
     * @param Agent $agent
     * @param $agent_id
     * @return array
     */
    public function resetAvatar(Agent $agent, $agent_id){
        try {
            $agent->restoreAvatar($agent, $agent_id);

            return [
                'message'  => __('Support Staff avatar reset to gravatar default', 'fluent-support')
            ];
        } catch (\Exception $e) {
            return [
                'message'  => $e->getMessage()
            ];
        }
    }

    public function ping(Request $request)
    {
        return [
            'ping' => 'pong'
        ];
    }
}
