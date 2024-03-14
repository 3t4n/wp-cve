<?php

namespace SuperbRecentPosts\Shortcodes;

use SuperbRecentPosts\Shortcodes\LatestPosts;

class PostsShortcodeController
{

    const SHORTCODE_LATEST_POSTS = 'spb-latest-posts';
    const SHORTCODE_LATEST_POSTS_CSS = 'spb-recent-posts-latest-posts-sc';

    public function __construct()
    {
        add_shortcode(self::SHORTCODE_LATEST_POSTS, array($this, 'LatestPosts'));
    }

    public function LatestPosts($atts)
    {
        wp_enqueue_style(self::SHORTCODE_LATEST_POSTS_CSS);
        $atts = shortcode_atts(array(
            'amount' => 3
        ), $atts, self::SHORTCODE_LATEST_POSTS);

        $args = array(
            'ignore_sticky_posts' => true,
            'posts_per_page' => intval($atts['amount'])
        );

        ob_start();
        new LatestPosts($args);
        return ob_get_clean();
    }
}
