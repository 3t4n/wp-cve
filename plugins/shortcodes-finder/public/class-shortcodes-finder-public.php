<?php
/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @since      1.0.0
 * @package    shortcodes-finder
 * @subpackage shortcodes-finder/public
 * @author     Scribit <wordpress@scribit.it>
 */
class Shortcodes_Finder_Public {

    /**
     * The ID of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $plugin_name    The ID of this plugin.
     */
    private $plugin_name;

    /**
     * The version of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $version    The current version of this plugin.
     */
    private $version;

    /**
     * Initialize the class and set its properties.
     *
     * @since    1.0.0
     * @param      string    $plugin_name       The name of the plugin.
     * @param      string    $version    The version of this plugin.
     */
    public function __construct($plugin_name, $version) {
        $this->plugin_name = $plugin_name;
        $this->version = $version;
    }

    /**
     * Register the stylesheets for the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function enqueue_styles() {

        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in Shortcodes_Finder_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Shortcodes_Finder_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */

        wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/shortcodes-finder-public.css', array(), $this->version, 'all');
    }

    /**
     * Register the JavaScript for the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function enqueue_scripts() {

        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in Shortcodes_Finder_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Shortcodes_Finder_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */

        wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/shortcodes-finder-public.js', array( 'jquery' ), $this->version, false);
    }

    /**
     * Handle disabled shortcodes content
     *
     * @since    1.3.0
     */
    public function disabled_shortcodes_handle($atts) {
        return '';
    }

    /**
     * Provide a the_content filter handle used in unused shortcode disabling
     *
     * @since    1.3.0
     */
    public function disable_unused_shortcodes_handle($content) {
        /*// Suppress preg_replace_callback notices
        error_reporting(E_ALL & ~E_NOTICE);

        $pattern = '/'. sf_get_shortcode_unused_regex(false) .'/s';
        $content = preg_replace_callback($pattern, 'strip_shortcode_tag', $content);*/

        return sf_clear_content_from_shortcode_unused($content);
    }

    /**
     * Provide a the_content filter handle used for remove disabled shortcodes
     *
     * @since    1.3.0
     */
    public function remove_disabled_shortcodes_handle($content) {
        $disabled_shortcodes = get_option(SHORTCODES_FINDER_OPTION_DISABLED_SHORTCODES);
        if (is_array($disabled_shortcodes)) {
            foreach ($disabled_shortcodes as $disabled_shortcode) {
                remove_shortcode($disabled_shortcode);
                add_shortcode($disabled_shortcode, array($this, 'disabled_shortcodes_handle'));
            }
        }

        return $content;
    }
}