<?php

namespace MailOptin\IContactConnect;

use MailOptin\Core\PluginSettings\Settings;
use MailOptin\Core\Repositories\AbstractCampaignLogMeta;

class SendCampaign extends AbstractIContactConnect
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

            $create_campaigns_args = apply_filters('mailoptin_icontact_create_campaign_args', [
                'name'                   => $campaign_title,
                'fromName'               => Settings::instance()->from_name(),
                'fromEmail'              => Settings::instance()->from_email(),
                'subscriptionManagement' => '0',
                'clickTrackMode'         => '1',
                'useAccountAddress'      => '1',
                'archiveByDefault'       => '1',
                'forwardToFriend'        => '0'
            ], $this->email_campaign_id, $this);

            $response = $this->icontact_instance()->make_request('campaigns', [$create_campaigns_args], 'post');

            if (isset($response['body']['campaigns'][0]['campaignId'])) {

                $campaign_id = $response['body']['campaigns'][0]['campaignId'];

                $create_message_args = apply_filters('mailoptin_icontact_create_message_args', [
                    'campaignId'  => $campaign_id,
                    'messageType' => 'normal',
                    'subject'     => $this->campaign_subject,
                    'htmlBody'    => $this->content_html,
                    'textBody'    => $this->content_text

                ], $this->email_campaign_id, $this);

                $response2 = $this->icontact_instance()->make_request('messages', [$create_message_args], 'post');

                if (isset($response2['body']['messages'][0]['messageId'])) {
                    $message_id = $response2['body']['messages'][0]['messageId'];

                    $send_email_args = apply_filters('mailoptin_icontact_send_email_args', [
                        'messageId'      => $message_id,
                        'includeListIds' => $list_id
                    ], $this->email_campaign_id, $this);

                    $response3 = $this->icontact_instance()->make_request('sends', [$send_email_args], 'post');

                    if (isset($response3['body']['sends'][0]['sendId'])) {
                        AbstractCampaignLogMeta::add_campaignlog_meta($this->campaign_log_id, 'icontact_campaign_id', $response3['body']['sends'][0]['sendId']);

                        return parent::ajax_success();
                    }
                }
            }

            $err = __('Unexpected error. Please try again', 'mailoptin');
            self::save_campaign_error_log($err, $this->campaign_log_id, $this->email_campaign_id);

            return parent::ajax_failure($err);

        } catch (\Exception $e) {

            self::save_campaign_error_log($e->getMessage(), $this->campaign_log_id, $this->email_campaign_id);

            return parent::ajax_failure(__('Unexpected error. Please try again', 'mailoptin'));
        }
    }
}