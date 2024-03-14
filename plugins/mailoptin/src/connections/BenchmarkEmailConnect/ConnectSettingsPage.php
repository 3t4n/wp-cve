<?php

namespace MailOptin\BenchmarkEmailConnect;

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
        $connected = AbstractBenchmarkEmailConnect::is_connected(true);
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
            'section_title_without_status' => __('Benchmark Email', 'mailoptin'),
            'section_title'                => __('Benchmark Email Connection', 'mailoptin') . " $status",
            'type'                         => AbstractConnect::EMAIL_MARKETING_TYPE,
            'benchmarkemail_api_key'             => [
                'type'          => 'text',
                'obfuscate_val' => true,
                'label'         => __('Enter API Key', 'mailoptin'),
                'description'   => sprintf(
                    __('%sGet your API key here%s', 'mailoptin'),
                    '<a target="_blank" href="https://ui.benchmarkemail.com/Integrate#API">',
                    '</a>'
                )
            ]
        ];

        return array_merge($arg, $settingsArg);
    }

    public function output_error_log_link($option, $args)
    {
        //Not a benchmarkemail connection section
        if (MAILOPTIN_CONNECTIONS_DB_OPTION_NAME !== $option || ! isset($args['benchmarkemail_api_key'])) {
            return;
        }

        //Output error log link if  there is one
        echo AbstractConnect::get_optin_error_log_link('benchmarkemail');
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