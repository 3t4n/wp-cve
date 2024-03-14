<?php

namespace WP_Rplg_Google_Reviews\Includes;

class Feed_Serializer {

    public function __construct() {
        add_action('admin_post_' . Post_Types::FEED_POST_TYPE . '_save', array($this, 'feed_save'), 30);
    }

    public function feed_save() {

        $raw_data_array = $_POST[Post_Types::FEED_POST_TYPE];

        $post_id = $this->save($raw_data_array['post_id'], $raw_data_array['title'], $raw_data_array['content']);

        // NOT: $referer = empty(wp_get_referer()) ? $raw_data_array['current_url'] : wp_get_referer();
        // COZ: Fatal error: Can't use function return value in write context in .../includes/class-feed-serializer.php on line ...
        $referer = wp_get_referer();
        $referer = empty($referer) ? sanitize_text_field(wp_unslash($raw_data_array['current_url'])) : wp_get_referer();

        wp_safe_redirect(
            add_query_arg(array(
                Post_Types::FEED_POST_TYPE . '_id' => $post_id,
            ), $referer)
        );
        exit;
    }

    public function save($post_id, $title, $content) {

        if (!current_user_can('manage_options')) {
            die('The account you\'re logged in to doesn\'t have permission to access this page.');
        }

        check_admin_referer('grw_wpnonce', 'grw_nonce');

        $post_id = wp_insert_post(array(
            'ID'           => sanitize_text_field(wp_unslash($post_id)),
            'post_title'   => sanitize_text_field(wp_unslash($title)),
            'post_content' => sanitize_text_field(wp_unslash($content)),
            'post_type'    => Post_Types::FEED_POST_TYPE,
            'post_status'  => 'publish',
        ));
        return $post_id;
    }

}
