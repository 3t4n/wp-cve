<?php

namespace WP_Rplg_Google_Reviews\Includes;

class Feed_Deserializer {

    private $wp_query;

    public function __construct(\WP_Query $wp_query) {
        $this->wp_query = $wp_query;
    }

    public function get_feed($post_id, $args = array()) {
        $default_args = array(
            'post_type'      => Post_Types::FEED_POST_TYPE,
            'p'              => $post_id,
            'posts_per_page' => 1,
            'no_found_rows'  => true,
        );

        $args = wp_parse_args($args, $default_args);
        $this->wp_query->query($args);

        if (!$this->wp_query->have_posts()) {
            return false;
        }

        return $this->wp_query->posts[0];
    }

    public function get_all_feeds($args = array()) {
        $default_args = array(
            'post_type'      => Post_Types::FEED_POST_TYPE,
            'fields'         => array('ID', 'post_title', 'post_content'),
            'posts_per_page' => 300,
            'no_found_rows'  => true,
        );

        $args = wp_parse_args($args, $default_args);
        $this->wp_query->query($args);

        if (!$this->wp_query->have_posts()) {
            return false;
        }

        return $this->wp_query->posts;
    }

    public function get_all_feeds_short($args = array()) {
        $short = array();
        $feeds = $this->get_all_feeds($args);
        if (is_array($feeds) || is_object($feeds)) {
            foreach ($feeds as $feed) {
                $item = ['id' => $feed->ID, 'name' => $feed->post_title];
                array_push($short, $item);
            }
        }
        return $short;
    }

    public function get_feed_count($args = array()) {
        $default_args = array(
            'post_type'      => Post_Types::FEED_POST_TYPE,
            'posts_per_page' => -1,
            'no_found_rows'  => true,
        );

        $args = wp_parse_args($args, $default_args);
        $this->wp_query->query($args);

        if (!$this->wp_query->have_posts()) {
            return 0;
        }

        return count($this->wp_query->posts);
    }

}
