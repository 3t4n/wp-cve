<?php

namespace WunderAuto;

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 */
class I18n
{
    /**
     * @param Loader $loader
     *
     * @return void
     */
    public function register($loader)
    {
        $loader->addAction(
            'plugins_loaded',
            $this,
            'loadPluginTextdomain'
        );
    }

    /**
     * Load the plugin text domain for translation.
     *
     * @return void
     */
    public function loadPluginTextdomain()
    {
        load_plugin_textdomain(
            'wunderauto',
            false,
            dirname(dirname(plugin_basename(__FILE__))) . '/languages/'
        );
    }
}
