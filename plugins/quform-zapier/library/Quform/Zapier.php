<?php

/**
 * @copyright Copyright (c) 2009-2020 ThemeCatcher (https://www.themecatcher.net)
 */
class Quform_Zapier
{
    /**
     * On plugin activation set the activated flag
     */
    public static function onActivation()
    {
        add_option('quform_zapier_activated', '1');
    }

    /**
     * Register services with the container
     *
     * @param Quform_Container $container
     */
    public static function containerSetup(Quform_Container $container)
    {
        new Quform_Zapier_Container($container);
    }

    /**
     * Bootstrap the plugin
     *
     * @param Quform_Container $container
     */
    public static function bootstrap(Quform_Container $container)
    {
        new Quform_Zapier_Dispatcher($container);
    }

    /**
     * Get the URL to the plugin folder
     *
     * @param   string  $path  Extra path to append to the URL
     * @return  string
     */
    public static function url($path = '')
    {
        return Quform::pathExtra(plugins_url(QUFORM_ZAPIER_NAME), $path);
    }

    /**
     * Get the URL to the plugin admin folder
     *
     * @param   string  $path  Extra path to append to the URL
     * @return  string
     */
    public static function adminUrl($path = '')
    {
        return Quform::pathExtra(self::url('admin'), $path);
    }

    /**
     * Get the classes for the given icon
     *
     * In Quform 2.13.0 the icon classes changed from 'fa' to 'qfb-icon', so we support both here.
     *
     * @param   string  $icon
     * @return  string
     */
    public static function icon($icon) {
        if (version_compare(QUFORM_VERSION, '2.13.0', '<')) {
            $icon = preg_replace('/qfb-icon qfb-icon-/', 'fa fa-', $icon);
        }

        return $icon;
    }
}
