<?php

namespace S2WPImporter\Plugins;

/**
 * Class Plugin
 *
 * @package S2WPImporter\Plugins
 */
class Plugin
{

    /**
     * @var string $slug The plugin slug to work with.
     */
    protected $slug;

    /**
     * @var array $active_plugins The slugs of active plugins.
     */
    protected $active_plugins = [];

    /**
     * @var null|callable
     */
    protected $isActiveCallback = null;

    /**
     * Plugin constructor.
     *
     * @param       $slug
     */
    public function __construct($slug)
    {
        $this->slug = $slug;
        $this->active_plugins = $this->getActivePlugins();
    }

    /**
     * Try to see if the plugin is active (maybe in mu-plugins)
     *
     * @param $callback
     *
     * @return mixed
     */
    public function setIsActiveCallback($callback)
    {
        $this->isActiveCallback = $callback;
    }

    /**
     * Check if the plugin is activated.
     *
     * @return bool
     */
    public function isActive()
    {
        $callback = $this->isActiveCallback;

        return in_array($this->slug, $this->active_plugins) || (is_callable($callback) && $callback());
    }

    /**
     * Check if the plugin is installed.
     *
     * @return bool
     */
    public function isInstalled()
    {
        $callback = $this->isActiveCallback;

        return is_dir(WP_PLUGIN_DIR . '/' . $this->slug) || (is_callable($callback) && $callback());
    }

    /**
     * Install the plugin.
     */
    public function install()
    {
        if (empty($this->slug)) {
            return __('ERROR: No plugin slug.', 'import-shopify-to-wp');
        }

        if (!current_user_can('install_plugins') || !current_user_can('activate_plugins')) {
            return __('ERROR: You lack permissions to install and/or activate plugins.', 'import-shopify-to-wp');
        }

        include_once(ABSPATH . 'wp-admin/includes/plugin-install.php');

        $api = plugins_api('plugin_information', ['slug' => $this->slug, 'fields' => ['sections' => false]]);

        if (is_wp_error($api)) {
            return sprintf(__('ERROR: Error fetching plugin information: %s', 'import-shopify-to-wp'), $api->get_error_message());
        }

        if (!class_exists('\Plugin_Upgrader', false)) {
            require_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';
        }

        $upgrader = new \Plugin_Upgrader(new UpgraderSkin([
            'nonce' => 'install-plugin_' . $this->slug,
            'plugin' => $this->slug,
            'api' => $api,
        ]));

        $install_result = $upgrader->install($api->download_link);

        if (!$install_result || is_wp_error($install_result)) {
            // $install_result can be false if the file system isn't writable.
            $error_message = __('Please ensure the file system is writable', 'import-shopify-to-wp');

            if (is_wp_error($install_result)) {
                $error_message = $install_result->get_error_message();
            }

            return sprintf(__('ERROR: Failed to install plugin: %s', 'import-shopify-to-wp'), $error_message);
        }

        return true;
    }

    /**
     * Activate the plugin.
     */
    public function activate()
    {
        if (empty($this->slug)) {
            return __('ERROR: No plugin slug.', 'import-shopify-to-wp');
        }

        if (!current_user_can('activate_plugins')) {
            return __('ERROR: You lack permissions to activate plugins.', 'import-shopify-to-wp');
        }

        $activate_result = activate_plugin($this->getFilePath(), '', false, true);

        if (is_wp_error($activate_result)) {
            return sprintf(__('ERROR: Failed to activate plugin: %s', 'import-shopify-to-wp'), $activate_result->get_error_message());
        }

        return true;
    }

    /**
     * Deactivate the plugin.
     */
    public function deactivate()
    {
        if (empty($this->slug)) {
            return __('ERROR: No plugin slug.', 'import-shopify-to-wp');
        }

        if (!current_user_can('activate_plugins')) {
            return __('ERROR: You lack permissions to deactivate plugins.', 'import-shopify-to-wp');
        }

        // Note: It's an array of plugins!
        deactivate_plugins([
            $this->getFilePath(),
        ]);

        return true;
    }

    /**
     * Get the path to main plugin file.
     *
     * @return string
     */
    public function getFilePath()
    {
        $php_files = glob(WP_PLUGIN_DIR . '/' . $this->slug . '/*.php');

        $the_file_path = false;
        foreach ($php_files as $file) {
            $plugin_data = get_plugin_data($file);

            if (!empty($plugin_data['Name'])) {
                $the_file_path = str_replace(WP_PLUGIN_DIR . '/', '', $file);
                break;
            }
        }

        return $the_file_path;
    }

    /**
     * Get all active plugins.
     *
     * @return array
     */
    public function getActivePlugins()
    {
        $active = (array)get_option('active_plugins', []);

        if (is_multisite()) {
            $sitewide = get_site_option('active_sitewide_plugins', []);
            $active = array_unique(array_merge($active, $sitewide));
        }

        $slugs = [];

        if (!empty($active)) {
            $slugs = array_map(
                function ($value) {
                    $plugin = explode('/', $value);

                    return $plugin[0];
                },
                $active
            );
        }

        return $slugs;
    }
}
