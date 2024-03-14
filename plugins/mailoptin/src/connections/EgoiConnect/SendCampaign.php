<?php

namespace MailOptin\EgoiConnect;

use MailOptin\Core\Repositories\AbstractCampaignLogMeta;

class SendCampaign extends AbstractEgoiConnect
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
     * Constructor poop.
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
     * Filter our non-null values.
     *
     * @param string $value
     *
     * @return bool
     */
    public function isNotNull($value)
    {
        return ! is_null($value);
    }

    /**
     * Send campaign via Constant Contact.
     *
     * @return array
     */
    public function send()
    {
        try {

            $list_id = $this->get_email_campaign_list_id($this->email_campaign_id);

            $campaign_title = $this->get_email_campaign_campaign_title($this->email_campaign_id);

            $sender_id = $this->connections_settings->egoi_sender();

            if (empty($sender_id)) {
                throw new \Exception('Sender ID is not defined');
            }

            $payload = [
                'list_id'       => $list_id,
                'internal_name' => $campaign_title,
                'subject'       => $this->campaign_subject,
                'content'       => [
                    'type'       => 'html',
                    'body'       => $this->content_html,
                    'plain_text' => $this->content_text
                ],
                'sender_id'     => absint($sender_id)
            ];

            $response = $this->egoi_instance()->make_request(
                'campaigns/email',
                apply_filters('mailoptin_egoi_campaign_settings', $payload, $this->email_campaign_id),
                'post'
            );

            if (isset($response['body']['campaign_hash'])) {

                $campaign_hash = $response['body']['campaign_hash'];

                $result = $this->egoi_instance()->make_request(
                    sprintf('campaigns/email/%s/actions/send', $campaign_hash),
                    [
                        'list_id'  => $list_id,
                        'segments' => [
                            'type' => 'none'
                        ]
                    ],
                    'post'
                );

                if (self::is_http_code_success($result['status_code'])) {
                    AbstractCampaignLogMeta::add_campaignlog_meta($this->campaign_log_id, 'egoi_campaign_id', $campaign_hash);

                    return parent::ajax_success();
                }

                throw new \Exception(is_string($result['body']) ? $result['body'] : $result($response['body']));
            }

            throw new \Exception(is_string($response['body']) ? $response['body'] : wp_json_encode($response['body']));

        } catch (\Exception $e) {
            self::save_campaign_error_log($e->getMessage(), $this->campaign_log_id, $this->email_campaign_id);

            return parent::ajax_failure(__('Unexpected error. Please try again', 'mailoptin'));
        }
    }
}