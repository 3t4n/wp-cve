<?php

namespace luckywp\termDescriptionRichText\plugin;

use luckywp\termDescriptionRichText\core\base\BasePlugin;

class Plugin extends BasePlugin
{

    private function pluginI18n()
    {
        __('Replaces plain-text editor for category, tag and custom taxonomy term description with the built-in WordPress WYSIWYG editor (TinyMCE).', 'lwptdr');
    }
}