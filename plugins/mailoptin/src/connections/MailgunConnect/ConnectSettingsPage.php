<?php

namespace MailOptin\MailgunConnect;

use MailOptin\Core\Connections\AbstractConnect;

class ConnectSettingsPage extends AbstractMailgunConnect
{
    public function __construct()
    {
        parent::__construct();

        add_filter('mailoptin_connections_settings_page', array($this, 'connection_settings'));
        add_action('wp_cspa_settings_after_title', array($this, 'output_error_log_link'), 10, 2);
    }

    public function connection_settings($arg)
    {
        $connected = AbstractMailgunConnect::is_connected(true);
        if (true === $connected) {
            $status = sprintf('<span style="color:#008000">(%s)</span>', __('Connected', 'mailoptin'));
        } else {
            $msg = '';
            if (is_string($connected)) {
                $msg = esc_html(" &mdash; $connected");
            }
            $status = sprintf("<span style='color:#FF0000'>(%s$msg) </span>", __('Not Connected', 'mailoptin'));
        }

        $settings = [
            [
                'section_title_without_status' => __('Mailgun', 'mailoptin'),
                'section_title'                => __('Mailgun Connection', 'mailoptin') . " $status",
                'type'                         => AbstractConnect::EMAIL_MARKETING_TYPE,
                'mailgun_api_key'              => [
                    'type'          => 'text',
                    'obfuscate_val' => true,
                    'label'         => __('API Key', 'mailoptin'),
                    'description'   => sprintf(
                        __('Log in to your %sMailgun account%s to generate or get your API key.', 'mailoptin'),
                        '<a target="_blank" href="https://app.mailgun.com/settings/api_security">',
                        '</a>'
                    ),
                ],
                'mailgun_domain_name'          => [
                    'type'        => 'text',
                    'label'       => __('Domain Name', 'mailoptin'),
                    'placeholder' => 'mg.mydomain.com',
                    'description' => __('Enter your Mailgun domain name.', 'mailoptin'),
                ],
                'mailgun_domain_region'        => [
                    'type'    => 'select',
                    'label'   => __('Domain Region', 'mailoptin'),
                    'options' => ['us' => 'US', 'eu' => 'EU']
                ]
            ]
        ];

        return array_merge($arg, $settings);
    }

    public function output_error_log_link($option, $args)
    {
        //Not a mailgun connection section
        if (MAILOPTIN_CONNECTIONS_DB_OPTION_NAME !== $option || ! isset($args['mailgun_api_key'])) {
            return;
        }

        //Output error log link if  there is one
        echo AbstractConnect::get_optin_error_log_link('mailgun');
    }

    public static function get_instance()
    {
        static $instance = null;

        if (is_null($instance)) {
            $instance = new self();
        }

        return $instance;
    }
}