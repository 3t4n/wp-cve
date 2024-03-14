<?php

namespace MailOptin\Core\Admin\PostListMenu;

// Exit if accessed directly

use MailOptin\Core\Repositories\EmailCampaignRepository;

if ( ! defined('ABSPATH')) {
    exit;
}

class PostPreview
{
    private $campaigns = null;

    public function __construct()
    {
        add_filter('post_row_actions', [$this, 'modify_list_row_actions'], 10, 2);
        add_action('in_admin_header', [$this, 'get_form_content']);
    }

    public function modify_list_row_actions($actions, $post)
    {
        if (count($this->get_campaigns()) > 0 && $post->post_status !== 'publish') {
            add_thickbox();
            $actions[] = sprintf(
                '<a name="%s" href="#TB_inline?width=500&height=200&inlineId=email-modal" class="thickbox" data-postID="%d">%s</a> ',
                sprintf(esc_html__('Send %s as a test email', 'mailoptin'), get_the_title($post->ID)),
                $post->ID,
                esc_html__('Send Test Email', 'mailoptin')
            );
        }

        return $actions;
    }

    public function get_form_content()
    {
        $campaignsHTML = '';
        foreach ($this->get_campaigns() as $campaign) {
            $campaignsHTML .= '<option value="' . esc_attr($campaign['id']) . '">' . esc_attr($campaign['name']) . '</option>';
        }
        echo '<div id="email-modal" style="display: none;">
            <form id="email-form" style="display: flex; flex-direction: column; justify-content: center; margin-left: 10%; margin-right: 10%;">
                <h2>' . esc_html__('Send Test Email', 'mailoptin') . '</h2>
                <div style="padding-bottom: 10px">
                    <label for="campaigns">' . esc_html__('Choose a campaign:', 'mailoptin') . '</label>
                    <select name="campaigns" id="campaigns" required>
                    ' . $campaignsHTML . '
                    </select>
                </div>
                <div style="padding-bottom: 10px">
                    <label for="email">' . esc_html__('Email Address:', 'mailoptin') . '</label>
                    <input type="email" id="email" name="email" required/>
                </div>
                <div>
                    <input class="button" id="email-preview" type="submit"/>
                </div>
                <div>
                    <span id="mailoptin-success" style="display:none;">' . esc_html__('Email sent. Go check your message.', 'mailoptin') . '</span>
                </div>
                <input id="mailoptin-send-test-email-nonce" type="hidden" value="' . wp_create_nonce('mailoptin-send-test-email-nonce') . '"/>
            </form>
        </div>';
    }

    public function get_campaigns()
    {
        if ( ! isset($this->campaigns)) {
            $email_campaign_ids = [];
            foreach (EmailCampaignRepository::get_email_campaign_ids() as $id) {
                $campaignSettings = EmailCampaignRepository::get_settings_by_id($id);
                if ( ! EmailCampaignRepository::is_campaign_active($id)) {
                    continue;
                }
                $campaign         = EmailCampaignRepository::get_email_campaign_by_id($id);
                $campaignPostType = $campaignSettings['custom_post_type'] ?? 'post';
                if ($campaign['campaign_type'] === EmailCampaignRepository::NEW_PUBLISH_POST && $campaignPostType === get_post_type()) {
                    $email_campaign_ids[] = ['id' => $id, 'name' => $campaign['name']];
                }
            }
            $this->campaigns = $email_campaign_ids;

            return $email_campaign_ids;
        }

        return $this->campaigns;
    }


    /**
     * @return PostPreview|null
     */
    public static function get_instance()
    {
        static $instance = null;

        if (is_null($instance)) {
            $instance = new self();
        }

        return $instance;
    }
}