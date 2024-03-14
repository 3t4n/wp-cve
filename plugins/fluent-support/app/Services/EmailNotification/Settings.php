<?php

namespace FluentSupport\App\Services\EmailNotification;

use FluentSupport\App\Services\Helper;

class Settings
{

    public function getEmailSettingsKeys()
    {
        $key = apply_filters('fluent_support/email_setting_keys', [
            'ticket_created_email_to_customer',
            'ticket_replied_by_agent_email_to_customer',
            'ticket_closed_by_agent_email_to_customer',
            'ticket_created_email_to_admin',
            'ticket_replied_by_customer_email_to_admin',
            'ticket_agent_on_change',
            'ticket_created_by_agent_email_to_customer'
        ]);
        return $key;
    }

    public function get($settingsKey)
    {
        if ($settingsKey == 'global_business_settings') {
            return [
                'settings' => $this->globalBusinessSettings(),
                'fields'   => $this->getGlobalBusinessSettingsFields()
            ];
        }

        return [
            'settings' => [],
            'fields'   => []
        ];
    }

    /**
     * save method will save the requested settings by settings key
     * @param $settingsKey
     * @param $settings
     * @return mixed
     */
    public function save($settingsKey, $settings)
    {
        if ($settingsKey == 'global_business_settings' && empty($settings['accepted_file_types'])) {
            $settings['accepted_file_types'] = [];
        }

        return Helper::updateOption($settingsKey, $settings);
    }

    /**
     * globalBusinessSettings method will fetch global settings from database, parse and return
     * @param bool $cached
     * @return array|mixed
     */
    public function globalBusinessSettings($cached = true)
    {
        static $settings;

        if($cached && $settings) {
            return $settings;
        }

        $defaults = [
            'portal_page_id'        => '',
            'login_message'         => sprintf(__('%1sPlease login or create an account to access the Customer Support Portal%2s [fluent_support_auth]', 'fluent-support'), '<p>', '</p>'),
            'disable_public_ticket' => 'no',
            'accepted_file_types'   => ['images', 'csv', 'documents', 'zip', 'json'],
            'max_file_size'         => 2,
            'max_file_upload'       => 3,
            'del_files_on_close'    => 'no',
            'enable_admin_bar_summary' => 'no',
            'enable_draft_mode' => 'no',
            'agent_feedback_rating' => 'no'
        ];

        //Get default/existing settings from database using the key global_business_settings
        $existingSettings = Helper::getOption('global_business_settings', []);

        if (!$existingSettings) {
            $settings = $defaults;
            return $settings;
        }

        $settings = wp_parse_args($existingSettings, $defaults);

        return $settings;
    }


    /**
     * getGlobalBusinessSettingsFields method will prepare the list of field, and it's property that will be used in global settings form
     * @return array
     */
    private function getGlobalBusinessSettingsFields()
    {

        $mimeGroups = Helper::getMimeGroups();

        $formattedMimeGroups = [];

        foreach ($mimeGroups as $mimeGroup => $mime) {
            $formattedMimeGroups[$mimeGroup] = $mime['title'];
        }

        $customRegistrationFormOptions = array(
            'address_line_1' => 'Address Line 1',
            'address_line_2' => 'Address Line 2',
            'city' => 'City',
            'zip' => 'Zip Code',
            'state' => 'State',
            'country' => 'Country',
        );

        $fields = [
            'portal_page_id'        => [
                'type'        => 'input-options',
                'label'       => __('Portal Page', 'fluent-support'),
                'show_id'     => true,
                'placeholder' => __('Select Portal Page', 'fluent-support'),
                'options'     => Helper::getWPPages(),//Get list of published pages
                'inline_help' => __('Please provide the page id where you want to show the tickets for your customers. Use shortcode <code>[fluent_support_portal]</code> in that page', 'fluent-support')
            ],
            'login_message'         => [
                'type'        => 'wp-editor',
                'label'       => __('Message for non logged in users', 'fluent-support'),
                'inline_help' => __('Please provide message for not logged in users. You can place login shortcode too Use shortcode <code>[fluent_support_login]</code> to show built-in login form. For the user registration use this shortcode <code>[fluent_support_signup]</code> and for both form please use <code>[fluent_support_auth]</code>', 'fluent-support')
            ],
            'disable_public_ticket' => [
                'type'           => 'inline-checkbox',
                'true_label'     => 'yes',
                'false-label'    => 'no',
                'checkbox_label' => __('Disable Public Ticket interaction', 'fluent-support'),
                'inline_help'    => __('If you enable this then only logged in user can reply the tickets. Otherwise, url will be signed and intended user can reply without logging in', 'fluent-support')
            ],
            'accepted_file_types'   => [
                'wrapper_class' => 'fs_half_field',
                'type'    => 'checkbox-group',
                'label'   => __('Accepted File Types', 'fluent-support'),
                'options' => $formattedMimeGroups
            ],
            'max_file_size' => [
                'wrapper_class' => 'fs_half_field',
                'type'    => 'input-text',
                'data_type' => 'number',
                'label'   => __('Max File Size (in MegaByte)', 'fluent-support'),
            ],
            'max_file_upload' => [
                'wrapper_class' => 'fs_half_field',
                'type'    => 'input-text',
                'data_type' => 'number',
                'label'   => __('Maximum File Upload', 'fluent-support'),
            ],
            'del_files_on_close' => [
                'type'           => 'inline-checkbox',
                'true_label'     => 'yes',
                'false-label'    => 'no',
                'checkbox_label' => __('Delete all attachments on ticket close', 'fluent-support'),
                'inline_help'    => __('If you enable this then when a ticket get closed it will delete all the attachments associated with the particular ticket.', 'fluent-support')
            ],
            'enable_admin_bar_summary' => [
                'type'           => 'inline-checkbox',
                'true_label'     => 'yes',
                'false-label'    => 'no',
                'checkbox_label' => __('Enable Fluent Summary In Admin Bar', 'fluent-support'),
                'inline_help'    => __('If you enable this, logged in user can see the ticket summary from top nav bar.', 'fluent-support')
            ],
            'enable_draft_mode' => [
                'type'           => 'inline-checkbox',
                'true_label'     => 'yes',
                'false-label'    => 'no',
                'checkbox_label' => __('Enable Draft Mode', 'fluent-support'),
                'inline_help'    => __('If you enable this setting, then if an agent close a ticket accidentally then the written response will be saved as draft.', 'fluent-support')
            ],
            'custom_registration_form_field'   => [
                'wrapper_class' => 'inline-checkbox',
                'type'    => 'checkbox-group',
                'label'   => __('Custom Registration Form Field', 'fluent-support'),
                'options' => $customRegistrationFormOptions
            ],
        ];

        if (defined('FLUENTSUPPORTPRO_PLUGIN_VERSION')) {
            $fields['agent_feedback_rating'] = [
                'type'           => 'inline-checkbox',
                'true_label'     => 'yes',
                'false-label'    => 'no',
                'checkbox_label' => __('Agent Feedback Rating', 'fluent-support'),
                'inline_help'    => __("If you enable this setting, users will have the option to provide feedback on an agent's response.", 'fluent-support')
            ];
        }

        return $fields;
    }

    public function saveBoxEmailSettings($box, $emailKey, $settings)
    {
        return $box->saveMeta('_email_' . $emailKey, $settings);
    }

    /**
     * getBoxEmailSettings method will reply the email settings
     * @param $box
     * @param $emailKey
     * @return array|false
     */
    public function getBoxEmailSettings($box, $emailKey)
    {
        if (!$box) {
            return false;
        }

        #ticket_closed_by_agent_email_to_customer 2 times, is it wrong or right!!!
        $strictSubjectKeys = apply_filters('fluent_support/strict_subjects', [
            'ticket_replied_by_agent_email_to_customer',
            'ticket_closed_by_agent_email_to_customer',
            'ticket_created_email_to_customer'
        ]);

        $settingsDefaults = [
            'ticket_created_email_to_customer'          => [
                'key'            => 'ticket_created_email_to_customer',
                'title'          => __('Ticket Created (To Customer)', 'fluent-support'),
                'description'    => __('This email will be sent when a customer submit a support ticket', 'fluent-support'),
                'email_subject'  => 'Re: {{ticket.title}} #{{ticket.id}}',
                'default_status' => 'no',
                'send_attachments'=> 'no'
            ],
            'ticket_replied_by_agent_email_to_customer' => [
                'key'            => 'ticket_replied_by_agent_email_to_customer',
                'title'          => __('Replied by Agent (To Customer)', 'fluent-support'),
                'description'    => __('This email will be sent when an agent reply to a ticket', 'fluent-support'),
                'email_subject'  => 'Re: {{ticket.title}} #{{ticket.id}}',
                'default_status' => 'yes',
                'send_attachments'=> 'no'
            ],
            'ticket_closed_by_agent_email_to_customer'  => [
                'key'            => 'ticket_closed_by_agent_email_to_customer',
                'title'          => __('Ticket Closed by Agent (To Customer)', 'fluent-support'),
                'description'    => __('This email will be sent when an agent close a ticket', 'fluent-support'),
                'email_subject'  => 'Re: {{ticket.title}} #{{ticket.id}}',
                'default_status' => 'no',
                'send_attachments'=> 'no'
            ],
            'ticket_created_email_to_admin'             => [
                'key'            => 'ticket_created_email_to_admin',
                'title'          => __('Ticket Created (To Admin)', 'fluent-support'),
                'description'    => __('This email will be sent when the business when a new ticket has been submitted', 'fluent-support'),
                'email_subject'  => 'New Ticket: {{ticket.title}} #{{ticket.id}}',
                'default_status' => 'yes',
                'send_attachments'=> 'no'
            ],
            'ticket_replied_by_customer_email_to_admin' => [
                'key'            => 'ticket_replied_by_customer_email_to_admin',
                'title'          => __('Replied by Customer (To Agent/Admin)', 'fluent-support'),
                'description'    => __('This email will be sent to Assigned Agent or Admin when a customer reply to a ticket', 'fluent-support'),
                'email_subject'  => 'New Response: {{ticket.title}} #{{ticket.id}}',
                'default_status' => 'yes',
                'send_attachments'=> 'no'
            ],
            'ticket_agent_on_change' => [
                'key'            => 'ticket_agent_on_change',
                'title'          => __('Ticket Agent Change (To Agent)', 'fluent-support'),
                'description'    => __('This email will be sent to newly assigned agent', 'fluent-support'),
                'email_subject'  => 'Ticket Agent Change: {{ticket.title}} #{{ticket.id}}',
                'default_status' => 'yes',
                'send_attachments'=> 'no'
            ],
            'ticket_created_by_agent_email_to_customer' => [
                'key' => 'ticket_created_by_agent_email_to_customer',
                'title' => __('Ticket Created by Agent (To Customer)', 'fluent-support'),
                'description' => __('This email will be sent when an agent create a ticket for a customer', 'fluent-support'),
                'email_subject' => 'Re: {{ticket.title}} #{{ticket.id}}',
                'default_status' => 'no',
                'send_attachments'=> 'no'
            ]
        ];

        if (!isset($settingsDefaults[$emailKey])) {
            return false;
        }

        $savedSettings = (array)$box->getMeta('_email_' . $emailKey, []);


        if (!$savedSettings) {
            $savedSettings = [
                'key'              => $settingsDefaults[$emailKey]['key'],
                'title'            => $settingsDefaults[$emailKey]['title'],
                'email_subject'    => $settingsDefaults[$emailKey]['email_subject'],
                'email_body'       => $this->getDefaultEmailBody($emailKey, $box->box_type),
                'status'           => $settingsDefaults[$emailKey]['default_status'],
                'can_edit_subject' => (in_array($emailKey, $strictSubjectKeys) && $box->box_type == 'email') ? 'no' : 'yes',
                'send_attachments' => $settingsDefaults[$emailKey]['send_attachments']
            ];

            if ($box->box_type == 'email' && in_array($emailKey, $strictSubjectKeys)) {
                $savedSettings['email_subject'] = 'Re: {{ticket.title}}';
                $savedSettings['can_edit_subject'] = 'no';
            }

            return $savedSettings;
        }

        $savedSettings['key'] = $settingsDefaults[$emailKey]['key'];
        $savedSettings['title'] = $settingsDefaults[$emailKey]['title'];
        $savedSettings['description'] = $settingsDefaults[$emailKey]['description'];

        if ($box->box_type == 'email' && in_array($emailKey, $strictSubjectKeys)) {
            $savedSettings['email_subject'] = 'Re: {{ticket.title}}';
            $savedSettings['can_edit_subject'] = 'no';
        }

        if (empty($savedSettings['email_subject'])) {
            $savedSettings['email_subject'] = $settingsDefaults[$emailKey]['email_subject'];
        }

        if (empty($savedSettings['status'])) {
            $savedSettings['status'] = $settingsDefaults[$emailKey]['default_status'];
        }

        if (empty($savedSettings['email_body'])) {
            $savedSettings['email_body'] = $this->getDefaultEmailBody($emailKey, $box->box_type);
        }

        if (empty($savedSettings['send_attachments'])) {
            $savedSettings['send_attachments'] = $settingsDefaults[$emailKey]['send_attachments'];
        }

        return $savedSettings;
    }

    /**
     * getDefaultEmailBody method will return html for email body
     * @param $emailKey
     * @param string $type
     * @return string
     */
    private function getDefaultEmailBody($emailKey, $type = 'web')
    {
        if ($emailKey == 'ticket_created_email_to_customer') {
            if ($type == 'web') {
                return '<p>Hi <strong><em>{{customer.full_name}}</em>,</strong></p><p>Your request (<a href="{{ticket.public_url}}">#{{ticket.id}}</a>) has been received, and is being reviewed by our support staff.</p><p>To add additional comments, follow the link below:</p><h4><a href="{{ticket.public_url}}">View Ticket</a></h4><p>&nbsp;</p><p>or follow this link: {{ticket.public_url}}</p><hr /><p>{{business.name}}</p>';
            } else {
                return '<p>Hi <strong><em>{{customer.full_name}}</em>,</strong></p><p>Your request has been received, and is being reviewed by our support staff.</p><p>Our support staff will reply back to you soon</p>';
            }
        } else if ($emailKey == 'ticket_replied_by_agent_email_to_customer') {
            if ($type == 'web') {
                return '<p>Hi <strong><em>{{customer.full_name}}</em>,</strong></p><p>An agent just replied to your ticket "<strong>{{ticket.title}}</strong>" (<a href="{{ticket.public_url}}">#{{ticket.id}}</a>). To view his reply or add additional comments, click the button below:</p><h4><a href="{{ticket.public_url}}">View Ticket</a></h4><p>or follow this link: {{ticket.public_url}}</p><hr /><p>Regards,<br />{{business.name}}</p>';
            } else {
                return '{{response.full_content}}<p>Regards,<br />{{agent.full_name}}</p>';
            }
        } else if ($emailKey == 'ticket_closed_by_agent_email_to_customer') {
            if ($type == 'web') {
                return '<p>Hi <strong><em>{{customer.full_name}},</strong></p><p>Your ticket - {{ticket.title}}</p><p>We hope that the ticket was resolved to your satisfaction. If you feel that the ticket should not be closed or if the ticket has not been resolved, please reopen the ticket (<a href="{{ticket.public_url}}">#{{ticket.id}}</a>)</p><p>Regards,<br />{{business.name}}</p>';
            } else {
                return '<p>Hi <strong><em>{{customer.full_name}},</strong></p><p>Your ticket - {{ticket.title}}</p><p>We hope that the ticket was resolved to your satisfaction. If you feel that the ticket should not be closed or if the ticket has not been resolved, please feel free to reply back.<p>Regards,<br />{{business.name}}</p>';
            }
        } else if ($emailKey == 'ticket_created_email_to_admin') {
            return '<p>A new ticket (<a href="{{ticket.admin_url}}">{{ticket.title}}</a>) as been submitted by {{customer.full_name}}</p><h4>Ticket Body</h4><p>{{ticket.content}}</p><p><b><a href="{{ticket.admin_url}}">View Ticket</a></b></p>';
        } else if ($emailKey == 'ticket_replied_by_customer_email_to_admin') {
            return '<p>A new response has been added to "<a href="{{ticket.admin_url}}">{{ticket.title}}</a>"  by {{customer.full_name}}</p><h4>Response Body</h4><p>{{response.content}}</p><p><b><a href="{{ticket.admin_url}}">View Ticket</a></b></p>';
        } else if($emailKey == 'ticket_agent_on_change') {
            return '<p>Hi <strong><em>{{agent.full_name}}</em>,</strong></p><p>Ticket "<a href="{{ticket.admin_url}}">#{{ticket.id}}</a>" assigned to you.</p>';
        } else if($emailKey == 'ticket_created_by_agent_email_to_customer') {
            return '<p>Hi <strong><em>{{customer.full_name}}</em>,</strong></p><p>{{agent.full_name}} created a ticket on behalf of you, you can check it <a href="{{ticket.public_url}}">here</a></p>.';
        }

        return '';
    }
}
