<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       wpautosave@gmail.com
 * @since      1.0.0
 *
 * @package    Wp_Autosave
 * @subpackage Wp_Autosave/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Wp_Autosave
 * @subpackage Wp_Autosave/admin
 * @author     wp-autosave team <wpautosave@gmail.com>
 */
class Wp_Autosave_Admin {

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
     * @param      string    $plugin_name       The name of this plugin.
     * @param      string    $version    The version of this plugin.
     */
    public function __construct( $plugin_name, $version ) {

        $this->plugin_name = $plugin_name;
        $this->version = $version;

    }

    /**
     * Register the stylesheets for the admin area.
     *
     * @since    1.0.0
     */
    public function enqueue_styles() {

        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in Wp_Autosave_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Wp_Autosave_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */

        wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/wp-autosave-admin.css', array(), $this->version, 'all' );

    }

    /**
     * Register the JavaScript for the admin area.
     *
     * @since    1.0.0
     */
    public function enqueue_scripts() {

        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in Wp_Autosave_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Wp_Autosave_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */

        wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/wp-autosave-admin.js', array( 'jquery' ), $this->version, false );

    }

    /**
     * Register the administration menu for this plugin into the WordPress Dashboard menu.
     *
     * @since    1.0.0
     */

    public function add_plugin_admin_menu() {

        /*
         * Add a settings page for this plugin to the Settings menu.
         *
         * NOTE:  Alternative menu locations are available via WordPress administration menu functions.
         *
         *        Administration Menus: http://codex.wordpress.org/Administration_Menus
         *
         */
        add_options_page( 'WP-autosave options', 'WP-autosave', 'manage_options', $this->plugin_name, array($this, 'display_plugin_setup_page')
        );
    }

    /**
     * Add settings action link to the plugins page.
     *
     * @since    1.0.0
     */

    public function add_action_links( $links ) {
        /*
        *  Documentation : https://codex.wordpress.org/Plugin_API/Filter_Reference/plugin_action_links_(plugin_file_name)
        */
       $settings_link = array(
        '<a href="' . admin_url( 'options-general.php?page=' . $this->plugin_name ) . '">' . __('Settings', $this->plugin_name) . '</a>',
       );
       return array_merge(  $settings_link, $links );

    }
    
    /**
     * Render the settings page for this plugin.
     *
     * @since    1.0.0
     */

    public function display_plugin_setup_page() {
        include_once( 'partials/wp-autosave-admin-display.php' );
    }

    public function options_update() {
       register_setting( $this->plugin_name, $this->plugin_name, array( $this, 'validate' ) );
    }

    /**
     * Check if input data is interval.
     *
     * @since    1.0.0
     */

    public function is_interval( $interval ) {
        if ( empty( $interval ) || ( strlen( trim( $interval ) ) > 4 ) || ( !( is_numeric( $interval ) ) ) ) {
            return false;
        }
        if ( is_numeric( $interval ) && ( $interval <= 0 ) ) {
            return false;
        }
        return true;
    }

    /**
     * Validate and accept input data.
     *
     * @since    1.0.0
     */

    public function validate( $input ) {       
        $valid = array();
        $valid['time_mark'] = ( isset( $input['time_mark'] ) && !empty( $input['time_mark'] ) ) ? 1 : 0;
        $valid['type_save'] = ( isset( $input['type_save'] ) && !empty( $input['type_save'] ) ) ? 1 : 0;
        if ( isset( $input['interval'] ) && $this->is_interval( $input['interval'] ) ) {
            $valid['interval'] = $input['interval'];
        }
        /* else $valid['interval'] will take default value */
        return $valid;
    }

}
