<?php
/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       http://presstigers.com
 * @since      1.0.0
 * 
 * @package    Simple_Owl_Carousel
 * @subpackage Simple_Owl_Carousel/includes
 * @author     PressTigers <support@presstigers.com>
 */
class Simple_Owl_Carousel_i18n {

    /**
     * Load the plugin text domain for translation.
     *
     * @since   1.0.0
     */
    public function load_plugin_textdomain() {

        load_plugin_textdomain(
                'simple-owl-carousel', false, dirname(dirname(plugin_basename(__FILE__))) . '/languages/'
        );
    }

}