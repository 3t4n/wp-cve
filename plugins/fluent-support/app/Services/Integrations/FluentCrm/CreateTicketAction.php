<?php

namespace FluentSupport\App\Services\Integrations\FluentCrm;

use FluentCrm\App\Services\Funnel\BaseAction;
use FluentCrm\App\Services\Funnel\FunnelHelper;
use FluentSupport\App\Models\Agent;
use FluentSupport\App\Models\Customer;
use FluentSupport\App\Models\MailBox;
use FluentSupport\App\Models\Ticket;
use FluentSupport\App\Services\Helper;
use FluentSupport\Framework\Support\Arr;

class CreateTicketAction extends BaseAction
{
    public function __construct()
    {
        $this->actionName = 'fs_create_ticket';
        $this->priority = 23;
        parent::__construct();

        add_filter('fluentcrm_ajax_options_fs_inboxes', function ($options) {
            $inboxes = MailBox::select(['id', 'name'])->get();
            $formattedInboxes = [];
            foreach ($inboxes as $inbox) {
                $formattedInboxes[] = [
                    'id'    => $inbox->id,
                    'title' => $inbox->name
                ];
            }
            return $formattedInboxes;
        });
        add_filter('fluentcrm_ajax_options_fs_agents', function ($options) {
            $agents = Agent::select(['id', 'first_name', 'last_name'])->where('status', 'active')->get();
            $formattedAgents = [];
            foreach ($agents as $agent) {
                $formattedAgents[] = [
                    'id'    => $agent->id,
                    'title' => $agent->full_name
                ];
            }
            return $formattedAgents;
        });
    }

    public function getBlock()
    {
        return [
            'category'       => __('Fluent Support', 'fluent-support'),
            'title'          => __('Create Support Ticket', 'fluent-support'),
            'ticket_content' => __('Add a support ticket for contact', 'fluent-support'),
            'icon'           => 'fc-icon-apply_tag',//fluentCrmMix('images/funnel_icons/apply_tag.svg'),
            'settings'       => [
                'ticket_title'       => '',
                'ticket_description' => '',
                'default_agent'      => '',
                'business_inbox'     => ''
            ],
            'priority' => 'normal'
        ];
    }

    public function getBlockFields()
    {
        $formattedPriorities = [];
        foreach (Helper::adminTicketPriorities() as $key => $label) {
            $formattedPriorities[] = [
                'id' => $key,
                'title' => $label
            ];
        }

        return [
            'title'     => __('Create Support Ticket', 'fluent-support'),
            'sub_title' => __('Add a support ticket for contact', 'fluent-support'),
            'fields'    => [
                'ticket_title'   => [
                    'type'        => 'input-text-popper',
                    'label'       => __('Ticket Title (you can use any smart code)', 'fluent-support'),
                    'placeholder' => __('Ticket Title', 'fluent-support')
                ],
                'ticket_content' => [
                    'type'          => 'html_editor',
                    'label'         => __('Ticket Description (you can use any smart code)', 'fluent-support'),
                    'placeholder'   => __('Ticket Description', 'fluent-support'),
                    'smart_codes'   => true,
                    'context_codes' => true
                ],
                'business_inbox' => [
                    'type'        => 'rest_selector',
                    'option_key'  => 'fs_inboxes',
                    'placeholder' => 'Business Inbox',
                    'label'       => 'Default Business Inbox'
                ],
                'default_agent'  => [
                    'type'        => 'rest_selector',
                    'option_key'  => 'fs_agents',
                    'placeholder' => 'Select Agent (optional)',
                    'label'       => 'Default Assigned Agent (optional)'
                ],
                'priority' => [
                    'options' => $formattedPriorities,
                    'label' => 'Priority (Admin)',
                    'placeholder' => 'Select Priority',
                    'type' => 'select'
                ]
            ]
        ];
    }

    public function handle($subscriber, $sequence, $funnelSubscriberId, $funnelMetric)
    {
        if (empty($sequence->settings['ticket_title']) || empty($sequence->settings['ticket_content'])) {
            FunnelHelper::changeFunnelSubSequenceStatus($funnelSubscriberId, $sequence->id, 'skipped');
            return false;
        }

        $subscriber->funnel_subscriber_id = $funnelSubscriberId;

        $emailSubject = apply_filters('fluent_crm/parse_campaign_email_text', Arr::get($sequence->settings, 'ticket_title'), $subscriber);
        $emailBody = apply_filters('fluent_crm/parse_campaign_email_text', Arr::get($sequence->settings, 'ticket_content'), $subscriber);

        $ticketData = [
            'title'   => substr(sanitize_text_field($emailSubject), 0, 192),
            'content' => wp_unslash(wp_kses_post($emailBody)),
            'source'  => 'fluent-crm'
        ];

        if (!empty($sequence->settings['business_inbox'])) {
            $ticketData['mailbox_id'] = (int)$sequence->settings['business_inbox'];
        }

        if (empty($ticketData['mailbox_id'])) {
            $defaultMailBox = Helper::getDefaultMailBox();
            if ($defaultMailBox) {
                $ticketData['mailbox_id'] = $defaultMailBox->id;
            }
        }

        if (!empty($sequence->settings['default_agent'])) {
            $ticketData['agent_id'] = (int) $sequence->settings['default_agent'];
        }

        if (!empty($sequence->settings['priority'])) {
            $ticketData['priority'] = sanitize_text_field($sequence->settings['priority']);
        }

        $customerData = Arr::only($subscriber->toArray(), (new Customer())->getFillable());

        $customerData['user_id'] = $subscriber->getWpUserId();
        $customerData = array_filter($customerData);

        $customer = Customer::maybeCreateCustomer($customerData);

        $ticketData['customer_id'] = $customer->id;
        $ticketData = apply_filters('fluent_support/create_ticket_data', $ticketData, $customer);
        do_action('fluent_support/before_ticket_create', $ticketData, $customer);
        $ticket = Ticket::create($ticketData);
        do_action('fluent_support/ticket_created', $ticket, $customer);

        return true;
    }
}
