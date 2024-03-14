<?php

namespace MailOptin\EgoiConnect;

use MailOptin\Core\Connections\AbstractConnect;

class ConnectSettingsPage extends AbstractEgoiConnect
{
    public function __construct()
    {
        parent::__construct();

        add_filter('mailoptin_connections_settings_page', array($this, 'connection_settings'));
        add_action('wp_cspa_settings_after_title', array($this, 'output_error_log_link'), 10, 2);
    }

    public function get_senders()
    {
        $senders = get_transient('mailoptin_egoi_sender_list');

        if ($senders === false) {

            $senders = ['' => esc_html__('Select...', 'mailoptin')];

            try {

                $response = $this->egoi_instance()->make_request('senders/email', ['limit' => 100, 'status' => 'active']);

                if (self::is_http_code_success($response['status_code'])) {

                    $senders = array_reduce($response['body']['items'], function ($carry, $item) {
                        $carry[$item['sender_id']] = sprintf('%s (%s)', $item['name'], $item['email']);

                        return $carry;
                    }, $senders);

                    set_transient('mailoptin_egoi_sender_list', $senders, 5 * MINUTE_IN_SECONDS);

                } else {
                    throw new \Exception(is_string($response['body']) ? $response['body'] : wp_json_encode($response['body']));
                }

            } catch (\Exception $e) {
                self::save_optin_error_log(is_string($e->getMessage()) ? $e->getMessage() : wp_json_encode($e->getMessage()), 'egoi');
            }
        }

        return $senders;
    }

    public function connection_settings($arg)
    {
        $connected = AbstractEgoiConnect::is_connected(true);
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
                'section_title_without_status' => __('E-Goi', 'mailoptin'),
                'section_title'                => __('E-Goi Connection', 'mailoptin') . " $status",
                'type'                         => AbstractConnect::EMAIL_MARKETING_TYPE,
                'egoi_api_key'                 => [
                    'type'          => 'text',
                    'obfuscate_val' => true,
                    'label'         => __('API Key', 'mailoptin'),
                    'description'   => sprintf(
                        __('Log in to your %sE-Goi account%s to generate or get your API key.', 'mailoptin'),
                        '<a target="_blank" href="https://bo.egoiapp.com/#/integrations/overview">',
                        '</a>'
                    ),
                ],
                'egoi_sender'                  => [
                    'type'        => 'select',
                    'label'       => __('Verified Sender', 'mailoptin'),
                    'options'     => $this->get_senders(),
                    'description' => esc_html__('Select a verified sender that will be used for sending emails to your E-Goi contacts.', 'mailoptin'),
                ]
            ]
        ];

        if ( ! self::is_connected()) {
            unset($settings[0]['egoi_sender']);
        }

        return array_merge($arg, $settings);
    }

    public function output_error_log_link($option, $args)
    {
        //Not a egoi connection section
        if (MAILOPTIN_CONNECTIONS_DB_OPTION_NAME !== $option || ! isset($args['egoi_api_key'])) {
            return;
        }

        //Output error log link if  there is one
        echo AbstractConnect::get_optin_error_log_link('egoi');

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