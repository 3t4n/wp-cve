<?php

namespace MailOptin\KlaviyoConnect;

use MailOptin\Core\PluginSettings\Settings;
use MailOptin\Core\Repositories\AbstractCampaignLogMeta;

class SendCampaign extends AbstractKlaviyoConnect
{
    /** @var int ID of email campaign */
    public $email_campaign_id;

    /** @var int ID of campaign log */
    public $campaign_log_id;

    /** @var string campaign subject */
    public $campaign_subject;

    /** @var string campaign email in HTML */
    public $content_text;

    /** @var string campaign email in plain text */
    public $content_html;

    /**
     *
     * @param int $email_campaign_id
     * @param int $campaign_log_id
     * @param string $campaign_subject
     * @param string $content_html
     * @param string $content_text
     */
    public function __construct($email_campaign_id, $campaign_log_id, $campaign_subject, $content_html, $content_text = '')
    {
        parent::__construct();

        $this->email_campaign_id = $email_campaign_id;
        $this->campaign_log_id   = $campaign_log_id;
        $this->campaign_subject  = $campaign_subject;
        $this->content_html      = $content_html;
        $this->content_text      = $content_text;
    }

    /**
     * Send campaign via Campaign Monitor.
     *
     * @return array
     */
    public function send()
    {
        try {

            $list_id = $this->get_email_campaign_list_id($this->email_campaign_id);

            $campaign_title = $this->get_email_campaign_campaign_title($this->email_campaign_id);

            $result = $this->klaviyo_instance()->create_campaign([
                'data' => [
                    'type'       => 'campaign',
                    'attributes' => [
                        'name'              => $campaign_title,
                        'audiences'         => [
                            'included' => [$list_id]
                        ],
                        'send_strategy'     => [
                            'method' => 'immediate'
                        ],
                        'campaign-messages' => [
                            'data' => [
                                [
                                    'type'       => 'campaign-message',
                                    'attributes' => [
                                        'channel' => 'email',
                                        'content' => [
                                            'subject'    => $this->campaign_subject,
                                            'from_email' => Settings::instance()->from_email(),
                                            'from_label' => Settings::instance()->from_name()
                                        ]
                                    ]
                                ]
                            ]
                        ]
                    ]
                ]
            ]);

            if (isset($result['body']->data->id)) {

                $created_campaign_id = $result['body']->data->id;

                $message_id = $result['body']->data->relationships->{'campaign-messages'}->data[0]->id;

                $template_id = $this->create_template();

                if ($template_id) {

                    if ($this->assign_campaign_message_template($template_id, $message_id)) {

                        $result = $this->klaviyo_instance()->make_request('campaign-send-jobs/', [
                            'data' => [
                                'type' => 'campaign-send-job',
                                'id'   => $created_campaign_id
                            ]
                        ], 'post');

                        if (self::is_http_code_success($result['status_code'])) {

                            AbstractCampaignLogMeta::add_campaignlog_meta($this->campaign_log_id, 'klaviyo_campaign_id', $created_campaign_id);
                            $this->klaviyo_instance()->delete_template($template_id);

                            return parent::ajax_success();
                        }
                    }
                }
            }

            self::save_campaign_error_log(wp_json_encode($result['body']), $this->campaign_log_id, $this->email_campaign_id);

            $err = __('Unexpected error. Please try again', 'mailoptin');

            return parent::ajax_failure($err);

        } catch (\Exception $e) {
            self::save_campaign_error_log($e->getMessage(), $this->campaign_log_id, $this->email_campaign_id);

            return parent::ajax_failure($e->getMessage());
        }
    }

    /**
     * @return mixed
     * @throws \Exception
     */
    public function create_template()
    {
        $response = $this->klaviyo_instance()->create_template([
                'data' => [
                    'type'       => 'template',
                    'attributes' => [
                        'name'        => sprintf('Template: %s', $this->campaign_subject),
                        'editor_type' => 'CODE',
                        'html'        => $this->content_html,
                        'text'        => $this->content_text
                    ]
                ]
            ]
        );

        if (isset($response['body']->data->id)) {
            return $response['body']->data->id;
        }

        self::save_campaign_error_log(wp_json_encode($response['body']), $this->campaign_log_id, $this->email_campaign_id);

        return false;
    }

    /**
     * @return bool
     * @throws \Exception
     */
    public function assign_campaign_message_template($template_id, $message_id)
    {
        $response = $this->klaviyo_instance()->make_request('campaign-message-assign-template/', [
            'data' => [
                'type'          => 'campaign-message',
                'id'            => $message_id,
                'relationships' => [
                    'template' => [
                        'data' => [
                            'type' => 'template',
                            'id'   => $template_id
                        ]
                    ]
                ]
            ]
        ],
            'post'
        );

        if (self::is_http_code_success($response['status_code'])) {
            return true;
        }

        self::save_campaign_error_log(wp_json_encode($response['body']), $this->campaign_log_id, $this->email_campaign_id);

        return false;
    }
}