<?php

namespace MailOptin\Core\EmailCampaigns\PostsEmailDigest;

use MailOptin\Core\EmailCampaigns\AbstractTemplate as ParentAbstractTemplate;
use MailOptin\Core\EmailCampaigns\TemplateTrait;
use WP_Post;

abstract class AbstractTemplate extends ParentAbstractTemplate
{
    use TemplateTrait;

    public $posts;

    public $email_campaign_id;

    public function __construct($email_campaign_id, $posts)
    {
        $this->posts = $posts;

        $this->email_campaign_id = $email_campaign_id;

        parent::__construct($email_campaign_id);
    }

    /**
     * HTML structure for single post item
     *
     * @return mixed
     */
    abstract function single_post_item();

    /**
     * Eg a Divider
     *
     * @return mixed
     */
    abstract function delimiter();

    public function parsed_post_list()
    {
        $delimiter = $this->delimiter();

        ob_start();
        $posts_count = count($this->posts);
        /**
         * @var int $index
         * @var WP_Post $post
         */
        foreach ($this->posts as $index => $post) {
            // index starts at 0. so we increment by one.
            $index++;

            $search = apply_filters('mo_email_campaign_ped_search_args', [
                '{{post.title}}',
                '{{post.content}}',
                '{{post.feature.image}}',
                '{{post.feature.image.alt}}',
                '{{post.url}}',
                '{{post.meta}}'
            ], $post, $this->email_campaign_id, $this);

            $replace = apply_filters('mo_email_campaign_ped_replace_args', [
                apply_filters('mo_posts_email_digest_post_title', $this->post_title($post), $post, $this->email_campaign_id),
                apply_filters('mo_posts_email_digest_post_content', $this->post_content($post), $post, $this->email_campaign_id),
                apply_filters('mo_posts_email_digest_post_feature_image', $this->feature_image($post), $post, $this->email_campaign_id),
                apply_filters('mo_posts_email_digest_post_feature_image_alt', $this->feature_image_alt($post), $post, $this->email_campaign_id),
                apply_filters('mo_posts_email_digest_post_url', $this->post_url($post), $post, $this->email_campaign_id),
                apply_filters('mo_posts_email_digest_post_meta', $this->post_meta($post), $post, $this->email_campaign_id)
            ], $post, $this->email_campaign_id, $this);

            echo apply_filters(
                'mo_email_campaign_ped_single_post_item',
                str_replace($search, $replace, $this->single_post_item()),
                $post, $this->email_campaign_id, $this
            );

            if ( ! empty($delimiter) && ($index % $posts_count) > 0) echo $delimiter;
        }

        return ob_get_clean();
    }
}