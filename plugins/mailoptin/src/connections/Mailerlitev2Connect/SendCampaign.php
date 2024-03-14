<?php

namespace MailOptin\Mailerlitev2Connect;

use MailOptin\Core\PluginSettings\Settings;
use MailOptin\Core\Repositories\AbstractCampaignLogMeta;

class SendCampaign extends AbstractMailerlitev2Connect
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
     * Send campaign via Mailerlite.
     *
     * @return array
     */
    public function send()
    {
        try {

            $list_id = $this->get_email_campaign_list_id($this->email_campaign_id);

            $campaignData = apply_filters('mailoptin_mailerlitev2_campaign_settings',
                [
                    'name'   => $this->get_email_campaign_campaign_title($this->email_campaign_id),
                    'type'   => 'regular',
                    'emails' => [
                        [
                            'subject'   => $this->campaign_subject,
                            'from_name' => Settings::instance()->from_name(),
                            'from'      => Settings::instance()->from_email(),
                            'content'   => $this->content_html
                        ]
                    ],
                    'groups' => [$list_id],
                ], $this->email_campaign_id
            );

            $campaignsResponse = $this->mailerlitev2_instance()->make_request('campaigns', $campaignData, 'post');

            if ( ! empty($campaignsResponse['body']['data']['id'])) {

                $campaign_id = $campaignsResponse['body']['data']['id'];

                // save the Mailerlite campaign ID against the campaign log.
                AbstractCampaignLogMeta::add_campaignlog_meta($this->campaign_log_id, 'mailerlitev2_campaign_id', $campaign_id);

                $sendCampaignsResponse = $this->mailerlitev2_instance()->make_request(
                    sprintf('campaigns/%s/schedule', $campaign_id),
                    ['delivery' => 'instant'],
                    'post'
                );

                if (isset($sendCampaignsResponse['body']['data']['id'])) {
                    return self::ajax_success();
                }

                self::save_campaign_error_log(wp_json_encode($sendCampaignsResponse['body']), $this->campaign_log_id, $this->email_campaign_id);

            } else {

                self::save_campaign_error_log(wp_json_encode($campaignsResponse['body']), $this->campaign_log_id, $this->email_campaign_id);
            }

            return parent::ajax_failure(__('Unexpected error. Please try again', 'mailoptin'));

        } catch (\Exception $e) {
            self::save_campaign_error_log($e->getMessage(), $this->campaign_log_id, $this->email_campaign_id);

            return parent::ajax_failure($e->getMessage());
        }
    }
}