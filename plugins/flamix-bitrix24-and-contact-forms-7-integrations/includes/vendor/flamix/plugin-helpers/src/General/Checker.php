<?php

namespace Flamix\Plugin\General;

class Checker
{
    /**
     * @param string $name Checking param name, ex: PHP Version
     * @param callable|bool $callback
     * @param array $options What write when pass (first option) and failed (second option)
     * @return string
     */
    public static function params(string $name, $callback, array $options = [])
    {
        $result = is_bool($callback) ? $callback : (bool)$callback();

        if ($result) {
            $result = '<span style="color: #46b450;">' . ($options['0'] ?? 'Enable') . '</span>';
        } else {
            $result = '<span style="color: #dc3232;">' . ($options['1'] ?? 'Disabled') . '</span>';
        }

        return $name . ': ' . $result;
    }

    /**
     * Is plugin active.
     *
     * Some people download plugin for CF7, but don't install CF7
     *
     * @param string $plugin
     * @return bool
     */
    public static function isPluginActive(string $plugin)
    {
        $plugin = str_contains($plugin, '.php') ? $plugin : "{$plugin}/{$plugin}.php";

        $wordpress_plugins = (array)get_option('active_plugins', []);

        // Multisite
        if (MULTISITE) {
            $network_plugins = array_keys((array)get_site_option('active_sitewide_plugins', []));
            $wordpress_plugins = array_merge($wordpress_plugins, $network_plugins);
        }

        return in_array($plugin, $wordpress_plugins);
    }

    /**
     * When saving email - check.
     *
     * @param string $option
     * @return bool|string
     */
    public static function isEmail(string $option)
    {
        return filter_var($option, FILTER_VALIDATE_EMAIL) ? $option : false;
    }
}