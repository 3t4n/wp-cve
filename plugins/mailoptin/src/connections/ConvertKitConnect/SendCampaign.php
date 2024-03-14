<?php

namespace MailOptin\ConvertKitConnect;

use MailOptin\Core\Repositories\AbstractCampaignLogMeta;
use MailOptin\Core\Repositories\EmailCampaignRepository;
use function MailOptin\Core\strtotime_utc;

class SendCampaign extends AbstractConvertKitConnect
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
     * Send campaign via Aweber.
     *
     * @return array
     */
    public function send()
    {
        try {

            $payload = [
                'content'     => $this->content_html,
                'subject'     => $this->campaign_subject,
                'description' => EmailCampaignRepository::get_email_campaign_name($this->email_campaign_id),
                'public'      => EmailCampaignRepository::get_customizer_value($this->email_campaign_id, 'ConvertKitConnect_make_public', false),
                'send_at'     => gmdate('Y-m-d\TH:i:s\Z', strtotime('-1 hour')) // setting a time in the past will send the newsletter immediately
            ];

            $template_name = $this->connections_settings->convertkit_template_name();

            if ( ! empty($template_name)) {
                $payload['email_layout_template'] = $template_name;
            }

            $response = $this->convertkit_instance()->send_newsletter($payload);

            if (is_array($response) && isset($response['body']->broadcast->id)) {

                $campaign_id = $response['body']->broadcast->id;

                // save the broadcast ID against the campaign log.
                AbstractCampaignLogMeta::add_campaignlog_meta($this->campaign_log_id, 'convertkit_campaign_id', $campaign_id);

                return self::ajax_success();
            }

            $err = __('Unexpected error. Please try again', 'mailoptin');
            self::save_campaign_error_log(json_encode($response['body']), $this->campaign_log_id, $this->email_campaign_id);

            return parent::ajax_failure($err);

        } catch (\Exception $e) {
            self::save_campaign_error_log($e->getMessage(), $this->campaign_log_id, $this->email_campaign_id);

            return parent::ajax_failure($e->getMessage());
        }
    }
}