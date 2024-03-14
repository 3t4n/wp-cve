<?php

namespace FluentSupport\App\Api\Classes;

use FluentSupport\App\Models\Agent;
use FluentSupport\App\Models\Customer;
use FluentSupport\App\Models\MailBox;
use FluentSupport\App\Models\Ticket;
use FluentSupport\App\Services\Helper;
use FluentSupport\App\Services\Tickets\ResponseService;

/**
 *  Tickets class for PHP API
 * Example Usage: $ticketsApi = FluentSupportApi('tickets');
 * @package FluentSupport\App\Api\Classes
 *
 * @version 1.0.0
 */
class Tickets
{
    private $instance = null;

    private $allowedInstanceMethods = [
        'all',
        'get',
        'find',
        'first',
        'paginate'
    ];

    public function __construct(Ticket $instance)
    {
        $this->instance = $instance;
    }

    /**
     * getTickets method will return all tickets
     *
     * @return object
     */
    public function getTickets()
    {
        $ticketsQuery = Ticket::with([
            'customer' => function ($query) {
                $query->select(['first_name', 'last_name', 'id', 'email']);
            }, 'agent' => function ($query) {
                $query->select(['first_name', 'last_name', 'id', 'email']);
            }
        ])
            ->orderBy('id', 'DESC');
        $tickets = $ticketsQuery->paginate();

        if (defined('FLUENTSUPPORTPRO')) {
            foreach ($tickets as $ticket) {
                $ticket->custom_fields = $ticket->customData();
            }
        }

        return $tickets;
    }

    /**
     * getTicket method will return a specific ticket by id
     * @param int $id
     * @return object|false
     */

    public function getTicket(int $id)
    {
        if (is_numeric($id)) {
            $ticket = Ticket::findOrFail($id);
            if (defined('FLUENTSUPPORTPRO')) {
                $ticket->custom_fields = $ticket->customData();
            }
            return $ticket;
        }
        return false;
    }

    /**
     * addResponse method add response to a ticket by agent and ticket ID
     * @param array $data
     * @param int $agentId
     * @param int $ticketId
     * @return array|boolean
     */

    public function addResponse(array $data, int $agentId, int $ticketId)
    {
        if (!$agentId || !$ticketId || !$data['content']) {
            return false;
        }

        $agent = Agent::findOrFail($agentId);
        $ticket = Ticket::findOrFail($ticketId);

        if ($agent && $ticket) {
            return (new ResponseService())->createResponse($data, $agent, $ticket);
        }
        return false;
    }

    /**
     *  createTicket method will create a new ticket
     * @param array $data
     * @return object| boolean
     */

    public function createTicket(array $data)
    {
        if (!$data['customer_id'] || !Customer::find($data['customer_id'])) {
            return false;
        }

        if (!$data['title'] || !$data['content']) {
            return false;
        }

        if(!$data['mailbox_id']){
            $defaultMailbox = Helper::getDefaultMailBox();
            if(!$defaultMailbox){
                return false;
            }
            $data['mailbox_id'] = $defaultMailbox->id;
        }else{
            $mailbox = MailBox::find($data['mailbox_id']);
            if (!$mailbox) {
                return false;
            }
        }

        $createdTicket = Ticket::create($data);

        if (defined('FLUENTSUPPORTPRO') && !empty($data['custom_fields'])) {
            $createdTicket->syncCustomFields($data['custom_fields']);
            $createdTicket->custom_fields = $createdTicket->customData();
        }

        return $createdTicket;

    }

    public function getInstance()
    {
        return $this->instance;
    }

    public function __call($method, $params)
    {
        if (in_array($method, $this->allowedInstanceMethods)) {
            return call_user_func_array([$this->instance, $method], $params);
        }

        throw new \Exception("Method {$method} does not exist.");
    }
}
