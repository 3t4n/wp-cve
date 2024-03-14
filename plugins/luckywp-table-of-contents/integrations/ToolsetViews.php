<?php

namespace luckywp\tableOfContents\integrations;

use luckywp\tableOfContents\core\base\BaseObject;
use luckywp\tableOfContents\core\Core;

class ToolsetViews extends BaseObject
{

    protected $isToolseView = false;
    protected $hasShortcode = false;

    public function init()
    {
        add_action('wpv_before_shortcode_post_body', function () {
            $this->isToolseView = true;
        });
        add_filter('the_content', [$this, 'theContent'], 9998);
        add_action('lwptoc_disable_autoinsert', function ($value) {
            if ($this->isToolseView || $this->hasShortcode) {
                return true;
            }
            return $value;
        });
        add_action('wpv_after_shortcode_post_body', function () {
            $this->isToolseView = false;
        });
    }

    public function theContent($content)
    {
        $this->hasShortcode = Core::$plugin->shortcode->hasShorcode($content);
        return $content;
    }
}
