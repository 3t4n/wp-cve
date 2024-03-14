<?php

defined('ABSPATH') or die();

class wl_companion_scripts
{
    public static function wl_companion_scripts_frontend()
    {
        wp_enqueue_script('wl-nineteen-script', WL_COMPANION_PLUGIN_URL . 'public/js/nineteen-custom.js');
    }
}
