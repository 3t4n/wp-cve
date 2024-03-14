<?php

defined('ABSPATH') or die('No direct access');

class Pushly_Public
{
    public static function init()
    {
        $self = new self();

        add_action('wp_head', array(__CLASS__, 'insert_header'), 10);
        add_filter('script_loader_tag', array(__CLASS__, 'async_enqueue'), 10, 2);

        return $self;
    }

    public static function insert_header()
    {
        $settings = get_option('pushly_options');

        if (!empty($settings['pushly_domain_key'])) {
            wp_enqueue_script('pushly-sdk', 'https://cdn.p-n.io/pushly-sdk.min.js?domain_key=' . rawurlencode($settings['pushly_domain_key']), [], false, true);
            require_once PUSHLY_PLUGIN_PATH_ROOT . 'views/templates/sdk.php';
        }
    }

    public static function async_enqueue($tag, $handle)
    {
        if ('pushly-sdk' === $handle && false === strpos($tag, 'async')) {
            return str_replace('<script ', '<script async ', $tag);
        }

        return $tag;
    }
}
