<?php

namespace ShopWP;

if (!defined('ABSPATH')) {
    exit();
}

class I18N
{
    
    public function load_textdomain()
    {
        $load_plugin_textdomain = load_plugin_textdomain(
            'shopwp',
            false,
            dirname(dirname(plugin_basename(__FILE__))) .
                '/' .
                SHOPWP_LANGUAGES_FOLDER
        );
    }

    public function init()
    {
        $this->load_textdomain();
    }
}
