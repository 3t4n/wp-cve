<?php

namespace SuperbRecentPosts;

use SuperbRecentPosts\Widget\SRPWidget;
use SuperbRecentPosts\Shortcodes\PostsShortcodeController;

if (!defined('WPINC')) {
    die;
}

class SuperbRecentPosts
{
    private static $instance;

    public static function GetInstance()
    {
        if (!isset(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function __construct()
    {
        add_action('wp_enqueue_scripts', array($this, 'spbrposts_register_scripts'));
        add_action('widgets_init', array($this, 'spbrposts_load_widget'));
        new PostsShortcodeController();
    }

    public function spbrposts_load_widget()
    {
        register_widget(new SRPWidget());
    }

    public function spbrposts_register_scripts()
    {
        wp_register_style('spbrposts-frontend-css', SUPERBRECENTPOSTS_ASSETS_PATH . '/css/frontend.css', array(), SUPERBRECENTPOSTS_VERSION);
        wp_register_style(PostsShortcodeController::SHORTCODE_LATEST_POSTS_CSS, SUPERBRECENTPOSTS_ASSETS_PATH . '/css/latest-posts-shortcode.css', array(), SUPERBRECENTPOSTS_VERSION);
    }
}
