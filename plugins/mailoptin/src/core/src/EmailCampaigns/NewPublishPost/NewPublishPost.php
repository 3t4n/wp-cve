<?php

namespace MailOptin\Core\EmailCampaigns\NewPublishPost;

use MailOptin\Core\Connections\ConnectionFactory;
use MailOptin\Core\EmailCampaigns\AbstractTriggers;
use MailOptin\Core\EmailCampaigns\Misc;
use MailOptin\Core\Repositories\AbstractCampaignLogMeta;
use MailOptin\Core\Repositories\EmailCampaignRepository as ER;
use WP_Post;
use function MailOptin\Core\moVarObj;

class NewPublishPost extends AbstractTriggers
{
    public function __construct()
    {
        parent::__construct();

        // new way post 5.6
        if (function_exists('wp_after_insert_post')) {
            // new hook added in 5.6 triggered after post is published and all post meta data saved.
            add_action('wp_after_insert_post', [$this, 'wp_after_insert_post'], 1, 4);
        }

        // get called first before save_post and wp_after_insert_post. perfect for saving the previous post status
        add_action('transition_post_status', function ($new_status, $old_status, WP_Post $post) {

            global $mo_old_post_status;

            if (isset($post->ID)) {
                $mo_old_post_status[$post->ID] = $old_status;
            }

            // fix incompatibility with backupbuddy making post content empty.
            if (class_exists('\pb_backupbuddy') && method_exists('\pb_backupbuddy', 'remove_action')) {
                \pb_backupbuddy::remove_action(array('save_post', 'save_post_iterate_edits_since_last'));
            }

        }, 1, 3);

        // old way pre 5.6
        if ( ! function_exists('wp_after_insert_post')) {

            add_action('save_post', function ($post_id, WP_Post $post) {

                if ((defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) || wp_is_post_revision($post) || wp_is_post_autosave($post)) {
                    return;
                }

                global $mo_old_post_status;

                $this->new_publish_post($post->post_status, $mo_old_post_status[$post_id], $post);

            }, 999999999, 2);
        }

        // deprecated because it is triggered before all meta data are saved.
        // add_action('transition_post_status', array($this, 'new_publish_post'), 1, 3);

        add_action('mailoptin_send_scheduled_email_campaign', array($this, 'send_scheduled_email_campaign'), 10, 2);

        // Gravity Forms post creation compat
        if (apply_filters('mailoptin_gform_advancedpostcreation_compatibility', false) && class_exists('\GFForms')) {

            remove_action('wp_after_insert_post', [$this, 'wp_after_insert_post'], 1);

            add_action('gform_advancedpostcreation_post_after_creation', function ($post_id) {
                $post = get_post($post_id);
                $this->new_publish_post($post->post_status, '', $post);
            });
        }
    }

    public function wp_after_insert_post($post_id, WP_Post $post, $update, $post_before)
    {
        $old_status = moVarObj($post_before, 'post_status');

        $this->new_publish_post($post->post_status, $old_status, $post);
    }

    /**
     * Send scheduled newsletter.
     *
     * @param int $email_campaign_id
     * @param int $campaign_id
     */
    public function send_scheduled_email_campaign($email_campaign_id, $campaign_id)
    {
        // self::send_campaign()automatically update campaign status when processed or failed.
        $this->send_campaign($email_campaign_id, $campaign_id);
    }

    /**
     * Get time email campaign is set to go out.
     *
     * @param int $email_campaign_id
     *
     * @return string
     */
    public function schedule_time($email_campaign_id)
    {
        $schedule_digit = $this->schedule_digit($email_campaign_id);
        $schedule_type  = $this->schedule_type($email_campaign_id);
        if (empty($schedule_digit) || empty($schedule_type)) return false;

        return $schedule_digit . $schedule_type;
    }

    /**
     * @param string $new_status New post status.
     * @param string $old_status Old post status.
     * @param WP_Post $post Post object.
     */
    public function new_publish_post($new_status, $old_status, $post)
    {
        if ($new_status == 'publish' && $old_status != 'publish') {

            if (get_post_meta($post->ID, '_mo_disable_npp', true) == 'yes') return;

            $new_publish_post_campaigns = ER::get_by_email_campaign_type(ER::NEW_PUBLISH_POST);

            foreach ($new_publish_post_campaigns as $npp_campaign) {
                $email_campaign_id = absint($npp_campaign['id']);

                if (ER::is_campaign_active($email_campaign_id) === false) continue;

                if ( ! apply_filters('mo_new_publish_post_loop_check', true, $post, $email_campaign_id)) continue;

                $custom_post_type = ER::get_merged_customizer_value($email_campaign_id, 'custom_post_type');

                $post_type_support = ['post'];

                if ($custom_post_type != 'post') {
                    $post_type_support = [$custom_post_type];
                }

                $post_type_support = apply_filters('mo_new_publish_post_post_types_support', $post_type_support, $email_campaign_id);

                if ( ! in_array($post->post_type, $post_type_support)) continue;

                $npp_post_authors = ER::get_merged_customizer_value($email_campaign_id, 'post_authors');

                if ( ! empty($npp_post_authors)) {
                    if ( ! in_array($post->post_author, $npp_post_authors)) continue;
                }

                $custom_post_type_settings = ER::get_merged_customizer_value($email_campaign_id, 'custom_post_type_settings');

                if ( ! empty($custom_post_type_settings)) {
                    $custom_post_type_settings = json_decode($custom_post_type_settings, true);

                    if (is_array($custom_post_type_settings)) {
                        foreach ($custom_post_type_settings as $taxonomy => $npp_terms) {
                            if (taxonomy_exists($taxonomy) && ! empty($npp_terms)) {

                                $npp_terms = array_map('absint', $npp_terms);

                                $post_terms       = [];
                                $post_terms_array = wp_get_object_terms($post->ID, $taxonomy, ['fields' => 'ids']);

                                if (is_array($post_terms_array) && ! empty($post_terms_array)) {
                                    $post_terms = array_map('absint', $post_terms_array);
                                }

                                // do not check if $post_terms is empty because if no term is on the post, wp_get_object_terms return empty array
                                // so we can use the empty to check against if NPP requires certain term(s)
                                if (is_array($npp_terms) && ! empty($npp_terms)) {
                                    $result = array_intersect($post_terms, $npp_terms);
                                    if (empty($result)) continue 2;
                                }
                            }
                        }
                    }
                }

                $npp_categories  = ER::get_merged_customizer_value($email_campaign_id, 'post_categories');
                $npp_tags        = ER::get_merged_customizer_value($email_campaign_id, 'post_tags');
                $post_categories = wp_get_post_categories($post->ID, ['fields' => 'ids']);
                $post_tags       = wp_get_post_tags($post->ID, ['fields' => 'ids']);

                // do not check if $post_categories is empty because if no category is on the post, wp_get_post_categories return empty array
                // so we can use the empty to check against if NPP requires certain category(s)
                if (is_array($npp_categories) && ! empty($npp_categories)) {
                    // use intersect to check if categories match.
                    $result = array_intersect($post_categories, $npp_categories);
                    if (empty($result)) continue;
                }

                if (is_array($npp_tags) && ! empty($npp_tags)) {
                    // use intersect to check if categories match.
                    $result = array_intersect($post_tags, $npp_tags);
                    if (empty($result)) continue;
                }

                do_action('mo_new_publish_post_before_send', $post);

                $send_immediately_active = $this->send_immediately($email_campaign_id);
                $email_subject           = Misc::parse_email_subject(ER::get_merged_customizer_value($email_campaign_id, 'email_campaign_subject'));

                $content_html = (new Templatify($email_campaign_id, $post))->forge();

                $campaign_id = $this->save_campaign_log(
                    $email_campaign_id,
                    self::format_campaign_subject($email_subject, $post),
                    $content_html
                );

                AbstractCampaignLogMeta::add_campaignlog_meta($campaign_id, 'new_publish_post_id', $post->ID);

                if ($send_immediately_active) {
                    $this->send_campaign($email_campaign_id, $campaign_id);
                } else {

                    if ( ! $this->schedule_time($email_campaign_id)) continue;

                    // convert schedule time to timestamp.
                    $schedule_time_timestamp = \MailOptin\Core\strtotime_utc($this->schedule_time($email_campaign_id));

                    $response = wp_schedule_single_event(
                        $schedule_time_timestamp,
                        'mailoptin_send_scheduled_email_campaign',
                        [$email_campaign_id, $campaign_id]
                    );

                    // wp_schedule_single_event() return false if event wasn't scheduled.
                    if (false !== $response) {
                        $this->update_campaign_status($campaign_id, 'queued', $schedule_time_timestamp);
                    }
                }
            }
        }
    }

    /**
     * Does the actual campaign sending.
     *
     * @param int $email_campaign_id
     * @param int $campaign_log_id
     */
    public function send_campaign($email_campaign_id, $campaign_log_id)
    {
        do_action('mo_new_publish_post_before_send_campaign', $email_campaign_id, $campaign_log_id);

        $campaign = $this->CampaignLogRepository->getById($campaign_log_id);

        if ( ! $campaign) return;

        $connection_service = $this->connection_service($email_campaign_id);

        $connection_instance = ConnectionFactory::make($connection_service);

        if ( ! $connection_service) return;

        $response = $connection_instance->send_newsletter(
            $email_campaign_id,
            $campaign_log_id,
            $campaign->title,
            $connection_instance->replace_placeholder_tags($campaign->content_html, 'html'),
            $connection_instance->replace_placeholder_tags($campaign->content_text, 'text')
        );

        if (isset($response['success']) && (true === $response['success'])) {
            $this->update_campaign_status($campaign_log_id, 'processed');
        } else {
            $this->update_campaign_status($campaign_log_id, 'failed');
        }
    }

    /**
     * Replace any placeholder in email subject to correct value.
     *
     * @param string $email_subject
     * @param \stdClass|WP_Post $data_source
     *
     * @return mixed
     */
    public static function format_campaign_subject($email_subject, $data_source)
    {
        $search  = ['{{title}}'];
        $replace = [$data_source->post_title];

        return do_shortcode(str_replace($search, $replace, $email_subject));
    }

    /**
     * Singleton.
     *
     * @return NewPublishPost
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