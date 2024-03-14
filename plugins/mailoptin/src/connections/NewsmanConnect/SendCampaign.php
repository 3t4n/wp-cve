<?php

namespace MailOptin\NewsmanConnect;

use MailOptin\Core\PluginSettings\Settings;
use MailOptin\Core\Repositories\AbstractCampaignLogMeta;

class SendCampaign extends AbstractNewsmanConnect
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
     * Send campaign via Newsman.
     *
     * @return array
     */
    public function send()
    {
        try {

            $list_id = $this->get_email_campaign_list_id($this->email_campaign_id);

            $create_campaign = $this->newsmanInstance()->apiRequest(
                'newsletter.create.json',
                'POST',
                apply_filters('mailoptin_newsman_campaign_settings', [
                    'list_id'          => $list_id,
                    'html'             => $this->content_html,
                    'text'             => $this->content_text,
                    'newsletter_props' => [
                        'encoding'=>'utf-8',
                        'subject' => $this->campaign_subject
                    ]
                ],
                    $this->email_campaign_id
                )
            );

            if (is_numeric($create_campaign)) {

                $this->newsmanInstance()->apiRequest(
                    'newsletter.confirm.json',
                    'POST',
                    ['newsletter_id' => $create_campaign]
                );

                AbstractCampaignLogMeta::add_campaignlog_meta($this->campaign_log_id, 'newsman_campaign_id', $create_campaign);

                // if we get here, campaign was sent because no exception was thrown by sendEmailCampaign().
                return parent::ajax_success();
            }

            $err = __('Unexpected error. Please try again', 'mailoptin');
            self::save_campaign_error_log($err, $this->campaign_log_id, $this->email_campaign_id);

            return parent::ajax_failure($err);

        } catch (\Exception $e) {
            self::save_campaign_error_log($e->getMessage(), $this->campaign_log_id, $this->email_campaign_id);

            return parent::ajax_failure($e->getMessage());
        }
    }
}