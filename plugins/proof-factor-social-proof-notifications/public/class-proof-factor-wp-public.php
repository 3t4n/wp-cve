<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://prooffactor.com
 * @since      1.0.0
 *
 * @package    Proof_Factor_WP
 * @subpackage Proof_Factor_WP/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Proof_Factor_WP
 * @subpackage Proof_Factor_WP/public
 * @author     Proof Factor LLC <enea@prooffactor.com>
 */
class Proof_Factor_WP_Public
{

    /**
     * The ID of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string $plugin_name The ID of this plugin.
     */
    private $plugin_name;

    /**
     * The version of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string $version The current version of this plugin.
     */
    private $version;

    /**
     * Initialize the class and set its properties.
     *
     * @since    1.0.0
     * @param      string $plugin_name The name of the plugin.
     * @param      string $version The version of this plugin.
     */
    public function __construct($plugin_name, $version)
    {

        $this->plugin_name = $plugin_name;
        $this->version = $version;

    }

    /**
     * Register the stylesheets for the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function enqueue_styles()
    {

        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in Proof_Factor_WP_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Proof_Factor_WP_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */

        wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/proof-factor-wp-public.css', array(), $this->version, 'all');

    }

    /**
     * Register the JavaScript for the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function enqueue_scripts()
    {

        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in Proof_Factor_WP_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Proof_Factor_WP_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */

        $options = get_option($this->plugin_name);
        $proof_account_id = $options['account_id'];

        $blog_url = home_url();
        if (!empty($blog_url)) {
            $blog_url = urlencode($blog_url);
        }

        if (empty($proof_account_id) == false) {
            wp_enqueue_script("{$this->plugin_name}-widget-code", "https://api.prooffactor.com/v1/partners/wordpress/jsembed.js?account_id={$proof_account_id}&site_url={$blog_url}", array(), $this->version, false);
        }

    }

    public function embed_proof()
    {
        $options = get_option($this->plugin_name);

        $proof_account_id = $options['account_id'];

        if (empty($proof_account_id)) {
            return;
        }

        $remote_html_key = "{$this->plugin_name}_remote_plugin_html_{$proof_account_id}";
        $html = get_transient($remote_html_key);

        if (empty($html)) {
            $blog_url = home_url();
            if (!empty($blog_url)) {
                $blog_url = urlencode($blog_url);
            }
            $response = wp_remote_get("https://api.prooffactor.com/v1/partners/wordpress/embed?account_id={$proof_account_id}&site_url={$blog_url}");
            if (is_wp_error($response)) {
                return;
            }
            $data = wp_remote_retrieve_body($response);
            if (is_wp_error($data)) {
                return;
            }
            set_transient($remote_html_key, $data, 1 * HOUR_IN_SECONDS);
        }
        echo $html;
    }

}
