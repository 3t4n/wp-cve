<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://www.buymeacoffee.com
 * @since      1.0.0
 *
 * @package    Buy_Me_A_Coffee
 * @subpackage Buy_Me_A_Coffee/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Buy_Me_A_Coffee
 * @subpackage Buy_Me_A_Coffee/includes
 * @author     Buymeacoffee <hello@buymeacoffee.com>
 */
class Buy_Me_A_Coffee_i18n
{


    /**
     * Load the plugin text domain for translation.
     *
     * @since    1.0.0
     */
    public function load_plugin_textdomain()
    {

        load_plugin_textdomain(
            'buy-me-a-coffee',
            false,
            dirname(dirname(plugin_basename(__FILE__))) . '/languages/'
        );
    }
}
