<?php
/**
 * Plugin Name: WP Simple Booking Calendar
 * Plugin URI: https://www.wpsimplebookingcalendar.com/
 * Description: The availability calendar for your needs.
 * Version: 2.0.10
 * Author: Veribo, Roland Murg
 * Author URI: https://www.wpsimplebookingcalendar.com/
 * Text Domain: wp-simple-booking-calendar
 * License: GPL2
 *
 * == Copyright ==
 * Copyright 2018 WP Simple Booking Calendar (www.wpsimplebookingcalendar.com)
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA 02110-1301 USA
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Main plugin class
 *
 */
class WP_Simple_Booking_Calendar
{

    /**
     * The current instance of the object
     *
     * @access private
     * @var    WP_Simple_Booking_Calendar
     *
     */
    private static $instance;

    /**
     * A list with the objects that handle database requests
     *
     * @access public
     * @var    array
     *
     */
    public $db = array();

    /**
     * A list with the objects that handle submenu pages
     *
     * @access public
     * @var    array
     *
     */
    public $submenu_pages = array();

    /**
     * Constructor
     *
     */
    public function __construct()
    {

        // Defining constants
        define('WPSBC_VERSION', '2.0.10');
        define('WPSBC_FILE', __FILE__);
        define('WPSBC_BASENAME', plugin_basename(__FILE__));
        define('WPSBC_PLUGIN_DIR', plugin_dir_path(__FILE__));
        define('WPSBC_PLUGIN_DIR_URL', plugin_dir_url(__FILE__));

        $this->include_files();
        $this->load_db_layer();

        define('WPSBC_TRANSLATION_TEXTDOMAIN', 'wp-simple-booking-calendar');

        // Check if just updated
        add_action('plugins_loaded', array($this, 'update_check'), 20);

        // Load the textdomain and the translation folders
        add_action('plugins_loaded', array($this, 'load_text_domain'), 30);

        // Update the database tables
        add_action('wpsbc_update_check', array($this, 'update_database_tables'));

        // Add and remove main plugin page
        add_action('admin_menu', array($this, 'add_main_menu_page'), 10);
        add_action('admin_menu', array($this, 'remove_main_menu_page'), 11);

        // Add submenu pages
        add_action('wp_loaded', array($this, 'load_admin_submenu_pages'), 11);

        // Admin scripts
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_scripts'));

        // Front-end scripts
        add_action('wp_enqueue_scripts', array($this, 'enqueue_front_end_scripts'));

        // Remove plugin query args from the URL
        add_filter('removable_query_args', array($this, 'removable_query_args'));

        // Add body class for WP versions greater than 5.3
        add_filter( 'admin_body_class', array($this, 'admin_body_class') );

        // Add a 5 star review call to action to admin footer text
        add_filter('admin_footer_text', array($this, 'admin_footer_text'));

        register_activation_hook(__FILE__, array($this, 'set_cron_jobs'));
        register_deactivation_hook(__FILE__, array($this, 'unset_cron_jobs'));

        /**
         * Plugin initialized
         *
         */
        do_action('wpsbc_initialized');

    }

    /**
     * Returns an instance of the plugin object
     *
     * @return WP_Simple_Booking_Calendar
     *
     */
    public static function instance()
    {

        if (!isset(self::$instance) && !(self::$instance instanceof WP_Simple_Booking_Calendar)) {
            self::$instance = new WP_Simple_Booking_Calendar;
        }

        return self::$instance;

    }

    /**
     * Add the main menu page
     *
     */
    public function add_main_menu_page()
    {

        add_menu_page('WP Simple Booking Calendar', 'WP Simple<br /> Booking Calendar', apply_filters('wpsbc_menu_page_capability', 'manage_options'), 'wp-simple-booking-calendar', '', 'dashicons-calendar-alt');

    }

    /**
     * Remove the main menu page as we will rely only on submenu pages
     *
     */
    public function remove_main_menu_page()
    {

        remove_submenu_page('wp-simple-booking-calendar', 'wp-simple-booking-calendar');

    }

    /**
     * Checks to see if the current version of the plugin matches the version
     * saved in the database
     *
     * @return void
     *
     */
    public function update_check()
    {

        $db_version = get_option('wpsbc_version', '');
        $do_update = false;

        // If current version number differs from saved version number
        if ($db_version != WPSBC_VERSION) {

            $do_update = true;

            // Update the version number in the db
            update_option('wpsbc_version', WPSBC_VERSION);

            // Add first activation time
            if (get_option('wpsbc_first_activation', '') == '') {
                update_option('wpsbc_first_activation', time());
            }

        }

        if ($do_update) {

            // Hook for fresh update
            do_action('wpsbc_update_check', $db_version);

            // Trigger set cron jobs
            $this->set_cron_jobs();

        }

    }

    /**
     * Creates and updates the database tables
     *
     * @return void
     *
     */
    public function update_database_tables()
    {

        foreach ($this->db as $db_class) {

            $db_class->create_table();

        }

    }

    /**
     * Loads plugin text domain
     *
     */
    public function load_text_domain()
    {

        $locale = apply_filters( 'plugin_locale', get_locale(), WPSBC_TRANSLATION_TEXTDOMAIN );

        // Search for Translation in /wp-content/languages/plugin/
        if (file_exists(trailingslashit( WP_LANG_DIR ) . 'plugins' . WPSBC_TRANSLATION_TEXTDOMAIN . '-' . $locale . '.mo')) {
            load_plugin_textdomain(WPSBC_TRANSLATION_TEXTDOMAIN, false, trailingslashit( WP_LANG_DIR ));
        }
        // Search for Translation in /wp-content/languages/
        elseif (file_exists(trailingslashit( WP_LANG_DIR ) . WPSBC_TRANSLATION_TEXTDOMAIN . '-' . $locale . '.mo')) {
            load_textdomain(WPSBC_TRANSLATION_TEXTDOMAIN, trailingslashit( WP_LANG_DIR ) . WPSBC_TRANSLATION_TEXTDOMAIN . '-' . $locale . '.mo');
        // Search for Translation in /wp-content/plugins/wp-simple-booking-calendar-premium/languages/
        } else {
            load_plugin_textdomain(WPSBC_TRANSLATION_TEXTDOMAIN, false, plugin_basename(dirname(__FILE__)) . '/languages');
        }

    }

    /**
     * Sets an action hook for modules to add custom schedules
     *
     */
    public function set_cron_jobs()
    {

        do_action('wpsbc_set_cron_jobs');

    }

    /**
     * Sets an action hook for modules to remove custom schedules
     *
     */
    public function unset_cron_jobs()
    {

        do_action('wpsbc_unset_cron_jobs');

    }

    /**
     * Include files
     *
     * @return void
     *
     */
    public function include_files()
    {

        /**
         * Include abstract classes
         *
         */
        $abstracts = scandir(WPSBC_PLUGIN_DIR . 'includes/abstracts');

        foreach ($abstracts as $abstract) {

            if (false === strpos($abstract, '.php')) {
                continue;
            }

            include WPSBC_PLUGIN_DIR . 'includes/abstracts/' . $abstract;

        }

        /**
         * Include all functions.php files from all plugin folders
         *
         */
        $this->_recursively_include_files(WPSBC_PLUGIN_DIR . 'includes');

        /**
         * Helper hook to include files early
         *
         */
        do_action('wpsbc_include_files');

    }

    /**
     * Recursively includes all functions.php files from the given directory path
     *
     * @param string $dir_path
     *
     */
    protected function _recursively_include_files($dir_path)
    {

        $folders = array_filter(glob($dir_path . '/*'), 'is_dir');

        foreach ($folders as $folder_path) {

            if (file_exists($folder_path . '/functions.php')) {
                include $folder_path . '/functions.php';
            }

            $this->_recursively_include_files($folder_path);

        }

    }

    /**
     * Sets up all objects that handle database related requests and adds them to the
     * $db property of the app
     *
     */
    public function load_db_layer()
    {

        /**
         * Hook to register db class handlers
         * The array element should be 'class_slug' => 'class_name'
         *
         * @param array
         *
         */
        $db_classes = apply_filters('wpsbc_register_database_classes', array());

        if (empty($db_classes)) {
            return;
        }

        foreach ($db_classes as $db_class_slug => $db_class_name) {

            $this->db[$db_class_slug] = new $db_class_name;

        }

    }

    /**
     * Sets up all objects that handle submenu pages and adds them to the
     * $submenu_pages property of the app
     *
     */
    public function load_admin_submenu_pages()
    {

        /**
         * Hook to register submenu_pages class handlers
         * The array element should be 'submenu_page_slug' => array( 'class_name' => array(), 'data' => array() )
         *
         * @param array
         *
         */
        $submenu_pages = apply_filters('wpsbc_register_submenu_page', array());

        if (empty($submenu_pages)) {
            return;
        }

        foreach ($submenu_pages as $submenu_page_slug => $submenu_page) {

            if (empty($submenu_page['data'])) {
                continue;
            }

            if (empty($submenu_page['data']['page_title']) || empty($submenu_page['data']['menu_title']) || empty($submenu_page['data']['capability']) || empty($submenu_page['data']['menu_slug'])) {
                continue;
            }

            $this->submenu_pages[$submenu_page['data']['menu_slug']] = new $submenu_page['class_name']($submenu_page['data']['page_title'], $submenu_page['data']['menu_title'], $submenu_page['data']['capability'], $submenu_page['data']['menu_slug']);

        }

    }

    /**
     * Enqueue the scripts and style for the admin area
     *
     */
    public function enqueue_admin_scripts($hook)
    {

        if (strpos($hook, 'wpsbc') !== false || in_array(get_post_type(), array('post', 'page'))) {

            if (!wp_script_is('chosen')) {

                wp_enqueue_script('wpsbc-chosen', WPSBC_PLUGIN_DIR_URL . 'assets/libs/chosen/chosen.jquery.min.js', array('jquery'), WPSBC_VERSION);
                wp_enqueue_style('wpsbc-chosen', WPSBC_PLUGIN_DIR_URL . 'assets/libs/chosen/chosen.css', array(), WPSBC_VERSION);

            }

        }

        if (strpos($hook, 'wpsbc') !== false) {

            $settings = get_option('wpsbc_settings', array());

            // Edit calendar scripts
            wp_register_script('wpsbc-script-edit-calendar', WPSBC_PLUGIN_DIR_URL . 'assets/js/script-admin-edit-calendar.js', array('jquery', 'jquery-ui-sortable', 'wp-color-picker', 'jquery-ui-datepicker'), WPSBC_VERSION);
            wp_localize_script('wpsbc-script-edit-calendar', 'wpsbc_plugin_settings', $settings);
            wp_enqueue_script('wpsbc-script-edit-calendar');

            // Color picker
            wp_enqueue_style('jquery-style', WPSBC_PLUGIN_DIR_URL . 'assets/css/jquery-ui.css', array(), WPSBC_VERSION);
            wp_enqueue_style('wp-color-picker');


        }

        if (!empty($_GET['page']) && $_GET['page'] == 'wpsbc-upgrader') {

            wp_register_script('wpsbc-script-upgrader', WPSBC_PLUGIN_DIR_URL . 'assets/js/script-admin-upgrader.js', array('jquery'), WPSBC_VERSION);
            wp_enqueue_script('wpsbc-script-upgrader');

        }

        if (!empty($_GET['page']) && $_GET['page'] == 'wpsbc-settings') {

            wp_register_script('wpsbc-script-uninstaller', WPSBC_PLUGIN_DIR_URL . 'assets/js/script-admin-uninstaller.js', array('jquery'), WPSBC_VERSION);
            wp_enqueue_script('wpsbc-script-uninstaller');

        }

        // Plugin styles
        wp_register_style('wpsbc-admin-style', WPSBC_PLUGIN_DIR_URL . 'assets/css/style-admin.css', array(), WPSBC_VERSION);
        wp_enqueue_style('wpsbc-admin-style');

        // Plugin script
        wp_register_script('wpsbc-admin-script', WPSBC_PLUGIN_DIR_URL . 'assets/js/script-admin.js', array('jquery', 'jquery-ui-sortable', 'wp-color-picker', 'jquery-ui-datepicker'), WPSBC_VERSION);
        wp_enqueue_script('wpsbc-admin-script');

        // Plugin styles from the front-end. Needed for the actual calendar
        wp_register_style('wpsbc-front-end-style', WPSBC_PLUGIN_DIR_URL . 'assets/css/style-front-end.min.css', array(), WPSBC_VERSION);
        wp_enqueue_style('wpsbc-front-end-style');

        /**
         * Hook to enqueue scripts immediately after the plugin's scripts
         *
         */
        do_action('wpsbc_enqueue_admin_scripts');

    }

    /**
     * Enqueue the scripts and style for the front-end part
     *
     */
    public function enqueue_front_end_scripts()
    {

        // Plugin styles
        wp_register_style('wpsbc-style', WPSBC_PLUGIN_DIR_URL . 'assets/css/style-front-end.min.css', array(), WPSBC_VERSION);
        wp_enqueue_style('wpsbc-style');

		// Datepicker
		wp_enqueue_style('dashicons');
		
        // Plugin script
        wp_register_script('wpsbc-script', WPSBC_PLUGIN_DIR_URL . 'assets/js/script-front-end.min.js', array('jquery', 'jquery-ui-datepicker'), WPSBC_VERSION, true);
        wp_localize_script('wpsbc-script', 'wpsbc', array(
            'ajax_url' => admin_url('admin-ajax.php')
        ));
        wp_enqueue_script('wpsbc-script');

        /**
         * Hook to enqueue scripts immediately after the plugin's scripts
         *
         */
        do_action('wpsbc_enqueue_front_end_scripts');

    }

    /**
     * Removes the query variables from the URL upon page load
     *
     */
    public function removable_query_args($args = array())
    {

        $args[] = 'wpsbc_message';

        return $args;

    }

    /**
     * Add custom class to the <body> tag in WP Admin
     *
     * @param string $text
     *
     */
    public function admin_body_class($classes){
        if ( version_compare( get_bloginfo('version'), '5.3', '>=' ) ) {
	        $classes .= ' wpsbc-greater-5-3';
        }
        return $classes;
    }

    /**
     * Replace admin footer text with a rate plugin message
     *
     * @param string $text
     *
     */
    public function admin_footer_text($text)
    {

        if (isset($_GET['page']) && strpos($_GET['page'], 'wpsbc') !== false) {
            return sprintf(__('If you enjoy using <strong>WP Simple Booking Calendar</strong>, please <a href="%s" target="_blank">leave us a ★★★★★ rating</a>. Big thank you for this!', 'wp-simple-booking-calendar'), 'https://wordpress.org/support/plugin/wp-simple-booking-calendar/reviews/?rate=5#new-post');
        }

        return $text;

    }

}

/**
 * Returns the WP Simple Booking Calendar instanced object
 *
 */
function wp_simple_booking_calendar()
{

    return WP_Simple_Booking_Calendar::instance();

}

// Let's get the party started
wp_simple_booking_calendar();
