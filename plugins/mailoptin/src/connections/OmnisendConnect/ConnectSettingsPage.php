<?php

namespace MailOptin\OmnisendConnect;

use MailOptin\Core\Connections\AbstractConnect;

class ConnectSettingsPage
{
    public function __construct()
    {
        add_filter('mailoptin_connections_settings_page', array($this, 'connection_settings'));

        add_action('wp_cspa_settings_after_title', array($this, 'output_error_log_link'), 10, 2);
    }

    public function connection_settings($arg)
    {
        $connected = AbstractOmnisendConnect::is_connected(true);
        if (true === $connected) {
            $status = sprintf('<span style="color:#008000">(%s)</span>', __('Connected', 'mailoptin'));
        } else {
            $msg = '';
            if (is_string($connected)) {
                $msg = esc_html(" &mdash; $connected");
            }
            $status = sprintf("<span style='color:#FF0000'>(%s$msg) </span>", __('Not Connected', 'mailoptin'));
        }

        $settingsArg[] = [
            'section_title_without_status' => __('Omnisend', 'mailoptin'),
            'section_title'                => __('Omnisend Connection', 'mailoptin') . " $status",
            'type'                         => AbstractConnect::EMAIL_MARKETING_TYPE,
            'omnisend_api_key'             => [
                'type'          => 'text',
                'obfuscate_val' => true,
                'label'         => __('Enter API Key', 'mailoptin'),
                'description'   => sprintf(
                    __('%sGet your API key here%s', 'mailoptin'),
                    '<a target="_blank" href="https://app.omnisend.com/integrations/api-keys">',
                    '</a>'
                )
            ]
        ];

        return array_merge($arg, $settingsArg);
    }

    public function output_error_log_link($option, $args)
    {
        //Not a omnisend connection section
        if (MAILOPTIN_CONNECTIONS_DB_OPTION_NAME !== $option || ! isset($args['omnisend_api_key'])) {
            return;
        }

        //Output error log link if  there is one
        echo AbstractConnect::get_optin_error_log_link('omnisend');
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
