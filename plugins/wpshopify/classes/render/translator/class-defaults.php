<?php

namespace ShopWP\Render\Translator;

use ShopWP\Utils\Data;

if (!defined('ABSPATH')) {
    exit();
}

class Defaults
{
    public $plugin_settings;
    public $Render_Attributes;

    public function __construct($plugin_settings, $Render_Attributes)
    {
        $this->plugin_settings = $plugin_settings;
        $this->Render_Attributes = $Render_Attributes;
    }

    public function translator($attrs) {
        return array_replace([], $attrs);
    }

    public function all_attrs($attrs = [])
    {
        return apply_filters('shopwp_translator_default_settings', [
            'hello' => $this->Render_Attributes->attr(
                $attrs,
                'hello',
                false
            ),
        ]);
    }
}