<?php

namespace MailOptin\IContactConnect;

use MailOptin\Core\Connections\AbstractConnect;

class ConnectSettingsPage extends AbstractIContactConnect
{
    public function __construct()
    {
        parent::__construct();

        add_filter('mailoptin_connections_settings_page', array($this, 'connection_settings'));
        add_action('wp_cspa_settings_after_title', array($this, 'output_error_log_link'), 10, 2);
    }

    public function connection_settings($arg)
    {
        $connected = AbstractIContactConnect::is_connected(true);
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
                'section_title_without_status' => __('iContact', 'mailoptin'),
                'section_title'                => __('iContact Connection', 'mailoptin') . " $status",
                'type'                         => AbstractConnect::EMAIL_MARKETING_TYPE,
                'icontact_app_id'              => [
                    'type'        => 'text',
                    'label'       => __('Application ID', 'mailoptin'),
                    'description' => sprintf(
                        __('Log in to your %siContact account%s to generate or get your application ID.', 'mailoptin'),
                        '<a target="_blank" href="https://app.icontact.com/icp/core/fusion/settings/integrations">',
                        '</a>'
                    ),
                ],
                'icontact_username'            => [
                    'type'        => 'text',
                    'label'       => __('Username / Email Address', 'mailoptin'),
                    'description' => __('Enter the provided application email address or username here. ', 'mailoptin'),
                ],
                'icontact_password'            => [
                    'type'          => 'text',
                    'obfuscate_val' => true,
                    'label'         => __('Application Password', 'mailoptin'),
                    'description'   => sprintf(
                        __('Log in to your %siContact account%s to generate or get your application password.', 'mailoptin'),
                        '<a target="_blank" href="https://app.icontact.com/icp/core/fusion/settings/integrations">',
                        '</a>'
                    ),
                ],
                'icontact_account_id'          => [
                    'type'        => 'text',
                    'label'       => __('Account ID', 'mailoptin'),
                    'description' => __('Enter your iContact account ID here.', 'mailoptin')
                ],
                'icontact_client_folder_id'    => [
                    'type'        => 'text',
                    'label'       => __('Client Folder ID', 'mailoptin'),
                    'description' => __('Enter the provided client folder ID here.', 'mailoptin')
                ]
            ]
        ];

        return array_merge($arg, $settings);
    }

    public function output_error_log_link($option, $args)
    {
        //Not a icontact connection section
        if (MAILOPTIN_CONNECTIONS_DB_OPTION_NAME !== $option || ! isset($args['icontact_app_id'])) {
            return;
        }

        //Output error log link if  there is one
        echo AbstractConnect::get_optin_error_log_link('icontact');

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