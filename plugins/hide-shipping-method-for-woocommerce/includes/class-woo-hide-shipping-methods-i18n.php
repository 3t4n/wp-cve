<?php
// If this file is called directly, abort.
if ( !defined( 'ABSPATH' ) ) {
    exit;
}
/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://www.thedotstore.com/
 * @since      1.0.0
 *
 * @package    Woo_Hide_Shipping_Methods
 * @subpackage Woo_Hide_Shipping_Methods/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Woo_Hide_Shipping_Methods
 * @subpackage Woo_Hide_Shipping_Methods/includes
 * @author     theDotstore <wordpress@multidots.in>
 */
class Woo_Hide_Shipping_Methods_i18n {

    /**
     * The domain specified for this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $domain    The domain identifier for this plugin.
     */
    private $domain;

    /**
     * Load the plugin text domain for translation.
     *
     * @since    1.0.0
     */
    public function load_plugin_textdomain() {

        $locale = apply_filters('plugin_locale', get_locale(), $this->domain);
        $mofile = $this->domain . '-' . $locale . '.mo';
        $path = WP_PLUGIN_DIR . '/' . trim($this->domain . '/languages', '/');
        load_textdomain($this->domain, $path . '/' . $mofile);
        $plugin_rel_path = apply_filters('woo_hide_shipping_methods_translation_file_rel_path', $this->domain . '/languages');
        load_plugin_textdomain($this->domain, false, $plugin_rel_path);
    }

    /**
     * Set the domain equal to that of the specified domain.
     *
     * @since    1.0.0
     * @param    string    $domain    The domain that represents the locale of this plugin.
     */
    public function set_domain($domain) {
        $this->domain = $domain;
    }

}