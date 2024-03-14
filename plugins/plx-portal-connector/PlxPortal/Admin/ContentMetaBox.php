<?php

namespace PlxPortal\Admin;

use PlxPortal\Content\ContentCpt;

class ContentMetaBox extends MetaBox
{
    public function __construct()
    {
        parent::__construct(
            'plx_portal_content_connector_content',
            'Access Token',
            ContentCpt::POST_TYPE,
            'normal',
            'high',
            function () {
                global $post;
                return $post->post_status !== 'auto-draft' && $post->post_date_gmt !== '0000-00-00 00:00:00';
            }
        );
    }

    public function render()
    {
        global $post;

        $html = '';

        if ($post->post_title && $post->post_content) {
            $html .= '<h1>' . $post->post_title . '</h1>';
            $html .= '<hr/>';
            $html .= $post->post_content;
        }

        echo $html;
    }
}
