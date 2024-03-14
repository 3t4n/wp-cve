<?php

namespace luckywp\termDescriptionRichText\front;

use luckywp\termDescriptionRichText\core\base\BaseObject;

class Front extends BaseObject
{

    public function init()
    {
        remove_filter('pre_term_description', 'wp_filter_kses');
        remove_filter('term_description', 'convert_chars');
        add_filter('term_description', 'convert_smilies', 20);
        add_filter('term_description', 'prepend_attachment');

        if (function_exists('wp_filter_content_tags')) {
            // WordPress 5.5+
            add_filter('term_description', 'wp_filter_content_tags');
        } else {
            // WordPress <5.5
            add_filter('term_description', 'wp_make_content_images_responsive');
        }

        global $wp_embed;
        if ($wp_embed) {
            add_filter('term_description', array($wp_embed, 'run_shortcode'), 8);
            add_filter('term_description', array($wp_embed, 'autoembed'), 8);
        }
    }
}
