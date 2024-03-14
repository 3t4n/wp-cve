<?php

namespace PlxPortal\Admin;

use PlxPortal\Content\ContentCpt;
use PlxPortal\Content\AccessToken;
use PlxPortal\Content\Shortcode;

class InformationMetaBox extends MetaBox
{
    public function __construct()
    {
        parent::__construct(
            'plx_portal_content_connector_information_side',
            'Information',
            ContentCpt::POST_TYPE,
            'side',
            'low',
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

        if ($post->ID) {
            $shortcode = "[" . Shortcode::SHORTCODE .  " id='" . $post->ID . "' heading='true']";
            $html .= '<label class="post-attributes-label" for="plx_portal_shortcode">Shortcode</label>';
            $html .= '<input name="plx_portal_shortcode" type="text" style="width: 100%;" value="' .  $shortcode . '" disabled>';
            $html .= '<br><br>';
        }

        if ($token = AccessToken::get($post->ID)) {
            $html .= '<label class="post-attributes-label" for="' .  AccessToken::FIELD_NAME . '">Access Token</label>';
            $html .= '<input name="' .  AccessToken::FIELD_NAME . '" type="text" style="width: 100%;" value="' . $token . '" disabled>';
        }

        echo $html;
    }
}
