<?php

namespace PlxPortal\Content;

use PlxPortal\Content\ContentCpt;

class AccessToken
{
    const FIELD_NAME = '_plx_portal_web_content_access_token';

    public function __construct()
    {
        add_action('wp_insert_post', array($this, 'store'), 1, 3);
    }

    public function store($post_id, $post)
    {
        if ($post->post_type === ContentCpt::POST_TYPE) {
            if (isset($_POST[Self::FIELD_NAME])) {
                update_post_meta($post_id, Self::FIELD_NAME, sanitize_text_field($_POST[Self::FIELD_NAME]));
            }
        }
    }

    public static function get($post_id)
    {
        $metadata = get_post_meta($post_id, Self::FIELD_NAME);
        return $metadata ? $metadata[0] : null;
    }
}
