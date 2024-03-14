<?php
if ( !defined('ABSPATH') ) {
    exit();
}

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 *
 * @package   WC_Swiss_Qr_Bill
 * @subpackage WC_Swiss_Qr_Bill/includes
 */
class WC_Swiss_Qr_Bill_i18n {

    /**
     * Load the plugin text domain for translation.
     *
     * @since    1.0.0
     */
    public static function load_plugin_textdomain() {

        load_plugin_textdomain(
            'swiss-qr-bill',
            false,
            dirname(dirname(plugin_basename(__FILE__))) . '/languages/'
        );

    }

}
