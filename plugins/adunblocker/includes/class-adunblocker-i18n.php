<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://digitalapps.com
 * @since      1.0.0
 *
 * @package    AdUnblocker
 * @subpackage AdUnblocker/includes
 */

class AdUnblocker_i18n {

    public function load_plugin_textdomain() {

        load_plugin_textdomain(
            'adunblocker',
            false,
            dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
        );

    }

}