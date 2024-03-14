<?php

namespace AOP\App;

use AOP\App\Database\DB;
use AOP\Lib\Illuminate\Support\Collection;

class Plugin
{
    const VERSION  = '1.0.0';
    const NAME     = 'admin-options-pages';
    const _NAME    = 'admin_options_pages';
    const TITLE    = 'Admin Options Pages';
    const NAME_ABB = 'aop';
    const PREFIX_  = 'aop_';
    const PREFIX   = 'aop-';
    const FILENAME = 'admin-options-pages.php';
    const SITE_URL = 'https://adminoptionspages.com';
    const DOCS_URL = 'https://docs.adminoptionspages.com';

    private static $assetsPath = 'includes/assets/';

    /**
     * Assets url.
     *
     * @return string
     */
    public static function assetsUrl()
    {
        return plugin_dir_url(__DIR__) . 'includes/assets/';
    }

    /**
     * Assets dir.
     *
     * @return string
     */
    public static function assetsDir()
    {
        return plugin_dir_path(__DIR__) . self::$assetsPath;
    }

    /**
     * Base file path
     *
     * @return string
     */
    public static function baseFile()
    {
        return plugin_dir_path(__DIR__) . Plugin::FILENAME;
    }

    /**
     * Root path.
     *
     * @return string
     */
    public static function root()
    {
        return plugin_dir_path(__DIR__);
    }

    /**
     * Activate plugin actions.
     *
     * @return mixed
     */
    public static function activation()
    {
        return register_activation_hook(static::baseFile(), function () {
            DB::createPluginTable();
            static::addOptions();
        });
    }

    /**
     * Uninstall plugin actions.
     */
    public static function uninstall()
    {
        if (!static::pluginsPage()) {
            return;
        }

        register_uninstall_hook(static::baseFile(), [__CLASS__, 'uninstallHandler']);
    }

    /**
     * Uninstall handler.
     */
    public static function uninstallHandler()
    {
        DB::dropPluginTable();
        static::deleteOptions();
    }

    /**
     * Add options when activating the plugin.
     */
    public static function addOptions()
    {
        add_option(static::PREFIX_ . 'db_version', static::VERSION, '', 'no');
        add_option(static::PREFIX_ . 'option_names', [], '', 'no');
    }

    /**
     * Delete all the plugin owned options and created options on uninstall.
     */
    public static function deleteOptions()
    {
        Collection::make(get_option(static::PREFIX_ . 'option_names'))->each(function ($option) {
            delete_option($option);
        });

        delete_option(static::PREFIX_ . 'option_names');
        delete_option(static::PREFIX_ . 'db_version');
        delete_option(static::PREFIX_ . 'admin_menu_list');
        delete_option(static::PREFIX_ . 'setting_editpage_hidden');
    }

    /**
     * Check if current page is plugins.php.
     *
     * @return string
     */
    public static function pluginsPage()
    {
        return strpos($_SERVER['SCRIPT_NAME'], 'plugins.php');
    }

    /**
     * Runs update_option() function twice.
     * It makes it possible always to update the autoload column, even if the value is still the same.
     *
     * @param      $option
     * @param      $value
     * @param null $autoload
     */
    public static function updateOption($option, $value, $autoload = null)
    {
        update_option($option, uniqid(), $autoload);
        update_option($option, $value, $autoload);
    }
}
