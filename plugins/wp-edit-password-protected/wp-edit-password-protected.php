<?php
/*
 * @link              http://wpthemespace.com
 * @since             1.0.0
 * @package           Wp Edit Password Protected
 *
 * @wordpress-plugin
 * Plugin Name:       Wp Edit Password Protected
 * Plugin URI:        http://wpthemespace.com
 * Description:       Create member only page and change the message displayed of default wp Password Protected.
 * Version:           1.2.6
 * Author:            Noor alam
 * Author URI:        http://wpthemespace.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       wp-edit-password-protected
 * Domain Path:       /languages
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}


/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
final class wpEditPasswordProtected
{

    /**
     * Plugin Version
     *
     * @since 1.0.0
     *
     * @var string The plugin version.
     */
    const version = '1.2.6';

    /**
     * Minimum PHP Version
     *
     * @since 1.0.0
     *
     * @var string Minimum PHP version required to run the plugin.
     */
    const MINIMUM_PHP_VERSION = '5.6';


    /**
     * Instance
     *
     * @since 1.0.0
     *
     * @access private
     * @static
     *
     * @var wpEditPasswordProtected The single instance of the class.
     */
    private static $_instance = null;

    /**
     * Instance
     *
     * Ensures only one instance of the class is loaded or can be loaded.
     *
     * @since 1.0.0
     *
     * @access public
     * @static
     *
     * @return wpEditPasswordProtected An instance of the class.
     */
    public static function instance()
    {

        if (is_null(self::$_instance)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    /**
     * Constructor
     *
     * @since 1.0.0
     *
     * @access public
     */
    public function __construct()
    {

        $this->define_constants();
        $this->add_all_files();
        add_action('init', [$this, 'i18n']);
        add_action('plugins_loaded', [$this, 'init']);
    }


    public function define_constants()
    {
        define('WP_EDIT_PASS_VERSION', self::version);
        define('WP_EDIT_PASS_PATH', dirname(__file__));
        define('WP_EDIT_PASS_FILE', __FILE__);
        define('WP_EDIT_PASS_DIR', plugin_dir_path(__FILE__));
        define('WP_EDIT_PASS_URL', plugins_url('', WP_EDIT_PASS_FILE));
        define('WP_EDIT_PASS_ASSETS', WP_EDIT_PASS_URL . '/assets/');
    }

    public function add_all_files()
    {
        if (is_admin()) {
            // We are in admin mode
            require_once(WP_EDIT_PASS_PATH . '/admin/wp_edit_pass_options.php');
            require_once(WP_EDIT_PASS_PATH . '/admin/nt-class.php');
        }
        require_once(WP_EDIT_PASS_PATH . '/admin/pagetemplater.php');
        require_once(WP_EDIT_PASS_PATH . '/includes/wp_edit_pass_customize.php');

        /* Kirki added */
        require_once(WP_EDIT_PASS_PATH . '/admin/kirki/kirki.php');
        require_once(WP_EDIT_PASS_PATH . '/admin/kirki/password-protect-settings.php');
        require_once(WP_EDIT_PASS_PATH . '/admin/kirki/admin-page-setup.php');
    }



    /**
     * Load Textdomain
     *
     * Load plugin localization files.
     *
     * Fired by `init` action hook.
     *
     * @since 1.0.0
     *
     * @access public
     */
    public function i18n()
    {

        load_plugin_textdomain('wp-edit-password-protected');
    }

    /**
     * Initialize the plugin
     *
     * Load the plugin only after Elementor (and other plugins) are loaded.
     * Checks for basic plugin requirements, if one check fail don't continue,
     * if all check have passed load the files required to run the plugin.
     *
     * Fired by `plugins_loaded` action hook.
     *
     * @since 1.0.0
     *
     * @access public
     */
    public function init()
    {


        add_action('admin_enqueue_scripts', [$this, 'wp_edit_pass_admin_scripts']);
        add_action('wp_login_failed', [$this, 'my_front_end_login_fail']);
        add_action('wp_enqueue_scripts', [$this, 'wp_edit_pass_script']);
        add_action('customize_controls_enqueue_scripts', [$this, 'customize_preview_assets']);


        $this->appsero_init_tracker_wp_edit_password_protected();
    }

    function wp_edit_pass_script()
    {
        wp_enqueue_style('wpps-fonts', WP_EDIT_PASS_ASSETS . 'css/wpps-fonts.css', array(), WP_EDIT_PASS_VERSION, 'all');
        wp_enqueue_style('wppps-style', WP_EDIT_PASS_ASSETS . 'css/wppps-style.css', array(), WP_EDIT_PASS_VERSION, 'all');
    }

    function wp_edit_pass_admin_scripts()
    {
        global $pagenow;

        wp_enqueue_style('wpps-admin', WP_EDIT_PASS_ASSETS . 'css/admin.css', array(), WP_EDIT_PASS_VERSION, 'all');

        wp_enqueue_script('wpepp-admin', WP_EDIT_PASS_ASSETS . 'js/admin.js', array('jquery'), WP_EDIT_PASS_VERSION, true);
    }

    function customize_preview_assets()
    {
        wp_enqueue_style('wpepp-style-customize', WP_EDIT_PASS_ASSETS . 'css/wpepp-customizer.css', array(), WP_EDIT_PASS_VERSION, 'all');
    }




    /**
     * Initialize the plugin tracker
     *
     * @return void
     */
    function appsero_init_tracker_wp_edit_password_protected()
    {

        if (!class_exists('Appsero\Client')) {
            require_once __DIR__ . '/vendor/appsero/client/src/Client.php';
        }

        $client = new Appsero\Client('08132ef7-0f22-4c36-9ac4-0cad92ae19de', 'Wp Edit Password Protected', __FILE__);

        // Active insights
        $client->insights()->init();
    }


    function my_front_end_login_fail($username)
    {
        $referrer = $_SERVER['HTTP_REFERER'];  // where did the post submission come from?
        // if there's a valid referrer, and it's not the default log-in screen
        if (!empty($referrer) && !strstr($referrer, 'wp-login') && !strstr($referrer, 'wp-admin')) {
            wp_redirect($referrer . '?login=failed');  // let's append some information (login=failed) to the URL for the theme to use
            exit;
        }
    }
}
wpEditPasswordProtected::instance();
