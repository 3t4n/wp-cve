<?php

namespace luckywp\tableOfContents\integrations;

use luckywp\tableOfContents\core\base\BaseObject;
use luckywp\tableOfContents\core\Core;

class Oxygen extends BaseObject
{

    public function init()
    {
        add_action('ct_builder_start', function () {
            Core::$plugin->onTheContentTrue('');
        }, 1);
        add_action('ct_builder_start', function () {
            global $template_content;
            $template_content = Core::$plugin->shortcode->theContent($template_content);
            Core::$plugin->onTheContentFalse('');
        }, 9999);
    }
}
