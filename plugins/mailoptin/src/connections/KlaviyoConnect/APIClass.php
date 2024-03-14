<?php
/**
 * Copyright (C) 2016  Agbonghama Collins <me@w3guy.com>
 */

namespace MailOptin\KlaviyoConnect;

class APIClass
{
    protected $api_key;

    protected $revision = '2023-12-15';

    /**
     * @var string
     */
    protected $api_url = 'https://a.klaviyo.com/api/';


    public function __construct($api_key)
    {
        $this->api_key = $api_key;
    }

    /**
     * @param $endpoint
     * @param array $args
     * @param string $method
     *
     * @return array
     * @throws \Exception
     */
    public function make_request($endpoint, $args = [], $method = 'get')
    {
        $wp_args = ['method' => strtoupper($method), 'timeout' => 30];

        $url = $this->api_url . $endpoint;

        $wp_args['headers'] = [
            "Authorization" => sprintf('Klaviyo-API-Key %s', $this->api_key),
            "revision"      => $this->revision,
            "Content-Type"  => 'application/json'
        ];

        if ($method !== 'get') {
            $args = json_encode($args);
        }

        switch ($method) {
            case 'post':
                $wp_args['body'] = $args;
                break;
            case 'get':
                $url = add_query_arg($args, $url);
                break;
            default:
                $wp_args['body'] = $args;
                break;
        }

        $response = wp_remote_request($url, $wp_args);

        if (is_wp_error($response)) {
            throw new \Exception($response->get_error_message());
        }

        $response_body      = json_decode(wp_remote_retrieve_body($response));
        $response_http_code = wp_remote_retrieve_response_code($response);

        return ['status_code' => $response_http_code, 'body' => $response_body];
    }

    /**
     * @return array
     * @throws \Exception
     */
    public function get_lists()
    {
        return $this->make_request('lists/');
    }

    /**
     * @param string $list_id
     * @param array $properties extra data to tie to the subscriber
     *
     * @return array
     * @throws \Exception
     */
    public function add_subscriber($list_id, $properties = [])
    {
        $body               = $properties['main'];
        $body['properties'] = $properties['extra'];

        $payload = [
            'data' => [
                'type'       => 'profile',
                'attributes' => $body
            ]
        ];

        $response = $this->make_request("profiles/", $payload, 'post');

        if (isset($response['body']->data->id)) {
            $payload2 = [
                'data' => [
                    'type'          => 'profile-subscription-bulk-create-job',
                    'attributes'    => [
                        'profiles' => [
                            'data' => [
                                [
                                    'type'       => 'profile',
                                    'id'         => $response['body']->data->id,
                                    'attributes' => [
                                        'email'         => $properties['main']['email'],
                                        'subscriptions' => [
                                            'email' => [
                                                'marketing' => [
                                                    'consent' => 'SUBSCRIBED'
                                                ]
                                            ]
                                        ]
                                    ]
                                ]
                            ]
                        ]
                    ],
                    'relationships' => [
                        'list' => [
                            'data' => [
                                'type' => 'list',
                                'id'   => $list_id
                            ]
                        ]
                    ]
                ]
            ];

            $this->make_request("profile-subscription-bulk-create-jobs/", $payload2, 'post');
        }

        return $response;
    }

    /**
     * @param $payload
     *
     * @return array
     *
     * @throws \Exception
     */
    public function create_template($payload)
    {
        return $this->make_request('templates', $payload, 'post');
    }

    /**
     * @param string $template_id
     *
     * @return array
     *
     * @throws \Exception
     */
    public function delete_template($template_id)
    {
        return $this->make_request("templates/{$template_id}/", [], 'delete');
    }

    /**
     * @param array $payload {
     *
     * @type string $list_id
     * @type string $template_id
     * @type string $from_email
     * @type string $from_name
     * @type string $subject
     * @type string $name (optional)
     * @type string $use_smart_sending (optional)
     * @type string $add_google_analytics (optional)
     * }
     *
     * @return array
     *
     * @throws \Exception
     */
    public function create_campaign($payload)
    {
        return $this->make_request('campaigns/', $payload, 'post');
    }

    /**
     * @param $campaign_id
     *
     * @return array
     * @throws \Exception
     */
    public function send_immediately($campaign_id)
    {
        if (empty($campaign_id)) {
            throw new \Exception('Campaign ID is required');
        }

        $response = $this->make_request("campaign/$campaign_id/send", [], 'post');

        return $response;
    }
}