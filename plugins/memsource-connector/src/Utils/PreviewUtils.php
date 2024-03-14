<?php

namespace Memsource\Utils;

class PreviewUtils
{
    /**
     * Register preview funcionality.
     */
    public static function register()
    {
        if (!is_admin()) {
            add_filter('pre_get_posts', [self::class, 'showPreview']);
        }
    }

    /**
     * Call self::setPostPublished when accessing a post with memsource token.
     *
     * @param \WP_Query $query
     * @return \WP_Query
     */
    public static function showPreview($query)
    {
        if (AuthUtils::getTokenFromRequest() && $query->is_main_query() && $query->is_preview() && $query->is_singular()) {
            add_filter('posts_results', [self::class, 'setPostPublished'], 10, 2);
        }

        return $query;
    }

    /**
     * Make post visible when using a valid post.
     *
     * @param  \WP_Post[] $posts
     * @return \WP_Post[]
     */
    public static function setPostPublished($posts)
    {
        remove_filter('posts_results', [self::class, 'setPostPublished'], 10);

        if (!empty($posts) && AuthUtils::validateTokenInRequest()) {
            $posts[0]->post_status = 'publish';
            add_filter('comments_open', '__return_false');
            add_filter('pings_open', '__return_false');
        }

        return $posts;
    }
}
