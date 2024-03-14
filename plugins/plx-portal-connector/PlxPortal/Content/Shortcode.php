<?php

namespace PlxPortal\Content;

use PlxPortal\Content\Filters;

class Shortcode
{
    const SHORTCODE = 'plxportal';
    public $post;

    public function __construct()
    {
        add_shortcode($this::SHORTCODE, array($this, 'shortcode'));
    }

    private function setPostContent()
    {
        $metadata = get_post_meta($this->post->ID, '_plx_portal_web_content_replacements');

        if ($metadata) {
            $replacements = json_decode($metadata[0], true);
            $to_replace = Filters::findReplaceables($this->post->post_content);

            $this->post->post_content = count($to_replace) && count($replacements) ? Filters::findAndReplace($this->post->post_content, $replacements) : $this->post->post_content;
        } else {
            $replacables = Filters::findReplaceables($this->post->post_content);
            $replacements = array();

            foreach ($replacables as $replacable) {
                $key = str_replace(array('[', ']'), '', $replacable);
                $replacements[$key] = $replacable;
            }

            $this->post->post_content = count($replacements) ? Filters::findAndReplace($this->post->post_content, $replacements) : $this->post->post_content;
        }
    }

    private function getHtml($show_heading)
    {
        $html = $show_heading === 'true' ? '<h1>' . $this->post->post_title . '</h1>' : '';
        $html .= $this->post->post_content;
        return $html;
    }

    public function shortcode($atts)
    {
        extract(shortcode_atts(array(
            'id' => null,
            'heading' => 'true',
        ), $atts));

        $this->post = get_post($id);

        if ($this->post) {
            $this->setPostContent();
            return $this->getHtml($heading);
        } else {
            return '';
        }
    }
}
