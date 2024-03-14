<?php

namespace FluentSupport\App\Hooks\Handlers;

use FluentSupport\App\Models\Conversation;
use FluentSupport\App\Models\Customer;
use FluentSupport\App\Models\Ticket;

class PrivacyHandler
{
    public function init()
    {
        add_filter('wp_privacy_personal_data_exporters', [$this, 'registerExporter']);
        add_filter('wp_privacy_personal_data_erasers', [$this, 'registerEraser']);
    }

    // Registering exporters to export data
    public function registerExporter($exporters)
    {
        $exporters['fluent-support-tickets'] = array(
            'exporter_friendly_name' => __('Fluent Support Tickets Exporter', 'fluent-support'),
            'callback'               => [$this, 'exportTickets'],
        );

        $exporters['fluent-support-conversations'] = array(
            'exporter_friendly_name' => __('Fluent Support Conversations Exporter', 'fluent-support'),
            'callback'               => [$this, 'exportConversations'],
        );

        $exporters['fluent-support-customer'] = array(
            'exporter_friendly_name' => __('Fluent Support Customer Exporter', 'fluent-support'),
            'callback'               => [$this, 'exportCustomerData'],
        );
        return $exporters;
    }

    // Registering erasers to erase data
    public function registerEraser($erasers)
    {
        $erasers['fluent-support'] = array(
            'eraser_friendly_name' => __('Fluent Support Personal Data Eraser', 'fluent-support'),
            'callback'             => [$this, 'erasePersonalData'],
        );
        return $erasers;
    }

    // Method to handle tickets data exporter
    public function exportTickets($user_email, $page = 1)
    {
        $customer = Customer::where('email', $user_email)->select(['id'])->first();

        if (!$customer) {
            return [
                'data' => [],
                'done' => true
            ];
        }

        $tickets = Ticket::with(['customer', 'agent', 'product', 'mailbox', 'tags', 'attachments'])
            ->where('customer_id', $customer->id)
            ->get();
        $data = [];

        foreach ($tickets as $ticket) {
            $data[] = [
                'group_id'    => 'fluent-support-tickets',
                'group_label' => __('Fluent Support Tickets', 'fluent-support'),
                'item_id'     => 'ticket-' . $ticket->id,
                'data'        => [
                    [
                        'name'  => __('Ticket ID', 'fluent-support'),
                        'value' => $ticket->id,
                    ],
                    [
                        'name'  => __('Ticket Title', 'fluent-support'),
                        'value' => $ticket->title,
                    ],
                    [
                        'name'  => __('Ticket Content', 'fluent-support'),
                        'value' => $ticket->content,
                    ],
                    [
                        'name'  => __('Ticket Attachments', 'fluent-support'),
                        'value' => $this->ticketAttachments($ticket->attachments),
                    ],
                    [
                        'name'  => __('Ticket Product', 'fluent-support'),
                        'value' => isset($ticket->product) ? $ticket->product->title : '',
                    ],
                    [
                        'name'  => __('Ticket Tags', 'fluent-support'),
                        'value' => $this->ticketTags($ticket->tags),
                    ],
                    [
                        'name'  => __('Ticket Status', 'fluent-support'),
                        'value' => $ticket->status,
                    ],
                    [
                        'name'  => __('Ticket Priority', 'fluent-support'),
                        'value' => $ticket->priority,
                    ],
                    [
                        'name'  => __('Ticket Created At', 'fluent-support'),
                        'value' => $ticket->created_at,
                    ]
                ],
            ];
        }

        /**
         * Filter to modify tickets exportable data
         * @param array $data
         * @param object $tickets
         * @return array Exportable data
         * @since v1.5.5
         */
        $exportableData = apply_filters('fluent_support/exportable_tickets_data', $data, $tickets);
        return [
            'data' => $exportableData,
            'done' => true,
        ];
    }

    // Method to handle conversations data exporter
    public function exportConversations($user_email, $page = 1)
    {
        $customer = Customer::where('email', $user_email)->select(['id'])->first();

        if (!$customer) {
            return [
                'data' => [],
                'done' => true
            ];
        }

        $conversations = Conversation::with(['attachments'])
            ->where('person_id', $customer->id)
            ->where('conversation_type', 'response')
            ->get();

        $data = [];
        foreach ($conversations as $conversation) {
            $data[] = [
                'group_id'    => 'fluent-support-conversations',
                'group_label' => __('Fluent Support Conversations', 'fluent-support'),
                'item_id'     => 'conversation-' . $conversation->id,
                'data'        => [
                    [
                        'name'  => __('Conversation ID', 'fluent-support'),
                        'value' => $conversation->id,
                    ],
                    [
                        'name'  => __('Ticket ID', 'fluent-support'),
                        'value' => $conversation->ticket_id,
                    ],
                    [
                        'name'  => __('Conversation Content', 'fluent-support'),
                        'value' => $conversation->content,
                    ],
                    [
                        'name'  => __('Conversation Attachments', 'fluent-support'),
                        'value' => $this->convAttachments($conversation->attachments),
                    ],
                    [
                        'name'  => __('Conversation Created At', 'fluent-support'),
                        'value' => $conversation->created_at,
                    ]
                ],
            ];
        }

        /**
         * Filter to modify conversations exportable data
         * @param array $data
         * @param object $conversations
         * @return array Exportable data
         * @since v1.5.5
         */
        $exportableData = apply_filters('fluent_support/exportable_conversations_data', $data, $conversations);
        return [
            'data' => $exportableData,
            'done' => true,
        ];
    }

    // Method to handle customers data exporter
    public function exportCustomerData($user_email, $page = 1)
    {
        $customer = Customer::where('email', $user_email)->first();

        if (!$customer) {
            return [
                'data' => [],
                'done' => true
            ];
        }

        $data[] = [
            'group_id'    => 'fluent-support-customer',
            'group_label' => __('Fluent Support Customer', 'fluent-support'),
            'item_id'     => 'customer-' . $customer->id,
            'data'        => [
                [
                    'name'  => __('Customer ID', 'fluent-support'),
                    'value' => $customer->id,
                ],
                [
                    'name'  => __('First Name', 'fluent-support'),
                    'value' => $customer->first_name,
                ],
                [
                    'name'  => __('Last Name', 'fluent-support'),
                    'value' => $customer->last_name,
                ],
                [
                    'name'  => __('Email', 'fluent-support'),
                    'value' => $customer->email,
                ],
                [
                    'name'  => __('Status', 'fluent-support'),
                    'value' => $customer->status,
                ],
                [
                    'name'  => __('IP Address', 'fluent-support'),
                    'value' => $customer->ip_address,
                ],
                [
                    'name'  => __('Last IP Address', 'fluent-support'),
                    'value' => $customer->last_ip_address,
                ],
                [
                    'name'  => __('Address Line One', 'fluent-support'),
                    'value' => $customer->address_line_1
                ],
                [
                    'name'  => __('Address Line Two', 'fluent-support'),
                    'value' => $customer->address_line_2
                ],
                [
                    'name'  => __('City', 'fluent-support'),
                    'value' => $customer->city
                ],
                [
                    'name'  => __('Zip', 'fluent-support'),
                    'value' => $customer->zip
                ],
                [
                    'name'  => __('State', 'fluent-support'),
                    'value' => $customer->state
                ],
                [
                    'name'  => __('Country', 'fluent-support'),
                    'value' => $customer->country
                ],
                [
                    'name'  => __('Created At', 'fluent-support'),
                    'value' => $customer->created_at
                ],
            ],
        ];

        /**
         * Filter to modify customer exportable data
         * @param array $data
         * @param object $customer
         * @return array Exportable data
         * @since v1.5.5
         */
        $exportableData = apply_filters('fluent_support/exportable_customer_data', $data, $customer);
        return [
            'data' => $exportableData,
            'done' => true,
        ];
    }

    public function ticketAttachments($attachments)
    {
        $text = '';
        foreach ($attachments as $attachment) {
            $text .= '<a href=' . esc_url($attachment->secureUrl) . '>' . esc_html($attachment->title) . '</a>, <br>';
        }

        return $text;
    }

    public function convAttachments($attachments)
    {
        $text = '';
        foreach ($attachments as $attachment) {
            $text .= '<a href=' . esc_url($attachment->secureUrl) . '>' . esc_html($attachment->title) . '</a>, <br>';
        }

        return $text;
    }

    public function ticketTags($tags)
    {
        $ticketTags = '';
        foreach ($tags as $tag) {
            $ticketTags .= $tag->title . ', ';
        }

        return $ticketTags;
    }

    // Method to handle data eraser
    public function erasePersonalData($user_email, $page = 1)
    {
        $customer = Customer::where('email', $user_email)->select(['id'])->first();

        if (!$customer) {
            return [
                'items_removed'  => true,
                'items_retained' => false,
                'messages'       => [],
                'done'           => true
            ];
        }

        $tickets = Ticket::where('customer_id', $customer->id)->get();
        foreach ($tickets as $ticket) {
            $ticket->deleteTicket();
        }

        $customer->delete();

        return [
            'items_removed'  => true,
            'items_retained' => false,
            'messages'       => [],
            'done'           => true,
        ];
    }
}
