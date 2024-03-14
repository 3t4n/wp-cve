<?php

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Express_Checkout
 * @subpackage Express_Checkout/includes
 * @author     wpgateways <wpgateways@gmail.com>
 */
class Express_Checkout_i18n {

    /**
     * @since    1.0.0
     */
    public function load_plugin_textdomain() {
        load_plugin_textdomain(
                'express-checkout', false, dirname(dirname(plugin_basename(__FILE__))) . '/languages/'
        );
    }

}
