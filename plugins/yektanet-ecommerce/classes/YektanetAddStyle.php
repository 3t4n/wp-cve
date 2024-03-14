<?php

class YektanetAddStyle
{
    public function __construct()
    {
        add_action('admin_enqueue_scripts', function () {
            wp_enqueue_style('yn-admin-styles', plugin_dir_url(__DIR__) . '/assets/css/styles.css', [], '1.1.4');
        });
    }
}