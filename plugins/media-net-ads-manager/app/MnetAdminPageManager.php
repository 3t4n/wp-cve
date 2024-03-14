<?php

namespace Mnet;

class MnetAdminPageManager
{
    private static $hookSuffix = null;
    public static function createAdminPage()
    {
        self::$hookSuffix = \add_menu_page('Media.net ads manager', 'Media.net Ads Manager', 'manage_options', 'mnet-ad-manager', array('Mnet\MnetAdminPageManager', 'renderAdminPage'), \plugin_dir_url(__DIR__) . 'images/plugin-icon.png', '59.38');
    }

    public static function renderAdminPage()
    {
        if (\is_admin()) {
            \wp_enqueue_script('jquery');
            include __DIR__ . '/views/MnetAdConfiguration.php';
            include __DIR__ . '/views/adBlockHandler.php';
        }
    }

    public static function enqueueScripts($hookSuffix)
    {
        if (self::$hookSuffix === $hookSuffix) {
            self::enqueueMnetScripts();
        }
    }

    public static function enqueueMnetScripts()
    {
        \wp_enqueue_style(self::getPluginPrefix() . '_main_style', \plugin_dir_url(__DIR__) . mnet_normalize_chunks('/../dist/js/mnet_admin_styles.css'), array(), MNET_PLUGIN_VERSION);
        \wp_enqueue_style('material-icons', "https://fonts.googleapis.com/icon?family=Material+Icons|Material+Icons+Outlined");
    }

    public static function injectAdminGlobalStyles()
    {
        $globalStyle = <<<EOF
        <style>
            body {
                background-color: #f4f6fa;
            }
            * {
                margin: 0;
                padding: 0;
            }
            #loading-message {
                height: calc(50vh - 32px - 16px);
                width: 100%;
                display: flex;
                align-items: flex-end;
                justify-content: center;
            }
        </style>
EOF;
        echo $globalStyle;
    }

    public static function enqueueScriptsLate($hookSuffix)
    {
        if (self::$hookSuffix === $hookSuffix) {
            \wp_enqueue_script(self::getPluginPrefix() . '_global_script', \plugin_dir_url(__DIR__) . mnet_normalize_chunks('/dist/js/mnetGlobal.js'), array(), MNET_PLUGIN_VERSION);

            self::removeOtherPluginScripts();
            self::removeOtherPluginStyles();

            \remove_action('admin_print_scripts', 'print_emoji_detection_script');
            \remove_action('admin_print_styles', 'print_emoji_styles');
        }
    }

    public static function removeOtherPluginScripts()
    {
        // Removing these scripts from our plugin's admin page
        // Advanced Ads
        \wp_dequeue_script('advanced-ads-admin-global-script');
        \wp_dequeue_script('advanced-ads-admin-styles');

        // Ads For WP
        \wp_dequeue_script('ads-for-wp-admin-js');
        \wp_dequeue_script('ads-for-wp-admin-analytics-js');

        // Ad inserter
        \wp_dequeue_script('ai-admin-js-gen');
    }

    public static function removeOtherPluginStyles()
    {
        // Removing these styles from our plugin's admin page
        \wp_dequeue_style('advanced-ads-admin-styles');
        \wp_dequeue_style('ads-for-wp-admin');
        \wp_dequeue_style('ai-admin-gen');
    }

    public static function getPluginPrefix()
    {
        return 'mnet_' . preg_replace('/\./', '', MNET_PLUGIN_VERSION);
    }

    public static function getBuildPath()
    {
        $folder_path = 'dist/js';
        return \plugin_dir_url(__DIR__) .  $folder_path;
    }
}
