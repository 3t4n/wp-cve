<?php

namespace FluentSupport\App\Hooks\Handlers;

use FluentSupport\App\App;
use FluentSupport\App\Models\Meta;
use FluentSupport\App\Services\EmailNotification\Settings;
use FluentSupport\App\Services\Emogrifier;
use FluentSupport\App\Services\Helper;
use FluentSupport\App\Services\Mailer;
use FluentSupport\Framework\Support\Arr;

class EmailNotificationHandler
{
    public function ticketCreated($ticket, $customer)
    {
        $mailbox = $ticket->mailbox;

        if (!$mailbox) {
            return;
        }

        // Let's send welcome email to customer if enabled
        $emailSettings = (new Settings())->getBoxEmailSettings($mailbox, 'ticket_created_email_to_customer');

        if ($emailSettings && $emailSettings['status'] == 'yes') {
            $subject = apply_filters('fluent_support/parse_smartcode_data', $emailSettings['email_subject'], [
                'customer' => $customer,
                'business' => $mailbox,
                'ticket'   => $ticket
            ]);

            $emailBody = $this->parseEmailBody($emailSettings['email_body'], [
                'customer'   => $customer,
                'business'   => $mailbox,
                'ticket'     => $ticket,
                'email_type' => 'ticket_created_email_to_customer'
            ]);

            $headers = $mailbox->getMailerHeader();

            $headers = apply_filters('fluent_support/mail_to_customer_header', $headers, [
                'cc_email'  => $ticket->getSettingsValue('cc_email', []),
                'hook_type' => 'ticket_created_email_to_customer'
            ]);

            $attachments = [];

            if ($emailSettings['send_attachments'] == 'yes' && ($files = $ticket->attachments)) {
                foreach ($files as $file) {
                    if ($file->driver == 'local') {
                        $filePath = $file->file_path;
                    } else {
                        $filePath = Arr::get($file->settings, 'local_temp_path');
                    }
                    if (file_exists($filePath)) {
                        $attachments[] = $filePath;
                    }
                }
            }

            Mailer::send($customer->email, $subject, $emailBody, $headers, $attachments);
        }

        // let's send email to admin if enabled
        $emailSettings = (new Settings())->getBoxEmailSettings($mailbox, 'ticket_created_email_to_admin');
        if ($emailSettings && $emailSettings['status'] == 'yes' && is_email($mailbox->settings['admin_email_address'])) {

            $mailTo = Arr::get($mailbox->settings, 'admin_email_address');

            if (!$mailTo || !is_email($mailTo)) {
                return;
            }

            $subject = apply_filters('fluent_support/parse_smartcode_data', $emailSettings['email_subject'], [
                'customer' => $customer,
                'business' => $mailbox,
                'ticket'   => $ticket
            ]);

            $emailBody = $this->parseEmailBody($emailSettings['email_body'], [
                'customer'   => $customer,
                'business'   => $mailbox,
                'ticket'     => $ticket,
                'email_type' => 'ticket_created_email_to_admin'
            ]);

            $headers = $mailbox->getMailerHeader();

            $attachments = [];

            if ($emailSettings['send_attachments'] == 'yes' && ($files = $ticket->attachments)) {
                foreach ($files as $file) {

                    if ($file->driver == 'local') {
                        $filePath = $file->file_path;
                    } else {
                        $filePath = Arr::get($file->settings, 'local_temp_path');
                    }
                    if (file_exists($filePath)) {
                        $attachments[] = $filePath;
                    }

                }
            }

            Mailer::send($mailTo, $subject, $emailBody, $headers, $attachments);
        }
    }

    public function agentReplied($response, $ticket, $agent)
    {
        $mailbox = $ticket->mailbox;


        if (!$mailbox) {
            return false;
        }

        // Let's send welcome email to customer if enabled
        $emailSettings = (new Settings())->getBoxEmailSettings($mailbox, 'ticket_replied_by_agent_email_to_customer');


        if ($emailSettings && $emailSettings['status'] == 'yes') {

            $ticket->load('customer');
            $customer = $ticket->customer;

            if ($customer->status == 'inactive') {
                return false;
            }

            $subject = apply_filters('fluent_support/parse_smartcode_data', $emailSettings['email_subject'], [
                'customer' => $customer,
                'business' => $mailbox,
                'ticket'   => $ticket,
                'response' => $response,
                'agent'    => $agent
            ]);

            $emailBody = $this->parseEmailBody($emailSettings['email_body'], [
                'customer'   => $customer,
                'business'   => $mailbox,
                'ticket'     => $ticket,
                'response'   => $response,
                'agent'      => $agent,
                'email_type' => 'ticket_replied_by_agent_email_to_customer'
            ]);


            $headers = $mailbox->getMailerHeader();
            $headers = apply_filters('fluent_support/mail_to_customer_header', $headers, [
                'cc_email'  => $response->getSettingsValue('cc_email', []),
                'hook_type' => 'ticket_replied_by_agent_email_to_customer'
            ]);
            $attachments = [];

            if ($emailSettings['send_attachments'] == 'yes' && ($files = $response->attachments)) {
                foreach ($files as $file) {
                    if ($file->driver == 'local') {
                        $filePath = $file->file_path;
                    } else {
                        $filePath = Arr::get($file->settings, 'local_temp_path');
                    }
                    if (file_exists($filePath)) {
                        $attachments[] = $filePath;
                    }
                }
            }

            Mailer::send($customer->email, $subject, $emailBody, $headers, $attachments);
        }
    }

    public function closedByAgent($ticket, $agent)
    {
        $mailbox = $ticket->mailbox;
        if (!$mailbox) {
            return;
        }

        // Let's send welcome email to customer if enabled
        $emailSettings = (new Settings())->getBoxEmailSettings($mailbox, 'ticket_closed_by_agent_email_to_customer');
        if ($emailSettings && $emailSettings['status'] == 'yes') {

            $ticket->load('customer');
            $customer = $ticket->customer;

            if ($customer->status == 'inactive') {
                return false;
            }


            $subject = apply_filters('fluent_support/parse_smartcode_data', $emailSettings['email_subject'], [
                'customer' => $customer,
                'business' => $mailbox,
                'ticket'   => $ticket,
                'agent'    => $agent,
            ]);

            $emailBody = $this->parseEmailBody($emailSettings['email_body'], [
                'customer'   => $customer,
                'business'   => $mailbox,
                'ticket'     => $ticket,
                'agent'      => $agent,
                'email_type' => 'ticket_closed_by_agent_email_to_customer'
            ]);

            $headers = $mailbox->getMailerHeader();
            $headers = apply_filters('fluent_support/mail_to_customer_header', $headers, [
                'cc_email'  => $ticket->getSettingsValue('cc_email', []),
                'hook_type' => 'ticket_closed_by_agent_email_to_customer'
            ]);
            Mailer::send($customer->email, $subject, $emailBody, $headers);
        }
    }

    public function customerReplied($response, $ticket, $customer)
    {
        $mailbox = $ticket->mailbox;
        if (!$mailbox) {
            return;
        }

        // Let's send welcome email to customer if enabled
        $emailSettings = (new Settings())->getBoxEmailSettings($mailbox, 'ticket_replied_by_customer_email_to_admin');
        if ($emailSettings && $emailSettings['status'] == 'yes') {

            $ticket->load('agent');
            $agent = $ticket->agent;

            $emailTo = $mailbox->settings['admin_email_address'];
            if ($agent) {
                $emailTo = $agent->email;
            }

            if (!$emailTo || !is_email($emailTo)) {
                return;
            }

            $subject = apply_filters('fluent_support/parse_smartcode_data', $emailSettings['email_subject'], [
                'customer' => $customer,
                'business' => $mailbox,
                'ticket'   => $ticket,
                'response' => $response,
                'agent'    => $agent
            ]);

            $emailBody = $this->parseEmailBody($emailSettings['email_body'], [
                'customer'   => $customer,
                'business'   => $mailbox,
                'ticket'     => $ticket,
                'response'   => $response,
                'agent'      => $agent,
                'email_type' => 'ticket_replied_by_customer_email_to_admin'
            ]);

            $headers = $mailbox->getMailerHeader();
            $attachments = [];

            if ($emailSettings['send_attachments'] == 'yes' && ($files = $response->attachments)) {
                foreach ($files as $file) {
                    if ($file->driver == 'local') {
                        $filePath = $file->file_path;
                    } else {
                        $filePath = Arr::get($file->settings, 'local_temp_path');
                    }
                    if (file_exists($filePath)) {
                        $attachments[] = $filePath;
                    }
                }
            }

            Mailer::send($emailTo, $subject, $emailBody, $headers, $attachments);
        }
    }

    public function onAgentAssign($agent, $ticket, $assigner)
    {
        $currentUser = Helper::getAgentByUserId();

        if ($currentUser && $currentUser->user_id == $agent->user_id) {
            return;
        }

        $mailbox = $ticket->mailbox;

        if (!$mailbox) {
            return;
        }

        // Let's send notification email to agent if enabled
        $emailSettings = (new Settings())->getBoxEmailSettings($mailbox, 'ticket_agent_on_change');

        if ($emailSettings && $emailSettings['status'] == 'yes') {

            if ($agent) {
                $emailTo = $agent->email;
            }

            if (!$emailTo || !is_email($emailTo)) {
                return;
            }

            $subject = apply_filters('fluent_support/parse_smartcode_data', $emailSettings['email_subject'], [
                'business' => $mailbox,
                'ticket'   => $ticket,
                'agent'    => $agent,
                'assigner' => $assigner
            ]);

            $emailBody = $this->parseEmailBody($emailSettings['email_body'], [
                'business'   => $mailbox,
                'ticket'     => $ticket,
                'agent'      => $agent,
                'assigner'   => $assigner,
                'email_type' => 'ticket_agent_on_change'
            ]);

            $headers = $mailbox->getMailerHeader();

            Mailer::send($emailTo, $subject, $emailBody, $headers);
        }
    }

    public function ticketCreatedByAgent($ticket, $customer, $agent)
    {
        $mailbox = $ticket->mailbox;

        if (!$mailbox) {
            return;
        }

        // Let's send welcome email to customer if enabled
        $emailSettings = (new Settings())->getBoxEmailSettings($mailbox, 'ticket_created_by_agent_email_to_customer');
        if ($emailSettings && $emailSettings['status'] == 'yes') {
            $subject = apply_filters('fluent_support/parse_smartcode_data', $emailSettings['email_subject'], [
                'customer' => $customer,
                'agent'    => $agent,
                'business' => $mailbox,
                'ticket'   => $ticket
            ]);

            $emailBody = $this->parseEmailBody($emailSettings['email_body'], [
                'customer'   => $customer,
                'business'   => $mailbox,
                'agent'      => $agent,
                'ticket'     => $ticket,
                'email_type' => 'ticket_created_by_agent_email_to_customer'
            ]);

            $headers = $mailbox->getMailerHeader();

            $attachments = [];

            if ($emailSettings['send_attachments'] == 'yes' && ($files = $ticket->attachments)) {
                foreach ($files as $file) {
                    if ($file->driver == 'local') {
                        $filePath = $file->file_path;
                    } else {
                        $filePath = Arr::get($file->settings, 'local_temp_path');
                    }
                    if (file_exists($filePath)) {
                        $attachments[] = $filePath;
                    }
                }
            }

            Mailer::send($customer->email, $subject, $emailBody, $headers, $attachments);
        }
    }

    private function emailTemplateCss()
    {
        $app = App::getInstance();
        return $app->view->make('emails.styles');
    }

    protected function parseEmailBody($emailBody, $data)
    {
        $data['email_body'] = apply_filters('fluent_support/parse_smartcode_data', $emailBody, $data);

        $app = App::getInstance();

        if (isset($data['business'])) {
            $businessName = $data['business']->name;
        } else {
            $businessName = get_bloginfo('name');
        }

        $footerText = apply_filters('fluent_support/email_footer_credit', 'This email is a service from ' . $businessName . '. Support Plugin is Powered by <a href="https://fluentsupport.com/?utm_source=user&utm_medium=wp&utm_campaign=mail_footer" style="color:#9e9e9e;" target="_new">FluentSupport</a>.');

        $data['email_footer'] = $footerText;

        $emailTypeForCustomerFooter = ['ticket_created_email_to_customer', 'ticket_replied_by_agent_email_to_customer', 'ticket_closed_by_agent_email_to_customer'];

        if (defined('FLUENTSUPPORTPRO') && in_array($data['email_type'], $emailTypeForCustomerFooter)
            && !is_null($data['business']->email_footer)) {
            $data['email_footer'] = apply_filters('fluent_support/parse_smartcode_data', $data['business']->email_footer, $data);
        }

        $emailBody = $app->view->make('emails.ticket_template', $data);
        $emogrifier = new Emogrifier($emailBody, $this->emailTemplateCss());
        $emogrifier->disableInvisibleNodeRemoval();
        return $emogrifier->emogrify();
    }

    public function getMailerHeaderWithCc($headers, $info)
    {
        if (isset($info['cc_email'])) {
            $CcHeaders = !empty($info['cc_email']) && is_array($info['cc_email']) ? implode(', ', $info['cc_email']) : '';
            $headers[] = $CcHeaders ? "Cc: $CcHeaders" : '';
        }

        return $headers;
    }

}
