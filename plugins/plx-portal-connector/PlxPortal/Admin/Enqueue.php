<?php

namespace PlxPortal\Admin;

class Enqueue
{
    public function __construct()
    {
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_styles'));
    }

    public function enqueue_admin_styles()
    {
        wp_enqueue_style('plx_portal_admin_styles', plugins_url('assets/style.css', PLX_PORTAL_PLUGIN), array(), '2.0.0', 'all');
    }
}
