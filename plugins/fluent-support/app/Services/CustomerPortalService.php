<?php

namespace FluentSupport\App\Services;

use Exception;
use FluentSupport\App\Models\MailBox;
use FluentSupport\App\Models\Meta;
use FluentSupport\App\Models\Ticket;
use FluentSupport\App\Models\Customer;
use FluentSupport\App\Services\Tickets\ResponseService;
use FluentSupport\App\Services\Tickets\TicketService;
use FluentSupport\Framework\Support\Arr;
use FluentSupport\App\Models\Attachment;
use FluentSupport\App\Models\Conversation;

class CustomerPortalService
{
    /**
     * This `getTickets` method is responsible for getting tickets for customer
     * @param object $customer
     * @param array $requestedStatus
     * @return object
     * @throws Exception
     * @since 1.5.7
     */
    public function getTickets($customer, $requestedStatus)
    {
        $this->validateCustomer($customer);

        $statuses = $this->getTicketStatues($requestedStatus);

        return $this->ticketsAdditionalData($customer, $statuses);
    }

    /**
     * getTicket method will get the ticket information with customer and agent as well as response in a ticket by ticket id
     * @param array $customerAdditionalData
     * @param int $ticketId
     * @return array
     * @since 1.5.7
     */
    public function getTicket($customerAdditionalData, $ticketId)
    {
        $ticket = $this->getTicketByID($ticketId);
        $ticket->human_date = sprintf(__('%s ago', 'fluent-support'), human_time_diff(strtotime($ticket->created_at), current_time('timestamp')));

        $customer = $this->getCustomer($customerAdditionalData, $ticket);

        $this->checkCustomerTicketAccess($customer, $ticket);

        return [
            'ticket'     => $this->syncTicketAdditionData($ticket),
            'responses'  => $this->getResponses($ticketId),
            'sign_on_id' => $ticket->customer_id
        ];
    }

    /**
     * This `createTicket` method is responsible for creating ticket for customer
     * @param object $customer
     * @param array $data
     * @param int $mailboxId
     * @return Ticket
     * @throws Exception
     */
    public function createTicket($customer, $data, $mailboxId)
    {
        $this->validateCustomer($customer);

        $data['title'] = sanitize_text_field(wp_unslash($data['title']));
        $data['content'] = wp_specialchars_decode(wp_unslash(wp_kses_post($data['content'])));
        $data['customer_id'] = $customer->id;
        $data['product_source'] = 'local';
        $data['mailbox_id'] = $this->resolveMailboxId($mailboxId);
        $data['source'] = 'web';

        $disabledFields = apply_filters('fluent_support/disabled_ticket_fields', []);
        $this->validateDisabledFields($data, $disabledFields);
        return $this->storeTicket($data, $customer, $disabledFields);
    }


    /**
     * This `createResponse` method is responsible for creating response by customer in a ticket by ticket id, and data
     * @param array $customerAdditionalData
     * @param int $ticketId
     * @param array $data
     * @return array
     * @throws Exception
     * @since 1.5.7
     */
    public function createResponse($customerAdditionalData, $ticketId, $data)
    {
        $data['content'] = wp_specialchars_decode(wp_unslash($data['content']));
        $data['conversation_type'] = 'response';

        $ticket = Ticket::with(['customer'])->findOrFail($ticketId);
        $customer = $this->getCustomer($customerAdditionalData, $ticket);

        $this->checkCustomerTicketAccess($customer, $ticket, 'response');

        $responseData = (new ResponseService())->createResponse($data, $customer, $ticket);

        return [
            'message'  => __('Reply has been added', 'fluent-support'),
            'response' => $responseData['response'],
            'ticket'   => $responseData['ticket']
        ];
    }


    /**
     * This `closeTicket` is responsible for closing ticket by ticket id
     * @param array $customerAdditionalData
     * @param int $ticketId
     * @return array
     * @throws Exception
     */
    public function closeTicket($customerAdditionalData, $ticketId)
    {
        $ticket = Ticket::with(['customer'])->findOrFail($ticketId);
        $customer = $this->getCustomer($customerAdditionalData, $ticket);

        $this->checkCustomerTicketAccess($customer, $ticket, 'close');

        return [
            'message' => __('Ticket has been closed', 'fluent-support'),
            'ticket'  => (new TicketService())->close($ticket, $customer)
        ];
    }

    /**
     * This `reOpenTicket` is responsible for reopening ticket by ticket id
     * @param array $customerAdditionalData
     * @param int $ticketId
     * @return array
     * @throws Exception
     */
    public function reOpenTicket($customerAdditionalData, $ticketId)
    {
        $ticket = Ticket::with(['customer'])->findOrFail($ticketId);
        $customer = $this->getCustomer($customerAdditionalData, $ticket);

        $this->checkCustomerTicketAccess($customer, $ticket, 'reopen');

        return [
            'message' => __('Ticket has been opened again', 'fluent-support'),
            'ticket'  => (new TicketService())->reopen($ticket, $customer)
        ];
    }

    /**
     * This `validateDisabledFields` method is responsible for validating disabled fields
     * @param array $data
     * @param array $disabledFields
     * @return array $data
     * @since 1.5.7
     */
    private function validateDisabledFields($data, $disabledFields)
    {
        if (!in_array('priority', $disabledFields)) {
            $data['priority'] = sanitize_text_field($data['client_priority']);
            $data['client_priority'] = sanitize_text_field($data['client_priority']);
        }

        if (in_array('product_services', $disabledFields)) {
            unset($data['product_id']);
        }

        return $data;
    }


    /**
     * This `storeTicket` method is responsible for storing a ticket in Ticket Model
     * @param array $data
     * @param object $customer
     * @param array $disabledFields
     * @return Ticket
     * @since 1.5.7
     */
    private function storeTicket($data, $customer, $disabledFields)
    {
        /*
         * Filter ticket data
         *
         * @since v1.0.0
         * @param array  $data
         * @param object $customer
         */
        $data = apply_filters('fluent_support/create_ticket_data', $data, $customer);

        /*
         * Action before ticket create
         *
         * @since v1.0.0
         * @param array  $data
         * @param object $customer
         */
        do_action('fluent_support/before_ticket_create', $data, $customer);

        $ticket = Ticket::create($data);

        TicketService::addTicketAttachments($data, $disabledFields, $ticket, $customer);
        $this->addCustomData($data, $ticket);

        do_action('fluent_support/ticket_created', $ticket, $customer);

        return $ticket;
    }


    /**
     * This `addCustomData` method is responsible for adding custom data to ticket
     * @param array $data
     * @param object $ticket
     * @return void
     */
    private function addCustomData($data, $ticket)
    {
        if (defined('FLUENTSUPPORTPRO')) {
            $customData = Arr::get($data, 'custom_data');
            if ($customData) {
                $customData = wp_unslash($customData);
                $ticket->syncCustomFields($customData);
            }
        }
    }

    /**
     * This `validateCustomer` method is responsible for validating customer
     * @param object|null $customer // It can be null if there's no customer
     * @since 1.5.7
     * @throws Exception
     */
    private function validateCustomer($customer)
    {
        if (!$customer) {
            throw new \Exception('Customer not found');
        }

        if ($customer->status == 'inactive') {
            throw new \Exception('Sorry, You do not have access to customer portal');
        }
    }

    /**
     * This `getCustomer` method is responsible for getting customer
     * @param array $customerAdditionalData
     * @param object $ticket
     * @return object $customer
     * @throws Exception
     *
     * @since 1.5.7
     */
    private function getCustomer($customerAdditionalData, $ticket)
    {
        if (Arr::get($customerAdditionalData, 'intended_ticket_hash') && Helper::isPublicSignedTicketEnabled()) {
            $customer = $ticket->customer;
        } else {
            $customer = $this->resolveCustomer(Arr::get($customerAdditionalData, 'on_behalf'), Arr::get($customerAdditionalData, 'user_ip'));
        }

        if (!$customer) {
            throw new \Exception('Sorry! No customer found');
        }

        return $customer;
    }

    /**
     * This `getTicketStatues` method is responsible for getting ticket statuses
     * @param array $requestedStatus
     * @return array
     * @since 1.5.7
     */
    private function getTicketStatues($requestedStatus)
    {
        return Arr::get([
            'open'   => ['new', 'active', 'on-hold'],
            'all'    => [],
            'closed' => ['closed']
        ], $requestedStatus);
    }

    /**
     * This `ticketsAdditionalData` method is responsible for getting tickets with additional data
     * @param object $customer
     * @param array $statuses
     * @return object $tickets
     * @since 1.5.7
     */
    private function ticketsAdditionalData($customer, $statuses)
    {
        $ticketsQuery = Ticket::with([
            'customer' => function ($query) {
                $query->select(['first_name', 'last_name', 'id']);
            }, 'agent' => function ($query) {
                $query->select(['first_name', 'last_name', 'id']);
            }
        ])
            ->where('customer_id', $customer->id)
            ->latest('updated_at');

        $ticketsQuery->where('customer_id', $customer->id);

        if ($statuses) {
            $ticketsQuery->whereIn('status', $statuses);
        }

        $tickets = $ticketsQuery->paginate();

        foreach ($tickets as $ticket) {
            $ticket->human_date = sprintf(__('%s ago', 'fluent-support'), human_time_diff(strtotime($ticket->created_at), current_time('timestamp')));
            $ticket->preview_response = $ticket->getLastResponse();
        }

        return $tickets;
    }

    /**
     * `resolveCustomer` method will create and return or only return existing customer
     * This method will get customer id or customer info or option to force create as parameter.
     * @param array $onBehalf
     * @param string $userIp // IP address of user
     * @param bool $forceCreate Default: false // If true, it will create a new customer
     * @return Customer // Collection
     */
    public function resolveCustomer($onBehalf, $userIp, $forceCreate = false)
    {
        if (!$onBehalf) {
            $user = get_user_by('ID', get_current_user_id());
            if (!$user) {
                return false;
            }
            $onBehalf = [
                'user_id'         => $user->ID,
                'email'           => $user->user_email,
                'last_ip_address' => $userIp
            ];
        }

        if ($forceCreate) {
            return Customer::maybeCreateCustomer($onBehalf);
        }

        return Customer::getCustomerFromData($onBehalf);
    }

    /**
     * resolveMailboxId method will either get information of the mailbox added by user or default and return the id
     * @param int $mailboxId
     * @return null
     */
    private function resolveMailboxId($mailboxId)
    {
        $mailbox = MailBox::find($mailboxId);
        if ($mailbox) {
            return $mailbox->id;
        }

        $mailbox = Helper::getDefaultMailBox();

        if ($mailbox) {
            return $mailbox->id;
        }
        return null;
    }

    // Supportive methods for getTicket

    /**
     * This `getTicketByID` method is responsible for getting a ticket by id
     * @param $ticketId
     * @return object $ticket
     */
    private function getTicketByID($ticketId)
    {
        $ticket = Ticket::where('id', $ticketId)
            ->with([
                'customer'    => function ($query) {
                    $query->select(['first_name', 'email', 'person_type', 'last_name', 'id', 'avatar']);
                }, 'agent'    => function ($query) {
                    $query->select(['first_name', 'email', 'person_type', 'last_name', 'id', 'title', 'avatar']);
                },
                'product',
                'attachments' => function ($q) {
                    $q->whereIn('status', ['active', 'inline']);
                }
            ])
            ->first();

        return $ticket;
    }

    /**
     * This `checkCustomerTicketAccess` method is responsible for checking customer ticket access
     * @param object $customer
     * @param object $ticket
     * @return bool true if access is granted
     * @throws Exception
     */
    private function checkCustomerTicketAccess($customer, $ticket, $action = false)
    {
        if (!$customer) {
            throw new \Exception('Sorry, You do not have permission to this support ticket');
        }

        if ($customer->status == 'inactive') {
            throw new \Exception('Sorry, You do not have access to customer portal');
        }

        if ($ticket->privacy == 'private' && $customer->id != $ticket->customer_id) {
            if ($action) {
                throw new \Exception("Sorry! You can not {$action} to this ticket");
            } else {
                throw new \Exception('You do not have permission to view this support ticket');
            }
        }

        $result = apply_filters('fluent_support/can_customer_access_ticket', true, $customer, $ticket, $action);

        if ($result && !is_wp_error($result)) {
            return $result;
        }

        if (!$result) {
            throw new \Exception('Sorry, You can not access to this ticket');
        }

        throw new \Exception($result->get_error_message());
    }


    /**
     * This `getResponses` method is responsible for getting a ticket's responses by ticket id
     * @param int $ticketId
     * @return mixed
     */
    private function getResponses($ticketId)
    {
        $responses = Conversation::where('ticket_id', $ticketId)
            ->with([
                'person' => function ($query) {
                    $query->select(['first_name', 'email', 'person_type', 'last_name', 'id', 'title', 'avatar']);
                },
                'attachments'
            ])
            ->filterByType(['response', 'ticket_merge_activity', 'ticket_split_activity'])
            ->latest('id')
            ->get();

            foreach ($responses as $response) {
                if (defined('FLUENTSUPPORTPRO_PLUGIN_VERSION') && Helper::isAgentFeedbackEnabled()) {
                    $agentFeedback = Meta::where('object_id', $response->id)
                        ->where('object_type', 'conversation_meta')
                        ->where('key', 'agent_feedback_ratings')
                        ->first();

                    if ($agentFeedback) {
                        $response->agent_feedback = $agentFeedback->value;
                    }
                }

                $response->human_date = sprintf(__('%s ago', 'fluent-support'), human_time_diff(strtotime($response->created_at), current_time('timestamp')));
                $response->content = links_add_target(make_clickable($response->content));
                if ($response->person) {
                    $response->person->setHidden(['email']);
                }
            }

        return $responses;
    }

    /**
     * This `syncTicketAdditionData` method is responsible for syncing ticket additional data
     * @param object $ticket
     * @return object $ticket
     */
    private function syncTicketAdditionData($ticket)
    {
        $ticket->content = links_add_target(make_clickable($ticket->content));

        if ($ticket->customer) {
            $ticket->customer->setHidden(['email']);
        }

        if ($ticket->agent) {
            $ticket->agent->setHidden(['email']);
        }

        if ($ticket->status == 'closed') {
            $ticket->load('closed_by_person');
            if ($ticket->closed_by_person) {
                $ticket->closed_by_person->setVisible(['first_name', 'last_name', 'id', 'full_name', 'photo']);
            }
        }

        if (defined('FLUENTSUPPORTPRO')) {
            $ticket->custom_fields = $ticket->customData('public', true);
        }

        return $ticket;
    }

    public function addUserFeedback($approvalStatus, $conversationID)
    {
        $existingAgentFeedback = Meta::where([
            'object_id' => $conversationID,
            'key' => 'agent_feedback_ratings',
        ])->first();

        if ($existingAgentFeedback) {
            return $this->updateExistingFeedback($existingAgentFeedback, $approvalStatus);
        } else {
            $agentFeedback = Meta::create([
                'object_id' => $conversationID,
                'key' => 'agent_feedback_ratings',
                'object_type' => 'conversation_meta',
                'value' => $approvalStatus,
            ]);
            return $agentFeedback;
        }
    }

    private function updateExistingFeedback($existingAgentFeedback, $approvalStatus)
    {
        if (($existingAgentFeedback->value === 'like' && $approvalStatus === 'like') ||
            ($existingAgentFeedback->value === 'dislike' && $approvalStatus === 'dislike')) {
             $existingAgentFeedback->delete();
        } else {
              $existingAgentFeedback->update([
                'value' => $approvalStatus,
            ]);
        }
        return $existingAgentFeedback;
    }

}
