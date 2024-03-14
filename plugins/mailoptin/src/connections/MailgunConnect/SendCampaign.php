<?php

namespace MailOptin\MailgunConnect;

use MailOptin\Core\PluginSettings\Connections;
use MailOptin\Core\PluginSettings\Settings;
use MailOptin\Core\Repositories\AbstractCampaignLogMeta;

class SendCampaign extends AbstractMailgunConnect
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

            $payload = [
                'from'    => Settings::instance()->from_email(),
                'to'      => $list_id,
                'subject' => $this->campaign_subject,
                'text'    => $this->content_text,
                'html'    => $this->content_html
            ];

            $response = $this->mailgun_instance()->make_request(
                sprintf('%s/messages', Connections::instance()::instance()->mailgun_domain_name()),
                apply_filters('mailoptin_mailgun_campaign_settings', $payload, $this->email_campaign_id),
                'post'
            );

            if (self::is_http_code_success($response['status_code']) && isset($response['body']['id'])) {
                AbstractCampaignLogMeta::add_campaignlog_meta($this->campaign_log_id, 'mailgun_campaign_id', $response['body']['id']);

                return parent::ajax_success();
            }

            self::save_campaign_error_log(is_string($response['body']) ? $response['body'] : wp_json_encode($response['body']), $this->campaign_log_id, $this->email_campaign_id);

            return parent::ajax_failure(__('Unexpected error. Please try again', 'mailoptin'));

        } catch (\Exception $e) {
            self::save_campaign_error_log($e->getMessage(), $this->campaign_log_id, $this->email_campaign_id);

            return parent::ajax_failure($e->getMessage());
        }
    }
}