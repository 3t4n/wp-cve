<?php

namespace App\Base;

use App\Utils\Helper;

class Enqueue extends Plugin
{
    /**
     * Register
     * @return void
     */
    public function register()
    {
        add_action('admin_enqueue_scripts', [$this, 'enqueueAdmin']);
        add_action('wp_enqueue_scripts', [$this, 'enqueueFront']);
    }

    /**
     * Enqueue scripts
     * @return void
     */
    public function enqueueAdmin()
    {
        wp_enqueue_style('nsio-admin.css', self::$plugin_url . 'assets/nsio-admin.css');
    }


    /**
     * Enqueue frontend scripts
     * @return void
     */
    public function enqueueFront()
    {
        if (!Helper::isAuthGranted()) {
            return;
        }

        $sources = json_decode(get_option('nextsale_script_tags'));

        if ($sources[0] != null || $sources[0] != '') {
            $explode = explode('&', $sources[0]);
            $sources = [$explode[0] . "&v=" . microtime()];
            update_option('nextsale_script_tags', json_encode($sources));
        } 

        if (!$sources || !is_array($sources)) {
            return;
        }

        wp_enqueue_script('nsio-loader.js', self::$plugin_url . 'assets/nsio-loader.js');

        wp_localize_script('nsio-loader.js', 'nsio_script', [
            'sources' => $sources
        ]);
    }
}
