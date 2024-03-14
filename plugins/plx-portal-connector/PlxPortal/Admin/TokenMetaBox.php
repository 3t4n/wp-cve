<?php

namespace PlxPortal\Admin;

use PlxPortal\Content\ContentCpt;

class TokenMetaBox extends MetaBox
{
    public function __construct()
    {
        parent::__construct(
            'plx_portal_content_connector_main',
            'Access Token',
            ContentCpt::POST_TYPE,
            'normal',
            'high',
            function () {
                global $post;
                return $post->post_status === 'auto-draft' && $post->post_date_gmt === '0000-00-00 00:00:00';
            }
        );
    }

    public function render()
    {
        $html = '<input name="_plx_portal_web_content_access_token" type="text" style="width: 340px;" value="" placeholder="Enter Token..." required>';
        $html .= '<p>You can find your Access Token by logging into the Portal and browsing to <a href="https://portal.plx.mk/content" target="_blank" title="view content">Content</a></p>';
        $html .= '<input type="hidden" name="plx_meta_noncename" id="plx_meta_noncename" value="' . wp_create_nonce(PLX_PORTAL_PLUGIN_BASENAME) . '" />';

        echo $html;
    }
}
