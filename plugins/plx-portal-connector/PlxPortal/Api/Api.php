<?php

namespace PlxPortal\Api;

use PlxPortal\Content\Content;
use PlxPortal\Content\ContentCpt;
use PlxPortal\Content\AccessToken;
use WP_Query;

class Api
{
    const PORTAL_IP = '138.68.165.18';
    const ROUTE = 'plx-portal/v1';
    const SYNC_ROUTE_SLUG = 'sync';
    const SYNC_ROUTE = '/wp-json/' . self::ROUTE . '/' . self::SYNC_ROUTE_SLUG;

    public function __construct()
    {
        add_action('rest_api_init', [$this, 'registerEndPoint']);
    }

    public function registerEndPoint()
    {
        register_rest_route(self::ROUTE, self::SYNC_ROUTE_SLUG, array(
            'methods' => 'POST',
            'callback' => [$this, 'callback']
        ));
    }

    public function callback($request)
    {
        $body = $request->get_body();
        $data = json_decode($body);

        if (isset($data->token)) {
            $args = array(
                'post_type' => ContentCpt::POST_TYPE,
                'posts_per_page' => 1,
                'meta_query'  => array(
                    array(
                        'key' => AccessToken::FIELD_NAME,
                        'value' => $data->token
                    )
                )
            );

            $query =  new WP_Query($args);

            if ($query->have_posts()) {
                $post = $query->posts[0];
                Content::update($post->ID, $data->token);
            }
        }
    }
}
