<?php

namespace MailOptin\RegisteredUsersConnect;

use MailOptin\Core\Connections\AbstractConnect;
use MailOptin\Core\Logging\CampaignLogRepository;
use MailOptin\Core\PluginSettings\Settings;
use MailOptin\Core\Repositories\AbstractCampaignLogMeta;
use WP_Background_Process;


class WP_Mail_BG_Process extends WP_Background_Process
{
    /**
     * @var string
     */
    protected $action = 'mo_wp_mail_bg_process';

    /**
     * Initiate new background process.
     */
    public function __construct()
    {
        // Uses unique prefix per blog so each blog has separate queue.
        $this->prefix = 'wp_' . get_current_blog_id();

        parent::__construct();
    }

    /**
     * HTML email content type.
     *
     * @return string
     */
    public function html_content_type()
    {
        return 'text/html';
    }

    /**
     * Add content_type email filter.
     */
    public function add_html_content_type()
    {
        add_filter('wp_mail_content_type', array($this, 'html_content_type'));
    }

    /**
     * Remove content_type email filter.
     */
    public function remove_html_content_type()
    {
        remove_filter('wp_mail_content_type', array($this, 'html_content_type'));
    }

    /**
     * Filters the email address to send from.
     *
     * @param string|int $campaign_log_id
     */
    public function wp_mail_from_filter($campaign_log_id = '')
    {
        add_filter('wp_mail_from', function ($val) use ($campaign_log_id) {

            $from_email = Settings::instance()->from_email();

            $from_email = ! empty($from_email) ? $from_email : $val;

            if (apply_filters('mo_wp_mail_bg_process_enable_post_author_from_email', false)) {

                if ( ! empty($campaign_log_id)) {

                    $post_id = AbstractCampaignLogMeta::get_campaignlog_meta($campaign_log_id, 'new_publish_post_id', true);

                    if ( ! empty($post_id)) {
                        $post_author = get_post($post_id)->post_author;
                        $from_email  = get_user_by('id', $post_author)->user_email;
                    }
                }
            }

            return apply_filters('mo_wp_mail_bg_process_from_email', $from_email, $campaign_log_id);
        });
    }

    /**
     * Filters the name to associate with the “from” email address.
     *
     * @param string|int $campaign_log_id
     */
    public function wp_mail_from_name_filter($campaign_log_id = '')
    {
        add_filter('wp_mail_from_name', function ($val) use ($campaign_log_id) {
            $from_name = Settings::instance()->from_name();

            $from_name = ! empty($from_name) ? $from_name : $val;

            if (apply_filters('mo_wp_mail_bg_process_enable_post_author_from_name', false)) {

                if ( ! empty($campaign_log_id)) {

                    $post_id = AbstractCampaignLogMeta::get_campaignlog_meta($campaign_log_id, 'new_publish_post_id', true);

                    if ( ! empty($post_id)) {
                        $post_author = get_post($post_id)->post_author;
                        $from_name   = get_user_by('id', $post_author)->display_name;
                    }
                }
            }

            return apply_filters('mo_wp_mail_bg_process_from_name', $from_name, $campaign_log_id);
        });
    }

    /**
     * Add plain text message to email to send.
     *
     * @param string $plain_text_message
     */
    public function add_plain_text_message($plain_text_message)
    {
        add_action('phpmailer_init', function ($phpmailer) use ($plain_text_message) {
            $phpmailer->AltBody = $plain_text_message;
        });
    }

    public function wp_mail_error_log($email_address, $campaign_log_id, $email_campaign_id)
    {
        add_action('wp_mail_failed', function ($wp_error) use ($email_address, $campaign_log_id, $email_campaign_id) {
            $status = $wp_error->get_error_message();
            AbstractConnect::save_campaign_error_log(
                "Email address: $email_address; Note: $status",
                $campaign_log_id,
                $email_campaign_id
            );
        });
    }

    public function replace_user_merge_tags($content, $email_address)
    {
        $user = get_user_by('email', $email_address);

        if ( ! $user) return $content;

        $search = [
            '{{username}}',
            '{{useremail}}',
            '{{firstname}}',
            '{{lastname}}',
            '{{displayname}}',
            '{{websiteurl}}',
        ];

        $replace = [
            $user->user_login,
            $user->user_email,
            $user->first_name,
            $user->last_name,
            $user->display_name,
            $user->user_url
        ];

        return str_replace($search, $replace, $content);
    }

    /**
     * Task
     *
     * Override this method to perform any actions required on each
     * queue item. Return the modified item for further processing
     * in the next pass through. Or, return false to remove the
     * item from the queue.
     *
     * @param mixed $user_data object of user's username and email address.
     *
     * @return mixed
     */
    protected function task($user_data)
    {
        $email_address = $user_data->user_email;

        $unsubscribed_contacts = get_option('mo_wp_user_unsubscribers', []);
        if ( ! empty($unsubscribed_contacts)) {
            if (in_array(base64_encode($email_address), $unsubscribed_contacts)) return false;
        }

        $username          = $user_data->user_login;
        $email_campaign_id = $user_data->email_campaign_id;
        $campaign_log_id   = $user_data->campaign_log_id;

        $content_html = $this->replace_user_merge_tags(CampaignLogRepository::instance()->retrieveContentHtml($campaign_log_id), $email_address);
        $content_text = $this->replace_user_merge_tags(CampaignLogRepository::instance()->retrieveContentText($campaign_log_id), $email_address);

        $search = ['{{unsubscribe}}', '{{webversion}}'];

        $replace = [
            home_url('?mo_wp_user_unsubscribe=' . base64_encode($email_address)),
            home_url('?mo_view_web_version=' . $campaign_log_id)
        ];

        $content_text = str_replace($search, $replace, $content_text);
        $content_html = str_replace($search, $replace, $content_html);

        $subject = CampaignLogRepository::instance()->retrieveTitle($campaign_log_id);

        $this->wp_mail_from_filter($campaign_log_id);
        $this->wp_mail_from_name_filter($campaign_log_id);
        $this->add_plain_text_message($content_text);

        $this->add_html_content_type();

        $this->wp_mail_error_log($email_address, $campaign_log_id, $email_campaign_id);

        $response = wp_mail($email_address, $subject, $content_html); // send the newsletter.
        
        $this->remove_html_content_type();

        if ( ! $response) {

            $status = __('Email failed to be delivered', 'mailoptin');

            AbstractConnect::save_campaign_error_log(
                "Username: $username; Email address: $email_address; Note: $status",
                $campaign_log_id,
                $email_campaign_id
            );
        }

        return false;
    }

    /**
     * Save queue
     *
     * @return static
     */
    public function mo_save($campaign_log_id, $email_campaign_id)
    {
        $key = $this->generate_key();

        if ( ! empty($this->data)) {
            $update = update_site_option($key, $this->data);

            if ( ! $update) {
                AbstractConnect::save_campaign_error_log(
                    'Unable to save WP Mail BG process',
                    $campaign_log_id,
                    $email_campaign_id
                );
            }
        }

        return $this;
    }

    public function mo_dispatch($campaign_log_id, $email_campaign_id)
    {
        $dispatched = parent::dispatch();

        if (is_wp_error($dispatched)) {
            AbstractConnect::save_campaign_error_log(
                sprintf('Unable to dispatch WP Mail BG: %s', $dispatched->get_error_message()),
                $campaign_log_id,
                $email_campaign_id
            );
        }

        return $dispatched;
    }
}