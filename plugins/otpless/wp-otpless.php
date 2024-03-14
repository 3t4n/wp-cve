<?php
/**
 * @package OTPless
 * Plugin Name: OTPless
 * Description: OTPless enable wordpress website to verify user's mobile number without OTPs.
 * Version:     2.0.52
 * Author:      OTPless
 * Author URI:  https://otpless.com/
 * Text Domain: wp-otpless
 */

if (!defined('ABSPATH')) {
    // Exit if accessed directly.
    exit;
}

/**
 * Main Otpless Link Class
 *
 * The init class that runs the Otpless plugin.
 * Intended To make sure that the plugin's minimum requirements are met.
 */
class Otpless_Link
{

    /**
     * Plugin Version
     *
     * @since 2.0.39
     * @var string The plugin version.
     */
    const VERSION = '2.0.39';

    /**
     * Minimum PHP Version
     *
     * @since 7.3.0
     * @var string Minimum PHP version required to run the plugin.
     */
    const MINIMUM_PHP_VERSION = '7.3';

    /**
     * Constructor
     *
     * @since 0.0.0
     * @access public
     */
    public function __construct()
    {
        // Load the translation.
        add_action('init', array($this, 'i18n'));

        // Initialize the plugin.
        add_action('plugins_loaded', array($this, 'init'));

        // register_activation_hook(__FILE__, array($this, 'otpless_activation'));
        // register_activation_hook(__FILE__, array($this, 'otpless_activation'));

         // Register plugin deactivation hook.
        register_deactivation_hook( __FILE__,  array($this,'otpless_deactivation') );
    }

    function otpless_activation() {
        add_option('otpless_do_activation_redirect', true);
    }

    function otpless_redirect() {
        if (get_option('otpless_do_activation_redirect', false)) {
            delete_option('otpless_do_activation_redirect');

            $origin = filter_var($_SERVER['HTTP_HOST'], FILTER_SANITIZE_URL);
            $url = "https://".$origin."/wp-admin";
            wp_redirect("https://otpless.com/activate?redirect=".urlencode($url));
        }
    }


    function otpless_deactivation() {
        // Reset your plugin options here
        // For example, if you have stored options in the WordPress options table
        delete_option( 'otpless_option_name' );
    }

    /**
     * Load Textdomain
     *
     * Load plugin localization files.
     * Fired by `init` action hook.
     *
     * @since 0.0.0
     * @access public
     */
    public function i18n()
    {
        load_plugin_textdomain('Otpless-wp-plugin');
    }

    /**
     * Initialize the plugin
     *
     * Fired by `plugins_loaded` action hook.
     *
     * @since 0.0.0
     * @access public
     */
    public function init()
    {
        // Once we get here, We have passed all validation checks so we can safely include our widgets.
        require_once 'includes/class-admin.php';
        if (is_admin()) {
            new Otpless_Admin();
        }

        require_once 'vendor/autoload.php';
        require_once 'includes/class-login.php';
        new Otpless_Login();

        // add_action('admin_init', array($this, 'check_activation'));
        // add_action('admin_init', array($this, 'otpless_redirect'));
    }
}

// Instantiate Otpless Link.
new Otpless_Link();