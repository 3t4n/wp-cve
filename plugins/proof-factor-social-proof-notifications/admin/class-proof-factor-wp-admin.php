<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://prooffactor.com
 * @since      1.0.0
 *
 * @package    Proof_Factor_WP
 * @subpackage Proof_Factor_WP/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Proof_Factor_WP
 * @subpackage Proof_Factor_WP/admin
 * @author     Proof Factor LLC <enea@prooffactor.com>
 */
class Proof_Factor_WP_Admin
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
     * @param      string $plugin_name The name of this plugin.
     * @param      string $version The version of this plugin.
     */
    public function __construct($plugin_name, $version)
    {

        $this->plugin_name = $plugin_name;
        $this->version = $version;

    }

    /**
     * Register the stylesheets for the admin area.
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

        wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/proof-factor-wp-admin.css', array(), $this->version, 'all');

    }

    /**
     * Register the JavaScript for the admin area.
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

        wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/proof-factor-wp-admin.js', array('jquery'), $this->version, false);

    }

    public function admin_notice_html()
    {

        $options = get_option($this->plugin_name);
        $proof_account_id = $options['account_id'];
        $actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

        if (stripos($actual_link, 'page=proof-factor-wp') == false && empty($proof_account_id)) {
            ?>
            <div class="notice notice-error is-dismissible">
                <p class="ps-error">Proof Factor is not configured! <a href="admin.php?page=proof-factor-wp">Click here</a>
                </p>
            </div>
            <?php
        }
    }

    public function add_plugin_admin_menu()
    {

        /*
         * Add a settings page for this plugin to the Settings menu.
         *
         * NOTE:  Alternative menu locations are available via WordPress administration menu functions.
         *
         *        Administration Menus: http://codex.wordpress.org/Administration_Menus
         *
         */
        add_options_page('Proof Factor Settings & Setup', 'Proof Factor Settings', 'manage_options', $this->plugin_name, array($this, 'display_plugin_setup_page'));
    }

    /**
     * Add settings action link to the plugins page.
     *
     * @since    1.0.0
     */

    public function add_action_links($links)
    {
        /*
        *  Documentation : https://codex.wordpress.org/Plugin_API/Filter_Reference/plugin_action_links_(plugin_file_name)
        */
        $settings_link = array(
            '<a href="' . admin_url('options-general.php?page=' . $this->plugin_name) . '">' . __('Settings', $this->plugin_name) . '</a>',
        );
        return array_merge($settings_link, $links);

    }

    /**
     * Render the settings page for this plugin.
     *
     * @since    1.0.0
     */

    public function display_plugin_setup_page()
    {
        include_once('partials/proof-factor-wp-admin-display.php');
    }

    public function validate($input)
    {
        return $input;
    }


    public function options_update()
    {
        register_setting($this->plugin_name, $this->plugin_name, array($this, 'validate'));
    }

    function post_activate_redirect() {
        $redirect_key = $this->plugin_name . '_do_activation_redirect';
        if (get_option($redirect_key, false)) {
            delete_option($redirect_key);
            if(!isset($_GET['activate-multi'])) {
                exit( wp_redirect(admin_url('options-general.php?page=' . $this->plugin_name)) );
            }

        }
    }

    function added_options($option_name, $new_value) {
        $payload = [
            'url' => home_url(),
            'email' => get_option('admin_email'),
            'blog_name' => get_option('blogname'),
            'options' => $new_value,
            'option_name' => $option_name
        ];
        Proof_Factor_WP_Helper::remote_json_post("https://api.prooffactor.com/v1/partners/wordpress/updated_settings", $payload);
    }

    function updated_options($old_value, $new_value, $option_name)
    {
        $payload = [
            'url' => home_url(),
            'email' => get_option('admin_email'),
            'blog_name' => get_option('blogname'),
            'options' => $new_value,
            'option_name' => $option_name
        ];
        Proof_Factor_WP_Helper::remote_json_post("https://api.prooffactor.com/v1/partners/wordpress/updated_settings", $payload);
    }
}
