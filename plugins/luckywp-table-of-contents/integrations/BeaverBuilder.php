<?php

namespace luckywp\tableOfContents\integrations;

use luckywp\tableOfContents\core\base\BaseObject;
use luckywp\tableOfContents\core\Core;

class BeaverBuilder extends BaseObject
{

    protected $do = false;

    public function init()
    {
        if (defined('FL_THEME_BUILDER_VERSION')) {
            add_action('fl_theme_builder_before_render_content', function () {
                $this->do = true;
            });

            add_filter('fl_builder_before_render_shortcodes', function ($content) {
                return $this->do ? Core::$plugin->onTheContentTrue($content) : $content;
            }, 1);

            add_filter('lwptoc_need_processing_headings', function ($need) {
                return $this->do ? false : $need;
            }, 10000);

            add_filter('fl_builder_after_render_shortcodes', function ($content) {
                if ($this->do) {
                    $this->do = false;
                    $content = Core::$plugin->shortcode->theContent($content);
                    return Core::$plugin->onTheContentFalse($content);
                }
                return $content;
            }, 10000);
        }
    }
}
