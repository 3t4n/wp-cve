<?php

namespace FluentSupport\App\Hooks\Handlers;

use FluentSupport\App\Models\Activity;

/**
 *  ActivityLogger class for Hooks
 *
 * @package FluentSupport\App\Hooks\Handlers
 *
 * @version 1.0.0
 */

class ActivityLogger
{

    /**
     * init method will register all action hooks related to ticket creation, ticket response,  response by agent, note by agent, ticket closed or reopen
     */
    public function init()
    {
        // Ticket Related activities
        add_action('fluent_support/ticket_created', function ($ticket, $customer) {

            $customer->last_response_at = current_time('mysql');
            $customer->save();

            $description = sprintf('%1$s created a %2$s via %3$s', $this->getPersonMarkup($customer), $this->getTicketMarkup($ticket), $ticket->source);

            $log = [
                'event_type' => 'fluent_support/ticket_created',
                'person_id' => $customer->id,
                'person_type' => $customer->person_type,
                'object_id' => $ticket->id,
                'object_type' => 'ticket',
                'description' => $description
            ];

            Activity::create($log);
        }, 20, 2);

        //Response added by customer in a ticket
        add_action('fluent_support/response_added_by_customer', function ($response, $ticket, $person) {

            $person->last_response_at = current_time('mysql');
            $person->save();

            $description = sprintf('Customer %1$s added a response on %2$s via %3$s', $this->getPersonMarkup($person), $this->getTicketMarkup($ticket), $response->source);

            $log = [
                'event_type' => 'fluent_support/response_added_by_customer',
                'person_id' => $person->id,
                'person_type' => $person->person_type,
                'object_id' => $ticket->id,
                'object_type' => 'ticket',
                'description' => $description
            ];

            Activity::create($log);
        }, 20, 3);

        //Response added by agent in a ticket
        add_action('fluent_support/response_added_by_agent', function ($response, $ticket, $person) {
            $description = sprintf('%1$s added a response on %2$s via %3$s', $this->getPersonMarkup($person), $this->getTicketMarkup($ticket), $response->source);

            $log = [
                'event_type' => 'fluent_support/response_added_by_agent',
                'person_id' => $person->id,
                'person_type' => $person->person_type,
                'object_id' => $ticket->id,
                'object_type' => 'ticket',
                'description' => $description
            ];

            Activity::create($log);
        }, 20, 3);

        //Note added by agent to a ticket
        add_action('fluent_support/note_added_by_agent', function ($response, $ticket, $person) {
            $description = sprintf('%1$s added a note on %2$s via %3$s', $this->getPersonMarkup($person), $this->getTicketMarkup($ticket), $response->source);

            $log = [
                'event_type' => 'fluent_support/note_added_by_agent',
                'person_id' => $person->id,
                'person_type' => $person->person_type,
                'object_id' => $ticket->id,
                'object_type' => 'ticket',
                'description' => $description
            ];

            Activity::create($log);
        }, 20, 3);

        //Ticket closed by customer or agent
        add_action('fluent_support/ticket_closed', function ($ticket, $person) {

            if ($person->person_type == 'customer') {
                $person->last_response_at = current_time('mysql');
                $person->save();
            }

            $description = sprintf('%1$s closed %2$s', $this->getPersonMarkup($person), $this->getTicketMarkup($ticket));

            $log = [
                'event_type' => 'fluent_support/ticket_closed',
                'person_id' => $person->id,
                'person_type' => $person->person_type,
                'object_id' => $ticket->id,
                'object_type' => 'ticket',
                'description' => $description
            ];

            Activity::create($log);
        }, 20, 2);

        //Ticket reopen by customer or agent.
        add_action('fluent_support/ticket_reopen', function ($ticket, $person) {

            if ($person->person_type == 'customer') {
                $person->last_response_at = current_time('mysql');
                $person->save();
            }

            $description = sprintf('%1$s reopened on %2$s', $this->getPersonMarkup($person), $this->getTicketMarkup($ticket));

            $log = [
                'event_type' => 'fluent_support/ticket_reopen',
                'person_id' => $person->id,
                'person_type' => $person->person_type,
                'object_id' => $ticket->id,
                'object_type' => 'ticket',
                'description' => $description
            ];

            Activity::create($log);
        }, 20, 2);

        // Ticket Assigned by agent
        add_action('fluent_support/agent_assigned_to_ticket', function ($assignedAgent, $ticket, $assigner) {

            $description = sprintf('Assign  %1$s ticket to %2$s by %3$s', $this->getTicketMarkup($ticket), $this->getPersonMarkup($assignedAgent), $this->getPersonMarkup($assigner));

            if($assigner && isset($assigner->id) && isset($assigner->person_type)){
                $log = [
                    'event_type' => 'fluent_support/agent_assigned_to_ticket',
                    'person_id' => $assigner->id,
                    'person_type' => $assigner->person_type,
                    'object_id' => $ticket->id,
                    'object_type' => 'ticket',
                    'description' => $description
                ];
                Activity::create($log);
            }
        }, 20, 3);

        add_action('fluent_support/ticket_deleted', function($agent, $ticketData) {
            $description = sprintf('%s deleted %s(#%d) at %s', $this->getPersonMarkup($agent), $ticketData['title'], $ticketData['id'], current_time('mysql'));
            $log = [
                'event_type' => 'fluent_support/ticket_deleted',
                'person_id' => $agent->id,
                'person_type' => 'agent',
                'object_id' => $ticketData['id'],
                'object_type' => 'ticket',
                'description' => $description
                ];
                Activity::create($log);
        }, 20, 2);
    }

    /**
     *  getTicketMarkup method will generate hyperlink to view the ticket details
     * @param $ticket
     * @param false $ticketText
     * @return string
     */
    public function getTicketMarkup($ticket, $ticketText = false)
    {
        if (!$ticketText) {
            $ticketText = sprintf(__('Ticket: %s', 'fluent-support'), $ticket->title);
        }

        return '<a class="fs_link_trans fs_tk" href="#view_ticket">' . $ticketText . '</a>';
    }

    /**
     * getPersonMarkup method will generate hyperlink to view a customer or agent
     * @param $person
     * @return string
     */
    public function getPersonMarkup($person)
    {
        $route = 'view_agent';
        if (isset($person->person_type) && $person->person_type == 'customer') {
            $route = 'view_customer';
        }
        return '<a class="fs_link_trans fs_pr" href="#' . $route . '">' . $person->full_name . '</a>';
    }
}
