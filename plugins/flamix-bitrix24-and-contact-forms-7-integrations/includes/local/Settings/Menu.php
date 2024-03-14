<?php

namespace FlamixLocal\CF7\Settings;

trait Menu
{
    protected static string $code;
    protected static string $dir;

    public static function registerMenu(): void
    {
        static::$code = FLAMIX_BITRIX24_CF7_CODE;
        static::$dir = FLAMIX_BITRIX24_CF7_DIR;

        add_action('admin_menu', [self::class, 'add_menu']);
        add_filter('plugin_action_links_' . static::$code . '/' . static::$code . '.php', [self::class, 'add_link_to_plugin_widget']);
    }

    /**
     * Register settings page in menu.
     */
    public static function add_menu(): void
    {
        // Menu in Setting
        add_options_page(static::PLUGIN_TITLE, static::PLUGIN_NAME, 'administrator', __FILE__, [self::class, 'include_setting_page']);
    }

    /**
     * Include page.
     */
    public static function include_setting_page()
    {
        include_once static::$dir . '/resources/views/index.php';
    }

    /**
     * Add link to Setting Page and Install Module Landing.
     * @param array $links
     * @return array
     */
    public static function add_link_to_plugin_widget(array $links): array
    {
        $url = esc_url(add_query_arg(
            'page',
            static::$code . '/includes/local/Settings/Menu.php',
            get_admin_url() . 'options-general.php'
        ));

        $settings_link = '<a href="' . $url . '">' . __('Settings') . '</a>';
        $plugin_link = '<a target="_blank" href="' . static::PLUGIN_URL . '">' . __('Bitrix24 Plugin', static::$code) . '</a>';

        array_push($links, $settings_link, $plugin_link);
        return $links;
    }
}