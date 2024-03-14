<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @package    Dotdigital_WordPress
 */
namespace Dotdigital_WordPress\Includes;

class Dotdigital_WordPress_I18n
{
    /**
     * Load the plugin text domain for translation.
     */
    public function load_plugin_textdomain()
    {
        load_plugin_textdomain(DOTDIGITAL_WORDPRESS_PLUGIN_NAME, \false, \dirname(\dirname(plugin_basename(__FILE__))) . '/languages/');
    }
}
