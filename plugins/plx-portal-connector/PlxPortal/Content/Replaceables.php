<?php

namespace PlxPortal\Content;

use PlxPortal\Content\ContentCpt;

class Replaceables
{
    const FIELD_NAME = '_plx_portal_web_content_replacements';
    const INPUT_PREFIX = '_plx_portal_web_content_replacement_';

    public function __construct()
    {
        add_action('save_post', array($this, 'store'), 99, 3);
    }

    public function store($post_id, $post)
    {
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }

        if ($post->post_type === ContentCpt::POST_TYPE) {
            $key_prefix = '_plx_portal_web_content_replacement_';

            $replacement_value_inputs = array_filter($_POST, function ($key) use ($key_prefix) {
                return strpos($key, $key_prefix) === 0;
            }, ARRAY_FILTER_USE_KEY);

            $replacements = [];

            foreach ($replacement_value_inputs as $key => $value) {
                $key_no_prefix = str_replace($key_prefix, '', $key);
                $replacements[$key_no_prefix] = $value;
            }

            if (count($replacements)) {
                $json = json_encode($replacements);
                $data = sanitize_text_field($json);

                update_post_meta($post_id, self::FIELD_NAME, $data);
            }
        }
    }

    public static function get($post_id): array
    {
        $metadata = get_post_meta($post_id, Self::FIELD_NAME);
        return $metadata ? json_decode($metadata[0], true) : [];
    }
}
