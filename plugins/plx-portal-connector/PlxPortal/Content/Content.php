<?php

namespace PlxPortal\Content;

use PlxPortal\Content\AccessToken;
use PlxPortal\Content\ContentCpt;

class Content
{
    public function __construct()
    {
        add_filter('wp_insert_post_data', array($this, 'store'), 2, 1000);
    }

    private static function requestContent($token)
    {
        $response = wp_remote_get('https://portal.plx.mk/api/content/' . $token);
        return is_wp_error($response) ? ['status' => 'error', 'data' => $response->errors] : json_decode(wp_remote_retrieve_body($response));
    }

    public function store($data, $postarr)
    {
        if (
            $data['post_type'] == ContentCpt::POST_TYPE
            && isset($_POST[AccessToken::FIELD_NAME])
        ) {
            $token = $_POST[AccessToken::FIELD_NAME];
            $request = $this->requestContent($token);

            if ($request->status === 'success') {
                $data['post_title'] = $request->data->title;
                $data['post_content'] = $request->data->body;

                return $data;
            } else {
                return [];
            }
        }

        return $data;
    }

    public static function update($post_id, $token)
    {
        $response = self::requestContent($token);

        if ($response->status === 'success') {;
            wp_update_post(array(
                'ID'           => $post_id,
                'post_title'   => $response->data->title,
                'post_content' => $response->data->body,
            ));
        }
    }
}
