<?php

namespace FluentSupport\App\Http\Controllers;

use FluentSupport\App\Http\Requests\TicketRequest;
use FluentSupport\App\Http\Requests\TicketResponseRequest;
use FluentSupport\App\Models\Conversation;
use FluentSupport\App\Models\Ticket;
use FluentSupport\App\Services\FluentCRMServices;
use FluentSupport\App\Services\Helper;
use FluentSupport\App\Services\ProfileInfoService;
use FluentSupport\App\Services\TicketHelper;
use FluentSupport\Framework\Request\Request;
use FluentSupport\App\Modules\PermissionManager;
use FluentSupport\App\Services\Tickets\TicketService;

/**
 *  TicketController class for REST API related to ticket
 * This class is responsible for getting / inserting/ modifying data for all request related to ticket
 * @package FluentSupport\App\Http\Controllers
 *
 * @version 1.0.0
 */
class TicketController extends Controller
{
    /**
     * This `me` method will return the current user profile info
     * @param Request $request
     * @param ProfileInfoService $profileInfoService
     * @return array
     */
    public function me(Request $request, ProfileInfoService $profileInfoService)
    {
        $user = wp_get_current_user();
        $settings = [
            'user_id'     => $user->ID,
            'email'       => $user->user_email,
            'person'      => Helper::getAgentByUserId($user->ID),
            'permissions' => PermissionManager::currentUserPermissions(),
            'request'     => $request->all()
        ];

        $withPortalSettings = $request->getSafe('with_portal_settings');

        return $profileInfoService->me($settings, $withPortalSettings);
    }

    /**
     * index method will return the list of ticket based on the selected filter
     * @param Request $request
     * @return array
     */
    public function index(Request $request, TicketService $ticketService)
    {
        //Selected filter type, either simple or Advanced
        $filterType = $request->getText('filter_type', 'simple');
        $data = $request->all();
        return $ticketService->getTickets($data, $filterType);
    }

    /**
     * createTicket method will create new ticket as well as customer or WP user
     * @param Request $request
     * @param Ticket $ticket
     * @return array
     * @throws \Exception
     */
    public function createTicket(TicketRequest $request, Ticket $ticket)
    {
        $data = $request->sanitize();

        $ticketData = $data['ticket'];

        if (!empty($data['attachments'])) {
            $ticketData['attachments'] = $data['attachments'];
        }

        $maybeNewCustomer = $data['newCustomer'];

        $createdTicket = $ticket->createTicket($ticketData, $maybeNewCustomer);

        if (is_wp_error($createdTicket)) {
            return $this->sendError([
                'message' => $createdTicket->get_error_message()
            ]);
        }

        return [
            'message' => 'Ticket has been successfully created',
            'ticket'  => $createdTicket
        ];
    }

    /**
     * getTicket method will return ticket information by ticket id
     * @param Request $request
     * @param $ticket_id
     * @return array
     */
    public function getTicket(Request $request, Ticket $ticket, $ticket_id)
    {
        try {
            $ticketWith = $request->getSafe('with', 'sanitize_text_field');
            if (!$ticketWith) {
                $ticketWith = ['customer', 'agent', 'product', 'mailbox', 'tags', 'attachments' => function ($q) {
                    $q->whereIn('status', ['active', 'inline']);
                }];
            }
            $withCrmData = in_array('fluentcrm_profile', $request->query('with_data', []));

            return $ticket->getTicket($ticketWith, $withCrmData, $ticket_id);
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }

    /**
     * createResponse method will create response by agent for the ticket
     * @param Request $request
     * @param Ticket $ticket
     * @param int $ticket_id
     * @return array
     * @throws \FluentSupport\Framework\Validator\ValidationException
     */
    public function createResponse(TicketResponseRequest $request, Ticket $ticket, $ticket_id)
    {

        $data = $request->sanitize();

        try {
            return $ticket->createResponse($data, $ticket_id);
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }

    /**
     * createDraft method will create draft by agent for the ticket
     * @param Request $request
     * @param Ticket $ticket
     * @param int $ticket_id
     * @return array
     * @throws \FluentSupport\Framework\Validator\ValidationException
     */
    public function createOrUpdatDraft(TicketResponseRequest $request, Ticket $ticket, $ticket_id)
    {

        $data = $request->sanitize();

        try {
            return $ticket->addOrUpdatDraft($data, $ticket_id);
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }

    public function getDraft(Ticket $ticket, $ticket_id)
    {
        try {
            return $ticket->fetchDraft($ticket_id);
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }

    public function deleteDraft(Ticket $ticket, $draft_id)
    {
        $draft_id = intval($draft_id);

        try {
            return $ticket->removeDraft($draft_id);
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }

    /**
     * getTicketWidgets method generate additional information for a ticket by  customer
     * @param Ticket $ticket
     * @param $ticket_id
     * @return array
     */
    public function getTicketWidgets(Ticket $ticket, $ticket_id)
    {
        try {
            return $ticket->getTicketWidgets($ticket_id);
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }

    /**
     * updateTicketProperty method will update ticket property
     * @param Request $request
     * @param Ticket $ticket
     * @param $ticket_id
     * @return array
     */
    public function updateTicketProperty(Request $request, Ticket $ticket, $ticket_id)
    {
        $propName = $request->getSafe('prop_name', 'sanitize_text_field');
        $propValue = $request->getSafe('prop_value', 'sanitize_text_field' );

        try {
            return $ticket->updateTicketProperty($propName, $propValue, $ticket_id);
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }

    /**
     * closeTicket method close the ticket by id
     * @param Ticket $ticket
     * @param int $ticket_id
     * @return array
     */
    public function closeTicket(Ticket $ticket, $ticket_id)
    {
        try {
            return $ticket->closeTicket($ticket_id, $this->request->getSafe('close_ticket_silently'));
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }

    /**
     * reOpenTicket method will reopen a closed ticket
     * @param Request $request
     * @param $ticket_id
     * @return array
     */
    public function reOpenTicket(Ticket $ticket, $ticket_id)
    {
        try {
            return $ticket->reOpenTicket($ticket_id);
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }

    /**
     * doBulkActions method is responsible for bulk action
     * This function will get ticket ids and action as parameter and perform action based on the selection
     * @param Request $request
     * @param Ticket $ticket
     * @return array|string[]|void
     * @throws \Exception
     */
    public function doBulkActions(Request $request, Ticket $ticket)
    {
        $action = $request->getSafe('bulk_action', 'sanitize_text_field'); //get action
        $ticket_ids = $request->get('ticket_ids', []);
        $sanitizedTicketIds = array_map('intval', $ticket_ids);

        try {
            return $ticket->handleBulkActions($action, $sanitizedTicketIds);
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }
    /**
     * deleteTicket method will delete a ticket
     * @param Request $request
     * @param TicketService $ticketService
     * @return array
     */
    public function deleteTicket(TicketService $ticketService, $ticket_id)
    {
        $ticket = Ticket::findOrFail($ticket_id);
        try {
            return $ticketService->delete($ticket);
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }

    /**
     * doBulkReplies method will create response for bulk tickets
     * This function will get ticket ids, content, attachment etc and create response for tickets
     * @param Request $request
     * @param Conversation $conversation
     * @return array
     * @throws \Exception
     */
    public function doBulkReplies(Request $request, Conversation $conversation)
    {
        $data = $request->get();
        $this->validate($data, [
            'content'    => 'required',
            'ticket_ids' => 'required|array'
        ]);

        try {
            return $conversation->doBulkReplies($data);
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }

    /**
     * deleteResponse method will remove a response from ticket by ticket id and response id
     * @param Request $request
     * @param Conversation $conversation
     * @param $ticket_id
     * @param $response_id
     * @return array
     */
    public function deleteResponse(Conversation $conversation, $ticket_id, $response_id)
    {
        try {
            return $conversation->deleteResponse($ticket_id, $response_id);
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }

    /**
     * updateResponse method will update ticket response using ticket and response id
     * @param Request $request
     * @param Conversation $conversation
     * @param int $ticket_id
     * @param int $response_id
     * @return array
     * @throws \Exception
     */
    public function updateResponse(TicketResponseRequest $request, Conversation $conversation, $ticket_id, $response_id)
    {
        $data = $request->getSafe(['content','ticket_id','response_id']);

        try {
            return $conversation->updateResponse($data, $ticket_id, $response_id);
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }

    public function approveDraftResponse(TicketResponseRequest $request, Conversation $conversation, $ticket_id, $response_id)
    {
        $data = [
            'content' => $request->getSafe('content', 'sanitize_text_field')
        ];
        $conversationType = 'response';

        try {
            return $conversation->publishDraftResponse($data, $ticket_id, $response_id, $conversationType);
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }

    /**
     * getLiveActivity method will return the activity in a ticket by agents
     * @param Request $request
     * @param $ticket_id
     * @return array
     */
    public function getLiveActivity(Request $request, $ticket_id)
    {
        $agent = Helper::getAgentByUserId();

        return [
            'live_activity' => TicketHelper::getActivity($ticket_id, $agent->id)
        ];
    }

    /**
     * removeLiveActivity method will remove activities that
     * @param Request $request
     * @param $ticket_id
     * @return array
     */
    public function removeLiveActivity(Request $request, $ticket_id)
    {
        $agent = Helper::getAgentByUserId();

        return [
            'result'   => TicketHelper::removeFromActivities($ticket_id, $agent->id),
            'agent_id' => $agent->id
        ];
    }

    /**
     * addTag method will add tag in ticket by ticket id
     * @param Request $request
     * @param $ticket_id
     * @return array
     */
    public function addTag(Request $request, $ticket_id)
    {
        $ticket = Ticket::findOrFail($ticket_id);

        $ticket->applyTags($request->getSafe('tag_id', 'intval'));

        return [
            'message' => __('Tag has been added to this ticket', 'fluent-support'),
            'tags'    => $ticket->tags
        ];
    }

    /**
     * detachTag method will remove all tags from tickets
     * @param $ticket_id
     * @param $tag_id
     * @return array
     */
    public function detachTag($ticket_id, $tag_id)
    {
        $ticket = Ticket::findOrFail($ticket_id);
        $ticket->detachTags($tag_id);

        return [
            'message' => __('Tag has been removed from this ticket', 'fluent-support'),
            'tags'    => $ticket->tags
        ];
    }

    /**
     * changeTicketCustomer method will update customer in a ticket
     * This method will get ticket id and customer id as parameter, it will replace existing customer id with new
     * @param Request $request
     * @return array
     */
    public function changeTicketCustomer(Request $request)
    {
        $updateCustomer = Ticket::where('id', $request->getSafe('ticket_id', 'intval'))
            ->update(['customer_id' => $request->getSafe('customer', 'intval')]);
        return [
            'message'         => __('Customer has been updated', 'fluent-support'),
            'updatedCustomer' => $updateCustomer
        ];
    }

    /**
     * getTicketCustomData method will return the custom data by ticket id
     * @param Request $request
     * @param $ticket_id
     * @return array|array[]
     */
    public function getTicketCustomData(Request $request, $ticket_id)
    {
        if (!defined('FLUENTSUPPORTPRO')) {
            return [
                'custom_data'     => [],
                'rendered_fields' => []
            ];
        }

        $ticket = Ticket::findOrFail($ticket_id);

        return [
            'custom_data'     => (object)$ticket->customData(),
            'rendered_fields' => \FluentSupportPro\App\Services\CustomFieldsService::getRenderedPublicFields($ticket->customer)
        ];
    }

    /**
     * syncFluentCrmTags method will synchronize the tags with Fluent CRM by contact id
     *This function will get contact id and tags as parameter, get existing tags from crm and updated added/removed tags
     * @param Request $request
     * @param FluentCRMServices $fluentCRMServices
     * @return array
     */
    public function syncFluentCrmTags(Request $request, FluentCRMServices $fluentCRMServices)
    {
        $data = $request->only(['contact_id', 'tags']);
        try {
            return $fluentCRMServices->syncCrmTags($data);
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }

    /**
     * This `syncFluentCrmLists` method will synchronize the lists with Fluent CRM by contact id
     *  This method will get contact id and lists as parameter, get existing lists from crm and updated added/removed lists
     * @param Request $request
     * @param FluentCRMServices $fluentCRMServices
     * @return array
     */

    public function syncFluentCrmLists(Request $request, FluentCRMServices $fluentCRMServices)
    {
        $data = $request->only(['contact_id', 'lists']);
        try {
            return $fluentCRMServices->syncCrmLists($data);
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }
}
