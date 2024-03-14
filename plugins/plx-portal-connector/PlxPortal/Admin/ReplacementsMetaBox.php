<?php

namespace PlxPortal\Admin;

use PlxPortal\Content\ContentCpt;
use PlxPortal\Content\Filters;
use PlxPortal\Content\Replaceables;

class ReplacementsMetaBox extends MetaBox
{
    public function __construct()
    {
        parent::__construct(
            'plx_portal_content_connector_side',
            'Content Replacements',
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

        if ($post->post_content) {
            $to_replace_arr = Filters::findReplaceables($post->post_content);
            $replacements = Replaceables::get($post->ID);

            if ($to_replace_arr) {
                foreach ($to_replace_arr as $to_replace) {
                    $to_replace_without_brackets = str_replace(array('[', ']', ' '), '', $to_replace);
                    $name_with_prefix = Replaceables::INPUT_PREFIX . $to_replace_without_brackets;
                    $label = ucwords(str_replace('-', ' ', $to_replace_without_brackets));
                    $value = isset($replacements[$to_replace_without_brackets]) ? $replacements[$to_replace_without_brackets] : $to_replace;

                    $html .= '<label class="post-attributes-label" for="' . $name_with_prefix . '">' . $label . '</label>';
                    $html .= '<input name="' . $name_with_prefix . '" type="text" style="width: 100%;" value="' . $value . '" required>';
                    $html .= '<br><br>';
                }
            }
        }

        $html .= '<input type="hidden" name="plx_meta_noncename" id="plx_meta_noncename" value="' . wp_create_nonce(PLX_PORTAL_PLUGIN_BASENAME) . '" />';

        echo $html;
    }
}
